jQuery(document).ready(function ($) {
	var parent = $('.quantity');

	$.each(parent, function () {
		$(this).append('<a href="#" class="arrow-up incrementor" data-increment="up"><span class="dashicons dashicons-arrow-up-alt2"></span></a>' +
				'<a href="#" class="arrow-down incrementor" data-increment="down"><span class="dashicons dashicons-arrow-down-alt2"></span></a>');
	});

	/**
	 * Add/subtract from the input type number fields
	 */
	$('.incrementor').on('click', function (e) {
		e.preventDefault();
		azera_shop_luxury_calc_value($(this));
	});

	function azera_shop_luxury_calc_value(el) {
		var input = $(el.siblings('input'));

		switch ( $(el).attr('data-increment') ) {
			case 'up':
				input.val(parseInt(input.val()) + 1).trigger('change');
				break;
			case 'down':
				if ( input.val() === 0 ) {
					return;
				}
				input.val(parseInt(input.val()) - 1).trigger('change');
				break;
		}
	}
});