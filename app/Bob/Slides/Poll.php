<?php
namespace App\Bob\Slides;

/**
 * Classe para as enquetes presentes nos slides
 */
class Poll
{
    /**
     * Número da enquete
     * @var int
    */
    public $number;

    /**
     * Votos ['value' => quantity]
     * @var Array
    */
    protected $votes = [];

    /**
     * Porcentagens ['value' => percent]
     * @var Array
    */
    protected $percentages = [];

    /**
     * Número total de votos
     * @var int
    */
    protected $total = 0;

    public function __construct($number)
    {
        $this->number = $number;
    }

    /**
     * Adiciona um voto na enquete
     *
     * @param object $value
     */
    public function addVote($value)
    {
        if (!array_key_exists($value, $this->votes)) {
            $this->votes[$value] = 0;
        }

        $this->votes[$value]++;
        $this->total++;

        $this->calculatePercentages();
    }

    /**
     * Cálcula as porcentanges de votos
     */
    public function calculatePercentages()
    {
        foreach ($this->votes as $key => $quantity) {
            $this->percentages[$key] = ceil(($quantity / $this->total) * 100);
        }
    }

    /**
     * Retorna as porcentagens de votos
     * @return Array Porcentagens ['value' => percent]
     */
    public function getPercentages()
    {
        return $this->percentages;
    }

    /**
     * Retorna os votos
     * @return Array Votos ['value' => quantity]
     */
    public function getVotes()
    {
        return $this->votes;
    }
}