<?php

const LTD8_RATINGS_ADMIN = true;

$page = (string) $_GET['page'];

if (!preg_match('/^\w+$/', $page)) {
    return;
}

foreach (['local', 'bitrix'] as $dir) {
    $path = $_SERVER['DOCUMENT_ROOT'] . "/$dir/modules/ltd8.ratings/admin/pages/$page.php";
    if (file_exists($path)) {
        include $path;
        break;
    }
}
