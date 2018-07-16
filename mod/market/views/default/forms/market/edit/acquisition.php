<!-- View: market/views/default/forms/market/edit/acquisition.php -->
<?php
$item_guid = $vars['guid'];
$entity = get_entity($item_guid);
$entity_owner = get_entity($entity->owner_guid);

$receipts = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'transfer_receipt',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
));

$receipt_items = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'receipt_item',
		'relationship_guid' => $item_guid,
		'inverse_relationship' => false,
		'limit' => false,
));

$receipt_items = array_merge($receipt_items, elgg_get_entities_from_metadata(array(
		'type'                 => 'object',
		'subtype'              => 'receipt_item',
		'metadata_name_value_pairs' => array('name'=>'item_guid', 'value'=>$item_guid),
)));


$related_item_guids = array();
foreach($receipt_items as $receipt_item){
	if ($receipt_item->que_contribution == 'purchase'){
		$purchases[] = $receipt_item;
		$purchase_cost += $receipt_item->total + $receipt_item->shipping_cost + $receipt_item->sales_tax;
	}																		$display .= '$purchase_cost: '.$purchase_cost.'<br>$receipt_item->title: '.$receipt_item->title.'<br>$receipt_item->guid: '.$receipt_item->guid.'<br>';
	$linked_items = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'relationship' => 'receipt_item',
			'relationship_guid' => $receipt_item->guid,
			'inverse_relationship' => true,
			'limit' => false,
	));
	foreach($linked_items as $linked_item){
		$related_item_guids['linked_item']  = $linked_item->guid;
		$related_item_guids['receipt_item'] = $receipt_item->guid;			$display .= '$linked_item->guid: '.$linked_item->guid.'<br>';
	}
}
$transfer = $receipts[0];
	$transfer_guid = $transfer->guid;										$display .= '$transfer_guid: '.$transfer_guid.'<br>';
	$merchant = '';
	$merchants = elgg_get_entities_from_relationship(array(
		'type'                 => 'group',
		'relationship'         => 'merchant_of',
		'relationship_guid'    => $transfer_guid,
	    'inverse_relationship' => true,
		'limit'                => false,
	));
	if (!$merchants){
		// provided for backward compatibility
		$merchants = elgg_get_entities_from_relationship(array(
				'type'                 => 'group',
				'relationship'         => 'supplier_of',
				'relationship_guid'    => $transfer_guid,
				'inverse_relationship' => true,
				'limit'                => false,
			));
		}
	if ($merchants){
		$i        = $merchants[0];
		$merchant = elgg_view('output/url', array('text' => $i->name,'href' =>  "groups/profile/$i->guid"));
		$merchant = $i->guid;
	}
	else {
		$merchant = $transfer->merchant;
	}
	$pick_merchant = "<span title='Select merchant'>".
						elgg_view('output/url', array(
							'text' => 'pick', 
						    'class' => 'elgg-button-submit-element elgg-lightbox',
						    'data-colorbox-opts' => '{"width":500, "height":525}',
					        'href' => "market/groups/supplier/$transfer_guid/1/Merchant")).
					"</span>";												$display .= 'Merchant: '.$merchant.'<br>';

if ($transfer){
	$transfer_item  = elgg_view('output/url', ['text'  => elgg_view('jot/icon', ['jot'  => $transfer, 
			                                                                     'size' => 'tiny']),
			                                   'href'  =>  "jot/edit/$transfer->guid", 
			                                   'title' => sprintf(elgg_echo('jot:edit'), 'receipt')]);

               //$previous_owner = $merchant;
               if (elgg_entity_exists($merchant)){
                   $previous_owner = elgg_view('input/grouppicker', array(
                							'name' => 'item[last_owner]',
                		                    'values' => $merchant,
                                            'limit'=> 1,
                                            'autocomplete'=>'on',
                					));
               }
               else {
                   $previous_owner = elgg_view('input/grouppicker', array(
                							'name' => 'item[last_owner]',
                		                    'value' => $merchant,
                                            'limit'=> 1,
                                            'autocomplete'=>'on',
                					));                   
               }
               
               $date_acquired  = $transfer->purchase_date;
               setlocale(LC_MONETARY, 'en_US');
               $amount_paid    = money_format('%.2n', $purchase_cost);
}
else          {//$transfer_item   = $pick_merchant;
	           $create_transfer = elgg_view('input/checkbox', array('name' => 'create_transfer',)).'create new Transfer';
			   $owner_value     = $entity->last_owner ?: 'pick a merchant or type a name';
	           //$previous_owner  = elgg_view('input/text', array('name' => 'item[last_owner]', 'value' => $owner_value, 'onfocus' => "if (this.value=='pick a merchant or type a name') this.value=''", 'onblur'  => "if (this.value=='') this.value='pick a merchant or type a name'", 'onsubmit'=> "if (this.value=='pick a merchant or type a name') this.value=''",));
	           if (elgg_entity_exists($owner_value)){
    	           $previous_owner  = elgg_view('input/grouppicker', array(
                    							'name' => 'item[last_owner]',
    	                                        'values'=>$owner_value,
                    		                    'limit'=> 1,
                                                'autocomplete'=>'on',
                    					));
	           }
	           else {
    	           $previous_owner  = elgg_view('input/grouppicker', array(
                    							'name' => 'item[last_owner]',
    	                                        'value'=>$owner_value,
                    		                    'limit'=> 1,
                                                'autocomplete'=>'on',
                    					));
	           }
               $date_acquired   = elgg_view('input/date', array('name' => 'item[moment]', 'value' => $entity->moment));
               $amount_paid     = elgg_view('input/text', array('name' => 'item[acquire_cost]', 'value' => $entity->acquire_cost));
}

$acquisition_content = "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Condition</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".
					elgg_view('input/radio', array('name' => 'item[acquire_condition]',
                                                    'value' => $entity->acquire_condition, 
													'options'=>array('New' => 'new',
																	 'Used' => 'used',
															         'Refurbished' =>'refurfished'),
							                        'align' => 'horizontal'))."
				</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Acquired from $transfer_item</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>$previous_owner</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Date acquired</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>$date_acquired</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Amount paid</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>$amount_paid</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>$create_transfer</div>
			</div>";

$acquisition_content .= "</div>
</div>";
echo "$acquisition_content";
//echo $display;
