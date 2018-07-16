<?php
$experience   = elgg_extract('entity', $vars);
$section      = elgg_extract('this_section', $vars);
$subtype      = $experience->getSubtype();
//$fields       = jot_prepare_brief_view_vars($subtype, $experience, $section);

elgg_load_library('elgg:market');
	
echo market_render_section(array('section'    => 'experience', 
                                 'action'     => 'view',
                                 'owner_guid' => elgg_get_logged_in_user_guid(),
                                 'entities'   => $experiences, 
                                 'item_guid'  => $item_guid, 
                                 'entity'     => $experience));	
