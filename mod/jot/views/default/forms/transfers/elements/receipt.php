<!-- Form: jot\views\default\forms\transfers\elements\receipt.php -->
<?php
/**********
 * Version 03
 * Processes:
   * Receipt
   * Receive 
   * Experience
   * Event
 */

setlocale(LC_MONETARY, 'en_US');
$guid           = (int) get_input('guid', elgg_extract('guid', $vars, 0)); $display       .= '$guid='.$guid.'<br>';
$section        = get_input('section');                                    $display       .= '$section='.$section.'<br>';
$aspect         = elgg_extract('aspect',$vars);                            $display       .= '<br>$aspect='.$aspect.'<br>';
//$aspect         = $section;
//$aspect         = $vars['aspect'];
$asset_guid     = elgg_extract('asset', $vars);                            $display .= '14 $asset: '.$asset.'<br>';
$container_guid = elgg_extract('container_guid', $vars);
$owner_guid     = elgg_extract('owner_guid', $vars, elgg_get_logged_in_user_guid());
$referrer       = elgg_extract('referrer', $vars);                     
$subtype        = 'transfer';
$graphics_root  = elgg_get_site_url().'_graphics';
$presentation   = elgg_extract('presentation', $vars, 'full');         $display       .= '$presentation='.$presentation.'<br>';
$perspective    = elgg_extract('perspective', $vars);
$origin         = elgg_extract('origin', $vars);
$action         = elgg_extract('action', $vars);
$qid            = elgg_extract('qid', $vars, 'q'.$guid);
$asset          = get_entity($asset_guid);
$transfer_exists= true;   // Does a transfer receipt already exist?  Initialize to 'true'
$item_exists    = false;  // Does the managed item exist in Quebx already?  Initialize to 'false' 
if ($guid == 0 || $perspective == 'add'){
	$transfer_exists = false;
}
else {
    $entity                        = get_entity($guid);
    $title                         = $entity->title;                                               $display .= '31 $title: '.$title.'<br>';
    $space                         = $entity->space ?: $subtype;
    $hidden_fields['guid']         = $entity->guid;
    $hidden_fields['space']        = $entity->space;
    $container_guid                = $entity->getContainerGUID();
    if ((elgg_instanceof($entity, 'object', 'market') || elgg_instanceof($entity, 'object', 'item')) || elgg_instanceof($asset, 'object', 'market')) {
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
}                                                                     $display .= '89 $title: '.$title.'<br>';
                                                                      $display .= '90 $transfer_exists: '.$transfer_exists.'<br>90 $item_exists: '.$item_exists.'<br>';
$receipt_read_only = ($entity->status == 'Received');                 // Disable all input when the jot has been received
$receipt_disabled  = $receipt_read_only;                              // disable input if all items have been received

if ($transfer->aspect == 'receive'){
	$sales_receipt = get_entity($container_guid);                     $display .= '95 $sales_receipt->guid: '.$sales_receipt->guid.'<br>';
	$transfer      = (object) array_merge((array) $transfer, (array) $sales_receipt);
	$hidden_fields['jot[title]'] = $title;                            $display .= '96 $transfer->moment: '.$transfer->moment.'<br>';
}
if (is_object($transfer)){
	foreach ($transfer as $key1=>$value1){
		if (is_object($value1)){
			foreach($value1 as $key2=>$value2){
				if(is_object($value2)){
					foreach($value2 as $key3=>$value3){					$display .= '$transfer['.$key1.']['.$key2.']['.$key3.']='.$value3.'<br>';
					}
				}   													$display .= '$transfer['.$key1.']['.$key2.']='.$value2.'<br>';
			}
		}																$display .= '$transfer['.$key1.']='. $value1.'<br>';
	}
}

/*@TODO - 2018-02-15 - SAJ - Fields thought to be unused.  Delete once confirmed.
$hidden_fields['element_type']   = $element_type;
$hidden_fields['section']        = $section;
$hidden_fields['aspect']         = $aspect;
$hidden_fields['container_guid'] = $container_guid;
$hidden_fields['parent_guid']    = $vars['parent_guid'];
*/                                                                      
$hidden_fields['action']              = $action;
$hidden_fields['subtype']             = $subtype;
$hidden_fields['presentation']        = $presentation;
$hidden_fields['item_type']           = 'receipt_item';
$hidden_fields['jot[qid]']            = $qid;
$hidden_fields['jot[space]']          = $space;
$hidden_fields['jot[aspect]']         = $aspect;
$hidden_fields['jot[guid]']           = $transfer_guid;
$hidden_fields['jot[location]']       = elgg_extract('location', $vars);
$hidden_fields['jot[owner_guid]']     = $owner_guid;
$hidden_fields['jot[container_guid]'] = $container_guid;

if ($transfer_exists && elgg_entity_exists($transfer_guid)){ 
	// switch subtype if it exists
	$transfer      = get_entity($transfer_guid);
	$subtype       = $transfer->getSubtype();
	$aspect        = $transfer->aspect;                               $display.='121 $aspect: '.$aspect.'<br>';
	$hidden_fields['jot[aspect]'] = $aspect;
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
	if ($aspect == 'receive'){
		$receipt_items = elgg_get_entities_from_metadata(array(
		                                     'type'                 => 'object',
		                                     'subtype'              => 'receipt_item',
		                                     'container_guid'       => $container_guid,
	                                    	// Receipt items must have a 'sort_order' value to apper in this group.  This value is applied by the actions pick and edit.
	                                    	 'order_by_metadata'    => array('name' => 'sort_order', 
	                                    				                        'direction' => ASC, 
	                                    				                        'as' => 'integer'),
		                 ));
	}

    if ($aspect == 'receive'){
    	$merchant_guid = $sales_receipt->merchant;
    }
    else {
    	$merchant_guid = $transfer->merchant;
    }                                                                                           $display .= '173 $merchant_guid: '.$merchant_guid.'<br>';
    if (elgg_entity_exists($merchant_guid) && 
        is_int($merchant_guid)){   /*filters out merchants whose name begins with a number*/
        if (isset($merchant_guid) && elgg_entity_exists($merchant_guid)){$merchants = get_entity($merchant_guid);}     $display .= '178 isset($merchant_guid): '.isset($merchant_guid).'<br>113 $merchant_guid: '.print_r($merchant_guid, true).'<br>';
    	if (isset($merchant_guid) && empty($merchants)){
        	$merchants = elgg_get_entities_from_relationship(array(
        		'type'                 => 'group',
        		'relationship'         => 'merchant_of',
        		'relationship_guid'    => $transfer_guid,
        	    'inverse_relationship' => true,
        		'limit'                => false,
        	));
    	}                                                                     $display .= '143 isset($merchants): '.isset($merchants).'<br>';
    	if (isset($merchant_guid) && empty($merchants)){
    		// provided for backward compatibility
    		$merchants = elgg_get_entities_from_relationship(array(
    				'type'                 => 'group',
    				'relationship'         => 'supplier_of',
    				'relationship_guid'    => $transfer_guid,
    				'inverse_relationship' => true,
    				'limit'                => false,
    		));
    	}
    }                                                                             $display .= '195 isset($merchants): '.isset($merchants).'<br>';
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
}                                                                                 $display .= '208 $merchant_guid: '.$merchant_guid.'<br>';
                                                                                  $display .= '209 $receipt_items[0][retain_line_label] = '.$receipt_items[0]['retain_line_label'].'<br>167 $receipt_items->retain_line_label = '.$receipt_items->retain_line_label.'<br>';
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
  	      }
$merchant = "$link";
	}
}	
elseif (elgg_instanceof(get_entity($merchant_guid), 'group')){                               $display .= '277 $merchant_guid: '.$merchant_guid.'<br>';
    $merchant = elgg_view('output/span', ['content'=>elgg_view('input/grouppicker', array(
							'name' => 'jot[merchant]',
//		                    'values' => $transfer->merchant,         // plural - selected from existing groups
    						'values'=> (array) $merchant_guid,
                            'limit'=> 1,
                            'autocomplete'=>'on',
					)),
    		        'class'=>'receipt-input',]);					
}
else {                                                                                              $display .= '285 $merchant_guid: '.$merchant_guid.'<br>';
    $merchant = elgg_view('output/span', ['content'=>elgg_view('input/grouppicker', array(
							'name' => 'jot[merchant]',
		                    'value'  => $merchant_guid,                  // singular - not an existing group
                            'limit'=> 1,
                            'autocomplete'=>'on',
					)),
    		        'class'=>'receipt-input',]);
}

$set_properties_button = elgg_view('output/url', ['id'    => 'properties_set',
						                          'text'  => elgg_view_icon('settings-alt'), 
						                          'title' => 'set properties',]);
                                                                                                     $display .= '299 $merchant_value: '.print_r($merchant_value, true).'<br>';
if ($transfer_guid){
	$shipment_receipts = elgg_get_entities([
			                 'type'                => 'object',
			                 'subtype'             => 'transfer',
			                 'container_guid'      => $transfer_guid,
			                 'limit'               => false]);}
