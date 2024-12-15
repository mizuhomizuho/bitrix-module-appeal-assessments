<?php

use Bitrix\Main\Loader;
use Ltd8\Ratings\CriterionTable;

class Ltd8RatingsComponent extends \CBitrixComponent
{
    private function getData(): array
    {
        $return = [];

        $list = CriterionTable::getList([
            "cache" => [
                "ttl" => 3600 * 24 * 888,
                "cache_joins" => true,
            ],
        ]);

        while ($item = $list->fetch()) {
            $return["CRITERION"][$item["ID"]] = $item;
        }
        
        return $return;
    }

    public function executeComponent(): void
    {
        Loader::includeModule("ltd8.ratings");

        if ($this->startResultCache()) {

            $this->arResult = $this->getData();

            $this->includeComponentTemplate();
        }
    }
}