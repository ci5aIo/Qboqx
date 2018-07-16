<!-- page: mod\jot\pages\jot\view.php -->
<!-- view: mod\jot\views\default\object\experience.php -->
<?php
$full          = elgg_extract('full_view', $vars, FALSE);
$section       = elgg_extract('this_section', $vars, 'Summary');
$experience    = elgg_extract('entity', $vars, FALSE);
$item_guid     = elgg_extract('asset', $vars, $experience->asset);
$selected      = elgg_extract('selected', $vars);
$list_type     = elgg_extract('list_type', $vars);
    if (empty($item_guid)){$item_guid = $experience->assets;}
$item          = get_entity($item_guid);
$view          = get_subtype_from_id($experience->guid);
$owner         = $experience->getOwnerEntity();
$tu            = $experience->time_updated;
$container     = $experience->getContainerEntity();
$jot           = elgg_get_excerpt($experience->description);
$subtype       = $experience->getSubtype();
$aspect        = $experience->aspect;
$owner_icon    = elgg_view_entity_icon($owner, 'tiny', array('use_hover'=>false));
$metadata      = elgg_view_menu('entity', array(
                	'entity' => $vars['entity'],
                	'handler' => 'jot',
                	'sort_by' => 'priority',
                	'class' => 'elgg-menu-hz',
                ));
Switch ($list_type){
    case 'brief':
        if ($aspect  == 'instruction'){
            $selected = 'Instructions';
        }
        else {
            $selected = ucfirst($aspect);
        }
        
            $i = $experience;
//  			$list_body = elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/{$i->guid}"));
 			elgg_load_library('elgg:market');
 			$content =  market_render_section(array(
                                 'section'    => 'experience',
                                 'selected'   => $selected,
                                 'action'     => 'view',
                                 'entity'     => $experience,));
 			$params = array(
        		'header'   => $header,
        		'metadata' => $metadata,
        		'subtitle' => $subtitle,
        		'tags'     => 'none',
        	    'content'  => $content,
        	);
        
        	$params = array_merge($params, $vars);
        	$list_body = elgg_view('object/elements/summary', $params);
			$body      = elgg_view_image_block($owner_icon, $list_body);
        
        break;
    case 'list':
        elgg_load_library('elgg:market');
        $content = market_render_section(array(
                                         'section'    => 'experience',
                                         'selected'   => $selected,
                                         'selected_state'=> 'closed',
                                         'action'     => 'view', 
                                         'owner_guid' => elgg_get_logged_in_user_guid(),
                                         'entity'     => $experience));		  
    	$params = array(
        	'metadata'      => $metadata,
    	    'tags'          => 'none',
    	    'content'       => $content,
    	    'show_comments' => false,
    	);
    
    	$params = array_merge($params, $vars);
    	$list_body = elgg_view('object/elements/list', $params);
        	
    	$body = elgg_view_image_block($owner_icon, $list_body);
    	
    	if (elgg_get_plugin_setting('market_comments', 'market') == 'yes') {
            $params2 = array('inline'=>false, 'show_heading'=>false);
            $params2 = array_merge($vars, $params2);
            $add_comment = true;
            $count_comments = $experience->countComments();
        	$comments = elgg_view_comments($experience, $add_comment, $params2);
        }
    
    	$body .= elgg_view('output/url', array('guid'=>$experience->getGUID(), 'text'=>$count_comments.' comments', 'do'=>'show_comments', 'style'=>array('cursor:pointer')));
        if ($comments){
        	$body .= "<div panel='comments' guid='".$experience->getGUID()."' class='elgg-comments' style='display: none'>$comments</div>";    
        }
        
        break;
    default:
        if ($item->type == 'object' ){
        	  if ($item->getSubtype() == 'place'){
        	  	$asset_href = "places/view/$item_guid";
        	  }
        	  else {
        	  	$href = "market/view/$item_guid";
        	  }
        }
        
        $asset_link    = elgg_view('output/url', array(
        				 'href' => $asset_href,
        //				 'href' => "market/view/$item->guid",
        				 'text' => $item->title,
        )).'<br>';
        $owner_link    = elgg_view('output/url', array(
        				'href' => "jot/owned/$owner->username",
        				'text' => $owner->name,
        )).'<br>';
        $tags          = elgg_view('output/tags', array('tags' => $experience->tags)).'<br>';
        $date          = elgg_view_friendly_time($experience->time_created).'<br>';
        $comments_text = elgg_echo("comments") . " ($comments_count)";
        $comments_link = elgg_view('output/url', array(
        			 	 'href' => $experience->getURL() . '#jot-comments',
        				 'text' => $comments_text,
        ));
        $comments_count = $experience->countComments();
        if ($comments_count == 0) {$comments_link = '';}
        if (!$experience->tags)  {$tags='';}
        $subtitle = "$asset_link $owner_link $tags $date $comments_link";
        //	$subtitle = "{$category}{$custom}{$supplemental}{$pick}<br>{$author_text} {$date} {$comments_link}";
        //    $content = elgg_view("jot/display/experience",array('entity'=>$experience, 'asset'=>$item, 'this_section'=>$section));
        
            elgg_load_library('elgg:market');
            $content = market_render_section(array(
                                             'section'    => 'experience',
                                             'selected'   => $selected,
                                             'action'     => 'view', 
                                             'owner_guid' => elgg_get_logged_in_user_guid(),
                                             'entity'     => $experience));		  
        	$params = array(
        		'header'   => $header,
        		'metadata' => $metadata,
        		'subtitle' => $subtitle,
        		'tags'     => $tags,
        	    'content'  => $content,
        	);
        
        	$params = array_merge($params, $vars);
        	$list_body = elgg_view('object/elements/summary', $params);
        
        	$body = elgg_view_image_block($owner_icon, $list_body);
        	
        	break;
}
	
echo <<<HTML
<div class="jot elgg-content">
	$body
</div>
HTML;
eof: