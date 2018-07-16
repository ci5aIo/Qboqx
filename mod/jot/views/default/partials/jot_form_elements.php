<?php
$element      = elgg_extract('element', $vars);
$guid         = elgg_extract('guid', $vars);
$cid          = elgg_extract('cid', $vars);
$qid          = elgg_extract('qid', $vars);
$qid_n        = elgg_extract('qid_n', $vars);
$space        = elgg_extract('space', $vars);
$aspect       = elgg_extract('aspect', $vars);
$perspective  = elgg_extract('perspective', $vars);
$presentation = elgg_extract('presentation', $vars, 'qbox');
$context      = elgg_extract('context', $vars);
$section      = elgg_extract('section', $vars, false);
$compartment  = elgg_extract('compartment', $vars, 'Profile');
$form_class   = elgg_extract('form_class', $vars, 'inline-container');
if (elgg_entity_exists($guid)){
	$entity  = get_entity($guid);
	$subtype = $entity->getSubtype();
}
Switch ($element){
	case 'new_item_details':
		$item_metadata = str_replace('jot[', 
		                             'jot[snapshot][', 
		                              str_replace('item[', 
		                                          "line_item[$qid_n][", 
		                                          elgg_view('forms/market/edit/family', $vars)));
		$form_body .= elgg_view('output/div',['content'=>$item_metadata,
				                              'class'  => $element,
				                              'options'=> ['id'=>"{$qid_n}_$element"]]);
		break;
	case 'properties':
			    $form_body .="<div id=$qid_n class='jq-dropdown jq-dropdown-tip jq-dropdown-relative'>
	                           <div class='jq-dropdown-panel'>".
                                   elgg_view('forms/market/properties', [
	    	                                   'element_type'   =>'receipt_item',
	    	                                   'container_type' => 'transfer',
		                                       'qid'            => $qid,
                                          	   'qid_n'          => $qid_n,
		                                       'line_item_behavior_list_class'  => $qid_n."_line_item_behavior_list",
                                               'line_item_behavior_list_data'   =>['qid'=>$qid_n],
		                                       'line_item_behavior_radio_class' => 'receipt-line-item-behavior',
	                        	   ])."
	                           </div>
                           </div>";
		break;
	case 'new_receipt_item':
		$delete = elgg_view('output/url', ['title'=>'remove receipt item',
									       'class'=>'remove-node',
										   'data-qid' => $qid_n,
									       'style'=> 'cursor:pointer',
									       'text' => elgg_view_icon('delete-alt'),]);
		
		$set_properties_button = elgg_view('output/url', ['data-jq-dropdown'    => '#'.$qid_n,
										                  'data-qid'            => $qid_n,
										                  'data-horizontal-offset'=>"-15",
								                          'text'                => elgg_view_icon('settings-alt'), 
								                          'title'               => 'set properties',
		]);
		

		$form_body .="<div class='rTableRow receipt_item ui-sortable-handle' data-qid='$qid_n'>
						<div class='rTableCell'>$delete</div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>".elgg_view('input/hidden', ['name' => "line_item[$qid_n][new_line]"]).
						                          elgg_view('input/number', ['name' => "line_item[$qid_n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1'])."</div>
						<div class='rTableCell'>$set_properties_button ".elgg_view('input/text', array(
													'name'      => "line_item[$qid_n][title]",
								                    'class'     => 'rTableform90',
								                    'required'  => '',
						                            'id'        => 'line_item_input',
								                    'data-name' => 'title',
												))."
			            </div>
						<div class='rTableCell'>".elgg_view('input/checkbox', array(
													'name'      => "line_item[$qid_n][taxable]",
													'value'     => 1,
								                    'data-qid'  => $qid_n,
								                    'data-name' => 'taxable',
		        			                        'default'   => false,
												))."</div>
						<div class='rTableCell'>".elgg_view('input/text', array(
													'name'      => "line_item[$qid_n][cost]",
							 						'class'     => 'last_line_item',
								                    'data-qid'  => $qid_n,
								                    'data-name' => 'cost',
	    											'class'     => 'nString',
												))."</div>
						<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid}_line_total line_total_raw'></span></div>
                     </div>";
		//$form_body .= $property_cards;
    break; //($element_type == 'new_receipt_item')
	case 'new_discovery_item':
		$delete = elgg_view('output/url', ['title'=>'remove receipt item',
									       'class'=>'remove-node',
										   'data-qid' => $qid_n,
									       'style'=> 'cursor:pointer',
									       'text' => elgg_view_icon('delete-alt'),]);
		
		$set_properties_button = elgg_view('output/url', ['data-jq-dropdown'    => '#'.$qid_n,
										                  'data-qid'            => $qid_n,
										                  'data-horizontal-offset'=>"-15",
								                          'text'                => elgg_view_icon('settings-alt'), 
								                          'title'               => 'set properties',
		]);
		
		//refer to new_receipt_item above for updated method (e.g. 'name' => "line_item[$qid_n][taxable]") to capture new line items
		$form_body .= "
                    		<div class='rTableRow ui-sortable-handle' data-qid='$qid_n'>
                    			<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>".quebx_view_icon('move')."</div>
                    			<div class='rTableCell' style='width:10%; padding: 0px 0px;vertical-align:top' title='When this happened'>".elgg_view('input/date', array(
                    											'name' => 'jot[observation][discovery][date][]',
                    									))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I did'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][action][]',
                    							        		'class'=> 'rTableform',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I observed'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][observation][]',
                    							        		'class'=> 'rTableform',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I learned'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][discovery][]',
                    							        		'class'=> 'rTableform last_discovery',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:0; padding: 0px 0px' title='remove'><a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>";
		//$form_body .= $property_cards;
    break; //($element_type == 'new_receipt_item')
	case 'q':
		switch($aspect){
			case 'content':
				$title     = 'Contents';
				$body_vars = ['title'         => $title,
						      'guid'          => $guid,
						      'container_guid'=> $guid,
						      'qid'           => $qid,
						      'selected'      => true,
						      'presentation'  =>'qbox',
						      'action'        => 'view',
						      'full_view'     => false,
						      'aspect'        => $aspect,
						      'perspective'   => $perspective];
				$form_body =  elgg_view('output/div', ['content' => elgg_view_entity($entity, $body_vars),
						                               'class'   => 'qbox-content-expand',
						                               'options' => ['id'=>$qid]]);
				$contents   = elgg_get_entities(['type'=>'object', 'subtypes'=>ELGG_ENTITIES_ANY_VALUE, 'limit' => false,]);
				foreach ($contents as $content){
					$elements[] = ['guid'           => $content->guid,
					               'container_guid' => $content->container_guid,
							       'title'          => $content->title];
				}
				$parent_id      = $guid;
				foreach ($elements as $element) {
				    $id = $element['guid'];
				    $parent_id = $element['container_guid'];
				    $data[$id] = $element;
				    $index[$parent_id][] = $id;
				}
				$options = ['data'           => $data, 
						    'index'          => $index, 
						    'parent_id'      => $guid, 
						    'ul_class'       => 'hierarchy', 
						    'collapsible'    => true,
						    'collapse_level' => 1,
						    'level'          => 0,
						    'links'          => true];
				$vars['title']        = $title;
				$vars['show_title']   = true;
	        	$vars['content']      = quebx_display_child_nodes($options);
	        	$vars['show_save']    = false;
	        	$vars['disable_save'] = true;
				$form_body =  elgg_view('output/div', ['content' => elgg_view_layout('qbox',$vars),
						                               'class'   => 'qbox-content-expand',
						                               'options' => ['id'=>$qid]]);
				
				break;
				
			case 'experience':
				$title     = 'Experience';
				$tab_vars  = ['menu'         => 'q_expand',
                              'guid'         => $guid,
        		              'class'        => "qbox-$guid",
						      'qid_parent'   => $qid,];
        		$expand_tabs =  elgg_view('jot/menu', $tab_vars);
        		unset($tab_vars);
		        $action    =  'forms/experiences/edit';
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid.'_1',
		                      'section' => 'things',
						      'selected'=> true,
						      'style'   => 'display:none;',
						      'presentation'=>'qbox_experience',
						      'action'  => 'add'];
		        $thing_panel = elgg_view($action, $body_vars);
				$tab_vars  = ['subtype'      => 'experience',
                              'this_section' => 'Expand',
                              'state'        => 'selected',
                              'action'       => 'add',
                              'guid'         => $guid,
        		              'presentation' => 'qbox',
						      'class'        => "qbox-$guid",
                              'ul_style'     => 'border-bottom: 0px solid #cccccc',
                              'ul_aspect'    => 'attachments',
						      'attachments'  => ['things'=>1]];
		        $tabs      =  elgg_view('quebx/menu', $tab_vars);
		        $asset_title= get_entity($guid)->title;
		        $action    =  'experiences/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element',];
				$body_vars = ['title'   => 'Experience - '.$asset_title,
						      'guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
		                      'section' => 'main',
						      'selected'=> true,
						      'presentation'=>$presentation,
						      'action'  => 'add', 
						      'tabs'    => $tabs,
						      'expand_tabs'  => $expand_tabs,
						      'preloaded_panels'=> $thing_panel];
