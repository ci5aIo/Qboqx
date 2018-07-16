define(['elgg', 'jquery', 'elgg/spinner'], function (elgg, $, spinner) {

	$(document).on('submit', '.elgg-form-cart-add-to-shelf', function (e) {
		e.preventDefault();
		var $form = $(this);
		elgg.action($form.attr('action'), {
			data: $form.serialize(),
			beforeSend: function () {
				spinner.start();
			},
			complete: function () {
				spinner.stop();
			},
			success: function (response) {
				if (response && response.output) {
					var totalLines = response.output.totalLines || false;
					if (typeof totalLines === 'number' && totalLines >= 0) {
						$('[data-cart-indicator]').attr('data-cart-indicator', totalLines).data('cartIndicator', totalLines);
					}
				}
			}
		});
	});

});

// forked from mod\file_tools\views\default\js\file_tools\site.php
elgg.shelf.select_all = function(e) {
	e.preventDefault();

	if ($(this).find("span:first").is(":visible")) {
		// select all
		$('#shelf_list_items input[type="checkbox"]').attr("checked", "checked");
	} else {
		// deselect all
		$('#shelf_list_items input[type="checkbox"]').removeAttr("checked");
	}

	$(this).find("span").toggle();
}

