<?php
// note that this has no access

$guid = get_input('guid');
$time = get_input('time');
$name = get_input('name');
$entity = get_entity($guid);
$filestorename = $time .'/'. $name;

$file = new ElggFile();

$file->owner_guid = $entity->owner_guid;
$file->container_guid = $entity->owner_guid;
$mime_type = $file->detectMimeType($file->getFilenameOnFilestore());
$prefix = "marketfile/{$entity->guid}/";
$file->setFilename($prefix . $filestorename);
$file->setMimeType($mime_type);
$file->originalfilename = $name;
$file->simpletype = elgg_get_file_simple_type($mime_type);

// fix for IE https issue
header("Pragma: public");

header("Content-type: $mime_type");
if (strpos($mime_type, "image/") !== false || $mime_type == "application/pdf") {
	header("Content-Disposition: inline; filename=\"$name\"");
} else {
	header("Content-Disposition: attachment; filename=\"$name\"");
}

if (!file_exists($file->getFilenameOnFilestore())) {
	exit;
}

ob_clean();
flush();
readfile($file->getFilenameOnFilestore());
exit;