define(['elgg', 'jquery', 'elgg/spinner'], function (elgg, $, spinner) {

	$(document).on('submit', '.elgg-form-cart-add-to-cart', function (e) {
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

