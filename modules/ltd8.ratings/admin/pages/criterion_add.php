<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Ltd8\Ratings\CriterionTable;

if (!defined('LTD8_RATINGS_ADMIN')) {
    exit;
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

global $APPLICATION;
$moduleId = "ltd8.ratings";
if ($APPLICATION->GetGroupRight($moduleId) < "R") {
    $APPLICATION->AuthForm("Доступ запрещен");
}

$APPLICATION->SetTitle("Критерии");

Loader::includeModule('ltd8.ratings');

$request = Context::getCurrent()->getRequest();

if ($request->get("criterion_name") !== null) {
    CriterionTable::add([
        "NAME" => (string)$request->get("criterion_name"),
    ]);
    CAdminMessage::ShowNote("Критерий добавлен");
}

$aTabs = [
    ["DIV" => "ltd8_ratings_criterion_tab_add", "TAB" => "Добавить", "TITLE" => "Добавить"],
];

$tabControl = new CAdminTabControl("tabControl", $aTabs);

?>

    <form method="post">

        <?= bitrix_sessid_post(); ?>

        <?php $tabControl->Begin(); ?>

        <?php $tabControl->BeginNextTab(); ?>

        <tr>
            <td>
                Критерий
            </td>
            <td>
                <input type="text" name="criterion_name" value=""/>
            </td>
        </tr>

        <?php $tabControl->Buttons(); ?>

        <input type="submit" name="save" value="Добавить" class="adm-btn-save"/>

        <?php $tabControl->End(); ?>

    </form>

<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
