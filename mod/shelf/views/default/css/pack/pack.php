<?php
?>
#box-button-wrapper.box{
	display: inline-block;
	position: relative;
	background: url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif) repeat-x scroll 0 0 #CCCCCC;
    border: 1px solid #999999;
    border-radius: 5px 5px 5px 5px;
    box-shadow: none;
    color: #333333;
    cursor: pointer;
    font-weight: bold;
    text-align: center;
    text-decoration: none;
    text-shadow: 0 1px 0 white;
    font-size: 14px;
}

#box-button-wrapper .swfupload {
	left: 0;
}

.box-queue-item {
	background-color: #EEEEEE;
	
	border: 1px solid #CCCCCC;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	
	font: 11px Verdana, Geneva, sans-serif;
	
	margin-top: 5px;
	max-width: 350px;
	padding: 10px;
}

.box-error {
	background-color: #FDE5DD !important;
}

.box-queue-item .cancel a {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat 0 -234px;
	width: 16px;
	height: 16px;
	margin: 0 2px;
	
	float: right;
	text-indent: -9999px;
}
.box-queue-item .cancel a:hover {
	background-position: 0 -216px;
}

.box-queue-item.completed {
	background-color: #E5E5E5;
}

.box-progress {
	background-color: #E5E5E5;
	margin-top: 10px;
	width: 100%;
}

.box-progress-bar {
	background-color: #0099FF;
	height: 3px;
	width: 1px;
}