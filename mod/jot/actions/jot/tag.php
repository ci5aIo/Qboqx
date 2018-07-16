<?php
/**
 * QuebX tag elements action
 *
 * @package ElggFile
 */

// Get variables

$element_type =        get_input('element_type');
$access_id    =        get_input('access_id');
$object_guid  =        get_input('object_guid');
$object_type  =        get_input('object_type');
$owner_guid   =        get_input('owner_guid');
$seller          =        get_input('merchant');
$address_street1 =        get_input('merchant_address_street1');
$address_street2 =        get_input('merchant_address_street2');
$address_city    =        get_input('merchant_address_city');
$address_state   =        get_input('merchant_address_state');
$address_route   =        get_input('merchant_address_route');
$phone           =        get_input('merchant_phone');
$email           =        get_input('merchant_email');
$website         =        get_input('merchant_website');

elgg_make_sticky_form('jotForm');

$object = get_entity($object_guid);

if ($element_type == 'Merchant'){
	$object->merchant = $seller;
	$object->merchant_address_street1 = $address_street1;
	$object->merchant_address_street2 = $address_street2;
	$object->merchant_address_city = $address_city;
	$object->merchant_address_state = $address_state;
	$object->merchant_address_route = $address_route;
	$object->merchant_phone = $phone;
	$object->merchant_email = $email;
	$object->merchant_website = $website;
}
