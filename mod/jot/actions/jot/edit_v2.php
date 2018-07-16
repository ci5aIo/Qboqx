<?php
/**
 * Elgg jot Plugin
 * version 02
 */
/*
 * Action for :
     * jot/views/default/forms/transfers/elements/ownership.php
     * jot/views/default/forms/transfers/elements/receipt.php
 * 
 */

// Make sure we're logged in (send us to the front page if not)
gatekeeper();

// Get input data
$guid         = (int) get_input('guid', 0);
$parent_guid  = (int) get_input('parent_guid');
$aspect       =       get_input('aspect', 'receipt');
$apply        =       get_input('apply');
$title        =       get_input('title');
// Receive jot data
$jot_input     =      get_input('jot');
// Receive line items
$items        =       get_input('item');
$item_type    =       get_input('item_type');
$referrer     =       get_input('referrer');               //$display .= '22 $referrer: '.$referrer.'<br>';

$relationship = $item_type;

$transfer_type = get_entity($guid)->getSubtype();

if ($transfer_type == 'market' || $transfer_type == 'item'){
    // passed an entity guid to create a new receipt
    $guid          = 0;
    $existing_item = true;
}

$jot          = get_entity($guid);
$parent_item  = get_entity($parent_guid);
$presentation = get_input('presentation');
	
if (elgg_entity_exists($jot)) {
	$subtype     = $jot->getSubtype();
	$referrer    = "jot/view/$guid";
	$exists      = true;
}
else {
	$subtype     = get_input('subtype', 'transfer');
	$exists      = false;
}                                                           //$display .= '35 $exists: '.$exists.'<br>';

if (!$exists) { // create a new jot item
    $user_guid           = elgg_get_logged_in_user_guid();
    $access_id           = get_default_access();
    $jot                 = new ElggObject();
    $jot->subtype        = $subtype;
    $jot->owner_guid     = $user_guid;
    $jot->container_guid = $user_guid;
	$jot->access_id      = $access_id;
    $jot->save();
}

/*
$display .= '48 Input<br>';
$display .= '49 $referrer: '.$referrer.'<br>$guid: '.$guid.'<br>'; 
if ($subtype == 'transfer'){
	$variables      = elgg_get_config("{$subtype}_{$aspect}");
}
else {
	$variables = elgg_get_config("{$subtype}s");	
}

// Process jot 
$input = array();
foreach ($variables as $name => $form_type) {
	$input[$name] = get_input($name);
	if ($name == 'title') {
		$input[$name] = strip_tags($input[$name]);
	}
	if ($form_type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}*/

foreach ($jot_input as $name => $value){
    $jot->$name = $value;                                                   //$display .= '71 $jot['.$name.']='.$jot[$name].'<br>'; 
                                                                            //$display .= '72 $jot_input['.$name.'] = '.$jot_input[$name].'<br>';
}

$title = $input['title'];
if ($jot_input){
    $title = $jot_input['title'];
}
		        
elgg_make_sticky_form('jot');

// Convert string of tags into a preformatted array
//$tagarray = string_to_tag_array($tags);
$error = FALSE;

