<?php
/**
 * Quebx section element
 * wrap contents in a section tag
 *
 * @package Quebx
 * @subpackage Core
 *
 * @uses string $vars['content']        The string between the <section></section> tags.
 * @uses string $vars['class']          class attributes to apply to section
 * @uses array  $vars['options']        collection of other attributes
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
echo elgg_format_element('section', $attributes, $text, $options);
