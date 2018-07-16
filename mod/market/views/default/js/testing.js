/**
 * 
 */
define(function(require) {

   var $ = require('jquery');
   var Ajax = require('elgg/Ajax');

   $(document).on('click', '.trigger-element-testing', function(e) {
       e.preventDefault();

       var ajax = new Ajax();
       ajax.view('partials/testing').done(function(output) {
          $('.placeholder').append($(output));
       });       
   });

   $(document).ready(function(){
	    $("a.collapser-testing").on("click", function(e) {
	        e.preventDefault();

	        var ajax = new Ajax();
	        ajax.view('partials/testing').done(function(output) {
	           $('.placeholder').append($(output));
	        });
	        
	        $("<div>Collapser clicked</div>").dialog();
	    });
	});

//   $(document).on("click", "a.collapser", function(e) {
//         e.preventDefault();
//         var cid = $(this).parents("div.story").attr("data-cid");
//         var str = $(this).parent().find("textarea[data-focus-id=NameEdit--"+cid+"]").val();
//         $(this).parents("div.model").children("header.preview").removeClass("collapsed");
//         $(this).parents("div.model").children("header.preview").addClass("expanded");
//         $(this).parents("div.model").children("header.preview").find("span.story_name").html(str);
//         $(this).parents("div.rTableRow.story").removeClass("pin");
//         $(this).parents("div.rTableRow.story").css("cursor", "move");
//         $(this).parents("div.details").removeClass("expanded");
//         $(this).parents("div.details").addClass("collapsed");
//     });
   
});