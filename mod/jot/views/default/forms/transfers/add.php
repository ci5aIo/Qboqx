Form: jot\views\default\forms\transfers\add.php<br>-->
<?php

$item_guid      = $vars['container_guid'];
//$item_guid      = $vars['entity_guid'];
$description    = $vars['description'];
$decision_state = (int)$vars['decision_state'];
$referrer       = $vars['referrer'];
$transfer_title = elgg_extract('title', $vars, 'Transfer to new owner');
$item           = get_entity($item_guid);
$ts             = time();
$title          = elgg_echo("New Transfer");
$tags           = "";

//TODO: assign access rights based on a user's association with specific groups.  See "Transfer" in QuebX.tpd for design criteria.
$acquire_rights_phantom = true;
$transfer_rights_phantom = true;

/*
echo "$transfer_title: ".$transfer_title."<br>";
echo "<br>item owner: ".$item_owner;
echo "<br>logged in user: ".elgg_get_logged_in_user_guid()."<br>";
*/

//echo elgg_dump($vars);

/*
 // Decide
// Is the transfer associated with an item?
if ($item_guid){
	//Yes - The transfer is associated with an item
	// Is the item owned by the logged-in user?
	if($item_owner == elgg_get_logged_in_user_guid()){
		//Yes - The item is owned by the logged-in user.
		$decision_state = 1; // Transfer to new owner
	}
		//No - The item is not owned by the logged-in user.
	else {
		$decision_state = 2; // Offer to Buy
	}
}
else {
	//No - The transfer is not associated with an item
	// Does the logged-in user have permission to acquire items from outside of QuebX?
	if($acquire_rights_phantom){
		$decision_state = 3; // Reciept Purchase
	}
	else {
		$decision_state = 4; // Request Purchase
	}
}
*/

echo "<!--referrer: $referrer-->";

echo elgg_view('input/hidden', array('name' => 'item_guid'     , 'value' => $item_guid));
echo elgg_view('input/hidden', array('name' => 'referrer'      , 'value' => $referrer));
echo elgg_view('input/hidden', array('name' => 'subtype'       , 'value' => 'transfer'));
echo elgg_view('input/hidden', array('name' => 'decision_state', 'value' => $decision_state));
 
// Get plugin settings
$allowhtml = elgg_get_plugin_setting('jot_allowhtml', 'jot');
$numchars  = elgg_get_plugin_setting('jot_numchars', 'jot');
if($numchars == ''){
	$numchars = '250';
}

if (defined('ACCESS_DEFAULT')) {
	$access_id = ACCESS_DEFAULT;
} else {
	$access_id = 0;
}

echo elgg_view('forms/transfers/elements/header');

Switch($decision_state){
	case '1':
		$view_form = 'forms/transfers/elements/ownership';
		break;
	case '2':
		$view_form = 'forms/transfers/elements/offer';
		break;
	case '3':
		$view_form = 'forms/transfers/elements/offer';
		break;
	case '4':
		$view_form = 'forms/transfers/elements/offer';
		break;
}
// Why doesn't the switch work above?  It worked before transferring $decision_state from the jot/view (inline as commented out above).
if ($decision_state == 1){
	$view_form = 'forms/transfers/elements/ownership';
}
if ($decision_state == 2){
	$view_form = 'forms/transfers/elements/offer';
}
if ($decision_state == 3){
	$view_form = 'forms/transfers/elements/offer';
}
if ($decision_state == 4){
	$view_form = 'forms/transfers/elements/offer';
}

echo elgg_view($view_form, $vars);

//echo elgg_dump($vars);
