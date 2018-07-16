<?php 
/**
 * Custom Content module
 *
 */
$mod_path = elgg_get_site_url().'market/views/default/market/sidebar/navigation';
$title = elgg_echo("market:mine"); 
//$body = $mod_path;
$body = elgg_view($mod_path);

echo elgg_view_module('aside', $title, $body);
echo "Doesn't work: $mod_path";
?>

