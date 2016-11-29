<?
use Bitrix\Main\Context,
	Bitrix\Main\Loader,
	Bitrix\Main\Type\DateTime,
	Bitrix\Currency,
	Bitrix\Catalog,
	Bitrix\Iblock;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arResult */
/** @global CUser $USER */

global $USER;

include "change_active.php";

$arResult = array();

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

$currentUser = $USER->GetId();
$currentUserIsAdmin = $USER->IsAdmin();

//SELECT
$arSelect = array(
	"ID",
	"NAME",
);
//WHERE
if($currentUserIsAdmin)
{
	$arFilter = array(
		"IBLOCK_ID" => 5,
	);
}
else
{
	$arFilter = array(
		"IBLOCK_ID" => 5,
		"PROPERTY_OPERATOR" => $currentUser,
	);
}
//EXECUTE
$allPartners = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
$userPartners = array();
$intKey=0;
while($partner = $allPartners->GetNext())
{
	$arResult["PARTNER"][$intKey] = $partner;
	$arResult["PARTNER_ID_LIST"][$intKey] = $partner["ID"];
	$arResultPartnerLink[$partner["ID"]] = &$arResult["PARTNER"][$intKey];
	$intKey++;
}

if(0 == count($arResult["PARTNER"]))
{
	ShowError("Доступ запрещен. Пользователь не является оператором ни у одного партнера.");
	return;
}

$userProducts = array();
//SELECT
$arSelect = array(
	"ID",
	"IBLOCK_ID",
	"ACTIVE",
	"NAME",
	"PREVIEW_PICTURE",
	"PREVIEW_TEXT",
	"DETAIL_PAGE_URL",
);
foreach($arResult["PARTNER_ID_LIST"] as $partnerIntKey=>$partnerId)
{
	//WHERE
	$arFilter = array(
		"IBLOCK_ID" => 2,
		"PROPERTY_PARTNER" => $partnerId,
	);
	//NAVIGATE
	$arNavParams = array(
		"nPageSize" => 4,
		"bShowAll" => "Y",
	);
		
	$partnerProduct = CIBlockElement::GetList(array(), $arFilter, false, $arNavParams, $arSelect);
	$partnerProduct->SetUrlTemplates();
	$intKey=0;
	while($product = $partnerProduct->GetNext())
	{
		$product["PREVIEW_PICTURE"] = CFile::GetFileArray($product["PREVIEW_PICTURE"]);
		$arResult["PARTNER_PRODUCT"][$partnerId][$intKey] = $product;
		$arResult["PARTNER_PRODUCT_ID_LIST"][$partnerId][$intKey] = $product["ID"];
		$arResultProductLink[$intKey["ID"]] = &$arResult["PARTNER_PRODUCT"][$partnerId][$intKey];
		$intKey++;
	}
	$arResult["NAV_RESULT"][$partnerId] = $partnerProduct;
	$arResult['NAV_STRING'][$partnerId] = $partnerProduct->GetPageNavStringEx($navComponentObject, '', '', 'Y');
}

$this->includeComponentTemplate();
?>