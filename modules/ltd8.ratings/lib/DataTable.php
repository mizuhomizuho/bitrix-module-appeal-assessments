<?php

namespace Ltd8\Ratings;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;

class DataTable extends DataManager
{
    public static function getTableName()
    {
        return 'ltd8_ratings_data';
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => 'Id',
                ]
            ),
            new IntegerField(
                'MAIN_ID',
                [
                    'required' => true,
                    'title' => 'Main id',
                ]
            ),
            new IntegerField(
                'INTERACTION_WITH_THE_OPERATOR',
                [
                    'required' => true,
                    'title' => 'Interaction with the operator',
                ]
            ),
            new IntegerField(
                'POLITENESS',
                [
                    'required' => true,
                    'title' => 'Politeness',
                ]
            ),
            new IntegerField(
                'SPEED_AND_ACCURACY_OF_RESPONSES',
                [
                    'required' => true,
                    'title' => 'Speed and accuracy of responses',
                ]
            ),
        ];
    }
}