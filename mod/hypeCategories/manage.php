<?php

require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

admin_gatekeeper();
set_context('admin');

$body = elgg_view('hypeCategories/admin/manager');
$body = elgg_view_layout('two_column_left_sidebar', '', $body);

//@EDIT - 2017-10-01 - SAJ - Function call: page_draw (deprecated since 1.8) Use elgg_view_page()
//page_draw(elgg_echo('hypeCategories:admin:manager'), $body);
elgg_view_page(elgg_echo('hypeCategories:admin:manager'), $body);

?>