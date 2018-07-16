<br>market\views\default\object\icon\object\image.php<br>
<?php

$entity = elgg_extract('entity', $vars);

$requested_size = $size = elgg_extract('size', $vars);

$gallery_config = get_icon_sizes($entity);

if (array_key_exists($requested_size, $gallery_config)) {
	$values = elgg_extract($requested_size, $gallery_config);
	$requested_w = $values['w'];
	if ($values['square']) {
		$requested_h = $values['h'];
	}
} else {
	list($requested_w, $requested_h) = explode('x', $requested_size);
}

$class = elgg_extract('img_class', $vars, 'gallery-media-icon centered');

$title = htmlspecialchars($entity->title, ENT_QUOTES, 'UTF-8', false);

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

	$entity->setIconURL("icons/" . $entity->guid . $size . ".jpg");
/*	
	$src = elgg_view('market/thumbnail', array(
				'marketguid' => $entity->guid,
				'size' => 'master',
				'class' => 'market-image-popup',
	            ));
*/
$src = 	$entity->getIconURL($vars['size']);                      $display .= '$src: '.$src;

$img = elgg_view('output/img', array(
	'src' => $src,
//	'src' => $entity->getIconURL($vars['size']),
	'alt' => $title,
	'class' => $class,
	'width' => ($requested_w) ? $requested_w : null,
	'height' => ($requested_h) ? $requested_h : null,
		));
/*
	$img = elgg_view('market/thumbnail', array(
				'marketguid' => $entity->guid,
				'size' => 'medium',
				'class' => 'market-image-popup',
	            ));
*/
if ($url) {

	if (!$requested_h) {
		$requested_h = 'auto';
	} else {
		$requested_h = "{$requested_h}px";
	}
	if (!$requested_w) {
		$requested_w = '100%';
	} else {
		$requested_w = "{$requested_w}px";
	}
	$params = array(
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
		'data-guid' => $entity->guid,
	);
	$class = elgg_extract('link_class', $vars, '');

	if ($class) {
		$params['class'] = $class;
	}

	echo elgg_view('output/url', $params);
	echo $display;
} else {
	echo $img;
	echo $display;
}
