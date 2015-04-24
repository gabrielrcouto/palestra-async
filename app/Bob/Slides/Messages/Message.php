<?php
namespace App\Bob\Slides\Messages;

/**
 * Mensagem padrão
 */
class Message
{
    /**
     * @var ConnectionInterface
    */
    protected $connection;

    /**
     * @var Objeto unserialized do JSON
    */
    protected $data;

    public function __construct($data, $connection)
    {
        $this->connection = $connection;
        $this->data = $data;
    }
}