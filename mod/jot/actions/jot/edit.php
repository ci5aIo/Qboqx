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
$guid         = (int) get_input('guid', 0);
$parent_guid  = (int) get_input('parent_guid');
$aspect       =       get_input('aspect', 'receipt');
$apply        =       get_input('apply');
$title        =       get_input('title');
// Receive jot data
$jot_input     =      get_input('jot');       if(empty($jot_input)){unset($jot_input); $display .= 'empty $jot_input<br>';} $display .= '25 $jot_input[title]'.$jot_input['title'].'<br>';
$jot_snapshot  =      $jot_input['snapshot'];                       unset($jot_input['snapshot']); 
// Receive line items
$items        =       get_input('line_item');   if(empty($items)){       unset($items);}
$return_items =       get_input('return_item'); if(empty($return_items)){unset($return_items);}
$item_type    =       get_input('item_type');   if(empty($item_type)){   unset($item_type);}
$referrer     =       get_input('referrer');                                                //$display .= '22 $referrer: '.$referrer.'<br>';

$relationship = $item_type;
$jot          = get_entity($guid);
$parent_item  = get_entity($parent_guid);
$presentation = get_input('presentation');

if (elgg_instanceof($jot, 'object', 'market') || elgg_instanceof($jot, 'object', 'item')) {
	$subtype     = $jot->getSubtype();
	$referrer    = $referrer ?: "market/view/$guid";
	$jot_exists  = false;
	
}
elseif (elgg_entity_exists($jot)) {
	$subtype     = $jot->getSubtype();
	$referrer    = $referrer ?: "jot/view/$guid";
	$jot_exists  = true;
}
else {
	$subtype     = get_input('subtype', 'transfer');
	$jot_exists  = false;
}
if ($jot->aspect == $jot_input['aspect']){
    ;                                      $display .= '48 $jot_exists: '.$jot_exists.'<br>$jot->aspect: '.$jot->aspect.'<br>$jot_input[aspect]: '.$jot_input['aspect'].'<br>';
}
//goto eof;                                                                                    //$display .= '49 $jot_input[merchant] = '.$jot_input['merchant'].'<br>';
if (!$jot_exists) { // create a new jot item
    $user_guid           = elgg_get_logged_in_user_guid();
    $access_id           = get_default_access();
    $jot                 = new ElggObject();
    $jot->subtype        = $subtype;
    // Received from sending form as $jot_input ...
//     $jot->owner_guid     = $user_guid;
//     $jot->container_guid = $user_guid;
    foreach ($jot_input as $name => $value){
        if (empty($value)){unset($jot_input[$name]); continue;}
        $jot->$name = $value;                                   //$display .= '59 $jot_input['.$name.']='.$jot_input[$name].'<br>';
    }
	$jot->access_id    = $access_id;                            //$display .= '61 $jot->merchant = '.$jot->merchant.'<br>'; //goto eof;
    $jot->save();
    add_entity_relationship($jot->getguid(), 'transfer_receipt', $jot->asset);					
}
//foreach($jot as $name=>$value){$display .= $name.'=>'.$value.'<br>';} goto eof;
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


