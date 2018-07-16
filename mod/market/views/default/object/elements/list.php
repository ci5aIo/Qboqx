<?php
/**
 * Object list
 * Adapted from summary.php
 */

$entity        = elgg_extract('entity'       , $vars);
$title_link    = elgg_extract('title'        , $vars, '');
$metadata      = elgg_extract('metadata'     , $vars, false);
$subtitle      = elgg_extract('subtitle'     , $vars, '');
$content       = elgg_extract('content'      , $vars, false);
$tags          = elgg_extract('tags'         , $vars, false);
$show_activity = elgg_extract('show_activity', $vars, false);
$show_comments = elgg_extract('show_comments', $vars, false);
$show_contents = elgg_extract('show_contents', $vars, false);
$add_comment   = elgg_extract('add_comment'  , $vars, $show_comments);
$include_dropbox = elgg_extract('include_dropbox', $vars, true);

if ($title_link === '') {
	if (isset($entity->title)) {
		$text = $entity->title;
	} else {
		$text = $entity->name;
	}
	$params = array(
		'text' => $text,
		'href' => $entity->getURL(),
		'is_trusted' => true,
	);
	$title_link = elgg_view('output/url', $params);
}
if ($tags) {
	$tags = elgg_view('output/tags', array('tags' => $entity->tags));
}

if ($metadata) {
	$view_elements .= $metadata;
}
if ($title_link) {
	$view_elements .= "$title_link";
}
if ($subtitle){
    $view_elements .= "<div class=\"elgg-subtext\">$subtitle</div>";
}
if ($show_activity){

    $experiences = elgg_get_entities(array(
        'type'          => 'object', 
        'subtype'       => 'experience', 
        'container_guid'=> $entity->getGUID(),
    ));
    foreach($experiences as $i){
        $guids[] = $i->guid;
    }
    $options  = array(
        'guids'          => $guids,
        'list_type'      => 'brief'
    );
    if (!empty($experiences)){
        $activity = elgg_list_entities($options);
    }
}
//$view_elements .= $tags;

if (elgg_get_plugin_setting('market_comments', 'market') == 'yes' &&
    $show_comments) {
    $params = array('inline'=>false, 'show_heading'=>false);
    $params = array_merge($vars, $params);
	$comments = elgg_view_comments($entity, $add_comment, $params);
}

