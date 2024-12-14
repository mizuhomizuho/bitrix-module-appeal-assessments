<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Ltd8\Ratings\Admin\CriterionEdit;
use Ltd8\Ratings\Admin\Table;
use Ltd8\Ratings\CriterionTable;

global $APPLICATION;

if (!defined("LTD8_RATINGS_ADMIN")) {
    exit;
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

const LTD8_RATINGS_MODULE_ID = "ltd8.ratings";

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
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
    $edit = new CriterionEdit($tableClass);
    $edit->echo();
}
else {
    $lAdmin = new \CAdminUiList($tableName);
    $table = new Table($tableClass, $lAdmin);
    $table->build();
    $lAdmin->CheckListMode();
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
    $table->echoTable();
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
