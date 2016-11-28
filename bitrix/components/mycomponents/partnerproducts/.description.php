<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Партнерские товары",
	"DESCRIPTION" => "Список товаров партнеров оператора",
	"ICON" => "/images/pp.gif",
	"SORT" => 10,
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "partner_products",
			"NAME" => "Партнерские товары",
			"SORT" => 30,
		)
	)
);
?>