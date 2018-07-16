Form: jot\views\default\forms\transfers\elements\receipt.php
<?php
/**********
 * Version 02
 * 
 */

setlocale(LC_MONETARY, 'en_US');
$transfer_guid  = (int) get_input('guid');                             $display       .= '$transfer_guid='.$transfer_guid.'<br>';
$section        = get_input('section');                                $display       .= '$section='.$section.'<br>';
$aspect         = elgg_extract('aspect',$vars);                        $display        = '<br>$aspect='.$aspect.'<br>';
//$aspect         = $section;
//$aspect         = $vars['aspect'];
$asset          = $vars['asset'];
$container_guid = $vars['container_guid'];
$referrer       = $vars['referrer'];                                   $display       .= '$referrer='.$referrer.'<br>';
$subtype        = 'transfer';
$graphics_root  = elgg_get_site_url().'_graphics';
$presentation   = elgg_extract('presentation', $vars, 'full');         $display       .= '$presentation='.$presentation.'<br>';

$exists = true;
if ($transfer_guid == 0){
	$exists = false;
}
else {
	$transfer_type = get_entity($transfer_guid)->getSubtype();
    // can pass an entity guid to receipt to begin a new receipt record
    if ($transfer_type == 'market' || $transfer_type == 'item'){
        $originating_item     = get_entity($transfer_guid);
        $title                = $originating_item->title;
        $receipt_item_details = array('retain_line_label' => 'no',
                                      'item_guid'         => $originating_item->guid,
                                      'qty'               => 1,
                                     );
        $receipt_items[] = $receipt_item_details;
        $existing_item   = true;
        $exists          = false;
    }
    else {
        $entity          = get_entity($transfer_guid);
        $title           = $entity->title;
        $exists          = true;
    }
}                                                                      $display .= '$exists: '.$exists.'<br>';

if ($exists){
	// switch subtype if it exists
	$subtype       = $entity->getSubtype();
	$aspect        = $entity->aspect;
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
}

