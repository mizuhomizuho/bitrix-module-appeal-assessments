<?php

namespace Ltd8\Ratings;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;

class DataTable extends DataManager
{
    public static function getTableName()
    {
        return "ltd8_ratings_data";
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                "ID",
                [
                    "primary" => true,
                    "autocomplete" => true,
                    "title" => "ID",
                ]
            ),
            new IntegerField(
                "MAIN_ID",
                [
                    "required" => true,
                    "title" => "MAIN_ID",
                ]
            ),
            new IntegerField(
                "CRITERION_ID",
                [
                    "required" => true,
                    "title" => "CRITERION_ID",
                ]
            ),
            new IntegerField(
                "STARS",
                [
                    "required" => true,
                    "title" => "Количество звезд",
                ]
            ),
        ];
    }
}