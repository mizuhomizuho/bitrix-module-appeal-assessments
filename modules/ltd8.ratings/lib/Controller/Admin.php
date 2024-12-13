<?php

namespace Mycomp\Exchangerates\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\Response\TextResponse;
use Bitrix\Main\Localization\Loc;

class Admin extends Controller
{
    public function configureActions()
    {
        return [
            'index' => [
                'prefilters' => [],
                '+prefilters' => [
                    new \Mycomp\Exchangerates\Admin\Filter(),
                ],
            ],
        ];
    }

    public function indexAction()
    {


        ;

        var_dump(\CJSCore::Init(['file_input']),111);
        exit;
//        // Можно работать с моделями, например, получать данные из базы данных
//        // В данном примере просто выводим текст на странице
//        return new TextResponse("Hello, this is your page content!");
    }
}