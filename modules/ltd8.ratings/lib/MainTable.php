<?php

namespace Ltd8\Ratings;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;

class MainTable extends DataManager
{
    public static function getTableName()
    {
        return "ltd8_ratings_main";
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                "ID",
                [
                    "primary" => true,
                    "autocomplete" => true,
                    "title" => "Id",
                ]
            ),
            new IntegerField(
                "REQUEST_NUMBER",
                [
                    "required" => true,
                    "title" => "Case number",
                ]
            ),
        ];
    }
}