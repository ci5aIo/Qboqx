<?php

?>
//<script>
elgg.provide("elgg.item_tools");
elgg.provide("elgg.item_tools.uploadify");
elgg.provide("elgg.item_tools.tree");

// extend jQuery with a function to serialize to JSON
(function( $ ){
	$.fn.serializeJSON = function() {
		var json = {};
		jQuery.map($(this).serializeArray(), function(n, i){
			if (json[n['name']]){
				if (!json[n['name']].push) {
					json[n['name']] = [json[n['name']]];
				}
				json[n['name']].push(n['value'] || '');
			} else {
				json[n['name']] = n['value'] || '';
			}
		});
		return json;
	};
})( jQuery );

elgg.item_tools.uploadify.init = function(){
	$uploadifyButton = $('#uploadify-button-wrapper');

	if($uploadifyButton.length){
		$('#item-tools-uploadify-cancel').on("click", elgg.item_tools.uploadify.cancel);
		$('#item-tools-multi-form').submit(elgg.item_tools.uploadify.upload);
		
		$uploadifyButton.uploadify({
			swf: "<?php echo elgg_get_site_url(); ?>mod/item_tools/vendors/uploadify/uploadify.swf",
			uploader: "<?php echo elgg_get_site_url(); ?>mod/item_tools/procedures/upload/multi.php",
			formData: {"X-Requested-With": "XMLHttpRequest"},
			buttonText: elgg.echo("item_tools:forms:browse"),
			queueID: "uploadify-queue-wrapper",
			fileTypeExts: "<?php echo item_tools_allowed_extensions(true); ?>",
			fileSizeLimit: "<?php echo item_tools_get_readable_file_size_limit(); ?>",
			fileObjName: "upload",
			height: "23",
			width: "120",
			auto: false, 
			onQueueComplete: function(queueData){
				var folder = $('#item_tools_file_parent_guid').val();
				
				var forward_location = item_tools_uploadify_return_url + "#";
				if(folder > 0){
					forward_location += folder;
				}
				
				document.location.href = forward_location;
			},
			onUploadStart: function(file){
				
				$('#uploadify-button-wrapper').uploadify("settings", "formData", $('#item-tools-multi-form').serializeJSON());
			},
			onUploadSuccess: function(file, data, response){
				data = $.parseJSON(data);
				
				if(data && data.system_messages){
					elgg.register_error(data.system_messages.error);
					elgg.system_message(data.system_messages.success);
				}
			},
			onUploadError: function(file, data, response){
				data = $.parseJSON(data);
				
				if(data && data.system_messages){
					elgg.register_error(data.system_messages.error);
					elgg.system_message(data.system_messages.success);
				}
			},
			onSelect: function(instance) {
	           $("#item-tools-uploadify-cancel").removeClass("hidden");
	        },
	        onClearQueue: function(queueItemCount){
	        	$("#item-tools-uploadify-cancel").addClass("hidden");
	        }
		});
	}
}

elgg.item_tools.uploadify.cancel = function(){
	$('#uploadify-button-wrapper').uploadify("cancel", "*");
}

elgg.item_tools.uploadify.upload = function(event){
	$('#uploadify-button-wrapper').uploadify("upload", "*");
	
	return false;
}

elgg.item_tools.tree.init = function(){
	$tree = $('#item-tools-folder-tree');

	if($tree.length){
		$tree.tree({
			rules: {
				multiple: false,
				drag_copy: false,
				valid_children : [ "root" ]
			},
			ui: {
				theme_name: "classic"
			},
			callback: {
				onload: function(tree){
					var hash = window.location.hash;

					if(hash){
						tree.select_branch($tree.find('a[href="' + hash + '"]'));
						tree.open_branch($tree.find('a[href="' + hash + '"]'));

						var folder_guid = hash.substr(1);
					} else {
						tree.select_branch($tree.find('a[href="#"]'));
						tree.open_branch($tree.find('a[href="#"]'));

						var folder_guid = 0;
					}

					elgg.item_tools.load_folder(folder_guid);
					
					$tree.show();
				},
				onselect: function(node, tree){
					var hash = $(node).find('a:first').attr("href").substr(1);

					window.location.hash = hash;
				},
				onmove: function(node, ref_node, type, tree_obj, rb){
					var parent_node = tree_obj.parent(node);

					var folder_guid = $(node).find('a:first').attr('href').substr(1);
					var parent_guid = $(parent_node).find('a:first').attr('href').substr(1);
										
					var order = [];
					$(parent_node).find('>ul > li > a').each(function(k, v){
						var guid = $(v).attr('href').substr(1);
						order.push(guid);
					});

					if(parent_guid == window.location.hash.substr(1)){
						$("#item_tools_list_items_container .elgg-ajax-loader").show();
					}
					
					elgg.action("item_tools/folder/reorder", {
						data: {
							folder_guid: folder_guid,
							parent_guid: parent_guid,
							order: order
						},
						success: function(){
							if(parent_guid == window.location.hash.substr(1)){
								elgg.item_tools.load_folder(parent_guid);
							}
						}
					});
				}
			}
		}).find("a").droppable({
			accept: "#item_tools_list_items .item-tools-item",
			hoverClass: "item-tools-tree-droppable-hover",
			tolerance: "pointer",
			drop: function(event, ui){
				droppable = $(this);
				draggable = ui.draggable;

				drop_id = droppable.attr("href").substring(1);
				drag_id = draggable.parent().attr("id").split("-").pop();

				elgg.item_tools.move_file(drag_id, drop_id, draggable);
			}
		});
	}
}

