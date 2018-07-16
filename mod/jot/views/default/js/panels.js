/**
 * 
 */

$(document).ready(function(){
    $("#Description_tab").click(function(){
        $("#Description_panel").show("fast");
        $("#Description_tab").addClass("elgg-state-selected");
        
        $("#Documents_panel").hide("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Steps_panel").hide("fast");
        $("#Steps_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").hide("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").hide("fast");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Things_panel").hide("fast");
        $("#Things_tab").removeClass("elgg-state-selected");
    });
    $("#Documents_tab").click(function(){
        $("#Documents_panel").show("fast");
        $("#Documents_tab").addClass("elgg-state-selected");
        
        $("#Description_panel").hide("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Steps_panel").hide("fast");
        $("#Steps_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").hide("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").hide("fast");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Things_panel").hide("fast");
        $("#Things_tab").removeClass("elgg-state-selected");
    });
    $("#Steps_tab").click(function(){
        $("#Steps_panel").show("fast");
        $("#Steps_tab").addClass("elgg-state-selected");

        $("#Documents_panel").hide("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Description_panel").hide("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").hide("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").hide("fast");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Things_panel").hide("fast");
        $("#Things_tab").removeClass("elgg-state-selected");
    });
    $("#Gallery_tab").click(function(){
        $("#Gallery_panel").show("fast");
        $("#Gallery_tab").addClass("elgg-state-selected");

        $("#Documents_panel").hide("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Description_panel").hide("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Steps_panel").hide("fast");
        $("#Steps_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").hide("fast");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Things_panel").hide("fast");
        $("#Things_tab").removeClass("elgg-state-selected");
    });
    $("#Schedule_tab").click(function(){
        $("#Schedule_panel").show("fast");
        $("#Schedule_tab").addClass("elgg-state-selected");
        
        $("#Documents_panel").hide("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Description_panel").hide("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Steps_panel").hide("fast");
        $("#Steps_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").hide("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Things_panel").hide("fast");
        $("#Things_tab").removeClass("elgg-state-selected");
    });
    $("#Things_tab").click(function(){
        $("#Things_panel").show("fast");
        $("#Things_tab").addClass("elgg-state-selected");
        
        $("#Documents_panel").hide("fast");
        $("#Documents_tab").removeClass("elgg-state-selected");
        $("#Description_panel").hide("fast");
        $("#Description_tab").removeClass("elgg-state-selected");
        $("#Steps_panel").hide("fast");
        $("#Steps_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").hide("fast");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").hide("fast");
        $("#Schedule_tab").removeClass("elgg-state-selected");
    });
});

$(function () {
	//Add input fields when TAB pressed.  Source: http://jsbin.com/amoci/123/edit
	$(document).on('keydown', 'input.last_task_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_task_item");
		  $(this).trigger({type: 'keypress', which: 9, keyCode: 9}); // supposed to send the TAB keystroke, but doesn't work ... yet.
		  var html = $('.task_item').html();
		  $(html).insertBefore('.new_task_item');
	    } 
	});
	$(document).on('keydown', 'input.last_part_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_part_item");
		  var html = $('.part_item').html();
		  $(html).insertBefore('.new_part_item');
	    } 
	});
	// Add input field when [+] pressed
	$('.add-task-item').on('click', function(e){
		e.preventDefault();
		
		var html = $('.task_item').html();
		$(html).insertBefore('.new_task_item');
	});
	$('.add-part-item').on('click', function(e){
		e.preventDefault();
		
		var html = $('.part_item').html();
		$(html).insertBefore('.new_part_item');
	});
	
	// remove a node
	$('.remove-node').on('click', function(e){
		e.preventDefault();
		
		// remove the node
		$(this).parents('div').parents('div').eq(0).remove();
	});
    $( "#sortable" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});	
    $( "#sortable_parts" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});	
    $( "#draggable" ).draggable({
        revert: false,
        connectToSortable: "#sortable",
      });
});