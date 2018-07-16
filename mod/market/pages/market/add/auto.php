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

 /*Experimental - begin
  * Adapted from pages/new.php
  */
		gatekeeper();
		
		$container_guid = (int) get_input('guid');
		$container = get_entity($container_guid);
		if (!$container) {
		
		}
		
		$parent_guid = 0;
		$item_owner = $container;
		if (elgg_instanceof($container, 'object')) {
			$parent_guid = $container->getGUID();
			$item_owner = $container->getContainerEntity();
		}
		
//		elgg_set_page_owner_guid($item_owner->getGUID());
		
//		$title = elgg_echo('pages:add');
//		elgg_push_breadcrumb($title);
		
		$vars = quebx_prepare_form_vars(null, $parent_guid);
		$content = elgg_view_form('pages/edit', array(), $vars);
		
		$body = elgg_view_layout('content', array(
			'filter' => '',
			'content' => $content,
			'title' => $title,
		));
		
//		echo elgg_view_page($title, $body);
 
//Experimental - end

elgg_load_js('lightbox');
elgg_load_css('lightbox');

elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
elgg_push_breadcrumb(elgg_echo('market:auto'), "market/category/auto");

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

$title = elgg_echo('market:auto:title');

// Show form, or error if users has used his quota
if ($marketmax == 0 || elgg_is_admin_logged_in()) { 
	$form_vars = array('name' => 'marketForm', 'js' => 'onsubmit="acceptTerms();return false;"', 'enctype' => 'multipart/form-data');
//	$content = quebx_view_category_form("add","auto", $form_vars);
	$content = elgg_view_form("market/add/auto", $form_vars);
} elseif ($marketmax > $marketactive) { 
	$form_vars = array('name' => 'marketForm', 'js' => 'onsubmit="acceptTerms();return false;"', 'enctype' => 'multipart/form-data');
	$content = elgg_view_form("market/add/auto", $form_vars);
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
