<?php
/**
 * Elgg jot Plugin
 *  version 3
 * @package jot
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
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
$guid         = (int) get_input('guid'); if ($guid == 0 ){unset($guid);}  $display .= '19 $guid: '.$guid.'<br>';
$parent_guid  = (int) get_input('parent_guid');
$apply        =       get_input('apply');                           //$display .= '$apply: '.$apply.'<br>';goto eof;
$title        =       get_input('title');
$aspect       =       get_input('aspect');                          //$display .= '23 $aspect: '.$aspect.'<br>'; //goto eof;
$action       =       get_input('action');
$this_section =       get_input('this_section');
// Receive jot data
$jot_input    =      get_input('jot');       //if(empty($jot_input)){unset($jot_input); $display .= 'empty $jot_input<br>';} $display .= '25 $jot_input[title]'.$jot_input['title'].'<br>';
$jot_snapshot =      $jot_input['snapshot'];                       unset($jot_input['snapshot']); 
$aspect       =      $aspect ?: $jot_input['aspect'];
if (empty($aspect)){
	$aspect   = 'receipt';
	$jot_input['aspect']=$aspect;
}                                                                   $display .= '33 $aspect: '.$aspect.'<br>';
// Receive line items
$items        =       get_input('line_item');   if(empty($items)){       unset($items);}    //$display .= '32 '.print_r($items, true).'<br>';
$return_items =       get_input('return_item'); if(empty($return_items)){unset($return_items);}
$item_type    =       get_input('item_type');   if(empty($item_type)){   unset($item_type);}
$referrer     =       get_input('referrer');                                                //$display .= '35 $referrer: '.$referrer.'<br>';
$user_guid    =       elgg_get_logged_in_user_guid();
$access_id    =       get_default_access();

$relationship = $item_type;
$jot          = get_entity($guid);
$parent_item  = get_entity($parent_guid);
$presentation = get_input('presentation');
$exists       = false;
if (elgg_entity_exists($jot)) {
	$subtype     = $jot->getSubtype();
	$referrer    = $referrer ?: "jot/view/$guid";
	$exists      = true;
}
else {
	$subtype     = get_input('subtype') ?: $jot_input['subtype'] ?: 'transfer';
}
                                                                                        $display .= '52 $exists: '.$exists.'<br>52 $aspect: '.$aspect.'<br>52$subtype: '.$subtype.'<br>52$action: '.$action.'<br>';
//goto switch_subtype;   
foreach ($jot_input as $name => $value){
    //if (empty($value)){unset($jot_input[$name]); continue;}                 //$display .= '55 $jot_input['.$name.']='.$value.'<br>';
    if (is_array($value)){                                                    //$display .= '56 $jot_input['.$name.']: '.$value.'<br>';
        
    // Process experience        
        if ($subtype == 'experience'){
            if ($name == 'instruction' && $aspect == 'instruction'){
                $instructions = $value;
            }
            if ($name == 'observation' && $aspect == 'observation'){
                $observations = $value;                                       //$display .= '64 $value: '.print_r($value, false).'<br>';
            }
            if ($name == 'event'       && $aspect == 'event'){
                $events       = $value;
            }
            if ($name == 'project'     && $aspect == 'project'){
                $project      = $value;
            }
        }
/*        foreach($value as $key1=>$value1){                                    //$display .= '57 $jot_input['.$name.']['.$key1.']='.$value1.'<br>';
            //if (empty($value1)){unset($jot_input[$name][$key1]); continue;}   //$display .= '58 $jot_input['.$name.']['.$key1.']='.$value1.'<br>';
            if ($name == 'ghost_assets' || 
                $name == 'process' || 
                $name == 'event' || 
                $name == 'observation' || 
                $name == 'project'){
                foreach ($value1 as $key2=>$value2){
                    //if (empty($value2)){unset($jot_input[$name][$key1][$key2]); continue;}    //$display .= '65 $jot_input['.$name.']['.$key1.']['.$key2.'] = '.$jot_input[$name][$key1][$key2].'<br>';
                    if ($name == 'ghost_assets'){
                        $ghost_assets[$key2][$key1]=$value2;
                    }
                    if ($name == 'instruction' && $aspect_type == 'instruction'){
                        $instructions[$key2][$key1]=$value2;
                    }
                    if ($name == 'observation' && $aspect_type == 'observation'){
                        $observations[$key2][$key1]=$value2;
                    }
                    if ($name == 'event' && $aspect_type == 'event'){
                        $events[$key2][$key1]=$value2;
                    }
                    if ($name == 'project' && $aspect_type == 'project'){
                        $project[$key2][$key1]=$value2;
                    }
                }
            }
        }*/
    }
    // Process experience        
        if ($subtype == 'experience'){
            if ($name == 'instruction') {                                      
                if (!empty($instructions)){$jot_input[$name] = $instructions;}
                else                      {unset ($jot_input[$name]);}}
            if ($name == 'observation')   {
                if (!empty($observations)){$jot_input[$name] = $observations;}
                else                      {unset ($jot_input[$name]);}}
            if ($name == 'event')  {
                if (!empty($events))       {$jot_input[$name] = $events;}
                else                      {unset ($jot_input[$name]);}}
            if ($name == 'project')  {
                if (!empty($project))     {$jot_input[$name] = $project;}
                else                      {unset ($jot_input[$name]);}}            
            if ($name == 'ghost_assets'){
                if (!empty($ghost_assets)){$jot_input[$name] = $ghost_assets;}
                else                      {unset ($jot_input[$name]);}}
            if ($name == 'assets'    || 
                $name == 'documents' || 
                $name == 'images')    {
                if (!empty($value))       {$jot_input[$name] = is_array($jot_input[$name]) ? $jot_input[$name] : array($jot_input[$name]);}
                else                      {unset ($jot_input[$name]);}}
        }
}                                                                       //$display .= '124 $instructions<br>'.print_r($instructions, true).'<br>';
/*
    // Process experience                          
    if ($aspect == 'experience'){
        if ($name == 'instruction' && $aspect_type == 'instruction'){
            $instructions = $value;
        }
        if ($name == 'observation' && $aspect_type == 'observation'){
            $observations = $value;
        }
        if ($name == 'event' && $aspect_type == 'event'){
            $event = $value;
        }
        if ($name == 'project' && $aspect_type == 'project'){
            $project = $value;
        }
    }
*/
                                                           //$display .= '142 $jot_input[instruction]<br>'.print_r($jot_input['instruction'], true).'<br>'; //goto eof;
if (!$exists) { // create a new jot item
    $jot                 = new ElggObject();
    $jot->subtype        = $subtype;
    $jot->container_guid = elgg_get_logged_in_user_guid();
    $jot->owner_guid     = elgg_get_logged_in_user_guid();
    if ($jot->save()){
    	$guid            = $jot->getGUID();}                //$display .= '153 $jot:<br>'.print_r($jot, true).'<br>';//goto eof;
    if ($aspect == 'nothing'){
        $jot->aspect     = $subtype;}
    else {
        $jot->aspect     = $aspect;}
	$jot->access_id      = $access_id;                       //$display .= '61 $jot->merchant = '.$jot->merchant.'<br>'; //goto eof;
    // Received from sending form as $jot_input ...
//     $jot->owner_guid     = $user_guid;
//     $jot->container_guid = $user_guid;
}

if ($jot_input['container_guid'] == $jot_input['guid']){     // A jot should not be its own container.  Clean-up existing.
	$jot_input['container_guid'] = $jot->getOwnerGUID();
}                                                            //$display .= '165 $jot_input:<br>'.print_r($jot_input, true).'<br>';                                                           //$display .= '$jot<br>'.print_r($jot, true).'<br>';
if ($exists && $aspect == 'receive'){
	$jot              = get_entity($jot_input['container_guid']);         // A receive item extends the receipt.
}

foreach ($jot_input as $name => $value){
    if (is_array($value) || empty($value)){continue;}
    // Extract shipment receipt data.
    if ($name == 'received_moment' ||
    	$name == 'packing_list_no') {continue;}
    $jot->$name = $value;                                                  $display .= '175 $jot->'.$name." = ".$value."<br>"; 
    // for display only ...
		if (is_array($value)){                                             
			foreach($value as $this_key=>$this_value){                     $display .= '178 '.$name.'['.$this_key.'] => '.$this_value.'<br>';
			    if (is_array($this_value)){
			        foreach ($this_value as $key2=>$value2){               $display .= '180 '.$this_key.'['.$key2.'] => '.$value2.'<br>';
        			    if (is_array($value2)){
        			        foreach ($value2 as $key3=>$value3){           $display .= '182 '.$key2.'['.$key3.'] => '.$value3.'<br>';
        			        }
        			    }
			        }
			    }
			}
		}
}
           
//goto eof;
//goto switch_subtype;
// Save only the metadata name/value pairs received from $jot_input.  Further processing of nested arrays occurrs in the appropriate aspect section below.
// Don't save if this is a shipment receipt.  
if($action != 'receive'){
	$jot->save();}
//goto eof;

if ($jot_input){
    $title = $jot_input['title'];
}
		        
elgg_make_sticky_form('jot');
$error = FALSE;

