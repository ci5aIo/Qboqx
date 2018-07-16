<!--Form: jot\views\default\forms\transfers\elements\ownership.php-->
<?php
/**
 * Included from jot\views\default\forms\transfers\edit.php
 */

if ($exists){
	// switch subtype if it exists
	$subtype       = $entity->getSubtype();
	$aspect        = $entity->aspect;
	$transfer_items = elgg_get_entities_from_relationship(array(
		'type'                 => 'object',
		'relationship'         => 'ownership_item',
		'relationship_guid'    => $transfer_guid,
		'inverse_relationship' => true,
		'limit'                => false,
		// Receipt items must have a 'sort_order' value to apper in this group.  This value is applied by the actions pick and edit.
		'order_by_metadata'    => array('name' => 'sort_order', 
				                        'direction' => ASC, 
				                        'as' => 'integer'),
	));
	$new_owners = elgg_get_entities_from_relationship(array(
		'type'                 => 'group',
		'relationship'         => 'merchant_of',
		'relationship_guid'    => $transfer_guid,
	    'inverse_relationship' => true,
		'limit'                => false,
	));
	if (!$new_owners){
		// provided for backward compatibility
		$new_owners = elgg_get_entities_from_relationship(array(
				'type'                 => 'group',
				'relationship'         => 'supplier_of',
				'relationship_guid'    => $transfer_guid,
				'inverse_relationship' => true,
				'limit'                => false,
		));
	}
}
if(!empty($shelf)){
    $bulk_transfer = true;
// Extract Shelf Items    
    foreach($shelf as $key=>$contents){                        $display .= '$key=>$contents: '."$key -> $contents<br>";
        foreach($contents as $position=>$value){              //$display .= '$position->$value: '.$position.' -> '.$value.'<br>';
            while (list($position, $value) = each($contents)){//$display .= '$position->$value: '.$position.' -> '.$value.'<br>';
                                                              $display .= '$position: '.$position.'<br>';
                if ($position == 'guid'){                         //$display .= '$position->$value: '.$position.' -> '.$value.'<br>';
                    $transfer_items[] = get_entity($value);    $display .= 'guid: '.$value.'<br>';
                    $shelf_items[]['guid'] = $value;
                }
                if ($position == 'quantity'){                 $display .= '$position->$value: '.$position.' -> '.$value.'<br>';
                    $shelf_items[]['qty'] = $value;             $display .= '$qty: '.$qty.'<br>';
                }
            }
        }
    }
}
// Pivot Shelf Items
// @TODO incorporate the pivot into the extraction
	  foreach ($shelf_items as $key1=>$value1){                    $display .= '59 $key: '.$key1.'<br>';
	  	foreach($value1 as $key2=>$value2){                   $display .= '60 $values: '.$key2.'=>'.$value2.'<br>';
	  	    if ($key2 == 'guid'){
	  	        $line_items[$key1][$key2] = $value2;           //$display .= '62 $line_items[$key1][$key2]: '.$line_items[$key1].'['.$line_items[$key1][$key2].']<br>';
	  	        $key3 = $key1;
	  	    }
	  	    if ($key2 == 'qty'){
	  	        $line_items[$key3][$key2] = $value2;           //$display .= '65 $line_items[$key3][$key2]: '.$line_items[$key2].'['.$line_items[$key3][$key2].']<br>';
	  	    }
		  }
	  }
$line_items = array_values($line_items); // reorder the array to remove blank keys 

$form .= elgg_view('input/hidden', array('name' => 'element_type'       , 'value' => $element_type)).
	     elgg_view('input/hidden', array('name' => 'action'             , 'value' => elgg_extract('action', $vars))).
	     elgg_view('input/hidden', array('name' => 'guid'               , 'value' => $transfer_guid)).
	     elgg_view('input/hidden', array('name' => 'section'            , 'value' => $section)).
	     elgg_view('input/hidden', array('name' => 'aspect'             , 'value' => $aspect)).
	     elgg_view('input/hidden', array('name' => 'title'              , 'value' => 'Transfer Ownership')).
	     elgg_view('input/hidden', array('name' => 'subtype'            , 'value' => $subtype)).
	     elgg_view('input/hidden', array('name' => 'asset'              , 'value' => $vars['asset'])).
	     elgg_view('input/hidden', array('name' => 'container_guid'     , 'value' => $vars['container_guid'])).
         elgg_view('input/hidden', array('name' => 'parent_guid'        , 'value' => $vars['parent_guid'])).
         elgg_view('input/hidden', array('name' => 'item_type'          , 'value' => 'ownership_item')).
 	 	 elgg_view('input/hidden', array('name' => 'referrer'           , 'value' => $referrer)).
 	 	 elgg_view('input/hidden', array('name' => 'presentation'       , 'value' => $presentation));

