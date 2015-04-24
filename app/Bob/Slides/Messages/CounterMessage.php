<?php
namespace App\Bob\Slides\Messages;

/**
 * Mensagem de questão
 */
class CounterMessage extends Message
{
    public $type = 'counter';
    public $number;

    public function __construct($number)
    {
        $this->number = $number;
    }
}