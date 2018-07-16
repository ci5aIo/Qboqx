<?php
setlocale(LC_MONETARY, 'en_US');
$guid           = (int) elgg_extract('guid',  $vars);                   $display     .= '$guid='.$guid.'<br>';
$section        = elgg_extract('section',     $vars);                      $display  .= '$section='.$section.'<br>';
$space          = elgg_extract('space',       $vars);                   $display     .= '$space: '.$space.'<br>';
//$aspect         = elgg_extract('aspect',      $vars);                   $display     .= '<br>$aspect='.$aspect.'<br>';
$selected       = elgg_extract('selected',    $vars, false);            $display     .= '$selected: '.$selected.'<br>';
$perspective    = elgg_extract('perspective', $vars);
//$aspect         = $section;
//$aspect         = $vars['aspect'];
$container_guid = $vars['container_guid'];
$referrer       = $vars['referrer'];                                   $display       .= '$referrer='.$referrer.'<br>';
$shelf          = $vars['shelf'];
$qid            = $vars['qid'];
$subtype        = 'transfer';
$presentation   = $vars['presentation'] ?: 'full';                    $display       .= '$presentation='.$presentation.'<br>';
$exists         = true;
$entity         = get_entity($guid);                                  $display .= '18 $entity->status: '.$entity->status.'<br>';
$asset_guid     = $guid;
Switch ($perspective){
	case 'edit':
        $transfer       = get_entity($guid);
		$state          = $transfer->state;
		$aspect         = $transfer->aspect;
		$asset_guid     = $transfer->asset;
		$hidden_fields .= elgg_view('input/hidden', ['name'=>"jot[transfer][guid]",     'value'=>$guid]);
		//No break.  Continue to next statement ...
	case 'add':
        $asset          = get_entity($asset_guid);
    	$tabs[]=['title'=>'Give',  'aspect'=>'donate', 'section'=>'give', 'note'=>'Give to someone', 'class'=>'qbox-q2', 'guid'=>$guid, 'selected'=>$selected == 'donate'];
        $tabs[]=['title'=>'Loan',  'aspect'=>'loan',   'section'=>'loan', 'note'=>'Loan to someone', 'class'=>'qbox-q2', 'guid'=>$guid, 'selected'=>$selected == 'loan'];
        $tabs[]=['title'=>'Sell',  'aspect'=>'sell',   'section'=>'sell', 'note'=>'Sell to someone', 'class'=>'qbox-q2', 'guid'=>$guid, 'selected'=>$selected == 'sell'];
        $tabs[]=['title'=>'Trash', 'aspect'=>'trash',  'section'=>'trash','note'=>'Discard',         'class'=>'qbox-q2', 'guid'=>$guid, 'selected'=>$selected == 'trash'];
        
        if (!empty($selected)){  //Selected can be either an aspect or a boolean;
        	foreach ($tabs as $key=>$tab){
        		if ($tab['aspect'] === $selected){continue;}
        		unset ($tabs[$key]);
       	}
        }
        
        $vars['tabs']=$tabs;
		$hidden_fields .= elgg_view('input/hidden', ['name'=>"jot[aspect]",              'value'=>$subtype]);
		$hidden_fields .= elgg_view('input/hidden', ['name'=>"jot[transfer][asset]",     'value'=>$asset_guid]);
		$hidden_fields .= elgg_view('input/hidden', ['name'=>"jot[transfer][state]",     'value'=>$state ?: 'offer']);
		$hidden_fields .= elgg_view('input/hidden', ['name'=>'jot[transfer][aspect]',    'value'=>$aspect,              'id'   =>'q-aspect']); // Value populated by tab selection
		$hidden_fields .= elgg_view('input/hidden', ['name'=>'jot[transfer][recipient]', 'value'=>$transfer->recipient, 'id'   =>'recipient']);// Value populated by recipient selection
		
		$recipient_selector = elgg_view('output/url',['data-jq-dropdown'      =>'#jq-dropdown-recipient-'.$guid,
						                         'data-horizontal-offset'=>'-7',
				                                 'class'                 =>'arrow target '
				]);
		$recipient_label = elgg_view('output/div',['content'=>'To*',
				                                    'class' => 'rTableCell']);
		$recipient_input   = elgg_view('input/text',['name'=>'ghost_recipient','id'=>'qbox_filter','class'=>'recipient_input','style'=>'width:90%', 'onkeyup'=>'filterList()', 'placeholder'=>'Recipient', 'value'=>$transfer->recipient]);
        $recipient_input_radio = elgg_view('output/div',['content'=>"<label style='padding:0px 5px'><input type='radio' name='recipient_ghost_selector' data-status='new_user' class='jq-dropdown-menu-option'>$recipient_input</label>",
        		                                         'class'=>'jq-dropdown-menu',
        		                                         'options'=>['style'=>"border-bottom-left-radius: unset;border-bottom-right-radius: unset; border-bottom:unset"]]);
// 		$recipient_options = elgg_view('output/div',['content'=> elgg_view('output/div', ['content'=>$recipient_input_radio.
// 									        		                                                   elgg_list_entities([
// 																									    'type'      => 'user',
// 																									    'list_type' => 'table',
// 									        		                                                    'no_header' => true,
// 																									    'columns'   => [
// 																									    	elgg()->table_columns->radio(null, ['name'=>'recipient_ghost_selector', 'default'=>false, 'class'=>'jq-dropdown-menu-option']),
// 																									        elgg()->table_columns->icon(null, ['size'=>'tiny']),
// 																									        elgg()->table_columns->getDisplayName(),
// 																									    ],
// 																									]),
//         		                                                                             'class'=>'jq-dropdown-menu',]),
//         		                                     'class'   => 'jq-dropdown jq-dropdown-tip jq-dropdown-scroll jq-dropdown-relative jq-dropdown-anchor-right',
//         		                                     'options' => ['style'=> 'display: none;', 'id'=>'jq-dropdown-recipient-'.$guid]
//         		                ]);
		
//        $users = elgg_get_entities(['type'=>'object', 'subtype'=>'market', 'limit'=>0]);
		$users = elgg_get_entities(['type'=>'user', 'limit'=>0]);
		foreach ($users as $item) {
			$item_icon     = elgg_view_entity_icon($item, "tiny");
			$item_url      = elgg_view('output/url',['text'=>$item->getDisplayName(), 'href'=>$item->getURL(), 'class'=>'recipient']);
			$list_items[$item_icon.$item_url] = $item->getGUID();
		}
		if (!empty($transfer->recipient)){$checked = 'checked';}
		$user_options = elgg_view('input/radio',['options'=>$list_items, 'name'=>'recipient_ghost_selector', 'default'=>false, 'radio_class'=>'jq-dropdown-menu-option', 'id'=>'selections', $checked=>'']);
		$recipient_options = elgg_view('output/div',['content'=> $recipient_input_radio.elgg_view('output/div', ['content'=>$user_options,
        		                                                                             'class'=>'jq-dropdown-menu',
									        		                                         'options'=>['style'=>"border-top-left-radius: unset;border-top-right-radius: unset; border-top:unset"]]),
        		                                     'class'   => 'jq-dropdown jq-dropdown-scroll  jq-dropdown-tip jq-dropdown-relative jq-dropdown-anchor-right',
        		                                     'options' => ['style'=> 'display: none;', 'id'=>'jq-dropdown-recipient-'.$guid]
        		                ]);
		$recipient_input = elgg_view('output/div',['content'=>elgg_format_element('span', ['class' => 'qbox-recipient'], $transfer->recipient).
				                                         $recipient_selector.
				                                         $recipient_options,
			                                 'class'  =>'rTableCell dropdown',
				                             'options'=>['style'=>'min-width:150px;box-shadow: inset 0 0 3px #888; white-space:nowrap;']]);
        
		$email_label    = elgg_view('output/div',['content'=>'Email',
				                                 'class'  =>'rTableCell',
				                                 'options'=>['style'=>'white-space: nowrap;']]);
		$email_input    = elgg_view('output/div',['content'=>elgg_view('input/email',['name'=>'jot[transfer][recipient_email]', 'placeholder'=>'email address for recipient']),
				                                 'class'  =>'rTableCell',
				                                 'options'=>['style'=>'padding-left:0; padding-right:0']]);
		$email         = elgg_view('output/span',['content'=>$email_label.$email_input,
				                                  'class'  =>'new_user_email']);
		$asset_label = elgg_view('output/div',['content'=>'Item',
				                               'class' => 'rTableCell']);
		$asset_name  = elgg_view('output/div',['content'=> $asset->title,
				                               'class' => 'rTableCell']);
		if ($aspect != 'trash'){
			$form_fields[] = elgg_view('output/div',['content'=>$recipient_label.$recipient_input.$email,
			  	                                     'class'  => 'rTableRow recipientRow']);}
		$moment_label    = elgg_view('output/div',['content'=>'Date',
				                                 'class'  =>'rTableCell',
				                                 'options'=>['style'=>'white-space: nowrap;']]);
		$moment_input    = elgg_view('output/div',['content'=>elgg_view('input/date',['name'=>'jot[transfer][moment]', 'value'=>$transfer->moment ?: strtotime("now")]),
				                                 'class'  =>'rTableCell',
				                                 'options'=>['style'=>'padding-left:0; padding-right:0;width:27%']]);
		$blank_cell   = elgg_view('output/div',['class'=> 'rTableCell']);
		$form_fields[] = elgg_view('output/div',['content'=>$moment_label.$moment_input.$blank_cell.$blank_cell,
		  	                                     'class'  => 'rTableRow']);
			
		$vars['element'] = 'transfer';
        $navigation = elgg_view('navigation/tabs_slide', $vars);
/*        $navigation_label = elgg_view('output/div',['content'=>'Transfer type:',
        		                                    'class'=>'rTableCell']);
        $navigation_input = elgg_view('output/div', ['content'=>$tabs,
        		                                     'class'=>'rTableCell']);
        $form_fields[] = elgg_view('output/div',['content'=>$navigation_label.$navigation_input,
					                                 'class'  => 'rTableRow']);					                                 
*/      
        foreach ($tabs as $key=>$panel){
        	$n                 = $key + 1;
        	$vars['qid']       = $qid.'_'.$n;
        	$vars['div_class'] = 'option-panel';
        	$vars['panel']     = $panel;
        	$vars['asset']     = $asset_guid;
        	$vars['transfer']  = $transfer;
        	$panels           .= elgg_view('forms/transfers/elements/transfer_aspects', $vars);
        }
        $details_div= elgg_format_element('div',['class'=>"qbox-details qbox-{$guid}"], $panels);
        foreach($form_fields as $sort_order=>$form_field){
        	$rows .= $form_field;
        }
        $header     = elgg_view('output/div',['content'=>$rows,
        		                              'class'  =>'rTable']); 
        $content   .= $hidden_fields.$header.$navigation.$details_div;
        break;
	case 'list':
		$content = $perspective;
		break;
}
echo $content;

//echo '$aspect: '.$aspect;
//echo register_error($display);