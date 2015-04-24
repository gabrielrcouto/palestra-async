<?php
namespace App\Bob\Slides\Messages;

/**
 * Mensagem de ping
 */
class PingMessage extends Message
{
    public $type = 'ping';
    public $time;

    public function __construct($data, $connection)
    {
        parent::__construct($data, $connection);

        $this->time = $data->time;
    }
}