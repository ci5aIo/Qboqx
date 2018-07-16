<?php
/*
 *
 * Elgg Pearl
 *
 * @package pearl
 * @author Clifton Barron - SocialApparatus
 * @license Commercial
 * @copyright Copyright (c) 2012, Clifton Barron
 *
 * @link http://socia.us
 *
 */

elgg_register_event_handler('init', 'system', 'pearl_init');

function pearl_init() {
$url = elgg_get_site_url();
    if (elgg_get_context() != 'admin') {
        elgg_register_css('pearl', $url . 'mod/pearl/css/pearl.css');
        elgg_register_css('navigation', $url . 'mod/pearl/css/navigation.css');
        elgg_register_css('layout', $url . 'mod/pearl/css/layout.css');
        elgg_register_css('ubuntu', 'http://fonts.googleapis.com/css?family=Ubuntu:400,300,300italic,400italic,500,500italic,700,700italic');
        elgg_register_css('oleo','http://fonts.googleapis.com/css?family=Oleo+Script+Swash+Caps:700');
        elgg_register_css('nivo-default', $url . 'mod/pearl/css/default.css');
        elgg_register_css('nivo', $url . 'mod/pearl/css/nivo-slider.css');
        elgg_load_css('pearl');
        elgg_load_css('navigation');
        elgg_load_css('ubuntu');
        elgg_load_css('oleo');
        elgg_load_css('layout');
    } else {
        elgg_unregister_css('pearl');
        elgg_unregister_css('ubuntu');
        elgg_unregister_css('oleo');
    }
    elgg_register_js('hoverintent', $url . 'mod/pearl/views/default/js/jquery.hoverIntent.minified.js');
    elgg_register_js('nivo', $url . 'mod/pearl/views/default/js/jquery.nivo.slider.js');
    elgg_register_js('pearl', $url . 'mod/pearl/views/default/js/pearl.js');
/* 2019-12-19 - SAJ - moved js folder to views/default    
    elgg_register_js('hoverintent', $url . 'mod/pearl/js/jquery.hoverIntent.minified.js');
    elgg_register_js('nivo', $url . 'mod/pearl/js/jquery.nivo.slider.js');
    elgg_register_js('pearl', $url . 'mod/pearl/js/pearl.js');
    elgg_extend_view('page/elements/header', 'pearl/usermenu', 0);
*/
/* 2017-08-31 - SJ - Removed to retain default elgg registration 
    elgg_unregister_js('jquery');
    elgg_unregister_js('jquery-ui');
*/
    elgg_register_css('jquery-ui', 'http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css');
    elgg_load_css('jquery-ui');
/* 2017-08-31 - SJ - Removed to retain default elgg registration 
    elgg_register_js('jquery', 'http://code.jquery.com/jquery-1.9.1.js', 'head');
    elgg_load_js('jquery');
    elgg_register_js('jquery-ui', 'http://code.jquery.com/ui/1.9.2/jquery-ui.js', 'head');
    elgg_load_js('jquery-ui');
*/
    elgg_load_js('hoverintent');
    elgg_load_js('pearl');
    elgg_register_plugin_hook_handler('head', 'page', 'my_theme_head');
    
    function my_theme_head($hook, $type, $return, $params) {
    	$return['links'][] = array(
    			'id' => 'favicon',
    			'rel' => 'shortcut icon',
    			'href' => elgg_normalize_url('mod/pearl/graphics/favicon.ico'),
    	);
    	$return['links'][] = array(
    			'rel' => 'apple-touch-icon',
    			'sizes' => '32x32',
    			'type' => 'image/png',
    			'href' => elgg_normalize_url('mod/pearl/graphics/favicon-32.png'),
    	);
    	
    	return $return;
    }
}

?>
