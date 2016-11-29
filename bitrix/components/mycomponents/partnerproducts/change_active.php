<?
use Bitrix\Main\Context,
	Bitrix\Main\Loader,
	Bitrix\Main\Type\DateTime,
	Bitrix\Currency,
	Bitrix\Catalog,
	Bitrix\Iblock;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @global CUser $USER */

global $USER;
if(!CModule::IncludeModule("iblock"))
{
	ShowError(GetMessage("Модуль Информационных блоков не установлен"));
	return;
}
if(isset($_POST["activate"]) || isset($_POST["deactivate"]))
{
	if(!$USER->IsAuthorized())
	{
		ShowError("Доступ запрещен. Пользователь не авторизован.");
		return;
	}
	if(!CModule::IncludeModule("iblock"))
	{
		ShowError(GetMessage("Модуль Информационных блоков не установлен"));
		return;
	}
	
	if(!$USER->IsAdmin())
	{
		if($product = CIBlockElement::GetList(array(), array("ID" => $_POST["productId"]))->GetNextElement())
		{
			$productPartner = $product->GetProperty("PARTNER");
			if($partner = CIBlockElement::GetList(array(), array("ID" => $productPartner["VALUE"]))->GetNextElement())
			{
				$partnerOperator = $partner->GetProperty("OPERATOR");
				if(($USER->GetId()) == $partnerOperator["VALUE"])
				{
					$el = new CIBlockElement;
					$res = isset($_POST["activate"])
						? $el->Update($_POST["productId"],array("ACTIVE"=>"Y"))
						: $el->Update($_POST["productId"],array("ACTIVE"=>"N"));
				}
				else
				{
					ShowError("Доступ запрещен. У пользователя нет прав на изменение товара.");
					return;
				}
				unset($partner, $partnerOperator);
			}
			else
			{
				ShowError("Доступ запрещен. Партнер товара не найден.");
				return;
			}
			unset($product, $productPartner);
		}
		else
		{
			ShowError("Доступ запрещен. Товар не найден.");
			return;
		}
	}
	else
	{
		$el = new CIBlockElement;
		$res = isset($_POST["activate"])
			? $el->Update($_POST["productId"],array("ACTIVE"=>"Y"))
			: $el->Update($_POST["productId"],array("ACTIVE"=>"N"));
	}
}
?>