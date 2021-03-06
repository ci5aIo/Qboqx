<!-- Form: jot\views\default\forms\transfers\elements\receipt.php -->
<?php
/**********
 * Version 03
 * 
 */

setlocale(LC_MONETARY, 'en_US');
$transfer_guid  = (int) get_input('guid');                             $display       .= '$transfer_guid='.$transfer_guid.'<br>';
$section        = get_input('section');                                $display       .= '$section='.$section.'<br>';
$aspect         = elgg_extract('aspect',$vars);                        $display       .= '<br>$aspect='.$aspect.'<br>';
//$aspect         = $section;
//$aspect         = $vars['aspect'];
$asset          = elgg_extract('asset', $vars);
$container_guid = elgg_extract('container_guid', $vars);
$owner_guid     = elgg_extract('owner_guid', $vars, elgg_get_logged_in_user_guid());
$referrer       = elgg_extract('referrer', $vars);                     
$subtype        = 'transfer';
$graphics_root  = elgg_get_site_url().'_graphics';
$presentation   = elgg_extract('presentation', $vars, 'full');         $display       .= '$presentation='.$presentation.'<br>';

$exists = true; //  Does a transfer receipt already exist?  Initialize to 'true'
if ($transfer_guid == 0){
	$exists = false;
}
else {
	$transfer = get_entity($transfer_guid);
    // can pass an entity guid to receipt to begin a new receipt record for that entity
    if (elgg_instanceof($transfer, 'object', 'market') || elgg_instanceof($transfer, 'object', 'item')){
        $originating_item     = $transfer;
        $title                = $originating_item->title;
        $receipt_item_details = array('retain_line_label' => 'no',
                                      'item_guid'         => $originating_item->guid,
                                      'qty'               => 1,
                                     );
        $receipt_items[] = $receipt_item_details;
        
        $existing_item   = true;                                                        $display .= '38 $existing_item: '.$existing_item.'<br>38 $receipt_items[0][retain_line_label]: '.$receipt_items[0]['retain_line_label'].'<br>';
        $asset           = $transfer_guid;
        $guid            = 0;
//        $hidden_fields['guid'] = $guid;
        // Determine whether a linked receipt item already exists ...
        $existing_linked_item = elgg_get_entities_from_relationship(array(
    				'type' => 'object',
    				'relationship' => 'receipt_item',
    				'relationship_guid' => $transfer_guid,
    				'inverse_relationship' => true,
    				'limit' => 1,
    			));
        if ($existing_linked_item){
            $display .= 'guid: '.$existing_linked_item[0]->guid.'<br>';
            $existing_transfers = elgg_get_entities_from_relationship(array(
    				'type' => 'object',
    				'relationship_guid' => $existing_linked_item[0]->guid,
    				'inverse_relationship' => false,
    				'limit' => 1,
    			));
            $transfer_guid         = $existing_transfers[0]->guid;
            $entity                = get_entity($transfer_guid);
            $title                 = $entity->title;
            $hidden_fields['guid'] = $entity->guid;
            $exists = true;
        }
        else {
            $exists = false;
        }
        echo '<br>$transfer_type: '.$transfer_type;
    }
    else {
        $entity                = get_entity($transfer_guid);
        $title                 = $entity->title;
        $hidden_fields['guid'] = $transfer_guid;
        $exists                = true;
        $existing_item         = false; 
    }
}                                                                      $display .= '77 $exists: '.$exists.'<br>77 $existing_item: '.$existing_item.'<br>';

$hidden_fields['element_type']   = $element_type;
$hidden_fields['asset']          = $asset;
$hidden_fields['action']         = elgg_extract('action', $vars);
$hidden_fields['section']        = $section;
$hidden_fields['aspect']         = $aspect;
$hidden_fields['subtype']        = $subtype;
$hidden_fields['container_guid'] = $container_guid;
$hidden_fields['parent_guid']    = $vars['parent_guid'];
$hidden_fields['item_type']      = 'receipt_item';
$hidden_fields['presentation']   = $presentation;

$hidden_fields['jot[aspect]']         = $aspect;
$hidden_fields['jot[guid]']           = $guid;
$hidden_fields['jot[location]']       = elgg_extract('location', $vars);
$hidden_fields['jot[owner_guid]']     = $owner_guid;
$hidden_fields['jot[asset]']          = $asset;
$hidden_fields['jot[container_guid]'] = $container_guid;

if ($exists){
	// switch subtype if it exists
	$subtype       = $entity->getSubtype();
	$aspect        = $entity->aspect;
	$receipt_items = $receipt_items ?: elgg_get_entities_from_relationship(array(
                                    		'type'                 => 'object',
                                    		'relationship'         => 'receipt_item',
                                    		'relationship_guid'    => $transfer_guid,
                                    		'inverse_relationship' => true,
                                    		'limit'                => false,
                                    		// Receipt items must have a 'sort_order' value to apper in this group.  This value is applied by the actions pick and edit.
                                    		'order_by_metadata'    => array('name' => 'sort_order', 
                                    				                        'direction' => ASC, 
                                    				                        'as' => 'integer'),
                                    	));
    $merchant = $entity->merchant;                                        $display .= '111 $merchant: '.$merchant.'<br>';
    if (elgg_entity_exists($merchant) && is_int($merchant)/*accounts for merchants whose name begins with a number*/){
        if (isset($merchant) && elgg_entity_exists($merchant)){$merchants = array(get_entity($merchant));}     $display .= '112 isset($merchants): '.isset($merchants).'<br>113 $merchant: '.print_r($merchant, true).'<br>';
    	if (isset($merchant) && empty($merchants)){
        	$merchants = elgg_get_entities_from_relationship(array(
        		'type'                 => 'group',
        		'relationship'         => 'merchant_of',
        		'relationship_guid'    => $transfer_guid,
        	    'inverse_relationship' => true,
        		'limit'                => false,
        	));
    	}                                                                     $display .= '121 isset($merchants): '.isset($merchants).'<br>';
    	if (isset($merchant) && empty($merchants)){
    		// provided for backward compatibility
    		$merchants = elgg_get_entities_from_relationship(array(
    				'type'                 => 'group',
    				'relationship'         => 'supplier_of',
    				'relationship_guid'    => $transfer_guid,
    				'inverse_relationship' => true,
    				'limit'                => false,
    		));
    	}
    }                                                                        $display .= '131 isset($merchants): '.isset($merchants).'<br>';
}
else {
    unset($hidden_fields['guid']);
}                                                                                 //$display       .= '135 $merchants[0]->name = '.$merchants[0]->getGuid().'<br>';
                                                                                  $display       .= '136 $receipt_items[0][retain_line_label] = '.$receipt_items[0]['retain_line_label'].'<br>136 $receipt_items->retain_line_label = '.$receipt_items->retain_line_label.'<br>';
//if (empty($merchants) && isset($merchant)){$merchants = $merchant;}                                                                                  
?>
<script type="text/javascript">
$(document).ready(function() {
	// clone line item node
	$('.clone-line-item-action').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.line_item').html();
		$(html).insertBefore('.new_line_item');
	});
	// remove a node
	$('.remove-node').on('click', function(e){
		e.preventDefault();
		
		// remove the node
		$(this).parents('div').parents('div').eq(0).remove();
	});
});</script>
<?php
/*
$group_type = 'supplier';
$merchant_action     = 'groups/add/element';
$merchant_form_vars  = array(
                    'enctype'     => 'multipart/form-data', 
                    'name'        => 'group_list',
			 	    'action'      => "action/groups/add?element_type=$group_type&item_guid=$item_guid");
$merchant_body_vars  = array(
                    'item_guid'   => $item_guid,
			        'group_type'  => $group_type,
					'form_type'   => 'div');
$merchant_form = elgg_view_form($merchant_action, $merchant_form_vars, $merchant_body_vars);
*/
if ($exists){
	$pick_merchant = "<span title='Select merchant'>".
						elgg_view('output/url', array(
							'text' => 'pick', 
						    'class' => 'elgg-button-submit-element elgg-lightbox',
						    'data-colorbox-opts' => '{"width":500, "height":525}',
					        'href' => "market/groups/supplier/$transfer_guid/1/Merchant")).
					"</span>";
	$set_button = "<span title='Select receipt location'>".
						elgg_view('output/url', array(
							'text' => 'place ...',
							'class' => 'elgg-button-submit-element elgg-lightbox',
							'data-colorbox-opts' => '{"width":500, "height":525}',
							'href' => "places/set/". $transfer_guid)).
					"</span>";
}
else {
	$pick_merchant = "<span title='Save form before picking merchant'>".
						elgg_view('output/url', array(
							'text' => 'pick',
						    'class' => 'elgg-button-submit-element')).
					"<span>";
	$set_button = "<span title='Save form before placing receipt'>".
						elgg_view('output/url', array(
							'text' => 'place ...',
							'class' => 'elgg-button-submit-element')).
					"</span>";
}
//$merchant='';
if (isset($merchants)) {
	$element_type = 'merchant';
	foreach ($merchants as $i) {
	    if (elgg_entity_exists($i->guid)){
            $link         = elgg_view('input/grouppicker', array(
                							'name' => 'jot[merchant]',
                		                    'values' => $i->guid,
                                            'limit'=> 1,
                                            'autocomplete'=>'on',
                					));
/*        	$link         = elgg_view('output/url', array('text' => $i->name,'href' =>  "groups/profile/$i->guid"));
        	$merchant_guid = elgg_view('input/hidden', array('name' => 'merchant_guid', 'value' => $i->guid,));
        	$merchant_name = elgg_view('input/hidden', array('name' => 'merchant', 'value' => $i->name,));
        //	if ($i->canEdit()) {
        		$detach = elgg_view("output/url",array(
        	    	'href' => "action/jot/detach?element_type=$element_type&&guid=$i->guid&container_guid=$transfer_guid",
        	    	'text' => elgg_view_icon('unlink'),
        	    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked merchant'),
        	    	'encode_text' => false,
        	    ));
*/  	      }
//$merchant .= "$link<br>";
$merchant = "$detach $link $merchant_guid $merchant_name<br>";
	}
}	
elseif (isset($merchant)){                                                       $display .= '242 $merchant: '.$merchant.'<br>';
    $merchant = elgg_view('input/grouppicker', array(
    							'name' => 'jot[merchant]',
    		                    'value' => $merchant,
                                'limit'=> 1,
                                'autocomplete'=>'on',
    					));}
else {$merchant = elgg_view('input/grouppicker', array(
							'name' => 'jot[merchant]',
		                    'values' => $entity->merchant,
                            'limit'=> 1,
                            'autocomplete'=>'on',
					));}
                                                                                 $display .= '255 $merchant: '.$merchant.'<br>';
if ($presentation == 'full'){
?>
    <script type="text/javascript">
    // Add input fields when TAB pressed.  Source: http://jsbin.com/amoci/123/edit
    $(function () {
    	$(document).on('keydown', 'input.last_line_item', function(e) { 
    	    var keyCode = e.keyCode || e.which; 
    
    	    if (keyCode == 9) { 
    	      e.preventDefault(); 
    	      $(this).removeClass("last_line_item");
    		  var html = $('.line_items').html();
    		  $(html).insertBefore('.new_line_items');
    	    } 
    	});
    });
    </script>
<?php

foreach($hidden_fields as $name => $value){
    $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
}
	$title_input    = elgg_view('input/text'  , array('name' => 'jot[title]'                  , 'value' => $title,));
    $title_input   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][title]'        , 'value' => $title,));
    $cashier        = elgg_view('input/text'  , array('name' => 'jot[cashier]'                , 'value' => $entity->cashier,));
    $cashier       .= elgg_view('input/hidden', array('name' => 'jot[snapshot][cashier]'      , 'value' => $entity->cashier,));
    $purchase_date  = elgg_view('input/date'  , array('name' => 'jot[purchase_date]'          , 'value' => $entity->purchase_date,));
    $purchase_date .= elgg_view('input/hidden', array('name' => 'jot[snapshot][purchase_date]', 'value' => $entity->purchase_date,));
    $purchased_by   = elgg_view('input/text'  , array('name' => 'jot[purchased_by]'           , 'value' => $entity->purchased_by,));
    $purchased_by  .= elgg_view('input/hidden', array('name' => 'jot[snapshot][purchased_by]' , 'value' => $entity->purchased_by,));
    $order_no       = elgg_view('input/text'  , array('name' => 'jot[order_no]'               , 'value' => $entity->order_no,));
    $order_no      .= elgg_view('input/hidden', array('name' => 'jot[snapshot][order_no]'     , 'value' => $entity->order_no,));
    $invoice_no     = elgg_view('input/text'  , array('name' => 'jot[invoice_no]'             , 'value' => $entity->invoice_no,));
    $invoice_no    .= elgg_view('input/hidden', array('name' => 'jot[snapshot][invoice_no]'   , 'value' => $entity->invoice_no,));
    $document_no    = elgg_view('input/text'  , array('name' => 'jot[document_no]'            , 'value' => $entity->document_no,));
    $document_no   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][document_no]'  , 'value' => $entity->document_no,));
    $receipt_no     = elgg_view('input/text'  , array('name' => 'jot[receipt_no]'             , 'value' => $entity->receipt_no,));
    $receipt_no    .= elgg_view('input/hidden', array('name' => 'jot[snapshot][receipt_no]'   , 'value' => $entity->receipt_no,));

	$form .= "<div class='rTable' style='width:600px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:90px'>Title</div>
					<div class='rTableCell' style='width:510px'>$title_input
					</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:90px'>Merchant</div>
					<div class='rTableCell' style='width:510px'>".$merchant."</div>
				</div>			
			</div>
		</div>";
	
	$form .= "<div class='rTable' style='width:700px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:100px'>Sales Assoc.</div>
					<div class='rTableCell' style='width:200px'>$cashier</div>
					<div class='rTableCell' style='width:100px'>Date</div>
					<div class='rTableCell' style='width:120px'>$purchase_date</div>
					<div class='rTableCell' style='width:100px'></div>
					<div class='rTableCell' style='width:120px'></div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:100px'>Buyer</div>
					<div class='rTableCell' style='width:200px'>$purchased_by</div>
					<div class='rTableCell' style='width:100px'>Order #</div>
					<div class='rTableCell' style='width:120px'>$order_no</div>
					<div class='rTableCell' style='width:100px'>Invoice #</div>
					<div class='rTableCell' style='width:120px'>$invoice_no</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:100px'></div>
					<div class='rTableCell' style='width:200px'></div>
					<div class='rTableCell' style='width:100px'>Document #</div>
					<div class='rTableCell' style='width:120px'>$document_no</div>
					<div class='rTableCell' style='width:100px'>Receipt #</div>
					<div class='rTableCell' style='width:120px'>$receipt_no</div>
				</div>
			</div>
	</div>
	<div class='rTable' style='width:100%'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableHead' style='width:5%'>Qty</div>
					<div class='rTableHead' style='width:60%'>Item</div>
					<div class='rTableHead' style='width:5%'>tax?</div>
					<div class='rTableHead' style='width:10%'>Cost</div>
					<div class='rTableHead' style='width:10%'>Total</div>
				</div>
				";
                                                                               $display       .= '340 $receipt_items[0][retain_line_label] = '.$receipt_items[0]['retain_line_label'].'<br>';
	// Populate existing receipt items
	$n=0;
	foreach($receipt_items as $item){
		$n = $n+1;
		$element_type = 'receipt item';                                        $display .= '347 $item[retain_line_label]: '. $item['retain_line_label'].'<br>';
		$can_edit = $existing_item ?: $item->canEdit(); 
		$qty = $cost = NULL;
		unset($hidden_line_item_fields);
		if ($can_edit) {                                                       $display .= '351 can edit<br>';
			$delete = elgg_view("output/url",array(
		    	'href' => "action/jot/delete?guid=$item->guid&container_guid=$transfer_guid",
		    	'text' => elgg_view_icon('arrow-left'),
		    	'confirm' => sprintf('Remove receipt item?'),
		    	'encode_text' => false,
		    ));
			$select = elgg_view('input/checkbox', array('name'    => 'do_me',
														'value'   => $item->guid,
								        			    'default' => false,
								        			   ));
	        if ($existing_item){
	            $linked_item[] = $originating_item;                            $display .= '370 is_array($linked_item): '.is_array($linked_item).'<br>';
	            $item['retain_line_label'] = 'no';                             $display .= '372 $item[item_guid]: '.$item['item_guid'].'<br>';
	        }
	        elseif (!empty($item['item_guid'])){
	            $linked_item[] = get_entity($item['item_guid']);
	        }
	        else {                                                             $display .= '377 $linked_item->guid: '.$linked_item->guid.'<br>';
	            $linked_item = elgg_get_entities_from_relationship(array(
    				'type' => 'object',
    				'relationship' => 'receipt_item',
    				'relationship_guid' => $item->guid,
    				'inverse_relationship' => true,
    				'limit' => false,
    			));
		}                                                                      $display .= '384 $linked_item[0]->guid:'.$linked_item[0]->guid.'<br>';
	        
    
	$display .= 'Title: '.$title.'<br>';         
	$display .= '$item[retain_line_label]: '.$item['retain_line_label'].'<br>';
	$display .= '$item[item_guid]: '.$item['item_guid'].'<br>';
	$display .= '$item->guid: '.$item->guid.'<br>';
	$display .= '$linked_item->guid[0]: '.$linked_item[0]->guid.'<br>';
	$display .= '$existing_item: '.$existing_item.'<br>';
	
		    $hidden_line_item_fields['line_item[guid][]']              = $item->guid;
            $hidden_line_item_fields['line_item[subtype][]']           = 'receipt_item';
            $hidden_line_item_fields['line_item[retain_line_label][]'] = 'yes';
            $hidden_line_item_fields['line_item[timeline_label][]']    = 'Initial Purchase';
            $hidden_line_item_fields['line_item[show_on_timeline[]']   = 1;
            $hidden_line_item_fields['line_item[que_contribution][]']  = 'purchase';
            $hidden_line_item_fields['line_item[add_cost_to_que][]']   = 1;
            
	        if (is_array($linked_item) && ($item->retain_line_label == 'no' || $item['retain_line_label'] == 'no' || $bulk_transfer)){         $display .= '388 link'.$item->guid.'<br>';
	//         if (!empty($linked_item[0]) && ($item->retain_line_label != 'yes' // retained for backward compatibility 
	//         		                     || $item->retain_line_label != 'retain')){
	            $item_guid = $linked_item[0]->guid ?: $item->item_guid;
				$detach = elgg_view("output/url",array(
			    	'href' => "action/jot/detach?element_type=receipt_item&&guid=".$item_guid."&container_guid=$item->guid",
			    	'text' => elgg_view_icon('unlink'),
			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked item'),
			    	'encode_text' => false,
			    ));
				if (!$existing_item){
				    $line_item = $detach;
		            $hidden_line_item_fields['line_item[title][]']             = $item->title;
		            $hidden_line_item_fields['line_item[subtype][]']           = $item->getSubtype();
		            $hidden_line_item_fields['line_item[retain_line_label][]'] = $item->retain_line_label;
		            $hidden_line_item_fields['line_item[timeline_label][]']    = $item->timeline_label;
		            $hidden_line_item_fields['line_item[que_contribution][]']  = $item->que_contribution;
		            $hidden_line_item_fields['line_item[item_guid][]']         = $item->item_guid;
				}
	        	$line_item .= elgg_view('output/url', array(
	        			'text' =>  $linked_item[0]->title ?: $item->title,
	        			'href' =>  "market/view/$item_guid/Inventory",
		        		'class'=> 'rTableform90',
	        	    ));
		        if ($existing_item){
		            $hidden_line_item_fields['line_item[title][]']             = $originating_item->title;
		            $hidden_line_item_fields['line_item[item_guid][]']         = $originating_item->guid;
                    $hidden_line_item_fields['line_item[retain_line_label][]'] = 'no';
		        }
	        } else {                                                                                     $display .= '421 input<br>';
	        	$line_item = elgg_view('input/text', array(
	        			'name' => 'line_item[title][]',
	        			'value' => $item->title,
	        			'class'=> 'rTableform90',
	        	));        	
	        }
            $qty = $existing_item ? 1 :$item->qty;                                                 $display .= '$qty:'.$qty.'<br>';
            $cost = $existing_item ?$originating_item->acquire_cost :$item->cost;
//$display .= '$line_item: '.$line_item.'<br>';
	        if ($item->taxable == 1){
	        	$tax_options = array('name'    => 'line_item[taxable][]',
							         'checked' => 'checked',
							         'value'   => 1,
	        			             'default' => false,
	        			            );
	        } else {
	        	$tax_options = array('name'    => 'line_item[taxable][]',
							         'value'   => 1,
	        			             'default' => false,
								    );
	        }
	        $tax_check = elgg_view('input/checkbox', $tax_options);
	       	$pick = elgg_view('output/url', array(
	        		'text' => elgg_view_icon('settings-alt'),
	        		'class' => 'elgg-lightbox',
	        		'data-colorbox-opts' => '{"width":600, "height":525}',
	        		'href' => "market/pick/item/" . $item->guid));
	        $pick_menu = "<span title='Set line item properties'>$pick</span>";
	        $item_total = '';
	        if (!empty($item->total) && $item->sort_order == 1){
	        	$item_total = money_format('%#10n', $item->total);
	        }
	        else {
	        	$item_total = number_format($item->total, 2);
	        }
		}

	$form .= "	<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'>{$delete}{$select}</div>
					<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
																	'name' => 'line_item[qty][]',
																	'value' => $qty,
																))."</div>
					<div class='rTableCell' style='width:60%'>$pick_menu $line_item</div>
					<div class='rTableCell' style='width:5%'>$tax_check</div>
					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
																	'name' => 'line_item[cost][]',
																	'value' => $cost,
																))."</div>
					<div class='rTableCell' style='width:10%;text-align:right'>$item_total</div>";
                	foreach($hidden_line_item_fields as $name => $value){
                        $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
                    }
	$form .="   </div>";
	}
	// Populate blank lines
