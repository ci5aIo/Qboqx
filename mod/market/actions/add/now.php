<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

// Make sure we're logged in
gatekeeper();
		
// Get input data
$title = get_input('item_title');
$body = '';
$category = 'item';
	
// Make sure the title isn't blank
if (empty($title)) {

	register_error(elgg_echo("market:blank"));
	forward(REFERER);

} else {
		
	// Initialise a new ElggObject
	$market = new ElggObject();
			
	// Tell the system it's an market post
	$market->subtype = "market";
			
	// Set its owner to the current user
	$market->owner_guid = elgg_get_logged_in_user_guid();
			
	// Set its access_id
	$market->access_id = $access;
			
	// Set its title and description appropriately
	$market->title = $title;
	$market->description = $body;
	$marketpost->marketcategory = $category;
			
	// Before we can set metadata, we need to save the market post
	if (!$market->save()) {
		register_error(elgg_echo("market:error"));
	}
		
	// Success message
	system_message(elgg_echo("market:posted"));
			
	// Add to river
	elgg_create_river_item(
			['view'=>'river/object/market/create',
					'action_type' => 'create',
					'subject_guid' => elgg_get_logged_in_user_guid(),
					'object_guid' => $market->guid]);
			
	// Forward to the main market page
	forward(elgg_get_site_url() . "market");

}

