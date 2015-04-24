<?php
namespace App\Bob\Slides\Messages;

/**
 * Mensagem de votação de enquete
 */
class PollMessage extends Message
{
    public $type = 'poll';
    public $number;
    public $value;

    public function __construct($data, $connection)
    {
        parent::__construct($data, $connection);

        $this->number = $data->number;
        $this->value = $data->value;
    }
}