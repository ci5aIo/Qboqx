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

// Make sure we're logged in (send us to the front page if not)
gatekeeper();

// Get input data
$guid        = (int) get_input('guid');
if (!$guid){$guid = (int) get_input('marketpost');}                          $display .= '$guid: '.$guid.'<br>';
$container_guid = (int) get_input('container_guid');
$parent_guid = (int) get_input('parent_guid');
$title       =       get_input('title');
if (!$title){$title = get_input('markettitle');                              $display .= '$title: '.$title.'<br>';}
/*
$category    =       get_input('marketcategory');
$body        =       get_input('marketbody');
$access      =       get_input('access_id');
$tags        =       get_input('markettags');
*/
$item        =       get_input('item');
$apply       =       get_input('apply');
$upload_guids =      get_input('upload_guids');
$album_guid  = (int) get_input('upload_to_album');                           $display .= '$album_guid: '.$album_guid.'<br>'; 
$unlink      =       get_input('unlink');
if ($upload_guids && !is_array($upload_guids)){$upload_guids = array($upload_guids);}
if ($unlink       && !is_array($unlink)      ){$unlink = array($unlink);}
if (!$title)                                  {$title = $item['title'];      $display .= '$title: '.$title.'<br>';}
$user_guid   =       elgg_get_logged_in_user_guid();
$referrer    =       elgg_get_site_url()."market/view/$guid";
$album       =       get_entity($album_guid);

//	    register_error(elgg_echo('$model_no: '.$model_no));
//	    register_error(elgg_echo('$serial_no: '.$serial_no));
//$display = '$title: '.$title;
//$display = "item[title]: ".$title.'<br>';

elgg_make_sticky_form('market');

// Convert string of tags into a preformatted array
$tagarray = string_to_tag_array($tags);
$error = FALSE;

