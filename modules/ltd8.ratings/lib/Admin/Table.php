<?php

namespace Ltd8\Ratings\Admin;

use Bitrix\Main\Application;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Web\Uri;

class Table extends Base
{
    private bool $noEdit = false;
    private bool $noAdd = false;
    private ?array $list = null;
    private ?PageNavigation $navigation = null;
    private ?array $headers = null;
    private ?object $query = null;

    public function __construct(private string $tableClass)
    {

    }

    private function getQuery(): ?object
    {
        return $this->query;
    }

    public function setQuery(object $query): void
    {
        $this->query = $query;
    }

    private function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    private function getNavigation(): ?PageNavigation
    {
        return $this->navigation;
    }

    private function setNavigation(PageNavigation $navigation): void
    {
        $this->navigation = $navigation;
    }

    private function loadData(): void
    {
        $tableClass = $this->tableClass;
        $tableName = $tableClass::getTableName();

        $this->change();

        $nav = new PageNavigation($tableName . "_nav");
        $nav->allowAllRecords(true)
            ->setPageSize(20)
            ->initFromUri();

        $query = $this->getQuery();

        if ($query === null) {
            $listInstance = $tableClass::getList([
                "filter" => [],
                "count_total" => true,
                "offset" => $nav->getOffset(),
                "limit" => $nav->getLimit(),
            ]);
            $countAll = $listInstance->getCount();
        } else {
            $countAllQuery = clone $query;
            $countAllQuery->setSelect(["COUNT_ALL"]);
            $countAllQuery->registerRuntimeField(
                "",
                new ExpressionField(
                    "COUNT_ALL",
                    "COUNT(*)",
                )
            );
            $countAll = $countAllQuery->fetch()['COUNT_ALL'];
            $listInstance = $query
                ->setOffset($nav->getOffset())
                ->setLimit($nav->getLimit());
        }


        $listResult = $listInstance->fetchAll();

        $this->setList([
            "countAll" => $countAll,
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

        foreach ($list as $item) {
            $row = $lAdmin->AddRow($item["ID"], $item);
            $uri = new Uri($request->getRequestedPage());
            $listActions = [];
            $uri->addParams(["page" => $request->get("page"), $tableName . "_id" => $item["ID"]]);
            if ($this->canWrite()) {
                if (!$this->isNoEdit()) {
                    $uri->addParams([$tableName . "_mode" => "edit"]);
                    $listActions[] = [
                        "ICON" => "edit",
                        "TEXT" => "Редактировать",
                        "LINK" => $uri->getUri(),
                    ];
                }
                $uri->addParams([
                    $tableName . "_mode" => "delete",
                    $tableName . "_nav" => $request->get($tableName . "_nav"),
                ]);
                $listActions[] = [
                    "ICON" => "delete",
                    "TEXT" => "Удалить",
                    "LINK" => $uri->getUri(),
                ];
            }
            $row->AddActions($listActions);
        }
    }

    private function change()
    {
        if (!$this->canWrite()) {
            return;
        }

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

        $request = Application::getInstance()->getContext()->getRequest();
        $tableClass = $this->tableClass;
        $tableName = $tableClass::getTableName();

        $lAdmin = new \CAdminList($tableName);

        $listHeaders = [];

        if (is_array($this->getHeaders())) {
            foreach ($this->getHeaders() as $headerKey => $headerValue) {
                $listHeaders[] = ["id" => $headerKey, "content" => $headerValue, "default" => true];
            }
        } else {
            foreach ($tableClass::getMap() as $value) {
                $listHeaders[] = ["id" => $value->getName(), "content" => $value->getTitle(), "default" => true];
            }
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

        $nav->setRecordCount($listResult["countAll"]);

        $this->build($listResult["list"], $lAdmin);

        if (!$this->isNoAdd() && $this->canWrite()) {
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