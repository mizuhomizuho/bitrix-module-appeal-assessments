<?php

namespace Ltd8\Ratings\Admin;

use Bitrix\Main\Application;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Web\Uri;

class Table
{
    public function __construct(private string $tableClass, private string $page)
    {

    }

    private function build(object $list, object $lAdmin)
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $tableClass = $this->tableClass;
        $tableName = $tableClass::getTableName();

        while ($item = $list->fetch()) {
            $row = $lAdmin->AddRow($item["ID"], $item);
            $uri = new Uri($request->getRequestedPage());
            $listActions = [];
            $uri->addParams(["page" => $this->page, $tableName . "_mode" => "edit", $tableName . "_id" => $item["ID"]]);
            $listActions[] = [
                "ICON" => "edit",
                "TEXT" => "Редактировать",
                "LINK" => $uri->getUri(),
            ];
            $uri->addParams([$tableName . "_mode" => "delete"]);
            $listActions[] = [
                "ICON" => "delete",
                "TEXT" => "Удалить",
                "LINK" => $uri->getUri(),
            ];
            $row->AddActions($listActions);
        }
    }

    private function change()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $tableClass = $this->tableClass;
        $tableName = $tableClass::getTableName();

        if ($request->get($tableName . "_mode") === "delete") {
            $tableClass::delete((int)$request->get($tableName . "_id"));
            \CAdminMessage::ShowNote("Успешно удалено");
        }
    }

    public function echo()
    {
        global $APPLICATION;

        $this->change();

        $request = Application::getInstance()->getContext()->getRequest();
        $tableClass = $this->tableClass;
        $tableName = $tableClass::getTableName();

        $lAdmin = new \CAdminList($tableName);

        $listHeaders = [];
        foreach ($tableClass::getMap() as $value) {
            $listHeaders[] = ["id" => $value->getName(), "content" => $value->getTitle(), "default" => true];
        }
        $lAdmin->AddHeaders($listHeaders);

        $nav = new PageNavigation($tableName . "_nav");
        $nav->allowAllRecords(true)
            ->setPageSize(20)
            ->initFromUri();

        $list = $tableClass::getList(
            array(
                "filter" => [],
                "count_total" => true,
                "offset" => $nav->getOffset(),
                "limit" => $nav->getLimit(),
            )
        );

        $nav->setRecordCount($list->getCount());

        $this->build($list, $lAdmin);

        $uri = new Uri($request->getRequestedPage());
        $uri->addParams(["page" => $this->page, $tableName . "_mode" => "edit"]);
        $lAdmin->AddAdminContextMenu([[
            "TEXT" => "Добавить",
            "LINK" => $uri->getUri(),
            "TITLE" => "Добавить новый элемент",
            "ICON" => "btn_new",
        ]]);

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

    }
}