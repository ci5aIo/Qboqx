<?php
/**
 * Asset view in Asset Picker
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['entity'] Asset entity
 * @uses $vars['input_name'] Name of the returned data array
 */

/* @var ElggEntity $entity */
$entity        = $vars['entity'];
$take_snapshot = $vars['snapshot'];
$container_guid = elgg_extract('container_guid', $vars);
if (elgg_instanceof($entity, 'object', 'market') || elgg_instanceof($entity, 'object', 'item')){
    $variable_name  = $vars['input_name'];
    $variable_value = $entity->getGUID();
    $icon        = elgg_view_entity_icon($entity, 'tiny');
    $name        = $entity->title;
    $element_type = $entity->getSubtype();
    $entity_guid = $entity->getGUID();
    $icon_guid   = $entity->icon ?: $entity_guid;
    $name_link   = elgg_view('output/url', array('text' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                                             'href' => $entity->getURL(),
        ));
    $icon_link   = elgg_view('output/url', array(
        			'text' => $icon, 
        		    'class' => 'elgg-lightbox',
        	        'href' => "mod/market/viewimage.php?marketguid=$icon_guid"));
    $detach_link = elgg_view("output/url",
			       ['href' => "action/jot/detach?element_type=$element_type&guid={$entity->getGUID()}&container_guid=$container_guid",
			    	'text' => elgg_view_icon('link').elgg_view_icon('unlink'),
			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $name),
			        'class'=>'hover-change',                                      // icon change controlled by css
			    	'encode_text' => false,
			       ]
				);
}
else {
    $variable_name  = $vars['input_name'];
    $variable_value = $entity;
    $name        = $entity;
    $name_link   = $name;
    $icon        = elgg_get_site_url() . "mod/market/graphics/noimagetiny.png";
    $icon_link   = "<img src=\"{$icon}\">";
}

if ($take_snapshot){
    $snapshot_variable_name  = substr_replace($variable_name, '[snapshot]', strpos($variable_name, '['), 0);
    $snapshot_input          = "<input type='hidden' name='".htmlspecialchars($snapshot_variable_name, ENT_QUOTES, 'UTF-8')."[]' value='$variable_value' />"; 
}
$item = "<li data-guid= $entity_guid>
	<div class='elgg-image-block'>
		<div class='elgg-image'>$icon_link</div>
		<div class='elgg-image-alt'>$detach_link</div>
		<div class='elgg-head'>$name_link</div>
	</div>
	<input type='hidden' name='{htmlspecialchars($variable_name, ENT_QUOTES, 'UTF-8')}[]' value='$variable_value />
	$snapshot_input
</li>";
echo $item;