/*				$content = elgg_view('output/div', ['content' => elgg_view_form($action, $form_vars, $body_vars),
						                            'class'   => 'qbox-content-expand',
						                            'options' => ['id'=>$qid]]);*/
				$content = elgg_view("forms/$action", $body_vars);
				switch ($presentation){
					case 'inline':
						$vars['action'] = $action;
						$vars['body_vars']=$body_vars;
						$vars['form_vars']=$form_vars;
						$vars['perspective']  = 'add';
						$form_body = elgg_view_layout('inline',$vars);
						break;
					default:
						$form_body = $content;
						break;
				}
		/* from 'Market' below
		        if ($context == 'widgets'){
		        	$vars['position'] = 'relative';
		        	$form_body = elgg_view_layout('inline', $vars);}
		        else {
		        	$form_body = "<div class = 'inline-content-expand' id=$qid>
						           		$content
						          </div>";
		        }*/
			break;
			case 'image':
				$title = 'Attach pictures';
				$action = 'experiences/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element',];
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
		                      'section' => 'gallery',
						      'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => 'add',
						      'title'   => $title,];
				$form_body = elgg_view('output/div', ['content' => elgg_view_form($action, $form_vars, $body_vars),
						                              'class'   => 'qbox-content-expand',
						                              'options' => ['id'=>$qid]]);
			break;
			case 'document':
				$title = 'Attach documents';
				$action = 'experiences/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element',];
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
		                      'section' => 'documents',
						      'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => 'add',
						      'title'   => $title,];
				$form_body = elgg_view('output/div', ['content' => elgg_view_form($action, $form_vars, $body_vars),
						                              'class'   => 'qbox-content-expand',
						                              'options' => ['id'=>$qid]]);
			break;
			case 'project':
				$action = 'forms/experiences/edit';
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
		                      'section' => $aspect,
						      'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => 'add'];
				$form_body = elgg_view('output/div', ['content' => elgg_view($action, $body_vars),
						                              'class'   => 'qbox-content-expand',
						                              'options' => ['id'=>$qid]]);
			break;
			case 'transfer':
				$form_action = 'transfers/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element_v2',
						      'id'      => $perspective.'_'.$aspect,];
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
						      'space'   => $space,
		                      'aspect'  => $aspect,
						      'perspective'=>$perspective,
						      //'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => $perspective,
						      'context' => $context,
						      'title'   => $title,
						      'disable_save'=>true,];
				Switch ($perspective){
					case 'add':
						$body_vars['title'] = 'Transfer';
						$content   = elgg_view_form($form_action, $form_vars, $body_vars);
						break;
					case 'list':
						$body_vars['title']='Transfers';
						$transfers = elgg_get_entities_from_relationship(
							   ['type'                 => 'object',
								'relationship'         => 'transfer_receipt',
								'relationship_guid'    => $guid,
								'inverse_relationship' => true,]);
						$body_vars['list_type_toggle'] =  true;
						$body_vars['view_type']        =  'compact';
						$body_vars['perspective']      =  'list';
						$body_vars['show_title']       =  true;
							$list_items                =  elgg_view('output/div',['content'=>elgg_view_entity_list($transfers, $body_vars)]);
						$body_vars['content']          =  $list_items;
						$body_vars['show_save']        =  false;
						$body_vars['show_full_view']   =  false;
						$content                       =  elgg_view_layout('qbox', $body_vars);
						break;
				}
				$form_body = elgg_view('output/div', ['content' => $content,
						                              'class'   => 'qbox-container',
						                              'options' => ['id'=>$qid]]);
				break;
			case 'return':
				$title = 'Return items';
				$action = 'transfers/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element_v2',];
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
		                      'aspect'  => $aspect,
						      'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => 'add',
						      'title'   => $title,
						      'disable_save'=>true,];
				$content   = elgg_view_form($action, $form_vars, $body_vars);
				$form_body = elgg_view('output/div', ['content' => $content,
						                              'class'   => 'qbox-container',
						                              'options' => ['id'=>$qid]]);
				
				break;
			case 'replace':
				$title = 'Replace with a different one';
				$action = 'transfers/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element_v2',];
				$body_vars = $vars;
				$body_vars['container_guid'] = $guid;
				$body_vars['selected']       = true;
				$body_vars['presentation']   = 'qbox';
				$body_vars['action']         = 'add';
				$body_vars['title']          = $title;
				$body_vars['disable_save']   = true;
				$body_vars['show_full_view'] = false;
				$content   = elgg_view_form($action, $form_vars, $body_vars);
				$form_body = elgg_view('output/div', ['content' => $content,
						                              'class'   => 'qbox-container',
						                              'options' => ['id'=>$qid]]);
				
				break;
			default:
				$title = 'Attach '.$aspect;
				$action = 'experiences/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element_v2',];
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
						      'space'   => $space,
		                      'aspect'  => $aspect,
						      'perspective'=>$perspective,
		                      'section' => $aspect,
						      'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => 'add',
						      'title'   => $title,];
				$content   = elgg_view_form($action, $form_vars, $body_vars);
