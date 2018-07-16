<?php

require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/engine/start.php');

$category_guid = get_input('category_guid');
$category = get_entity($category_guid);

if (isadminloggedin () && get_context() !== 'groups') {
    add_submenu_item(elgg_echo('hypeCategories:admin:submenu'), $CONFIG->wwwroot . 'mod/hypeCategories/manage.php', 'hype');
}
$container_guid = $category->container_guid;
$container = get_entity($container_guid);

$context = get_input('context');
if ($context == 'site') {
    $body = elgg_view_entity($category, 'full');
    $body = elgg_view_layout('two_column_left_sidebar', $area1, $body);

    //@EDIT - 2017-10-01 - SAJ - Function call: page_draw (deprecated since 1.8) Use elgg_view_page()
    //page_draw(elgg_echo('hypeCategories:admin:manager'), $body);
    elgg_view_page(elgg_echo('hypeCategories:admin:manager'), $body);
} elseif ($context == 'groups') {
    set_page_owner($container_guid);
    set_context('groups');
    $body = elgg_view_entity($category, 'full');
    $body = elgg_view_layout('two_column_left_sidebar', $area1, $body);

    //@EDIT - 2017-10-01 - SAJ - Function call: page_draw (deprecated since 1.8) Use elgg_view_page()
    //page_draw(elgg_echo('hypeCategories:admin:manager'), $body);
    elgg_view_page(elgg_echo('hypeCategories:admin:manager'), $body);
} else {
    $body = elgg_view_entity($category, 'full');
    $body = elgg_view_layout('two_column_left_sidebar', $area1, $body);

    //@EDIT - 2017-10-01 - SAJ - Function call: page_draw (deprecated since 1.8) Use elgg_view_page()
    //page_draw('', $body);
    elgg_view_page('', $body);
}

?>