// Make sure the title isn't blank
if (empty($title)) {
	register_error(elgg_echo("market:blank"));
	$error =  TRUE;
} 
else {
  if ($guid) {
    // editing an existing market item
    $display .= 'editing an existing item...<br>';
    $entity                  = get_entity($guid);
	$parent_item                 = get_entity($parent_guid);
  } 
  else {
    // creating a new market item
    $display .= 'creating a new item...<br>';
    $entity                   = new ElggObject();
    $entity->subtype          = 'market';
    $entity->owner_guid       = $user_guid;
//    $entity->container_guid   = $user_guid;
  }
  if (!$error && elgg_instanceof($entity,'object','market') && $entity->canEdit()) {
    // process custom actions first
	// allows for error checking, relationships etc.
	if (file_exists(dirname(__FILE__) . "/custom/{$entity->marketcategory}/{$level}.php")) {
	 include dirname(__FILE__) . "/custom/{$entity->marketcategory}/{$level}.php";
	}
  //* 2015-03-07 - added by Scott to process 	tags
	// Extract the string of tags from the entity
	$tags = $entity->labels;
//  system_message(elgg_echo($tags));
	
	// Kick the string of tags back to the entity as an array of individual tags
	$entity->labels = string_to_tag_array($tags);
// Process $item[]	
// TODO: Delete empty characteristic names or features saved from earlier 'apply'.  Currently, they are just not displayed.
	foreach ($item as $key => $value) {                                             //$display .= '48 $key: '.$key.'<br>$value: '.$value.'<br>$item[$key]: '.$item[$key].'<br>';
		if ($value == '0' || $value == ''){                                       //$display .= '50 $value: '.$value.'<br>';
			unset($item[$key]);
		}
	  //* 2015-03-07 - added by Scott to process 	tags
		if ($key == 'tags'){
			$item[$key] = string_to_tag_array($value);
		}
		
//      Remove blank characteristics
		if ($key == 'characteristic_names'){
			foreach ($item[$key] as $this_key=>$this_value){                      //$display .= '59 $this_key: '.$this_key.'<br>$this_value: '.$this_value.'<br>';
				if ($this_value == ''){                                           //$display .= '60 $this_value: '.$this_value.'<br>';
					unset($item['characteristic_names'][$this_key]);
					unset($item['characteristic_values'][$this_key]);
				}
			}													                  //$display .= '64 $item[$key]: '.$item[$key].'<br>';
			foreach ($item[$key] as $this_key=>$this_value){                      //$display .= '65 $this_key: '.$this_key.'<br>$this_value: '.$this_value.'<br>';			
			}
		}
		if ($key == 'this_characteristic_names'){
			foreach ($item[$key] as $this_key=>$this_value){
				if ($this_value == ''){                     
					unset($item['this_characteristic_names'][$this_key]);
					unset($item['this_characteristic_values'][$this_key]);
				}
			}												
		}
		if ($key == 'features' || $key == 'this_features'){
			foreach ($item[$key] as $this_key=>$this_value){
				if ($this_value == ''){                     
					unset($item[$key][$this_key]);
				}
			}												
		}
		if ($key == 'images'){
			foreach ($item[$key] as $this_key=>$this_value){
				if ($this_value == 0){                     
					unset($item[$key][$this_key]);
				}
			}												
		}
	}  //@END - foreach ($item as $key => $value)

	$item_images = $item['images']; 
	if (!is_array($item_images)){array($item_images);}
	$item_images = array_unique($item_images);
	foreach ($item_images as $this_key=>$guid){                                   $display .= '$item_images['.$this_key.'] => '.$guid.'<br>';
	}
	foreach ($unlink as $this_key=>$guid){                                        $display .= '$unlink['.$this_key.'] => '.$guid.'<br>';
	}
	if (!empty($unlink)){
		  $remaining = array_diff ($item_images, $unlink);
	}
	else {$remaining = $item_images;}
	
	foreach ($remaining as $this_key=>$guid){                                     $display .= '$remaining['.$this_key.'] => '.$guid.'<br>';
	}
	$item['images'] = $remaining;
	$images         = $remaining; 

	if ($upload_guids) {
	// @TODO - Analyze how to create new album.  Uncomment 'New Album' option on input form.
		
		$metadata = elgg_get_metadata(array(
		'guid' => $album->guid,
		'limit' => 0
			));
	
		// for display only ...
		foreach($upload_guids as $key => $values){                             $display .= '['.$key.']=>'.$values.'<br>';
			foreach ($values as $this_key => $value){                          $display .= '['.$this_key.']=>'.$value.'<br>';
				$upload_file = get_entity($value);
				foreach ($upload_file as $key => $value){                      $display .= '['.$key.']=>'.$value.'<br>';
				}
			}
		}
		
		foreach($upload_guids as $upload_guid){
			$image = get_entity($upload_guid);
			if (!elgg_instanceof($image)) {
				continue;
			}
			$image->container_guid = $album->guid; // in case these were uploaded with filedrop
			
			if (!$image->title) {
				 $image->title = $image->originalfilename;
			}
			$image->access_id = $album->access_id;
	
			foreach ($metadata as $md) {
				$names[] = $md->name;
			}
	
			$names = array_unique($names);
	
			foreach ($names as $name) {
				$image->$name = $album->$name;
			}
	
			if ($image->save()) {                                                 $display .= '177 image saved: '.$upload_guid.'<br>';
				$images[] = $upload_guid;
			}
		}			
	} //END - if ($upload_guids)

	if (!is_array($images)){$images = array($images);}
	$item['images'] = $images; 
	foreach ($item['images'] as $this_key=>$guid){                                   $display .= '184 $item_images['.$this_key.'] => '.$guid.'<br>';
	}
	
// Push processed $item[] to $entity
	foreach ($item as $key => $value) {
		$entity->$key = $value;
		
		// for display only ...
		if (is_array($value)){                                                     //$display .= 'Array: '.$key.'<br>';
			foreach($value as $this_key=>$this_value){                             $display .= '200 ['.$this_key.'] => '.$this_value.'<br>';
			}
			continue;
		}                                                                          //$display .= $key.'=>'.$value.'<br>';
	}
	
  	if (is_array($item)){                                                      $display .= '$item values received ...<br>';
	  	foreach($item as $key => $value){
	  		$entity->$key = $value;                                            $display .= '&nbsp;&nbsp;&nbsp;'.$key.'=>'.$value.'<br>';
			
			// for display only ...
			if (is_array($value)){                                             //$display .= 'Array: '.$key.'<br>';
				foreach($value as $this_key=>$this_value){                     $display .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;215 ['.$this_key.'] => '.$this_value.'<br>';
				}
				continue;
			}                                                                  //$display .= $key.'=>'.$value.'<br>';
	  	}
	  }
	
//	if (count($images) == 1){$entity['images'][0] = $images[0];}                $display .= '222 '.$entity->images.'<br>'; 
	if ($entity->record_stage == 'newborn' || $entity->record_stage == ''){$entity->record_stage = 'defined';}  
	  
	$entity->access_id      = $access;
	  
  	if (!$entity->save()) {
  	    register_error(elgg_echo('market:save:failure'));
	    $error = TRUE;
  	}
  } //@END - if (!$error && elgg_instanceof($entity,'object','market') && $entity->canEdit())

/*  if ($error) {
    if ($guid) {
      forward("market/edit/" . $guid);
    } else {
      forward("market/add");
    }
  }
*/
if (!$error){
  // no errors, so clear the sticky form and save any file attachment

	elgg_clear_sticky_form('market');
/*	//Using filedrop, so images are handled already
	$images = $entity->images;
	if (!is_array($images)){$images = array($images);}
  
		// Now see if we have a file icon
		if ((isset($_FILES['upload'])) && (substr_count($_FILES['upload']['type'],'image/'))) {
// 			$folder                  = "market/";
// 			$filename                = $entity->guid;
			
			$folder                  = 'image/';
			$filename                = uniqid();
			$extension               = ".jpg";
			$prefix                  = $folder.$filename;
			$filehandler             = new ElggFile();
			$filehandler->owner_guid = $entity->owner_guid;
			$filehandler->setFilename($folder.$filename.$extension);                 $display .= 'filename: '.$filehandler->getFilename().'<br>'; 
			$filehandler->open("write");
			$filehandler->write(get_uploaded_file('upload'));
			$filehandler->close();
			$images[] = $filename;
	
			$thumbtiny   = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
			$thumbsmall  = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
			$thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),153,153, true);
			$thumblarge  = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
			if ($thumbtiny) {                                       $display .= '$thumbtiny exists <br>';
	
				$thumb             = new ElggFile();
				$thumb->owner_guid = $entity->owner_guid;
				$thumb->setMimeType('image/jpeg');
				$thumb->setFilename($prefix."tiny.jpg");
				$thumb->open("write");
				$thumb->write($thumbtiny);                          $display .= 'thumbtiny: '.$thumb->getFilename().'<br>';
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
*/
//		if ((isset($_FILES['upload_guids'])) && (substr_count($_FILES['upload_guids']['type'],'image/'))) {
// 			$folder                  = "market/";
// 			$filename                = $entity->guid;
//			foreach ($_FILES['upload_guids']['name'] as $name){
//				$display .= dump($_FILES['upload_guids']);
				//$display .= '['.$key.']=>'.$name.'<br>';
/*				foreach ($name as $this_key => $whatzit){
					$display .= '['.$this_key.']=>'.$whatzit.'<br>';
				}
*///			}
/*			$folder                  = 'image/';
			$filename                = uniqid();
			$extension               = ".jpg";
			$prefix                  = $folder.$filename;
			$filehandler             = new ElggFile();
			$filehandler->owner_guid = $entity->owner_guid;
			$filehandler->setFilename($folder.$filename.$extension);                 $display .= 'filename: '.$filehandler->getFilename().'<br>'; 
			$filehandler->open("write");
			$filehandler->write(get_uploaded_file('upload'));
			$filehandler->close();
			$images[] = $filename;
	
			$thumbtiny   = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
			$thumbsmall  = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
			$thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),153,153, true);
			$thumblarge  = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
			if ($thumbtiny) {                                       $display .= '$thumbtiny exists <br>';
	
				$thumb             = new ElggFile();
				$thumb->owner_guid = $entity->owner_guid;
				$thumb->setMimeType('image/jpeg');
				$thumb->setFilename($prefix."tiny.jpg");
				$thumb->open("write");
				$thumb->write($thumbtiny);                          $display .= 'thumbtiny: '.$thumb->getFilename().'<br>';
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
			}*/
//		}
// 	$entity->images = $images;
//	$entity->save();
		
  $new_h = $h . '/' . $item[$level];
		
		// Success message
		system_message(elgg_echo("market:posted"));
	
		if ($apply){
			// Stay on page
		  system_message("Changes applied");		
		}
		elseif (elgg_view_exists("forms/market/edit/$category")) {
			if (elgg_view_exists("forms/market/edit".$new_h)) {
//		    forward("market/edit_more/{$entity->guid}{$new_h}");
		  }
//		  forward("market/edit_more/{$entity->guid}/$category");
		} else {
		  // Forward to the calling page
		  forward($referrer);
		}
	}
}

register_error($display);
