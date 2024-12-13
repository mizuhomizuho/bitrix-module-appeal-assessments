<?php

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;
use Lcli\Iblock\ElementPropertyTable;

class Ltd8RatingsComponent extends \CBitrixComponent implements Controllerable
{
    public function configureActions(): array
    {
        return [
            'getTime' => [
                '-prefilters' => [
                    '\Bitrix\Main\Engine\ActionFilter\Authentication'
                ],
            ],
        ];
    }

    public function getTimeAction($data)
    {
        $res = [];

        return [
            'res' => $res,
        ];
    }

    public function executeComponent()
    {
        if ($this->startResultCache()) {

            $this->arResult['DATA'] = [];

            $this->includeComponentTemplate();
        }
    }
}