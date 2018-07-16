<?php
access_show_hidden_entities(true);

$guids       = elgg_extract('guid', $vars, false);
$qid         = elgg_extract('qid', $vars);
$div_class   = elgg_extract('div_class', $vars);
$panel       = elgg_extract('panel', $vars);
$asset_guid  = elgg_extract('asset', $vars);
$transfer    = elgg_extract('transfer', $vars);
$perspective = elgg_extract('perspective', $vars);
$panel_display = elgg_extract('panel_display', $vars);
$aspect      = $panel['aspect'];
$hidden_line_item_fields["jot[transfer][aspect]"] = $aspect;
$asset       = get_entity($asset_guid);
$tense       = 'present';
if ($perspective == 'edit'){$tense = 'past';}
if ($vars['selected'] == $aspect){
    		$style = "display:block;";
    	}
Switch ($aspect){
	case 'donate':
    	$fields['tax_deductible']       =['type'=>'checkbox', 
    	                                  'label'=>'This donation is tax deductible']; 
    	$fields['value']                =['type'=>'text',     
    			                          'label'=>'value',
    			                          'class'=>'form-control currency'];
    	$connected_value = 'yes';
    	$delivery_value = 'direct';
    	if ($tense == 'past'){
    		$connected_value = $transfer->stay_connected;
    		$delivery_value  = $transfer->delivery;
    	} 
    	$fields['stay_connected']       =['type'=>'radio',
    			                          'label'=>'Stay connected',
    			                          'name'=>'jot[transfer][donate][stay_connected]',
    			                          'value'=>$connected_value,
    			                          'qid'=>$qid,
    			                          'options'=>['Yes:  Keep me informed'  => 'yes',
    			                                      'No:  Make a clean break' => 'no']];
    	$fields['delivery']             =['type'=>'radio',
    			                          'label'=>'Delivery',
    			                          'name'=>'jot[transfer][donate][delivery]',
    			                          'value'=>$delivery_value,
    			                          'qid'=>$qid,
    			                          'class'=>'donate-ship',
    			                          'options'=>['Pick up/Drop off'=> 'direct',
    			                                      'Send'            => 'ship']];
    	$fields['recipient_street_1']   =['type'=>'text',     'label'=>'Street',  'name'=>'jot[transfer][donate][recipient_street_1]',   'value'=>$transfer->recipient_street_1,    'class'=>'donate-delivery-address'];
    	$fields['recipient_street_2']   =['type'=>'text',     'label'=>'Apt #',   'name'=>'jot[transfer][donate][recipient_street_2]',   'value'=>$transfer->recipient_street_2,    'class'=>'donate-delivery-address'];
    	$fields['recipient_city']       =['type'=>'text',     'label'=>'City',    'name'=>'jot[transfer][donate][recipient_city]',       'value'=>$transfer->recipient_city,        'class'=>'donate-delivery-address'];
    	$fields['recipient_state']      =['type'=>'text',     'label'=>'State',   'name'=>'jot[transfer][donate][recipient_state]',      'value'=>$transfer->recipient_state,       'class'=>'donate-delivery-address'];
    	$fields['recipient_postal_code']=['type'=>'text',     'label'=>'Zip Code','name'=>'jot[transfer][donate][recipient_postal_code]','value'=>$transfer->recipient_postal_code, 'class'=>'donate-delivery-address'];
    	$fields['recipient_phone']      =['type'=>'text',     'label'=>'Phone',   'name'=>'jot[transfer][donate][recipient_phone]',      'value'=>$transfer->recipient_phone,       'class'=>'donate-delivery-address'];
    	$fields['recipient_email']      =['type'=>'text',     'label'=>'Email',   'name'=>'jot[transfer][donate][recipient_email]',      'value'=>$transfer->recipient_email,       'class'=>'donate-delivery-address'];
    	
    	if (!empty($transfer)){
    		$items = elgg_get_entities(['type'           => 'object',
    				                    'subtype'        => 'line_item',
    				                    'container_guid' => $transfer->getGUID()]);
    	}
    	else {
	    	if (!is_array($guids)){$guids=array($guids);}
	    	$items = elgg_get_entities(['guids'=>$guids]);
    	}
    	if (isset($items)){
    		unset($rows, $donated_items, $donations);
    		$header .= elgg_view('output/div',['class'=>'rTableHead qbox-donate']);
    		$header .= elgg_view('output/div',['content'=>'Donations', 'class'=>'rTableHead qbox-donate']);
    		$tax_deductible_label = elgg_view('output/span',['content'=>'tax', 'title'=>'Tax Deductible?']);
    		$header .= elgg_view('output/div',['content'=>$tax_deductible_label, 'class'=>'rTableHead qbox-donate']);
    		$header .= elgg_view('output/div',['content'=>'Value', 'class'=>'rTableHead qbox-donate']);
    		foreach ($items as $key=>$line_item){
    			unset($item_guid, $selector, $taxable, $cells, $sort_order, $hidden_line_item_fields);
    			$sort_order = $key+1;
    			switch ($line_item->getSubtype()){
    				case 'market':
    				case 'item':
    					$item_guid = $line_item->getGUID();
    					break;
    				default:
		    			$guid       = $line_item->getGUID();
		    			$item_guid  = $line_item->asset;
		    			$hidden_line_item_fields["jot[transfer][$aspect][$key][guid]"] = $guid;
    					break;
		    	}
    			$selector = elgg_view('output/span',['content'=>elgg_view('input/checkbox',['name'=>"jot[transfer][$aspect][$key][selected]", 'checked'=>'checked', 'default'=>false]),
    				                                 'options'=>['title'=>'Donate this item']]);
    			$item_name = $line_item->title;
    			$tax_deductible = elgg_view('output/span',['content'=>elgg_view('input/checkbox',['name'=>"jot[transfer][$aspect][$key][tax_deductible]", 'default'=>false]),
    				                                       'options'=>['title'=>'This donation is tax deductible']]);
    			$value_attributes = array(
					'value' => '',
					'disabled' => false,
					'type' => 'text',
		    		'name'=>"jot[transfer][$aspect][$key][value]",
				    'placeholder'=>$fields['value']['label'],
		    		'min'  =>0,
		    		'class'=> $fields['value']['class']);
		    	$value_input = elgg_format_element('input', $value_attributes); 
    			$hidden_line_item_fields["jot[transfer][$aspect][$key][asset]"]       = $item_guid;
                $hidden_line_item_fields["jot[transfer][$aspect][$key][sort_order]"]  = $sort_order;
	            
                $cells .= elgg_view('output/div',['content'=>$selector, 'class'=>'rTableCell qbox-donate']);
                $cells .= elgg_view('output/div',['content'=>$item_name, 'class'=>'rTableCell qbox-donate']);
                $cells .= elgg_view('output/div',['content'=>$tax_deductible, 'class'=>'rTableCell qbox-donate']);
                $cells .= elgg_view('output/div',['content'=>"<span class='input-group-addon'>$</span>".$value_input, 'class'=>'rTableCell']);
	    		foreach($hidden_line_item_fields as $name => $value){
                  $cells .= elgg_view('input/hidden', array('name' => $name, 'value' => $value));
	    		}
                $rows .= elgg_view('output/div',['content'=>$cells, 'class'=>'rTableRow']);
    		}
    		$donations_heading = elgg_view('output/div',['content'=>$header, 'class'=>'rTableHeading']);
    		$donations_items = elgg_view('output/div',['content'=>$rows, 'class'=>'rTableBody']);
    		$donations     = elgg_view('output/div',['content'=>$donations_heading.$donations_items,'class'=>'rTable qbox-donate']);
    	}
    	
    	$tax_deductible_label = $fields['tax_deductible']['label'];
    	$tax_deductible_input = elgg_view('input/'.$fields['tax_deductible']['type'],
    			                         ['name'=>$fields['tax_deductible']['name'],
    			                          'class'=>$fields['tax_deductible']['class']]);
    	$value_attributes = array(
			'value' => '',
			'disabled' => false,
			'type' => 'text',
    		'name'=>$fields['value']['name'],
		    'placeholder'=>$fields['value']['label'],
    		'min'  =>0,
    		'class'=> 'form-control currency');
    	$value_input = elgg_format_element('input', $value_attributes);
    	$stay_connected_label = $fields['stay_connected']['label'];
    	$stay_connected_input = elgg_view('input/radio',
    			                          ['name'=>$fields['stay_connected']['name'],
    			                           'value'=>$fields['stay_connected']['value'],
    			                           'label_class'=>'donation-label',
    			                           'required'=>'',
    			                           'options'=>$fields['stay_connected']['options']]);
    
    	$delivery_label = $fields['delivery']['label'];
    	$delivery_input = elgg_view('input/radio',
    			                     ['name'=>$fields['delivery']['name'],
    			                      'value'=>$fields['delivery']['value'],
    			                      'label_class'=>'donation-label',
    			                      'options'=>$fields['delivery']['options']]);
    	$delivery_street_1_label = "<label class='delivery-label' for ='".$fields['recipient_street_1']['label']."'>".$fields['recipient_street_1']['label'].":</label>";
    	$delivery_street_1_input = elgg_view('input/text',
    			                              ['name'=>$fields['recipient_street_1']['name'],
    			                               'placeholder'=>$fields['recipient_street_1']['label'],
    			                               'style'=>'width:255px']);
    	$delivery_street_2_label = "<label class='delivery-label' for ='".$fields['recipient_street_2']['label']."'>".$fields['recipient_street_2']['label'].":</label>";
    	$delivery_street_2_input = elgg_view('input/text',
    			                              ['name'=>$fields['recipient_street_2']['name'],
    			                               'placeholder'=>$fields['recipient_street_2']['label'],
    			                               'style'=>'width:255px']);
    	$delivery_city_label = "<label class='delivery-label' for ='".$fields['recipient_city']['label']."'>".$fields['recipient_city']['label'].":</label>";
    	$delivery_city_input = elgg_view('input/text',
    			                              ['name'=>$fields['recipient_city']['name'],
    			                               'placeholder'=>$fields['recipient_city']['label'],
    			                               'style'=>'width:255px']);
    	$delivery_state_label = "<label class='delivery-label' for ='".$fields['recipient_state']['label']."'>".$fields['recipient_state']['label'].":</label>";
    	$delivery_state_input = elgg_view('input/text',
    			                              ['name'=>$fields['recipient_state']['name'],
    			                               'placeholder'=>$fields['recipient_state']['label'],
    			                               'style'=>'width:55px']);
    	$delivery_postal_code_label = "<label class='delivery-label' for ='".$fields['recipient_postal_code']['label']."'>".$fields['recipient_postal_code']['label'].":</label>";
    	$delivery_postal_code_input = elgg_view('input/text',
    			                              ['name'=>$fields['recipient_postal_code']['name'],
    			                               'placeholder'=>$fields['recipient_postal_code']['label'],
    			                               'style'=>'width:55px']);
    	$delivery_phone_label = "<label class='delivery-label' for ='".$fields['recipient_phone']['label']."'>".$fields['recipient_phone']['label'].":</label>";
    	$delivery_phone_input = elgg_view('input/text',
    			                              ['name'=>$fields['recipient_phone']['name'],
    			                               'placeholder'=>$fields['recipient_phone']['label'],
    			                               'style'=>'width:55px']);
    	
/*    	$tax_deductible_row = elgg_view('output/div', ['content'=>$tax_deductible_input.$tax_deductible_label,
    			                                       'class'=>'row']);
    	$value_row = elgg_view('output/div', ['content'=>$value_input.$value_label,
    			                                  'class'=>'row '.$fields['value']['class']]);
    	
    	$content = elgg_view('output/div', ['content'=>$tax_deductible_row.$value_row]);
*/    	
    	// Replacing above for accelerated design.  Revert once complete.
    	
    	$content = "<div>
                        <div class='inset' style='display:block;padding:3px;'>
                        	$donations
    					</div>
<!--                        <div class='row'>
    						$tax_deductible_input $tax_deductible_label
    						<div class='donate-value'>
    							<span class='input-group-addon'>$</span>
    							$value_input
    						</div>
    					</div>
-->    					<div class='row'>
    						$stay_connected_label
    					</div>
                        <div>
    						$stay_connected_input
    					</div>
    					<div class='row'>
    						$delivery_label
    					</div>
                        <div>
    						$delivery_input
    					</div>
                        <div class='inset delivery-address' style='white-space:nowrap;width:350px;'>
                        	<em>Delivery Address</em><br>
                        	<p>$delivery_street_1_label$delivery_street_1_input</p>
                        	<p>$delivery_street_2_label$delivery_street_2_input</p>
                        	<p>$delivery_city_label$delivery_city_input</p>
                        	<p>$delivery_state_label$delivery_state_input</p>
                        	<p>$delivery_postal_code_label$delivery_postal_code_input</p>
                        	<p>$delivery_phone_label$delivery_phone_input</p>
                        </div>
    				</div>";
    	
    	$form_body = elgg_view('output/div',['content'=> $content,
    			                             'class'   =>$div_class,
    	                                      'options'=> ['id'=>$qid,
    			                                           'style'=>$style]]);
    	break;
	case 'loan':
		$content = $aspect;
    	$form_body = elgg_view('output/div',['content'=> $content,
    			                             'class'   =>$div_class,
    	                                      'options'=> ['id'=>$qid,
    			                                           'style'=>$style]]);
		break;
	case 'sell':
		$content = $aspect;
    	$form_body = elgg_view('output/div',['content'=> $content,
    			                             'class'   =>$div_class,
    	                                      'options'=> ['id'=>$qid,
    			                                           'style'=>$style]]);
		break;
	case 'trash':
		unset($option_3, $value_3);
		// populate defaults
		$asset_label  = $asset->title.' ...';
		$options[1] = 'Recover - pull out of trash heap'; $values[1] = 'recover';
		$options[2] = 'Trash - move to trash heap';       $values[2] = 'trash';
		$options[3] = 'Dump - remove for good';           $values[3] = 'remove';
		$disposition_value = $values[2];
		
		if ($tense == 'past'){
			$disposition_value = $transfer->disposition;
			switch ($disposition_value){
				case 'trash':
					$options[2] = 'Moved to trash heap';
					break;
				case 'recover':
					$options[1] = 'Recovered from trash heap';
					break;
			}
		}
		$options_input = [$options[1] =>$values[1],
    			          $options[2] =>$values[2],
    			          $options[3] =>$values[3]];
		if ($perspective == 'add'){unset($options_input[$options[1]]);}
		$remove_input = elgg_view('input/radio', ['name'=>'jot[transfer][trash][disposition]', 'value'=>$disposition_value, 'options'=>$options_input]);
		$content = "<div>
                       <div class='row'>
    						$asset_label
    					</div>
                        <div>
    						$remove_input
    					</div>
    				</div>";
    	$form_body = elgg_view('output/div',['content'=> $content,
    			                             'class'   =>$div_class,
    	                                      'options'=> ['id'=>$qid, 'style'=>$style]]);
		break;
}
echo $form_body;

access_show_hidden_entities(false);
