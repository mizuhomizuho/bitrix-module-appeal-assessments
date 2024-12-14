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
    private array $filter = [];

    public function __construct(private string $tableClass, private \CAdminList $cAdminList)
    {

    }

    private function getTableClass(): string
    {
        return $this->tableClass;
    }

    private function getCAdminList(): \CAdminList
    {
        return $this->cAdminList;
    }

    private function getFilter(): array
    {
        return $this->filter;
    }

    public function setFilter(array $filter): void
    {
        $this->filter = $filter;
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

    private function getNavigation(): PageNavigation
    {
        if ($this->navigation === null) {
            $tableClass = $this->getTableClass();
            $tableName = $tableClass::getTableName();
            $nav = new PageNavigation($tableName . "_nav");
            $nav->allowAllRecords(true)
                ->setPageSize(20)
                ->initFromUri();
            $this->setNavigation($nav);
        }

        return $this->navigation;
    }

    private function setNavigation(PageNavigation $navigation): void
    {
        $this->navigation = $navigation;
    }

    private function loadData(): array
    {
        $tableClass = $this->getTableClass();

        $this->change();

        $nav = $this->getNavigation();

        $query = $this->getQuery();

        if ($query === null) {
            $listInstance = $tableClass::getList([
                "filter" => $this->getFilter(),
                "count_total" => true,
                "offset" => $nav->getOffset(),
                "limit" => $nav->getLimit(),
            ]);
            $countAll = $listInstance->getCount();
        } else {
            foreach ($this->getFilter() as $value) {
                $query->where($value);
            }
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

        return [
            "instance" => $listInstance,
            "countAll" => $countAll,
            "list" => $listResult,
        ];
    }

    public function getList(): ?array
    {
        if ($this->list === null) {
            $this->setList($this->loadData());
        }

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

    public function build()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $tableClass = $this->getTableClass();
        $tableName = $tableClass::getTableName();
        $lAdmin = $this->getCAdminList();

        $list = $this->getList()["list"];

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

        $listHeaders = [];
        if ($this->getHeaders() === null) {
            foreach ($tableClass::getMap() as $value) {
                $listHeaders[] = ["id" => $value->getName(), "content" => $value->getTitle(), "default" => true];
            }
        } else {
            foreach ($this->getHeaders() as $headerKey => $headerValue) {
                $listHeaders[] = ["id" => $headerKey, "content" => $headerValue, "default" => true];
            }
        }
        $lAdmin->AddHeaders($listHeaders);

        $nav = $this->getNavigation();
        $nav->setRecordCount($this->getList()["countAll"]);

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

        $lAdmin->SetNavigation($nav, "Элементы");
    }

    private function change()
    {
        if (!$this->canWrite()) {
            return;
        }

        $request = Application::getInstance()->getContext()->getRequest();
        $tableClass = $this->getTableClass();
        $tableName = $tableClass::getTableName();

        if ($request->get($tableName . "_mode") === "delete") {
            $tableClass::delete((int)$request->get($tableName . "_id"));
            \CAdminMessage::ShowNote("Успешно удалено");
        }
    }
}