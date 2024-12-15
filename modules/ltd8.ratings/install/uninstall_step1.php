<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;

if (!check_bitrix_sessid()) return;

$request = Application::getInstance()->getContext()->getRequest();

?>

<form action="<?= $request->getRequestUri() ?>" method="post">

    <?= bitrix_sessid_post() ?>

    <h3><?= Loc::getMessage("LTD8_RATINGS_UNINSTALL_STEP_1_TITLE") ?></h3>

    <input type="checkbox" name="ltd8_ratings_delete_components" id="ltd8_ratings_delete_components">
    <label for="ltd8_ratings_delete_components"><?= Loc::getMessage("LTD8_RATINGS_UNINSTALL_STEP_1_LABEL_1") ?></label>
    <br><br>

    <input type="checkbox" name="ltd8_ratings_delete_db" id="ltd8_ratings_delete_db">
    <label for="ltd8_ratings_delete_db"><?= Loc::getMessage("LTD8_RATINGS_UNINSTALL_STEP_1_LABEL_2") ?></label>
    <br><br>

    <input type="submit" value="<?= Loc::getMessage("LTD8_RATINGS_UNINSTALL_STEP_1_BTN_DELETE") ?>">
</form>