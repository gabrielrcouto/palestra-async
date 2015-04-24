<?php
namespace App\Bob\Slides\Messages;

/**
 * Mensagem de troca de slide
 */
class SlideMessage extends Message
{
    public $type = 'slide';
    public $indexh;
    public $indexv;
    public $indexf;
    public $nextindexh;
    public $nextindexv;
    protected $secret;
    protected $socketId;

    public function __construct($data, $connection)
    {
        parent::__construct($data, $connection);

        $this->indexh = $data->indexh;
        $this->indexv = $data->indexv;
        $this->indexf = $data->indexf;
        $this->nextindexh = $data->nextindexh;
        $this->nextindexv = $data->nextindexv;
        $this->secret = $data->secret;
        $this->socketId = $data->socketId;
    }
}