<?php
namespace App\Bob\Slides;

use Slides\Messages\Message;

/**
 * Enviador de mensagens
 */
class Sender
{
    /**
     * Envia a mensagem para uma conexão
     *
     * @param Message $message
     * @param ConnectionInterface $connection
     */
    public static function send($message, $connection)
    {
        if (!is_string($message)) {
            $message = json_encode($message);
        }

        $connection->send($message);
    }
}