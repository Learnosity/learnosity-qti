<?php

namespace LearnosityQti\Utils;

use LearnosityQti\Utils\General\StringHelper;

class MathChecker
{
    public static function checkForMath(array $question)
    {
        $questionString = json_encode($question);
        $hasMath = false;

        if (StringHelper::contains($questionString, '<m:math') || StringHelper::contains($questionString, '<math')) {
            $hasMath = true;
            array_walk_recursive($question, function (&$value) use ($question) {
                if (is_string($value) && StringHelper::contains($value, '<m:math') || StringHelper::contains($value, '<math')) {
                    // TODO: Just do regex because I cant figure out how to properly do it with HtmlDOM
                    // Gets rid of all namespace definitions
                    $value = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $value);
                    // Gets rid of all namespace references
                    $value = str_replace('<m:', '<', $value);
                    $value = str_replace('</m:', '</', $value);
                    // Remove annotation tags
                    $value = preg_replace("/<annotation\b[^>]*>(.*?)<\/annotation>/", "", $value, -1, $count);
                    return $value;
                }
            });
        }

        // If has math
        if ($hasMath == true) {
            $question['data']['is_math'] = true;
        }

        return $question;
    }
}
