<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Ltd8\Ratings\Admin\Edit;
use Ltd8\Ratings\Admin\Ratings;
use Ltd8\Ratings\Admin\Table;
use Ltd8\Ratings\DataTable;

if (!defined("LTD8_RATINGS_ADMIN")) {
    exit;
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

global $APPLICATION;

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

$adminRatings = new Ratings();
$params = $adminRatings->getQueryParams();

$table = new Table($tableClass);
$table->setHeaders($params["headers"]);
$table->setQuery($params["query"]);
$table->setNoAdd(true);
$table->setNoEdit(true);
$table->echo();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
