import './autocomplete_steps'


(function ($) {

	$(document).on('ready', function () {
		const nonce = $('#hts_close_omnisend_nonce').val();
		$('.hts-omnisend .notice-dismiss').on('click', function() {
			$.post(ajaxurl, { action: 'hostinger_dismiss_omnisend_notice', nonce: nonce });
			$('.hts-omnisend').remove();
		});

	});

})(jQuery);
