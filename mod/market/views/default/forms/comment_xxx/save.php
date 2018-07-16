<!-- form: \market\views\default\forms\comment\save.php -->
<?php
/**
 * Elgg comments add form
 * v4
 * @package Elgg
 *
 * @uses ElggEntity $vars['entity'] The entity to comment on
 * @uses bool       $vars['inline'] Show a single line version of the form?
 */
//$referrer = "market/view/".$vars['entity']->getGUID();
$entity      = $vars['entity'];
$referrer    = $entity->geturl();
$entity_guid = $entity->getGUID();
$form_vars   = array('action'=>'action/jot/add');
$body_vars   = array('entity'=>$entity);

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
});
</script>
<?php
if (isset($vars['entity']) && elgg_is_logged_in()) {
	
	$inline = elgg_extract('inline', $vars, false);
	if ($inline) {
		echo elgg_view('input/text', array('name' => 'jot_text', 'value' => 'Title'));
		echo elgg_view('input/submit', array('value' => elgg_echo('comment')));
	} else {

$jot_box .= "<label>".elgg_echo('jot:post')."</label><hr style='color:rgb(248, 238, 238)'>
	<div class='rTable' style='width:100%;padding: 0px 0px'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:5%;padding: 0px 0px'>".
									elgg_view('output/url', array(
									    'text' => '+',
										'href' => '#',
										'class' => 'elgg-button-submit-element clone-line-item-action' // unique class for jquery
										))."</div>
				<div class='rTableCell' style='width:95%;padding: 0px 0px'>";

// Populate blank lines
for ($i = $n+1; $i <= $n+1; $i++) {
	$pick = elgg_view('output/url', array(
			'text' => 'pick',
			'class' => 'elgg-button-submit-element elgg-lightbox',
			'data-colorbox-opts' => '{"width":500, "height":525}',
			'href' => "market/pick/item/" . $transfer_guid));

$jot_box .= "		 <div class='rTableRow'>
				<div class='rTableCell' style='width:95%;padding: 0px 0px;border:0px'>
					".elgg_view_form('jot/jotbox_row', $form_vars, $body_vars)."
				</div>
			</div>";
}
$jot_box .= '</div></div>';
// Populate form footer
$jot_box .="	<div class='new_line_item'></div>
</div>";

$cancel_button = elgg_view('input/button', array(
		'value' => elgg_echo('cancel'),
		'href' =>  $entity ? $referrer : '#',
));

$jot_box .=
'<div class="elgg-foot">'.
     elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => $transfer_guid,
	)).
   elgg_view('input/hidden', array(
		'name' => 'asset',
		'value' => $vars['asset'],
	)).
   elgg_view('input/hidden', array(
		'name' => 'container_guid',
		'value' => $vars['container_guid'],
	)).
   elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent_guid'],
	)).
   elgg_view('input/hidden', array(
	     'name' => 'aspect', 
	     'value' => $aspect
	)).
	elgg_view('input/hidden', array(
		'name' => 'referrer',
		'value' => $referrer,
	)).
	elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->getGUID()
	)).
		elgg_view('input/hidden', array(
		'name' => 'referral_path',
		'value' => $referrer
	)).
	
'</div>';

$jot_box .=
"<div id='line_store' style='visibility: hidden; display:inline-block;'>
	<div class='line_item'><p>
		<div class='rTableRow' style=''>
				<div class='rTableCell' style='width:5%;padding: 0px 0px'><a href='#' class='remove-node'>".elgg_view_icon('delete')."</a></div>
				<div class='rTableCell' style='width:95%;padding: 0px 0px'>
					".elgg_view_form('jot/jotbox_row', $form_vars, $body_vars)."
				</div>
			</div>
	</div>
</div>";
$jot_box .= "</div>";
}
	
}

echo $jot_box;

/****************************/

if (!elgg_is_logged_in()) {
	return;
}

$entity = elgg_extract('entity', $vars);
/* @var ElggEntity $entity */

$comment = elgg_extract('comment', $vars);
/* @var ElggComment $comment */

$inline = elgg_extract('inline', $vars, false);
$is_edit_page = elgg_extract('is_edit_page', $vars, false);

$entity_guid_input = '';
if ($entity) {
	$entity_guid_input = elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $entity->guid,
	));
}

$comment_text = '';
$comment_guid_input = '';
if ($comment && $comment->canEdit()) {
	$entity_guid_input = elgg_view('input/hidden', array(
		'name' => 'comment_guid',
		'value' => $comment->guid,
	));
	$comment_label  = elgg_echo("generic_comments:edit");
	$submit_input = elgg_view('input/submit', array('value' => elgg_echo('save')));
	$comment_text = $comment->description;
} else {
	$comment_label  = elgg_echo("generic_comments:add");
	$submit_input = elgg_view('input/submit', array('value' => elgg_echo('comment')));
}

$cancel_button = '';
if ($comment) {
	$cancel_button = elgg_view('input/button', array(
		'value' => elgg_echo('cancel'),
		'class' => 'elgg-button-cancel mlm',
		'href' => $entity ? $entity->getURL() : '#',
	));
}

if ($inline) {
	$comment_input = elgg_view('input/text', array(
		'name' => 'generic_comment',
		'value' => $comment_text,
	));

	echo $comment_input . $entity_guid_input . $comment_guid_input . $submit_input;
} else {

	$comment_input = elgg_view('input/longtext', array(
		'name' => 'generic_comment',
		'value' => $comment_text,
	));

	$is_edit_page_input = elgg_view('input/hidden', array(
		'name' => 'is_edit_page',
		'value' => (int)$is_edit_page,
	));

	echo <<<FORM
<div>
	<label>$comment_label</label>
	$comment_input
</div>
<div class="elgg-foot">
	$is_edit_page_input
	$comment_guid_input
	$entity_guid_input
	$submit_input $cancel_button
</div>
FORM;
}