if ($shipment_receipts){
    unset($doc_links);
    foreach($shipment_receipts as $document){
    	$doc_links .='<li>'.elgg_view('output/url',['text'=>$document->title, 'data-guid'=>$document->getGUID()]).'</li>';
    }
    $document_links = "<ul>$doc_links</ul>";
}
                                                                                                     
foreach($hidden_fields as $name => $value){
    $form    .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
	$content .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
}                                                                                                    $display .= '318 $title: '.$title.'<br>';
	$title_input    = elgg_view('input/text'  , array('name' => 'jot[title]'                  , 'value' => $title, 'placeholder' => 'Receipt title', 'required'=>'','class'=>'receipt-input', 'readonly'=>$receipt_read_only));
    $title_input   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][title]'        , 'value' => $title));
    $associate_label= 'Sales Assoc.';
    $asset_input     = elgg_view('input/text'  , array('name' => 'jot[asset]'                 , 'value' => $asset,            'class'=>'receipt-input', 'readonly'=>$receipt_read_only));
    $cashier        = elgg_view('input/text'  , array('name' => 'jot[cashier]'                , 'value' => $transfer->cashier,'class'=>'receipt-input', 'readonly'=>$receipt_read_only));
    $cashier       .= elgg_view('input/hidden', array('name' => 'jot[snapshot][cashier]'      , 'value' => $transfer->cashier));
    $transaction_date_label = 'Purchase Date';
    $moment_label_receipt = elgg_view('output/span',['content'=>'Date','class'=>'receipt-input']);
    $moment         = elgg_view('input/date'  , array('name' => 'jot[moment]'                 , 'value' => $transfer->moment ?: strtotime("now"),'class'=>'receipt-input', 'readonly'=>$receipt_read_only));
    $moment        .= elgg_view('input/hidden', array('name' => 'jot[snapshot][moment]'       , 'value' => $transfer->moment));
    $actor_label    = 'Buyer';
    $purchased_by   = elgg_view('input/text'  , array('name' => 'jot[purchased_by]'           , 'value' => $transfer->purchased_by,'class'=>'receipt-input', 'readonly'=>$receipt_read_only));
    $purchased_by  .= elgg_view('input/hidden', array('name' => 'jot[snapshot][purchased_by]' , 'value' => $transfer->purchased_by,));
    $order_no_label = 'Order #';
    $order_no       = elgg_view('input/text'  , array('name' => 'jot[order_no]'               , 'value' => $transfer->order_no,'class'=>'receipt-input', 'readonly'=>$receipt_read_only));
    $order_no      .= elgg_view('input/hidden', array('name' => 'jot[snapshot][order_no]'     , 'value' => $transfer->order_no,));
    $preorder_label = 'Pre ordered';
    $preorder_options =                              ['name'=>'jot[preorder_flag]'            , 'class'=>'preorder-flag','label_class'=>'receipt-input'];
    if ($transfer->preorder_flag){$preorder_options['checked'] = 'checked';
                                  $preorder_style              = 'visibility:visible;white-space:nowrap';}
    else                         {$preorder_style              = 'visibility:hidden';}
    if ($receipt_disabled)       {$preorder_options['disabled']= true;
                                  unset($preorder_options['name'], $preorder_options['value']);
    $preorder_flag  .=            elgg_view('input/hidden',['name'=>'jot[preorder_flag]'       , 'value'=>$transfer->preorder_flag]);}
    $preorder_flag  .= elgg_view('input/switchbox',$preorder_options);
    $preorder_flag  .= elgg_view('input/hidden',['name'=>'jot[snapshot][preorder_flag]'       , 'value'=>$transfer->preorder_flag]);
    $delivery_date_label = 'Scheduled date';
    $delivery_date  = elgg_view('input/date', ['name'=>'jot[delivery_date]'                   , 'value'=>$transfer->delivery_date,'class'=>'receipt-input', 'readonly'=>$receipt_read_only]);
    $delivery_date  .= elgg_view('input/hidden', ['name'=>'jot[snapshot][delivery_date]'      , 'value'=>$transfer->delivery_date]);
    $purchase_order_no_label = 'PO #';
    $purchase_order_no = elgg_view('input/text'   , array('name' => 'jot[purchase_order_no]'  , 'value' => $transfer->purchase_order_no,'class'=>'receipt-input', 'readonly'=>$receipt_read_only));
    $purchase_order_no .= elgg_view('input/hidden', array('name' => 'jot[snapshot][purchase_order_no]', 'value' => $transfer->purchase_order_no,));
    $invoice_no_label = 'Invoice #';
	$invoice_no     = elgg_view('input/text'  , array('name' => 'jot[invoice_no]'             , 'value' => $transfer->invoice_no,'class'=>'receipt-input', 'readonly'=>$receipt_read_only));
    $invoice_no    .= elgg_view('input/hidden', array('name' => 'jot[snapshot][invoice_no]'   , 'value' => $transfer->invoice_no,));
    $document_no    = elgg_view('input/text'  , array('name' => 'jot[document_no]'            , 'value' => $transfer->document_no,'class'=>'receipt-input', 'readonly'=>$receipt_read_only));
    $document_no   .= elgg_view('input/hidden', array('name' => 'jot[snapshot][document_no]'  , 'value' => $transfer->document_no,));
    $transaction_no_label = 'Receipt #';
    $receipt_no     = elgg_view('input/text'  , array('name' => 'jot[receipt_no]'             , 'value' => $transfer->receipt_no,'class'=>'receipt-input', 'readonly'=>$receipt_read_only));
    $receipt_no    .= elgg_view('input/hidden', array('name' => 'jot[snapshot][receipt_no]'   , 'value' => $transfer->receipt_no,));
    $receipt_item_header_selector = elgg_view('output/span', ['content'=>
    								elgg_view('output/url', [
				    				    'text' => '+',
				    					'href' => '#',
										'class' => 'elgg-button-submit-element new-item',
										'data-element'=> 'new_receipt_item',
				    					'data-qid'    => $qid,
				    					'data-rows'   => 0,
				    					]), 
    								'class'=>'receipt-input']);
    $receipt_item_header_recd_qty = 'Rec\'d';
    $receipt_item_header_qty   = 'Qty';
    $receipt_item_header_item  = 'Item';
    $receipt_item_header_tax   = 'tax';
    $receipt_item_header_cost  = 'Cost';
    $receipt_item_header_total = 'Total';
    $receive_item_header_received  = elgg_view_icon('check');
    $receive_item_header_recd_qty  = 'Qty<br>Received';
    $receive_item_header_qty   = 'Qty<br>Ordered';
    $receive_item_header_item  = 'Item';
    $receive_item_header_backordered = 'Qty<br>Back Ordered';
    $receive_item_header_bo_delivery_date = 'Date<br>Scheduled';
    
    $title_input_value    = elgg_view('output/span', ['content'=>$title,                  'class'=>'receive-input']);
    $merchant_value       = elgg_view('output/span',['content'=>get_entity($merchant_guid)->name,'class'=>'receive-output']);
	$cashier_value        = elgg_view('output/span', ['content'=>$transfer->cashier,      'class'=>'receive-output']);
    $moment_label_receive = elgg_view('output/span', ['content'=>'Order date',            'class'=>'receive-output']);
    $moment_value         = elgg_view('output/span', ['content'=>$transfer->moment,       'class'=>'receive-output']);
    $purchased_by_value   = elgg_view('output/span', ['content'=>$transfer->purchased_by, 'class'=>'receive-output']);
    $order_no_value       = elgg_view('output/span', ['content'=>$transfer->order_no,     'class'=>'receive-output']);
    $preorder_value_options = ['name'=>'jot[preorder_flag]', 'value'=>$transfer->preorder_flag,'class'=>'preorder-flag', 'disabled'=>'', 'checked' => 'checked', 'label_class'=>'receive-output', 'label_style'=>'display:none;', 'default'=>false];
    $preorder_value_flag  = elgg_view('input/switchbox',$preorder_value_options);
    $delivery_date_value  = elgg_view('output/span', ['content'=>$transfer->delivery_date, 'class'=>'receive-output']);
    $purchase_order_no_value = elgg_view('output/span', ['content'=>$transfer->purchase_order_no, 'class'=>'receive-output']);
	$invoice_no_value     = elgg_view('output/span', ['content'=>$transfer->invoice_no, 'class'=>'receive-output']);
    $document_no_value    = elgg_view('output/span', ['content'=>$transfer->document_no, 'class'=>'receive-output']);
    $receipt_no_value     = elgg_view('output/span', ['content'=>$transfer->receipt_no, 'class'=>'receive-output']);
    $received_moment        = elgg_view('input/date', ['name'=>'jot[received_moment]'             , 'value'=>$transfer->received_moment ?: strtotime("now"), 'readonly'=>$receipt_read_only]);
    $received_moment       .= elgg_view('input/hidden', ['name'=>'jot[snapshot][received_moment]'      , 'value'=>$transfer->received_moment]);
    $packing_list_no      = elgg_view('input/text'   , array('name' => 'jot[packing_list_no]'  , 'value' => $transfer->packing_list_no, 'readonly'=>$receipt_read_only));
    $packing_list_no     .= elgg_view('input/hidden', array('name' => 'jot[snapshot][packing_list_no]', 'value' => $transfer->packing_list_no,));
    $receipt_notes        = elgg_view('input/longtext',['name'=>'jot[notes]', 'value'=>$transfer->notes,'class'=>'receipt-input']);
    $receive_notes        = elgg_view('input/longtext',['name'=>'jot[receive_notes]', 'value'=>$transfer->receive_notes,'class'=>'receive-input']);
    
                                                                                              $display .= '402 $moment_value: '.$moment_value.'<br>';

