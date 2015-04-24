<?php
namespace App\Bob\Slides\Messages;

/**
 * Mensagem de buzina
 */
class HornMessage extends Message
{
    public $type = 'horn';

    public function __construct($data, $connection)
    {
        parent::__construct($data, $connection);
    }
}