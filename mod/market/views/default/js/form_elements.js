/**
 * 
 */
define(function(require) {

   var $ = require('jquery');
   var Ajax = require('elgg/Ajax');

   $(document).on('click', '.trigger-element', function(e) {
       e.preventDefault();

       var ajax = new Ajax();
       var field_type = $(this).data("element");
       var entity     = $(this).attr("guid");
       var cid        = "c"+Math.floor((Math.random()*200)+1);
		
       ajax.view('partials/form_elements',{
    	   data: {
    		 element: field_type,
    		 guid: entity,
    		 cid: cid
    	   },
       }).done(function(output) {
//          $('.new_progress_marker').append($(output));
//     	  $(this).parents('header').next('.rTable').find('.new_progress_marker').append($(output));
    	   $('.new_progress_marker').before($(output));
       });       
   });
	$(document).on('keydown', 'input.last_characteristic', function(e) { 
	    var keyCode = e.keyCode || e.which;
	    var ajax = new Ajax();
	    var field_type ='new_item_characteristic';
	    
	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_characteristic");
		  ajax.view('partials/form_elements',{
	    	   data: {
	    		 element: field_type
	    	   },
	       }).done(function(output) {
	    	   $('.new_characteristic').before($(output));
	       });
	    } 
	});
	$(document).on('keydown', 'input.last_feature', function(e) { 
	    var keyCode = e.keyCode || e.which;
	    var ajax = new Ajax();
	    var field_type ='new_item_feature';
	    
	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_feature");
		  ajax.view('partials/form_elements',{
	    	   data: {
	    		 element: field_type
	    	   },
	       }).done(function(output) {
	    	   $('.new_feature').before($(output));
	       });
	    } 
	});
	
	$(document).on('click', "#Gallery_tab", function(e){
        var guid       = $(this).data("guid");
        var field_type = 'gallery';
	    var ajax = new Ajax();
	    var state = $(this).data("state");
	    var panel_exists = $(this).next('#gallery_panel').length>0;
	    console.log(panel_exists);
	    if(panel_exists){
        	$("#gallery_panel").slideToggle("slow");
        	if ($(this).hasClass("selected")){
        		$(this).removeClass("selected");
        	}
        	else{
        		$(this).addClass("selected");
        	}
	    } 
	    else {
	        ajax.view('partials/form_elements',{
	        	data: {
	        		element: field_type,
	        		guid: guid,
	        	},
	        }).done(function(output){
	        	$('#Gallery_tab').after($(output));
	        	$("#gallery_panel").slideDown("slow");
	        	$("#Gallery_tab").addClass("selected");
	        });
	    }
	});
});