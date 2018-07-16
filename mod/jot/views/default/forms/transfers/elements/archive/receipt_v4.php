<!-- Form: jot\views\default\forms\transfers\elements\receipt.php -->
<?php
/**********
 * Version 03
 * 
 */

setlocale(LC_MONETARY, 'en_US');
$guid  = (int) get_input('guid', 0);                             $display       .= '$transfer_guid='.$transfer_guid.'<br>';
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
$origin         = elgg_extract('origin', $vars);
$action         = elgg_extract('action', $vars);
$transfer_exists= true;   // Does a transfer receipt already exist?  Initialize to 'true'
$item_exists    = false;  // Does the managed item exist in Quebx already?  Initialize to 'false' 
if ($guid == 0){
	$transfer_exists = false;
}
else {
    $entity                = get_entity($guid);
    $title                 = $entity->title;
    $hidden_fields['guid'] = $entity->guid;
    if ((elgg_instanceof($entity, 'object', 'market') || elgg_instanceof($entity, 'object', 'item')) || !empty($asset)) {
    // Can pass an entity guid to receipt to begin a new receipt record for that entity
        $originating_item     = $asset ?: $entity;
        unset($entity);
        $title                = $originating_item->title;
        $receipt_items[0]['retain_line_label'] = 'no';
        $receipt_items[0]['item_guid']         = $originating_item->guid;
        $receipt_items[0]['qty']               = 1;
        $merchant_name                         = $originating_item->last_owner;
        $item_exists          = true;
        $origin               = $guid;
        $guid                 = 0;
    // Determine whether a linked receipt item already exists for this managed item ...
        $existing_linked_item = elgg_get_entities_from_relationship(array(
    				'type' => 'object',
    				'relationship' => 'receipt_item',
    				'relationship_guid' => $originating_item->guid,
    				'inverse_relationship' => true,
    				'limit' => 1,
    			));
        if ($existing_linked_item){
            $existing_transfers = array_merge(
                elgg_get_entities_from_relationship(array(
    				'type' => 'object',
    				'relationship_guid' => $existing_linked_item[0]->guid,
    				'inverse_relationship' => false,
    				'limit' => 1,)),
                elgg_get_entities(array(
                    'type'    => 'object',
                    'subtype' => 'transfer',
                    'container_guid' => $existing_linked_item[0]->guid,
                )));
            $existing_transfer     = $existing_transfers[0]->guid;
            $transfer_guid         = $existing_transfer->guid;
            $transfer              = get_entity($transfer_guid);
            $transfer_exists = true;
        }
        else {
            $transfer_exists = false;
            $entity = $entity ?: $asset;
        }
    }
    else {
        $transfer_guid   = $guid;
        $transfer        = get_entity($transfer_guid);
        $transfer_exists = true;
    }
}
                                                                      $display .= '76 $transfer_exists: '.$transfer_exists.'<br>76 $item_exists: '.$item_exists.'<br>';

$hidden_fields['element_type']   = $element_type;
$hidden_fields['action']         = $action;
$hidden_fields['section']        = $section;
$hidden_fields['aspect']         = $aspect;
$hidden_fields['subtype']        = $subtype;
$hidden_fields['container_guid'] = $container_guid;
$hidden_fields['parent_guid']    = $vars['parent_guid'];
$hidden_fields['item_type']      = 'receipt_item';
$hidden_fields['presentation']   = $presentation;

$hidden_fields['jot[aspect]']         = $aspect;
$hidden_fields['jot[guid]']           = $transfer_guid;
$hidden_fields['jot[location]']       = elgg_extract('location', $vars);
$hidden_fields['jot[owner_guid]']     = $owner_guid;
$hidden_fields['jot[container_guid]'] = $container_guid;

