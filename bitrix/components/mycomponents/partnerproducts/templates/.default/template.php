<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
?>

<div>
<p> Партнерские товары</p>
</div>
<div>
	<?foreach ($arResult["PARTNER"] as $partner) { ?>
	<a href="<?=$_SERVER['PHP_SELF']?>?partnerId=<?=$partner["ID"]?>"><?=$partner["NAME"]?></a>
	<? } ?>
	<?if(isset($_GET["partnerId"])) { 
		$partnerId = $_GET["partnerId"];
		} else {
		$partnerId = $arResult["PARTNER_ID_LIST"][0];
	}?>
	<div>
		<?foreach ($arResult["PARTNER_PRODUCT"][$partnerId] as $product) { ?>
			<table style="width:100%">
				<tr>
					<td>
						<?if(is_array($product["PREVIEW_PICTURE"])) { ?>
							<a href="<?=$product["DETAIL_PAGE_URL"]?>"><img
								border="0"
								src="<?=$product["PREVIEW_PICTURE"]["SRC"]?>"
								width="<?=$product["PREVIEW_PICTURE"]["WIDTH"]?>"
								height="<?=$product["PREVIEW_PICTURE"]["HEIGHT"]?>"
								alt="<?=$product["NAME"]?>"
								title="<?=$product["NAME"]?>"
								/>
							</a>
						<? } ?>
					</td>
					<td>
						<a href="<?=$product["DETAIL_PAGE_URL"]?>"><?=$product["NAME"]?></a>
					</td> 
					<td>
						<p><?=substr($product["PREVIEW_TEXT"],0,100)?> ...</p>
					</td>
					<td>
						<form action="<?=$_SERVER['PHP_SELF']?>?partnerId=<?=$partnerId?>" method="post">
							<input type="hidden" name="productId" value="<?=$product["ID"]?>">
							<?if($product["ACTIVE"] == "Y") { ?>
								<span>Активен</span>
								<input type="submit" name="deactivate" value="Деактивтровать">
							<? } else if($product["ACTIVE"] == "N") { ?>
								<span>Не активен</span>
								<input type="submit" name="activate" value="Активировать">
							<? } ?>
						</form>
					</td>
					</tr>
			</table>
		<? } ?>
	</div>
<p><?// echo $arResult["NAV_STRING"]; ?></p>
</div>