/*				$form_body = elgg_view('output/div', ['content' => $content,
						                              'class'   => 'qbox-content-expand',
						                              'options' => ['id'=>$qid]]);
*/				$form_body = elgg_view('output/div', ['content' => $content,
						                              'class'   => 'qbox-content-expand',
						                              'options' => ['id'=>$qid]]);
			break;
		}
	break;
	case 'qbox':
		Switch ($space){
 			case 'transfer':
 				if ($space==$subtype){                                  // This is an existing transfer
					if ($perspective == 'delete'){
	 					$entity->delete();
	 					continue;
	 				}                               	 				// View or edit a transfer
					$body_vars = ['guid'          =>$guid,
							      //'container_guid'=>$guid,
							      'qid'           =>$qid,
							      'qid_n'         =>$qid_n,
							      'space'         =>$space,
			                      'aspect'        =>$aspect,
							      'asset'         =>$entity->asset,
							      'perspective'   =>$perspective,
						          'context'       =>$context,
							      'selected'      =>true,
							      'presentation'  =>'qbox',
							      'view_type'     =>'compact',
							      'disable_save'  =>$perspective == 'view',
							      'show_title'    =>true,
							      'let_edit'      =>$perspective == 'view',
							      'let_view'      =>$perspective == 'edit',];
					//$form_body  = elgg_view('output/div', ['options' => ['id'=>'form-messages']]);
					//$form_result_msg = elgg_view('output/div',['options'=>['id'=>"form-messages"]]); 
					$form_body .= elgg_view('output/div', ['content' => //$form_result_msg.
							                                            elgg_view_entity($entity, $body_vars),
							                              'class'    => 'qbox-content-expand',
							                              'options'  => ['id'=>$qid]]);
 				}
 				else {                                                 // Add new transfer
 					$view      = "transfers/edit";
 					$form_vars = ['action'  => 'action/jot/edit_v4'];
//  					$body_vars = ['element_type'   => 'item',
// 			                      'panel'         => 'receipts'];
                    $body_vars = ['title'         => "Add a $aspect",
			                      'action'        => 'add',
			                      'do'            => $perspective.'_'.$aspect,
			                      'qid'           => $qid,
							      'qid_n'         => $qid_n,
							      'space'         => $space,
			                      'aspect'        => $aspect,
							      'perspective'   => $perspective,
						          'context'       => $context,
							      'owner_guid'     => elgg_get_logged_in_user_guid(),
			                      'container_guid' => elgg_get_logged_in_user_guid(),
			                      'selected'      => true,
							      'presentation'  => 'qbox',
							      'view_type'     => 'compact',
							      'disable_save'  => false,
							      'show_title'    => true,
							      'let_edit'      => false,
							      'let_view'      => false];
 					$form_body =  elgg_view('output/div', ['content' => elgg_view_form($view, $form_vars, $body_vars),
							                               'class'   => 'qbox-content-expand',
							                               'options' => ['id'=>$qid]]);
 					//$display .= '$space:'.$space.'<br>';
 					$display .= '<hr>'.print_r($body_vars, true);
 				}
				break;
 			case 'market':
 				switch ($perspective){
 					case 'pack':
 						$action    = 'forms/jot/edit';
 						$body_vars = ['guid'         => $guid,
 								      'aspect'       => $aspect,
 								      'perspective'  => $perspective,
 								      'presentation' => 'drop_box'
 								     ];
 						$content = elgg_view($action, $body_vars);
 						
 						break;
 					case 'edit':
						$action    =  'forms/market/edit';
						$body_vars = ['guid'    => $guid];
						$content   = elgg_view($action, $body_vars);
				 		$vars['content']      = $content;
				        $vars['disable_save'] = $disable_save;
				        if ($context == 'widgets'){$vars['position'] = 'relative';}
						break;
 					case 'view':
 						$body_vars = ['entity' =>$entity,
 						              'guid'=>$guid,
								      'full_view'=>false,
								      'this_section'=>'Profile',
								      'view_type'=>'inline',
								      'perspective'=>$perspective];
						$content   = elgg_view_entity($entity, $body_vars);
				 		$vars['content']      = $content;
				 		$vars['show_save']    = false;
				        if ($context == 'widgets'){$vars['position'] = 'relative';}
						break;
 				}
				$content = elgg_view_layout('qbox',$vars);
		        $form_body = elgg_view('output/div', ['content' => $content,
						                              'class'    => 'qbox-content-expand',
						                              'options'  => ['id'=>$qid]]);
				break;
			default:
				$action    =  'forms/experiences/edit';
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
		                      'section' => $section,
						      'selected'=> true,
						      'presentation'=>'qbox_experience',
						      'action'  => 'add'];
				$form_body =  elgg_view($action, $body_vars);
				break;		
		}
	break;
	case 'qbox-maximized':
		switch ($perspective){
 			case 'edit':
				$action    =  'forms/market/edit';
				$body_vars = ['guid'    => $guid];
				$content   = elgg_view($action, $body_vars);
		 		$vars['content']      = $content;
		        $vars['disable_save'] = $disable_save;
		        if ($context == 'widgets'){$vars['position'] = 'relative';}
				break;
 			case 'view':
 				$body_vars = ['entity' =>$entity,
 						      'guid'=>$guid,
 						      'qid'=>$qid,
							  'full_view'=>false,
							  'this_section'=>'Profile',
							  'view_type'=>'inline',
							  'perspective'=>$perspective];
				$content   = elgg_view_entity($entity, $body_vars);
		 		$vars['content']      = $content;
		 		$vars['show_save']    = false;
		 		$vars['let_edit']     = true;
		        if ($context == 'widgets'){$vars['position'] = 'relative';}
				break;
 		}
		$form_body = elgg_view_layout('maximized',$vars);
		break;
	case 'Expand':
		$action    =  'forms/experiences/edit';
		$body_vars = ['guid'    => $guid,
				      'container_guid'=> $guid,
				      'qid'     => $qid,
                      'section' => 'expand',
				      'selected'=> true,
				      'presentation'=>'qbox_experience',
				      'action'  => 'add'];
		$form_body =  elgg_view($action, $body_vars);
	break;
	case 'Things':
		
	case 'Documents':
