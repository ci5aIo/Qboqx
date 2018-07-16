<?php
	$select_type = elgg_extract('select_type', $vars, false);
	$show_icon   = elgg_extract('show_icon'  , $vars, false);

	Switch ($select_type){
	    case 'checkbox':
	        $view = 'input/checkbox';
	        break;
	    case 'radio':
	        $view = 'input/radio';
	        break;
	    default:
	        break;
	}
	if ($show_icon){
	    $icon = $item->getIcon('tiny');
	}
	
	$list_item = '<div>';
	
echo 'here';
