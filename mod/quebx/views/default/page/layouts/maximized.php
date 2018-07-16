<?php
$guid      = elgg_extract('guid', $vars);
$qid       = elgg_extract('qid', $vars);
$space     = elgg_extract('space',$vars);
$aspect    = elgg_extract('aspect',$vars);
$perspective=elgg_extract('perspective', $vars);
$context   = elgg_extract('context', $vars);
$title     = elgg_extract('title', $vars, $vars['entity']->title);
$content   = elgg_extract('content', $vars);
$show_save = elgg_extract('show_save', $vars, true);
$show_full = elgg_extract('show_full_view', $vars, true);
$disable_save = elgg_extract('disable_save', $vars, false);
$position  = elgg_extract('position', $vars, false);
$close_icon= elgg_view_icon('window-close',['title'=>'Close']);
$minimize_icon = elgg_view_icon('window-minimize',['title'=>'Minimize']);
//$close_icon= elgg_view_icon('delete',['title'=>'Close']);
$let_edit = elgg_extract('let_edit', $vars, false);
$let_view = elgg_extract('let_view', $vars, false);
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
/*$save_button = $show_save ? "<a class='do' id='qboxSave' data-qid=$qid value='Save' $disabled data-perspective='save'>
							     $save_icon
						     </a>":
                           null;*/
$save_button = $show_save ? "<button  class='do' type='submit' id='qboxSave' data-qid=$qid value='Save' $disabled data-perspective='save'>
							     $save_icon
						     </button>":
                           null;
$close_button = "<button type='button' id='maximizedClose' data-qid=$qid data-perspective='$perspective'>
					$close_icon
				</button>";
$minimize_button = "<button type='button' id='maximizedMinimize' data-qid=$qid data-perspective='$perspective'>
					$minimize_icon
				</button>";
/*$full_view_link = elgg_view('output/url',['text'=>elgg_view_icon('external-link',['title'=>'full view']),
		                                  'href'=>"$domain/$perspective/$guid"]);
*/$inline_view_link = elgg_view('output/url',['text'=>elgg_view_icon('external-link',['title'=>'maximize view']),
		                                  'class'=>'do',
                                          'data-element'=>'market',
                                          'data-space'=>$space,
                                          'data-aspect'=>$aspect,
		                                  'data-context'=>'market',
                                          'data-guid'=>$guid,
                                          'data-qid'=>$qid,
                                          'data-perspective'=>'view']);
if ($position){$pos_style = "style='position:$position'";}
if ($let_edit){
	$edit_button = elgg_view('output/div',['content'=>elgg_view('output/url',['text'=>elgg_view_icon('edit',['title'=>'Edit','class'=>'far']), 
								                                  		      'class'=>'do',
			                                                                  'data-element'=>'qbox-maximized',
			                                                                  'data-space'=>$space,
			                                                                  'data-aspect'=>$aspect,
			                                                                  'data-guid'=>$guid,
			                                                                  'data-qid'=>$qid,
			                                                                  'data-perspective'=>'edit']),
			                               'options'=>['id'=>'inlineEdit']]);
}
if ($let_view){
	$edit_button = elgg_view('output/div',['content'=>elgg_view('output/url',['text'=>elgg_view_icon('file',['title'=>'View','class'=>'far']), 
								                                  		      'class'=>'do',
			                                                                  'data-element'=>'qbox-maximized',
			                                                                  'data-space'=>$space,
			                                                                  'data-aspect'=>$aspect,
			                                                                  'data-guid'=>$guid,
			                                                                  'data-qid'=>$qid,
			                                                                  'data-perspective'=>'view']),
			                               'options'=>['id'=>'inlineView']]);
}
if ($show_full){
	$inline_button = elgg_view('output/div', ['content'=>$inline_view_link,
			                                'options'=>['id'=>'inlineView']]);}
$form_body = "<div id=$qid class='maximized-content-expand jq-dropdown'>
				<div class='jq-dropdown-panel maximized maximized-visible' $pos_style role='data entry' tabindex='-1' data-space='$space' data-perspective='$perspective' data-context = '$context'>
						<div id='maximizedLoadedContent'>
								<div>
									<div class='elgg-body maximized-body'>
										<div class='elgg-layout elgg-layout-default clearfix'>
                                              $content
                                              </div>
                                          </div>
								</div>
							</div>
                            $inline_button
                            $edit_button
                            $close_button
							$save_button
						</div>
				</div>
			</div>";
echo $form_body ;