// Make sure the title isn't blank
if (empty($title)) {
	register_error(elgg_echo("jot:blank"));
	$error =  TRUE;
} else {


  if (!$error) {
	  $jot->title       = $title;
	  $jot->description = $body;
// Process input
foreach($input as $key => $value){                               //$display .= '94 $input: '.$key.'=>'.$value.'<br>';
		if ($key == 'tags'){
		  $jot->$key = string_to_tag_array($value);
	      }
// Save the $input values
	    $jot->$key = $value;
	  }

// Process line items
//   Pivot Line Items
	  foreach ($items as $key=>$values){                          //$display .= '104 $key: '.$key.'<br>';
	  	foreach($values as $key_value=>$value){                   //$display .= '105 $values: '.$key_value.'=>'.$value.'<br>';
	  		$line_items[$key_value][$key] = $value;               //$display .= '110 $line_items[$key_value][$key]: '.$line_items[$key_value].'['.$line_items[$key_value][$key].']<br>';
		  }
	  }
//   Remove blank Line Items
	  foreach ($line_items as $key=>$values){                     //$display .= '111 $line_item: '.$key.'<br>';
	  	foreach($values as $key_value=>$value){                   //$display .= '111 $values: '.$key_value.'=>'.$value.'<br>';
			if ($key_value == 'title' && $value == ''){
				unset($line_items[$key]);
			}													  //$display .= '114 $line_items[$key]: '.$key_value.'=>'.$line_items[$key][$key_value].'<br>';
		 }                                                         
	  }

// @TODO: Display values for verification
	  $item_qty=0;
	  foreach ($line_items as $key=>$values){                     //$display .= '121 $line_item: '.$key.'<br>';
	  	$item_qty += $values['qty'];

	  	foreach($values as $key_value=>$value){                   $display .= '124 $line_items[$key]: '.$key_value.'=>'.$line_items[$key][$key_value].'<br>';
	  	}
	  }
	  foreach ($line_items as $line => $values) {
	  	unset($line_item_guid);
	  	unset($linked_item);
	  	$line_item_guid = $values['guid'];                            $display .= '130 $line_item_guid: '.$line_item_guid.'<br>';
	  	unset($line_item);
  		$create_item = false;
  		$line_item = get_entity($line_item_guid);
  		if (empty($line_item)){                                       $display .= '133 empty($line_item)<br>';
  		}
//@EDIT 2016-04-22 - SAJ - begin
  		if ($line_item->retain_line_label == 'create'){
	  		$create_item = true;
  		}
//@EDIT 2016-04-22 - SAJ - end
  		if ($line_item->retain_line_label == 'link'){                 $display .= '142 retain_line_label = '.$line_item->retain_line_label.'<br>$line_item->title: '.$line_item->title.'<br>';
	  		$link_item = true;
            $linked_item = elgg_get_entities_from_relationship(array(
    			'type'                 => 'object',
    			'relationship'         => $item_type,
    			'relationship_guid'    => $line_item_guid,
    			'inverse_relationship' => true,
    			'limit'                => false,
    		));
            if (empty($linked_item)){
                $guid_one     = $item_guid;            // item
        		$relationship = 'receipt_item';
        		$guid_two     = $line_item->getGUID(); // receipt item
                add_entity_relationship($guid_one, $relationship, $guid_two);
            }
  		}                                            
//@EDIT 2016-11-09 - SAJ
        if (!empty($line_item->item_guid)){
	  		$link_item = true;
	  		$linked_item = get_entity($line_item->item_guid);
  		}                                                             $display .= '164 retain_line_label = '.$line_item->retain_line_label.'<br>$line_item->title: '.$line_item->title.'<br>';
//        
  		if ($line_item && ($create_item || $link_item)){              $display .= '166 $line_item && ($create_item = '.$create_item.' || $link_item = '.$link_item.')<br>';
  			if (empty($linked_item) && $create_item){                 $display .= '167 empty($linked_item) && $create_item<br>';		
				$linked_item = new ElggObject();
				$linked_item->subtype = 'market';
				$linked_item->title   = $line_item->title;
				if ($linked_item->save()){
					// link new item to line item
					add_entity_relationship($linked_item->getguid(), $relationship, $line_item_guid);
					// link new item to receipt item
					add_entity_relationship($guid, "{$subtype}_{$aspect}", $linked_item->getguid());
					$line_item->retain_line_label = 'no';              
					$line_item->item_guid         = $linked_item->getGUID();
					$line_item->save();                                $display .= '162 linked_item saved<br>'; $display .= '162 retain_line_label = '.$line_item->retain_line_label.'<br>';
				}
			}
			
//          Associate line item with item
			if ($link_item){                                           $display .= '167 Associate line item with item<br>';
				$guid_one     = $line_item_guid;
				$relationship = strtolower($line_item->link_type);
				$guid_two     = $line_item->item_guid;                    $display .= '170 $guid_one: '.$guid_one.', $relationship: '.$relationship.', $guid_two: '.$guid_two.'<br>';
				if ($relationship == 'part' || $relationship == 'supply'){
					if (check_entity_relationship($guid_one, $relationship, $guid_two) == false){
						add_entity_relationship($guid_one, $relationship, $guid_two);					
					}
				}
				if ($relationship == 'warranty'){
					$linked_item->$relationship = $line_item->title;   $display .= '177 $linked_item->'.$relationship.' = '.$linked_item->$relationship.'<br>';
				}
			}
	  	}
		
		if (empty($line_item)){                                        $display .= '182 empty($line_item)<br>';
			$line_item = new ElggObject();
			$line_item->subtype = $item_type;
		}
		$line_item->sort_order = $line + 1;

		foreach($values as $dimension => $value){
			$line_item->$dimension = $value;
		}
	  
		if ($line_item->unpack == 1                    && 
		    $line_item->qty >= 1                        ){           $display .= '193 $line_item->unpack = true<br>'; $display .= '191 $linked_item->guid: '.$line_item->item_guid.'<br>';
		   /* Count the linked items
		    * If there is exactly 1 linked item, this becomes the box to hold the new items
		    * If there is more than 1 linked item, get the container_guid of the first item 
		    * Set the new items to unpack as the difference between what exists and what is requested
		    * Create new items 
			    * Set the container_guid of the new items to the $linked_item guid
		    */
		    
			$pieces = elgg_get_entities(array(                     // Identify the pieces
							'type' => 'object',
							'subtypes' => array('market', 'item'),
							'wheres' => array(
								"e.container_guid = {$linked_item[0]->getGUID()}",
							),
						));                                        $display .= '208 $linked_item[0]->getGUID() '.$linked_item[0]->getGUID().'<br>';
			
		    $linked_items = count($linked_item);                   $display .= '210 $linked_items: '.$linked_items.'<br>';
			$piece_count   = count($pieces);                       $display .= '211 $piece_count: '.$piece_count.'<br>';
			if ($linked_items == 1){
				$container_guid = $linked_item[0]->getGUID();
			}
			else {                                                 $display .= '215 $container_guid: '.$container_guid.'<br>';
				$container_guid = elgg_get_logged_in_user_guid ();
			}
			$n = 0;
			for ($i = $n+1; $i <= ($line_item->qty - $piece_count); $i++) { //Set the new items to unpack as the difference between what exists and what is requested
				$new_item = new ElggObject();
				$new_item->subtype = 'item';                        $display .= '221 $new_item->subtype: '.$new_item->subtype.'<br>';
				$new_item->title   = $line_item->title;             $display .= '222 $new_item->title: '.$new_item->title.'<br>';
				$new_item->container_guid = $container_guid;        $display .= '223 $new_item->container_guid: '.$new_item->container_guid.'<br>';
				$new_item->save();
				add_entity_relationship($new_item->getguid(), 'receipt_item', $jot->getguid());
			}
			
		}
		
//      Store the current value of the line item
		$line_item->total = $line_item->qty * $line_item->cost;
		if ($line_item->distribute_freight == 1){
			$line_item->shipping_cost = $jot->shipping_cost * ($line_item->qty/$item_qty);
		}
		if ($line_item->taxable == 1){
			$line_item->sales_tax = $jot->sales_tax * ($line_item->qty/$item_qty);
		}
		
		if ($line_item->save()){                                    $display .= '239 $line_item[item_guid]: '.$line_item['item_guid'].'<br>';
	 		$subtotal                         += $line_item->total;
		}
		else {
			register_error(elgg_echo('jot:save:failure'));
		}
//      Associate line item with jot
		if (check_entity_relationship($line_item->getguid(), $relationship, $jot->getguid()) == false){
			add_entity_relationship($line_item->getguid(), $relationship, $jot->getguid());
		}
 	  } //END foreach ($line_items as $line => $values)
 	  
 	  $jot->subtotal = $subtotal;
 	  $jot->total_cost  = $jot->subtotal + $jot->shipping_cost + $jot->sales_tax; 
 	  if (!$jot->save()) {
 	  	register_error(elgg_echo('jot:save:failure'));
 	  	$error = TRUE;
 	  }
  }

  if (!$error) {
// no errors, so clear the sticky form and save any file attachment
	elgg_clear_sticky_form('jot');

// Now see if we have a file icon
	if ((isset($_FILES['upload'])) && (substr_count($_FILES['upload']['type'],'image/'))) {

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
	if (elgg_view_exists("forms/jot/edit/$category")) {
		// Forward to the next form in the wizard sequence
		forward("jot/edit_more/{$jot->guid}/$category");
	} 
	if (!$exists){
		$action_type  = 'create';
		$subject_guid = $jot->owner_guid;
		$object_guid  = $jot->guid;
		$target_guid  = $jot->container_guid;                               $display .= '314 $type: '.$type.'<br>$subtype: '.$subtype.'<br>';
		elgg_create_river_item(array(
			'action_type'   => $action_type,
			'view'          => 'river/object/jot/'.$action_type,
			'subject_guid'  => $subject_guid,
			'object_guid'   => $object_guid,
			'target_guid'   => $target_guid,
		));
	
//		register_error($display);
		if ($presentation == 'box'){
		    forward($referrer);
		}
		else {
		    forward("jot/edit/{$jot->getGUID()}/$aspect");
		}
	}
	if ($apply){
	    if ($exists){
	        if ($existing_item){
	            forward("jot/edit/$jot->guid/receipt");
	        }
	    }
	    system_message("Changes applied");
	}
	else {
// Forward to the referring page
	  system_message(elgg_echo("jot:posted"));                  
	  	$action_type  = 'update';
		$subject_guid = $jot->owner_guid;
		$object_guid  = $jot->guid;
		$target_guid  = $jot->container_guid;                     
		$options      = array(
			'view'          => 'river/object/jot/'.$action_type,
			'action_type'   => $action_type,
			'subject_guid'  => $subject_guid,
			'object_guid'   => $object_guid,
			'target_guid'   => $target_guid,
		);                                  
	  elgg_create_river_item($options);
//  	  register_error($display);
	  forward($referrer);
		}
  }
}
eof:
register_error($display);
