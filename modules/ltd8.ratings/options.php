<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

/** @global CMain $APPLICATION */

$module_id = "ltd8.ratings";

if ($APPLICATION->GetGroupRight($module_id) < "W") {
    return;
}

$aTabs = [[
    "DIV" => "module_" . md5($module_id) . "_edit",
    "TAB" => Loc::getMessage("LTD8_RATINGS_OPTIONS_PAGE_TAB_RIGHTS_TITLE"),
]];
$tabControl = new CAdminTabControl(
    "module_" . md5($module_id) . "_tab_control",
    $aTabs
);
$tabControl->Begin();

$request = Application::getInstance()->getContext()->getRequest();
?>
    <form action="<?= $request->getRequestUri() ?>?mid=<?= $module_id ?>&lang=<?= LANG ?>" method="post">
        <?php
        echo bitrix_sessid_post();
        $tabControl->BeginNextTab();
        require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/admin/group_rights.php');
        $tabControl->Buttons();
        ?>
        <input class="adm-btn-save" type="submit" name="Update"
               value="<?= Loc::getMessage("LTD8_RATINGS_OPTIONS_PAGE_BTN_RIGHTS_SAVE") ?>"/>
    </form>
<?php
$tabControl->End();
