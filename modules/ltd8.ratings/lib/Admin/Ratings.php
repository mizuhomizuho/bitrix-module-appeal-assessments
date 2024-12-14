<?php

namespace Ltd8\Ratings\Admin;

use Ltd8\Ratings\CriterionTable;
use Ltd8\Ratings\MainTable;

class Ratings
{
    public function getReplaceParams(array $list): array
    {
        $data = $this->getDataForReplace($list);

        $replaceData = [];
        foreach ($list as $item) {
            if (isset($data['main'][$item["MAIN_ID"]])) {
                $replaceData[$item["ID"]]["MAIN_ID"] = $data['main'][$item["MAIN_ID"]]["REQUEST_NUMBER"];
            }
            if (isset($data['criterion'][$item["CRITERION_ID"]])) {
                $replaceData[$item["ID"]]["CRITERION_ID"] = $data['criterion'][$item["CRITERION_ID"]]["NAME"];
            }
        }

        $replaceHeaders = [];
        foreach (MainTable::getMap() as $value) {
            if ($value->getName() === "REQUEST_NUMBER") {
                $replaceHeaders["MAIN_ID"] = $value->getTitle();
                break;
            }
        }
        foreach (CriterionTable::getMap() as $value) {
            if ($value->getName() === "NAME") {
                $replaceHeaders["CRITERION_ID"] = $value->getTitle();
                break;
            }
        }

        return [
            "data" => $replaceData,
            "headers" => $replaceHeaders,
        ];
    }

    private function getDataForReplace(array $list): array
    {
        $mainIds = [];
        $criterionIds = [];
        foreach ($list as $item) {
            $mainIds[$item["MAIN_ID"]] = $item["MAIN_ID"];
            $criterionIds[$item["CRITERION_ID"]] = $item["CRITERION_ID"];
        }
        $mainIds = array_keys($mainIds);
        $criterionIds = array_keys($criterionIds);

        $mainResult = [];
        if ($mainIds) {
            $list = MainTable::getList([
                "filter" => [
                    "ID" => $mainIds,
                ],
            ]);
            if ($item = $list->fetch()) {
                $mainResult[$item["ID"]] = $item;
            }
        }

        $criterionResult = [];
        if ($mainIds) {
            $list = CriterionTable::getList([
                "filter" => [
                    "ID" => $criterionIds,
                ],
            ]);
            if ($item = $list->fetch()) {
                $criterionResult[$item["ID"]] = $item;
            }
        }

        return [
            "main" => $mainResult,
            "criterion" => $criterionResult,
        ];
    }
}