<?php

namespace Ltd8\Ratings\Admin;

use Bitrix\Main\Application;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Web\Uri;

class Table
{
    private bool $noEdit = false;
    private bool $noAdd = false;
    private ?array $list = null;
    private ?PageNavigation $navigation = null;
    private array $replaceData = [];
    private array $replaceHeaders = [];

    public function __construct(private string $tableClass)
    {

    }

    private function getReplaceData(): array
    {
        return $this->replaceData;
    }

    public function setReplaceData(array $replaceData): void
    {
        $this->replaceData = $replaceData;
    }

    private function getReplaceHeaders(): array
    {
        return $this->replaceHeaders;
    }

    public function setReplaceHeaders(array $replaceHeaders): void
    {
        $this->replaceHeaders = $replaceHeaders;
    }

    private function getNavigation(): ?PageNavigation
    {
        return $this->navigation;
    }

    private function setNavigation(?PageNavigation $navigation): void
    {
        $this->navigation = $navigation;
    }

    public function loadData(): void
    {
        $tableClass = $this->tableClass;
        $tableName = $tableClass::getTableName();

        $nav = new PageNavigation($tableName . "_nav");
        $nav->allowAllRecords(true)
            ->setPageSize(20)
            ->initFromUri();

        $list = $tableClass::getList([
            "filter" => [],
            "count_total" => true,
            "offset" => $nav->getOffset(),
            "limit" => $nav->getLimit(),
        ]);

        $listResult = $list->fetchAll();

        $this->setList([
            "instance" => $list,
            "list" => $listResult,
        ]);
        $this->setNavigation($nav);
    }

    public function getList(): ?array
    {
        return $this->list;
    }

    private function setList(?array $list): void
    {
        $this->list = $list;
    }

    private function isNoEdit(): bool
    {
        return $this->noEdit;
    }

    public function setNoEdit(bool $noEdit): void
    {
        $this->noEdit = $noEdit;
    }

    private function isNoAdd(): bool
    {
        return $this->noAdd;
    }

    public function setNoAdd(bool $noAdd): void
    {
        $this->noAdd = $noAdd;
    }

    private function build(array $list, object $lAdmin)
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $tableClass = $this->tableClass;
        $tableName = $tableClass::getTableName();
        $replaceHeaders = $this->getReplaceHeaders();
        $replaceData = $this->getReplaceData();

        foreach ($list as $item) {

            foreach ($replaceHeaders as $columnTitle => $newTitle) {
                if (isset($replaceData[$item["ID"]][$columnTitle])) {
                    $item[$columnTitle] = $replaceData[$item["ID"]][$columnTitle];
                }
            }

            $row = $lAdmin->AddRow($item["ID"], $item);
            $uri = new Uri($request->getRequestedPage());
            $listActions = [];
            if (!$this->noEdit) {
                $uri->addParams(["page" => $request->get("page"), $tableName . "_mode" => "edit", $tableName . "_id" => $item["ID"]]);
                $listActions[] = [
                    "ICON" => "edit",
                    "TEXT" => "Редактировать",
                    "LINK" => $uri->getUri(),
                ];
            }
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
        $replaceHeaders = $this->getReplaceHeaders();

        $lAdmin = new \CAdminList($tableName);

        $listHeaders = [];
        foreach ($tableClass::getMap() as $value) {
            $content = $value->getTitle();
            if (isset($replaceHeaders[$value->getName()])) {
                $content = $replaceHeaders[$value->getName()];
            }
            $listHeaders[] = ["id" => $value->getName(), "content" => $content, "default" => true];
        }
        $lAdmin->AddHeaders($listHeaders);

        $nav = $this->getNavigation();
        if ($nav === null) {
            $this->loadData();
            $nav = $this->getNavigation();
        }

        $listResult = $this->getList();
        if ($listResult === null) {
            $this->loadData();
            $listResult = $this->getList();
        }
        $list = $listResult["instance"];

        $nav->setRecordCount($list->getCount());

        $this->build($listResult["list"], $lAdmin);

        if (!$this->noAdd) {
            $uri = new Uri($request->getRequestedPage());
            $uri->addParams(["page" => $request->get("page"), $tableName . "_mode" => "edit"]);
            $lAdmin->AddAdminContextMenu([[
                "TEXT" => "Добавить",
                "LINK" => $uri->getUri(),
                "TITLE" => "Добавить новый элемент",
                "ICON" => "btn_new",
            ]], false, false);
        }

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