if (!$error && $aspect == 'receipt') {
  $jot->title       = $title;
  $jot->description = $body;
      
// Process input
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
if (isset($jot_input)){                                                  $display .= '120 $jot_input[merchant]'.$jot_input['merchant'].'<br>';
    foreach($jot_input as $key=>$value){                          //$display .= '121 $jot_input['.$key.']: '.$jot_input[$key].'<br>';
        if (empty($value)){
            unset ($jot_input[$key]);
            continue;
        }                                                         //$display .= '125 $jot_input['.$key.']: '.$jot_input[$key].'<br>';
        if ($key == 'merchant'){
            if (is_array($jot_input['merchant']) && isset($jot_input['merchant'][0])){
                $merchant = $jot_input['merchant'][0];
            }
            else {
                if (isset($jot_input['merchant'])){
                    $merchant = $jot_input['merchant'];          //$display .= '132 $merchant:'.$merchant.'<br>135 $jot_input[merchant]:'.$jot_input['merchant'].'<br>';
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
if (isset($items)){
//   Pivot Line Items
	  foreach ($items as $key=>$values){                          //$display .= '104 $key: '.$key.'<br>';
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

// @TODO: Display values for verification
	  $item_qty=0;
	  foreach ($line_items as $key=>$values){                     //$display .= '160 $line_items['.$key.']<br>';
	  	$item_qty += $values['qty'];

	  	foreach($values as $key_value=>$value){                   //$display .= '177 $line_items['.$key.']['.$key_value.'] =>'.$line_items[$key][$key_value].'<br>';
	  	}
	  }
	  if (isset($line_items)){
    	  foreach ($line_items as $line => $values) { //@BEGIN: 182 foreach ($line_items as $line => $values) 
    	  	unset($line_item_guid, $linked_item, $line_item, $existing_item);
    	  	$line_item_guid = $values['guid'];                           $display .= '183 $line_item_guid: '.$line_item_guid.'<br>';
    	  	if (empty($line_item_guid) && isset($values['item_guid'])){
    	  	    $existing_item  = $values['item_guid'];                  $display .= '185 $existing_item: '.$existing_item.'<br>';
    	  	}                                            
      		$create_item = false;
      		$line_item = get_entity($line_item_guid);
      		if (empty($line_item)){                                      $display .= '190 empty($line_item)<br>';
      		    $line_item = $values;                                    $display .= '191 $line_item[title]: '.$line_item['title'].'<br>';
      		}
      		else {
      		    foreach($values as $dimension => $value){
      		        $line_item->$dimension = $value;                     //$display .= '195 $line_item->'.$dimension.'=>'.$line_item->$dimension.'<br>';
      		    }
      		}                                                            //foreach($line_item as $key=>$value){$display .= '197 '.$key.'=>'.$value.'<br>';}
    //@EDIT 2016-04-22 - SAJ - begin
      		$create_item = ($line_item['retain_line_label'] == 'create');    	  		
      		$link_item   = ($line_item['retain_line_label'] == 'link');
    //@EDIT 2016-04-22 - SAJ - end
      		if ($link_item){                                             $display .= '200 retain_line_label = '.$line_item['retain_line_label'].'<br>$line_item->title: '.$line_item['title'].'<br>';
                $linked_item = elgg_get_entities_from_relationship(array(
        			'type'                 => 'object',
        			'relationship'         => $item_type,
        			'relationship_guid'    => $line_item_guid,
        			'inverse_relationship' => true,
        			'limit'                => false,
        		));
      		}                                            
    //@EDIT 2016-11-09 - SAJ
            if (isset($line_item['item_guid'])){
    	  		$link_item = true;
    	  		$linked_item = get_entity($line_item['item_guid']);       $display .= '190 $linked_item->guid = '.$linked_item->guid.'<br>190 $line_item->title: '.$line_item->title.'<br>';
      		}
//foreach($line_item as $key=>$value){$display .= '215 '.$key.'=>'.$value.'<br>';} //goto eof; continue;
      		if (isset($line_item) && ($create_item || $link_item)){              $display .= '221 $line_item && ($create_item = '.$create_item.' || $link_item = '.$link_item.')<br>'; //goto eof;
      			if (empty($linked_item) && $create_item){                 $display .= '222 empty($linked_item) && $create_item<br>';
    				$linked_item          = new ElggObject();
    				$linked_item->subtype = 'market';
    				$linked_item->title   = $line_item->title;
    				if ($linked_item->save()){
    					// link new item to line item
    					add_entity_relationship($linked_item->getguid(), $relationship, $line_item_guid);
    					// link new item to receipt item
    					add_entity_relationship($guid, "{$subtype}_{$aspect}", $linked_item->getguid());
    					$line_item['retain_line_label'] = 'no';              
    					$line_item['item_guid']         = $linked_item->getGUID();
    					$line_item->save();                                $display .= '233 linked_item saved<br>'; $display .= '162 retain_line_label = '.$line_item->retain_line_label.'<br>';
    				}
    			}
//foreach($line_item as $key=>$value){$display .= '204 '.$key.'=>'.$value.'<br>';} continue;
    //          Associate line item with item
    			if ($link_item){                                              $display .= '238 Associate line item with item<br>';
    				$guid_one     = $line_item_guid;
    				$relationship = strtolower($line_item->link_type);
    				$guid_two     = $line_item['item_guid'];                  $display .= '241 $guid_one: '.$guid_one.', $relationship: '.$relationship.', $guid_two: '.$guid_two.'<br>';
    				if ($relationship == 'part' || $relationship == 'supply'){
    					if (!check_entity_relationship($guid_one, $relationship, $guid_two)){
    						add_entity_relationship($guid_one, $relationship, $guid_two);					
    					}
    				}
    				if ($relationship == 'warranty'){
    					$linked_item['relationship'] = $line_item['title'];   $display .= '248 $linked_item->'.$relationship.' = '.$linked_item->$relationship.'<br>';
    				}
    			}
    	  	}
//foreach($line_item as $key=>$value){$display .= '220 '.$key.'=>'.$value.'<br>';} continue;
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
//foreach($line_item as $key=>$value){$display .= '267.1 '.$key.'=>'.$value.'<br>';} foreach($new_line_item as $key=>$value){$display .= '267.2 '.$key.'=>'.$value.'<br>';} //goto eof;
    		if ($line_item['unpack'] == 1                    &&
    		    $line_item['qty'] >= 1                        ){           $display .= '269 $line_item->unpack = true<br>'; $display .= '191 $linked_item->guid: '.$line_item->item_guid.'<br>';
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
    						));                                        $display .= '278 $linked_item[0]->getGUID() '.$linked_item[0]->getGUID().'<br>';
    			
    		    $linked_items = count($linked_item);                   $display .= '280 $linked_items: '.$linked_items.'<br>';
    			$piece_count   = count($pieces);                       $display .= '281 $piece_count: '.$piece_count.'<br>';
    			if ($linked_items == 1){
    				$container_guid = $linked_item[0]->getGUID();
    			}
    			else {                                                 $display .= '285 $container_guid: '.$container_guid.'<br>';
    				$container_guid = elgg_get_logged_in_user_guid ();
    			}
    			$n = 0;
    			for ($i = $n+1; $i <= ($line_item['qty'] - $piece_count); $i++) { //Set the new items to unpack as the difference between what exists and what is requested
    				$new_item = new ElggObject();
    				$new_item->subtype = 'item';                        $display .= '291 $new_item->subtype: '.$new_item->subtype.'<br>';
    				$new_item->title   = $line_item->title;             $display .= '292 $new_item->title: '.$new_item->title.'<br>';
    				$new_item->container_guid = $container_guid;        $display .= '293 $new_item->container_guid: '.$new_item->container_guid.'<br>';
    				$new_item->save();
    				add_entity_relationship($new_item->getguid(), 'receipt_item', $jot->getguid());
    			}
    		}
//foreach($line_item as $key=>$value){$display .= '298 '.$key.'=>'.$value.'<br>';}// continue;     		
    //      Store the current value of the line item
    		$line_item->total = $line_item->qty * $line_item->cost;       $display .= '304 $line_item->total: '.$line_item->total.'<br>';
    		if ($line_item->distribute_freight == 1){
    			$line_item->shipping_cost = $jot->shipping_cost * ($line_item->qty/$item_qty);
    		}
    		if ($line_item['taxable'] == 1){
    			$line_item->sales_tax = $jot->sales_tax * ($line_item->qty/$item_qty);
    		}
    		if ($line_item->save()){                                    $display .= '311 $line_item->guid: '.$line_item->guid.'<br>';
    	 		$subtotal                         += $line_item->total;
    		}
    		else {
    			register_error(elgg_echo('jot:save:failure'));
    		} 
//      Associate line item with item
            unset($guid_one, $guid_two, $guid_three);
    	    $guid_one     = $line_item->guid;
			$relationship = 'receipt_item';
			$guid_two     = $line_item->item_guid;                    //$display .= '302 $guid_one: '.$guid_one.', $relationship: '.$relationship.', $guid_two: '.$guid_two.'<br>'; goto eof;
			$guid_three   = $jot->getguid();
			$related_items = get_entity_relationships($guid_one);      //$display .= '$related_items'.print_r($related_items, true).'<br>';
			if (!check_entity_relationship($guid_one, $relationship, $guid_two)){
				add_entity_relationship($guid_one, $relationship, $guid_two);					
			}
//      Associate line item with jot
    		if (!check_entity_relationship($guid_one, $relationship, $guid_three)){
    			add_entity_relationship($guid_one, $relationship, $guid_three);
    		}
    	    if (!check_entity_relationship($guid_three, 'transfer_receipt', $guid_two) && $line_item->getSubtype() == 'receipt_item'){
				add_entity_relationship($guid_three, 'transfer_receipt', $guid_two);					
			}
     	  } //END foreach ($line_items as $line => $values)
     	  
     	  $jot->subtotal = $subtotal;
     	  $jot->total  = $jot->subtotal + $jot->shipping_cost + $jot->sales_tax; 
     	  if (!$jot->save()) {
     	  	register_error(elgg_echo('jot:save:failure'));
     	  	$error = TRUE;
     	  }
      }  //@END: 182 foreach ($line_items as $line => $values)
  } //@END: 152 if (isset($items))
} //@END: 104 if (!$error && $aspect == 'receipt') 

if (!$error && $aspect == 'return'){                                                $display .= 'Processing return ...<br>';
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
/*	if (elgg_view_exists("forms/jot/edit/$category")) {
		// Forward to the next form in the wizard sequence
		forward("jot/edit_more/{$jot->guid}/$category");
	} 
*/	if (!$jot_exists){
		$action_type  = 'create';
		$subject_guid = $jot->owner_guid;
		$object_guid  = $jot->guid;
		$target_guid  = $jot->asset;                               $display .= '358 $type: '.$type.'<br>$subtype: '.$subtype.'<br>';
		elgg_create_river_item(array(
			'action_type'   => $action_type,
			'view'          => 'river/object/jot/'.$action_type,
			'subject_guid'  => $subject_guid,
			'object_guid'   => $object_guid,
			'target_guid'   => $target_guid,
		));
	
//		register_error($display);
		if ($presentation == 'box'){
//		    forward($referrer);
		}
		else {
		    forward("jot/edit/$object_guid/$aspect?origin=$target_guid");
		}
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
