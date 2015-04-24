<?php
namespace App\Bob\Slides\Messages;

/**
 * Gerenciador de mensagens, capaz de identificar o tipo da mensagem com base
 * nos seus campos
 */
class MessageManager
{
    /**
     * Cria um objeto que extende Message com base no tipo da mensagem
     *
     * @param string $json JSON recebido
     * @param ConnectionInterface $connection
     * @return Message ou classe que extende Message
     */
    public static function createMessage($json, $connection)
    {
        $obj = json_decode($json);

        if ($obj->type == 'horn') {
            return new HornMessage($obj, $connection);
        } else if ($obj->type == 'slide') {
            return new SlideMessage($obj, $connection);
        } else if ($obj->type == 'poll') {
            return new PollMessage($obj, $connection);
        } else if ($obj->type == 'ping') {
            return new PingMessage($obj, $connection);
        } else if ($obj->type == 'question') {
            return new QuestionMessage($obj, $connection);
        } else {
            return new Message($obj, $connection);
        }
    }
}