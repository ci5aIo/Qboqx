<?php
/**
 * Menu button
 * 
 */
?>

<div class="landr-menu"> 
    <a href="#landr-menu"><i class="fa fa-bars fa-2x"></i></a>
</div>

<div class="landr-menu-horizontal">
	<?php echo elgg_view_menu('site'); ?>
</div>

<div class="remodal" data-remodal-id="landr-menu">
	<?php echo elgg_view_menu('site'); ?>
</div>