<?php

namespace Ltd8\Ratings\Controller;

use Bitrix\Main\Application;
use Ltd8\Ratings\DataTable;
use Ltd8\Ratings\MainTable;
use Ltd8\Ratings\Model;

class Stars extends \Bitrix\Main\Engine\Controller
{
    public function configureActions()
    {
        return [
            'add' => [
                '-prefilters' => [
                    '\Bitrix\Main\Engine\ActionFilter\Authentication',
                ],
            ],
        ];
    }

    public function addAction()
    {
        $request = Application::getInstance()->getContext()->getRequest();

        $criterionId = (int) $request->get("criterionId");
        $requestNumber = (int) $request->get("requestNumber");
        $stars = (int) $request->get("stars");

        $modelStars = new Model\Stars();

        return $modelStars->add($criterionId, $requestNumber, $stars);
    }
}