$view_elements .= elgg_view('object/summary/extend', $vars);
$view_elements .= $activity;
if ($content) {
	$view_elements .= "<div class=\"elgg-content\">$content</div>";
}
if ($comments && $show_comments){
	$view_elements .= "<div class=\"elgg-comments\">$comments</div>";    
}
if($show_contents){
	$item_guid = $entity->getGUID();
	$contents = elgg_get_entities(array(
	                'type' => 'object',
					'subtypes' => array('market', 'item', 'contents'),
	                'joins'    => array('JOIN elgg_objects_entity e2 on e.guid = e2.guid'),
					'wheres' => array(
						"e.container_guid = $item_guid",
	                    "NOT EXISTS (SELECT *
	                                 from elgg_entity_relationships s1
	                                 WHERE s1.relationship = 'component'
	                                   AND s1.guid_two = e.container_guid)"
					),
	                'order_by' => 'e2.title',
	                'limit' => false,
				));
	$components = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'component',
		'relationship_guid' => $item_guid,
	    'inverse_relationship' => true,
		'limit' => false,
	));
	$accessories = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'accessory',
		'relationship_guid' => $item_guid,
	    'inverse_relationship' => true,
		'limit' => false,
	));
	unset($compartment_class, $content_items, $count);
	$count          = 0;
	$compartment_class   = 'compartment-contents-header';
	if ($contents){
		$compartment_class .= ' compartment-open';
		$count = count($contents);
		foreach($contents as $key=>$object){
			unset($guid, $container_guid, $title, $qid_n);
			$qid_n  = "q{$guid}_041";
			$delete = elgg_view('output/url', ['data-qid_n' => $qid_n,
		                           'title'=>'remove content',
							       'class'=>'remove-node',
							       'style'=> 'cursor:pointer',
							       'text' => elgg_view_icon('delete-alt')+'&nbsp;',]);
			$guid           = $object->guid;
			$container_guid = $object->container_guid;
			$title = elgg_view('output/url',
							['data-guid'        => $guid,
							 'data-qid'         => "q{$guid}",
							 'data-qid_n'       => $qid_n,
							 'data-element'     => 'market',
							 'data-space'       => 'market',
							 'data-perspective' => 'view',
							 'data-presentation'=> 'inline',
							 'data-context'     => 'market',
							 'class'            => 'do',
							 'text'             => $object->title]);
			$content_items .= "<li class='compartment-content-item' data-guid='$guid' data-container-guid='$container_guid'><span style='white-space:nowrap;'>$delete$title</span></li>";
		}
	}
	else {$compartment_class .= ' compartment-closed';}
	$labels      .= "<a href='#' class='$compartment_class' data-jq-dropdown='#$item_guid-compartment-1' data-count='$count' data-aspect='content'>contents (<span>$count</span>)</a>";
	$compartment .= "<div id='$item_guid-compartment-1' class='jq-dropdown jq-dropdown-relative jq-dropdown-tip'>
			              <ul class='jq-dropdown-menu' data-aspect='content'>
				              <li>contents</li>
				              $content_items
			              </ul>
		             </div>";	
	
	unset($compartment_class, $content_items, $count);
	$count          = 0;
	$compartment_class   = 'compartment-components-header';
	if ($components){
		$compartment_class .= ' compartment-open';
		$count = count($components);
		foreach($components as $key=>$object){
			unset($guid, $title, $qid_n);
			$qid_n  = "q{$guid}_042";
			$delete = elgg_view('output/url', ['data-qid_n' => $qid_n,
		                           'title'=>'remove content',
							       'class'=>'remove-node',
							       'style'=> 'cursor:pointer',
							       'text' => elgg_view_icon('delete-alt')+'&nbsp;',]);
			$guid           = $object->guid;
			$title = "<a data-guid='$guid' data-qid='q{$guid}' data-qid_n='$qid_n' data-element='market' data-space='market' data-perspective='view' data-presentation='inline' data-context='market' class='do'>{$object->title}</a>";
			$content_items .= "<li class='compartment-component-item' data-guid='$guid'><span style='white-space:nowrap;'>$delete$title</span></li>";
		}
	}
	else {$compartment_class .= ' compartment-closed';}
	$labels      .= "<a href='#' class='$compartment_class' data-jq-dropdown='#$item_guid-compartment-2' data-count='$count' data-aspect='component'>components (<span>$count</span>)</a>";
	$compartment .= "<div id='$item_guid-compartment-2' class='jq-dropdown jq-dropdown-relative jq-dropdown-tip'>
					    <ul class='jq-dropdown-menu' data-aspect='component'>
			                <li>components</li>
			                $content_items
			            </ul>
	                 </div>";
	
	unset($compartment_class, $content_items, $count);
	$count          = 0;
	$compartment_class   = 'compartment-accessories-header';
	if ($accessories){
		$compartment_class .= ' compartment-open';
		$count = count($accessories);
		foreach($accessories as $key=>$object){
			unset($guid, $title, $qid_n);
			$qid_n  = "q{$guid}_043";
			$delete = elgg_view('output/url', ['data-qid_n' => $qid_n,
		                           'title'=>'remove content',
							       'class'=>'remove-node',
							       'style'=> 'cursor:pointer',
							       'text' => elgg_view_icon('delete-alt')+'&nbsp;',]);
			$guid           = $object->guid;
			$title = "<a data-guid='$guid' data-qid='q{$guid}' data-qid_n='$qid_n' data-element='market' data-space='market' data-perspective='view' data-presentation='inline' data-context='market' class='do'>{$object->title}</a>";
			$content_items .= "<li class='compartment-accessory-item' data-guid='$guid'><span style='white-space:nowrap;'>$delete$title</span></li>";
		}
	}
	else {$compartment_class .= ' compartment-closed';}
	$labels      .= "<a href='#' class='$compartment_class' data-jq-dropdown='#$item_guid-compartment-3' data-count='$count' data-aspect='accessory'>accessories (<span>$count</span>)</a>";
	$compartment .= "<div id='$item_guid-compartment-3' class='jq-dropdown jq-dropdown-relative jq-dropdown-tip'>
					    <ul class='jq-dropdown-menu' data-aspect='accessory'>
					    	<li>accessories</li>
					    	$content_items
		                </ul>
	                 </div>";
	$view_elements .= "<div class='quebx-compartment-labels' data-boqx-guid='$item_guid'>$labels</div>";
	$view_elements .= "<div class='quebx-compartments' data-boqx-guid='$item_guid'>$compartment</div>";
}
if ($include_dropbox){
	    $dropbox = "<div id='qbox-pack-$item_guid' class='dropbox'>
	    				 <div class='dropbox-panel qbox-pallet' data-boqx-guid=$item_guid >
		    				 <div class='qbox-drop' data-aspect='content'></div>
			    			 <div class='qbox-drop' data-aspect='accessory'></div>
			    			 <div class='qbox-drop' data-aspect='component'></div>
			    		  </div>
                     </div>";
	    $view_elements .= $dropbox;
}

echo $view_elements;