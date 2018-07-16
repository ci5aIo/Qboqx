<?php
$title       = 'Items on the shelf';
$entity      = elgg_extract('entity', $vars);
$aspect      = elgg_extract('aspect', $vars);

$file        = new ElggFile;
$file->owner_guid = elgg_get_logged_in_user_guid();
$file->setFilename("shelf.json");
if ($file->exists()) {
	$file->open('read');
	$json = $file->grabFile();
	$file->close();
}

$data = json_decode($json, true);                 //$display .= '$data: '.$data.PHP_EOL;

if (empty($data)) {
	$content = elgg_format_element('p', ['class' => 'elgg-no-results'], elgg_echo('shelf:is_empty'));
} else {
    $body_vars = array('data'=>$data, 'item'=>$entity, 'aspect'=>$aspect);
    $body_vars = array_merge($vars, $body_vars);
    $content = elgg_view("forms/shelf/pick", $body_vars);
}

$body = elgg_view_layout('action', array(
	'content' => $content,
	'title' => $title,	
	'filter' => '',
));
echo $body;