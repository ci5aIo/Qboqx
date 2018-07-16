<?php
/**
 * Elgg jot Plugin
 * @package jot
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Scott Jenkins
 * @copyright SmarterNetwork 2010-2016
 * @version 1.8
 */

// Make sure we're logged in
gatekeeper();
		
// Get input data
$jot_object   = get_input('jot');
$subtype      = get_input('subtype');
$aspect       = get_input('aspect');
$aspect_02    = get_input('aspect_02');  // Sub-aspect
$element_type = get_input('element_type');
$category     = get_input('marketcategory');
$referrer     = elgg_extract('referrer', $vars, get_input('referrer'));

if (sizeof($jot_object) > 0) {
 	if (empty($title)){$title   = $jot_object['title'];}
	if (empty($aspect)){$aspect = $jot_object['aspect'];}
	if ($jot_object['do'] == 'Describe...'){
		$que = TRUE;                                           $display .= 'Describe...<br>';
	}
	$jot_object->category = $category;                         $display .= '$category: '.$category.'<br>';		
//	$jot_object->marketcategory = $category;                   $display .= '$category: '.$category.'<br>';		
}

if (!$aspect && $subtype){
	$aspect = $subtype;
}
if ($aspect == 'item'){
	$subtype = 'market';
}
if ($aspect_02){
	$config_vars = "$aspect_$aspect_02";
}
else {
	$config_vars = "{$aspect}s";
}

$variables = elgg_get_config($config_vars);

$input = array();
foreach ($variables as $name => $type) {
	$input[$name] = get_input($name);
	if ($name == 'title') {
		$input[$name] = strip_tags($input[$name]);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}
$relationship = $subtype;
if (sizeof($input)>0){
	if (empty($title)){$title = $input['title'];}
}

if (sizeof($jot_object) > 0) {
	if (empty($title)){$title = $jot_object['title'];}         $display .= '$jot_object[title]: '.$jot_object['title'].'<br>';
}

elgg_make_sticky_form('jotForm');

// Make sure the title isn't blank
if (empty($title)) {
	register_error($display);
	register_error(elgg_echo("jot:blank"));
//	forward($referrer);

} else {
		
	// Initialise a new ElggObject
	$jot = new ElggObject();
	
	if (sizeof($input) > 0) {
		foreach ($input as $name => $value) {
			$jot->$name = $value;
//			register_error('$name:'.$name.' = $value'.$value);
		}
	}
	
	$jot->title = $title;
	$jot->description = $description;
	// Tell the system it's a jot
	if ($aspect) {
		$jot->subtype     = $aspect;
		$display .= '$aspect='.$aspect.'<br>';
	}
	if ($subtype){
		$jot->subtype = $subtype;
	}
	else {
		$jot->subtype = "jot";	
	}		
	$display .= '$jot[subtype]: '.$jot['subtype'].'<br>';
	// Set its owner to the current user
	$jot->owner_guid = elgg_get_logged_in_user_guid();
	
	if (sizeof($jot_object) > 0) {
		$jot->category = $category;
//	    $jot->marketcategory = $category;
		$jot->record_stage = 'newborn';
/*		foreach ($jot_object as $name => $value) {
			$jot->$name = $value;
		}
*/	}
			
	// Add tags
	if (is_array($tagarray)) {
		$jot->tags = $tagarray;
	}
	
	// Save the jot
	if (!$jot->save()) {
		register_error(elgg_echo("jot:error"));
	}
	
	elgg_clear_sticky_form('jotForm');
			
	// Now see if we have a file icon
	if ((isset($_FILES['upload']['name'])) && (substr_count($_FILES['upload']['type'],'image/'))) {
		$prefix = "jot/".$jot->guid;
		
		$filehandler = new ElggFile();
		$filehandler->owner_guid = $jot->owner_guid;
		$filehandler->setFilename($prefix . ".jpg");
		$filehandler->open("write");
		$filehandler->write(get_uploaded_file('upload'));
		$filehandler->close();
		
		$thumbtiny = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
		$thumbsmall = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
		$thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),153,153, true);
		$thumblarge = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
		if ($thumbtiny) {
			
			$thumb = new ElggFile();
			$thumb->owner_guid = $jot->owner_guid;
			$thumb->setMimeType('image/jpeg');
			
			$thumb->setFilename($prefix."tiny.jpg");
			$thumb->open("write");
			$thumb->write($thumbtiny);
			$thumb->close();
			
			$thumb->setFilename($prefix."small.jpg");
			$thumb->open("write");
			$thumb->write($thumbsmall);
			$thumb->close();
			
			$thumb->setFilename($prefix."medium.jpg");
			$thumb->open("write");
			$thumb->write($thumbmedium);
			$thumb->close();
			
			$thumb->setFilename($prefix."large.jpg");
			$thumb->open("write");
			$thumb->write($thumblarge);
			$thumb->close();
				
		}
	}

	// Success message
//	system_message(elgg_echo("jot:posted"));
			
	// Add to river

    /* deprecated since 1.9
     * add_to_river('river/object/jot/create','create',$jot->owner_guid,$jot->guid);
     */
    elgg_create_river_item([
    		'view'=>'river/object/jot/create',
    		'action_type'=> 'create',
    		'subject_guid' => $jot->owner_guid,
    		'object_guid' => $jot->guid]);

    // Create the relationship
    if (!empty($item_guid)){add_entity_relationship($jot->guid, $relationship, $item_guid);}

	if ($que){forward("market/edit/$jot->guid");}
	
register_error($display);
	
/*			
	// Remove the jot cache
	remove_metadata($_SESSION['user']->guid,'title');
	remove_metadata($_SESSION['user']->guid,'body');
	remove_metadata($_SESSION['user']->guid,'aspect');
	remove_metadata($_SESSION['user']->guid,'referrer');
*/
//	forward($referrer);
	// Forward to the main jot page
//	forward(elgg_get_site_url() . "jot");

}
