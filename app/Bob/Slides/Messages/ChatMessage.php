<?php
namespace App\Bob\Slides\Messages;

/**
 * Mensagem de questão
 */
class ChatMessage extends Message
{
    public $type = 'chat';
    public $message;
    public $nickname;

    public function __construct($data, $connection)
    {
        parent::__construct($data, $connection);

        $cache = \Cache::get($connection->session);

        $this->message = strip_tags($data->message);
        $this->nickname = $cache['nickname'];
    }
}