<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

elgg_load_js('lightbox');
elgg_load_css('lightbox');

elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
elgg_push_breadcrumb(elgg_echo('market:add'));

if (elgg_get_plugin_setting('market_adminonly', 'market') == 'yes') {
	admin_gatekeeper();
}

// How many classifieds can a user have
$marketmax = elgg_get_plugin_setting('market_max', 'market');
if(!$marketmax){
	$marketmax == 0;
}
//count_user_objects() was deprecated in favor of elgg_get_entities()
//$marketactive = count_user_objects(elgg_get_logged_in_user_guid(), 'market');
$marketactive = elgg_get_entities(elgg_get_logged_in_user_guid(), 'market');

$title = elgg_echo('market:add:title');

$form_vars = array('name' => 'marketForm', 'enctype' => 'multipart/form-data');
// set up sticky form

$body_vars = array(
    'markettitle' => NULL,
    'marketbody' => NULL,
    'markettags' => NULL,
    'marketcategory' => NULL,
    'access_id' => ACCESS_PUBLIC,
);
if (elgg_is_sticky_form('market')) {
		$sticky_values = elgg_get_sticky_values('market');
		foreach ($sticky_values as $key => $value) {
			$body_vars[$key] = $value;
		}
}

elgg_clear_sticky_form('market');

// Show form, or error if users has used his quota
if ($marketmax == 0 || elgg_is_admin_logged_in()) {
	$content = elgg_view_form("market/edit", $form_vars, $body_vars);
} elseif ($marketmax > $marketactive) {
	$content = elgg_view_form("market/edit", $form_vars, $body_vars);
} else {
	$content = elgg_view("market/error");
}

// Show market sidebar
$sidebar = elgg_view("market/sidebar");

$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		);

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
