<?php
/**
* Display an icon from the quebx icons sprite.
*
* @package Quebx
* @subpackage Core
*
* @uses $vars['class'] Class of quebx-icon
*/

$class = (array) elgg_extract("class", $vars);
$class[] = "quebx-icon";

$vars["class"] = $class;

$attributes = elgg_format_attributes($vars);

echo "<span $attributes></span>";
