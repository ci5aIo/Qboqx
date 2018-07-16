<?php

elgg_push_breadcrumb(elgg_echo('shelf:mine'), '/shelf');
$show = get_input($page[1]);                                  $display .= '4 show:'.$show.'<br>';
$title = elgg_echo('shelf:mine');
$content = '';

/*if (shelf()->countLines()) {
	elgg_register_menu_item('title', [
		'name' => 'shelf:empty',
		'text' => elgg_echo('shelf:empty'),
		'href' => '/action/shelf/empty',
		'confirm' => true,
		'is_action' => true,
		'link_class' => 'elgg-button elgg-button-delete',
	]);
}*/

// from CartStorage.php>get()
		$guid = elgg_get_logged_in_user_guid();
//		$guid = _elgg_services()->session->getId();     //$display .= '$guid: '.$guid.PHP_EOL;
		$file = new ElggFile;
		$file->owner_guid = $guid;
//		$file->owner_guid = elgg_get_site_entity()->guid;
		$file->setFilename("shelf.json");
//		$file->setFilename("/shelf/$guid.json");
		if ($file->exists()) {
			$file->open('read');
			$json = $file->grabFile();
			$file->close();
		}

		$data = json_decode($json, true);                 //$display .= '$data: '.$data.PHP_EOL;

if (empty($data)) {
	$content = elgg_format_element('p', ['class' => 'elgg-no-results'], elgg_echo('shelf:is_empty'));
} else {
    $action  = 'shelf/arrange';
	$form_vars  = array('enctype'     => 'multipart/form-data', 
                        'name'        => 'shelf_arrange');
    $body_vars = array('data'=>$data,'subtype'=>$show);
    $content = elgg_view_form($action, $form_vars, $body_vars);
}
if (elgg_is_xhr()) {
	echo $content;
} else {
	$layout = elgg_view_layout('one_sidebar', [
		'title' => $title,
		'content' => $content,
		'filter' => false,
		'class' => 'cart-layout',
	]);

	echo elgg_view_page($title, $layout);
}
echo $display;