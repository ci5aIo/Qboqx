<?php
/**
 * Elgg image view
 *
 * @uses string $vars['src'] The image src url.
 * @uses string $vars['guid'] The image guid.
 * @uses string $vars['size'] The image size.
 * @uses string $vars['alt'] The alternate text for the image (required).
 */

$options                   = (array) $vars['options'];
$attributes['class']       = $vars['class'];
$text                      = $vars['content'];
$options['encode_content'] = $vars['encode_content'];
                       unset($vars['encode_content']);
$guid                      = $vars['guid'];
$size                      = $vars['size'];
$base_url                  = elgg_get_site_url() . "gallery/icon";
 
if ($options){
    foreach($options as $key=>$attribute){
        $attributes[$key] = $attribute;
    }
}

if (!isset($vars['alt'])) {
	elgg_log("The view output/img requires that the alternate text be set.", 'NOTICE');
}

if(isset($vars['src'])){
$vars['src'] = elgg_normalize_url($vars['src']);
$vars['src'] = elgg_format_url($vars['src']);
}
else {
	$vars['src'] = "$base_url/$guid/$size";
}
$attributes = elgg_format_attributes($vars);
echo "<img $attributes/>";
