<?php

namespace Ltd8\Ratings\Controller;

var_dump(2223);
exit;
class Stars extends \Bitrix\Main\Engine\Controller
{
    public function configureActions()
    {
        return [
            'add' => [
                'prefilters' => [
//                    '\Bitrix\Main\Engine\ActionFilter\Authentication',
//                    '\Bitrix\Main\Engine\ActionFilter\Csrf',
//                    '\Bitrix\Main\Engine\ActionFilter\HttpMethod',
                ],
                '-prefilters' => [
//                    '\Bitrix\Main\Engine\ActionFilter\Authentication',
//                    '\Bitrix\Main\Engine\ActionFilter\Csrf',
//                    '\Bitrix\Main\Engine\ActionFilter\HttpMethod',
                ],
                '+prefilters' => [
//                    new \Mycomp\Exchangerates\Admin\Filter(),
//                    '\Bitrix\Main\Engine\ActionFilter\HttpMethod',
                ]
            ],
        ];
    }

    public function listAllowedScopes()
    {
        return [
            Controller::SCOPE_AJAX,
        ];
    }

    public function addAction()
    {

//        \Mycomp\Exchangerates\Admin\Filter::xxx();
//        LocalRedirect("/bitrix/admin/auth.php");
//        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/header.php");
//        require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin.php");
        var_dump(2221);
        exit;

    }
}