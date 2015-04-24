<?php

namespace App\Bob\String;

use \URLify;

class Slug {
    public static function generate($text)
    {
        $text = trim($text);
        $text = str_replace(' ', '-', $text);
        $text = URLify::filter($text);
        $text = preg_replace('/[^a-zA-Z0-9_-]/s', '', $text);

        return $text;
    }
}