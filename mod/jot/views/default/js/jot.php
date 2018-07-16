<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
elgg.provide('jot.javascript');

jot.javascript.init = function(){
$(document).ready(function(){

	$(document).on("click", ".clone-receipt-action", function(e){"use strict"
	    e.preventDefault();
		// clone the node
		var line_item = $(".receipt_line_items").clone(true, true).contents();
		$(line_item).insertBefore(".new_line_items");
	}); 
})
};
elgg.register_hook_handler('init', 'system', jot.javascript.init);

<?php if (FALSE) : ?></script><?php endif; ?>