<?php

$folders = elgg_extract("folders", $vars);
$folder = elgg_extract("folder", $vars);

$selected_id = "item_tools_list_tree_main";
if ($folder instanceof ElggObject) {
	$selected_id = $folder->getGUID();
}

$page_owner = elgg_get_page_owner_entity();
$site_url = elgg_get_site_url();

// load JS
elgg_load_js("jquery.tree");
elgg_load_css("jquery.tree");

elgg_load_js("jquery.hashchange");
?>
<script type="text/javascript">
	function item_tools_get_selected_tree_folder_id(){
		var result = 0;

		tree = jQuery.tree.reference($("#item_tools_list_tree"));
		result = item_tools_tree_folder_id(tree.selected);
		return result;
	}

	function item_tools_remove_folder_files(link) {
		if (confirm("<?php echo elgg_echo("item_tools:folder:delete:confirm_files");?>")) {
			var cur_href = $(link).attr("href");
			$(link).attr("href", cur_href + "&files=yes");
		}
		return true;
	}
	
	function item_tools_tree_folder_id(node, parent) {
		if(parent == true) {
			var find = "a:first";
		} else {
			var find = "a";
		}
		
		var element_id = node.find(find).attr("id");
		return element_id.substring(24, element_id.length);
	}
	
	function item_tools_select_node(folder_guid, tree) {
		tree = jQuery.tree.reference($("#item_tools_list_tree"));
		
		tree.select_branch($("#item_tools_tree_element_" + folder_guid));
		tree.open_branch($("#item_tools_tree_element_" + folder_guid));
	}
	
	$(function() {
		<?php if (item_tools_use_folder_structure()) { ?>
		if (window.location.hash.substring(1) == '') {
			elgg.item_tools.load_folder(0);
		}

		$(window).hashchange(function(){
			elgg.item_tools.load_folder(window.location.hash.substring(1));
		});
		
		$("a[href*='item_tools/file/new'], a[href*='item_tools/import/zip']").on("click", function(e) {
			var link = $(this).attr('href');
		
			window.location = link + '?folder_guid=' + item_tools_get_selected_tree_folder_id();
			e.preventDefault();
	        
		});
		<?php }?>
	
		$('.item_tools_load_folder').on('click', function() {
			folder_guid = $(this).attr('rel');
			item_tools_select_node(folder_guid);
		});
	
		$('select[name="file_sort"], select[name="file_sort_direction"]').change(function() {
			item_tools_show_loader($("#item_tools_list_folder"));
			var folder_url = "<?php echo $site_url;?>item_tools/list/<?php echo elgg_get_page_owner_guid();?>?folder_guid=" + item_tools_get_selected_tree_folder_id() + "&search_viewtype=<?php echo get_input("search_viewtype", "list"); ?>&sort_by=" + $('select[name="file_sort"]').val() + "&direction=" + $('select[name="file_sort_direction"]').val();
			$("#item_tools_list_items_container").load(folder_url);
		});
	
		$('a#item_tools_action_bulk_download').click(function() {
			checkboxes = $('input[name="item_tools_file_action_check"]:checked');
			
			if (checkboxes.length) {
				data = [];
				$.each($('input[name="item_tools_file_action_check"]:checked'), function(i, value) {
					data.push($(value).val());
				});
	
				window.location = '<?php echo $site_url; ?>item_tools/file/download?guids=' + data.join('-');
			} else {
				alert('<?php echo elgg_echo("item_tools:list:alert:none_selected"); ?>');
			}
		});
	});

	function item_tools_show_loader(elem) {
		var overlay_width = elem.outerWidth();
		var margin_left = elem.css("margin-left");
			
		$("#item_tools_list_items_overlay").css("width", overlay_width).css("left", margin_left).show();
	}
	
	$(function () {
		<?php if ($page_owner->canEdit() || ($page_owner instanceof ElggGroup && $page_owner->isMember())) { ?>
		$("#item_tools_list_tree a").droppable({
			"accept": ".item_tools_file",
			"hoverClass": "ui-state-hover",
			"tolerance": "pointer",
			"drop": function(event, ui) {
	
				var file_move_url = "<?php echo $site_url;?>item_tools/proc/file/move";
				var file_guid = $(ui.draggable).prev("input").val();
				if(file_guid == undefined) {
					file_guid = $(ui.draggable).attr('id').replace('file_','');
				}
				var folder_guid = $(this).attr("id");
				var selected_folder_guid = item_tools_get_selected_tree_folder_id();

				item_tools_show_loader($(ui.draggable));
				
				$(ui.draggable).hide();
				
				$.post(file_move_url, {"file_guid": file_guid, "folder_guid": folder_guid}, function(data) {
					elgg.item_tools.load_folder(selected_folder_guid);
				});
			},
			"greedy": true
		});
		<?php } ?>
	});

</script>

<?php

$body = "<div id='item-tools-folder-tree' class='clearfix hidden'>";
$body .= elgg_view_menu("item_tools_folder_sidebar_tree", array(
	"container" => $page_owner,
	"sort_by" => "priority"
));
$body .= "</div>";

if ($page_owner->canEdit() || ($page_owner instanceof ElggGroup && $page_owner->isMember() && $page_owner->item_tools_structure_management_enable != "no")) {
	elgg_load_js("lightbox");
	elgg_load_css("lightbox");
	
	$body .= "<div class='mtm'>";
	$body .= elgg_view("input/button", array("value" => elgg_echo("item_tools:new:title"), "id" => "item_tools_list_new_folder_toggle", "class" => "elgg-button-action"));
	$body .= "</div>";
}

// output file tree
echo elgg_view_module("aside", "", $body, array("id" => "item_tools_list_tree_container"));
