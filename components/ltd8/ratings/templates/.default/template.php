<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$requestNumber = \Ltd8\Ratings\Main::getRequestNumber();

?>
<div class="ltd8-ratings__box">

    <h2>Номер обращения <b><?=$requestNumber?></b></h2>

    <br>

    <div class="ltd8-ratings__stars">
        <?php
        foreach (range(1, 5) as $starsValue) {
            ?>
            <div
                class="ltd8-ratings__stars-item js-ltd8-ratings__stars-item"
                data-nequest-number="<?=$requestNumber?>"
                data-stars="<?=$starsValue?>"></div>
            <?php
        }
        ?>
    </div>
</div>