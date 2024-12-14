<?php

namespace Ltd8\Ratings\Model;

use Ltd8\Ratings\DataTable;
use Ltd8\Ratings\MainTable;

class Stars
{
    private function getMainId(int $requestNumber): false|int
    {
        $mainId = 0;

        $list = MainTable::getList([
            "filter" => [
                "REQUEST_NUMBER" => $requestNumber,
            ],
        ]);
        if ($item = $list->fetch()) {
            $mainId = (int) $item["ID"];
        }

        if ($mainId === 0) {
            $result = MainTable::add([
                "REQUEST_NUMBER" => $requestNumber,
            ]);
            $mainId = (int) $result->getId();
        }

        if ($mainId) {
            return $mainId;
        }

        return false;
    }

    public function add(int $criterionId, int $requestNumber, int $stars): array
    {
        $return = ["result" => false];

        $mainId = $this->getMainId($requestNumber);
        if (!$mainId) {
            $return["messages"][] = "Ошибка";
            return $return;
        }

        $list = DataTable::getList([
            "filter" => [
                "MAIN_ID" => $mainId,
                "CRITERION_ID" => $criterionId,
            ],
        ]);
        if ($item = $list->fetch()) {
            DataTable::update($item["ID"], [
                "STARS" => $stars,
            ]);
        }
        else {
            DataTable::add([
                "MAIN_ID" => $mainId,
                "CRITERION_ID" => $criterionId,
                "STARS" => $stars,
            ]);
        }

        $return["result"] = true;
        return $return;
    }
}