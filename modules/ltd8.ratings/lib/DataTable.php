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
                    "title" => Loc::getMessage("LTD8_RATINGS_LIB_DATA_TABLE_TITLE_ID"),
                ]
            ),
            new IntegerField(
                "MAIN_ID",
                [
                    "required" => true,
                    "title" => Loc::getMessage("LTD8_RATINGS_LIB_DATA_TABLE_TITLE_MAIN_ID"),
                ]
            ),
            new IntegerField(
                "CRITERION_ID",
                [
                    "required" => true,
                    "title" => Loc::getMessage("LTD8_RATINGS_LIB_DATA_TABLE_TITLE_CRITERION_ID"),
                ]
            ),
            new IntegerField(
                "STARS",
                [
                    "required" => true,
                    "title" => Loc::getMessage("LTD8_RATINGS_LIB_DATA_TABLE_TITLE_STARS"),
                ]
            ),
        ];
    }
}