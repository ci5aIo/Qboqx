<?php
/**
 * Aalborg theme navbar
 * 
 */

$url = sslCheck();
$elgg = elgg_get_site_url();

if (!elgg_is_active_plugin('aalborg_theme') || fnmatch($elgg, $url)) {

// drop-down login
echo elgg_view('core/account/login_dropdown');

?>

<a class="elgg-button-nav" rel="toggle" data-toggle-selector=".elgg-nav-collapse" href="#">
	<?= elgg_view_icon('bars'); ?>
</a>

<div class="elgg-nav-collapse">
	<?php echo elgg_view_menu('site'); ?>
</div>

<?php }