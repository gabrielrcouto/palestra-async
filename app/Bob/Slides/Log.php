<?php
namespace App\Bob\Slides;

/**
 * Classe simples para Log no terminal
 */
class Log
{
    /**
     * Apenas echoa a mensagem e pula de linha
     *
     * @param string $message
     */
    public static function d($message)
    {
        echo $message . "\n";
    }
}