elgg.item_tools.breadcrumb_click = function(event) {
	var href = $(this).attr("href");
	var hash = elgg.parse_url(href, "fragment");

	if(hash){
		window.location.hash = hash;
	} else {
		window.location.hash = "#";
	}

	event.preventDefault();
}

elgg.item_tools.load_folder = function(folder_guid){
	var query_parts = elgg.parse_url(window.location.href, "query", true);
	var search_type = 'list';
	
	if(query_parts && query_parts.search_viewtype){
		search_type = query_parts.search_viewtype;
	}
	
	var url = elgg.get_site_url() + "item_tools/list/" + elgg.get_page_owner_guid() + "?folder_guid=" + folder_guid + "&search_viewtype=" + search_type;

	$("#item_tools_list_items_container .elgg-ajax-loader").show();
	$("#item_tools_list_items_container").load(url, function(){
		var new_add_link = elgg.get_site_url() + "file/add/" + elgg.get_page_owner_guid() + "?folder_guid=" + folder_guid;
		$('ul.elgg-menu-title li.elgg-menu-item-add a').attr("href", new_add_link);

		var new_zip_link = elgg.get_site_url() + "file/zip/" + elgg.get_page_owner_guid() + "?folder_guid=" + folder_guid;
		$('ul.elgg-menu-title li.elgg-menu-item-zip-upload a').attr("href", new_zip_link);
	});
}

elgg.item_tools.move_file = function(file_guid, to_folder_guid, draggable){
	elgg.action("file/move", {
		data: {
			file_guid: file_guid, 
			folder_guid: to_folder_guid
		},
		error: function(result){
			var hash = elgg.parse_url(window.location.href, "fragment");

			if(hash){
				elgg.item_tools.load_folder(hash);
			} else {
				elgg.item_tools.load_folder(0);
			}
		},
		success: function(result){
			draggable.parent().remove();
		}
	});
}

elgg.item_tools.select_all = function(e){
	e.preventDefault();

	if($(this).find("span:first").is(":visible")){
		// select all
		$('#item_tools_list_items input[type="checkbox"]').attr("checked", "checked");
	} else {
		// deselect all
		$('#item_tools_list_items input[type="checkbox"]').removeAttr("checked");
	}

	$(this).find("span").toggle();
}

elgg.item_tools.bulk_delete = function(e){
	e.preventDefault();

	$checkboxes = $('#item_tools_list_items input[type="checkbox"]:checked');

	if($checkboxes.length){
		if(confirm(elgg.echo("deleteconfirm"))) {
			var postData = $checkboxes.serializeJSON();

			if($('#item_tools_list_items input[type="checkbox"][name="folder_guids[]"]:checked').length && confirm(elgg.echo("item_tools:folder:delete:confirm_files"))){
				postData.files = "yes";
			}

			$("#item_tools_list_items_container .elgg-ajax-loader").show();
			
			elgg.action("file/bulk_delete", {
				data: postData,
				success: function(res){
					$.each($checkboxes, function(key, value){
						$('#elgg-object-' + $(value).val()).remove();
					});

					$("#item_tools_list_items_container .elgg-ajax-loader").hide();
				}
			});
		}
	}
}

elgg.item_tools.bulk_download = function(e){
	e.preventDefault();

	$checkboxes = $('#item_tools_list_items input[type="checkbox"]:checked');

	if($checkboxes.length){
		elgg.forward("file/bulk_download?" + $checkboxes.serialize());
	}
}

elgg.item_tools.new_folder = function(event){
	event.preventDefault();

	var hash = window.location.hash.substr(1);
	var link = elgg.get_site_url() + "item_tools/folder/new/" + elgg.get_page_owner_guid() + "?folder_guid=" + hash;
	
	$.fancybox({
		href: link,
		titleShow: false
	});
}

elgg.item_tools.upload_tab_click = function(event) {
	event.preventDefault();

	$('#item-tools-upload-tabs .elgg-state-selected').removeClass("elgg-state-selected");
	$(this).parent().addClass("elgg-state-selected");

	var id = $(this).attr("id").replace("-link", "");
	$('#item-tools-upload-wrapper form').hide();
	$('#' + id).show();
}

elgg.item_tools.init = function(){
	// uploadify functions
	elgg.item_tools.uploadify.init();

	// upload functions
	$('#item-tools-upload-tabs a').on("click", elgg.item_tools.upload_tab_click);
	
	// tree functions
	elgg.item_tools.tree.init();
	
	$('#item_tools_breadcrumbs a').on("click", elgg.item_tools.breadcrumb_click);
	$('#item_tools_select_all').on("click", elgg.item_tools.select_all);
	$('#item_tools_action_bulk_delete').on("click", elgg.item_tools.bulk_delete);
	$('#item_tools_action_bulk_download').on("click", elgg.item_tools.bulk_download);

	$('#item_tools_list_new_folder_toggle').on('click', elgg.item_tools.new_folder);
}

// register init hook
elgg.register_hook_handler("init", "system", elgg.item_tools.init);