//		$action    = 'forms/experiences/edit';
		$title     = 'Attach documents';
		$action    = 'experiences/edit';
		$form_vars = ['name'    => 'jotForm',
                      'enctype' => 'multipart/form-data',
                      'action'  => 'action/jot/add/element', 
					  'class'   => $form_class];
		$body_vars = ['guid'    => $guid,
				      'container_guid'=> $guid,
				      'qid'     => $qid,
                      'section' => 'documents',
				      'selected'=> true,
				      'presentation'=>'inline',
				      'action'  => 'add',
				      'title'   => $title,];
		$content = elgg_view('output/div', ['content' => elgg_view_form($action, $form_vars, $body_vars),
				                            'class'   => 'inline-content-expand',
				                            'options' => ['id'=>$qid]]);
        $form_body = $content;
		
		break;
	case 'Gallery':
		$title     = 'Attach pictures';
		$action    = 'experiences/edit';
		//$action    = 'forms/experiences/edit';
		$form_vars = ['name'    => 'jotForm',
                      'enctype' => 'multipart/form-data',
                      'action'  => 'action/jot/add/element', 
					  'class'   => $form_class];
		$body_vars = ['guid'    => $guid,
				      'container_guid'=> $guid,
				      'qid'     => $qid,
                      'section' => 'gallery',
				      'selected'=> true,
				      'presentation'=>'inline',
				      'action'  => 'add',
				      'title'   => $title,];
