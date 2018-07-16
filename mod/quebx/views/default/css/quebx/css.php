<?php
/**
 * Elgg Market Plugin
 * @author slyhne
 */

?>
.quebx-page-box .quebx-page-header > .quebx-inner {
    width: 500px;
    margin: 0px auto;
    height: 500px;
}

.quebx_pricetag {
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #ffffff;
	background:#00a700;
	border: 1px solid #00a700;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	width: auto;
	height: 12px;
	padding: 2px 10px 2px 10px;
	margin:10px 0 10px 0;
}

/*CSS code for class hoverhelp:*/

.hoverhelp       {border-bottom: thin dotted; border-top: thin dotted; background: #ffeedd;}
.hoverhelp span  {position: absolute; left: -9999px;
                  margin: 4px 0 0 0px; padding: 3px 3px 3px 3px; 
                  border-style:solid; border-color:black; border-width:1px;}
.hoverhelp:hover {text-decoration: none; background: #F0F0EE; z-index: 6; }
.hoverhelp:hover span {margin: 20px 0 0 170px; background: #ffeedd; z-index:6;} 

/*CSS code for class hoverinfo:*/

.hoverinfo {
	<!-- background: #ffeedd; -->
	width:200px;
}
.hoverinfo:hover {
	text-decoration: none;
	text-shadow: none;
	z-index: 6; 
}
.hoverinfo span  {
	position: absolute; 
	left: -9999px;
	padding: 3px 3px 3px 3px;
	margin: 4px 0 0 0px; 
	border-style:solid; 
	border-color:black; 
	border-width:1px; 
	z-index:6;
}
.hoverinfo:hover span {
	position:absolute;
	left: -160px; 
	<!-- top: 15px; -->
	background: #d5e3f1;
	margin: 20px 0 0 170px; 
	background: #d5e3f1; 
	font: 10px/100% Arial, Helvetica, sans-serif;
} 

/* Featured */
/*
.elgg-module-featured {
	border: 1px solid #4690D6;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
	border-radius: 6px;
}*/
.elgg-module-featured {
	border: 0px solid #00a700;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: none;
}
.elgg-module-featured > .elgg-head {
	padding: 5px;
	background-color: #4690D6;
}
.elgg-module-featured > .elgg-head * {
	color: white;
}
.elgg-module-featured > .elgg-body {
	padding: 10px;
}

/*
 * Section style adapted from mod\resume
 */

.section h3{
margin:25;
}

.section_collapsible_box {
	background:none;
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	padding:2px;
	margin:0px;
	display:block;
}

.section_collapsibleboxlink {
	float:right;
    font-size:18px;
    position:relative;
    bottom:8px;
}

.section_collapsible_box_hidden {
	background:none;
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	padding:2px;
	margin:0px;
	display:hidden;
}

.section_contentWrapper {
margin:0 10px 4px;
padding:0px 10px;
}

/*overrides core*/
.elgg-menu-entity, .elgg-menu-annotation {
	height: 20px;
}
.elgg-categories > li a{
	color: #aaa;
}
.elgg-list-container > .elgg-pagination{
	display:block;
}

.hover-change .fa +  .fa,
.hover-change:hover > .fa {
  display: none;
}
.hover-change:hover > .fa +  .fa {
  display: inherit;
}