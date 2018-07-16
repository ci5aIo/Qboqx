<!-- page: mod\jot\pages\jot\view.php -->
<!-- view: mod\jot\views\default\object\transfer.php -->

<?php
$full          = elgg_extract('full_view', $vars, FALSE);
$section       = elgg_extract('this_section', $vars, 'Summary');
$transfer      = elgg_extract('entity', $vars, FALSE);
$aspect        = elgg_extract('aspect', $vars);
$action        = elgg_extract('action', $vars, FALSE);
$item_guid     = elgg_extract('asset', $vars, $transfer->asset);
$view_type     = elgg_extract('view_type', $vars, 'full');
$item          = get_entity($item_guid);
$view          = get_subtype_from_id($transfer->guid);
$owner         = $transfer->getOwnerEntity();
$container     = $transfer->getContainerEntity();
$asset_link    = elgg_view('output/url', array(
				 'href' => "market/view/$item->guid",
				 'text' => $item->title,
)).'<br>';
$owner_link    = elgg_view('output/url', array(
				'href' => "jot/owned/$owner->username",
				'text' => $owner->name,
)).'<br>';
$tags          = elgg_view('output/tags', array('tags' => $transfer->tags)).'<br>';
$date          = elgg_view_friendly_time($transfer->time_created).'<br>';
$comments_text = elgg_echo("comments") . " ($comments_count)";
$comments_link = elgg_view('output/url', array(
			 	 'href' => $transfer->getURL() . '#jot-comments',
				 'text' => $comments_text,
));
$comments_count = $transfer->countComments();
if ($comments_count == 0) {$comments_link = '';}
if (!$transfer->tags)  {$tags='';}
$owner_icon = elgg_view_entity_icon($owner, 'small');

$metadata = elgg_view_menu('entity', 
						  ['entity' => $transfer,    // for $entity  part of "$handler/edit/{$entity->getGUID()}"
						   'handler' => 'jot',       // for $handler part of "$handler/edit/{$entity->getGUID()}"
						   'sort_by' => 'priority',
						   'class' => 'elgg-menu-hz',
						   'view_type'=>$view_type,
						  ]);
	
$subtitle = "$asset_link $owner_link $tags $date $comments_link";
//	$subtitle = "{$category}{$custom}{$supplemental}{$pick}<br>{$author_text} {$date} {$comments_link}";
if (!empty($transfer->merchant)){
    if (elgg_entity_exists($transfer->merchant)){
        $merchants = get_entity($transfer->merchant);
    }
}
else {
    $merchants = elgg_get_entities_from_relationship(array(
    	'type' => 'group',
    	'relationship' => 'merchant_of',
    	'relationship_guid' => $transfer->guid,
        'inverse_relationship' => true,
    	'limit' => false,
    ));
	$merchants = array_merge($merchants, elgg_get_entities_from_relationship(array(
			'type' => 'group',
			'relationship' => 'return_receipt',
			'relationship_guid' => $transfer->guid,
		    'inverse_relationship' => false,
			'limit' => false,
		)));
    $merchants = array_merge($merchants, elgg_get_entities_from_relationship(array(
    			'type' => 'group',
    			'relationship' => 'supplier_of',
    			'relationship_guid' => $transfer->guid,
    			'inverse_relationship' => true,
    			'limit' => false,
    	)));
}
if (isset($merchants)) {
	$merchant = $merchants[0] ?: $merchants;
    if (elgg_entity_exists($merchant->guid)){
        $merchant_guid = (int) $merchant->getGUID();
    	$merchant_link  = elgg_view('output/url', array('text' => $merchant->name,'href' =>  "groups/profile/$merchant_guid"));
    }
    else {
        $merchant_link  = $transfer->merchant;
    }
}
else {
    if (elgg_entity_exists($transfer->merchant)){
        $merchant_guid = $transfer->merchant;
    }
    $merchant_link = $transfer->merchant;
}

$params = array_merge(
              array('header' => $header,
                	'metadata' => $metadata,
                	'subtitle' => $subtitle,
                    'tags'     => false,
              ), 
              $vars);                                                $display .= '95 $params: '.print_r($params, true).'<br>';

Switch ($view_type){
    case 'list':
        $params['subtitle'] = 'receipt<br>'.
                              'Merchant: '.$merchant_link.'<br>'.
                              'Total: '.money_format('%#10n', $transfer->total);
    	$list_body = elgg_view('object/elements/summary', $params);
    	$body_vars['body_class']='quebx-menu-q';
    	$jot_icon = elgg_view('jot/icon', array('jot' => $transfer, 'size' => 'small'));
    	$transfer_info = elgg_view_image_block($jot_icon, $list_body, $body_vars);
/*        elgg_load_library('elgg:market');
        $content = market_render_section(array(
                                         'section'    => 'receipt',
                                         'selected'   => $selected,
                                         'action'     => 'view',
                                         'view_type'  => 'compact',
                                         'owner_guid' => elgg_get_logged_in_user_guid(),
                                         'entity'     => $transfer));
        $body = 'transfer '.elgg_view('output/url', array('text'=>$transfer->getGUID(), 'href'=>'jot/view/'.$transfer->getGUID()));
        $body .= $content;*/
    	$body = elgg_view('output/div', ['content'=>$transfer_info,
	    		                         'class'  =>"jot elgg-content"]);
        break;
    case 'compact':
    case 'compact dropdown':
    case 'popup':
    	$perspective = elgg_extract('perspective', $vars);
    	$space = elgg_extract('space', $vars);
    	switch ($perspective){
    		case 'view':
    			$transfer_info = elgg_view('jot/display/transfer/summary', $params);
    			break;
    		case 'add':
    		case 'edit':
	    		if ($aspect == 'trash'  ||
	    		    $aspect == 'donate' ||
	    		    $aspect == 'loan'   ||
	    		    $aspect == 'sell' ){$vars['asset']=$item_guid;
							    		$form_action = 'action/jot/add/element_v2';}
	    		else                   {$form_action = 'action/jot/edit_v4';}
	    		$transfer_info = elgg_view_form('transfers/edit',['action'=>$form_action, 'id'=> "{$perspective}_{$space}"],$vars);
	    		break;
    		case 'list':
    			$transfer_info = elgg_view('jot/display/transfer/ledger', $vars);
    			break;
		}
    	$body = $transfer_info;
    	break;
    default:
    	$list_body = elgg_view('object/elements/summary', $params);
    	$extra_params = $vars;
    	$extra_params['entity']=$transfer;
    	$extra_params['asset'] = $item;
    	$extra_params['this_section'] = $section;
	    $extra = elgg_view("jot/display/transfer",$extra_params);
    
    	$transfer_info = elgg_view_image_block($owner_icon, $list_body.$extra);
    
	    //$text = elgg_view('output/longtext', array('value' => $transfer->description));
	    $body = elgg_view('output/div', ['content'=>$transfer_info,
	    		                         'class'  =>"jot elgg-content"]);
    break;
}

echo <<<HTML
	$body
HTML;
//register_error($display);