if ($transfer_exists){ 
	// switch subtype if it exists
	$subtype       = $transfer->getSubtype();
	$aspect        = $transfer->aspect;
	$receipt_items = elgg_get_entities_from_relationship(array(
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
	$receipt_items = $receipt_items ?: elgg_get_entities_from_metadata(array(
	                                        'type'                 => 'object',
	                                        'subtype'              => 'receipt_item',
	                                        'container_guid'       => $transfer_guid,
                                    		// Receipt items must have a 'sort_order' value to apper in this group.  This value is applied by the actions pick and edit.
                                    		'order_by_metadata'    => array('name' => 'sort_order', 
                                    				                        'direction' => ASC, 
                                    				                        'as' => 'integer'),
	                 ));

    $merchant = $transfer->merchant;                                        $display .= '111 $merchant: '.$merchant.'<br>';
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
    unset($hidden_fields['guid'], $hidden_fields['jot[guid]']);
    if (!empty($asset)){
        if (elgg_entity_exists($asset->last_owner)){
            $merchants[0] = get_entity($asset->last_owner);
        }
        else {
            $merchant = $asset->last_owner;
        }
    }
}                                                                                 //$display       .= '135 $merchants[0]->name = '.$merchants[0]->getGuid().'<br>';
                                                                                  $display       .= '136 $receipt_items[0][retain_line_label] = '.$receipt_items[0]['retain_line_label'].'<br>136 $receipt_items->retain_line_label = '.$receipt_items->retain_line_label.'<br>';
//if (empty($merchants) && isset($merchant)){$merchants = $merchant;}                                                                                  
/*
$group_type = 'supplier';
$merchant_action     = 'groups/add/element';
$merchant_form_vars  = array(
                    'enctype'     => 'multipart/form-data', 
                    'name'        => 'group_list',
			 	    'action'      => "action/groups/add?element_type=$group_type&item_guid=$receipt_item_guid");
$merchant_body_vars  = array(
                    'item_guid'   => $receipt_item_guid,
			        'group_type'  => $group_type,
					'form_type'   => 'div');
$merchant_form = elgg_view_form($merchant_action, $merchant_form_vars, $merchant_body_vars);
*/
if ($transfer_exists){
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
$merchant = "$link";
//$merchant = "$detach $link $merchant_guid $merchant_name<br>";
	}
}	
elseif (elgg_instanceof(get_entity($merchant), 'group')){                                                       $display .= '242 $merchant: '.$merchant.'<br>';
    $merchant = elgg_view('input/grouppicker', array(
							'name' => 'jot[merchant]',
		                    'values' => $transfer->merchant,
                            'limit'=> 1,
                            'autocomplete'=>'on',
					));}
else {
    $merchant = elgg_view('input/grouppicker', array(
							'name' => 'jot[merchant]',
		                    'value'  => $merchant,
                            'limit'=> 1,
                            'autocomplete'=>'on',
					));}
$set_properties_button = elgg_view('output/url', array(
		                   'id'    => 'properties_set',
                           'text'  => elgg_view_icon('settings-alt'), 
                           'title' => 'set properties',
));
                                                                                 $display .= '255 $merchant: '.$merchant.'<br>';

foreach($hidden_fields as $name => $value){
    $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
}
	$title_input    = elgg_view('input/text'  , array('name' => 'jot[title]'                  , 'value' => $title,));
    $title_input   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][title]'        , 'value' => $title,));
    $cashier        = elgg_view('input/text'  , array('name' => 'jot[cashier]'                , 'value' => $transfer->cashier,));
    $cashier       .= elgg_view('input/hidden', array('name' => 'jot[snapshot][cashier]'      , 'value' => $transfer->cashier,));
    $purchase_date  = elgg_view('input/date'  , array('name' => 'jot[purchase_date]'          , 'value' => $transfer->purchase_date,));
    $purchase_date .= elgg_view('input/hidden', array('name' => 'jot[snapshot][purchase_date]', 'value' => $transfer->purchase_date,));
    $purchased_by   = elgg_view('input/text'  , array('name' => 'jot[purchased_by]'           , 'value' => $transfer->purchased_by,));
    $purchased_by  .= elgg_view('input/hidden', array('name' => 'jot[snapshot][purchased_by]' , 'value' => $transfer->purchased_by,));
    $order_no       = elgg_view('input/text'  , array('name' => 'jot[order_no]'               , 'value' => $transfer->order_no,));
    $order_no      .= elgg_view('input/hidden', array('name' => 'jot[snapshot][order_no]'     , 'value' => $transfer->order_no,));
    $invoice_no     = elgg_view('input/text'  , array('name' => 'jot[invoice_no]'             , 'value' => $transfer->invoice_no,));
    $invoice_no    .= elgg_view('input/hidden', array('name' => 'jot[snapshot][invoice_no]'   , 'value' => $transfer->invoice_no,));
    $document_no    = elgg_view('input/text'  , array('name' => 'jot[document_no]'            , 'value' => $transfer->document_no,));
    $document_no   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][document_no]'  , 'value' => $transfer->document_no,));
    $receipt_no     = elgg_view('input/text'  , array('name' => 'jot[receipt_no]'             , 'value' => $transfer->receipt_no,));
    $receipt_no    .= elgg_view('input/hidden', array('name' => 'jot[snapshot][receipt_no]'   , 'value' => $transfer->receipt_no,));

//goto eof;

