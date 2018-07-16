<?php
$entity  = $vars['item'];
$name    = $vars['name'];
$value   = $entity->getGUID();
$display_name = $entity->getDisplayName();
$class   = elgg_extract('class',$vars, false);
if ($class){
	$class_str = "class='$class'"; 
}

$input_radio = "<span title='select'><input $class_str type='radio' name='$name' value='$value' data-display-name='$display_name'></span>";
echo $input_radio;