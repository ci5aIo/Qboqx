-- Form: jot\views\default\forms\jot\view.php
<?php
$title = elgg_extract('title', $vars, '');
$item_guid   = $vars['item_guid'];
$description = $vars['description'];
$subtype     = elgg_extract('subtype', $vars, '');
$aspect      = elgg_extract('aspect', $vars, '');
$asset       = elgg_extract('asset', $vars, '');
$element_type= elgg_extract('element_type', $vars, '');
$referrer    = elgg_extract('referrer', $vars,'');
$item        = get_entity($item_guid);
$jot         = elgg_extract('jot', $vars);
/*
echo '$item_guid:'.$item_guid.'<br>
      $jot->title: '.$jot->title.'<br>
      $element_type: '.$element_type.'<br>';
*/
$jot_link = elgg_view('output/url', array(
	      'text' => $jot['title'],
	      'href' =>  "tasks/view/{$jot->guid}"));

if ($jot->getSubtype() == 'task_top' || $jot->getSubtype() == 'task'){
echo "
	<div class='rTable' style='width:375px'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'>Task</div>
				<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'><span title='View scheduled task'>$jot_link</span></div>
		    </div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:85px;padding:0px 0px 0px 0px;'>More</div>
				<div class='rTableCell' style='width:285px;padding:0px 0px 0px 0px;'>...</div>
		    </div>
		</div>
	</div>";
}