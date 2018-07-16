<?php
/**
 * Elgg jot Plugin
 * @package jot
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 */
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
 * 
 */


// Make sure we're logged in (send us to the front page if not)
gatekeeper();

// Get input data
$guid         = (int) get_input('guid', 0);      //Receipt guid
$aspect       = get_input('aspect', 'receipt');
$jot_return   = get_input('jot');                //Array of receipt return values
$return_items = get_input('return_item');        //Array of line items
$item_type    = get_input('item_type');
$referrer     = get_input('referrer');
$relationship = $item_type;
$receipt      = get_entity($guid);               //Array of receipt values
$merchant_guid = $receipt->merchant_guid;
$user_guid    = elgg_get_logged_in_user_guid();

foreach ($jot_return as $name=>$value){
	$display .= '$jot_return.'.$name.'=>'.$value.'<br>';
}

//elgg_make_sticky_form('jotForm');

	  
// Determine whether Merchant exists	  
if (elgg_entity_exists($merchant_guid)){$merchant_exists = TRUE;}
else                                   {$merchant_exists = FALSE;}
$display     .= '$guid: '.$guid.'<br>
		         $merchant: '.$receipt->merchant.'<br>';
if ($merchant_exists)             {$display.='merchant exists<br>';} 
else                              {$display.='merchant does not exist<br>';}
	  
if ($merchant_exists == FALSE){
	/*Attempt to match merchant to existing merchant by name
	  If exact match found
		* Obtain merchant guid 
	  if no exact match found
		* Create the merchant
		* Set $merchant_exists to True
	 * Set $receipt->merchant_guid to merchant guid
	*/
	
	$merchant_name = $receipt->merchant;
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
		$receipt->merchant_guid = $existing_merchants[0]->guid;
	}
//if no exact match found
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
}

// Process returned items ...
foreach ($return_items as $key=>$values){                         $display .= '128 $key: '.$key.'<br>';
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
	  foreach ($line_items as $key=>$values){                     $display .= '142 $line_item: '.$key.'<br>';
	  	$item_qty += $values['qty'];

	  	foreach($values as $key_value=>$value){                   $display .= '145 $values: '.$key_value.'=>'.$value.'<br>';
	  	}
	  }

// Create Credit transaction	  
    $credit_transaction = new ElggObject();
	$credit_transaction->subtype            = 'transfer';
	$credit_transaction->aspect             = 'credit';
	$credit_transaction->title              = 'Credit';
	$credit_transaction->owner_guid         = $user_guid;
    $credit_transaction->container_guid     = $user_guid;
    $credit_transaction->credit_date        = $jot_return['return_date'];
    $credit_transaction->expiration_date    = $jot_return['expiration_date'];
    $credit_transaction->sales_receipt_guid = $receipt->guid;
    $credit_transaction->returned_by        = $user_guid;
    $credit_transaction->credit_amount      = $jot_return['credit'];                $display .= '159 $credit_transaction->credit_amount: '.$credit_transaction->credit_amount.'<br>';
    $credit_transaction->save();
    iF ($merchant_exists){
		/* Associate the Credit transaction with the merchant
		 	* Mark stage as 'sent to merchant'
		 	* Create river entry for workflow stage
		 * Auto-process the Credit transaction
		 	*   auto-accept Credit transaction
		 */
		$credit_transaction->stage = 'sent to merchant';
		if ($credit_transaction->save()){                  
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
    add_entity_relationship($receipt->guid, 'return', $credit_transaction->guid);
// Apply return line items to Credit transaction
	foreach ($line_items as $line => $values) {
	  	unset($return_item);
	  	$return_item = $values;
		unset($line_item_guid);
	  	$line_item_guid = $return_item['line_item_guid'];                            $display .= '166 $line_item_guid: '.$line_item_guid.'<br>';
  		$receipt_item   = get_entity($line_item_guid);                               $display .= '167 $receipt_item->item_guid: '.$receipt_item->item_guid.'<br>';
  		$linked_item    = get_entity($receipt_item->item_guid);                    
  		
  		$credit_line_item = new ElggObject();
  		$credit_line_item->title                  = $receipt_item->title;                      $display .= '170 $receipt_item->title: '.$receipt_item->title.'<br>';
  		$credit_line_item->subtype                = $receipt_item->getSubtype(); 
  		$credit_line_item->aspect                 = 'credit';
  		$credit_line_item->owner_guid             = $user_guid;
  		$credit_line_item->container_guid         = $user_guid;
  		$credit_line_item->receipt_line_item_guid = $return_item['line_item_guid'];             $display .= '175 $credit_line_item->receipt_line_item_guid: '.$credit_line_item->receipt_line_item_guid.'<br>';
  		$credit_line_item->credit_qty             = $return_item['qty'];                        $display .= '176 $credit_line_item->credit_qty: '.$credit_line_item->credit_qty.'<br>';
  		$credit_line_item->linked_item_guid       = $linked_item->guid;                         $display .= '177 $credit_line_item->linked_item_guid: '.$credit_line_item->linked_item_guid.'<br>';
		$credit_line_item->credit_value           = $return_item['received_value'];             $display .= '178 $credit_line_item->credit_value: '.$credit_line_item->credit_value.'<br>';
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
  		
  		
		$receipt_item->status     = 'returned';
		$receipt_item->save();
		$linked_item->owner_guid = $merchant_guid;
		$linked_item->save();
	    
		// Associate receipt line item with credit line item
		if (check_entity_relationship($line_item_guid, 'return', $credit_line_item->guid) == false ){
			add_entity_relationship ($line_item_guid, 'return', $credit_line_item->guid);
		}
		// Associate credit line item with credit transaction
		if (check_entity_relationship($credit_line_item->guid, 'return_item', $credit_transaction->guid) == false ){
			add_entity_relationship ($credit_line_item->guid, 'return_item', $credit_transaction->guid);
		}
  }
//forward($referrer);
register_error($display);