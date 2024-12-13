<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

global $APPLICATION;
$moduleId = "ltd8.ratings";
if ($APPLICATION->GetGroupRight($moduleId) < "R") {
    $APPLICATION->AuthForm("Доступ запрещен");
}

$APPLICATION->SetTitle("Оценки обращений");

$aTabs = [
    ["DIV" => "edit1", "TAB" => "Основные настройки", "TITLE" => "Параметры модуля"],
];
$tabControl = new CAdminTabControl("tabControl", $aTabs);














https://know-online.com/post/bitrix-admin-cadminlist







?>

<form method="POST">
    <?= bitrix_sessid_post() ?>
    <?php $tabControl->Begin(); ?>

    <?php $tabControl->BeginNextTab(); ?>

    <tr>
        <td>
            <input type="text" placeholder="Поиск по номеру" name="search_text" value="<?= htmlspecialchars($currentValue) ?>" />
        </td>
        <td>
            <input type="submit" name="search" value="Поиск" class="adm-btn-save" />
        </td>
    </tr>

    <?php $tabControl->Buttons(); ?>

    <input type="submit" name="save" value="Сохранить" class="adm-btn-save" />

    <?php $tabControl->End(); ?>
</form>

<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