Switch ($presentation){
	case 'full':
		Switch ($aspect){
			case 'receipt':
				if ($document_links){
				    $documents = "<div class='rTableRow'>
									<div class='rTableCell'>Documents</div>
								    <div class='rTableCell' style='text-align:left;'>$document_links</div>
								  </div>";}
				$form .= "<div style='margin-top:5px;'>{$title_input}{$title_input_value}</div>
					  <div class='rTable pre-order'>
                         <div class='rTableBody'>
							<div class='rTableRow'>
								<div class='rTableCell'                                                                                             > {$preorder_flag}{$preorder_value_flag}</div>
								<div class='rTableCell'                                                                                             > {$preorder_label}</div>
								<div class='rTableCell jot-preorder jot-delivery-date jot-delivery-date-label'         style = '$preorder_style'    > $delivery_date_label</div>
								<div class='rTableCell jot-preorder jot-delivery-date jot-delivery-date-field'         style = '$preorder_style'    > {$delivery_date}{$delivery_date_value}</div>
								<div class='rTableCell jot-preorder jot-purchase-order-no jot-purchase-order-no-label' style = '$preorder_style'    > $purchase_order_no_label</div>
								<div class='rTableCell jot-preorder jot-purchase-order-no jot-purchase-order-no-field' style = '$preorder_style'    > {$purchase_order_no}{$purchase_order_no_value}</div>
							</div>
					 	 </div>
					  </div>";
				$line_items_header= "          
				    <div class='rTable line_items receipt-line-items'>
						<div class='rTableBody'>
							<div id='sortable'>
							<div class='rTableRow pin'>
				                <div class='rTableCell'>$receipt_item_header_selector</div>
				    			<div class='rTableHead'>$receipt_item_header_recd_qty</div>
				    			<div class='rTableHead'>$receipt_item_header_qty</div>
				    			<div class='rTableHead'>$receipt_item_header_item</div>
				    			<div class='rTableHead'>$receipt_item_header_tax</div>
				    			<div class='rTableHead'>$receipt_item_header_cost</div>
				    			<div class='rTableHead'>$receipt_item_header_total</div>
				    		</div>";
				break;
			case 'receive':
				unset($moment_label_receipt, $moment, $merchant, $cashier, $purchased_by, $order_no, $invoice_no, $document_no, $receipt_no);
				$title_input          = str_replace('class="receive-input"', '', $title_input_value);
				$moment_value         = str_replace('class="receive-output"', '', $moment_value);
				$moment_label_receive = str_replace('class="receive-output"', '', $moment_label_receive);
				$merchant_value       = str_replace('class="receive-output"', '', $merchant_value);
				$cashier_value        = str_replace('class="receive-output"', '', $cashier_value);
				$purchased_by_value   = str_replace('class="receive-output"', '', $purchased_by_value);
				$order_no_value       = str_replace('class="receive-output"', '', $order_no_value);
				$invoice_no_value     = str_replace('class="receive-output"', '', $invoice_no_value);
				$document_no_value    = str_replace('class="receive-output"', '', $document_no_value);
				$receipt_no_value     = str_replace('class="receive-output"', '', $receipt_no_value);
				$received_moment = elgg_view('input/date', ['name'=>'jot[received_moment]'             , 'value'=>$transfer->moment ?: strtotime("now"), 'readonly'=>$receipt_read_only]);
				$received_moment .= elgg_view('input/hidden', ['name'=>'jot[snapshot][received_moment]', 'value'=>$transfer->moment]);
			    $delivery_date_value  = elgg_view('output/span', ['content'=>$sales_receipt->delivery_date]);
			    $purchase_order_no_value = elgg_view('output/span', ['content'=>$sales_receipt->purchase_order_no]);
			    $preorder_value_options = ['name'=>'jot[preorder_flag]', 'value'=>$sales_receipt->preorder_flag,'disabled'=>'', 'checked' => 'checked', 'label_class'=>'receive-output', 'default'=>false];
			    $preorder_value_flag  = elgg_view('input/switchbox',$preorder_value_options);
							
				
    
				$receive_input = "
					<div style='margin-top:5px;'>$title_input_value</div>
					<div class='rTable'>
                       <div class='rTableBody'>
                       		<div class='rTableRow'>
								<div class='rTableCell'> $preorder_value_flag</div>
								<div class='rTableCell'> $preorder_label</div>
								<div class='rTableCell'> $delivery_date_label</div>
								<div class='rTableCell'> $delivery_date_value</div>
								<div class='rTableCell'> $purchase_order_no_label</div>
								<div class='rTableCell'> $purchase_order_no_value</div>
							</div>
							<div class='rTableRow'>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
                				<div class='rTableCell' style='white-space:nowrap;'>Received date</div>
								<div class='rTableCell'>$received_moment</div>
								<div class='rTableCell' style='white-space:nowrap;'>Packing List #</div>
								<div class='rTableCell'>$packing_list_no</div>
							</div>
					   </div>
					</div>
                    ";
				$line_items_header = "<div class='rTable receive-line-items'>
					<div class='rTableBody'>
						<div class='rTableRow'>
			                <div class='rTableCell'>$receive_item_header_received</div>
			    			<div class='rTableHead'>$receive_item_header_recd_qty</div>
			    			<div class='rTableHead'>$receive_item_header_qty</div>
			    			<div class='rTableHead'>$receive_item_header_item</div>
			    			<div class='rTableHead'>$receive_item_header_backordered</div>
			    			<div class='rTableHead'>$receive_item_header_bo_delivery_date</div>
			    		</div>";
				/**************************/                                                   $display .= '473 $transfer->guid: '.$transfer->getGUID().'<br>';
	            $receive_line_items = elgg_get_entities(['type'           => 'object',
	            		                                 'subtype'        => 'line_item',
	            		                                 'container_guid' => $transfer->getGUID(),
	            		                                 'limit'          => false]);
	            if ($receive_line_items){
	            	foreach ($receive_line_items as $receive_line_item){                       $display .= '480	 $receive_line_item->guid: '.$receive_line_item->guid.'<br>';
	            		foreach($receipt_items as $line_item){                                 $display .= '481 $line_item->guid: '.$line_item->guid.'<br>';
	            			if (check_entity_relationship($receive_line_item->guid, 'receive_item', $line_item->guid)){
	            				$line_item_relationships[$line_item->guid] = $receive_line_item->guid;
	            			}
	            		}
	            	}
	            }                                                                               $display .= '489 $line_item_relationships<br>'.print_r($line_item_relationships, true).'<br>';
			            
				
				break;
		}
	    	$form .= "
	        $receive_input
	    	<div class='rTable'>
				<div class='rTableBody'>
					
					$documents
					<div class='rTableRow'>
						<div class='rTableCell' style='width:14%'>Merchant</div>
						<div class='rTableCell' style='width:86%;padding-right:0'>{$merchant}{$merchant_value}</div>
					</div>
				</div>
			</div>";
		$form .= "<div id='header_2' class='rTable'>
				<div class='rTableBody'>
					<div class='rTableRow'>
						<div class='rTableCell'>Associate</div>
						<div class='rTableCell'>{$cashier}{$cashier_value}</div>
						<div class='rTableCell'>{$moment_label_receipt}{$moment_label_receive}</div>
						<div class='rTableCell'>{$moment}{$moment_value}</div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'>Buyer</div>
						<div class='rTableCell'>{$purchased_by}{$purchased_by_value}</div>
						<div class='rTableCell'>Order #</div>
						<div class='rTableCell'>{$order_no}{$order_no_value}</div>
						<div class='rTableCell'>Invoice #</div>
						<div class='rTableCell'>{$invoice_no}{$invoice_no_value}</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>Doc #</div>
						<div class='rTableCell'>{$document_no}{$document_no_value}</div>
						<div class='rTableCell'>Receipt #</div>
						<div class='rTableCell'>{$receipt_no}{$receipt_no_value}</div>
					</div>
				</div>
		</div>";
		$form .= $line_items_header;
	//goto eof;
		// Populate existing line items
		$n=0;
		$delete = elgg_view('output/url', ['data-qid' => $qid_n,
				                           'title'=>'remove receipt item',
									       'class'=>'remove-node',
									       'style'=> 'cursor:pointer',
									       'text' => elgg_view_icon('delete-alt'),]);
		if (!empty($receipt_items)){
	    	foreach($receipt_items as $receipt_item){
		        $n++;
		        unset($line_item, $receive_item);
	    		$element_type = 'receipt item';                                        $display .= '545 $receipt_item[retain_line_label]: '. $receipt_item['retain_line_label'].'<br>';
	    		$can_edit = $item_exists ?: $receipt_item->canEdit();
	    		$recd_qty = NULL;
	    		$qty = $cost = NULL;
	    		$qid_n = "{$qid}_{$n}";
	    		$max = (($receipt_item->qty)-($receipt_item->recd_qty));
	    		$receipt_item_read_only = $receipt_read_only ?: ($max <= 0);  // disable receipt item input if all pieces have been received
	    		$receipt_item_disabled = $receipt_read_only ?: ($max <= 0);  // disable input if all items have been received
	    		$receive_item = get_entity($line_item_relationships[$receipt_item->getGUID()]);
	    		
	    		$disabled = ($max <= 0);  // disable input if all items have been received
	    		unset($hidden_line_item_fields, $receipt_item_total, $receipt_item_guid, $linked_item);
				$set_properties_button = elgg_view('output/url', array(
						                   'id'    => 'properties_set',
				                           'data-jq-dropdown'    => '#'.$qid.'_'.$n,
				                           'text'  => elgg_view_icon('settings-alt'), 
				                           'title' => 'set properties',
				));
					    		
	    		if ($can_edit) {                                                       $display .= '562 can edit<br>';
	    			$select = elgg_view('input/checkbox', array('name'    => 'do_me',
	    														'value'   => $receipt_item->guid,
	    								        			    'default' => false,
	    								        			   ));
	    	        if ($item_exists){
	    	            $linked_item[] = $originating_item;                            $display .= '568 is_array($linked_item): '.is_array($linked_item).'<br>';
	    	            $receipt_item->retain_line_label = 'no';                       $display .= '569 $receipt_item[item_guid]: '.$receipt_item['item_guid'].'<br>';
	    	        }
	    	        elseif (!empty($receipt_item->item_guid)){
	    	            $linked_item[] = get_entity($receipt_item->item_guid);
	    	        }
	    	        else {                                                             $display .= '574 $linked_item->guid: '.$linked_item->guid.'<br>';
	    	            $linked_item = elgg_get_entities_from_relationship(array(
	        				'type' => 'object',
	        				'relationship' => 'receipt_item',
	        				'relationship_guid' => $receipt_item->guid,
	        				'inverse_relationship' => true,
	        				'limit' => false,
	        			));
	    		}                                                                       $display .= '582 $linked_item[0]->guid:'.$linked_item[0]->guid.'<br>';
																		            	$display .= '583 Title: '.$title.'<br>';         
																		            	$display .= '584 $receipt_item[retain_line_label]: '.$receipt_item['retain_line_label'].'<br>';
																		            	$display .= '585 $receipt_item[item_guid]: '.$receipt_item['item_guid'].'<br>';
																		            	$display .= '586 $receipt_item->guid: '.$receipt_item->guid.'<br>';
																		            	$display .= '587 $item_exists: '.$item_exists.'<br>';
	    		    //populate defaults
	    		    $hidden_line_item_fields['line_item['.$receipt_item->guid.'][guid]']              = $receipt_item->guid;
//	                $hidden_line_item_fields['line_item['.$receipt_item->guid.'][sort_order]']        = $receipt_item->sort_order;
	                $line_item = elgg_view('input/text', array(
	                		'name' => 'line_item['.$receipt_item->guid.'][title]',
	                		'value' => $receipt_item->title,
	                		'class'=> 'rTableform90',
	                ));
	                
	    	        if (is_array($linked_item) 
	    	                && 
	    	             (   $receipt_item->retain_line_label   == 'no'
	    	              || $receipt_item['retain_line_label'] == 'no' 
	    	              || $bulk_transfer)){                                           $display .= '601 $receipt_item->guid: '.$receipt_item->guid.'<br>';
	    	//         if (!empty($linked_item[0]) && ($receipt_item->retain_line_label != 'yes' // retained for backward compatibility 
	    	//         		                     || $receipt_item->retain_line_label != 'retain')){
	    	            $receipt_item_guid = $linked_item[0]->guid ?: $receipt_item->item_guid;
	    				$detach = elgg_view("output/url",array(
	    			    	'href' => "action/jot/detach?element_type=receipt_item&guid=".$receipt_item_guid."&container_guid=$receipt_item->guid",
	    			    	'text' => elgg_view_icon('unlink'),
	    			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked item'),
	    			    	'encode_text' => false,
	    			    ));
	    				if (!$item_exists){                                              $display .= '611 !$item_exists<br>';
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
	    		        if ($item_exists && $n==1){                                               $display .= '625 $item_exists<br>';
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
	    	        }                                                                          $display .= '645 $receipt_item_guid: '.$receipt_item_guid.'<br>';
	                $receive_line_item = $line_item;
    				$recd_qty = $receive_item->recd_qty;                                       $display .= '660 $recd_qty: '.$recd_qty.'<br>';
	                $qty = $item_exists  ? 1
	                                       : $receipt_item->qty;                               $display .= '662 $qty:'.$qty.'<br>';
	                $cost = $item_exists ? $originating_item->acquire_cost 
	                                       : $receipt_item->cost;
	    //$display .= '$line_item: '.$line_item.'<br>';
	    	        $tax_options = ['name'    => 'line_item['.$receipt_item->guid.'][taxable]',
    							    'value'   => 1,
    							    'data-qid'=> $qid,
    	        			        'default' => false];
	    	        if ($receipt_item->taxable == 1){
	    	        	$tax_options['checked'] = 'checked';
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
	    	    $receive_line_item_options = ['name'     => 'line_item['.$receipt_item->guid.'][received]',
										      'value'    => 1,
        									  'readonly' => $receipt_item_read_only,
										      'data-qid' => $qid_n,
											  'data-name'=> 'received',
										      'default'  => false];
	    	    if ($receive_item->received == 1){
	    	    	$receive_line_item_options['checked']=true;
	    	    }                                                                                  $display .= '668 $receive_item->received: '.$receive_item->received.'<br>';
	    	    
	    		$receive_line_item_received = elgg_view('input/checkbox',$receive_line_item_options);
	    		$receipt_line_item_recd_qty = $receipt_item->recd_qty;
	    		$receipt_line_item_qty = elgg_view('input/number', ['name'      => 'line_item['.$receipt_item->guid.'][qty]',
    															  'value'     => $qty,
	    				                                          'max'       => 0,
        														  'readonly' => $receipt_item_read_only,
    															  'data-qid'  => $qid_n,
    							                                  'data-name' => 'qty',
    															]);
	    		$receive_line_item_qty = $qty;
	    		$receive_line_item_qty .= elgg_view('input/hidden', ['name'      => 'line_item['.$receipt_item->guid.'][qty]',
    															  'value'     => $qty,
    															  'data-qid'  => $qid_n,
    							                                  'data-name' => 'qty',
    															  'class'     =>'nString',
    															]);
	    		$receive_line_item_recd_qty = elgg_view('input/number', ['name'      => 'line_item['.$receipt_item->guid.'][recd_qty]',
    															  'value'     => $recd_qty,
    															  'data-qid'  => $qid_n,
    							                                  'data-name' => 'recd_qty',
        														  'max'       => $max,
        														  'readonly' => $receipt_item_read_only,
    															]);
	    		$receipt_line_item_cost = elgg_view('input/text', ['name'     => 'line_item['.$receipt_item->guid.'][cost]',
	    															'value'    => $cost,
        														    'readonly' => $receipt_item_read_only,
	    															'data-qid' => $qid_n,
	    							                                'data-name'=> 'cost',
	    															'class'    => 'nString',
	    														]);
	    		$receipt_line_item_bo_qty = elgg_view('input/number',['name'      => 'line_item['.$receipt_item->guid.'][bo_qty]',
									    							'value'    => $receipt_item->bo_qty,
        														      'max'       => $max,
        														      'readonly' => $receipt_item_read_only,
									    							'data-qid' => $qid_n,
										    						  'data-name' => 'bo_qty',]);
	    		$receipt_line_item_bo_delivery_date = elgg_view('input/date', ['name'      => 'line_item['.$receipt_item->guid.'][bo_delivery_date]', 
	    				                                                       'value'     => $transfer->bo_delivery_date,
        														               'readonly' => $receipt_item_read_only,
	    				                                                       'data-name' => 'bo_delivery_date']);
	    		}
	        	/**************/
	        	$property_cards .="<div id='$qid_n' class='jq-dropdown jq-dropdown-tip jq-dropdown-relative'>
                                      <div class='jq-dropdown-panel'>".
                                           elgg_view('forms/market/properties', array(
			                                   'element_type'   => 'receipt_item',
			                                   //**Note: Don't getGUID() or getContainerGUID().  These functions fail for new receipt items from an existing managed item
		                                       'guid'           => $receipt_item->guid,
		                                       'container_guid' => $receipt_item->container_guid,
			                                   'container_type' => 'transfer',
			                                   'origin'         => $origin,
		                                       'sort_order'     => $n,
		                                       'item'           => get_entity($receipt_item_guid),
		                                       'qid'            => $qid,
                                          	   'qid_n'          => $qid_n,
		                                       'line_item_behavior_list_class'  => $qid_n."_line_item_behavior_list",
                                               'line_item_behavior_list_data'   =>['qid'=>$qid_n],
		                                       'line_item_behavior_radio_class' => 'receipt-line-item-behavior'
		                    	        ))."
	    					       </div>
	    					   </div>";
//echo $form; goto eof;
	            Switch ($aspect){
	            	case 'receipt':
			            $form .= "
			            		<div class='rTableRow receipt_item' data-qid='$qid_n'>
			    					<div class='rTableCell'>$delete</div>
			    					<div class='rTableCell'>$receipt_line_item_recd_qty</div>
			    					<div class='rTableCell'>$receipt_line_item_qty</div>
			    					<div class='rTableCell'>$set_properties_button $line_item</div>
			    					<div class='rTableCell'>$tax_check</div>
			    					<div class='rTableCell'>$receipt_line_item_cost</div>
			    					<div class='rTableCell'><span id='{$qid_n}_line_total'>$receipt_item_total</span><span class='{$qid}_line_total line_total_raw'>$receipt_item->total</span></div>";
		                foreach($hidden_line_item_fields as $name => $value){
		                $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));}
			        	$form .= "</div>";
			        	break;
			        	/**************/
	            	case 'receive':
			    		$form .= 
			    		        "<div class='rTableRow receive_item'>
			    					<div class='rTableCell'>$receive_line_item_received</div>
			    					<div class='rTableCell'>$receive_line_item_recd_qty</div>
			    					<div class='rTableCell'>$receive_line_item_qty</div>
			    					<div class='rTableCell'>$receive_line_item</div>
			    					<div class='rTableCell'>$receipt_line_item_bo_qty</div>
			    					<div class='rTableCell'>$receipt_line_item_bo_delivery_date</div>";
		                foreach($hidden_line_item_fields as $name => $value){
		                $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));}
			        	$form .= "</div>";
			        	break;
	            }
	       	}
		}
		// Populate blank lines
		for ($i = $n+1; $i <= 1; $i++) {
			$set_properties_button = elgg_view('output/url', array(
					                   'id'    => 'properties_set',
			                           'data-jq-dropdown'    => '#'.$qid.'_'.$i,
			                           'text'  => elgg_view_icon('settings-alt'), 
			                           'title' => 'set properties',
			));
			    $pick = elgg_view('output/url', array(
			        		'text' => elgg_view_icon('settings-alt')));
		        $pick_menu = "<span title='Save form before setting line item properties'>$pick</span>";
		$form .= "<div class='rTableRow' style='cursor:move'>
						<div class='rTableCell'>$delete</div>
						<div class='rTableCell'>".elgg_view('input/hidden', ['name' => 'line_item[][new_line]']).
						                                           elgg_view('input/text', ['name' => 'line_item[][qty]'])."</div>
						<div class='rTableCell'>$set_properties_button ".elgg_view('input/text', array(
													'name' => 'line_item[][title]',
								                    'class'=> 'rTableform90',
												))."
						    <div id='{$qid}_{$i}' class='receipt-item-properties jq-dropdown jq-dropdown-tip' style='display:none'>
                                <div class='jq-dropdown-panel'>".
                                                 elgg_view('forms/market/properties', array(
                                	                                   'element_type'   =>'receipt_item',
                                	                                   'container_type' => 'transfer',
                                	                                   'origin'         => $origin,
	                                	        ))."
						        </div>
                            </div>
			            </div>
						<div class='rTableCell'>".elgg_view('input/checkbox', array(
													'name' => 'line_item[][taxable]',
													'value'=> 1,
		        			                        'default' => false,
												))."</div>
						<div class='rTableCell'>".elgg_view('input/text', array(
													'name' => 'line_item[][cost]',
							 						'class' => 'last_line_item',
												))."</div>
						<div class='rTableCell'></div>";
		$form .= "</div>";
		}
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
		
		Switch ($aspect){
			case 'receipt':
				//$form .= "<div class='new_line_items'></div>";
				$form .= "<div id={$qid}_new_line_items class='new_line_items'></div>
				             </div>
						</div>
					</div>
					<div class='rTable receipt-line-items'>
						<div class='rTableBody'>
							<div class='rTableRow pin'>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'>Subtotal</div>
								<div class='rTableCell'><span id={$qid}_subtotal>$subtotal</span><span class='{$qid}_subtotal line_total_raw'>$transfer->subtotal</span></div>
							</div>
							<div class='rTableRow pin'>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'>Shipping</div>
								<div class='rTableCell'>$shipping_cost</div>
							</div>
							<div class='rTableRow pin'>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'>Sales Tax $sales_tax_rate_label</div>
								<div class='rTableCell'>$sales_tax</div>
							</div>
							<div class='rTableRow pin'>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell'>Total</div>
								<div class='rTableCell'><span id={$qid}_total>$total</span><span class='{$qid}_total line_total_raw'>$transfer->total</span></div>
							</div>
						</div>
					</div>";
				break;
			case 'receive':   // No footer for receive form
				$form .= "
						</div>
				</div>";
				break;
		}
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
		
		$form .= "<div id={$qid}_line_item_property_cards class='line_item_property_cards'></div>
				  $property_cards";  // Append property cards to the form body.
	
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
																			'name' => 'jot[moment]',
																			'value' => $transfer->moment,
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
	        $hide_title = elgg_extract('hide_title', $vars, false);
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
	            case 'receive':
	            	$title = 'Receive';
	            	break;
	        }
	        if ($transfer->status !='Received'){
	        	$style = "style='cursor:move'";}
	          
			if (!$hide_title){
				$form .= "<b>$title</b><br>";
			}
			if ($document_links){
			    $documents = "<div class='rTableRow'>
								<div class='rTableCell'>Documents</div>
							    <div class='rTableCell' style='text-align:left;'>$document_links</div>
							  </div>";}
			$form .= "<div style='margin-top:5px;'>{$title_input}{$title_input_value}</div>
					  <div class='rTable pre-order'>
                         <div class='rTableBody'>
							<div class='rTableRow'>
								<div class='rTableCell'                                                                                             > {$preorder_flag}{$preorder_value_flag}</div>
								<div class='rTableCell'                                                                                             > {$preorder_label}</div>
								<div class='rTableCell jot-preorder jot-delivery-date jot-delivery-date-label'         style = '$preorder_style'    > $delivery_date_label</div>
								<div class='rTableCell jot-preorder jot-delivery-date jot-delivery-date-field'         style = '$preorder_style'    > {$delivery_date}{$delivery_date_value}</div>
								<div class='rTableCell jot-preorder jot-purchase-order-no jot-purchase-order-no-label' style = '$preorder_style'    > $purchase_order_no_label</div>
								<div class='rTableCell jot-preorder jot-purchase-order-no jot-purchase-order-no-field' style = '$preorder_style'    > {$purchase_order_no}{$purchase_order_no_value}</div>
							</div>
							<div class='rTableRow receive-input' style='display:none;'>
								<div class='rTableCell'></div>
								<div class='rTableCell'></div>
								<div class='rTableCell' style='white-space:nowrap;'>Received date</div>
								<div class='rTableCell'>$received_moment</div>
								<div class='rTableCell' style='white-space:nowrap;'>Packing List #</div>
								<div class='rTableCell'>$packing_list_no</div>
							</div>
					 	 </div>
					  </div>";
	    	$form .= "
	    	<div class='rTable'>
				<div class='rTableBody'>
					$documents
					<div class='rTableRow'>
						<div class='rTableCell' style='width:14%'>Merchant</div>
						<div class='rTableCell' style='width:86%;padding-right:0'>{$merchant}{$merchant_value}</div>
					</div>
				</div>
			</div>";
		
		$form .= "<div id='header_2' class='rTable'>
				<div class='rTableBody'>
					<div class='rTableRow'>
						<div class='rTableCell'>Associate</div>
						<div class='rTableCell'>{$cashier}{$cashier_value}</div>
						<div class='rTableCell'>{$moment_label_receipt}{$moment_label_receive}</div>
						<div class='rTableCell'>{$moment}{$moment_value}</div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'>Buyer</div>
						<div class='rTableCell'>{$purchased_by}{$purchased_by_value}</div>
						<div class='rTableCell'>Order #</div>
						<div class='rTableCell'>{$order_no}{$order_no_value}</div>
						<div class='rTableCell'>Invoice #</div>
						<div class='rTableCell'>{$invoice_no}{$invoice_no_value}</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>Doc #</div>
						<div class='rTableCell'>{$document_no}{$document_no_value}</div>
						<div class='rTableCell'>Receipt #</div>
						<div class='rTableCell'>{$receipt_no}{$receipt_no_value}</div>
					</div>
				</div>
		</div>";

		$form .= "
	    <div id='{$qid}_line_items' class='rTable line_items receipt-line-items'>
			<div class='rTableBody'>
				<div id='sortable'>
				<div class='rTableRow pin'>
	                <div class='rTableCell'>$receipt_item_header_selector</div>
	    			<div class='rTableHead'>$receipt_item_header_recd_qty</div>
	    			<div class='rTableHead'>$receipt_item_header_qty</div>
	    			<div class='rTableHead'>$receipt_item_header_item</div>
	    			<div class='rTableHead'>$receipt_item_header_tax</div>
	    			<div class='rTableHead'>$receipt_item_header_cost</div>
	    			<div class='rTableHead'>$receipt_item_header_total</div>
	    		</div>";
		
	    $received_form .= "
	    <div id='{$qid}_receive_line_items' class='rTable receive-line-items' style='display:none;'>
			<div class='rTableBody'>
				<div class='rTableRow'>
	                <div class='rTableCell'>$receive_item_header_received</div>
	    			<div class='rTableHead'>$receive_item_header_recd_qty</div>
	    			<div class='rTableHead'>$receive_item_header_qty</div>
	    			<div class='rTableHead'>$receive_item_header_item</div>
	    			<div class='rTableHead'>$receive_item_header_backordered</div>
	    			<div class='rTableHead'>$receive_item_header_bo_delivery_date</div>
	    		</div>";
	
		// Populate existing receipt items
		$n=0;
		$delete = elgg_view('output/url', array(
		        'title'=>'remove receipt item',
		        'class'=>'remove-node',
		        'style'=> 'cursor:pointer',
		        'text' => elgg_view_icon('delete-alt'),
				'data-qid_n'=>$qid_n,
		));
		if (!empty($receipt_items)){
	    	foreach($receipt_items as $receipt_item){
			if (!elgg_entity_exists($receipt_item->guid)){continue;}
	    		$n++;
	    		$element_type = 'receipt item';                                        $display .= '1210 $receipt_item[retain_line_label]: '. $receipt_item['retain_line_label'].'<br>';
	    		
	    		$can_edit = $item_exists ?: $receipt_item->canEdit(); 
	    		$qty = $cost = NULL;
	    		$qid_n = "{$qid}_{$n}";
	    		$max = (($receipt_item->qty)-($receipt_item->recd_qty));
	    		$receipt_item_read_only = $receipt_read_only ?: ($max == 0);  // disable receipt item input if all pieces have been received
	    		$receipt_item_disabled = $receipt_read_only ?: ($max == 0);  // disable input if all items have been received
	    		$receive_item = get_entity($line_item_relationships[$receipt_item->getGUID()]);
	    		
				$set_properties_button = elgg_view('output/url', array(
				                           'data-jq-dropdown'    => '#'.$qid_n,
						                   'data-qid'            => $qid_n,
						                   'data-horizontal-offset'=>"-15",
				                           'text'  => elgg_view_icon('settings-alt'), 
				                           'title' => 'set properties',
				));
	    		unset($hidden_line_item_fields, $receipt_item_total, $line_item);
	    		if ($can_edit) {                                                       $display .= '1228 can edit<br>';
	    			if ($item_exists){
	    	            $linked_item[] = $originating_item;                            $display .= '1230 is_array($linked_item): '.is_array($linked_item).'<br>';
	    	            $receipt_item['retain_line_label'] = 'no';                     $display .= '1231 $receipt_item[item_guid]: '.$receipt_item['item_guid'].'<br>';
	    	        }
	    	        elseif (!empty($receipt_item['item_guid'])){
	    	            $linked_item[] = get_entity($receipt_item['item_guid']);
	    	        }
	    	        else {                                                             $display .= '1236 $linked_item->guid: '.$linked_item->guid.'<br>';
	    	            $linked_item = elgg_get_entities_from_relationship(array(
	        				'type' => 'object',
	        				'relationship' => 'receipt_item',
	        				'relationship_guid' => $receipt_item->guid,
	        				'inverse_relationship' => true,
	        				'limit' => false,
	        			));
	    		}                                                                      $display .= '1244 $linked_item[0]->guid:'.$linked_item[0]->guid.'<br>';
	    	
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
	    	              || $bulk_transfer)){                                           $display .= '1261 link: '.$receipt_item->guid.'<br>';
	    	//         if (!empty($linked_item[0]) && ($receipt_item->retain_line_label != 'yes' // retained for backward compatibility 
	    	//         		                     || $receipt_item->retain_line_label != 'retain')){
	    	            $receipt_item_guid = $linked_item[0]->guid ?: $receipt_item->item_guid;
	    				$detach = elgg_view("output/url",array(
	    			    	'href' => "action/jot/detach?element_type=receipt_item&guid=".$receipt_item_guid."&container_guid=$receipt_item->guid",
	    			    	'text' => elgg_view_icon('unlink'),
	    			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked item'),
	    			    	'encode_text' => false,
	    			    ));
	    	        	$line_item .= elgg_view('output/url', array(
	    	        			'text' =>  $linked_item[0]->title ?: $receipt_item->title,
	    	        			'href' =>  "market/view/$receipt_item_guid/Inventory",
	    		        		'class'=> 'rTableform90',
	    	        			'required'=>'',
	    	        	    ));
	    		        if ($item_exists & $n==1){                                               $display .= '1277 $item_exists<br>';
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
	        	        			'name'      => 'line_item['.$receipt_item->guid.'][title]',
	        	        			'value'     => $receipt_item->title,
	        	        			'class'     => 'rTableform90',
	    							'data-name' => 'title',
	    	        			    'readonly'  =>$receipt_item_read_only
	        	        	        ));
	    	        } 
	    	        else {                                                                       $display .= '1304 input<br>';
	    	        	$line_item = elgg_view('input/text', array(
	    	        			'name'      => 'line_item['.$receipt_item->guid.'][title]',
	    	        			'value'     => $receipt_item->title,
	    	        			'class'     => 'rTableform90',
	    						'data-name' => 'title',
	    	        			'readonly'  =>$receipt_item_read_only
	    	        	));        	
	    	        }
	    	        $receive_line_item = $line_item;
    				if (!$item_exists){                                                           $display .= '1314 !$item_exists<br>';
    				    $line_item = $detach.$line_item;
    				}
    				$recd_qty = $receive_item->recd_qty;
	                $qty = $item_exists  ? 1
	                                       : $receipt_item->qty;                                  $display .= '1319 $qty:'.$qty.'<br>';
	                $cost = $item_exists ? $originating_item->acquire_cost 
	                                       : $receipt_item->cost;
		    		$tax_options = ['name'     => 'line_item['.$receipt_item->guid.'][taxable]',
    							    'value'    => 1,
    							    'data-qid' => $qid_n,
	    							'data-name'=> 'taxable',
    	        			        'default'  => false];
	    	        if ($receipt_item->taxable == 1){
	    	        	$tax_options['checked'] = 'checked';
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
	    		$receive_line_item_received = elgg_view('input/checkbox',['name'     => 'line_item['.$receipt_item->guid.'][received]',
										    							  'value'    => 1,
        														          'readonly' => $receipt_item_read_only,
										    							  'data-qid' => $qid_n,
											    						  'data-name'=> 'received',
										    	        			      'default'  => false]);
	    		$receipt_line_item_recd_qty = $recd_qty;
	    		$receipt_line_item_qty = elgg_view('input/number', ['name'      => 'line_item['.$receipt_item->guid.'][qty]',
    															  'value'     => $qty,
	    				                                          'max'       => 0,
        														  'readonly' => $receipt_item_read_only,
    															  'data-qid'  => $qid_n,
    							                                  'data-name' => 'qty',
    															]);
	    		$receive_line_item_qty = $qty;
	    		$receive_line_item_qty .= elgg_view('input/hidden', ['name'      => 'line_item['.$receipt_item->guid.'][qty]',
    															  'value'     => $qty,
    															  'data-qid'  => $qid_n,
    							                                  'data-name' => 'qty',
    															  'class'     =>'nString',
    															]);
	    		$receive_line_item_recd_qty = elgg_view('input/number', ['name'      => 'line_item['.$receipt_item->guid.'][recd_qty]',
    															  'value'     => $rec_qty,
    															  'data-qid'  => $qid_n,
    							                                  'data-name' => 'recd_qty',
        														  'max'       => $max,
        														  'readonly' => $receipt_item_read_only,
    															]);
	    		$receipt_line_item_cost = elgg_view('input/text', ['name'     => 'line_item['.$receipt_item->guid.'][cost]',
	    															'value'    => $cost,
        														    'readonly' => $receipt_item_read_only,
	    															'data-qid' => $qid_n,
	    							                                'data-name'=> 'cost',
	    															'class'    => 'nString',
	    														]);
	    		$receipt_line_item_bo_qty = elgg_view('input/number',['name'      => 'line_item['.$receipt_item->guid.'][bo_qty]',
									    							'value'    => $receipt_item->bo_qty,
        														      'max'       => $max,
        														      'readonly' => $receipt_item_read_only,
									    							'data-qid' => $qid_n,
										    						  'data-name' => 'bo_qty',]);
	    		$receipt_line_item_bo_delivery_date = elgg_view('input/date', ['name'      => 'line_item['.$receipt_item->guid.'][bo_delivery_date]', 
	    				                                                       'value'     => $transfer->bo_delivery_date,
        														               'readonly' => $receipt_item_read_only,
	    				                                                       'data-name' => 'bo_delivery_date']);
	//echo $form; goto eof;
	    		
	        	$form .= "<div class='rTableRow receipt_item' data-qid=$qid_n $style>
	    					<div class='rTableCell'>$delete</div>
	    					<div class='rTableCell'>$receipt_line_item_recd_qty</div>
	    					<div class='rTableCell'>$receipt_line_item_qty</div>
	    					<div class='rTableCell'>$set_properties_button $line_item</div>
	    					<div class='rTableCell'>$tax_check</div>
	    					<div class='rTableCell'>$receipt_line_item_cost</div>
	    					<div class='rTableCell'><span id='{$qid_n}_line_total'>$receipt_item_total</span><span class='{$qid}_line_total line_total_raw'>$receipt_item->total</span></div>";
                foreach($hidden_line_item_fields as $name => $value){
                $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));}
	        	$form .= "</div>";
	        	/**************/
	    		$received_form .= 
	    		        "<div class='rTableRow receive_item' data-qid=$qid_n'>
	    					<div class='rTableCell'>$receive_line_item_received</div>
	    					<div class='rTableCell'>$receive_line_item_recd_qty</div>
	    					<div class='rTableCell'>$receive_line_item_qty</div>
	    					<div class='rTableCell'>$receive_line_item</div>
	    					<div class='rTableCell'>$receipt_line_item_bo_qty</div>
	    					<div class='rTableCell'>$receipt_line_item_bo_delivery_date</div>";
                foreach($hidden_line_item_fields as $name => $value){
                $received_form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));}
	        	$received_form .= "</div>";
	        	/**************/
	        	$property_cards .="<div id='$qid_n' class='jq-dropdown jq-dropdown-tip jq-dropdown-relative'>
                                      <div class='jq-dropdown-panel'>".
                                           elgg_view('forms/market/properties', array(
			                                   'element_type'   => 'receipt_item',
			                                   //**Note: Don't getGUID() or getContainerGUID().  These functions fail for new receipt items from an existing managed item
		                                       'guid'           => $receipt_item->guid,
		                                       'container_guid' => $receipt_item->container_guid,
			                                   'container_type' => 'transfer',
			                                   'origin'         => $origin,
		                                       'sort_order'     => $n,
		                                       'item'           => get_entity($receipt_item_guid),
		                                       'qid'            => $qid,
                                          	   'qid_n'          => $qid_n,
		                                       'line_item_behavior_list_class'  => $qid_n."_line_item_behavior_list",
                                               'line_item_behavior_list_data'   =>['qid'=>$qid_n],
		                                       'line_item_behavior_radio_class' => 'receipt-line-item-behavior'
		                    	        ))."
	    					       </div>
	    					   </div>";
	        	}
			}
		// Populate blank lines
		for ($i = $n+1; $i <= 1; $i++) {
				$qid_n = "{$qid}_{$i}";
				$set_properties_button = elgg_view('output/url', array(
						                   'data-jq-dropdown'    => '#'.$qid_n,
						                   'data-qid'            => $qid_n,
						                   'data-horizontal-offset'=>"-15",
				                           'text'  => elgg_view_icon('settings-alt'), 
				                           'title' => 'set properties',
				));
			    $pick = elgg_view('output/url', array(
			        		'text' => elgg_view_icon('settings-alt')));
			    $form .= "
				        <div class='rTableRow receipt_item' data-qid=$qid_n style='cursor:move'>
	                        <div class='rTableCell'>$delete</div>
	                        <div class='rTableCell'></div>
	    					<div class='rTableCell'>".elgg_view('input/text', array(
						    												'name'     => "line_item[$qid_n][qty]",
						    												'data-qid' => $qid_n,
	    							                                        'data-name'=>'qty',
	    																	'class'    =>'nString',
	    															))."</div>
	    					<div class='rTableCell'>$set_properties_button ".elgg_view('input/text', array(
	    												'name'     => "line_item[$qid_n][title]",
	    							                    'class'    => 'rTableform90',
	    							                    'required' =>'',
	    							                    'data-name'=> 'title'
	    											))."
	    	                </div>
	    					<div class='rTableCell'>".elgg_view('input/checkbox', array(
	    												'name'      => "line_item[$qid_n][taxable]",
	    												'value'     => 1,
	    	        			                        'default'   => false,
	    												'data-qid'  => $qid_n,
	    							                    'data-name' => 'taxable',
	    											))."</div>
	    					<div class='rTableCell'>".elgg_view('input/text', array(
						    												'name'      => "line_item[$qid_n][cost]",
						    						 						'class'     => 'last_line_item',
						    												'data-qid'  => $qid_n,
	    							                                        'data-name' => 'cost',
	    																	'class'     => 'nString',
	    											))."</div>
	    					<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid}_line_total line_total_raw'></span></div>";
			    if ($hidden_blank_line_item_fields){
	                	foreach($hidden_blank_line_item_fields as $name => $value){
	                        $form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
	                }
			    }
	        	$form .="
	                    </div>";
	        	$property_cards .= "<div id=$qid_n class='jq-dropdown jq-dropdown-tip jq-dropdown-relative'>
	                            	<div class='jq-dropdown-panel'>".
                                             elgg_view('forms/market/properties', array(
	    	                                   'element_type'   =>'receipt_item',
	    	                                   'container_type' => 'transfer',
	    	                                   'origin'         => $origin,
		                                       'qid'            => $qid,
                                          	   'qid_n'          => $qid_n,
		                                       'line_item_behavior_list_class'  => $qid_n."_line_item_behavior_list",
                                               'line_item_behavior_list_data'   =>['qid'=>$qid_n],
		                                       'line_item_behavior_radio_class' => 'receipt-line-item-behavior',
	                        	        ))."
	                                 </div>
                                </div>";
		}
	
		// Populate form footer
		$subtotal       = $item_exists ? $receipt_item_total
		                                 : money_format('%#10n', $transfer->subtotal);
		$shipping_cost  = elgg_view('input/text', array('name' => 'jot[shipping_cost]','value' => $transfer->shipping_cost, 'data-qid'=>$qid, 'class'=>'nString',));
		$shipping_cost .= elgg_view('input/hidden', array('name' => 'jot[snapshot][shipping_cost]','value' => $transfer->shipping_cost,));
		$sales_tax      = elgg_view('input/text', array('name' => 'jot[sales_tax]', 'value' => $transfer->sales_tax, 'data-qid'=>$qid, 'class'=>'nString',));
		$sales_tax     .= elgg_view('input/hidden', array('name' => 'jot[snapshot][sales_tax]', 'value' => $transfer->sales_tax,));
		$total          = $item_exists ? $receipt_item_total
		                                 : money_format('%#10n', $transfer->total);
		
		$form .= "<div id={$qid}_new_line_items class='new_line_items'></div>
				  <div class='rTableRow pin'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>Subtotal</div>
						<div class='rTableCell'><span id={$qid}_subtotal>$subtotal</span><span class='{$qid}_subtotal line_total_raw'>$transfer->subtotal</span></div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>Shipping</div>
						<div class='rTableCell'>$shipping_cost</div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell' style='white-space:nowrap;'>Sales Tax</div>
						<div class='rTableCell'>$sales_tax</div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>Total</div>
						<div class='rTableCell'><span id={$qid}_total>$total</span><span class='{$qid}_total line_total_raw'>$transfer->total</span></div>
					</div>
		        </div>
			</div>
		</div>";
		
		$form .= $received_form."</div></div><div>Notes<br>".$receipt_notes.$receive_notes."</div>";
		
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
		$form .= "<div id={$qid}_line_item_property_cards class='line_item_property_cards'></div>
				  $property_cards";  // Append property cards to the form body.
		
	    if ($hidden_blank_line_item_fields){
	    	foreach($hidden_blank_line_item_fields as $name => $value){
	    		$form .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
                }
	    }
		$form .= "
			</div>";
	break; // $presentation == 'compact'