Switch ($presentation){
	case 'full':
		$form .= "<div class='rTable' style='width:600px'>
				<div class='rTableBody'>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:90px'>Title</div>
						<div class='rTableCell' style='width:510px'>$title_input</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:90px'>Merchant</div>
						<div class='rTableCell' style='width:510px'>$merchant</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:90px'>Seller</div>
						<div class='rTableCell' style='width:510px'>$seller</div>
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
					<div id='sortable'>
					<div class='rTableRow pin'>
						<div class='rTableCell' style='width:0%;text-align:right'>".
							elgg_view('output/url', array(
							    'text' => '+',
								'href' => '#',
								//'class' => 'elgg-button-submit-element clone-receipt-action',
								'class' => 'elgg-button-submit-element new-receipt-item',
								'data-element'=> 'new_receipt_item',
								))."</div>
						<div class='rTableHead' style='width:5%'>Qty</div>
						<div class='rTableHead' style='width:70%'>Item</div>
						<div class='rTableHead' style='width:0%'>tax?</div>
						<div class='rTableHead' style='width:10%'>Cost</div>
						<div class='rTableHead' style='width:10%'>Total</div>
					</div>
					";
	                                                                               $display       .= '347 $receipt_items[0][retain_line_label] = '.$receipt_items[0]['retain_line_label'].'<br>';
	//goto eof;
		// Populate existing receipt items
		$n=0;
		$delete = elgg_view('output/url', array(
		        'title'=>'remove receipt item',
		        'class'=>'remove-node',
		        'style'=> 'cursor:pointer',
		        'text' => elgg_view_icon('delete-alt'),
		));
		if (!empty($receipt_items)){
	    	foreach($receipt_items as $receipt_item){
		        $n++;
		        unset($line_item);
	    		$element_type = 'receipt item';                                        $display .= '360 $receipt_item[retain_line_label]: '. $receipt_item['retain_line_label'].'<br>';
	    		$can_edit = $item_exists ?: $receipt_item->canEdit(); 
	    		$qty = $cost = NULL;
	    		unset($hidden_line_item_fields, $receipt_item_total, $receipt_item_guid, $linked_item);
	    		if ($can_edit) {                                                       $display .= '352 can edit<br>';
	    			$select = elgg_view('input/checkbox', array('name'    => 'do_me',
	    														'value'   => $receipt_item->guid,
	    								        			    'default' => false,
	    								        			   ));
	    	        if ($item_exists){
	    	            $linked_item[] = $originating_item;                            $display .= '358 is_array($linked_item): '.is_array($linked_item).'<br>';
	    	            $receipt_item->retain_line_label = 'no';                       $display .= '359 $receipt_item[item_guid]: '.$receipt_item['item_guid'].'<br>';
	    	        }
	    	        elseif (!empty($receipt_item->item_guid)){
	    	            $linked_item[] = get_entity($receipt_item->item_guid);
	    	        }
	    	        else {                                                             $display .= '364 $linked_item->guid: '.$linked_item->guid.'<br>';
	    	            $linked_item = elgg_get_entities_from_relationship(array(
	        				'type' => 'object',
	        				'relationship' => 'receipt_item',
	        				'relationship_guid' => $receipt_item->guid,
	        				'inverse_relationship' => true,
	        				'limit' => false,
	        			));
	    		}                                                                       $display .= '371 $linked_item[0]->guid:'.$linked_item[0]->guid.'<br>';
																		            	$display .= '372 Title: '.$title.'<br>';         
																		            	$display .= '373 $receipt_item[retain_line_label]: '.$receipt_item['retain_line_label'].'<br>';
																		            	$display .= '374 $receipt_item[item_guid]: '.$receipt_item['item_guid'].'<br>';
																		            	$display .= '375 $receipt_item->guid: '.$receipt_item->guid.'<br>';
																		            	$display .= '376 $item_exists: '.$item_exists.'<br>';
	    		    //populate defaults
	    		    $hidden_line_item_fields['line_item['.$receipt_item->guid.'][guid]']              = $receipt_item->guid;
	                $hidden_line_item_fields['line_item['.$receipt_item->guid.'][sort_order]']        = $receipt_item->sort_order;
	                $line_item = elgg_view('input/text', array(
	                		'name' => 'line_item['.$receipt_item->guid.'][title]',
	                		'value' => $receipt_item->title,
	                		'class'=> 'rTableform90',
	                ));
	                
	    	        if (is_array($linked_item) 
	    	                && 
	    	             (   $receipt_item->retain_line_label   == 'no'
	    	              || $receipt_item['retain_line_label'] == 'no' 
	    	              || $bulk_transfer)){                                           $display .= '391 $receipt_item->guid: '.$receipt_item->guid.'<br>';
	    	//         if (!empty($linked_item[0]) && ($receipt_item->retain_line_label != 'yes' // retained for backward compatibility 
	    	//         		                     || $receipt_item->retain_line_label != 'retain')){
	    	            $receipt_item_guid = $linked_item[0]->guid ?: $receipt_item->item_guid;
	    				$detach = elgg_view("output/url",array(
	    			    	'href' => "action/jot/detach?element_type=receipt_item&guid=".$receipt_item_guid."&container_guid=$receipt_item->guid",
	    			    	'text' => elgg_view_icon('unlink'),
	    			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked item'),
	    			    	'encode_text' => false,
	    			    ));
	    				if (!$item_exists){                                              $display .= '401 !$item_exists<br>';
	    				    $line_item = $detach;
	    		            //$hidden_line_item_fields['line_item['.$receipt_item->guid.'][title]']             = $receipt_item->title;
	//     		            $hidden_line_item_fields['line_item[subtype][]']           = $receipt_item->getSubtype();
	//     		            $hidden_line_item_fields['line_item[retain_line_label][]'] = $receipt_item->retain_line_label;
	//     		            $hidden_line_item_fields['line_item[timeline_label][]']    = $receipt_item->timeline_label;
	//     		            $hidden_line_item_fields['line_item[que_contribution][]']  = $receipt_item->que_contribution;
	//    		            $hidden_line_item_fields['line_item[item_guid][]']         = $receipt_item->item_guid;
	    				}
	    	        	$line_item .= elgg_view('output/url', array(
	    	        			'text' =>  $linked_item[0]->title ?: $receipt_item->title,
	    	        			'href' =>  "market/view/$receipt_item_guid/Inventory",
	    		        		'class'=> 'rTableform90',
	    	        	    ));
	    		        if ($item_exists && $n==1){                                               $display .= '426 $item_exists<br>';
	    		            unset ($hidden_line_item_fields['line_item['.$receipt_item->guid.'][guid]']);
	    		            //$hidden_line_item_fields['line_item['.$receipt_item->guid.'][title]'] = $originating_item->title;
	//		                $hidden_line_item_fields['line_item[item_guid][]']   = $originating_item->guid;
			                $receipt_item_guid                                   = $originating_item->guid;
	//                         $hidden_line_item_fields['line_item[retain_line_label][]'] = 'no';
	    		        }
	    	        }
	    	        elseif (elgg_entity_exists($receipt_item->guid)){
	    	            // Unset these values because they are included in the receipt_item properties card
	    	            unset($hidden_line_item_fields['line_item['.$receipt_item->guid.'][title]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][subtype]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][retain_line_label]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][timeline_label]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][show_on_timeline]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][guid]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][item_guid]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][que_contribution]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][add_cost_to_que]']
	    	                 );
	    	        }                                                                          $display .= '437 $receipt_item_guid: '.$receipt_item_guid.'<br>';
	                $qty = $item_exists  ? 1
	                                       : $receipt_item->qty;                                  $display .= '439 $qty:'.$qty.'<br>';
	                $cost = $item_exists ? $originating_item->acquire_cost 
	                                       : $receipt_item->cost;
	    //$display .= '$line_item: '.$line_item.'<br>';
	    	        if ($receipt_item->taxable == 1){
	    	        	$tax_options = array('name'    => 'line_item['.$receipt_item->guid.'][taxable]',
	    							         'checked' => 'checked',
	    							         'value'   => 1,
	    	        			             'default' => false,
	    	        			            );
	    	        } else {
	    	        	$tax_options = array('name'    => 'line_item['.$receipt_item->guid.'][taxable]',
	    							         'value'   => 1,
	    	        			             'default' => false,
	    								    );
	    	        }
	    	        $tax_check = elgg_view('input/checkbox', $tax_options);
	    	        $href      = "market/pick/item/" . $receipt_item->guid;
	    	        if (!empty($origin)){
	    	            $href .= "?origin=$origin";
	    	        }
	    	       	$pick = elgg_view('output/url', array(
	    	        		'text' => elgg_view_icon('settings-alt'),
	    	        		'class' => 'elgg-lightbox',
	    	        		'data-colorbox-opts' => '{"width":600, "height":525}',
	    	        		'href' => $href));
	    	        $pick_menu = "<span title='Set line item properties'>$pick</span>";
	    //if (elgg_view_exists('forms/market/pick')){echo "'market/pick' exists<br>";} else {echo "'market/pick' not found<br>";}
	    	        if (!empty($receipt_item->total)){
	    	        	$receipt_item_total = $receipt_item->sort_order == 1 ? money_format('%#10n', $receipt_item->total) 
	    	        	                                     : number_format($receipt_item->total, 2);
	    	        }
	    	        else {
	    	            $receipt_item_total = $cost * $qty; 
	    	        }
	    		}
	//echo $form; goto eof;
	    		
	        	$form .= "<div class='rTableRow' style='cursor:move'>
	        					<div class='rTableCell' style='width:0%'>$delete</div>
	        					<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
	        																	'name' => 'line_item['.$receipt_item->guid.'][qty]',
	        																	'value' => $qty,
	        																))."</div>
	        					<div class='rTableCell' style='width:70%'>$set_properties_button $line_item
	        					    <div class='receipt-item-properties' style='display:none'>".
	        					                                           elgg_view('forms/market/properties', array(
	                                        	                                   'element_type'   => 'receipt_item',
	                                        	                                   //**Note: Don't getGUID() or getContainerGUID().  These functions fail for new receipt items from an existing managed item
	        					                                                   'guid'           => $receipt_item->guid,
	        					                                                   'container_guid' => $receipt_item->container_guid,
	                                        	                                   'container_type' => 'transfer',
	                                        	                                   'origin'         => $origin,
	        					                                                   'sort_order'     => $n,
	        					                                                   'item'           => get_entity($receipt_item_guid),
	                                        	        ))."
	        					    </div>
	        					</div>
	        					<div class='rTableCell' style='width:0%'>$tax_check</div>
	        					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
	        																	'name' => 'line_item['.$receipt_item->guid.'][cost]',
	        																	'value' => $cost,
	        																))."</div>
	        					<div class='rTableCell' style='width:10%;text-align:right'>$receipt_item_total</div>";
	                        	foreach($hidden_line_item_fields as $name => $value){
	                                $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
	                            }
	        	$form .="   </div>";
	        	}
			}
		// Populate blank lines
		for ($i = $n+1; $i <= 1; $i++) {
		
			    $pick = elgg_view('output/url', array(
			        		'text' => elgg_view_icon('settings-alt')));
		        $pick_menu = "<span title='Save form before setting line item properties'>$pick</span>";
		/*        if ($transfer_exists){
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
		$form .= "<div class='rTableRow' style='cursor:move'>
						<div class='rTableCell' style='width:0%'>$delete</div>
						<div class='rTableCell' style='width:5%'>".elgg_view('input/hidden', ['name' => 'line_item[][new_line]']).
						                                           elgg_view('input/text', ['name' => 'line_item[][qty]'])."</div>
						<div class='rTableCell' style='width:70%'>$set_properties_button ".elgg_view('input/text', array(
													'name' => 'line_item[][title]',
								                    'class'=> 'rTableform90',
												))."
						    <div class='receipt-item-properties' style='display:none'>".
						                                           elgg_view('forms/market/properties', array(
	                                	                                   'element_type'   =>'receipt_item',
	                                	                                   'container_type' => 'transfer',
	                                	                                   'origin'         => $origin,
	                                	        ))."
						    </div>
			            </div>
						<div class='rTableCell' style='width:0%'>".elgg_view('input/checkbox', array(
													'name' => 'line_item[][taxable]',
													'value'=> 1,
		        			                        'default' => false,
												))."</div>
						<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
													'name' => 'line_item[][cost]',
							 						'class' => 'last_line_item',
												))."</div>
						<div class='rTableCell' style='width:10%'></div>";
	/*                	foreach($hidden_blank_line_item_fields as $name => $value){
	                        $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
	                    }
	*/			$form .= "	</div>";
		}
		
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
		$subtotal       = $item_exists ? $receipt_item_total
		                                 : money_format('%#10n', $transfer->subtotal);
		$shipping_cost  = elgg_view('input/text', array('name' => 'jot[shipping_cost]','value' => $transfer->shipping_cost,));
		$shipping_cost .= elgg_view('input/hidden', array('name' => 'jot[snapshot][shipping_cost]','value' => $transfer->shipping_cost,));
		$sales_tax      = elgg_view('input/text', array('name' => 'jot[sales_tax]', 'value' => $transfer->sales_tax,));
		$sales_tax     .= elgg_view('input/hidden', array('name' => 'jot[snapshot][sales_tax]', 'value' => $transfer->sales_tax,));
		$sales_tax_rate = $transfer->sales_tax_rate;
		$total          = $item_exists ? $receipt_item_total
		                                 : money_format('%#10n', $transfer->total);
		if (!empty($sales_tax_rate)){
		    $sales_tax_rate_label = '('.number_format($sales_tax_rate*100, 0).'%)';
		}
		
		$form .= "<div class='new_line_items'></div>
				  <div class='rTableRow pin'>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:65%'></div>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:15%'>Subtotal</div>
						<div class='rTableCell' style='width:10%;text-align:right'>$subtotal</div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:65%'></div>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:15%'>Shipping</div>
						<div class='rTableCell' style='width:10%'>$shipping_cost</div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:65%'></div>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:15%'>Sales Tax $sales_tax_rate_label</div>
						<div class='rTableCell' style='width:10%'>$sales_tax</div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:65%'></div>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:15%'>Total</div>
						<div class='rTableCell' style='width:10%;text-align:right'>$total</div>
					</div>
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
				'href' =>  $transfer ? $referrer : '#',
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
		if ($transfer_exists){
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
			<div class='receipt_line_items'>
				<div class='rTableRow' style='cursor:move'>
						<div class='rTableCell' style='width:0%'>$delete</div>
						<div class='rTableCell' style='width:5%'>".elgg_view('input/hidden', ['name' => 'line_item[][new_line]']).
						                                           elgg_view('input/text', ['name' => 'line_item[][qty]'])."</div>
						<div class='rTableCell' style='width:70%'>$set_properties_button ".elgg_view('input/text', array(
													'name' => 'line_item[][title]',
								                    'class'=> 'rTableform90',
						                            'id'   => 'line_item_input',
												))."
			    	        <div class='receipt-item-properties' style='display:none'>".
		                                           elgg_view('forms/market/properties', array(
	                	                                   'element_type'   =>'receipt_item',
	                	                                   'container_type' => 'transfer',
	                	                                   'origin'         => $origin,
	                	        ))."
	        			    </div>
			            </div>
						<div class='rTableCell' style='width:0%'>".elgg_view('input/checkbox', array(
													'name' => 'line_item[][taxable]',
													'value'=> 1,
		        			                        'default' => false,
												))."</div>
						<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
													'name' => 'line_item[][cost]',
							 						'class' => 'last_line_item',
												))."</div>
						<div class='rTableCell' style='width:10%'></div>";
	/*                	foreach($hidden_blank_line_item_fields as $name => $value){
	                        $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
	                    }*/
	$form .= "	</div>
			</div>";
	$form .= "<div class = 'receipt_line_properties'>
				    <div class='properties' style='display:none'>".
				                                           elgg_view('forms/market/properties', array(
	                        	                                   'element_type'   =>'receipt_item',
	                        	                                   'container_type' => 'transfer',
	                        	                                   'origin'         => $origin,
	                        	        ))."
				    </div>
	    	 </div>";
	
	$form .= "
		</div>";
	
	break; // $presentation == 'full'

