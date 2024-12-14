<?php

namespace Ltd8\Ratings\Admin;

use Ltd8\Ratings\CriterionTable;
use Ltd8\Ratings\DataTable;
use Ltd8\Ratings\MainTable;

class Ratings
{
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