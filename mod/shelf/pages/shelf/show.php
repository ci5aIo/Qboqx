<?php
$entity_guid = get_input('item_guid');
$subtype     = get_input('show');                           $display .='3 show: '.$subtype.'<br>';
if (elgg_entity_exists($entity_guid)){
    $entity     = get_entity($entity_guid);
    $page_shell = 'box';
    $view_page  = 'pack';
}
else {
    $entity = get_user_by_username($entity_guid);
    $action = 'transfer';
}

$title       = elgg_echo('shelf:mine');

$file        = new ElggFile;
$file->owner_guid = elgg_get_logged_in_user_guid();
$file->setFilename("shelf.json");
if ($file->exists()) {
	$file->open('read');
	$json = $file->grabFile();
	$file->close();
}

$data = json_decode($json, true);                 $display .= '$data: '.$data.PHP_EOL.'<br>';

if (empty($data)) {
	$content = elgg_format_element('p', ['class' => 'elgg-no-results'], elgg_echo('shelf:is_empty'));
} 
else {
    $action  = 'shelf/pick'; // action defined as 'actions/shelf/pack' in start.php.  Otherwise, inexplicably, forwards to default page without applying action(??).  
	$form_vars  = array('enctype'     => 'multipart/form-data', 
                        'name'        => 'shelf_pick',
	);                                           $display.='34 $action: '.$action.'<br>';
    $body_vars = ['data'=>$data, 'item'=>$entity, 'show'=>$subtype,'aspect'=>'media_input'];
    $content = elgg_view_form($action, $form_vars, $body_vars);
}                                               $display.='<br>37'.$content.'<br>';
//if (elgg_view_exists('page/layouts/action')){$exist = 'action exists<br>';} else {$exist = 'action doesn\'t exist<br>';}
$layout = elgg_view_layout('action', array(
	'title' => $title,
	'content' => $content,
	'filter' => false,
	'class' => 'cart-layout',
));

$body = elgg_view_layout('action', array(
	'content' => $content,
	'title' => $title,	
	'filter' => '',
));                                              $display.='50 $view_page: '.$view_page.'<br>';

if ($view_page == 'pack'){
    echo elgg_view_page($title, $body, $page_shell);
}
elseif ($view_page == 'transfer'){
    echo elgg_view_page($title, $layout);
}
else{
    echo elgg_view_page($title, $body, 'box');
}
//echo $content;
//echo 'Whaaat!?';
//echo $display;