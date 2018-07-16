<?php
/**
 * Jot detach action
 *
 * @package ElggFile
 */

// Get variables
$element_type   =       get_input("element_type");
$attachment     = (int) get_input('guid', 0);
$container_guid = (int) get_input('container_guid', 0);
$access_id      = (int) get_input("access_id");
$owner_guid     = (int) get_input('owner_guid', elgg_get_logged_in_user_guid());
$attachments    =       get_input('params');
//register_error('$attachment:'.$attachment);
$container      = get_entity($container_guid); 

switch ($element_type){
	case 'support_group':
		$relationship = 'support_group_of';
		break;
	case 'supplier':
		$relationship = 'supplier_of';
		break;
	case 'merchant':
		$relationship = 'merchant_of ';
		break;
	default:
		$relationship = $element_type;
		break;
}

//echo elgg_view('input/file', array('name' => 'upload')); 

if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}

if (empty($attachment) && empty($attachments)){
register_error('No Attachments');
	forward(REFERER);
}
// Detach a single attachment
if(isset($attachment)){
	if(check_entity_relationship($attachment, $relationship, $container_guid)){          $display .= 'relationship exists<br>';
	 	remove_entity_relationship($attachment, $relationship, $container_guid);}
	if(check_entity_relationship($container_guid, $relationship, $attachment)){          $display .= 'relationship exists<br>';
		remove_entity_relationship($container_guid, $relationship, $attachment);}
	if($container->item_guid == $attachment){
		unset($container->item_guid);
	    $container->retain_line_label = 'yes';
		$container->save();}
}
// Detach multiple attachments
if ($attachments){
foreach($attachments as $i){
	if(check_entity_relationship($i, $relationship, $container_guid)){
		remove_entity_relationship($i, $relationship, $container_guid);
//		system_message("$element_type detached");
	}	
}
//	forward(REFERER);
}
eof:
register_error($display);