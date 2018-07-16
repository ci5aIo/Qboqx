<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 *
 * Modified by Kevin Jardine for arckinteractive.com
 */

// Make sure we're logged in
gatekeeper();
$display .= 'DRAFT: Merge with action/edit to correctly process uploaded images<br>';
// Get input data
$guid        = (int) get_input('guid');
if (!$guid){$guid = (int) get_input('marketpost');}        $display .= '$guid: '.$guid.'<br>';
$h           =       get_input('h');
$level       =       get_input('level');
$item        =       get_input('item');
$parent_guid = (int) get_input('parent_guid');
$upload_guids =      get_input('upload_guids', array());
$apply       =       get_input('apply');

elgg_make_sticky_form('market');

$entity = get_entity($guid);

if (elgg_instanceof($entity,'object','market') && $entity->canEdit()) {
	
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
//.
	
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
  }
  
foreach ($item as $key => $value) {
	$entity->$key = $value;
	
	if (is_array($value)){                                                     //$display .= 'Array: '.$key.'<br>';
		foreach($value as $this_key=>$this_value){                             //$display .= '89 '.$this_key.' => '.$this_value.'<br>';
		}
		continue;
	}                                                                          //$display .= $key.'=>'.$value.'<br>';
}
if ($entity->record_stage == 'newborn' || $entity->record_stage == ''){$entity->record_stage = 'defined';}  
if (!$entity->save()) {
	    register_error(elgg_echo('market:save:failure'));
	    $error = TRUE;
	  }

//$new_file = get_entity($upload_guids);
//	  	register_error($new_file->getFilenameOnFilestore());
	  	
//if (!empty($upload_guids)){
	foreach ($upload_guids as $upload){
		$upload_file = get_entity($upload);
		register_error($upload_file->getGUID());
//	}
	

//	register_error($upload);
//	register_error($upload->getFilenameOnFilestore());
}

// no errors, so clear the sticky form and save any file attachment
	elgg_clear_sticky_form('market');
	
// Now see if we have a file icon
	if ((isset($_FILES['upload'])) && (substr_count($_FILES['upload']['type'],'image/'))) {
	//if (((isset($_FILES['upload'])) && (substr_count($_FILES['upload']['type'],'image/'))) || !empty($upload_guids)) {
		if (!empty($upload_guids)){
			$new_file         = get_entity($upload_guids[0]);
			$open_file        = fopen($new_file->getFilenameOnFilestore(), "r");
		}                                                                                          $display .= 'Ready to upload image<br>';
		$filehandler             = new ElggFile();
		$prefix                  = "market/".$entity->guid;
		$filehandler->owner_guid = $entity->owner_guid;
		$filehandler->setFilename($prefix . ".jpg");                                               $display .= 'Filename: '.$filehandler->getFilename().'<br>';
		$filehandler->open("write");                
//		$filehandler->write($open_file);
//		register_error('Dropzone is not working<br>'.$filehandler->getFilenameOnFilestore());
		$filehandler->write(get_uploaded_file('upload'));                                    $display .= 'Wrote upload<br>';
//		$filehandler->write(get_uploaded_file('upload_guids'));
		$filehandler->close();
                                                                                             $display .= 'FilenameOnFilestore: '.$filehandler->getFilenameOnFilestore().'<br>'; 
		$thumbtiny = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true); 
		$thumbsmall = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
		$thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),153,153, true);
		$thumblarge = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
		if ($thumbtiny) {                                       $display .= '$thumbtiny exists <br>';

			$thumb = new ElggFile();
			$thumb->owner_guid = $entity->owner_guid;
			$thumb->setMimeType('image/jpeg');
			$thumb->setFilename($prefix."tiny.jpg");
			$thumb->open("write");
			$thumb->write($thumbtiny);                          $display .= 'Wrote $thumbtiny<br>';
			$thumb->close();          
			$thumb->setFilename($prefix."small.jpg");
			$thumb->open("write");
			$thumb->write($thumbsmall);                          $display .= 'Wrote $thumbsmall<br>';
			$thumb->close();
			$thumb->setFilename($prefix."medium.jpg");
			$thumb->open("write");
			$thumb->write($thumbmedium);                          $display .= 'Wrote $thumbmedium<br>';
			$thumb->close();
			$thumb->setFilename($prefix."large.jpg");
			$thumb->open("write");     
			$thumb->write($thumblarge);                     $display .= 'Wrote $thumblarge<br>';
			$thumb->close();
		}
	}
  }
  
  $new_h = $h . '/' . $item[$level];

//  system_message(elgg_echo('market:edit_more:response'));
register_error($display);

	if ($apply){
		// Stay on page
//	  system_message("Changes applied");
	}
  else {
	  if (elgg_view_exists("forms/market/edit".$new_h)) {
	    forward("market/edit_more/{$entity->guid}{$new_h}");
	  } else {
	    // Forward to the main market page
	    forward(REFERRER);
	    //forward("market");
	  }
	}

