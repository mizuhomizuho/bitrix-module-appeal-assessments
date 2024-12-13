<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Ltd8\Ratings\CriterionTable;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

global $APPLICATION;
$moduleId = "ltd8.ratings";
if ($APPLICATION->GetGroupRight($moduleId) < "R") {
    $APPLICATION->AuthForm("Доступ запрещен");
}

$APPLICATION->SetTitle("Критерии");

Loader::includeModule('ltd8.ratings');

$request = Application::getInstance()->getContext()->getRequest();

if ($request->get("delete_id") !== null) {
    CriterionTable::delete((int) $request->get("delete_id"));
    CAdminMessage::ShowNote("Критерий удален");
}

$lAdmin = new CAdminList(CriterionTable::getTableName());
$lAdmin->AddHeaders([
    ['id' => 'ID',    'content' => 'ИД', 'default' => true],
    ['id' => 'NAME', 'content' => 'Имя', 'default' => true],
    ['id' => 'BTN', 'content' => '', 'default' => true],
]);

$nav = new \Bitrix\Main\UI\PageNavigation("ltd8-ratings-nav");
$nav->allowAllRecords(true)
    ->setPageSize(1)
    ->initFromUri();

$list = CriterionTable::getList(
    array(
        "filter" => [],
        "count_total" => true,
        "offset" => $nav->getOffset(),
        "limit" => $nav->getLimit(),
    )
);

$nav->setRecordCount($list->getCount());

while ($item = $list->fetch()) {
    $row = $lAdmin->AddRow($item['ID'], $item);
    $uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());
    $uri->addParams(array("delete_id" => $item['ID']));
    $row->AddViewField('BTN', '<a href="' . $uri->getUri() . '">Удалить</a>');
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$lAdmin->DisplayList();

$APPLICATION->IncludeComponent(
    "bitrix:main.pagenavigation",
    "",
    array(
        "NAV_OBJECT" => $nav,
        "SEF_MODE" => "N",
    ),
    false
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