/***********************************************************
 * COMPACT DROPDOWN presentation
 ***********************************************************/
	case 'compact dropdown':
		$receive_line_items = elgg_get_entities(['type'           => 'object',
			            		                                 'subtype'        => 'line_item',
			            		                                 'container_guid' => $transfer->getGUID(),
			            		                                 'limit'          => false]);
        if ($receive_line_items){
            foreach ($receive_line_items as $receive_line_item){                       $display .= '1611 $receive_line_item->guid: '.$receive_line_item->guid.'<br>';
            	foreach($receipt_items as $line_item){                                 $display .= '1612 $line_item->guid: '.$line_item->guid.'<br>';
            		if (check_entity_relationship($receive_line_item->guid, 'receive_item', $line_item->guid)){
            			$line_item_relationships[$line_item->guid] = $receive_line_item->guid;
            		}
            	}
            }
        }
		$content .= "<div id='header_1' class='rTable'>
				<div class='rTableBody'>
					<div class='rTableRow'>
						<div class='rTableCell'>Title</div>
						<div class='rTableCell'>$title_input</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'>Merchant</div>
						<div class='rTableCell'>$merchant</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'>Seller</div>
						<div class='rTableCell'>$seller</div>
					</div>			
				</div>
			</div>
			<div id='header_2' class='rTable'>
				<div class='rTableBody'>
					<div class='rTableRow'>
						<div class='rTableCell'>$associate_label</div>
						<div class='rTableCell'>$cashier</div>
						<div class='rTableCell'>$transaction_date_label</div>
						<div class='rTableCell'>$moment</div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'>$actor_label</div>
						<div class='rTableCell'>$purchased_by</div>
						<div class='rTableCell'>$order_no_label</div>
						<div class='rTableCell'>$order_no</div>
						<div class='rTableCell'>$invoice_no_label</div>
						<div class='rTableCell'>$invoice_no</div>
					</div>
					<div class='rTableRow'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>Document #</div>
						<div class='rTableCell'>$document_no</div>
						<div class='rTableCell'>$transaction_no_label</div>
						<div class='rTableCell'>$receipt_no</div>
					</div>
				</div>
		</div>
		<div id='line_items' class='rTable'>
				<div class='rTableBody'>
					<div id='sortable'>
					<div class='rTableRow pin'>
						<div class='rTableCell'>".
							elgg_view('output/url', array(
							    'text' => '+',
								'href' => '#',
								'class' => 'elgg-button-submit-element new-item',
								'data-element'=> 'new_receipt_item',
								))."</div>
						<div class='rTableHead'>Qty</div>
						<div class='rTableHead'>Item</div>
						<div class='rTableHead'>tax?</div>
						<div class='rTableHead'>Cost</div>
						<div class='rTableHead'>Total</div>
					</div>
					";
		// Populate existing receipt items
		$n=0;
		$delete = elgg_view('output/url', array(
		        'title'=>'remove receipt item',
		        'class'=>'remove-node',
		        'style'=> 'cursor:pointer',
		        'text' => elgg_view_icon('delete-alt'),
				'data-qid_n'=>$qid_n,
		));
		if (!empty($receipt_items)){
	    	foreach($receipt_items as $receipt_item){
		        $n++;
		        unset($line_item);
	    		$element_type = 'receipt item';
	    		$can_edit = $item_exists ?: $receipt_item->canEdit(); 
	    		$qty = $cost = NULL;
	    		unset($hidden_line_item_fields, $receipt_item_total, $receipt_item_guid, $linked_item);
	    		if ($can_edit) {                                                       $display .= '352 can edit<br>';
	    			$select = elgg_view('input/checkbox', array('name'    => 'do_me',
	    														'value'   => $receipt_item->guid,
	    								        			    'default' => false,
	    								        			   ));
	    	        if ($item_exists){
	    	            $linked_item[] = $originating_item;
	    	            $receipt_item->retain_line_label = 'no';
	    	        }
	    	        elseif (!empty($receipt_item->item_guid)){
	    	            $linked_item[] = get_entity($receipt_item->item_guid);
	    	        }
	    	        else {
	    	            $linked_item = elgg_get_entities_from_relationship(array(
	        				'type' => 'object',
	        				'relationship' => 'receipt_item',
	        				'relationship_guid' => $receipt_item->guid,
	        				'inverse_relationship' => true,
	        				'limit' => false,
	        			));
	    		}
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
	    	              || $bulk_transfer)){
	    	            $receipt_item_guid = $linked_item[0]->guid ?: $receipt_item->item_guid;
	    				$detach = elgg_view("output/url",array(
	    			    	'href' => "action/jot/detach?element_type=receipt_item&guid=".$receipt_item_guid."&container_guid=$receipt_item->guid",
	    			    	'text' => elgg_view_icon('unlink'),
	    			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked item'),
	    			    	'encode_text' => false,
	    			    ));
	    				if (!$item_exists){
	    				    $line_item = $detach;
	    				}
	    	        	$line_item .= elgg_view('output/url', array(
	    	        			'text' =>  $linked_item[0]->title ?: $receipt_item->title,
	    	        			'href' =>  "market/view/$receipt_item_guid/Inventory",
	    		        		'class'=> 'rTableform90',
	    	        	    ));
	    		        if ($item_exists && $n==1){                                               $display .= '426 $item_exists<br>';
	    		            unset ($hidden_line_item_fields['line_item['.$receipt_item->guid.'][guid]']);
			                $receipt_item_guid                                   = $originating_item->guid;
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
	    	        if (!empty($receipt_item->total)){
	    	        	$receipt_item_total = $receipt_item->sort_order == 1 ? money_format('%#10n', $receipt_item->total) 
	    	        	                                     : number_format($receipt_item->total, 2);
	    	        }
	    	        else {
	    	            $receipt_item_total = $cost * $qty; 
	    	        }
	    		}
	    		
	        	$content .= "<div class='rTableRow' style='cursor:move'>
	        					<div class='rTableCell'>$delete</div>
	        					<div class='rTableCell'>".elgg_view('input/text', array(
	        																	'name' => 'line_item['.$receipt_item->guid.'][qty]',
	        																	'value' => $qty,
	        																))."</div>
	        					<div class='rTableCell'>$set_properties_button $line_item
	        					    <div class='receipt-item-properties' style='display:none'>".
	        					                                           elgg_view('forms/market/properties', array(
	                                        	                                   'element_type'   => 'receipt_item',
	        					                                                   'guid'           => $receipt_item->guid,
	        					                                                   'container_guid' => $receipt_item->container_guid,
	                                        	                                   'container_type' => 'transfer',
	                                        	                                   'origin'         => $origin,
	        					                                                   'sort_order'     => $n,
	        					                                                   'item'           => get_entity($receipt_item_guid),
	                                        	        ))."
	        					    </div>
	        					</div>
	        					<div class='rTableCell'>$tax_check</div>
	        					<div class='rTableCell'>".elgg_view('input/text', array(
	        																	'name' => 'line_item['.$receipt_item->guid.'][cost]',
	        																	'value' => $cost,
	        																))."</div>
	        					<div class='rTableCell'>$receipt_item_total</div>";
	                        	foreach($hidden_line_item_fields as $name => $value){
	                                $content .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
	                            }
	        	$content .="   </div>";
	        	}
			}
		// Populate blank lines
		for ($i = $n+1; $i <= 1; $i++) {
			    $pick = elgg_view('output/url', array(
			        		'text' => elgg_view_icon('settings-alt')));
		        $pick_menu = "<span title='Save form before setting line item properties'>$pick</span>";
		$content .= "<div class='rTableRow' style='cursor:move'>
						<div class='rTableCell'>$delete</div>
						<div class='rTableCell'>".elgg_view('input/hidden', ['name' => 'line_item[][new_line]']).
						                                           elgg_view('input/text', ['name' => 'line_item[][qty]'])."</div>
						<div class='rTableCell'>$set_properties_button ".elgg_view('input/text', array(
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
						<div class='rTableCell'>".elgg_view('input/checkbox', array(
													'name' => 'line_item[][taxable]',
													'value'=> 1,
		        			                        'default' => false,
												))."</div>
						<div class='rTableCell'>".elgg_view('input/text', array(
													'name' => 'line_item[][cost]',
							 						'class' => 'last_line_item',
												))."</div>
						<div class='rTableCell'></div>";
			$content .= "	</div>";
		}
		
		// Populate form footer
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
		
		$content .= "<div class='new_line_items'></div>
					<div class='rTableRow pin'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>Subtotal</div>
						<div class='rTableCell'>$subtotal</div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>Shipping</div>
						<div class='rTableCell'>$shipping_cost</div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>Sales Tax $sales_tax_rate_label</div>
						<div class='rTableCell'>$sales_tax</div>
					</div>
					<div class='rTableRow pin'>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>Total</div>
						<div class='rTableCell'>$total</div>
					</div>
		        </div>
				</div>
		</div>";
        $vars['content']      = $content;
        $vars['disable_save'] = elgg_extract('disable_save', $vars, false);
        $vars['show_title']   = elgg_extract('show_title', $vars, false);
        $vars['position']     = 'relative';
		$form                 = elgg_view_layout('qbox', $vars);
	break;
	case 'qbox':
	case 'popup':
		$received    = $entity->received ?: false;
		$preordered  = $entity->preorder_flag ?: false;
		$body_vars                 = $vars;
		$body_vars['presentation'] = 'compact';
		$body_vars['hide_title']   = true;
		$vars['content']      = elgg_view('forms/transfers/elements/receipt',$body_vars); // Swings back around to pull from the Compact presentation above.
        $vars['disable_save'] = elgg_extract('disable_save', $vars, false);
        $vars['show_title']   = elgg_extract('show_title', $vars, false);
        $vars['position']     = 'relative';
        $vars['show_receive'] = elgg_extract('show_receive', $vars, ($preordered + !$received));
        switch (strtolower(substr($entity->status,0, 1))){
        	case 'r': $vars['message'] = 'Received';break;
        	case 'p': $vars['message'] = 'Partial';break;
        	case 'o': $vars['message'] = 'Ordered';break;}
		$form                 = elgg_view_layout('qbox', $vars);
	break;		
}
echo $form;
eof:
//register_error($display);