if ($exists){
	$pick_owner = "<span title='Select merchant'>".
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
	$pick_owner = "<span title='Save form before picking merchant'>".
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
$new_owner='';
if ($new_owners) {
	$element_type = 'merchant';
	foreach ($new_owners as $i) {
	$link         = elgg_view('output/url', array('text' => $i->name,'href' =>  "groups/profile/$i->guid"));
	$new_owner_guid = elgg_view('input/hidden', array('name' => 'merchant_guid', 'value' => $i->guid,));
	$new_owner_name = elgg_view('input/hidden', array('name' => 'merchant', 'value' => $i->name,));
	if ($i->canEdit()) {
		$detach = elgg_view("output/url",array(
	    	'href' => "action/jot/detach?element_type=$element_type&&guid=$i->guid&container_guid=$transfer_guid",
	    	'text' => elgg_view_icon('unlink'),
	    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked merchant'),
	    	'encode_text' => false,
	    ));
	}
//$new_owner .= "$link<br>";
$new_owner .= "$detach $link $new_owner_guid $new_owner_name<br>";
	}
}	
else {$new_owner = elgg_view('input/text', array(
							'name' => 'jot[merchant]',
		                    'value' => $entity->merchant,
					));}

$ownership_options = array('name'=>'jot[ownership_selections]',
                           'options'=>array('Item Owner'=>'item',
                                            'Process Owner'=>'process',
                                            'Service Owner'=>'service',
                                            'Steward'=>'steward'),
                           'align'=>'horizontal');
$ownership_selections = elgg_view('input/checkboxes', $ownership_options);
$exchange_options = array('name'=>'jot[exchange_selection]',
                           'options'=>array('Agreement'=>'agreement',
                                            'Assignment'=>'assignment',
                                            'Promise'=>'promise',
                                            'Donate'=>'donate',
                                            'Trade'=>'trade'),
                           'align'=>'horizontal');
$exchange_selection = elgg_view('input/radio', $exchange_options);
$delivery_options = array('name'=>'jot[exchange_selection]',
                           'options'=>array('Ship'=>'ship',
                                            'Pick Up'=>'pickup',
                                            'Assign'=>'assign'),
                           'align'=>'horizontal');
$delivery_selection = elgg_view('input/radio', $delivery_options);

if ($presentation == 'full'){
    
    $form .= elgg_view('input/hidden', array('name' => 'jot[aspect]'        , 'value' => $aspect));
    $form .= elgg_view('input/hidden', array('name' => 'jot[guid]'          , 'value' => $guid));
    $form .= elgg_view('input/hidden', array('name' => 'jot[location]'      , 'value' => elgg_extract('location', $vars)));
    $form .= elgg_view('input/hidden', array('name' => 'jot[owner_guid]'    , 'value' => elgg_extract('owner_guid', $vars)));
    $form .= elgg_view('input/hidden', array('name' => 'jot[asset]'         , 'value' => $asset));
    $form .= elgg_view('input/hidden', array('name' => 'jot[container_guid]', 'value' => $container_guid));
        
	$form .= "<div class='rTable' style='width:600px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:110px'>Ownership</div>
					<div class='rTableCell' style='width:490px'>$ownership_selections</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:110px'>Transfer Terms</div>
					<div class='rTableCell' style='width:490px'>$exchange_selection</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:110px'>Delivery</div>
					<div class='rTableCell' style='width:490px'>$delivery_selection</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:110px'>Transfer Date</div>
					<div class='rTableCell' style='width:490px'>".elgg_view('input/date', array(
																		'name' => 'jot[transfer_date]',
																		'value' => $entity->transfer_date,
					                                                    'style' =>'width:90px',
																	))."
					</div>
				</div>	
				<div class='rTableRow'>
					<div class='rTableCell' style='width:110px'>Transfer to ".$pick_owner."</div>
					<div class='rTableCell' style='width:490px'>".$new_owner."</div>
				</div>		
			</div>
		</div>";
	$form .= "
	<div class='rTable' style='width:100%'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:0%'></div>
					<div class='rTableHead' style='width:5%'>Qty</div>
					<div class='rTableHead' style='width:95%'>Item</div>
				</div>
				";
	
	// Populate existing receipt items
	$n=0;
	$display .= '';
	foreach($transfer_items as $item){
		$n = $n+1;
		$element_type = 'ownership transfer item';
		if ($item->canEdit()) {
/*			$delete = elgg_view("output/url",array(
		    	'href' => "action/jot/delete?guid=$item->guid&container_guid=$transfer_guid",
		    	'text' => elgg_view_icon('arrow-left'),
		    	'confirm' => sprintf('Remove item?'),
		    	'encode_text' => false,
		    ));
*/			$select = elgg_view('input/checkbox', array('name'    => 'do_me',
														'value'   => $item->guid,
								        			    'default' => false,
								        			   ));
			$title = $item->title;
			if (!empty($shelf)){                                $display .= '306 - shelf is not empty<br>';
			    $linked_item = array($item);
			}
			else {
    	        $linked_item = elgg_get_entities_from_relationship(array(
    				'type' => 'object',
    				'relationship' => 'ownership_item',
    				'relationship_guid' => $item->guid,
    				'inverse_relationship' => true,
    				'limit' => false,
    			));
			}
       
	        if (!empty($linked_item[0]) && $bulk_transfer){
	//         if (!empty($linked_item[0]) && ($item->retain_line_label != 'yes' // retained for backward compatibility 
	//         		                     || $item->retain_line_label != 'retain')){
				$detach = elgg_view("output/url",array(
			    	'href' => "action/jot/detach?element_type=receipt_item&guid=".$linked_item[0]->guid."&container_guid=$item->guid",
			    	'text' => elgg_view_icon('unlink'),
			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked item'),
			    	'encode_text' => false,
			    ));
//				$line_item = $detach;
	        	$line_item = elgg_view('output/url', array(
	        			'text' =>  $linked_item[0]->title,
	        			'href' =>  "market/view/".$linked_item[0]->guid."/".$linked_item[0]->title."/Inventory",
	        	    ));
		        $line_item .= elgg_view('input/hidden', array(
		        		'name' => 'item[title][]',
		        		'value' => $title,
		            ));
		        $line_item .= elgg_view('input/hidden', array(
						'name' => 'item[item_guid][]',
						'value' => $item->guid,
					));
		        if ($bulk_transfer){
		            foreach($line_items as $key1=>$values){
		                foreach ($values as $key=>$value){                          $display .= '350 $key=>$value: '."$key=>$value<br>"; 
		                    if ($key == 'guid' && $value == $item->guid){
		                        $line_item_key = $key1;                             $display .= '352 $line_item_key: '."$line_item_key<br>";
		                    }
		                }
		            }
		            $qty = $line_items[$line_item_key]['qty'];                      $display .= '356 $qty: '."$qty<br>";
		        }
		        else {
		            $qty = $item->qty;
		        }
	        } else {
	        	$line_item = elgg_view('input/text', array(
	        			'name' => 'item[title][]',
	        			'value' => $title,
	        			'class'=> 'rTableform90',
	        	));        	
	        }
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
					<div class='rTableCell' style='width:0%'>$select</div>
					<div class='rTableCell' style='width:5%'>".elgg_view('input/number', array(
																	'name' => 'item[qty][]',
																	'value' => $qty,
																))."</div>
					<div class='rTableCell' style='width:95%'>$pick_menu $line_item</div>
				</div>
			</div>";
	}

	$cancel_button = elgg_view('input/button', array(
			'value' => elgg_echo('cancel'),
			'href' =>  $entity ? $referrer : '#',
	));
	
	$form .='<div class="elgg-foot">'.
	   elgg_view('input/submit', array('value' => elgg_echo('save'), 'name' => 'submit')).
	   elgg_view('input/submit', array('value' => 'Apply', 'name' => 'apply')).
	'<a href='.elgg_get_site_url() . $referrer.' class="cancel_button">Cancel</a>
	</div>';
}
if ($presentation == 'box'){
    $form .= elgg_view('input/hidden', array('name' => 'jot[aspect]'        , 'value' => $aspect));
    $form .= elgg_view('input/hidden', array('name' => 'jot[owner_guid]'    , 'value' => elgg_extract('owner_guid', $vars)));
    $form .= elgg_view('input/hidden', array('name' => 'jot[asset]'         , 'value' => $asset));
    $form .= elgg_view('input/hidden', array('name' => 'jot[container_guid]', 'value' => $container_guid));
    
	$form .= "<div class='rTable' style='width:400px'>
		<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:110px'>Ownership</div>
					<div class='rTableCell' style='width:290px'>$ownership_selections</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:110px'>Transfer Terms</div>
					<div class='rTableCell' style='width:290px'>$exchange_selection</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:110px'>Delivery</div>
					<div class='rTableCell' style='width:290px'>$delivery_selection</div>
				</div>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:110px'>Transfer Date</div>
					<div class='rTableCell' style='width:290px'>".elgg_view('input/date', array(
																		'name' => 'jot[transfer_date]',
																		'value' => $entity->transfer_date,
					                                                    'style' =>'width:90px',
																	))."
					</div>
				</div>	
				<div class='rTableRow'>
					<div class='rTableCell' style='width:110px'>Transfer to ".$pick_owner."</div>
					<div class='rTableCell' style='width:290px'>".$new_owner."</div>
				</div>		
            <div class='rTableRow'>
				<div class='rTableCell' style='width:110px'>Title</div>
				<div class='rTableCell' style='width:290px'>".elgg_view('input/text', array(
																	'name' => 'jot[title]',
						                                            'value' => $entity->title,
																))."
				</div>
			</div>
		</div>
	</div>";
	$form .= "
	<div class='rTable' style='width:400px'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableHead' style='width:5%'>Qty</div>
					<div class='rTableHead' style='width:70%'>Item</div>
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
					</div>";
		
	$form .= 
	'<div class="elgg-foot">'.
		elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save'))).
		elgg_view('input/submit', array('name' => 'jot[do]', 'value' => elgg_echo('jot:save:define'))).'
	</div>';	
}
echo $form;

//echo $display;