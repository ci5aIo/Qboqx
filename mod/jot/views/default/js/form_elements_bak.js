/**
 * 
 */
define(function(require) {

   var $ = require('jquery');
   var Ajax = require('elgg/Ajax');

   $(document).on('click', '.trigger-element', function(e) {
       e.preventDefault();

       var ajax = new Ajax();
       ajax.view('partials/form_elements').done(function(output) {
          $('.new_datefield').append($(output));
       });       
   }); 
});