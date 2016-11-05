<?php


namespace Lara;


/**
 * Class QuestionType
 * abstract class representing an enum of QuestionTypes for Surveys
 * @package Lara
 */
abstract class QuestionType
{
    const Text = 1;
    const Checkbox = 2;
    const Dropdown = 3;

    static function asText($type)
    {
        switch ($type) {
            case QuestionType::Text:
                return "Freitext";
            case QuestionType::Checkbox:
                return "Checkbox";
            case QuestionType::Dropdown:
                return "Dropdown";
            case null:
                return null;
        }
        return "";
    }
}