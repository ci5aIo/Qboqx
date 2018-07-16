<?php
$guid      = elgg_extract('guid', $vars);
$qid       = elgg_extract('qid', $vars);
$space     = elgg_extract('space',$vars);
$aspect    = elgg_extract('aspect',$vars);
$perspective=elgg_extract('perspective', $vars);
$presentation = elgg_extract('presentation', $vars);
$context   = elgg_extract('context', $vars);
$title     = elgg_extract('title', $vars, $vars['entity']->title);
$content   = elgg_extract('content', $vars);
$show_save = elgg_extract('show_save', $vars, true);
$show_full = elgg_extract('show_full_view', $vars, true);
$disable_save = elgg_extract('disable_save', $vars, false);
$show_receive = elgg_extract('show_receive', $vars, false);                                  $display .='14 $vars[show_receive] = '.$vars['show_receive'].'<br>';                                
$position  = elgg_extract('position', $vars, false);
$close_icon= elgg_view_icon('window-close',['title'=>'Close']);
$minimize_icon = elgg_view_icon('window-minimize',['title'=>'Minimize']);
$receive_icon  = elgg_view_icon('sign-in',['title'=>'Receive']);
//$close_icon= elgg_view_icon('delete',['title'=>'Close']);
$let_edit = elgg_extract('let_edit', $vars, false);
$let_view = elgg_extract('let_view', $vars, false);
$element   = elgg_extract('element', $vars, 'qbox');
$message   = elgg_extract('message', $vars, false);
if(elgg_extract('show_title', $vars, true)==true){
	$qbox_title = "<div id='qboxTitle'>$title</div>";
}
if ($context == 'market'){$domain = $context;} // Used in the $full_view_link
else                     {$domain = 'jot';}
switch($aspect){
	case 'donate':
		$save_icon_disabled_title = 'Please select a recipient and an action to save';
		break;
	case 'receipt':
		unset($save_icon_disabled_title);
}
if ($disable_save){
	$disabled = 'disabled style=\'cursor:unset;\'';
	$save_icon = elgg_view_icon('save',['title'=>$save_icon_disabled_title]);
}
else {
	$save_icon = elgg_view_icon('save',['title'=>'Save', 'name'=>'apply']);
}
/*$save_button = $show_save ? "<a class='do' type='submit' id='qboxSave' data-qid=$qid value='Save' $disabled data-perspective='save'>
							     $save_icon
						     </a>":
                           null;*/
$save_button = $show_save ? "<button  class='do' type='submit' id='qboxSave' data-qid=$qid value='Save' $disabled data-perspective='save'>
							     $save_icon
						     </button>":
                           null;
$close_button = "<button type='button' id='qboxClose' data-qid=$qid data-perspective='$perspective'>
					$close_icon
				</button>";
$minimize_button = "<button type='button' id='qboxMinimize' data-qid=$qid data-perspective='$perspective'>
					$minimize_icon
				</button>";
switch ($space){case 'market':  $plugin = 'market';break;
				case 'transfer':$plugin = 'jot';break;}
$full_view_link = elgg_view('output/url',['text'=>elgg_view_icon('external-link',['title'=>'full view']),
		                                  'href'=>"$plugin/$perspective/$guid"]);
if ($position){$pos_style = "style='position:$position'";}
if ($let_edit){
	$qid_n = $qid;
	if ($presentation == 'popup'){$qid_n = 'q'.$guid.'_e';}
	$edit_button = elgg_view('output/div',['content'=>elgg_view('output/url',['text'=>elgg_view_icon('edit',['title'=>'Edit','class'=>'far']), 
								                                  		      'class'=>'do',
			                                                                  'data-element'=>$element,
			                                                                  'data-space'=>$space,
			                                                                  'data-aspect'=>$aspect,
			                                                                  'data-guid'=> $guid,
			                                                                  'data-qid' => $qid_n,
//			                                                                  'data-jq-dropdown' => '#'.$qid_n,
			                                                                  'data-context'=>$context,
			                                                                  'data-presentation'=>$presentation,
			                                                                  'data-perspective'=>'edit']),
			                               'options'=>['id'=>'qboxEdit']]);
}
if ($let_view){
	$qid_n = $qid;
	if ($presentation == 'popup'){$qid_n = 'q'.$guid.'_v';}
	$edit_button = elgg_view('output/div',['content'=>elgg_view('output/url',['text'=>elgg_view_icon('file',['title'=>'View','class'=>'far']), 
								                                  		      'class'=>'do',
			                                                                  'data-element'=>$element,
			                                                                  'data-space'=>$space,
			                                                                  'data-aspect'=>$aspect,
			                                                                  'data-guid'=>$guid,
			                                                                  'data-qid'=>$qid_n,
//			                                                                  'data-jq-dropdown' => '#'.$qid_n,
			                                                                  'data-perspective'=>'view']),
			                               'options'=>['id'=>'qboxView']]);
}
if ($show_full){
	$full_button = elgg_view('output/div', ['content'=>$full_view_link,
			                                'options'=>['id'=>'fullView']]);}
if ($show_receive){
	$receive_button = elgg_view('output/div',['content'=>elgg_view('output/url',['text'=>$receive_icon, 
									                                  		      'class'=>'do',
				                                                                  'data-element'=>'phase',
				                                                                  'data-space'=>$space,
				                                                                  'data-aspect'=>'receive',
				                                                                  'data-guid'=>$guid,
				                                                                  'data-qid'=>$qid_n,
	//			                                                                  'data-jq-dropdown' => '#'.$qid_n,
				                                                                  'data-context'=>$context,
				                                                                  'data-presentation'=>$presentation,
				                                                                  'data-perspective'=>'edit']),
			                               'options'=>['id'=>'qboxReceive']]);}
if ($message){
	$msg = elgg_view('output/div', ['content'=>$message,
			                        'class'  =>'message-stamp']);
}
$form_body = "<div id=$qid class='qbox qbox-visible ui-moveable' $pos_style role='data entry' tabindex='-1' data-space='$space' data-aspect='$aspect' data-perspective='$perspective' data-context = '$context'>
				<div id='qboxWrapper'>
					<div>
						<div id='qboxContent'>
						$qbox_title
						$msg
							<div id='qboxLoadedContent'>
								<div>
									<div class='elgg-body qbox-body'>
										<div class='elgg-layout elgg-layout-default clearfix'>
                                              $content
                                              </div>
                                          </div>
								</div>
							</div>
                            $receive_button
                            $full_button
                            $edit_button
                            $minimize_button
                            $close_button
							$save_button
						</div>
					</div>
				</div>
			</div>";
echo $form_body;
//echo register_error($display);