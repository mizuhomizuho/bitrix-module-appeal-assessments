<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Moysklad\Sitemap\Initialization;

/** @var array $tabControl */
/** @global CMain $APPLICATION */

$module_id = "ltd8.ratings";

if (!($APPLICATION->GetGroupRight($module_id) >= "R")) {
    return;
}

$aTabs = [[
    "DIV" => "module_" . md5($module_id) . "_edit",
    "TAB" => "Настройка прав",
]];
$tabControl = new CAdminTabControl(
    "module_" . md5($module_id) . "_tab_control",
    $aTabs
);
$tabControl->Begin();
?>
    <form action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= $module_id ?>&lang=<?= LANG ?>" method="post">
        <?php
        echo bitrix_sessid_post();
        $tabControl->BeginNextTab();
        require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/admin/group_rights.php');
        $tabControl->Buttons();
        ?>
        <input class="adm-btn-save" type="submit" name="Update" value="Сохранить настройки"/>
    </form>
<?php
$tabControl->End();
