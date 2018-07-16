<?php
/**
 * Livesearch list
 * Adapted from views\default\page\components\image_block.php
 */

$entity = $vars['entity'];
$img    = $vars['img'];

$body = elgg_extract('body', $vars, $entity->title);
$image = elgg_extract('image', $vars, $img);
$alt_image = elgg_extract('image_alt', $vars, '');

$class = 'elgg-image-block';
$additional_class = elgg_extract('class', $vars, '');
if ($additional_class) {
	$class = "$class $additional_class";
}

$id = '';
if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
}

$body = "<div>$body</div>";

if ($image) {
	$image = "<div class=\"elgg-image\">$image</div>";
}

if ($alt_image) {
	$alt_image = "<div class=\"elgg-image-alt\">$alt_image</div>";
}

echo "<div class='$class clearfix' $id>
	$image$alt_image$body
</div>";