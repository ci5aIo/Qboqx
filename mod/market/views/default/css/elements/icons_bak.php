<?php
/**
 * Market icons
 *
 * @package Quenbx.Core
 * @subpackage UI
 */

?>

/* ***************************************
	ICONS
*************************************** */

.elgg-icon {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left;
	width: 16px;
	height: 16px;
	margin: 0 2px;
}

/*
 * Enhancement to Elgg.Core icons
 */
.elgg-icon-unlink-hover,
.elgg-icon-unlink:hover,
:focus > .elgg-icon-unlink {
	background-position: 0 -1530px;
}
.elgg-icon-unlink {
	background-position: 0 -1549px;
}
.elgg-icon-docedit-hover,
.elgg-icon-docedit:hover,
:focus > .elgg-icon-docedit {
	background-position: 0 -1567px;
}
.elgg-icon-docedit {
	background-position: 0 -1585px;
}