// Make sure the title isn't blank
if (empty($title)) {
	register_error(elgg_echo("jot:blank"));
	$error =  TRUE;
} else {

if ($error){goto eof;}
switch_subtype:
Switch ($subtype){
    case 'transfer':
        Switch ($aspect){
    /****************************************
     * $subtype = 'transfer'
     * $aspect = 'receipt'                   *****************************************************************************
     ****************************************/
            case 'receipt':
            	switch ($action){
		    /****************************************
             * $subtype = 'transfer'
		     * $aspect = 'receipt'                    *****************************************************************************
		     * $action = 'receive' (shipment receipt) 
		     ****************************************/
            		case 'receive':
		                if (empty($items)){break;}                                                  // Do nothing if nothing received.
		                unset($ordered_qty, $received_qty);
		                $ordered_qty = 0;
		                $received_qty = 0;
							                
						$shipment_receipt                  = new ElggObject();                       // Create a new shipment receipt
						$shipment_receipt->subtype         = $subtype;
						$shipment_receipt->container_guid  = $jot->getGUID();                        // Connect the shipment receipt to the sales receipt
						$shipment_receipt->title           = 'Shipment Receipt - '.$jot_input['received_moment'];
						$shipment_receipt->moment          = $jot_input['received_moment'];
						$shipment_receipt->packing_list_no = $jot_input['packing_list_no'];
						$shipment_receipt->aspect          = 'receive';
						$shipment_receipt->space           = 'transfer';
						$shipment_receipt->save();                                                 // Save shipment receipt
		               
	                	foreach($items as $key=>$line_item){
	                		$line_item = (object) $line_item;
	                		$receipt_item = get_entity($line_item->guid);
	                		$ordered_qty  += $receipt_item->qty;	                			
	                		unset($options, $fulfilled_qty, $receive_item);
	                		$fulfilled_qty = 0;
	                		if (empty($line_item->received) && 
	                			empty($line_item->recd_qty) && 
	                			empty($line_item->bo_qty)){continue;}
	                		$receive_item                 = new ElggObject();
	                		$receive_item->subtype        = 'line_item';
	                		$receive_item->container_guid = $shipment_receipt->getGUID();
	                		$receive_item->title          = $receipt_item->title;
	                		$receive_item->recd_qty       = $line_item->recd_qty;
	                		$receive_item->bo_qty         = $line_item->bo_qty;
	                		$receive_item->save();
                            /************/
	                		add_entity_relationship ($receive_item->getGUID(), 
	                				                 'receive_item', 
	                				                 $receipt_item->getGUID());                   // Connect the receive line item to the receipt item
                            /************/                            
                            $options = ['type'              => 'object',
	                				    'subtypes'          => ['market', 'item'],
                			            'relationship'      => 'receipt_item',
	                    	            'relationship_guid' => $line_item->item_guid];
	                		$linked_item_entity = elgg_get_entities_from_relationship($options)[0];      // $display .= '257'.print_r($linked_item_entity, true);
	                		if (!empty($linked_item_entity)){                                            // Line item linked to an item.
		                		$linked_item_entity->qty += $line_item->recd_qty;                       // Update the inventory count of the linked item entity with the quantity received
		                		$linked_item_entity->save();	                		                 // Save the existing receipt line item
	                		}
	                		/************/
                            $receipt_item->recd_qty    += $receive_item->recd_qty;                       $display.='261 $line_item->recd_qty='.$line_item->recd_qty.'<br>';// Add received qty to the existing receipt line item
                            $fulfilled_qty              = $receipt_item->recd_qty;                       // Update fulfillment proportion of the receipt line item
                            // Update fulfillment proportion of the sales receipt 
		                	switch (true){
	                     		case ($receipt_item->qty == $receipt_item->recd_qty && $receipt_item->qty > 0):
	                     	  		$receipt_item->status = 'Received'; //received
	                     	  		break;
	                     	  	case ($receipt_item->qty > $receipt_item->recd_qty && $receipt_item->recd_qty > 0):
	                     	  		$receipt_item->status = 'Partial'; //partial
	                     	  		break;
	                     	  	case ($fulfilled_qty == 0 || empty($fulfilled_qty)):
	                     	  		$receipt_item->status = 'Ordered'; // ordered
	                     	  		break;
	                     	}
	                     	// Update fulfillment status of the receipt line item [o(rdered), p(artial), R(eceived)]
	                		$receipt_item->save();
	                     	$receive_item->save();
	                	}                                                                           //Collect all shipment receipts
	                	$shipment_receipts = elgg_get_entities([
			                 'type'           => 'object',
	                		 'subtype'        => 'transfer',
	                		 'container_guid' => $jot->getGUID(),
			                 'limit'          => false]);		                               //Extract line items from each shipment receipt
		            	foreach($shipment_receipts as $key=>$shipment_receipt){
		                		unset($shipment_qty);
		                		$shipment_moment[] = $shipment_receipt->moment;
			                	$receive_items = elgg_get_entities([
			                                        'type'                 => 'object',
			                						'subtype'              => 'line_item',
			                                        'container_guid'       => $shipment_receipt->getGUID(),
				                				    'limit'                => false]);
			                	foreach ($receive_items as $key=>$line_item){                        $display .= '303 $line_item[guid] = '.$line_item->guid.'<br>';
			                		$shipment_qty += $line_item->recd_qty;                           $display .= '304 $line_item->recd_qty = '.$line_item->recd_qty.'<br>';
			                	}
			                	$shipment_receipt->pieces_received = $shipment_qty;
			                	$shipment_receipt->save();
			                	$received_qty += $shipment_qty;
				            }
	                	$jot->pieces_received= $received_qty;
                     	// Set the status
                     	switch (true){
                     		case ($ordered_qty == $received_qty && $ordered_qty > 0):
                     	  		$jot->status = 'Received';
                     	  		$jot->received = 1;
                     	  		break;
                     	  	case ($ordered_qty > $received_qty && $received_qty > 0):
                     	  		$jot->status = 'Partial';
                     	  		break;
                     	  	case ($received_qty == 0):
                     	  		$jot->status = 'Ordered';
                     	  		break;
                     	}
                        $jot->save();
            			break;
		    /****************************************
             * $subtype = 'transfer'
		     * $aspect = 'receipt' (sales receipt)   *****************************************************************************
		     * $action = 'receipt' (default)         
		     ****************************************/
            		case 'receipt':
            		default:
//                  $jot->title          = $jot_input['title'];
                 $preordered = $jot->preorder_flag;
                 if (elgg_entity_exists($guid)){
                  $line_items_existing = array_merge(
                                         elgg_get_entities_from_metadata(array(
                                                        'type'              => 'object',
		                                         		'subtype'           => 'receipt_item',
                                                        'container_guid'    => $guid,
                                                        'limit'             => 0,
                                                  )), 
                                         elgg_get_entities_from_relationship(array(
                                    		'type'                 => 'object',
                                    		'relationship'         => 'receipt_item',
                                    		'relationship_guid'    => $guid,
                                    		'inverse_relationship' => true,
                                    		'limit'                => false,
                                    	)));
                 }                                  
                if (!empty($line_items_existing)){
                        foreach($line_items_existing as $existing){                $display .= '225 $line_items_existing_guids[guid]: '.$existing['guid'].'<br>';
                            $line_items_existing_guids[] = $existing->guid;
                        }
                }
                $n=0;
                if (!empty($items)){
                	foreach($items as $key=>$value){
                		if (in_array($key, $line_items_existing_guids)){
                			$current_line_items[$n]=$value;                         $display .= '234 $current_line_items['.$n.'] = '.$current_line_items[$n].'<br>';
                			$n++;
                			continue;
                		}
                		else {
                			$new_line_items[] = $value;
                		}
                	}
               }
               if (!empty($new_line_items)){
	                foreach($new_line_items as $key=>$value){
	                    foreach($value as $key1=>$value1){                               $display .= '246 $new_line_items['.$key.']['.$key1.']: '.$value1.'<br>';
	                    	if ($key1  == 'new_line'){continue;}
	                    	if (empty($value1))      {continue;}
	                    	$current_line_items[$n][$key1]=$value1;                     $display .= '249 $current_line_items['.$n.']['.$key1.'] = '.$current_line_items[$n][$key1].'<br>';
	                    	foreach($value1 as $key2=>$value2){                         $display .= '250 $value2: '.$key1.'['.$key2.']['.$value1.']<br>';
	                    		if (empty($value2)){
	                    			unset($new_line_items[$key][$key1][$key2]);
	                                continue;
	                            }
	                            $current_line_items[$n][$key1]=$value2;              $display .= '256 $current_line_items['.$n.']['.$key1.'] = '.$current_line_items[$n][$key1].'<br>';
	                        }
	                    }
	                    $n++;
	               }
               }
               if (!empty($current_line_items)){
                    foreach ($current_line_items as $key=>$value){                   $display .= '263 $current_line_items['.$key.'] = '.$value.'<br>';
	                    if (empty($value['title'])){
	                    	// Selecting an item from the shelf gives the title of the item to the receipt item when no alternate title is provided 
                        	if ($current_line_items[$key]['retain_line_label']=='no' && isset($current_line_items[$key]['item_guid'])){
                        	$current_line_items[$key]['title'] = get_entity($current_line_items[$key]['item_guid'])->title;	
	                        }
	                        else {
	                            unset($current_line_items[$key]);
	                            continue;
	                        }
                        }                                                            $display .= '273 $current_line_items['.$key.'][title]: '.$current_line_items[$key]['title'].'<br>';
                        if (empty($value['qty'])){
                        	$current_line_items[$key]['qty'] = 1;
                        }
                        if ($value['link_type'] == 'select ...'){
                            unset($current_line_items[$key]['link_type']);
                        }
                        if (empty($value['show_on_timeline'])){
                            unset($current_line_items[$key]['timeline_label']);
                        }
                        if (empty($value['add_cost_to_que'])){
                            unset($current_line_items[$key]['que_contribution']);
                        }
                    }                                                                $display .= '286 '.print_r($current_line_items, true).'<br>';
               }
//goto eof;
				if (!empty($current_line_items)){
                    foreach ($current_line_items as $key=>$value){
                        if ($value['guid'] == ''){continue;}
                        $current_line_items_guids[] = $value['guid'];
                    }
                    // Delete residual metadata left over from previous edit
                    foreach ($current_line_items as $current_line_item){
                        unset ($existing_line_item_2);
                        $existing_line_item_2 = get_entity($current_line_item['guid']);
                        if (!empty($existing_line_item_2)){
                            if (!empty($existing_line_item_2->item_guid) && empty($current_line_item['item_guid'])){
                                $missing_metadata['item_guid'] = true;
                            }
                            if ($missing_metadata){
                                foreach($missing_metadata as $key=>$value){
                                elgg_delete_metadata(array(
            						'guid'          => $existing_line_item_2->getGUID(),
            						'metadata_name' => $key,
            					));
                                }
                            }
                        }
                    }
                }
        
                if (!empty($current_line_items_guids)){
                    $flipped_current_guids = array_flip($current_line_items_guids);
                }
                if (!empty($line_items_existing_guids)){
                    foreach($line_items_existing_guids as $key=>$existing_guid){
                        if (isset($flipped_current_guids[$existing_guid])){
                            continue;
                        }
                        else {
                            if (elgg_entity_exists($existing_guid)){
                                $existing_line_item = get_entity($existing_guid);         $display .= '312 $existing_line_item->guid:'.$existing_line_item->guid.'<br>';
                                $existing_line_item->delete();
                            }
                        }
                    }
                }
//goto eof;                // Process input
                if (isset($input)){
                    foreach($input as $key => $value){                               //$display .= '94 $input: '.$key.'=>'.$value.'<br>';
                    		if ($key == 'tags'){
                    		  $jot->$key = string_to_tag_array($value);
                    	    }
                    	    else {
                    	        $jot->$key = $value;
                    	    }
                    	  }
                }
                if (!empty($jot_input)){                                                  $display .= '329 $jot_input[merchant]'.$jot_input['merchant'].'<br>';
                    foreach($jot_input as $key=>$value){                          //$display .= '121 $jot_input['.$key.']: '.$jot_input[$key].'<br>';
                        if (empty($value)){
                            unset ($jot_input[$key]);
                            continue;
                        }                                                         //$display .= '125 $jot_input['.$key.']: '.$jot_input[$key].'<br>';
                        if ($key == 'merchant'){
                            if (is_array($jot_input['merchant']) && isset($jot_input['merchant'][0])){
                                // Merchant was a selection from the group picker
                                $merchant = $jot_input['merchant'][0];
                            }
                            else {
                                if (isset($jot_input['merchant'])){
                                // Merchant was not selected from the group picker
                                    // Try to find the merchant
                                        // Find an exact match
                                        // If exact match found, capture group entity
                                        elgg_load_library('elgg:market');
                                        $merchant_group = quebx_get_group_by_title(array('name'=>$jot_input['merchant']));
                                        // Find all merchants
                                        
                                        // Test each merchants name for a match to the input name
                                        
                                        // If exact match found, capture group entity and break loop
                                        
                                        // If close match found ... do what??
                                    
                                    // If merchant found, use merchant guid
                                        if ($merchant_group){
                                            $merchant = $merchant_group->getGUID();
                                        }
                                    // If merchant not found, create new merchant
/* @EDIT - 2018-03-15 - SAJ - Determine whether it is approriate to create new merchants on the fly.
 *  
 */                                       else {
                                            $options = array('group_name'=>$jot_input['merchant'],
                                                             'group_type'=>'merchant',
                                                             'aspect'    =>'supplier',
                                                             'membership'=> ACCESS_PUBLIC,
                                            );
                                            $merchant_group = quebx_create_group($options);                                            
                                        }

                                    $merchant = $merchant_group->getGUID();          //$display .= '132 $merchant:'.$merchant.'<br>135 $jot_input[merchant]:'.$jot_input['merchant'].'<br>';
                                }
                            }
                            if (isset($merchant)){$jot->$key = $merchant;}       //$display .= '135 $merchant:'.$merchant.'<br>135 $jot->merchant:'.$jot->merchant.'<br>';
                        }
                        else {
                            $jot->$key = $value;
                        }
                    }
                }
                  // Find and remove empty fields that were previously filled.
                	if (isset($jot_snapshot)){
                	    foreach ($jot_snapshot as $key => $value){
                	        if (isset($jot_snapshot[$key]) && empty($jot_input[$key])){
                	            unset($jot->$key);
                	        }
                	    }
                	}
                	
                //foreach ($jot_input as $key=>$value){                             $display .= '151 '.$key.'=>'.$value.'<br>';} //goto eof;

                // Process line items
                if (!empty($items)){
                //   Pivot Line Items
/*                	  foreach ($items as $key=>$values){                          //$display .= '104 $key: '.$key.'<br>';
                	  	foreach($values as $key_value=>$value){                   //$display .= '105 $values: '.$key_value.'=>'.$value.'<br>';
                	  		$line_items[$key_value][$key] = $value;               //$display .= '110 $line_items[$key_value][$key]: '.$line_items[$key_value].'['.$line_items[$key_value][$key].']<br>';
                		  }
                	  }
                //   Remove blank Line Items and empty item keys
                	  foreach ($line_items as $key=>$values){                     //$display .= '142 $line_item: '.$key.'<br>';
                	  	foreach($values as $key_value=>$value){                   //$display .= '143 $values: '.$key_value.'=>'.$value.'<br>';
                			if ($key_value == 'title' && empty($value)){
                				unset($line_items[$key]);
                			}													  //$display .= '150 $line_items[$key]: '.$key_value.'=>'.$line_items[$key][$key_value].'<br>';
                			if (empty($value)){                                   //$display .= '151 empty($value): '.$key_value.'<br>';
                			    unset($line_items[$key][$key_value]);
                			    continue;
                			}                                                     //$display .= '154 $line_items[$key]: '.$key_value.'=>'.$line_items[$key][$key_value].'<br>';
                		 }                                                         
                	  }
*/                    $line_items = $current_line_items;                
                // @TODO: Display values for verification
                	  $item_qty      = 0;
                	  $fulfilled_qty = 0;
                	  $taxable_total = 0;                                         $display .= '495 $preordered = '.$preordered.'<br>'; 
                	  foreach ($line_items as $key=>$values){                     $display .= '414 $line_items['.$key.']<br>';
                	  	$item_qty      += $values['qty'];
                	  	$fulfilled_qty += $preordered ? 0 : $values['qty'];
                	  	if ($values['taxable'] == 1){
                	  	    $taxable_total += $values['qty']*$values['cost'];   
                	  	}
                	  	foreach($values as $key_value=>$value){                   //$display .= '380 $line_items['.$key.']['.$key_value.'] =>'.$line_items[$key][$key_value].'<br>';
                	  	}
                	  }                                                            $display .= '421 $taxable_total = '.$taxable_total.'<br>';
                	  if ($taxable_total!=0){
                	      $tax_rate = $jot->sales_tax/$taxable_total;              $display .= '423 $tax_rate = '.$tax_rate.'<br>';
                	  }
                	  if (!empty($line_items)){
                	      $n=0;
                	      $received_qty = (int) 0;
                	      foreach ($line_items as $line => $values) {               //@BEGIN: 510 foreach ($line_items as $line => $values) 
                    	  	unset($line_item_guid, $linked_item, $line_item, $existing_item);
                    	  	$line_item_guid = $values['guid'];                           //$display .= '348 $line_item_guid: '.$line_item_guid.'<br>';
                    	  	if (empty($line_item_guid) && isset($values['item_guid'])){
                    	  	    $existing_item  = $values['item_guid'];                  //$display .= '350 $existing_item: '.$existing_item.'<br>';
                    	  	}                                            
                      		$create_item = false;
                      		if (empty($line_item_guid)){
                    			$line_item = new ElggObject();
                    			$line_item->subtype = 'receipt_item';
                    		 }
                    		 else {
                    		    $line_item = get_entity($line_item_guid);
                    		 }
                    		 $values['sort_order']  = $line + 1;
                    		 $values['container_guid'] = $jot->guid;
                    		                                                    //$display .= '363 '.print_r($values, true).'<br>';
                    		 foreach($values as $dimension => $value){		 	//$display .= '364 '.$dimension.': '.$value.'<br>';
                    		 	$line_item->$dimension = $value;
                    		 }
                    		 if (!$preordered){                              
                    		 	$line_item->recd_qty = $values['qty'];             $display.='593 $line_item->recd_qty ='.$line_item->recd_qty.'<br>580 $values[qty] ='.$values['qty'].'<br>';
                    		 }
                    		 $received_qty += $line_item->recd_qty;               $display .= '595 $line_item->recd_qty = '.$line_item->recd_qty.'<br>';
                             $line_item->save();
                             $line_item_guid = $line_item->getGUID();
                                                                    //foreach($line_item as $key=>$value){$display .= '197 '.$key.'=>'.$value.'<br>';}
                    //@EDIT 2016-04-22 - SAJ - begin
                      		$create_item = ($line_item->retain_line_label == 'create');    	  		
                      		$link_item   = ($line_item->retain_line_label == 'link');
                    //@EDIT 2016-04-22 - SAJ - end
                      		if ($link_item){                                             //$display .= '374 retain_line_label = '.$line_item['retain_line_label'].'<br>366 $line_item->title: '.$line_item['title'].'<br>';
                                $linked_item = elgg_get_entities_from_relationship(array(
                        			'type'                 => 'object',
                        			'relationship'         => $item_type,
                        			'relationship_guid'    => $line_item_guid,
                        			'inverse_relationship' => true,
                        			'limit'                => 1,
                        		));
                      		}                                            
                    //@EDIT 2016-11-09 - SAJ
                            if (isset($line_item->item_guid)){
                    	  		$link_item         = true;
                    	  		$linked_item       = get_entity($line_item->item_guid);       //$display .= '378 $linked_item->guid = '.$linked_item->guid.'<br>378 $line_item->title: '.$line_item->title.'<br>';
                    			$linked_item->qty  = $line_item->qty;
	                            if (!$preordered){                              
	                    		 	$linked_item->recd_qty = $line_item->qty;             $display.='618 $linked_item->recd_qty ='.$linked_item->recd_qty.'<br>604 $line_item->qty ='.$line_item->qty.'<br>';
	                    		 }                                                      
                    			$linked_item->save();
                      		}
                //foreach($line_item as $key=>$value){$display .= '215 '.$key.'=>'.$value.'<br>';} //goto eof; continue;
                      		if (isset($line_item) && ($create_item || $link_item)){       $display .= '623 $line_item && ($create_item = '.$create_item.' || $link_item = '.$link_item.')<br>'; //goto eof;
                      			if (empty($linked_item) && $create_item){                 $display .= '624 empty($linked_item) && $create_item<br>';
                    				$linked_item          = new ElggObject();
                    				$linked_item->subtype = 'market';
                    				$linked_item->title   = $line_item->title;
                    				$linked_item->qty     = $line_item->qty;
                  	                if ($linked_item->save()){
                    					// link new item to line item
                    					add_entity_relationship($linked_item->getguid(), $relationship, $line_item_guid);
                    					// link new item to receipt item
                    					add_entity_relationship($guid, 'receipt_item', $linked_item->getguid());
                    					$line_item->retain_line_label = 'no';              
                    					$line_item->item_guid         = $linked_item->getGUID();
                    					$line_item->save();                                //$display .= '393 linked_item saved<br>'; $display .= '393 retain_line_label = '.$line_item->retain_line_label.'<br>';
                    				}
                    			}
                //foreach($line_item as $key=>$value){$display .= '204 '.$key.'=>'.$value.'<br>';} continue;
                    //          Associate line item with item
                    			if ($link_item){                                              //$display .= '398 Associate line item with item<br>';
                    				$guid_one     = $line_item_guid;
                    				$relationship = strtolower($line_item->link_type);
                    				$guid_two     = $linked_item->guid;                        $display .= '644 $guid_one: '.$guid_one.', $relationship: '.$relationship.', $guid_two: '.$guid_two.'<br>';
                    				if ($relationship == 'part' || $relationship == 'supply'){
                    					if (!check_entity_relationship($guid_one, $relationship, $guid_two)){
                    						add_entity_relationship($guid_one, $relationship, $guid_two);					
                    					}
                    				}
                    				if ($relationship == 'warranty'){
                    					$linked_item['relationship'] = $line_item['title'];   $display .= '651 $linked_item->'.$relationship.' = '.$linked_item->$relationship.'<br>';
                    				}
                    			}
                    	  	}
/*                //foreach($line_item as $key=>$value){$display .= '220 '.$key.'=>'.$value.'<br>';} continue;
                    		if (empty($line_item->guid) || isset($existing_item)){
                    		    unset($line_item_from_existing);
                    		    $line_item_from_existing = $line_item;
                    		    $line_item = new ElggObject();                   
                    			$line_item->subtype    = $item_type;             //foreach($line_item as $key=>$value){$display .= '257 '.$key.'=>'.$value.'<br>';}
                    			foreach($line_item_from_existing as $dimension => $value){
                    			    $line_item->$dimension = $value;
                    			}
                    		}                                                              //$display .= print_r($line_item, true); goto eof;
                    		$line_item->sort_order = $line + 1;                            //$display .= '242 $line_item->sort_order: '.$line_item->sort_order.'<br>'; goto eof;
                //@TODO evaluate.  may be redundant.
                    		foreach($values as $dimension => $value){                      //$display .= '227 $values['.$dimension.'] =>'.$value.'<br>';
                    		    $line_item->$dimension = $value;                           //$display .= '231 $line_item->'.$dimension.': '.$line_item->$dimension.'<br>'; 
                    		}
*/                //foreach($line_item as $key=>$value){$display .= '267.1 '.$key.'=>'.$value.'<br>';} foreach($new_line_item as $key=>$value){$display .= '267.2 '.$key.'=>'.$value.'<br>';} //goto eof;
                    		if ($line_item->unpack == 1                    &&
                    		    $line_item->qty    >= 1                        ){           //$display .= '269 $line_item->unpack = true<br>'; $display .= '191 $linked_item->guid: '.$line_item->item_guid.'<br>';
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
                    						));                                        //$display .= '278 $linked_item[0]->getGUID() '.$linked_item[0]->getGUID().'<br>';
                    			
                    		    $linked_items = count($linked_item);                   //$display .= '280 $linked_items: '.$linked_items.'<br>';
                    			$piece_count   = count($pieces);                       //$display .= '281 $piece_count: '.$piece_count.'<br>';
                    			if ($linked_items == 1){
                    				$container_guid = $linked_item[0]->getGUID();
                    			}
                    			else {                                                 //$display .= '285 $container_guid: '.$container_guid.'<br>';
                    				$container_guid = elgg_get_logged_in_user_guid ();
                    			}
                    			$n = 0;
                    			for ($i = $n+1; $i <= ($line_item->qty - $piece_count); $i++) { //Set the new items to unpack as the difference between what exists and what is requested
                    				$new_item = new ElggObject();
                    				$new_item->subtype = 'item';                        //$display .= '291 $new_item->subtype: '.$new_item->subtype.'<br>';
                    				$new_item->title   = $line_item->title;             //$display .= '292 $new_item->title: '.$new_item->title.'<br>';
                    				$new_item->container_guid = $container_guid;        //$display .= '293 $new_item->container_guid: '.$new_item->container_guid.'<br>';
                    				$new_item->save();
//                    				add_entity_relationship($new_item->getguid(), 'receipt_item', $jot->getguid());
                    			}
                    		}
                //foreach($line_item as $key=>$value){$display .= '298 '.$key.'=>'.$value.'<br>';}// continue;     		
                    //      Store the current value of the line item
                    		$line_item->total = $line_item->qty * $line_item->cost;       $display .= '685 $line_item->total: '.$line_item->total.'<br>';
                    		if ($line_item->distribute_freight == 'on'){
                    			$line_item->shipping_cost = $jot->shipping_cost * ($line_item->qty/$item_qty);
                    		}
                    		if ($line_item->taxable == 1){
                    			$line_item->sales_tax = $jot->sales_tax * ($line_item->qty/$item_qty);
                    		}
                    		if ($line_item->save()){                                    $display .= '692 $line_item->guid: '.$line_item->guid.'<br>';
                    	 		$subtotal                         += $line_item->total;
                    		}
                    		else {
                    			register_error(elgg_echo('jot:save:failure'));
                    		} 
                //      Associate line item with item
                            unset($guid_one, $guid_two, $guid_three);
                    	    $guid_one     = $line_item->getGUID();
                			$relationship = 'receipt_item';
                			$guid_two     = $line_item->item_guid;                    $display .= '702 $guid_one: '.$guid_one.', $relationship: '.$relationship.', $guid_two: '.$guid_two.'<br>'; //goto eof;
                			$guid_three   = $jot->getguid();
                			$related_items = get_entity_relationships($guid_one);      //$display .= '$related_items'.print_r($related_items, true).'<br>';
                			if (!check_entity_relationship($guid_one, $relationship, $guid_two) && 
                			    elgg_entity_exists($guid_two)){
                				add_entity_relationship($guid_one, $relationship, $guid_two);					
                			}
                			if (elgg_entity_exists($linked_item) && $linked_item->title != $line_item->title){
                				$linked_item->title  = $line_item->title;
                				$linked_item->save();
                			}
                //      Associate line item with jot
/*                    		if (!check_entity_relationship($guid_one, $relationship, $guid_three)){
                    			add_entity_relationship($guid_one, $relationship, $guid_three);
                    		}
*/              //      Associate item with jot                                                              
                                                                                                       $display .= '718 $guid_three: '.$guid_three.', $relationship: '.$relationship.', $guid_two: '.$guid_two.' $line_item->getSubtype(): '.$line_item->getSubtype().'<br>';//goto eof;
                    	    if (!check_entity_relationship($guid_three, 'transfer_receipt', $guid_two) && 
//                                $line_item->getSubtype() == 'receipt_item' &&
                    	        elgg_entity_exists($guid_two)){
                				add_entity_relationship($guid_three, 'transfer_receipt', $guid_two);					
                			}
                     	  } //@END: 510 foreach ($line_items as $line => $values)
	                	  foreach ($line_items as $key=>$line_item){
			                		$ordered_qty  += $line_item['qty'];                               $display .= '729 $line_item[qty] = '.$line_item['qty'].'<br>';
			                	}
			              if ($preordered == 'on'){                                                   $display .='751 Collect all shipment receipts<br>';
				              $shipment_receipts = elgg_get_entities([
				                 'type'           => 'object',
		                		 'subtype'        => 'transfer',
		                		 'container_guid' => $jot->getGUID(),
				                 'limit'          => false]);		                                //$display .= '756 $shipment_receipts: '.print_r($shipment_receipts, true).'<br>';
		                	foreach($shipment_receipts as $key=>$shipment_receipt){                 $display .= '757 Extract line items from each shipment receipt<br>';
		                		unset($shipment_qty);                                               $display .= '758 $shipment_receipt->getGUID()='.$shipment_receipt->getGUID().'<br>';
		                		$shipment_moment[] = $shipment_receipt->moment;
			                	$receive_items = elgg_get_entities([
			                                        'type'                 => 'object',
			                						'subtype'              => 'line_item',
			                                        'container_guid'       => $shipment_receipt->getGUID(),
				                				    'limit'                => false]);               $display .= '764 $receive_items: '.print_r($receive_items, true).'<br>';
			                	foreach ($receive_items as $key=>$line_item){                        $display .= '762 $line_item[guid] = '.$line_item->guid.'<br>';
			                		$shipment_qty += $line_item->recd_qty;                           $display .= '763 $line_item->recd_qty = '.$line_item->recd_qty.'<br>';
			                	}
			                	$shipment_receipt->pieces_received = $shipment_qty;
			                	$shipment_receipt->save();
			                	$received_qty += $shipment_qty;
				            }
		                	//Extract quanties from each line item
		                	if (!is_array($shipment_moment)){(array)$shipment_moment;}
		                	
                	      }
			            	$jot->pieces         = $ordered_qty;                                      $display .= '765 $jot->pieces = '.$jot->pieces.'<br>';
	                     	$jot->pieces_received= $received_qty;                                     $display .= '766 $jot->pieces_received = '.$jot->pieces_received.'<br>';
	                     	                                                                          // Set the status
	                     	switch (true){
	                     		case (($ordered_qty == $received_qty && $ordered_qty > 0) || !$preordered):
	                     	  		$jot->status = 'Received';
	                     	  		$jot->received = true;
	                     	  		break;
	                     	  	case ($ordered_qty > $received_qty && $received_qty > 0):
	                     	  		$jot->status = 'Partial';
	                     	  		break;
	                     	  	case ($received_qty == 0):
	                     	  		$jot->status = 'Ordered';
	                     	  		break;
	                     	}
                     	  $jot->subtotal       = $subtotal;
                     	  $jot->sales_tax_rate = $tax_rate; 
                     	  $jot->total          = $jot->subtotal + $jot->shipping_cost + $jot->sales_tax;
                     	  if (!$jot->save()) {
                     	  	register_error(elgg_echo('jot:save:failure'));
                     	  	$error = TRUE;
                     	  }
end_foreach:                      }  
                  } //@END: 472 if (!empty($items)
                  	break; //@END: $action == default            	
            	}
                break; //@END: 198 $aspect == 'receipt'
                
	/****************************************
    * $subtype = 'transfer'
	* $aspect = 'receive' (shipment receipt) 
	****************************************/
            case 'receive':
		        if (empty($items)){break;}                                                  // Do nothing if nothing received.
	            unset($ordered_qty, $received_qty);
	            $ordered_qty = 0;
	            $received_qty = 0;
				$shipment_receipt                  = get_entity($guid);
				$shipment_receipt->received_moment = $jot_input['received_moment'];
			    $shipment_receipt->packing_list_no = $jot_input['packing_list_no'];
				$shipment_receipt->save();                                            $display .= '812 $shipment_receipt->guid = '.$shipment_receipt->getGUID().'<br>';
			
				$receive_line_items = elgg_get_entities(['type'           => 'object',
				       		                             'subtype'        => 'line_item',
				            		                     'container_guid' => $guid,
				            		                     'limit'          => false]);
				if ($receive_line_items){
					foreach ($receive_line_items as $receive_line_item){                                   $display .= '829	 $receive_line_item->guid: '.$receive_line_item->guid.'<br>';
						foreach($items as $key=>$line_item){                                               $display .= '830 $line_item[guid]: '.$line_item['guid'].'<br>';
							if (check_entity_relationship($receive_line_item->guid, 'receive_item', $line_item['guid'])){
								$line_item_relationships[$line_item['guid']] = $receive_line_item->guid;   $display .= '832 $line_item_relationships[$line_item[guid]]='.$line_item_relationships[$line_item['guid']].'<br>';
				            }
				         }
				   	}
				}
//goto eof;
				foreach($items as $key=>$line_item){
                	$line_item = (object) $line_item;
                	$receipt_item = get_entity($line_item->guid);
                	$ordered_qty  += $receipt_item->qty;                                 $display .= '841 $line_item['.$key.']='.$line_item.'<br>';	                			
                	unset($options, $fulfilled_qty, $receive_item);
                	$fulfilled_qty = 0;
                	if (empty($line_item->received) && 
                		empty($line_item->recd_qty) && 
                		empty($line_item->bo_qty)){continue;}
                	
                	if (elgg_entity_exists($line_item_relationships[$line_item->guid])){
                		$receive_item                 = get_entity($line_item_relationships[$line_item->guid]);
	                	$receive_item->recd_qty       = $line_item->recd_qty;
	                	$receive_item->bo_qty         = $line_item->bo_qty;
                	}
                	else {
                		$receive_item                 = new ElggObject();
	                	$receive_item->subtype        = 'line_item';
	                	$receive_item->container_guid = $shipment_receipt->getGUID();
	                	$receive_item->title          = $receipt_item->title;
	                	$receive_item->recd_qty       = $line_item->recd_qty;
	                	$receive_item->bo_qty         = $line_item->bo_qty;
	                	$receive_item->save();
                	
                	}
                	if (!check_entity_relationship ($receive_item->getGUID(), 'receive_item', $receipt_item->getGUID())){
		                   add_entity_relationship ($receive_item->getGUID(), 'receive_item', $receipt_item->getGUID());                   // Connect the receive line item to the receipt item
                	}
                     
                    $options = ['type'              => 'object',
	                			'subtypes'          => ['market', 'item'],
                			    'relationship'      => 'receipt_item',
	                    	    'relationship_guid' => $line_items->item_guid];
	                $linked_item_entity = elgg_get_entities_from_relationship($options)[0];      // $display .= '869'.print_r($linked_item_entity, true);
	                if (!empty($linked_item_entity)){                                            // Line item linked to an item.
		                $linked_item_entity->qty += $line_items->recd_qty;                       // Update the inventory count of the linked item entity with the quantity received
		                $linked_item_entity->save();	                		                 // Save the existing receipt line item
	                }
	                
                    $receipt_item->recd_qty  = $receive_item->recd_qty;                          // Add received qty to the existing receipt line item
                    $fulfilled_qty           = $receipt_item->recd_qty;                          // Update fulfillment proportion of the receipt line item
                    // Update fulfillment proportion of the sales receipt 
	                switch (true){
                     	case ($receipt_item->qty == $receipt_item->recd_qty && $receipt_item->qty > 0):
                     	  	  $receipt_item->status   = 'Received';
                     	  	  $receipt_item->received = 1;
                     	  	break;
                     	case ($receipt_item->qty > $receipt_item->recd_qty && $receipt_item->recd_qty > 0):
                     	  	$receipt_item->status = 'Partial';
                     	  	break;
                     	case ($fulfilled_qty == 0 || empty($fulfilled_qty)):
                     	  	 $receipt_item->status = 'Ordered';
                     	  	break;
                     	}
                    // Update fulfillment status of the receipt line item [o(rdered), p(artial), R(eceived)]
                	$receipt_item->save();
                    $receive_item->save();
                	}                                                                           //Collect all shipment receipts
                	$shipment_receipts = elgg_get_entities([
		                 'type'           => 'object',
                		 'subtype'        => 'transfer',
                		 'container_guid' => $jot->getGUID(),
		                 'limit'          => false]);		                               //Extract line items from each shipment receipt
	            	foreach($shipment_receipts as $key=>$shipment_receipt){
	                		unset($shipment_qty);
	                		$shipment_moment[] = $shipment_receipt->moment;
		                	$receive_items = elgg_get_entities(['type'                 => 'object',
						                						'subtype'              => 'line_item',
						                                        'container_guid'       => $shipment_receipt->getGUID(),
							                				    'limit'                => false]);
		                	foreach ($receive_items as $key=>$line_item){                    $display .= '911 $line_item[guid] = '.$line_item->guid.'<br>';
	                		$shipment_qty += $line_item->recd_qty;                           $display .= '912 $line_item->recd_qty = '.$line_item->recd_qty.'<br>';
		                	}
		                	$shipment_receipt->pieces_received = $shipment_qty;
		                	$shipment_receipt->save();
		                	$received_qty += $shipment_qty;
			            }
	                $jot->pieces_received= $received_qty;
                    // Set the status
                    switch (true){
                     	case ($ordered_qty == $received_qty && $ordered_qty > 0):
                     	      $jot->status = 'Received';
                     	  	  $jot->received = 1;
                     	  	  break;
                     	case ($ordered_qty > $received_qty && $received_qty > 0):
                     	  		$jot->status = 'Partial';
                     	  		break;
                     	case ($received_qty == 0):
                     	  		$jot->status = 'Ordered';
                     	  		break;
                     	}
                    $jot->save();
            		break;
    /****************************************
     * $subtype = 'transfer'
	 * $aspect = 'return'                    *****************************************************************************
     ****************************************/
            case 'return':
                /*Pseudocode
                 	* Receive return jot
                 	* Receive returned items  
                 	* Create Credit transaction
                 	* Change owner to Merchant
                 		* If Merchant is not a QuebX merchant, create a merchant account
                 		* Items
                 		* Images
                 		* Warranty 
                 	* Mark returned items as 'returned' on receipt
                 	* Link Credit transaction to receipt
                 	* Credit returning owner
                 		*  
                 	* 
                 */
                // Get input data
                $aspect        = get_input('aspect', 'return');
                $merchant_guid = $jot->merchant_guid;
                $user_guid     = elgg_get_logged_in_user_guid();
                
                foreach ($jot_input as $name=>$value){                                                 //$display .= '$jot_return.'.$name.'=>'.$value.'<br>';
                }
                	  
                // Determine whether Merchant exists	  
                if (elgg_entity_exists($merchant_guid)){$merchant_exists = TRUE;}
                else                                   {$merchant_exists = FALSE;}                   $display .= '374 $guid: '.$guid.'<br>408 $merchant: '.$jot->merchant.'<br>';
                if ($merchant_exists)             {                                                  $display .= '375 merchant exists<br>';} 
                else                              {                                                  $display .= '376 merchant does not exist<br>';}
                	  
                if ($merchant_exists == FALSE){
                	/*Attempt to match merchant to existing merchant by name
                	  If exact match found
                		* Obtain merchant guid 
                	  if no exact match found
                		* Create the merchant
                		* Set $merchant_exists to True
                	 * Set $receipt->merchant_guid to merchant guid
                	*/
                	
                	$merchant_name = $jot->merchant;
                	$dbprefix = elgg_get_config('dbprefix');
                //Attempt to match merchant to existing merchant by name
                	$existing_merchants = elgg_get_entities(array(
                		'type' => 'group',
                		'joins' => array(
                			"JOIN {$dbprefix}groups_entity ge ON ge.guid = e.guid",
                		   ),
                		'wheres' => array(
                			"ge.name = '$merchant_name'",
                		   ),
                	));
                //If exact match found
                	if (count($existing_merchants)>0){
                	//Obtain merchant guid 
                		$jot->merchant_guid = $existing_merchants[0]->guid;
                	}
                //if no exact match found
            /* Let's table this for now ...
                	else {
                	//Create the merchant
                		//from mod/groups/actions/groups/edit.php
                	$new_merchant = new ElggGroup();
                	$new_merchant->name = htmlspecialchars($merchant_name, ENT_QUOTES, 'UTF-8');
                	$new_merchant->membership = ACCESS_PRIVATE;
                	$new_merchant->access_id = ACCESS_PRIVATE;
                	$new_merchant->owner_guid = $user_guid;
                	$new_merchant->container_guid = $user_guid;
                	if ($new_merchant->save()){
                	//Set $receipt->merchant_guid to merchant guid
                		$receipt->merchant_guid = $new_merchant->guid;
                		$display .= 'New merchant created: '.$new_merchant->name.'<br>';
                	}
                	}
                	//Set $merchant_exists to True
                	IF ($receipt->save()){$merchant_exists = TRUE;}
            */    }
                
                // Process returned items ...
                foreach ($return_items as $key=>$values){                         //$display .= '443 $key: '.$key.'<br>';
                	  	foreach($values as $key_value=>$value){                   //$display .= '33 $values: '.$key_value.'=>'.$value.'<br>';
                	  		$line_items[$key_value][$key] = $value;               //$display .= '34 $line_items[$key_value][$key]: $line_items['.$key.']['.$line_items[$key_value][$key].']<br>';
                		  }
                	  }
                //   Remove blank Line Items
                	  foreach ($line_items as $key=>$values){                     //$display .= '111 $line_item: '.$key.'<br>';
                	  	foreach($values as $key_value=>$value){                   //$display .= '112 $values: '.$key_value.'=>'.$value.'<br>';
                			if ($key_value == 'qty' && ($value == '' || $value == 0)){
                				unset($line_items[$key]);
                			}
                		 }
                	  }
                	  $item_qty=0;
                	  foreach ($line_items as $key=>$values){                     //$display .= '457 $line_item: '.$key.'<br>';
                	  	$item_qty += $values['qty'];
                
                	  	foreach($values as $key_value=>$value){                   //$display .= '460 $values: '.$key_value.'=>'.$value.'<br>';
                	  	}
                	  }
                
                // Create Credit transaction	  
                    $credit_transaction = new ElggObject();
                	$credit_transaction->subtype            = $subtype;
                	$credit_transaction->aspect             = $aspect;
                	$credit_transaction->title              = $jot_input['title'];
                	$credit_transaction->owner_guid         = $user_guid;
                	// Associate credit transaction with purchase transaction
                    $credit_transaction->container_guid     = $jot_input['container_guid'] ?: $jot->guid;
                    $credit_transaction->credit_date        = $jot_input['return_date'];
                    $credit_transaction->expiration_date    = $jot_input['expire_date'];
                    $credit_transaction->returned_by        = $jot_input['returned_by'];
                    $credit_transaction->shipping           = $jot_input['shipping'];             //$display .= '493 $credit_transaction->shipping: '.$credit_transaction->shipping.'<br>';
                    $credit_transaction->sales_tax          = $jot_input['sales_tax'];            //$display .= '495 $credit_transaction->sales_tax: '.$credit_transaction->sales_tax.'<br>';
                    $credit_transaction->credit_amount      = $jot_input['total'];                //$display .= '495 $credit_transaction->credit_amount: '.$credit_transaction->credit_amount.'<br>';
            //        $credit_transaction->save();
                    if ($merchant_exists){
                		/* Associate the Credit transaction with the merchant
                		 	* Mark stage as 'sent to merchant'
                		 	* Create river entry for workflow stage
                		 * Auto-process the Credit transaction
                		 	*   auto-accept Credit transaction
                		 */
                		$credit_transaction->stage = 'sent to merchant';
                		if ($credit_transaction->save()){                  
                		  	$referrer     = "jot/view/$credit_transaction->getGUID()";
                		    $action_type  = 'create';
                			$subject_guid = $credit_transaction->owner_guid;
                			$object_guid  = $credit_transaction->guid;
                			$target_guid  = $credit_transaction->container_guid; 
                			$options      = array(
                						'view'          => 'river/object/jot/'.$action_type,
                						'action_type'   => $action_type,
                						'subject_guid'  => $subject_guid,
                						'object_guid'   => $object_guid,
                						'target_guid'   => $target_guid,
                					);
                			elgg_create_river_item($options);
                		}	
                    	if (check_entity_relationship($credit_transaction->guid, 'return_receipt', $receipt->merchant_guid) == false){
                			add_entity_relationship  ($credit_transaction->guid, 'return_receipt', $receipt->merchant_guid);
                		}
                    }
            ////        add_entity_relationship($receipt->guid, 'return', $credit_transaction->guid);
                // Apply return line items to Credit transaction
                	foreach ($line_items as $line => $return_item) {
                  		$credit_line_item = new ElggObject();
                  		$credit_line_item->title                  = $return_item['title'];
                  		$credit_line_item->subtype                = $return_item['subtype']; 
                  		$credit_line_item->aspect                 = $aspect;
                  		$credit_line_item->owner_guid             = $user_guid;
                  		// Associate credit line item with credit transaction
                  		$credit_line_item->container_guid         = $credit_transaction->getguid();
                  		// Associate receipt line item with credit line item
                  		$credit_line_item->receipt_line_item_guid = $return_item['line_item_guid'];
                  		$credit_line_item->linked_item_guid       = $return_item['linked_item_guid'];
                  		$credit_line_item->qty                    = $return_item['qty'];             
                		$credit_line_item->total                  = $return_item['received_value'];
            //continue;
                //  		$credit_line_item->save();
                		if ($credit_line_item->save()){                  
                		  	$action_type  = 'create';
                			$subject_guid = $credit_line_item->owner_guid;
                			$object_guid  = $credit_line_item->linked_item_guid;
                			$target_guid  = $credit_line_item->guid; 
                			$options      = array(
                						'view'          => 'river/object/jot/'.$action_type,
                						'action_type'   => $action_type,
                						'subject_guid'  => $subject_guid,
                						'object_guid'   => $object_guid,
                						'target_guid'   => $target_guid,
                					);
                			elgg_create_river_item($options);
                		}	
                  		
                  		$receipt_item = get_entity($credit_line_item->receipt_line_item_guid);
                  		$linked_item  = get_entity($credit_line_item->linked_item_guid);
                		if (!empty($receipt_item)){
                		    $receipt_item->status     = 'returned';
                		    $receipt_item->save();
                		}
                		if (!empty($linked_item)){
                		    $linked_item->owner_guid = $merchant_guid;
                		    $linked_item->save();
                		}
                  }
                //forward($referrer);
                register_error($display);
            break;
        } //@END $subtype == transfer 
        break;
    case 'experience':
