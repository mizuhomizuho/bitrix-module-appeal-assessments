<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Ltd8\Ratings\Admin\Edit;
use Ltd8\Ratings\Admin\Table;
use Ltd8\Ratings\CriterionTable;

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

$APPLICATION->SetTitle("Критерии");

Loader::includeModule($moduleId);

$request = Application::getInstance()->getContext()->getRequest();
$tableClass = CriterionTable::class;
$tableName = $tableClass::getTableName();

if ($request->get($tableName . "_mode") === "edit") {
    $table = new Edit($tableClass);
}
else {
    $table = new Table($tableClass, "criterion");
}

$table->echo();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
