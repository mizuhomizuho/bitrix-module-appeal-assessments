<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;
global $USER;

$moduleId = "ltd8.ratings";

$items = [];

if ($APPLICATION->GetGroupRight($moduleId) > "D") {
    $items[] = [
        "text" => "Оценки",
        "title" => "Оценки",
        "url" => "/bitrix/admin/ltd8_ratings.php?page=ratings",
    ];
    $items[] = [
        "text" => "Критерии",
        "title" => "Критерии",
        "url" => "/bitrix/admin/ltd8_ratings.php?page=criterion",
    ];
}

if ($USER->IsAdmin()) {
    $items[] = [
        "text" => "Права доступа",
        "title" => "Права доступа",
        "url" => "/bitrix/admin/settings.php?mid=ltd8.ratings",
    ];
}

if (!$items) {
    return [];
}

return [[
    "parent_menu" => "global_menu_marketing",
    "sort" => 8,
    "text" => "Оценки обращений",
    "title" => "Оценки обращений",
    "items_id" => "module_" . md5($moduleId) . "_references",
    "items" => $items,
]];