/****************************************
 * $subtype = 'experience'                *****************************************************************************
 ****************************************/
  // Find and remove empty fields that were previously filled.
	if (isset($jot_snapshot)){
	    foreach ($jot_snapshot as $key => $value){
	        if (isset($jot_snapshot[$key]) && empty($jot_input[$key])){
	            unset($jot->$key);
	        }
	    }
	}
    
    Switch ($aspect){
        case 'nothing':
            break;
        case 'instruction':
            /****************************************
             * $aspect = 'instruction'               *****************************************************************
             ****************************************/
            $steps_existing = elgg_get_entities_from_metadata(array('type'              => 'object',
                                                                    'subtype'           => 'instruction',
                                                                    'metadata_name_value_pairs' => array(
                                                                        'name'          => 'aspect', 
                                                                        'value'         =>'process_step'),
                                                                    'container_guid'    => $guid,
                                                                    'limit'             => 0,
                                                              ));
            $material_existing = elgg_get_entities_from_metadata(array('type'           => 'object',
                                                                    'subtype'           => 'instruction',
                                                                    'metadata_name_value_pairs' => array(
                                                                        'name'          => 'aspect', 
                                                                        'value'         => 'material'),
                                                                    'container_guid'    => $guid,
                                                                    'limit'             => 0,
                                                              ));
            $tools_existing = elgg_get_entities_from_metadata(array('type'              => 'object',
                                                                    'subtype'           => 'instruction',
                                                                    'metadata_name_value_pairs' => array(
                                                                        'name'          => 'aspect', 
                                                                        'value'         => 'tool'),
                                                                    'container_guid'    => $guid,
                                                                    'limit'             => 0,
                                                              ));
            $steps    = $instructions['step'];
            $material = $instructions['material'];
            $tools    = $instructions['tool'];
            
            // Process steps
            unset($line_item, $item, $existing, $existing_item, $existing_guids, $current_guids, $flipped_current_guids);
            if (!empty($steps_existing)){
                foreach($steps_existing as $existing){
                    $existing_guids[] = $existing->guid;
                }
            }
            if (!empty($steps)){
                foreach($steps as $key=>$current){     
                    foreach($current as $key1=>$value){                    $display .= '688 $steps['.$key.']['.$key1.'] = '.$value.'<br>';
                        if (empty($value)){
                            unset($steps[$key][$key1]);
                            continue;
                        }
                        $current_steps[$key1][$key]=$value;
                    }
                }
            }
//                                                                           $display .= '696 '.print_r($current_steps, true).'<br>';
//goto eof;
            if (!empty($current_steps)){
                foreach ($current_steps as $key=>$value){
                    if ($value['guid'] == ''){continue;}
                    $current_steps_guids[] = $value['guid'];
                }
            }
            if (!empty($current_steps_guids)){
                $flipped_current_guids = array_flip($current_steps_guids);
            }
            if (!empty($existing_guids)){
                foreach($existing_guids as $key=>$existing_guid){
                    if (isset($flipped_current_guids[$existing_guid])){
                        continue;
                    }
                    else {
                        $existing_item = get_entity($existing_guid);
                        $existing_item->delete();
                    }
                }
            }
            foreach ($current_steps as $line=>$line_item){
                if (empty($line_item['guid'])){
                    $item             = new ElggObject();
                    $item->subtype    = $aspect;     // instruction
                    $item->owner_guid = $user_guid;
                    $item->access_id  = $access_id;
                }
                else {
                    $item             = get_entity($line_item['guid']);
                }
                $item->title          = 'Step '.($line+1);                        //$display .= '$step->title: '.$step->title.'<br>';
                $item->description    = $line_item['description'];
                $item->aspect         = 'process_step';
                $item->sort_order     = $line+1;                                 //$display .= '$step->sort_order: '.$step->sort_order.'<br>';
                $item->container_guid = $guid;                                   //$display .= '675 $step:<br>'.print_r($step, true).'<br>';
                if ($item->save()) { 
               		$success = true;
                }
                else {
                    $error = true;
                }
           }
            // Process material
            unset($line_item, $item, $existing, $existing_item, $existing_guids, $current_items, $current_guids, $flipped_current_guids);
            if (!empty($material_existing)){
                foreach($material_existing as $existing){
                    $existing_guids[] = $existing->guid;
                }
            }
            if (!empty($material)){
                foreach($material as $key=>$current){     
                    foreach($current as $key1=>$value){                    $display .= '750 $material['.$key.']['.$key1.'] = '.$value.'<br>';
                        $current_items[$key1][$key]=$value;
                    }
                }
            }
            if (!empty($current_items)){
                foreach ($current_items as $key=>$line_item){
                    if (empty($line_item['title'])){
                        unset($current_items[$key]);
                    }
                }
                $current_items = array_values($current_items);
            }
                                                                           $display .= '769 '.print_r($current_items, true).'<br>';
//goto eof;
            if (!empty($current_items)){
                foreach ($current_items as $key=>$value){
                    if ($value['guid'] == ''){continue;}
                    $current_guids[] = $value['guid'];
                }
            }
            if (!empty($current_guids)){
                $flipped_current_guids = array_flip($current_guids);
            }
            if (!empty($existing_guids)){
                foreach($existing_guids as $key=>$existing_guid){
                    if (isset($flipped_current_guids[$existing_guid])){
                        continue;
                    }
                    else {
                        $existing_item = get_entity($existing_guid);
                        $existing_item->delete();
                    }
                }
            }
            foreach ($current_items as $line=>$line_item){
                if (empty($line_item['guid'])){
                    $item             = new ElggObject();
                    $item->subtype    = $aspect;     // instruction
                    $item->owner_guid = $user_guid;
                    $item->access_id  = $access_id;
                }
                else {
                    $item             = get_entity($line_item['guid']);
                }
                $item->title          = $line_item['title'];                     //$display .= '$step->title: '.$step->title.'<br>';
                $item->aspect         = 'material';
                $item->sort_order     = $line+1;                                 //$display .= '$step->sort_order: '.$step->sort_order.'<br>';
                $item->container_guid = $guid;                                   //$display .= '675 $step:<br>'.print_r($step, true).'<br>';
                $item->qty            = $line_item['qty'];
                $item->units          = $line_item['units'];
                if ($item->save()) { 
               		$success = true;
                }
                else {
                    $error = true;
                }
           }
            // Process tools
            unset($item, $existing, $existing_item, $existing_guids, $current_items, $current_guids, $flipped_current_guids);
            if (!empty($tools_existing)){
                foreach($tools_existing as $existing){
                    $existing_guids[] = $existing->guid;
                }
            }
            if (!empty($tools)){
                foreach($tools as $key=>$current){     
                    foreach($current as $key1=>$value){                    $display .= '823 $tools['.$key.']['.$key1.'] = '.$value.'<br>';
                        $current_items[$key1][$key]=$value;
                    }
                }
            }
            if (!empty($current_items)){
                foreach ($current_items as $key=>$line_item){
                    if (empty($line_item['title'])){
                        unset($current_items[$key]);
                    }
                }
                $current_items = array_values($current_items);
            }
                                                                           $display .= '836 '.print_r($current_items, true).'<br>';
//goto eof;
            if (!empty($current_items)){
                foreach ($current_items as $key=>$line_item){
                    if ($line_item['guid'] == ''){continue;}
                    $current_guids[] = $line_item['guid'];
                }
            }
            if (!empty($current_guids)){
                $flipped_current_guids = array_flip($current_guids);
            }
            if (!empty($existing_guids)){
                foreach($existing_guids as $key=>$existing_guid){
                    if (isset($flipped_current_guids[$existing_guid])){
                        continue;
                    }
                    else {
                        $existing_item = get_entity($existing_guid);
                        $existing_item->delete();
                    }
                }
            }
            foreach ($current_items as $line=>$line_item){
                if (empty($line_item['guid'])){
                    $item             = new ElggObject();
                    $item->subtype    = $aspect;     // instruction
                    $item->owner_guid = $user_guid;
                    $item->access_id  = $access_id;
                }
                else {
                    $item             = get_entity($line_item['guid']);
                }
                $item->title          = $line_item['title'];                        //$display .= '$step->title: '.$step->title.'<br>';
                $item->aspect         = 'tool';
                $item->sort_order     = $line+1;                                 //$display .= '$step->sort_order: '.$step->sort_order.'<br>';
                $item->container_guid = $guid;                                   //$display .= '675 $step:<br>'.print_r($step, true).'<br>';
                if ($item->save()) { 
               		$success = true;
                }
                else {
                    $error = true;
                }
           }
            
            break;
       case 'observation':
            /****************************************
             * $aspect = 'observation'               *****************************************************************
             ****************************************/
            $jot->state  = $observations['state'];
            $jot->save();
            unset($existing_guids);
            $discoveries_existing = elgg_get_entities_from_metadata(array('type'              => 'object',
                                                            'subtype'           => 'observation',
                                                            'metadata_name_value_pairs' => array('name'=>'aspect', 'value'=>'discovery'),
                                                            'container_guid'    => $guid,
                                                            'limit'             => 0,
                                                      ));//                   $display .= '704 '.print_r($discoveries_existing, false).'<br>';
            $efforts_existing     = elgg_get_entities_from_metadata(array('type'              => 'object',
                                                            'subtype'           => 'observation',
                                                            'metadata_name_value_pairs' => array('name'=>'aspect', 'value'=>'effort'),
                                                            'container_guid'    => $guid,
                                                            'limit'             => 0,
                                                      ));//                   $display .= '704 '.print_r($discoveries_existing, false).'<br>';
            $discoveries = $observations['discovery'];
            $efforts     = $observations['effort'];
            if (!empty($discoveries_existing)){
                foreach($discoveries_existing as $existing){                $display .= '715 $discoveries_existing->guid: '.$existing->guid.'<br>';
                    $discoveries_existing_guids[] = $existing->guid;
                }
            }
            if (!empty($efforts_existing)){
                foreach($efforts_existing as $existing){                $display .= '720 $efforts_existing->guid: '.$existing->guid.'<br>';
                    $efforts_existing_guids[] = $existing->guid;
                }
            }
            if (!empty($discoveries)){                                   
                foreach($discoveries as $key=>$current){                 //$display .= '713 $current: '.$current.'<br>';
                    foreach($current as $key1=>$value){                  //$display .= '714 $current: '.$key.'['.$key1.']['.$value.']<br>';
                        if (empty($value)){
                            unset($discoveries[$key][$key1]);
                            continue;
                        }
                        $current_discoveries[$key1][$key]=$value;
                    }
                }                                                      //$display .= '729 '.print_r($discoveries, true).'<br>';
            }
            unset($key, $key1, $current, $value);
            if (!empty($efforts)){                                   
                foreach($efforts as $key=>$current){                 //$display .= '733 $current: '.$current.'<br>';
                    foreach($current as $key1=>$value){              $display .= '734 $current: '.$key.'['.$key1.']['.$value.']<br>';
                        $current_efforts[$key1][$key]=$value;
                    }
                }
            }
            if (!empty($current_discoveries)){
                foreach ($current_discoveries as $key=>$value){
                    if ($value['guid'] == ''){continue;}
                    $current_discoveries_guids[] = $value['guid'];
                }
            }
            if (!empty($current_efforts)){
                foreach ($current_efforts as $key=>$value){
                    if ($value['guid'] == ''){continue;}
                    $current_efforts_guids[] = $value['guid'];
                }
            }
            if (!empty($discoveries_existing_guids)){
                foreach($discoveries_existing_guids as $key=>$guid){                     //$display .= '752 $existing_guid = '.$guid.'<br>'; 
                }
            }
            if (!empty($current_discoveries_guids)){
                $flipped_current_guids = array_flip($current_discoveries_guids);
            }
            if (!empty($current_efforts_guids)){
                $flipped_current_efforts_guids = array_flip($current_efforts_guids);
            }
            if (!empty($discoveries_existing_guids)){
                foreach($discoveries_existing_guids as $key=>$existing_guid){
                    if (isset($flipped_current_guids[$existing_guid])){
                        continue;
                    }
                    else {
                        $existing_discovery = get_entity($existing_guid);               $display .= '767 $existing_discovery->guid:'.$existing_discovery->guid.'<br>';
                        $existing_discovery->delete();
                    }
                }
            }
            if (!empty($efforts_existing_guids)){
                foreach($efforts_existing_guids as $key=>$existing_guid){
                    if (isset($flipped_current_efforts_guids[$existing_guid])){
                        continue;
                    }
                    else {
                        $existing_effort = get_entity($existing_guid);               $display .= '740 $existing_discovery->guid:'.$existing_discovery->guid.'<br>';
                        $existing_effort->delete();
                    }
                }
            }
        	// Process research efforts
        	// Pivot line items
        	if (!empty($discoveries)) {                                 //$display .= '722 Efforts<br>';
        		$relationship = 'discovery';
        		unset($line_items);
        		foreach ($discoveries as $key=>$values){
        		  	foreach($values as $key_value=>$value){
        		  		$line_items[$key_value][$key] = $value;         //$display .= '790 $line_items['.$key_value.']['.$key.'] = '.$value.'<br>';
        			  }
        		  }
        		// Remove blank Line Items
        		foreach ($line_items as $key=>$values){      	  		//$display .= '794 $line_items['.$key.']='.$key.'<br>';
        		    if ($values['action'] == ''){
        		        unset($line_items[$key]);
        		    }
        		}                                                       //$display .= '798 '.print_r($line_items, true).'<br>';
            	// Process live actions
                foreach ($line_items as $line => $values) {             //$display .= '800 '.print_r($values, true).'<br>';
            	  	unset($line_item_guid);
            	  	$line_item_guid = $values['guid'];                   //$display .= '802 $line_item_guid= '.$line_item_guid.'<br>';
            
            	  	unset($line_item);
            	  	if (empty($line_item_guid)){
            			$line_item = new ElggObject();
            			$line_item->subtype = $aspect;
            		 }
            		 else {
            		    $line_item = get_entity($line_item_guid);
            		 }
            		 $values['title']       = 'Discovery #'.($line + 1);
            		 $values['description'] = $values['discovery'];
            		 $values['sort_order']  = $line + 1;
            		 $values['aspect']      = 'discovery';
            		 $values['container_guid'] = $jot->guid;
            		                                                     $display .= '1145 '.print_r($values, true).'<br>';
            		 foreach($values as $dimension => $value){		 	//$display .= '818 '.$dimension.': '.$value.'<br>';
            		 	$line_item->$dimension = $value;
            		 }                                                    //$display .= '820 '.print_r($line_item, true).'<br>';
                     $line_item->save();
            	  }
        	}
        	if (!empty($efforts)) {                                 $display .= '1152 Efforts<br>';
        		$relationship = 'effort';
        		unset($line_items, $line, $line_item, $values, $key_value, $key);
        		foreach ($efforts as $key=>$values){
        		  	foreach($values as $key_value=>$value){
        		  		$line_items[$key_value][$key] = $value;
        			  }
        		  }
        		// Remove blank Line Items
        		foreach ($line_items as $key=>$values){      	  		$display .= '1161 $key: '.$key.'<br>';
        		    if ($values['title'] == ''){
        		        unset($line_items[$key]);
        		    }
        		}
            	// Process live actions
                foreach ($line_items as $line => $values) {
            	  	unset($line_item_guid);
            	  	$line_item_guid = $values['guid'];                   $display .= '1169 $line_item_guid= '.$line_item_guid.'<br>';
            
            	  	unset($line_item);
            	  	if (empty($line_item_guid)){
            			$line_item = new ElggObject();
            			$line_item->subtype = $aspect;
            		 }
            		 else {
            		    $line_item = get_entity($line_item_guid);
            		 }
            		 $values['sort_order']     = $line + 1;
            		 $values['aspect']         = 'effort';
            		 $values['container_guid'] = $jot->guid;
            		 
            		 foreach($values as $dimension => $value){		 	$display .= '860 '.$dimension.': '.$value.'<br>';
            		 	$line_item->$dimension = $value;
            		 }
                     $line_item->save();
            	  }
        	}
                                                                    //$display .= '738 $line_items<br>'.print_r($line_items, true).'<br>'; 
            break;
        case 'event':
            /****************************************
             * $aspect = 'event'                    *****************************************************************
             ****************************************/
            if (!empty($events)){                          $display .= '1222 $events:<br>'.print_r($events, true).'<br>'; //goto eof;
                unset($success);
$display = '1231 DRAFT - Not ready to process events.  Terminating process.'; goto eof;
                foreach ($schedule as $key=>$schedule_item){                        //$display .= '570 $schedule['.$key.'] = '.$schedule_item.'<br>';
                    if (empty($schedule_item['title'])){                            //$display .= '571 empty($schedule_item[title])';
                        continue;
                    }                                                               //$display .= '573 $schedule_item:<br>'.print_r($schedule_item, true).'<br>';
                    if (empty($schedule_item['guid'])){                             //$display .= '574 empty($schedule_item[guid]<br>';
                        $item          = new ElggObject();
                        $item->subtype = 'schedule_item';
                        $item->owner_guid = elgg_get_logged_in_user_guid();
                        $item->access_id  = get_default_access();
                    }
                    else {
                        $item = get_entity($schedule_item['guid']);                 //$display .= '581 $item->guid: '.$item->guid.'<br>';
                    }
                    foreach ($schedule_item as $key1=>$value){                      //$display .= '583 $schedule['.$key.']['.$key1.'] = '.$value.'<br>';
                        $item->$key1 = $schedule_item[$key1];
                    }
                    $item->sort_order = $key+1;
                    $item->parent_guid = $guid;
                    $item->container_guid = $guid;
                    
                    if ($item->save()){
                        $success = true;
                    }
                    else {
                        $error = true;
                    }
                }
            }   
            break;
        case 'project':
            
            break;    
    }
  break;  
} //goto eof;

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
/*	if (elgg_view_exists("forms/jot/edit/$category")) {
		// Forward to the next form in the wizard sequence
		forward("jot/edit_more/{$jot->guid}/$category");
	} 
*/	if (!$exists){
        if ($subtype == 'experience'){
            // Associate jot with item
            unset($guid_one, $guid_two, $guid_three, $relationship);
    	    $guid_one     = $jot->getguid();
			$relationship = $subtype;
			$guid_three   = $jot->container_guid;
    		if (!check_entity_relationship($guid_one, $relationship, $guid_three)){
    			add_entity_relationship($guid_one, $relationship, $guid_three);
    		}
    		$referrer = get_entity($jot->container_guid)->getURL();
        }
		$action_type  = 'create';
		$subject_guid = $jot->owner_guid;
		$object_guid  = $jot->guid;
		$target_guid  = $jot->container_guid;                               $display .= '358 $type: '.$type.'<br>$subtype: '.$subtype.'<br>';
		elgg_create_river_item(array(
			'action_type'   => $action_type,
			'view'          => 'river/object/jot/'.$action_type,
			'subject_guid'  => $subject_guid,
			'object_guid'   => $object_guid,
			'target_guid'   => $target_guid,
		));
	
//		register_error($display);
		if ($presentation == 'box' || $presentation == 'compact dropdown' || $presentation == 'compact'){
//		    forward($referrer);
		}
		else {
		 $referrer = $referrer ?: "jot/edit/{$jot->getGUID()}/$aspect?origin=$target_guid";
		}
	}
	if ($presentation == 'compact dropdown' || $presentation == 'compact'){
		goto eof;
	}
	if ($apply){
	    register_error($display);
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
	  forward($referrer ?: "jot/view/{$jot->getGUID()}");	  
	}
  }
} //@END 100 else
eof:
register_error($display);
