<?php
/**
 * Market icon view
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       topbar, tiny, small, medium (default), large, master
 * @uses $vars['href']       Optional override for link
 * @uses $vars['img_class']  Optional CSS class added to img
 * @uses $vars['link_class'] Optional CSS class added to link
 */

// Get file GUID
$marketguid = (int) get_input('marketguid', 0);
$folder     = 'icons/';
$filename   = $marketguid;
$size       = strtolower(get_input('size'));
if (!$size){
	$size   = elgg_extract('size', $vars, 'medium');}
$extension  = '.jpg';

$marketpost = get_entity($marketguid);

$item_icon = elgg_view('output/url', array(
		'href' => "market/view/{$marketpost->guid}/" . elgg_get_friendly_title($marketpost->title),
		'text' => elgg_view('market/thumbnail', array('marketguid' => $marketpost->guid, 'size' => 'small', 'tu' => $tu)),
			));
 

$entity = $vars['entity'];
//$entity = $marketpost;
//$entity = get_entity($marketguid);

// Set the size
if (!in_array(strtolower($size), array('large','medium','small','tiny','master'))) {
	$size = "medium";
}

$title = $entity->title;
$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8', false);

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$class = '';
if (isset($vars['img_class'])) {
	$class = $vars['img_class'];
}
if ($entity->thumbnail) {
	$class = "class=\"elgg-photo $class\"";
} else if ($class) {
	$class = "class=\"$class\"";
}

// Get the icon
$filehandler = new ElggFile();
$filehandler->owner_guid = $owner->guid;
		
$success = false;
$filehandler->setFilename($folder.$filename.$size.$extension);
if ($filehandler->open("read")) {
	if ($contents = $filehandler->read($filehandler->size())) {
		$success = true;
	} 
}

if (!$success) {
    $filehandler->setFilename("market/$marketguid$size.jpg");
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->size())) {
			$success = true;
		} 
	}
}

if (!$success) {
	$filehandler->setFilename("market/$marketguid.jpg");
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->size())) {
			$success = true;
		} 
	}
}

if (!$success) {
    $filehandler->setFilename("icons/$marketguid$size.jpg");
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->size())) {
			$success = true;
		} 
	}
}
if (!$success) {
$filehandler->setFilename("icons/" . $marketguid . "master.jpg");
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->size())) {
			$success = true;
		} 
	}
}
if (!$success) {
    $filehandler->setFilename("icons/" . $marketguid . $size . "thumb.jpg");
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->size())) {
			$success = true;
		} 
	}
}

// if (!$success) {
// 	$url = elgg_get_site_url() . "mod/market/graphics/noimage{$size}.png";
// }
// overrides above
$url = $entity->getURL();
// $img_src = $entity->getIconURL($vars['size']);
// $img_src = elgg_format_url($img_src);
$img_src = elgg_get_site_url(). "mod/market/thumbnail.php?marketguid=$entity->guid&size=$size";
$img = "<img $class src=\"$img_src\" alt=\"$title\" />";
if ($url) {
	$params = array(
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
	);
	if (isset($vars['link_class'])) {
		$params['class'] = $vars['link_class'];
	}
	echo elgg_view('output/url', $params);
} else {
    echo $contents;
}
