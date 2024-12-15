<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => 'Оценки обращений',
	"DESCRIPTION" => 'Оценки обращений',
	"SORT" => 8,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "ltd8_ratings",
			"NAME" => 'Оценки обращений',
			"SORT" => 8,
			"CHILD" => array(
				"ID" => "ltd8_ratings_cmpx",
			),
		),
	),
);
