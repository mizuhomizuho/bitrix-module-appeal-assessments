<?php

namespace Ltd8\Ratings\Admin;

use Bitrix\Main\Application;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Text\HtmlFilter;
use Bitrix\Main\UI\Filter\Options;
use Ltd8\Ratings\CriterionTable;
use Ltd8\Ratings\DataTable;
use Ltd8\Ratings\MainTable;

class Ratings
{
    private array $filter = [];

    public function getFilter(): array
    {
        return $this->filter;
    }

    private function setFilter(array $filter): void
    {
        $this->filter = $filter;
    }

    public function initFilter($tableName): void
    {
        $filter = [];
        $filterOption = new Options($tableName);
        $filterData = $filterOption->getFilter();
        foreach ($filterData as $k => $v) {
            $filter[$k] = $v;
        }

        $arFilter = [];
        if (
            isset($filter["findRequestNumber_numsel"],
            $filter["findRequestNumber_from"],
            $filter["findRequestNumber_to"])
        ) {
            if ($filter["findRequestNumber_numsel"] === "less") {
                $arFilter["<MAIN.REQUEST_NUMBER"] = $filter["findRequestNumber_to"];
            }
            elseif ($filter["findRequestNumber_numsel"] === "more") {
                $arFilter[">MAIN.REQUEST_NUMBER"] = $filter["findRequestNumber_from"];
            }
            elseif ($filter["findRequestNumber_numsel"] === "range") {
                $arFilter["<MAIN.REQUEST_NUMBER"] = $filter["findRequestNumber_to"];
                $arFilter[">MAIN.REQUEST_NUMBER"] = $filter["findRequestNumber_from"];
            }
            elseif ($filter["findRequestNumber_numsel"] === "exact") {
                $arFilter["=MAIN.REQUEST_NUMBER"] = $filter["findRequestNumber_to"];
            }
        }
        $this->setFilter($arFilter);
    }

    public function echoFilter($tableName): void
    {
        global $APPLICATION;

        $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
            'FILTER_ID' => $tableName,
            'GRID_ID' => $tableName,
            'FILTER' => [
                [
                    "id" => "findRequestNumber",
                    "name" => "Номер обращения",
                    'type' => 'number',
                    "default" => true,
                ],
            ],
            'ENABLE_LIVE_SEARCH' => true,
            'ENABLE_LABEL' => true
        ]);
    }

    public function getQueryParams(): array
    {
        return [
            "headers" => [
                "ID" => "ID",
                "REQUEST_NUMBER" => "Номер обращения",
                "CRITERION_NAME" => "Критерий",
                "STARS" => "Количество звезд",
            ],
            "select" => [
                "ID",
                "REQUEST_NUMBER" => "MAIN.REQUEST_NUMBER",
                "CRITERION_NAME" => "CRITERION.NAME",
                "STARS",
            ],
            "runtime" => [
                new ReferenceField(
                    'MAIN',
                    MainTable::class,
                    ["=this.MAIN_ID" => "ref.ID"],
                ),
                new ReferenceField(
                    'CRITERION',
                    CriterionTable::class,
                    ["=this.CRITERION_ID" => "ref.ID"],
                ),
            ],
        ];
    }
}