<?php

namespace Ltd8\Ratings\Admin;

class Base
{
    protected function canWrite(): bool
    {
        global $APPLICATION;
        return $APPLICATION->GetGroupRight(\LTD8_RATINGS_MODULE_ID) > "R";
    }
}