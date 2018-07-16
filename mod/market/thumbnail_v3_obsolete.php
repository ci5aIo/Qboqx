<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

// Get engine
require_once(dirname(__FILE__) . "/vendor/elgg/elgg/engine/start.php");

// Get file GUID
$marketguid = (int) get_input('marketguid', 0);
$entity     = get_entity($marketguid);
$owner      = $entity->getOwnerEntity();
$folder     = 'icons/';
$filename   = $marketguid;
$size       = elgg_strtolower(get_input('size'));
if (!$size){
	$size   = elgg_extract('size', $vars, 'medium');}
$extension  = '.jpg';

// Set the size
if (!in_array(elgg_strtolower($size), array('large','medium','small','tiny','master'))) {
	$size = "medium";
}

// Use master if we need the full size
if ($size == "master") {
//	$size = "";
}

// Get the icon
$filehandler = new ElggFile();
//$filehandler->owner_guid = $owner->guid;
		
$success = false;
$filehandler->setFilename($folder.$filename.$size.$extension);
if ($filehandler->open("read")) {
    if ($contents = $filehandler->read($filehandler->getSize())) {
		$success = true;
	} 
}
$filehandler->setFilename($folder.$filename.$extension);
if ($filehandler->open("read") && $size == "master") {
    if ($contents = $filehandler->read($filehandler->getSize())) {
		$success = true;
	} 
}
/*
if (!$success) {
    $filehandler->setFilename("market/$marketguid.$size.$extension");
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->getSize())) {
			$success = true;
		} 
	}
}

if (!$success) {
	$filehandler->setFilename("market/$marketguid.$extension");
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->getSize())) {
			$success = true;
		} 
	}
}
*/
if (!$success) {
    $filehandler->setFilename($folder.$marketguid.$size."thumb.$extension");
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->getSize())) {
			$success = true;
		} 
	}
}

if (!$success) {
$filehandler->setFilename($folder.$marketguid."master.$extension");
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->getSize())) {
			$success = true;
		} 
	}
}
if (!$success) {
	$path = elgg_get_site_url() . "mod/market/graphics/noimage{$size}.png";
	header("Location: $path");
}

header("Content-type: image/jpeg");
header('Expires: ' . date('r',time() + 864000));
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));

$splitString = elgg_split($contents, 1024);

foreach($splitString as $chunk) {
	echo $chunk;
}