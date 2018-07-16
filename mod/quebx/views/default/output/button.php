<?php
/**
 * Quebx button element
 * wrap contents in a button tag
 *
 * @package Quebx
 * @subpackage Core
 *
 * @uses string $vars['content']        The string between the <button></button> tags.
 * @uses string $vars['class']          class attributes to apply to section
 * @uses array  $vars['options']        collection of other attributes
 * @uses bool   $vars['encode_content'] Run $vars['content'] through htmlspecialchars() (false)
 */

$options                   = $vars['options'];
$attributes['class']       = $vars['class'];
$text                      = $vars['value'];
$options['encode_content'] = $vars['encode_content'];
unset($vars['encode_content']);

if (is_array($options)){
    foreach($options as $key=>$attribute){
        $attributes[$key] = $attribute;
    }
}
echo elgg_format_element('button', $attributes, $text, $options);
