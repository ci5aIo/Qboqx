<?php
/**
 * Quebx header element
 * wrap contents in a header tag
 *
 * @package Quebx
 * @subpackage Core
 *
 * @uses string $vars['content']        The string between the <header></header> tags.
 * @uses string $vars['class']          class attributes to apply to section
 * @uses bool   $vars['encode_content'] Run $vars['content'] through htmlspecialchars() (false)
 */

$options                   = $vars['options'];
$attributes['class']       = $vars['class'];
$text                      = $vars['content'];
$options['encode_content'] = $vars['encode_content'];
unset($vars['encode_content']);

if (is_array($options)){
    foreach($options as $key=>$attribute){
        $attributes[$key] = $attribute;
    }
}
echo elgg_format_element('header', $attributes, $text, $options);
