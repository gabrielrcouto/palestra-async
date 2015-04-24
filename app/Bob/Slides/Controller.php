<?php
namespace App\Bob\Slides;

use App\Bob\Slides\Messages\MessageManager;
use App\Bob\Slides\Messages\SlideMessage;
use App\Bob\Slides\Messages\PingMessage;
use App\Bob\Slides\Messages\PollMessage;
use App\Bob\Slides\Messages\PollResultMessage;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Controller para todas as mensagens da rota /slides
 */
class Controller implements MessageComponentInterface
{
    /**
     * Storage de conexões
     * @var SplObjectStorage de objetos ConnectionInterface
    */
    public static $connections;

    /**
     * Última mensagem de troca de slide recebida
     * @var SlideMessage
    */
    public static $lastMessage;

    /**
     * Array das enquetes
     * @var Array de Poll
    */
    public static $polls;

    public function __construct()
    {
        echo 'Controller construido' . PHP_EOL;

        self::$connections = new \SplObjectStorage;
        self::$polls = [];
    }

    /**
     * Função executada quando o servidor recebe uma nova conexão
     *
     * @param ConnectionInterface $connection
     */
    public function onOpen(ConnectionInterface $connection)
    {
        //O ID da sessão em MD5 virá através de um parâmetro $_GET da requisição HTTP
        //O código abaixo pega todos os parâmetros e monta em um array
        $queryExplode = explode('&', $connection->WebSocket->request->getQuery());
        $queryParams = [];

        foreach ($queryExplode as $queryParam) {
            $queryParamExplode = explode('=', $queryParam);
            $queryParamKey = $queryParamExplode[0];
            array_shift($queryParamExplode);
            $queryParamValue = implode($queryParamExplode);
            $queryParams[$queryParamKey] = $queryParamValue;
        }

        if (! array_key_exists('session', $queryParams)) {
            $connection->close();
            return;
        }

        if (! \Cache::has($queryParams['session'])) {
            $connection->close();
            return;
        }

        //Podemos injetar propriedades na conexão, será útil para usar em outras
        //funções
        $connection->session = $queryParams['session'];

        //Quando um usuário é conectado, armazenamos sua conexão em um
        //SplObjectStorage
        self::$connections->attach($connection);

        //Se já houve uma troca de slides, envia para o usuário ir até o slide
        //atual
        if (isset(Controller::$lastMessage)) {
            Sender::send(Controller::$lastMessage, $connection);
        }

        $cache = \Cache::get($connection->session);

        Log::d('Espectador ' . $cache['nickname'] . ' conectado: ' . $connection->resourceId);
        Log::d(count(self::$connections) . ' conectados');
    }

    /**
     * Função executada quando o servidor recebe uma mensagem
     *
     * @param ConnectionInterface $connection
     * @param String $jsonMessage
     */
    public function onMessage(ConnectionInterface $connection, $jsonMessage)
    {
        $cache = \Cache::get($connection->session);

        //Envia a mensagem para o MessageManager, ele identificará qual o tipo
        //dela
        $message = MessageManager::createMessage($jsonMessage, $connection);

        if ($message instanceof SlideMessage) {
            Controller::$lastMessage = $message;
        } else if ($message instanceof PollMessage) {
            //Se é uma mensagem de enquete, inicia a enquete e computa o voto
            if (!isset(self::$polls[$message->number])) {
                self::$polls[$message->number] = new Poll($message->number);
            }

            $poll = self::$polls[$message->number];
            $poll->addVote($message->value);

            $message = new PollResultMessage($poll);
        }

        if ($message instanceof PollResultMessage) {
            Log::d('Broadcast to everybody');

            //Broadcast para todos
            foreach (self::$connections as $anotherConnection) {
                Sender::send($message, $anotherConnection);
            }
        } else if ( $message instanceof PingMessage) {
            Log::d('Send back');
            Sender::send($message, $connection);
        } else {
            Log::d('Broadcast to others');
            //Broadcast para todos, menos para quem enviou
            foreach (self::$connections as $anotherConnection) {
                if ($anotherConnection !== $connection) {
                    Sender::send($message, $anotherConnection);
                }
            }
        }

        Log::d($jsonMessage . ' - ' . $cache['nickname'] . ' - ' . time());
    }

    /**
     * Função executada quando a conexão é encerrada
     *
     * @param ConnectionInterface $connection
     */
    public function onClose(ConnectionInterface $connection)
    {
        $cache = \Cache::get($connection->session);

        //A conexão foi encerrada, remove do storage
        self::$connections->detach($connection);

        Log::d('Conexão ' . $cache['nickname'] . ' encerrada: ' . $connection->resourceId);
        Log::d(count(self::$connections) . ' conectados');
    }

    /**
     * Função executada quando ocorre algum erro com a conexão
     *
     * @param ConnectionInterface $connection
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        $connection->close();

        Log::d('Ocorreu um erro: '. $e->getMessage());
    }
}