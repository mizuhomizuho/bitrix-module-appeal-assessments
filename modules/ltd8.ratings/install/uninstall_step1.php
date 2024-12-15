<?php

use Bitrix\Main\Application;

if (!check_bitrix_sessid()) return;

$request = Application::getInstance()->getContext()->getRequest();

?>

<form action="<?= $request->getRequestUri() ?>" method="post">
    <?= bitrix_sessid_post() ?>
    <h3>Удаление:</h3>
    <input type="checkbox" name="ltd8_ratings_delete_components" id="ltd8_ratings_delete_components" checked>
    <label for="ltd8_ratings_delete_components">Удалить все найденные компоненты "Оценки обращений"?</label>
    <br><br>
    <input type="checkbox" name="ltd8_ratings_delete_db" id="ltd8_ratings_delete_db" checked>
    <label for="ltd8_ratings_delete_db">Удалить все данные модуля "Оценки обращений" из базы данных?</label>
    <br><br>
    <input type="submit" value="Удалить">
</form>