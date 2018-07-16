View: shelf/views/default/resources/shelf/show.php
<?php
$title = elgg_echo('shelf:mine');
$file  = new ElggFile;
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
    $action  = 'shelf/pick';
	$form_vars  = array('enctype'     => 'multipart/form-data', 
                        'name'        => 'shelf_arrange');
    $body_vars = array('data'=>$data);
    $content = elgg_view_form($action, $form_vars, $body_vars);
}

echo $content;