<?php

?>
.item-tools-icon-tiny {
	width: 20px;
	height: 20px;
}

.elgg-menu-item-tools-folder-breadcrumb > li:after {
	padding: 0 4px;
	content: ">";
}

#item_tools_list_items_container {
	position: relative;
}

#item_tools_list_items_container .elgg-ajax-loader {
	background-color: white;
	opacity: 0.85;
	width: 100%;
	height: 100%;
	position: absolute;
	top: 0;
}

#item_tools_list_items .ui-draggable,
.item-tools-item.ui-draggable {
	cursor: move;
	background: white;
}

#item-tools-folder-tree .item-tools-tree-droppable-hover {
	border: 1px solid red;
}

#item-tools-multi-form .uploadify-queue-item {
	max-width: 100%;
}