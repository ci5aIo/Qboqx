<?php
namespace hypeJunction\Categories;
elgg_load_js('jquery.nestedsortable.js');
$container = elgg_extract('container', $vars, elgg_get_site_entity());
elgg_push_context('categories-manage');
echo '<div class="categories-manage">';