/***********************************************************
 * BOX presentation
 ***********************************************************/
	case 'box':
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
							                                            'value' => $transfer->title,
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
																			'value' => $transfer->cashier,
																		))."</div>
						<div class='rTableCell' style='width:75px'>Date</div>
						<div class='rTableCell' style='width:125px'>".elgg_view('input/date', array(
																			'name' => 'jot[purchase_date]',
																			'value' => $transfer->purchase_date,
																		))."
						</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:75px'>Buyer</div>
						<div class='rTableCell' style='width:125px'>".elgg_view('input/text', array(
																			'name' => 'jot[purchased_by]',
																			'value' => $transfer->purchased_by,
																		))."</div>
						<div class='rTableCell' style='width:75px'>Receipt #</div>
						<div class='rTableCell' style='width:125px'>".elgg_view('input/text', array(
																			'name' => 'jot[receipt_no]',
																			'value' => $transfer->receipt_no,
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
														'name' => 'line_item[][qty]',
													))."</div>
							<div class='rTableCell' style='width:70%'>".elgg_view('input/text', array(
														'name' => 'line_item[][title]',
													))."</div>
							<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
														'name' => 'line_item[][taxable]',
														'value'=> 1,
			        			                        'default' => false,
													))."</div>
							<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
														'name' => 'line_item[][cost]',
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
							<div class='rTableCell' style='width:10%;text-align:right'>".money_format('%#10n', $transfer->subtotal)."</div>
						</div>
						<div class='rTableRow'>
							<div class='rTableCell' style='width:5%'></div>
							<div class='rTableCell' style='width:70%'></div>
							<div class='rTableCell' style='width:5%'></div>
							<div class='rTableCell' style='width:10%'>Shipping</div>
							<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
																				'name' => 'jot[shipping_cost]',
																				'value' => $transfer->shipping_cost,
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
																				'value' => $transfer->sales_tax,
																			))."
							</div>
						</div>
						<div class='rTableRow'>
							<div class='rTableCell' style='width:5%'></div>
							<div class='rTableCell' style='width:70%'></div>
							<div class='rTableCell' style='width:5%'></div>
							<div class='rTableCell' style='width:10%'>Total</div>
							<div class='rTableCell' style='width:10%;text-align:right'>".money_format('%#10n', $transfer->total_cost)."</div>
						</div>
					</div>
			</div>";
		$form .= 
		'<div class="elgg-foot">'.
			elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save'))).
			elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save:define'))).
			elgg_view("output/url",array(
			    	'href' => "action/jot/delete?guid=$receipt_item->guid&container_guid=$transfer_guid",
			    	'text' => 'Delete',
			    	'confirm' => sprintf('Delete receipt?'),
			    	'encode_text' => false,
			    )).'
	     </div>';	
		break; //@END 747 $presentation == 'box'

