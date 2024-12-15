<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
    "NAME" => Loc::getMessage("LTD8_RATINGS_COMPONENTS_DESCRIPTION_NAME"),
    "DESCRIPTION" => Loc::getMessage("LTD8_RATINGS_COMPONENTS_DESCRIPTION_TEXT"),
    "SORT" => 8,
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "content",
        "CHILD" => array(
            "ID" => "ltd8_ratings",
            "NAME" => Loc::getMessage("LTD8_RATINGS_COMPONENTS_DESCRIPTION_CHILD"),
            "SORT" => 8,
            "CHILD" => array(
                "ID" => "ltd8_ratings_cmpx",
            ),
        ),
    ),
);
