<?php
$guid      = elgg_extract('guid', $vars);
$qid       = elgg_extract('qid', $vars);
$space     = elgg_extract('space',$vars);
$aspect    = elgg_extract('aspect',$vars);
$perspective=elgg_extract('perspective', $vars);
$title     = elgg_extract('title', $vars, $vars['entity']->title);
$content   = elgg_extract('content', $vars);
$disable_save = elgg_extract('disable_save', $vars, false);
$position  = elgg_extract('position', $vars, false);
$close_icon= elgg_view_icon('window-close',['title'=>'Close']);
//$close_icon= elgg_view_icon('delete',['title'=>'Close']);
$let_edit = elgg_extract('let_edit', $vars, false);
if(elgg_extract('show_title', $vars, true)==true){
	$qbox_title = "<div id='qboxTitle'>$title</div>";
}
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
	$save_icon = elgg_view_icon('save',['title'=>'Save']);
}
if ($position){$pos_style = "style='position:$position'";}
if ($let_edit){
	$edit_button = elgg_view('output/div',['content'=>elgg_view('output/url',['text'=>elgg_view_icon('edit',['title'=>'Editxxx']), 
								                                  		      'class'=>'do',
			                                                                  'data-space'=>$space,
			                                                                  'data-aspect'=>$aspect,
			                                                                  'data-guid'=>$guid,
			                                                                  'data-qid'=>$qid,
			                                                                  'data-perspective'=>'edit']),
			                               'options'=>['id'=>'qboxEdit']]);
}
$form_body = "<div id=$qid class='qbox qbox-visible ui-moveable' $pos_style role='data entry' tabindex='-1' data-perspective='$perspective'>
				<div id='qboxWrapper'>
					<div>
						<div id='qboxContent'>
						$qbox_title
						<div id='qboxLoadedContent'>
								<div>
									<div class='elgg-body qbox-body'>
										<div class='elgg-layout elgg-layout-default clearfix'>
                                              $content
                                              </div>
                                          </div>
								</div>
							</div>
                            $edit_button
							<button type='button' id='qboxClose' data-qid=$qid data-perspective='$perspective'>
								$close_icon
							</button>
							<button type='submit' id='qboxSave' value='Save' $disabled>
								$save_icon
							</button>
						</div>
					</div>
				</div>
			</div>";
echo $form_body;