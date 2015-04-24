<?php
namespace App\Bob\Slides\Messages;

/**
 * Mensagem de resultado de enquete
 */
class PollResultMessage extends Message
{
    public $type = 'poll-result';
    public $number;
    public $votes;
    public $percentages;

    public function __construct($poll)
    {
        $this->number = $poll->number;
        $this->votes = $poll->getVotes();
        $this->percentages = $poll->getPercentages();
    }
}