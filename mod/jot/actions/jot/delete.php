<?php
/**
 * Elgg jot Plugin
 * @package jot
 * @TODO: Check whether a jot has relatives.  Do not delete if conditions are not met. See design for conditions.  
 */

 
// Make sure we're logged in (send us to the front page if not)
if (!elgg_is_logged_in()) forward();

// Get input data
$guid           = (int) get_input('guid');
$container_guid = (int) get_input('container_guid');
if (!$container_guid){
	$container_guid = $jot['asset'];
}
$referrer       = $vars['referrer'];
$tab            = get_input('tab');
$delete_contents = get_input('delete_contents');                                           $display .= '$delete_contents: '.$delete_contents.'<br>';

$container      = get_entity($container_guid);
$jot            = get_entity($guid);
$item_guid      = $container_guid;
$item           = get_entity($item_guid);
$subtype        = $jot->getSubtype();
if ($container){
	$container_subtype = $container->getSubtype();
}
if (!$referrer && $container){
	$referrer       = "jot/view/$container_guid";
//	$referrer       = "jot/$container_subtype/$container_guid";
}
if ($tab){
	$referrer  .= "/$tab";
}
if ($subtype == 'receipt_item'){ // Stay put when deleting a receipt item
	$referrer = null;
}
// register_error('$referrer: '.$referrer);
// register_error('$guid: '.$guid);
// register_error('$container_guid: '.$container_guid);

// Make sure we actually have permission to edit
if ($jot->canEdit()
/*    && ($subtype == "jot" 
     || $subtype == "issue"
     || $subtype == "observation"
     || $subtype == "effort"
     || $subtype == "cause"
     || $subtype == "part"
     || $subtype == "item"
     || $subtype == "next_step"
     || $subtype == "transfer"
    )*/
	) {
	
	// Get owning user
	$owner = get_entity($jot->getOwnerGUID());
				
	// Delete the images

	$prefix = "jot/".$guid;
		
	$tiny = $prefix."tiny.jpg";
	$small = $prefix."small.jpg";
	$medium = $prefix."medium.jpg";
	$large = $prefix."large.jpg";
	$master = $prefix.".jpg";
				
	if ($tiny) {
		$delfile = new ElggFile();
		$delfile->owner_guid = $owner->guid;
		$delfile->setFilename($tiny);
		$delfile->delete();
	}

	if ($small) {

					$delfile = new ElggFile();
					$delfile->owner_guid = $owner->guid;
					$delfile->setFilename($small);
					$delfile->delete();
				}

				if ($medium) {

					$delfile = new ElggFile();
					$delfile->owner_guid = $owner->guid;
					$delfile->setFilename($medium);
					$delfile->delete();
				}

				if ($large) {

					$delfile = new ElggFile();
					$delfile->owner_guid = $owner->guid;
					$delfile->setFilename($large);
					$delfile->delete();
				}

				if ($master) {

					$delfile = new ElggFile();
					$delfile->owner_guid = $owner->guid;
					$delfile->setFilename($master);
					$delfile->delete();
				}


		// Delete the jot contents
				if ($delete_contents){
				    $contents = elgg_get_entities_from_relationship(array(
                            		'type'                 => 'object',
                            		'relationship_guid'    => $jot->guid,
                            		'inverse_relationship' => true,
                            		'limit'                => false,
                            	));
				    foreach($contents as $content_item){
				        $rowsaffected += $content_item->delete();
				    }
				}
		// Delete the jot
				$rowsaffected += $jot->delete();
				if ($rowsaffected > 0) {
		
		// Success message
					system_message(elgg_echo("jot:deleted"));
				} else {
					register_error(elgg_echo("jot:notdeleted"));
				}
				
			// Forward to the referring page
// 			if ($referrer){
// 				forward($referrer);
// 			}
			//forward(elgg_get_site_url() . "market/view/{$item_guid}");
            forward(REFERRER);
		}
		
eof:
//register_error($display);