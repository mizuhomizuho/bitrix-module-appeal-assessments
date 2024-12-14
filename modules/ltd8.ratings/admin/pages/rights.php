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

$moduleId = "ltd8.ratings";

global $APPLICATION;
if ($APPLICATION->GetGroupRight($moduleId) < "R") {
    $APPLICATION->AuthForm("Доступ запрещен");
}

$APPLICATION->SetTitle("Оценки");

Loader::includeModule($moduleId);

$request = Application::getInstance()->getContext()->getRequest();
$tableClass = DataTable::class;
$tableName = $tableClass::getTableName();

$table = new Table($tableClass);
$table->loadData();
$list = $table->getList();

$adminRatings = new Ratings();
$replaceParams = $adminRatings->getReplaceParams($list["list"]);
$table->setReplaceData($replaceParams["data"]);
$table->setReplaceHeaders($replaceParams["headers"]);

$table->setNoAdd(true);
$table->setNoEdit(true);
$table->echo();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
