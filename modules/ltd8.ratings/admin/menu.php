<?php

use Bitrix\Main\Localization\Loc;

return array(
    array(
        'parent_menu' => 'global_menu_marketing',
        'sort' => 8,
        'text' => 'Оценки обращений',
        'title' => 'Оценки обращений',
        'items_id' => 'ltd8_ratings_menu_references',
        'items' => array(
            array(
                'text' => 'Оценки',
                'title' => 'Оценки',
                'url' => '/bitrix/admin/ltd8_ratings.php?page=main',
            ),
            array(
                'text' => 'Добавить критерий',
                'title' => 'Добавить критерий',
                'url' => '/bitrix/admin/ltd8_ratings.php?page=criterion_add',
            ),
            array(
                'text' => 'Критерии',
                'title' => 'Критерии',
                'url' => '/bitrix/admin/ltd8_ratings.php?page=criterion_list',
            ),
        ),
    ),
);