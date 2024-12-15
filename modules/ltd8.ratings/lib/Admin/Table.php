<?php

namespace Ltd8\Ratings\Admin;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
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
    private ?array $select = null;
    private ?array $runtime = null;
    private array $filter = [];

    public function __construct(private string $tableClass, private \CAdminUiList $cAdminList)
    {

    }

    private function getRuntime(): ?array
    {
        return $this->runtime;
    }

    public function setRuntime(array $runtime): void
    {
        $this->runtime = $runtime;
    }

    private function getSelect(): ?array
    {
        return $this->select;
    }

    public function setSelect(array $select): void
    {
        $this->select = $select;
    }

    private function getTableClass(): string
    {
        return $this->tableClass;
    }

    private function getCAdminList(): \CAdminUiList
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
                ->setPageSize($this->getCAdminList()->getNavSize())
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

        $params = [
            "filter" => $this->getFilter(),
            "count_total" => true,
            "offset" => $nav->getOffset(),
            "limit" => $nav->getLimit(),
        ];

        if ($this->getSelect() !== null) {
            $params["select"] = $this->getSelect();
        }

        if ($this->getRuntime() !== null) {
            $params["runtime"] = $this->getRuntime();
        }

        $listInstance = $tableClass::getList($params);

        return [
            "instance" => $listInstance,
            "countAll" => $listInstance->getCount(),
            "list" => $listInstance->fetchAll(),
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

        $lAdmin->AddGroupActionTable([
            "delete" => Loc::getMessage("LTD8_RATINGS_LIB_ADMIN_TABLE_BTN_DELETE"),
        ]);

        $list = $this->getList()["list"];

        foreach ($list as $item) {
            $row = $lAdmin->AddRow($item["ID"], $item);
            $uri = new Uri($request->getRequestedPage());
            $listActions = [];
            $uri->addParams([
                "page" => $request->get("page"),
                $tableName . "_id" => $item["ID"],
                "lang" => LANG,
            ]);
            if ($this->canWrite()) {
                if (!$this->isNoEdit()) {
                    $uri->addParams([$tableName . "_mode" => "edit"]);
                    $listActions[] = [
                        "ICON" => "edit",
                        "TEXT" => Loc::getMessage("LTD8_RATINGS_LIB_ADMIN_TABLE_BTN_EDIT"),
                        "LINK" => $uri->getUri(),
                    ];
                }
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
            $uri->addParams([
                "page" => $request->get("page"),
                $tableName . "_mode" => "edit",
                "lang" => LANG,
            ]);
            $lAdmin->AddAdminContextMenu([[
                "TEXT" => Loc::getMessage("LTD8_RATINGS_LIB_ADMIN_TABLE_BTN_ADD_TEXT"),
                "LINK" => $uri->getUri(),
                "TITLE" => Loc::getMessage("LTD8_RATINGS_LIB_ADMIN_TABLE_BTN_ADD_TITLE"),
                "ICON" => "btn_new",
            ]], false, false);
        }

        $lAdmin->SetNavigation($nav, Loc::getMessage("LTD8_RATINGS_LIB_ADMIN_TABLE_NAV_ELEMENT_TITLE"));
    }

    private function change()
    {
        if (!$this->canWrite()) {
            return;
        }

        $request = Application::getInstance()->getContext()->getRequest();
        $tableClass = $this->getTableClass();
        $tableName = $tableClass::getTableName();

        if ($request->get("action_button_$tableName") === "delete" && is_array($request->get("ID"))) {
            foreach ($request->get("ID") as $id) {
                $tableClass::delete((int)$id);
            }
        }
    }

    public function echoTable()
    {
        $this->getCAdminList()->DisplayList();
    }
}