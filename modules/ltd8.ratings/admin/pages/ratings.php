<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Ltd8\Ratings\Admin\Ratings;
use Ltd8\Ratings\Admin\Table;
use Ltd8\Ratings\DataTable;

global $APPLICATION;

if (!defined("LTD8_RATINGS_ADMIN")) {
    exit;
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

const LTD8_RATINGS_MODULE_ID = "ltd8.ratings";

$APPLICATION->SetTitle("Оценки");

if ($APPLICATION->GetGroupRight(LTD8_RATINGS_MODULE_ID) < "R") {
    \CAdminMessage::ShowNote("Доступ запрещен");
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
    return;
}

Loader::includeModule(LTD8_RATINGS_MODULE_ID);

$request = Application::getInstance()->getContext()->getRequest();
$tableClass = DataTable::class;
$tableName = $tableClass::getTableName();

$lAdmin = new \CAdminList($tableName);

$adminRatings = new Ratings();
$adminRatings->initFilter($lAdmin);
$params = $adminRatings->getQueryParams();

$table = new Table($tableClass, $lAdmin);
$table->setFilter($adminRatings->getFilter());
$table->setHeaders($params["headers"]);
$table->setQuery($params["query"]);
$table->build();

$lAdmin->CheckListMode();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$adminRatings->echoFilter($tableName);
$table->setNoAdd(true);
$table->setNoEdit(true);
$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
