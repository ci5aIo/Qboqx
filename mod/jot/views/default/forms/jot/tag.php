<!-- jot\views\default\forms\jot\tag.php -->
<?php

$access_id          = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$guid               = elgg_extract('guid', $vars, null);
$selected_item      = elgg_extract('item', $vars, false);
$element_type       = elgg_extract('element_type', $vars, null);
$content_options    = elgg_extract('content_options', $vars, null);
$submit_label       = elgg_echo('Tag');
$owner              = elgg_get_page_owner_entity();
$owner_guid         = $owner->guid;
if ($selected_item) {
	$url = $selected_item->getURL();
}
if (!$owner_guid) {
	$owner_guid = elgg_get_logged_in_user_guid();
}
$object      = get_entity($guid);
$object_type = $object->getsubtype();

echo elgg_view('input/hidden', array('name' => 'element_type', 'value' => $element_type));
echo elgg_view('input/hidden', array('name' => 'access_id'   , 'value' => $access_id   ));
echo elgg_view('input/hidden', array('name' => 'object_guid' , 'value' => $guid        ));
echo elgg_view('input/hidden', array('name' => 'object_type' , 'value' => $object_type ));
echo elgg_view('input/hidden', array('name' => 'owner_guid'  , 'value' => $owner_guid  ));
//echo elgg_dump($vars);
if ($element_type == 'Merchant'){
	$seller   = elgg_view('input/text', array(
			'name' => 'merchant',
			'value' => $object->merchant,
	));
	$address_street1   = elgg_view('input/text', array(
			'name' => 'merchant_address_street1',
			'value' => $object->merchant_address_street1,
	));
	$address_street2   = elgg_view('input/text', array(
			'name' => 'merchant_address_street2',
			'value' => $object->merchant_address_street2,
	));
	$address_city   = elgg_view('input/text', array(
			'name' => 'merchant_address_city',
			'value' => $object->merchant_address_city,
	));
	$address_state   = elgg_view('input/text', array(
			'name' => 'merchant_address_state',
			'value' => $object->merchant_address_state,
	));
	$address_route   = elgg_view('input/text', array(
			'name' => 'merchant_address_route',
			'value' => $object->merchant_address_route,
	));
	$phone   = elgg_view('input/text', array(
			'name' => 'merchant_phone',
			'value' => $object->merchant_phone,
	));
	$email   = elgg_view('input/text', array(
			'name' => 'merchant_email',
			'value' => $object->merchant_email,
	));
	$website   = elgg_view('input/text', array(
			'name' => 'merchant_website',
			'value' => $object->merchant_website,
	));
	
	echo "<div class='rTable' style='width:100%'>
			<div class='rTableBody'>";
	echo "		<div class='rTableRow'>
					<div class='rTableCell'>Seller</div>
					<div class='rTableCell'>$seller</div>
				</div>";
	echo "		<div class='rTableRow'>
					<div class='rTableCell'>Address</div>
					<div class='rTableCell'>$address_street1</div>
				</div>";
	echo "		<div class='rTableRow'>
					<div class='rTableCell'></div>
					<div class='rTableCell'>$address_street2</div>
				</div>";
	echo "		<div class='rTableRow'>
					<div class='rTableCell'></div>
					<div class='rTableCell'>
						<div class='rTableRow'>
							<div class='rTableCell'>City</div>
							<div class='rTableCell'>State</div>
							<div class='rTableCell'>Zip</div>
						</div>
						<div class='rTableRow'>
							<div class='rTableCell'>$address_city</div>
							<div class='rTableCell'>$address_state</div>
							<div class='rTableCell'>$address_route</div>
						</div>
					</div>
				</div>";
	echo "		<div class='rTableRow'>
					<div class='rTableCell'>Phone</div>
					<div class='rTableCell'>$phone</div>
				</div>";
	echo "		<div class='rTableRow'>
					<div class='rTableCell'>Email</div>
					<div class='rTableCell'>$email</div>
				</div>";
	echo "		<div class='rTableRow'>
					<div class='rTableCell'>Website</div>
					<div class='rTableCell'>$website</div>
				</div>";
	
	echo "	</div>
		</div>";
}
echo elgg_view('input/submit', array('value' => $submit_label)).'</p>';

echo '</div>';