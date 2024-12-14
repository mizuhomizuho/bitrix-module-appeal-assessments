<?php

namespace Ltd8\Ratings\Admin;

use Bitrix\Main\Application;
use Bitrix\Main\Text\HtmlFilter;
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

    public function initFilter(\CAdminList $lAdmin): void
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $lAdmin->InitFilter([
            "findRequestNumber",
        ]);
        $findRequestNumber = trim((string)$request->get("findRequestNumber"));
        $arFilter = [];
        if ($findRequestNumber !== '') {
            $arFilter[] = \Bitrix\Main\Entity\Query::filter()
                ->where("MAIN.REQUEST_NUMBER", $findRequestNumber);
        }
        $this->setFilter($arFilter);
    }

    public function echoFilter(string $tableName): void
    {
        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();

        $findRequestNumber = (string)$request->get("findRequestNumber");

        $oFilter = new \CAdminFilter($tableName . "_filter", [
            'findRequestNumber' => "Номер обращения",
        ]);

        ?>

        <form name="<?= "find_${$tableName}_form" ?>" method="get" action="<?= $APPLICATION->GetCurPage() ?>?">
            <input type="hidden" name="page" value="<?= HtmlFilter::encode($request->get("page")) ?>">
            <?php $oFilter->Begin() ?>
            <tr>
                <td>Название:</td>
                <td>
                    <input type="text" name="findRequestNumber" size="30"
                           value="<?= HtmlFilter::encode($findRequestNumber) ?>">
                </td>
            </tr>
            <?php $oFilter->Buttons([
                    "table_id" => $tableName,
                    "url" => $APPLICATION->GetCurPage(),
                    "form" => "find_${$tableName}_form"]
            ) ?>
            <?php $oFilter->End() ?>
        </form>
        <?php
    }

    public function getQueryParams(): array
    {
        $headers = [
            "ID" => "ID",
            "REQUEST_NUMBER" => "Номер обращения",
            "CRITERION_NAME" => "Критерий",
            "STARS" => "Количество звезд",
        ];

        $query = DataTable::query()->setSelect([
            "ID",
            "REQUEST_NUMBER" => "MAIN.REQUEST_NUMBER",
            "CRITERION_NAME" => "CRITERION.NAME",
            "STARS",
        ])
            ->registerRuntimeField(
                "MAIN",
                [
                    "data_type" => MainTable::class,
                    "reference" => [
                        "=this.MAIN_ID" => "ref.ID",
                    ],
                ]
            )
            ->registerRuntimeField(
                "CRITERION",
                [
                    "data_type" => CriterionTable::class,
                    "reference" => [
                        "=this.CRITERION_ID" => "ref.ID",
                    ],
                ]
            );

        return [
            "headers" => $headers,
            "query" => $query,
        ];
    }
}