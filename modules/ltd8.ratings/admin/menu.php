<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

global $APPLICATION;
global $USER;

$moduleId = "ltd8.ratings";

$items = [];

if ($APPLICATION->GetGroupRight($moduleId) > "D") {
    $items[] = [
        "text" => Loc::getMessage("LTD8_RATINGS_MENU_ITEM_1_TEXT"),
        "title" => Loc::getMessage("LTD8_RATINGS_MENU_ITEM_1_TITLE"),
        "url" => "/bitrix/admin/ltd8_ratings.php?page=ratings&lang=" . LANG,
    ];
    $items[] = [
        "text" => Loc::getMessage("LTD8_RATINGS_MENU_ITEM_2_TEXT"),
        "title" => Loc::getMessage("LTD8_RATINGS_MENU_ITEM_2_TITLE"),
        "url" => "/bitrix/admin/ltd8_ratings.php?page=criterion&lang=" . LANG,
    ];
}

if ($USER->IsAdmin()) {
    $items[] = [
        "text" => Loc::getMessage("LTD8_RATINGS_MENU_ITEM_3_TEXT"),
        "title" => Loc::getMessage("LTD8_RATINGS_MENU_ITEM_3_TITLE"),
        "url" => "/bitrix/admin/settings.php?mid=ltd8.ratings&lang=" . LANG,
    ];
}

if (!$items) {
    return [];
}

return [[
    "parent_menu" => "global_menu_marketing",
    "sort" => 8,
    "text" => Loc::getMessage("LTD8_RATINGS_MENU_MAIN_TEXT"),
    "title" => Loc::getMessage("LTD8_RATINGS_MENU_MAIN_TITLE"),
    "items_id" => "module_" . md5($moduleId) . "_references",
    "items" => $items,
]];