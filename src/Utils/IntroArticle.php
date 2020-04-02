<?php
namespace App\Utils;

class IntroArticle {

    // static => pas besoin d'instanciation
    public static function introArticle(string $content, int $limit = 100)
    {
        if(mb_strlen($content) <= $limit) {
            return $content;
        }
        $lastSpace = mb_strpos($content, ' ', $limit);
        return substr($content, 0, $lastSpace) . ' ...';
    } 
}