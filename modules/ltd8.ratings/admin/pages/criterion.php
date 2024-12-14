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

global $APPLICATION;

$APPLICATION->SetTitle("Критерии");

if ($APPLICATION->GetGroupRight(LTD8_RATINGS_MODULE_ID) < "R") {
    \CAdminMessage::ShowNote("Доступ запрещен");
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
    return;
}

Loader::includeModule(LTD8_RATINGS_MODULE_ID);

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
