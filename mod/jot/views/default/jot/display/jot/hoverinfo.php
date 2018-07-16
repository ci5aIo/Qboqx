<?php
/*
 * used by: 
 	* jot\views\default\jot\display\transfer\summary.php
 * 
 */

$jot      = elgg_extract('entity', $vars, false);
$jot_type = elgg_entity_exists($jot->guid) ? $jot->getSubtype() : NULL;
$tag_type = $vars['tag_type'];
if (isset($jot['merchant'])){
    $is_entity = true;
    $merchant = $jot['merchant'];
    $seller = $merchant->name;
}
else {
    if (elgg_entity_exists($jot->merchant)){
        $is_entity = true;
        $merchant  = get_entity($jot->merchant);
        $seller = $merchant->name;
    }
    else {
        $is_entity = false;
        $seller = $jot->merchant;
    }
}

if ($jot_type == 'transfer'){
	if ($tag_type == 'Merchant'){
		$info = "<div class='rTable' style='width:100%'>
				<div class='rTableBody'>
					<div class='rTableRow'>
						<div class='rTableCell'>Seller</div>
						<div class='rTableCell'>$seller</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'>Address</div>
						<div class='rTableCell'>".elgg_echo($jot->merchant_address_street1)."</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'></div>
						<div class='rTableCell'>".elgg_echo($jot->merchant_address_street2)."</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'></div>
						<div class='rTableCell'>".elgg_echo($jot->merchant_address_city)." ".elgg_echo($jot->merchant_address_state)."</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'>Phone</div>
						<div class='rTableCell'>".elgg_echo($jot->merchant_phone)."</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'>Email</div>
						<div class='rTableCell'>".elgg_echo($jot->merchant_email)."</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'>Website</div>
						<div class='rTableCell'>".elgg_echo($jot->merchant_website)."</div>
					</div>
				</div>
			</div>";
	}
}

echo $info;
?>