//	for ($i = $n+1; $i <= 3; $i++) {
	
		    $pick = elgg_view('output/url', array(
		        		'text' => elgg_view_icon('settings-alt')));
	        $pick_menu = "<span title='Save form before setting line item properties'>$pick</span>";
	/*        if ($exists){
		        $pick = elgg_view('output/url', array(
		        		'text' => elgg_view_icon('settings-alt'),
		        		'class' => 'elgg-lightbox',
		        		'data-colorbox-opts' => '{"width":500, "height":525}',
		        		'href' => "market/pick/item/" . $transfer_guid));
		        $pick_menu = "<span title='Set line item properties'>$pick</span>";
	        	    }
		    else {
		        $pick = elgg_view('output/url', array(
		        		'text' => elgg_view_icon('settings-alt')));
		        $pick_menu = "<span title='Save form before setting line item properties'>$pick</span>";
		    }
	*/			//'href' => "market/pick?element_type=item&container_guid=" . $transfer_guid));
	$form .= "	<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'><a href='#' class='remove-node'>[X]</a></div>
					<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
												'name' => 'line_item[qty][]',
											))."</div>
					<div class='rTableCell' style='width:60%'>".$pick_menu.' '.elgg_view('input/text', array(
												'name' => 'line_item[title][]',
							                    'class'=> 'rTableform90',
											))."</div>
					<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
												'name' => 'line_item[taxable][]',
												'value'=> 1,
	        			                        'default' => false,
											))."</div>
					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
												'name' => 'line_item[cost][]',
						 						'class' => 'last_line_item',
											))."</div>
					<div class='rTableCell' style='width:10%'></div>
				</div>";
	//}
	
	// Populate form footer
