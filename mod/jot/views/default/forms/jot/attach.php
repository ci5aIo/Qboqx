<?php
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
//$container_guid = elgg_extract('container_guid', $vars);
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$guid = elgg_extract('guid', $vars, null);

$files = elgg_get_entities(array('type'=>'object','subtype'=>'file', 'owner_guid' => $owner->guid, ));

if ($guid) {
	$file_label = elgg_echo("file:replace");
	$submit_label = elgg_echo('save');
} else {
	$file_label = elgg_echo("file:file");
	$submit_label = elgg_echo('upload');
}
//@EDIT - 2013-11-23 - SAJ
	$element_type = get_input('element_type');
	$container_guid = get_input('container_guid');
/*	echo 'Page: mod\jot\pages\jot\attach.php<br>';
	echo 'View: mod\jot\views\default\forms\jot\attach.php<br>';
	echo "container_guid: {$container_guid}<br>";
	echo 'element_type: '.$element_type;
*/	echo elgg_view('input/hidden', array('name' => 'element_type', 'value' => $element_type));
//

$input_form = elgg_view('input/dropzone', array('name' => 'upload_guids[]',
//									'accept' => 'application/pdf',
									'max' => 25,
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $container_guid,
									'subtype' => 'file',));
/*
?>
<div>
<!--	<label><?php echo $file_label; ?></label><br> -->
	<?php echo elgg_view('input/file', array('name' => 'upload')); ?>
</div>
<!--
<div>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
</div>
<div>
	<label><?php echo elgg_echo('description'); ?></label>
	<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
</div>
<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>
<?php
$categories = elgg_view('input/categories', $vars);
if ($categories) {
	echo $categories;
}

?>
<div>
	<label><?php echo elgg_echo('access'); ?></label><br />
	<?php echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?>
</div>
<div class="elgg-foot">
-->
<?php
*/
echo $input_form;

echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'file_guid', 'value' => $guid));
}

//echo elgg_view('input/submit', array('value' => $submit_label)).'</p>';