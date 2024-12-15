<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) return;

$request = Application::getInstance()->getContext()->getRequest();

?>

<form action="<?= $request->getRequestUri() ?>" method="post">
    <?= bitrix_sessid_post() ?>
    <h3><?= Loc::getMessage("LTD8_RATINGS_INSTALL_STEP_1_TITLE") ?></h3>
    <?php
    if (!LTD8_RATINGS_INSTALL_STEP_1_IS_MAIN_TABLE_EXISTS) {
        ?>
        <input type="checkbox" name="ltd8_ratings_add_test_criterion" id="ltd8_ratings_add_test_criterion" checked>
        <label for="ltd8_ratings_add_test_criterion"><?= Loc::getMessage("LTD8_RATINGS_INSTALL_STEP_1_LABEL_1") ?></label>
        <br><br>
        <input type="checkbox" name="ltd8_ratings_add_test_data" id="ltd8_ratings_add_test_data">
        <label for="ltd8_ratings_add_test_data"><?= Loc::getMessage("LTD8_RATINGS_INSTALL_STEP_1_LABEL_2") ?></label>
        <br><br>
        <?php
    }
    ?>
    <?php
    if (LTD8_RATINGS_INSTALL_STEP_1_ISSET_COMPONENT) {
        ?>
        <input type="checkbox" name="ltd8_ratings_rewrite_files" id="ltd8_ratings_rewrite_files" required>
        <label for="ltd8_ratings_rewrite_files">
            <?= Loc::getMessage("LTD8_RATINGS_INSTALL_STEP_1_LABEL_3") ?>
        </label>
        <br><br>
        <?php
    }
    ?>
    <input type="submit" value="<?= Loc::getMessage("LTD8_RATINGS_INSTALL_BTN_OK") ?>">
</form>