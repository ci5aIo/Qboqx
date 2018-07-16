<?php
/**
 * Edit an issue
 *
 * @package ElggPages
 */

gatekeeper();

$issue_guid = (int)get_input('guid');
$issue = get_entity($issue_guid);
if (!$issue) {
	register_error(elgg_echo('noaccess'));
//	Referrer;
	forward('');
}

$container = $issue->getContainerEntity();
if (!$container) {
	register_error(elgg_echo('noaccess'));
//	Referrer;
    forward('');
}

elgg_set_page_owner_guid($container->getGUID());

elgg_push_breadcrumb($issue->title, $issue->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo("issues:edit");

if ($issue->canEdit()) {
	$vars = issues_prepare_form_vars($issue);
	$content = elgg_view_form('issues/edit', array(), $vars);
} else {
	$content = elgg_echo("issues:noaccess");
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