$form .= elgg_view('input/hidden', array('name' => 'element_type'       , 'value' => $element_type));
$form .= elgg_view('input/hidden', array('name' => 'action'             , 'value' => elgg_extract('action', $vars)));
$form .= elgg_view('input/hidden', array('name' => 'guid'               , 'value' => $transfer_guid));
$form .= elgg_view('input/hidden', array('name' => 'section'            , 'value' => $section));
$form .= elgg_view('input/hidden', array('name' => 'aspect'             , 'value' => $aspect));
$form .= elgg_view('input/hidden', array('name' => 'subtype'            , 'value' => $subtype));
$form .= elgg_view('input/hidden', array('name' => 'asset'              , 'value' => $vars['asset'])).
	     elgg_view('input/hidden', array('name' => 'container_guid'     , 'value' => $vars['container_guid'])).
         elgg_view('input/hidden', array('name' => 'parent_guid'        , 'value' => $vars['parent_guid'])).
         elgg_view('input/hidden', array('name' => 'aspect'             , 'value' => $aspect)).
         elgg_view('input/hidden', array('name' => 'item_type'          , 'value' => 'receipt_item')).
 	 	 elgg_view('input/hidden', array('name' => 'referrer'           , 'value' => $referrer)).
 	 	 elgg_view('input/hidden', array('name' => 'presentation'       , 'value' => $presentation));

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
$merchant='';
if ($merchants) {
	$element_type = 'merchant';
	foreach ($merchants as $i) {
	$link         = elgg_view('output/url', array('text' => $i->name,'href' =>  "groups/profile/$i->guid"));
	$merchant_guid = elgg_view('input/hidden', array('name' => 'merchant_guid', 'value' => $i->guid,));
	$merchant_name = elgg_view('input/hidden', array('name' => 'merchant', 'value' => $i->name,));
	if ($i->canEdit()) {
		$detach = elgg_view("output/url",array(
	    	'href' => "action/jot/detach?element_type=$element_type&&guid=$i->guid&container_guid=$transfer_guid",
	    	'text' => elgg_view_icon('unlink'),
	    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked merchant'),
	    	'encode_text' => false,
	    ));
	}
//$merchant .= "$link<br>";
$merchant .= "$detach $link $merchant_guid $merchant_name<br>";
	}
}	
else {$merchant = elgg_view('input/text', array(
							'name' => 'jot[merchant]',
		                    'value' => $entity->merchant,
					));}

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
    
    $form .= elgg_view('input/hidden', array('name' => 'jot[aspect]'        , 'value' => $aspect));
    $form .= elgg_view('input/hidden', array('name' => 'jot[guid]'          , 'value' => $guid));
    $form .= elgg_view('input/hidden', array('name' => 'jot[location]'      , 'value' => elgg_extract('location', $vars)));
    $form .= elgg_view('input/hidden', array('name' => 'jot[owner_guid]'    , 'value' => elgg_extract('owner_guid', $vars)));
    $form .= elgg_view('input/hidden', array('name' => 'jot[asset]'         , 'value' => $asset));
    $form .= elgg_view('input/hidden', array('name' => 'jot[container_guid]', 'value' => $container_guid));
        
	$form .= "<div class='rTable' style='width:600px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:90px'>Title</div>
					<div class='rTableCell' style='width:510px'>".elgg_view('input/text', array(
																		'name' => 'jot[title]',
							                                            'value' => $title,
																	))."
					</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:90px'>Merchant ".$pick_merchant."</div>
					<div class='rTableCell' style='width:510px'>".$merchant."</div>
				</div>			
			</div>
		</div>";
	
	$form .= "<div class='rTable' style='width:600px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:100px'>Sales Assoc.</div>
					<div class='rTableCell' style='width:200px'>".elgg_view('input/text', array(
																		'name' => 'jot[cashier]',
																		'value' => $entity->cashier,
																	))."</div>
					<div class='rTableCell' style='width:180px'>Date</div>
					<div class='rTableCell' style='width:200px'>".elgg_view('input/date', array(
																		'name' => 'jot[purchase_date]',
																		'value' => $entity->purchase_date,
																	))."
					</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:100px'>Buyer</div>
					<div class='rTableCell' style='width:200px'>".elgg_view('input/text', array(
																		'name' => 'jot[purchased_by]',
																		'value' => $entity->purchased_by,
																	))."</div>
					<div class='rTableCell' style='width:180px'>Receipt #</div>
					<div class='rTableCell' style='width:200px'>".elgg_view('input/text', array(
																		'name' => 'jot[receipt_no]',
																		'value' => $entity->receipt_no,
																	))."
					</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:100px'></div>
					<div class='rTableCell' style='width:200px'></div>
					<div class='rTableCell' style='width:180px'>Document #</div>
					<div class='rTableCell' style='width:200px'>".elgg_view('input/text', array(
																		'name' => 'jot[document_no]',
																		'value' => $entity->document_no,
																	))."
					</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:100px'></div>
					<div class='rTableCell' style='width:200px'></div>
					<div class='rTableCell' style='width:180px'>Receipt Location $set_button</div>
					<div class='rTableCell' style='width:200px'>".elgg_view('input/text', array(
																		'name' => 'jot[location_receipt]',
																		'value' => $entity->location_receipt,
																	))."
					</div>
				</div>
			</div>
	</div>
	<!--On Selected: [pick item]-->
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
	
	// Populate existing receipt items
	$n=0;
	$display .= '';
	foreach($receipt_items as $item){
		$n = $n+1;
		$element_type = 'receipt item';                                        $display .= '285 '. $item['retain_line_label'].'<br>';
		$can_edit = $existing_item ? true : $item->canEdit(); 
		$qty = $cost = NULL;
		if ($can_edit) {                                                       $display .= '287 can edit<br>';
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
	            $linked_item[] = $originating_item;                            $display .= '300 is_array($linked_item): '.is_array($linked_item).'<br>';
	        }
	        elseif (!empty($item->item_guid)){
	            $linked_item[] = get_entity($item->item_guid);
	        }
	        else {
	            $linked_item = elgg_get_entities_from_relationship(array(
    				'type' => 'object',
    				'relationship' => 'receipt_item',
    				'relationship_guid' => $item->guid,
    				'inverse_relationship' => true,
    				'limit' => false,
    			));
		}
	        
    
	$display .= 'Title: '.$title.'<br>';         
	$display .= '$item->retain_line_label: '.$item['retain_line_label'].'<br>';
	$display .= '$item->item_guid: '.$item->item_guid.'<br>';
	$display .= '$item->guid: '.$item->guid.'<br>';
	$display .= '$linked_item->guid: '.$linked_item[0]->guid.'<br>';
	//$display .= 'Warranty: '.$linked_item[0]->warranty.'<br>';
	
	        if (is_array($linked_item) && ($item['retain_line_label'] == 'no' || $bulk_transfer)){         $display .= '319 link<br>';
	//         if (!empty($linked_item[0]) && ($item->retain_line_label != 'yes' // retained for backward compatibility 
	//         		                     || $item->retain_line_label != 'retain')){
				$detach = elgg_view("output/url",array(
			    	'href' => "action/jot/detach?element_type=receipt_item&&guid=".$linked_item[0]->guid."&container_guid=$item->guid",
			    	'text' => elgg_view_icon('unlink'),
			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked item'),
			    	'encode_text' => false,
			    ));
				if (!$existing_item){$line_item = $detach;}
	        	$line_item .= elgg_view('output/url', array(
	        			'text' =>  $linked_item[0]->title,
	        			'href' =>  "market/view/".$linked_item[0]->guid."/Inventory",
		        		'class'=> 'rTableform90',
	        	    ));
		        $line_item .= elgg_view('input/hidden', array(
		        		'name' => 'item[title][]',
		        		'value' => $title,
		            ));
		        $line_item .= elgg_view('input/hidden', array(
						'name' => 'item[guid][]',
						'value' => $item->guid,
					));
		        if ($existing_item){
		            $line_item .= elgg_view('input/hidden', array(
						'name' => 'item[retain_line_label][]',
						'value' => 'link',
					));
		            $line_item .= elgg_view('input/hidden', array(
						'name' => 'item[timeline_label][]',
						'value' => 'Initial Purchase',
					));
		            $line_item .= elgg_view('input/hidden', array(
						'name' => 'item[que_contribution][]',
						'value' => 'purchase',
					));
    		        $line_item .= elgg_view('input/hidden', array(
    						'name' => 'item[item_guid][]',
    						'value' => $originating_item->guid,
    					));
		        }
	            $qty = $existing_item ? 1 :$item->qty;
	            $cost = $existing_item ?$originating_item->acquire_cost :$item->cost;
	        } else {                                                                                     $display .= '343 input<br>';
	        	$line_item = elgg_view('input/text', array(
	        			'name' => 'item[title][]',
	        			'value' => $title,
	        			'class'=> 'rTableform90',
	        	));        	
	        }
	//$display .= '$line_item: '.$line_item.'<br>';
	        if ($item->taxable == 1){
	        	$tax_options = array('name'    => 'item[taxable][]',
							         'checked' => 'checked',
							         'value'   => 1,
	        			             'default' => false,
	        			            );
	        } else {
	        	$tax_options = array('name'    => 'item[taxable][]',
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
																	'name' => 'item[qty][]',
																	'value' => $qty,
																))."</div>
					<div class='rTableCell' style='width:60%'>$pick_menu $line_item</div>
					<div class='rTableCell' style='width:5%'>$tax_check</div>
					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
																	'name' => 'item[cost][]',
																	'value' => $cost,
																))."</div>
					<div class='rTableCell' style='width:10%;text-align:right'>$item_total</div>
				</div>";
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
												'name' => 'item[qty][]',
											))."</div>
					<div class='rTableCell' style='width:60%'>".$pick_menu.' '.elgg_view('input/text', array(
												'name' => 'item[title][]',
							                    'class'=> 'rTableform90',
											))."</div>
					<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
												'name' => 'item[taxable][]',
												'value'=> 1,
	        			                        'default' => false,
											))."</div>
					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
												'name' => 'item[cost][]',
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
*/	$form .= "<div class='new_line_items'></div>
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
					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
																		'name' => 'jot[shipping_cost]',
																		'value' => $entity->shipping_cost,
																	))."
					</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:10%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:60%'></div>
					<div class='rTableCell' style='width:5%'></div>
					<div class='rTableCell' style='width:10%'>Sales Tax</div>
					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
																		'name' => 'jot[sales_tax]',
																		'value' => $entity->sales_tax,
																	))."
					</div>
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
	
	$form .='<div class="elgg-foot">'.
	   elgg_view('input/submit', array('value' => elgg_echo('save'), 'name' => 'submit')).
	   elgg_view('input/submit', array('value' => 'Apply', 'name' => 'apply')).
	'<a href='.elgg_get_site_url() . $referrer.' class="cancel_button">Cancel</a>
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
												'name' => 'item[qty][]',
											))."</div>
					<div class='rTableCell' style='width:60%'>".$pick_menu.' '.elgg_view('input/text', array(
												'name' => 'item[title][]',
							                    'class'=> 'rTableform90',
											))."</div>
					<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
												'name' => 'item[taxable][]',
												'value'=> 1,
	        			                        'default' => false,
											))."</div>
					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
												'name' => 'item[cost][]',
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
													'name' => 'item[qty][]',
												))."</div>
						<div class='rTableCell' style='width:70%'>".elgg_view('input/text', array(
													'name' => 'item[title][]',
												))."</div>
						<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
													'name' => 'item[taxable][]',
													'value'=> 1,
		        			                        'default' => false,
												))."</div>
						<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
													'name' => 'item[cost][]',
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
		elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save:define'))).'
	</div>';	
}
echo $form;
// Line Items clone
echo "
<div style='visibility:hidden'>
	<div class='line_items'>
		<div class='rTableRow'>
				<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
											'name' => 'item[qty][]',
										))."</div>
				<div class='rTableCell' style='width:70%'>".$pick_menu.' '.elgg_view('input/text', array(
											'name' => 'item[title][]',
										))."</div>
				<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
											'name' => 'item[taxable][]',
											'value'=> 1,
        			                        'default' => false,
										))."</div>
				<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
											'name' => 'item[cost][]',
					 						'class' => 'last_line_item',
										))."</div>
				<div class='rTableCell' style='width:10%'></div>
			</div>
	</div>
</div>"; // end of Line Items clone

echo $display;