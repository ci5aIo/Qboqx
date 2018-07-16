<?php
/**
 * Elgg Market Plugin
 * @author slyhne
 */

?>
.market-photo {
	text-align: center;
	margin-bottom: 15px;
}
.market-gallery-item {
	text-align: center;
	width: 165px;
}

.market_pricetag {
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
.market-nav.treeview ul {
	background-color: transparent;
}

.market-nav.treeview a.selected {
	color: #555555;
}

.market-nav.treeview .hover {
	color: #0054a7;
}


//CSS code for class hoverhelp:

.hoverhelp       {border-bottom: thin dotted; border-top: thin dotted; background: #ffeedd;}
.hoverhelp:hover {text-decoration: none; background: #F0F0EE; z-index: 6; }
.hoverhelp span  {position: absolute; left: -9999px;
                  margin: 20px 0 0 0px; padding: 3px 3px 3px 3px;
                  border-style:solid; border-color:black; border-width:1px; z-index: 6;}
.hoverhelp:hover span {left: 2%; background: #ffeedd;} 
.hoverhelp span  {position: absolute; left: -9999px;
                  margin: 4px 0 0 0px; padding: 3px 3px 3px 3px; 
                  border-style:solid; border-color:black; border-width:1px;}
.hoverhelp:hover span {margin: 20px 0 0 170px; background: #ffeedd; z-index:6;} 

/*.highlight       {background: #ffeedd;}*/
.highlight:hover {background: #F0F0EE}

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