//		$form_body =  elgg_view($action, $body_vars);
		$content = elgg_view('output/div', ['content' => elgg_view_form($action, $form_vars, $body_vars),
				                            'class'   => 'inline-content-expand',
				                            'options' => ['id'=>$qid]]);
        $form_body = $content;
		
	break;
	case 'transfer':
		$action    =  'forms/transfers/edit';
		$body_vars = ['guid'    => $guid,
				      'container_guid'=> $guid,
				      'qid'     => $qid,
                      'aspect' => $aspect,
				      'selected'=> true,
				      'presentation'=>'qbox_transfer',
				      'action'  => 'add'];
		
		$form_body = elgg_view($action, $body_vars);
		break;
	case 'market':
		switch ($perspective){
 			case 'edit':
//				$action    =  'forms/market/profile';
//				$body_vars = ['guid'    => $guid];
 				if ($context == 'market'){
 					$presentation = 'inline'; // otherwise be what is passed in
 				}
				$form_version = 'market/profile';
				$form_vars = ['name' => 'marketForm', 
						      'enctype' => 'multipart/form-data', 
						      'action' => 'action/market/edit', 
						      'class'=>$form_class];
				$body_vars = ['guid'        => $guid,
						      'qid'         => $qid,
						      'qid_n'       => $qid_n,
						      'space'       => $space,
						      'aspect'      => $aspect,
						      'perspective' => $perspective,
						      'context'     => $context,
						      'presentation'=> $presentation];
				$content = elgg_view_form($form_version, $form_vars, $body_vars);				  
//				$content   = elgg_view($action, $body_vars);
		 		if ($context == 'widgets'){
		        	$vars['content']        = $content;
			 		$vars['show_save']      = false;                   //Upper Save icon lands outside of the form and won't work.
			 		$vars['show_full_view'] = false;
			        $vars['position']       = 'relative';
		        	$form_body = elgg_view_layout('inline', $vars);}
		        else {
		        	$form_body = "<div class = 'qbox-container inline-content-expand' id=$qid>
						           		$content
						          </div>";
		        }
				break;
 			case 'view':
 					unset($compartments);
				    $compartments[] = 'Profile';
				    $compartments[] = 'Summary';
				    $compartments[] = 'Details';
				    $compartments[] = 'Maintenance';
				    $compartments[] = 'Inventory';
				    $compartments[] = 'Management';
				    $compartments[] = 'Accounting';
				    $compartments[] = 'Gallery';
				    $compartments[] = 'Timeline';
		 		$this_compartment   = $compartments[0]; 
 				$body_vars            = ['entity'      =>$entity,
			 						     'guid'        =>$guid,
			 						     'qid'         =>$qid,
 						                 'qid_n'       => $vars['qid_n'],
										 'full_view'   => false,
 						                 'preload_compartments'=>true,
										 'compartment' => $this_compartment,
 						                 'compartments'=> $compartments,
										 'view_type'   => 'inline',
 						                 'element'     => $element,
										 'perspective' => $perspective,
 				                         'presentation'=>$presentation];
				$content              = elgg_view_entity($entity, $body_vars);
		 		$vars['content']      = $content;
		 		$vars['show_save']    = false;
		 		$vars['let_edit']     = true;
		 		$vars['show_title']   = false;
		 		$vars['show_full_view']=false;
				$data                 = ['guid'=>$guid,'qid'=>$qid,'element'=>'compartment','space'=>$space,'perspective'=>$perspective,'compartment'=>$compartment, 'context'=>'inline', 'presentation'=>$presentation];
				$vars['tabs']         = elgg_view('items/menu', ['guid' =>$guid, 'this_section' => $this_compartment,'link_class'=>'do quebx-htabs-compact', 'data'=>$data, 'compartments'=>$sections]);
		        if ($context == 'widgets'){
		        	$vars['position'] = 'relative';}
				$form_body = elgg_view_layout('inline', $vars);
				break;
 		}
		break;
	case 'inline':
		switch ($perspective){
 			case 'edit':
				$action    =  'forms/market/edit';
				$body_vars = ['guid'    => $guid];
				$content   = elgg_view($action, $body_vars);
		 		$vars['content']      = $content;
		 		$vars['show_save']    = false;                   //Upper Save icon lands outside of the form and won't work.
//		 		$vars['disable_save'] = $disable_save;
		        if ($context == 'widgets'){$vars['position'] = 'relative';}
				break;
 			case 'view':
 				$this_section         = $section ?: 'Profile'; 
 				$body_vars            = ['entity' =>$entity,
			 						     'guid'=>$guid,
			 						     'qid'=>$qid,
 						                 'qid_n'=>$vars['qid_n'],
										 'full_view'=>false,
										 'compartment'=>$compartment,
										 'view_type'=>'inline',							  
										 'perspective'=>$perspective,
 				                         'presentation'=>$presentation];
				$content              = elgg_view_entity($entity, $body_vars);
		 		$vars['content']      = $content;
		 		$vars['show_save']    = false;
		 		$vars['let_edit']     = true;
		 		$vars['show_title']   = true;
				$data                 = ['guid'=>$guid,'qid'=>$qid,'element'=>'compartment','space'=>$space,'perspective'=>$perspective,'compartment'=>$compartment, 'context'=>'inline', 'presentation'=>$presentation];
				$vars['tabs']         = elgg_view('items/menu', ['guid' =>$guid, 'this_section' => $this_section,'link_class'=>'do quebx-htabs-compact', 'data'=>$data]);
		        if ($context == 'widgets'){
		        	$vars['position'] = 'relative';}
				break;
 		}
		$form_body = elgg_view_layout('inline', $vars);
		break;
	case 'popup':
		switch ($perspective){
			case 'edit':
				$max_width = '500px';
			case 'view':
				$max_width = $max_width ?: '500px';
				Switch ($space){
		 			case 'transfer':
		 					$body_vars = ['guid'          =>$guid,
									      //'container_guid'=>$guid,
									      'qid'           =>$qid,
									      'qid_n'         =>$qid_n,
									      'space'         =>$space,
					                      'aspect'        =>$aspect,
									      'asset'         =>$entity->asset,
									      'perspective'   =>$perspective,
									      'element'       =>$element,
									      'presentation'  =>'popup',
								          'context'       =>$context,
									      'selected'      =>true,
									      'view_type'     =>'compact',
									      'disable_save'  =>$perspective == 'view',
									      'show_title'    =>true,
									      'let_edit'      =>$perspective == 'view',
									      'let_view'      =>$perspective == 'edit',];
							//$form_body  = elgg_view('output/div', ['options' => ['id'=>'form-messages']]);
							$form_body .= elgg_view('output/div', ['content' => elgg_view('output/div',['content'=> elgg_view_entity($entity, $body_vars),
									                                                                    'class'  =>'jq-dropdown-panel',
									                                                                    'options'=>['style'=>"overflow:auto;max-width:$max_width;"]]),
									                              'class'    => 'jq-dropdown jq-dropdown-tip',
									                              'options'  => ['id'=>$qid]]);
						break;
				}
				break;
		}
		break;
	default:
	break;
}


echo $form_body;
//register_error($display);