<?php
/**
 * Form for adding and editing comments
 *
 * @package Elgg
 *
 * @uses ElggEntity  $vars['entity']  The entity being commented
 * @uses ElggComment $vars['comment'] The comment being edited
 * @uses bool        $vars['inline']  Show a single line version of the form?
 */

if (!elgg_is_logged_in()) {
	return;
}

$river_id_input = "";
$river_id = elgg_extract('river_id', $vars, false);
if ($river_id){
	$river_id_input =  elgg_view('input/hidden', array(
		'name' => 'river_id',
		'value' => $river_id
	));
}

$entity_guid_input = '';
if (isset($vars['entity'])) {
	$entity_guid_input = elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->guid,
	));
}

$comment_text = '';
$comment_guid_input = '';
if (isset($vars['comment']) && $vars['comment']->canEdit()) {
	$entity_guid_input = elgg_view('input/hidden', array(
		'name' => 'comment_guid',
		'value' => $vars['comment']->guid,
	));
	$comment_label  = '<label>'.elgg_echo("generic_comments:edit").'</label>';
	$submit_input = elgg_view('input/submit', array('value' => elgg_echo('save')));
	$comment_text = $vars['comment']->description;
} else {
	$comment_label  = '<label>'.elgg_echo("generic_comments:add").'</label>';
	$submit_input = elgg_view('input/submit', array('value' => elgg_echo('save'), 'class' => 'elgg-button-submit-element'));
}

$cancel_link = '';
if (isset($vars['comment'])) {
	$cancel_link = "<a class='elgg-cancel mlm' href='#'>" . elgg_echo('cancel') . "</a>";
}

$inline = elgg_extract('inline', $vars, false);

// testing ...
//unset($submit_input);

if ($inline) {
	$comment_input = elgg_view('input/text', array(
		'name' => 'generic_comment',
		'value' => $comment_text,
	));

	echo $river_id_input . $comment_input . $entity_guid_input . $comment_guid_input . $submit_input;
} else {
	$comment_input = elgg_view('input/longtext', array(
		'name' => 'generic_comment',
		'value' => $comment_text,
	    'placeholder'=>'Leave a comment ...',
	    'class' => 'commentarea',
	    'style'  => array('height:20px'),
	));
$owner_icon = elgg_view_entity_icon(elgg_get_logged_in_user_entity(), 'tiny');
$comment_input = elgg_view_image_block($owner_icon, $comment_input);
	
unset ($comment_label);
	echo <<<FORM
<div>
	$comment_label
	$comment_input
</div>
<div class="elgg-foot">
	$comment_guid_input
	$entity_guid_input
	$submit_input $cancel_link
<div>
FORM;
}
