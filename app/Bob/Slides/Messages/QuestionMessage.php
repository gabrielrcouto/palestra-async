<?php
namespace App\Bob\Slides\Messages;

/**
 * Mensagem de questão
 */
class QuestionMessage extends Message
{
    public $type = 'question';
    public $question;
    public $nickname;

    public function __construct($data, $connection)
    {
        parent::__construct($data, $connection);

        $cache = \Cache::get($connection->session);

        $this->question = $data->question;
        $this->nickname = $cache['nickname'];
    }
}