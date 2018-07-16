<?php
// 2018-06-05 - Stolen from file_tools/views/default/js/file_tools/site.php
?>
//<script>
elgg.provide("quebx.shelf_tools");
elgg.provide("quebx.shelf_tools.pack");

// extend jQuery with a function to serialize to JSON
(function( $ ) {
	$.fn.serializeJSON = function() {
		var json = {};
		jQuery.map($(this).serializeArray(), function(n, i) {
			if (json[n['name']]) {
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

quebx.shelf_tools.pack.init = function() {
    var shelf_exists = $('.shelf-list-items').length> 0;
    if (shelf_exists){
		var top = $('.shelf-list-items').offset().top - parseFloat($('.shelf-list-items').css('marginTop').replace(/auto/, 500));
    }
	
	$(window).scroll(function (event) {
		var y = $(this).scrollTop();
		if (y >= top) {
			$('.shelf-list-items').addClass('shelf-float');
			$('body').css('margin-top','90px');
		} else {
			$('.shelf-list-items').removeClass('shelf-float');
			$('body').css('margin-top','0');
		}
	});
	$packButton = $('#pack-button-wrapper');

	if ($packButton.length) {
		$('#quebx-pack-cancel').on("click", quebx.shelf_tools.pack.cancel);
		$('#quebx-multi-form').submit(quebx.shelf_tools.pack.box);
		
		$packButton.box({
			swf: quebx.normalize_url("mod/shelf/vendors/pack/pack.swf"),
			uploader: quebx.normalize_url("mod/file_tools/procedures/upload/multi.php"),
			formData: {"X-Requested-With": "XMLHttpRequest"},
			buttonText: quebx.echo("file_tools:forms:browse"),
			queueID: "box-queue-wrapper",
			height: "23",
			width: "120",
			auto: false,
			onQueueComplete: function(queueData) {
				var folder = $('#quebx_file_parent_guid').val();
				
				var forward_location = file_tools_uploadify_return_url + "#";
				if (folder > 0) {
					forward_location += folder;
				}
				
				document.location.href = forward_location;
			},
			onUploadStart: function(file) {
				
				$('#pack-button-wrapper').pack("settings", "formData", $('#quebx-multi-form').serializeJSON());
			},
			onUploadSuccess: function(file, data, response) {
				data = $.parseJSON(data);
				
				if (data && data.system_messages) {
					quebx.register_error(data.system_messages.error);
					quebx.system_message(data.system_messages.success);
				}
			},
			onUploadError: function(file, data, response) {
				data = $.parseJSON(data);
				
				if (data && data.system_messages) {
					quebx.register_error(data.system_messages.error);
					quebx.system_message(data.system_messages.success);
				}
			},
			onSelect: function(instance) {
	           $("#quebx-pack-cancel").removeClass("hidden");
	        },
	        onClearQueue: function(queueItemCount) {
	        	$("#quebx-pack-cancel").addClass("hidden");
	        }
		});
	}
}

quebx.shelf_tools.pack.cancel = function() {
	$('#pack-button-wrapper').pack("cancel", "*");
}

quebx.shelf_tools.pack.upload = function(event) {
	$('#pack-button-wrapper').pack("upload", "*");
	
	return false;
}


quebx.shelf_tools.load_box = function(box_guid) {
	var query_parts = quebx.parse_url(window.location.href, "query", true);
	var search_type = 'list';
	
	if (query_parts && query_parts.search_viewtype) {
		search_type = query_parts.search_viewtype;
	}
	
	var url = quebx.get_site_url() + "shelf/list/" + quebx.get_page_owner_guid() + "?box_guid=" + box_guid + "&search_viewtype=" + search_type;

	$("#shelf_tools_list_files_box .elgg-ajax-loader").show();
	$("#shelf_tools_list_files_box").load(url, function() {
		var new_add_link = quebx.get_site_url() + "file/add/" + quebx.get_page_owner_guid() + "?box_guid=" + box_guid;
		$('ul.elgg-menu-title li.elgg-menu-item-add a').attr("href", new_add_link);

		var new_zip_link = quebx.get_site_url() + "file/zip/" + quebx.get_page_owner_guid() + "?box_guid=" + box_guid;
		$('ul.elgg-menu-title li.elgg-menu-item-zip-upload a').attr("href", new_zip_link);
	});
}
                                      
quebx.shelf_tools.pack_item = function(item_guid, boqx_guid, boqx, item, aspect, container_guid) {
	console.log('+quebx.shelf_tools.pack_item');
	console.log("item_guid: "+item_guid);
	console.log("boqx_guid: "+boqx_guid);
	console.log("aspect: " +aspect);
	console.log('container_guid: '+container_guid);
	element = 'qbox';
	space = 'market';
	perspective = 'pack';
	elgg.action("shelf/pack", {
		data: {
			element: element,
	 		guid: item_guid,
	 		boqx_guid: boqx_guid,
			origin: 'shelf',
		    aspect: aspect,
	 		perspective: perspective
		},
		error: function(result) {
		},
		success: function(result) {
        	title            = item.find('.elgg-body').text();
        	link             = "<a data-guid='"+item_guid+"' data-qid='q"+item_guid+"' data-qid_n='q"+item_guid+"_0' data-element='market' data-space='"+space+"' data-perspective='view' data-presentation='inline' data-context='market' class='do'>"+title+"</a>";
        	$shelf           = $('div#shelf_list_items').children('ul');
        	$qbox            = boqx.parents('li.elgg-item').children('div.qbox-open');
        	$boqx            = $qbox.find('div.quebx-compartments');
        	$boqx_label      = $qbox.find('div.quebx-compartment-labels').children('a[data-aspect='+aspect+']')
        	$compartment     = $boqx.find('ul[data-aspect='+aspect+']');
        	$old_boqx        = $('div.elgg-main').find("li[data-guid='"+item_guid+"']").parents("div.quebx-compartments");
        	 old_boqx_guid   = $old_boqx.data('boqx-guid');
        	$old_compartment = $old_boqx.find("li[data-guid='"+item_guid+"']").parents('ul');
        	 old_aspect      = $old_compartment.data('aspect');
          	//$old_boqx_label  = $('div.elgg-main').find(".quebx-compartment-labels[data-boqx-guid='"+container_guid+"']").children('a[data-aspect='+old_aspect+']');
          	$old_boqx_label  = $old_compartment.parents('.quebx-compartments').prev('.quebx-compartment-labels').children("a[data-aspect="+old_aspect+"]");
        	
        	$boqx_label.addClass('compartment-open').removeClass('compartment-closed');

			// remove item from menu when dropped on the same boqx
        	$boqx.find("li[data-guid='"+item_guid+"']").remove();
        	if (aspect == 'content'){
            	$shelf.children("li#quebx-shelf-item-"+item_guid).attr("data-container-guid", boqx_guid);
            	if (boqx_guid != old_boqx_guid){
	              	$old_boqx.find("li.compartment-content-item[data-guid='"+item_guid+"']").remove();  //remove item from old content compartment.  There can be only one content compartment for any item.
            	}
            	if (boqx_guid == old_boqx_guid && aspect != old_aspect){
                    $old_compartment.find("li[data-guid='"+item_guid+"']").remove();                 // remove item from old compartment.  An item can be in only one compartment inside of a boqx.
            	}
        	}
        	// insert item into menu
        	$boqx.find("ul.jq-dropdown-menu[data-aspect='"+aspect+"']").append("<li class='compartment-"+aspect+"-item' data-guid='"+item_guid+"' data-container-guid='"+boqx_guid+"'>"+link+"</li>");
        	// count the number of items in the new and old menu
        	items       = $compartment.children("li.compartment-"+aspect+"-item").length;
          	old_items   = $old_compartment.children("li.compartment-"+aspect+"-item").length;
          	// update the new and old boqx lables with the counts
        	$boqx_label.children('span').text(items);
        	$old_boqx_label.children('span').text(old_items);
        	// remove the qbox higlight
        	setTimeout(function () {
        		$qbox.removeClass( "box-state-highlight" );
        	}, 2000);
        	// close the old compartment if it contains no items
        	if (old_items == 0){
            	$old_boqx_label.addClass('compartment-closed').removeClass('compartment-open');
        	}
        	console.log('+success');
        	console.log('title(site): '+title);
		}
	});
}


quebx.shelf_tools.init = function() {
	// box functions
	quebx.shelf_tools.pack.init();
}

// register init hook
elgg.register_hook_handler("init", "system", quebx.shelf_tools.init);