/***********************************************************
 * COMPACT presentation
 ***********************************************************/
		case 'compact':
	        Switch ($action){
	            case 'add':
	                $title = 'Add Receipt';
	                break;
	            case 'edit':
	                $title = 'Edit Receipt';
			        break;
	            case 'view':
			        $title = 'Receipt';
			        break;        
	        }
	        $form .= "<b>$title</b><br>";
	    	$form .= "<div class='rTable' style='width:540px'>
				<div class='rTableBody'>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:14%'>Title</div>
						<div class='rTableCell' style='width:86%'>$title_input</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:14%'>Merchant</div>
						<div class='rTableCell' style='width:86%'>$merchant</div>
					</div>
				</div>
			</div>";
		
		$form .= "<div class='rTable' style='width:540px'>
				<div class='rTableBody'>
					<div class='rTableRow'>
						<div class='rTableCell' style='width:14%'>Associate</div>
						<div class='rTableCell' style='width:28%'>$cashier</div>
						<div class='rTableCell' style='width:14%'>Date</div>
						<div class='rTableCell' style='width:21%'>$purchase_date</div>
						<div class='rTableCell' style='width:14%'></div>
						<div class='rTableCell' style='width:21%'></div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'>Buyer</div>
						<div class='rTableCell'>$purchased_by</div>
						<div class='rTableCell'>Order #</div>
						<div class='rTableCell'>$order_no</div>
						<div class='rTableCell'>Invoice #</div>
						<div class='rTableCell'>$invoice_no</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>Doc #</div>
						<div class='rTableCell'>$document_no</div>
						<div class='rTableCell'>Receipt #</div>
						<div class='rTableCell'>$receipt_no</div>
					</div>
				</div>
		</div>";
		$form .= "
	    <div class='rTable' style='width:100%'>
			<div class='rTableBody'>
				<div id='sortable'>
				<div class='rTableRow pin'>
	                <div class='rTableCell' style='width:0%'>".
	    				elgg_view('output/url', array(
	    				    'text' => '+',
	    					'href' => '#',
							//'class' => 'elgg-button-submit-element clone-receipt-action',
							'class' => 'elgg-button-submit-element new-receipt-item',
							'data-element'=> 'new_receipt_item',
	    					))."
					</div>
	    			<div class='rTableHead' style='width:10%'>Qty</div>
	    			<div class='rTableHead' style='width:65%'>Item</div>
	    			<div class='rTableHead' style='width:5%'>tax?</div>
	    			<div class='rTableHead' style='width:10%'>Cost</div>
	    			<div class='rTableHead' style='width:10%'>Total</div>
	    		</div>
				";
	/*	$form .= "<div class='rTable' style='width:100%'>
				<div class='rTableBody'>
					<div id='sortable'>
					<div class='rTableRow pin'>
				            <div class='rTableCell'>
	                        	<div class='rTable' style='width:100%'>
	                        			<div class='rTableBody'>
	                        				<div class='rTableRow'>
	                        					<div class='rTableCell' style='width:0%'>".
	                                    								elgg_view('output/url', array(
	                                    								    'text' => '+',
	                                    									'href' => '#',
	                                    									'class' => 'elgg-button-submit-element clone-receipt-action' // unique class for jquery
	                                    									))."</div>
	                        					<div class='rTableHead' style='width:10%'>Qty</div>
	                        					<div class='rTableHead' style='width:65%'>Item</div>
	                        					<div class='rTableHead' style='width:5%'>tax?</div>
	                        					<div class='rTableHead' style='width:10%'>Cost</div>
	                        					<div class='rTableHead' style='width:10%'>Total</div>
	                        				</div>
	                                     </div>
						             </div>
						        </div>
						   </div>
					";*/
	    $set_properties_button = elgg_view('output/url', array(
		                           'id'    => 'properties_set',
	                               'text'  => elgg_view_icon('settings-alt'), 
	                               'title' => 'set properties',
	    ));
	
		// Populate existing receipt items
		$n=0;
		$delete = elgg_view('output/url', array(
		        'title'=>'remove receipt item',
		        'class'=>'remove-receipt-node',
		        'style'=> 'cursor:pointer',
		        'text' => elgg_view_icon('delete-alt'),
		));
		if (!empty($receipt_items)){
	    	foreach($receipt_items as $receipt_item){
	    		$n++;
	    		$element_type = 'receipt item';                                        $display .= '360 $receipt_item[retain_line_label]: '. $receipt_item['retain_line_label'].'<br>';
	    		$can_edit = $item_exists ?: $receipt_item->canEdit(); 
	    		$qty = $cost = NULL;
	    		unset($hidden_line_item_fields, $receipt_item_total);
	    		if ($can_edit) {                                                       $display .= '364 can edit<br>';
	    			$select = elgg_view('input/checkbox', array('name'    => 'do_me',
	    														'value'   => $receipt_item->guid,
	    								        			    'default' => false,
	    								        			   ));
	    	        if ($item_exists){
	    	            $linked_item[] = $originating_item;                            $display .= '381 is_array($linked_item): '.is_array($linked_item).'<br>';
	    	            $receipt_item['retain_line_label'] = 'no';                     $display .= '382 $receipt_item[item_guid]: '.$receipt_item['item_guid'].'<br>';
	    	        }
	    	        elseif (!empty($receipt_item['item_guid'])){
	    	            $linked_item[] = get_entity($receipt_item['item_guid']);
	    	        }
	    	        else {                                                             $display .= '387 $linked_item->guid: '.$linked_item->guid.'<br>';
	    	            $linked_item = elgg_get_entities_from_relationship(array(
	        				'type' => 'object',
	        				'relationship' => 'receipt_item',
	        				'relationship_guid' => $receipt_item->guid,
	        				'inverse_relationship' => true,
	        				'limit' => false,
	        			));
	    		}                                                                      $display .= '395 $linked_item[0]->guid:'.$linked_item[0]->guid.'<br>';
	    	
	    		    //populate defaults
	    		    $hidden_line_item_fields['line_item['.$receipt_item->guid.'][guid]']              = $receipt_item->guid;
	//                 $hidden_line_item_fields['line_item[subtype]']           = 'receipt_item';
	//                 $hidden_line_item_fields['line_item[retain_line_label]'] = 'yes';
	//                 $hidden_line_item_fields['line_item[timeline_label]']    = 'Initial Purchase';
	//                 $hidden_line_item_fields['line_item[show_on_timeline']   = 1;
	//                 $hidden_line_item_fields['line_item[que_contribution]']  = 'purchase';
	//                 $hidden_line_item_fields['line_item[add_cost_to_que]']   = 1;
	                $hidden_blank_line_item_fields = $hidden_line_item_fields;
	                unset($hidden_blank_line_item_fields['line_item[guid]']);
	                
	    	        if (is_array($linked_item) 
	    	                && 
	    	             ($receipt_item->retain_line_label == 'no'
	    	              || $receipt_item['retain_line_label'] == 'no' 
	    	              || $bulk_transfer)){                                           $display .= '407 link'.$receipt_item->guid.'<br>';
	    	//         if (!empty($linked_item[0]) && ($receipt_item->retain_line_label != 'yes' // retained for backward compatibility 
	    	//         		                     || $receipt_item->retain_line_label != 'retain')){
	    	            $receipt_item_guid = $linked_item[0]->guid ?: $receipt_item->item_guid;
	    				$detach = elgg_view("output/url",array(
	    			    	'href' => "action/jot/detach?element_type=receipt_item&guid=".$receipt_item_guid."&container_guid=$receipt_item->guid",
	    			    	'text' => elgg_view_icon('unlink'),
	    			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked item'),
	    			    	'encode_text' => false,
	    			    ));
	    				if (!$item_exists){                                              $display .= '417 !$item_exists<br>';
	    				    $line_item = $detach;
	    		            //$hidden_line_item_fields['line_item['.$receipt_item->guid.'][title]']             = $receipt_item->title;
	//     		            $hidden_line_item_fields['line_item['.$receipt_item->guid.'][subtype]']           = $receipt_item->getSubtype();
	//     		            $hidden_line_item_fields['line_item['.$receipt_item->guid.'][retain_line_label]'] = $receipt_item->retain_line_label;
	//     		            $hidden_line_item_fields['line_item['.$receipt_item->guid.'][timeline_label]']    = $receipt_item->timeline_label;
	//     		            $hidden_line_item_fields['line_item['.$receipt_item->guid.'][que_contribution]']  = $receipt_item->que_contribution;
	//     		            $hidden_line_item_fields['line_item['.$receipt_item->guid.'][item_guid]']         = $receipt_item->item_guid;
	    				}
	    	        	$line_item .= elgg_view('output/url', array(
	    	        			'text' =>  $linked_item[0]->title ?: $receipt_item->title,
	    	        			'href' =>  "market/view/$receipt_item_guid/Inventory",
	    		        		'class'=> 'rTableform90',
	    	        	    ));
	    		        if ($item_exists & $n==1){                                               $display .= '426 $item_exists<br>';
	    		            unset ($hidden_line_item_fields['line_item['.$receipt_item->guid.'][guid]']);
	    		            //$hidden_line_item_fields['line_item['.$receipt_item->guid.'][title]']             = $originating_item->title;
	    		            $hidden_line_item_fields['line_item['.$receipt_item->guid.'][item_guid]']         = $originating_item->guid;
	                        $hidden_line_item_fields['line_item['.$receipt_item->guid.'][retain_line_label]'] = 'no';
	    		        }
	    	        }
	    	        elseif (elgg_entity_exists($receipt_item->guid)){
	    	            // Unset these values because they are included in the receipt_item properties card
	    	            unset($hidden_line_item_fields['line_item['.$receipt_item->guid.'][title]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][subtype]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][retain_line_label]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][timeline_label]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][show_on_timeline]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][guid]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][item_guid]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][que_contribution]'],
	    		              $hidden_line_item_fields['line_item['.$receipt_item->guid.'][add_cost_to_que]']
	    	                 );
	        	        $line_item = elgg_view('input/text', array(
	        	        			'name' => 'line_item['.$receipt_item->guid.'][title]',
	        	        			'value' => $receipt_item->title,
	        	        			'class'=> 'rTableform90',
	        	        	        ));
	    	        } 
	    	        else {                                                                       $display .= '432 input<br>';
	    	        	$line_item = elgg_view('input/text', array(
	    	        			'name' => 'line_item['.$receipt_item->guid.'][title]',
	    	        			'value' => $receipt_item->title,
	    	        			'class'=> 'rTableform90',
	    	        	));        	
	    	        }
	                $qty = $item_exists  ? 1
	                                       : $receipt_item->qty;                                  $display .= '479 $qty:'.$qty.'<br>';
	                $cost = $item_exists ? $originating_item->acquire_cost 
	                                       : $receipt_item->cost;
	    	        if ($receipt_item->taxable == 1){
	    	        	$tax_options = array('name'    => 'line_item['.$receipt_item->guid.'][taxable]',
	    							         'checked' => 'checked',
	    							         'value'   => 1,
	    	        			             'default' => false,
	    	        			            );
	    	        } else {
	    	        	$tax_options = array('name'    => 'line_item['.$receipt_item->guid.'][taxable]',
	    							         'value'   => 1,
	    	        			             'default' => false,
	    								    );
	    	        }
	    	        $tax_check = elgg_view('input/checkbox', $tax_options);
	    	        $href      = "market/pick/item/" . $receipt_item->guid;
	    	        if (!empty($origin)){
	    	            $href .= "?origin=$origin";
	    	        }
	    	       	$pick = elgg_view('output/url', array(
	    	        		'text' => elgg_view_icon('settings-alt'),
	    	        		'class' => 'elgg-lightbox',
	    	        		'data-colorbox-opts' => '{"width":600, "height":525}',
	    	        		'href' => $href));
	    	        $pick_menu = "<span title='Set line item properties'>$pick</span>";
	    //if (elgg_view_exists('forms/market/pick')){echo "'market/pick' exists<br>";} else {echo "'market/pick' not found<br>";}
	    	        if (!empty($receipt_item->total)){
	    	        	$receipt_item_total = $receipt_item->sort_order == 1 ? money_format('%#10n', $receipt_item->total) 
	    	        	                                     : number_format($receipt_item->total, 2);
	    	        }
	    	        else {
	    	            $receipt_item_total = $cost * $qty; 
	    	        }
	    		}
	//echo $form; goto eof;
	    		
	        	$form .= "<div class='rTableRow' style='cursor:move'>
	    					<div class='rTableCell' style='width:0%'>$delete$select</div>
	    					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
	    																	'name' => 'line_item['.$receipt_item->guid.'][qty]',
	    																	'value' => $qty,
	    																))."</div>
	    					<div class='rTableCell' style='width:65%'>$set_properties_button $line_item
	    					    <div class='receipt-item-properties' style='display:none'>".
	                               elgg_view('forms/market/properties', array(
		                                   'element_type'   => 'receipt_item',
		                                   //**Note: Don't getGUID() or getContainerGUID().  These functions fail for new receipt items from an existing managed item
	                                       'guid'           => $receipt_item->guid,
	                                       'container_guid' => $receipt_item->container_guid,
		                                   'container_type' => 'transfer',
		                                   'origin'         => $origin,
	                                       'sort_order'     => $n,
	                                       'item'           => get_entity($receipt_item_guid),
	                    	        ))."
	    					    </div>
	   					    </div>
	    					<div class='rTableCell' style='width:5%'>$tax_check</div>
	    					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
	    																	'name' => 'line_item['.$receipt_item->guid.'][cost]',
	    																	'value' => $cost,
	    																))."</div>
	    					<div class='rTableCell' style='width:10%;text-align:right'>$receipt_item_total</div>";
	                    	foreach($hidden_line_item_fields as $name => $value){
	                            $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
	                        }
	        	$form .= "</div>";
	        	}
			}
		// Populate blank lines
		for ($i = $n+1; $i <= 1; $i++) {
		
			    $pick = elgg_view('output/url', array(
			        		'text' => elgg_view_icon('settings-alt')));
			    $form .= "
				        <div class='rTableRow receipt_item' style='cursor:move'>
	                        <div class='rTableCell' style='width:0%'>$delete</div>
	    					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
	    												'name' => 'line_item[][qty]',
	    											))."</div>
	    					<div class='rTableCell' style='width:65%'>$set_properties_button ".elgg_view('input/text', array(
	    												'name' => 'line_item[][title]',
	    							                    'class'=> 'rTableform90',
	    											))."
	                             <div class='receipt-item-properties' style='display:none'>".
	                                   elgg_view('forms/market/properties', array(
	    	                                   'element_type'   =>'receipt_item',
	    	                                   'container_type' => 'transfer',
	    	                                   'origin'         => $origin,
	                        	        ))."
	                             </div>
	    		            </div>
	    					<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
	    												'name' => 'line_item[][taxable]',
	    												'value'=> 1,
	    	        			                        'default' => false,
	    											))."</div>
	    					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
	    												'name' => 'line_item[][cost]',
	    						 						'class' => 'last_line_item',
	    											))."</div>
	    					<div class='rTableCell' style='width:10%'></div>";
			    if ($hidden_blank_line_item_fields){
	                	foreach($hidden_blank_line_item_fields as $name => $value){
	                        $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
	                }
			    }
	        	$form .="
	                    </div>";
	//@TODO Stretch the properties across multiple cells
	/*  		    $form .= "
				        <div class='rTableRow receipt-item' style='cursor:move'>
				              <div class='rTableCell'>
	                        	  <div class='rTable' style='width:100%'>
	                        			<div class='rTableBody'>
	                        				<div class='rTableRow'>
	                        					<div class='rTableCell' style='width:0%'>$delete</div>
	                        					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
	                        												'name' => 'line_item[][qty]',
	                        											))."</div>
	                        					<div class='rTableCell' style='width:65%'>$set_properties_button ".elgg_view('input/text', array(
	                        												'name' => 'line_item[][title]',
	                        							                    'class'=> 'rTableform90',
	                        											))."
	                        		            </div>
	                        					<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
	                        												'name' => 'line_item[][taxable]',
	                        												'value'=> 1,
	                        	        			                        'default' => false,
	                        											))."</div>
	                        					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
	                        												'name' => 'line_item[][cost]',
	                        						 						'class' => 'last_line_item',
	                        											))."</div>
	                        					<div class='rTableCell' style='width:10%'></div>";
	                                	foreach($hidden_blank_line_item_fields as $name => $value){
	                                        $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
	            }
	        	$form .="                   </div>
						               </div>
						          </div>
					          </div>
	                    </div>";
	        	$form .="
				          <div class='rTableRow receipt-item-properties' style='display:none'>
	                           <div class='rTableCell'>
	                                <div class='rTable' style='width:100%'>
	                        			<div class='rTableBody'>
	                        				<div class='rTableRow'>
	                        					<div class='rTableCell'>".
			                                           elgg_view('forms/market/properties', array(
	                    	                                   'element_type'   =>'receipt_item',
	                    	                                   'container_type' => 'transfer',
	                    	                                   'origin'         => $origin,
	                                        	        ))."
	                    					    </div>
	                                        </div>
						                </div>
						            </div>
						       </div>
	                      </div>";
	*/	}
	
		// Populate form footer
		$subtotal       = $item_exists ? $receipt_item_total
		                                 : money_format('%#10n', $transfer->subtotal);
		$shipping_cost  = elgg_view('input/text', array('name' => 'jot[shipping_cost]','value' => $transfer->shipping_cost,));
		$shipping_cost .= elgg_view('input/hidden', array('name' => 'jot[snapshot][shipping_cost]','value' => $transfer->shipping_cost,));
		$sales_tax      = elgg_view('input/text', array('name' => 'jot[sales_tax]', 'value' => $transfer->sales_tax,));
		$sales_tax     .= elgg_view('input/hidden', array('name' => 'jot[snapshot][sales_tax]', 'value' => $transfer->sales_tax,));
		$total          = $item_exists ? $receipt_item_total
		                                 : money_format('%#10n', $transfer->total);
		
		$form .= "<div class='new_line_items'></div>
				  <div class='rTableRow pin'>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:10%'></div>
						<div class='rTableCell' style='width:60%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:15%'>Subtotal</div>
						<div class='rTableCell' style='width:10%;text-align:right'>$subtotal</div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:10%'></div>
						<div class='rTableCell' style='width:60%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:15%'>Shipping</div>
						<div class='rTableCell'>$shipping_cost</div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:10%'></div>
						<div class='rTableCell' style='width:60%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:15%'>Sales Tax</div>
						<div class='rTableCell'>$sales_tax</div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell' style='width:0%'></div>
						<div class='rTableCell' style='width:10%'></div>
						<div class='rTableCell' style='width:60%'></div>
						<div class='rTableCell' style='width:5%'></div>
						<div class='rTableCell' style='width:15%'>Total</div>
						<div class='rTableCell' style='text-align:right'>$total</div>
					</div>
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
				'href' =>  $transfer ? $referrer : '#',
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
		   elgg_view('input/submit', array('value' => elgg_echo('save'), 'name' => 'apply')).
		'</div>';
		if ($transfer_exists){
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
			<div class='receipt_line_items'>
				     <div class='rTableRow receipt-item' style='cursor:move'>
	        			<div class='rTableCell' style='width:0%'>$delete</div>
						<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
													'name' => 'line_item[][qty]',
												))."</div>
						<div class='rTableCell' style='width:65%'>$set_properties_button ".elgg_view('input/text', array(
													'name' => 'line_item[][title]',
								                    'class'=> 'rTableform90',
												))."
						    <div class='receipt-item-properties' style='display:none'>".
						                                           elgg_view('forms/market/properties', array(
	                                	                                   'element_type'   =>'receipt_item',
	                                	                                   'container_type' => 'transfer',
	                                	                                   'origin'         => $origin,
	                                	        ))."
						    </div>
			            </div>
						<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
													'name' => 'line_item[][taxable]',
													'value'=> 1,
		        			                        'default' => false,
												))."</div>
						<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
													'name' => 'line_item[][cost]',
							 						'class' => 'last_line_item',
												))."</div>
						<div class='rTableCell' style='width:10%'></div>";
		                if ($hidden_blank_line_item_fields){
	                    	foreach($hidden_blank_line_item_fields as $name => $value){
	                            $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
	                        }
		                }
	$form .= "	</div>
			</div>
		</div>";
	break; // $presentation == 'compact'
}
echo $form;
eof:
//echo $display;