/*	$form .= "	<div class='rTableRow'>
					<div class='rTableCell'>
	        			<span title='New line item'>".
						elgg_view('output/url', array(
						    'text' => '+',
	//						'text' => 'add item',
							'href' => '#',
							'class' => 'elgg-button-submit-element clone-line-item-action' // unique class for jquery
							))."</span></div>
				</div>
*/
	$shipping_cost  = elgg_view('input/text', array('name' => 'jot[shipping_cost]','value' => $entity->shipping_cost,));
	$shipping_cost .= elgg_view('input/hidden', array('name' => 'jot[snapshot][shipping_cost]','value' => $entity->shipping_cost,));
	$sales_tax      = elgg_view('input/text', array('name' => 'jot[sales_tax]', 'value' => $entity->sales_tax,));
	$sales_tax     .= elgg_view('input/hidden', array('name' => 'jot[snapshot][sales_tax]', 'value' => $entity->sales_tax,));
	
	$form .= "<div class='new_line_items'></div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:60%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:10%'>Subtotal</div>
					<div class='rTableCell' style='width:10%;text-align:right'>".money_format('%#10n', $entity->subtotal)."</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:60%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:10%'>Shipping</div>
					<div class='rTableCell' style='width:10%'>$shipping_cost</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:60%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:10%'>Sales Tax</div>
					<div class='rTableCell' style='width:10%'>$sales_tax</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:60%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:10%'>Total</div>
					<div class='rTableCell' style='width:10%;text-align:right'>".money_format('%#10n', $entity->total_cost)."</div>
				</div>
			</div>
	</div>";
	/*
	foreach ($variables as $name => $type) {
	echo '<div><label>'.elgg_echo("transfer:$name").'</label>';
	if ($type != 'longtext') {
				echo '<br />';
			}
	echo elgg_view("input/$type", array(
				'name' => "$name",
				'value' => $vars[$name],
			)).'</div>';
	}
	*/
	$cancel_button = elgg_view('input/button', array(
			'value' => elgg_echo('cancel'),
			'href' =>  $entity ? $referrer : '#',
	));
	$delete_link = elgg_view("output/url",array(
		    	'href' => "action/jot/delete?guid=$transfer_guid&delete_contents=true",
		    	'text' => 'Delete',
		    	'confirm' => sprintf('Delete receipt?'),
		    	'encode_text' => false,
		    ));
	$delete_button = elgg_view('input/button', array(
			'value' => elgg_echo('cancel'),
			'href' =>  $delete_link,
	));
	
	$form .='<div class="elgg-foot">'.
	   elgg_view('input/submit', array('value' => elgg_echo('save'), 'name' => 'submit')).
	   elgg_view('input/submit', array('value' => 'Apply', 'name' => 'apply')).
	'<a href='.elgg_get_site_url() . $referrer.' class="cancel_button">Cancel</a>'.
	$delete_link.'
	</div>';
	if ($exists){
		$pick = elgg_view('output/url', array(
	        	'text' => elgg_view_icon('settings-alt'),
	//        	'text' => 'pick',
	        	'class' => 'elgg-lightbox',
	//        	'class' => 'elgg-button-submit-element elgg-lightbox',
				'data-colorbox-opts' => '{"width":500, "height":525}',
				'href' => "market/pick/item/" . $transfer_guid));
	    $pick_menu = "<span title='Set line item properties'>$pick</span>";
	}
	else {
		$pick = elgg_view('output/url', array(
	        	'text' => elgg_view_icon('settings-alt')));
	    $pick_menu = "<span title='Save form before setting line item properties'>$pick</span>";
	}
							
	$form .= 
	"<div id='line_store' style='visibility: hidden; display:inline-block;'>
		<div class='line_items'>
			<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'><a href='#' class='remove-node'>[X]</a></div>
					<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
												'name' => 'line_item[qty][]',
											))."</div>
					<div class='rTableCell' style='width:60%'>".$pick_menu.' '.elgg_view('input/text', array(
												'name' => 'line_item[title][]',
							                    'class'=> 'rTableform90',
											))."</div>
					<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
												'name' => 'line_item[taxable][]',
												'value'=> 1,
	        			                        'default' => false,
											))."</div>
					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
												'name' => 'line_item[cost][]',
						 						'class' => 'last_line_item',
											))."</div>
					<div class='rTableCell' style='width:10%'></div>
				</div>
		</div>
	</div>";
}
if ($presentation == 'box'){
    $form .= elgg_view('input/hidden', array('name' => 'jot[aspect]'        , 'value' => $aspect));
    $form .= elgg_view('input/hidden', array('name' => 'jot[owner_guid]'    , 'value' => elgg_extract('owner_guid', $vars)));
    $form .= elgg_view('input/hidden', array('name' => 'jot[asset]'         , 'value' => $asset));
    $form .= elgg_view('input/hidden', array('name' => 'jot[container_guid]', 'value' => $container_guid));
    
	$form .= "<div class='rTable' style='width:400px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Title</div>
				<div class='rTableCell' style='width:310px'>".elgg_view('input/text', array(
																	'name' => 'jot[title]',
						                                            'value' => $entity->title,
																))."
				</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90px'>Merchant ".$pick_merchant."</div>
				<div class='rTableCell' style='width:310px'>".$merchant."</div>
			</div>			
		</div>
	</div>";
	$form .= "<div class='rTable' style='width:400px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:75px'>Sales Assoc.</div>
					<div class='rTableCell' style='width:125px'>".elgg_view('input/text', array(
																		'name' => 'jot[cashier]',
																		'value' => $entity->cashier,
																	))."</div>
					<div class='rTableCell' style='width:75px'>Date</div>
					<div class='rTableCell' style='width:125px'>".elgg_view('input/date', array(
																		'name' => 'jot[purchase_date]',
																		'value' => $entity->purchase_date,
																	))."
					</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:75px'>Buyer</div>
					<div class='rTableCell' style='width:125px'>".elgg_view('input/text', array(
																		'name' => 'jot[purchased_by]',
																		'value' => $entity->purchased_by,
																	))."</div>
					<div class='rTableCell' style='width:75px'>Receipt #</div>
					<div class='rTableCell' style='width:125px'>".elgg_view('input/text', array(
																		'name' => 'jot[receipt_no]',
																		'value' => $entity->receipt_no,
																	))."
					</div>
				</div>
			</div>
	</div>
	<div class='rTable' style='width:400px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableHead' style='width:5%'>Qty</div>
					<div class='rTableHead' style='width:70%'>Item</div>
					<div class='rTableHead' style='width:5%'>tax?</div>
					<div class='rTableHead' style='width:10%'>Cost</div>
					<div class='rTableHead' style='width:10%'>Total</div>
				</div>";
	    $pick = elgg_view('output/url', array(
	        		'text' => elgg_view_icon('settings-alt')));
		$form .= "	<div class='rTableRow'>
						<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
													'name' => 'line_item[qty][]',
												))."</div>
						<div class='rTableCell' style='width:70%'>".elgg_view('input/text', array(
													'name' => 'line_item[title][]',
												))."</div>
						<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
													'name' => 'line_item[taxable][]',
													'value'=> 1,
		        			                        'default' => false,
												))."</div>
						<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
													'name' => 'line_item[cost][]',
							 						'class' => 'last_line_item',
												))."</div>
						<div class='rTableCell' style='width:10%'></div>
					</div>";
		
		// Populate form footer
		$form .= "<div class='new_line_items'></div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:70%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:10%'>Subtotal</div>
						<div class='rTableCell' style='width:10%;text-align:right'>".money_format('%#10n', $entity->subtotal)."</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:70%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:10%'>Shipping</div>
						<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
																			'name' => 'jot[shipping_cost]',
																			'value' => $entity->shipping_cost,
																		))."
						</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:70%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:10%'>Tax</div>
						<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
																			'name' => 'jot[sales_tax]',
																			'value' => $entity->sales_tax,
																		))."
						</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:70%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:10%'>Total</div>
						<div class='rTableCell' style='width:10%;text-align:right'>".money_format('%#10n', $entity->total_cost)."</div>
					</div>
				</div>
		</div>";
	$form .= 
	'<div class="elgg-foot">'.
		elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save'))).
		elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save:define'))).
		elgg_view("output/url",array(
		    	'href' => "action/jot/delete?guid=$item->guid&container_guid=$transfer_guid",
		    	'text' => 'Delete',
		    	'confirm' => sprintf('Delete receipt?'),
		    	'encode_text' => false,
		    )).'
     </div>';	
}
echo $form;
eof:
echo $display;
