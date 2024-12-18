<?php

namespace Ltd8\Ratings;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;

class CriterionTable extends DataManager
{
    public static function getTableName()
    {
        return "ltd8_ratings_criterion";
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                "ID",
                [
                    "primary" => true,
                    "autocomplete" => true,
                    "title" => Loc::getMessage("LTD8_RATINGS_LIB_CRITERION_TABLE_TITLE_ID"),
                ]
            ),
            new StringField(
                "NAME",
                [
                    "required" => true,
                    "validation" => function () {
                        return [
                            new LengthValidator(null, 255),
                        ];
                    },
                    "title" => Loc::getMessage("LTD8_RATINGS_LIB_CRITERION_TABLE_TITLE_NAME"),
                ]
            ),
        ];
    }
}