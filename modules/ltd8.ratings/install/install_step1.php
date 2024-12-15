<?php

use Bitrix\Main\Application;

if (!check_bitrix_sessid()) return;

$request = Application::getInstance()->getContext()->getRequest();

?>

<form action="<?= $request->getRequestUri() ?>" method="post">
    <?= bitrix_sessid_post() ?>
    <h3>Установка:</h3>
    <?php
    if (!LTD8_RATINGS_INSTALL_STEP_1_IS_MAIN_TABLE_EXISTS) {
        ?>
        <input type="checkbox" name="ltd8_ratings_add_test_criterion" id="ltd8_ratings_add_test_criterion" checked>
        <label for="ltd8_ratings_add_test_criterion">Заполнить таблицу критериев?</label>
        <br><br>
        <input type="checkbox" name="ltd8_ratings_add_test_data" id="ltd8_ratings_add_test_data">
        <label for="ltd8_ratings_add_test_data">Заполнить тестовыми данными таблицу обращений?</label>
        <br><br>
        <?php
    }
    ?>
    <?php
    if (LTD8_RATINGS_INSTALL_STEP_1_ISSET_COMPONENT) {
        ?>
        <input type="checkbox" name="ltd8_ratings_rewrite_files" id="ltd8_ratings_rewrite_files" required>
        <label for="ltd8_ratings_rewrite_files">
            Найден ранее установленный компонент. Стандартные файлы в нем будут перезаписаны. Продолжить?
        </label>
        <br><br>
        <?php
    }
    ?>
    <input type="submit" value="Установить">
</form>