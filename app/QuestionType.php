<?php


namespace Lara;


abstract class QuestionType
{
    const FullText = 1;
    const YesNo = 2;
    const Custom = 3;

    static function asText($type)
    {
        switch ($type) {
            case QuestionType::FullText:
                return "Freitext";
            case QuestionType::YesNo:
                return "Checkbox";
            case QuestionType::Custom:
                return "Dropdown";
            case null:
                return null;
        }
        return "";
    }
}