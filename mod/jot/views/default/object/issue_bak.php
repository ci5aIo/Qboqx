<?php
echo 'pages: mod\jot\pages\issues\view.php<br>';
echo 'views: mod\jot\views\default\object\issue.php';

$full = elgg_extract('full_view', $vars, FALSE);
$issue = $vars['entity'];
$item = $issue['asset'];
$owner = $issue->getOwnerEntity();
$tu = $issue->time_updated;
$container = $issue->getContainerEntity();
$jot = elgg_get_excerpt($issue->description);
$owner_link = elgg_view('output/url', array(
	'href' => "jot/owned/{$owner->username}",
	'text' => $owner->name,
));

$tags = elgg_view('output/tags', array('tags' => $issue->tags));
$date = elgg_view_friendly_time($issue->time_created);
$comments_count = $issue->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $issue->getURL() . '#jot-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $issue,
//	'entity' => $vars['entity'],
	'handler' => 'jot',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));
//echo elgg_dump($vars);
$issues = array();

$issues[] = array(
	'label'=>'Title',
	'field' => $issue->title
);	
$issues[] = array(
	'label'=>'Description',
	'field' => $issue->description
);	
$issues[] = array(
	'label'=>'Status',
	'field' => $issue->status
);	
$issues[] = array(
	'label'=>'Issue #',
	'field' => $issue->number
);	
$issues[] = array(
	'label'=>'Asset',
	'field' => get_entity($issue->asset)->title
);	
$issues[] = array(
	'label'=>'Tags',
	'field' => $issue->tags
);	

	$body = "<table border='0' width='100%'><tr>
	         <td width='220px'><center>";
	$body .= elgg_view('output/url', array(
	                                           //jot/viewimage.php not available on site.  
			'href' => elgg_get_site_url() . "mod/jot/viewimage.php?jotguid={$issue->guid}",
			'text' => elgg_view('jot/thumbnail', array('jotguid' => $issue->guid, 'size' => 'large', 'tu' => $tu)),
			'class' => "elgg-lightbox",
			));
	$body .= "</center></td><td>";
	foreach ($issues as $issue) {
  $body .= "<p><b>{$issue['label']}:</b> {$issue['field']}</p>";
}
	if ($allowhtml != 'yes') {
		$body .= elgg_autop(parse_urls(strip_tags($issue->description)));
	} else {
		$body .= elgg_view('output/longtext', array('value' => $issue->description));
	}
	$body .= elgg_view('jot/display_level_info',array('entity'=>$issue));
	$body .= "</td></tr><tr>
	          <td><center>";
    $body .= $value;
//	$body .= "<span class='jot_pricetag'><b>" . elgg_echo('jot:price') . "</b> {$currency}{$issue->price}</span>";
	$body .= "</center></td><td><center>";
	if (elgg_get_plugin_setting('jot_pmbutton', 'jot') == 'yes') {
		if ($owner->guid != elgg_get_logged_in_user_guid()) {
			$body .= elgg_view('output/url', array(
							'class' => 'elgg-button elgg-button-action',
							'href' => "messages/compose?send_to={$owner->guid}",
							'text' => elgg_echo('jot:pmbuttontext'),
							));
		}
	}
	$body .= "</center></td></tr></table>";

	$subtitle = "{$category}{$custom}{$supplemental}{$pick}<br>{$author_text} {$date} {$comments_link}";

	$params = array(
//		'entity' => $issue,
		'header' => $header,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);

	$owner_icon = elgg_view_entity_icon($owner, 'small');

	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	$issue_info = elgg_view_image_block($owner_icon, $list_body);

echo <<<HTML
$issue_info
<div class="jot elgg-content">
	$body
</div>
HTML;
