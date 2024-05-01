(function ($) {

    $( document ).ready(function() {

        /**
         * Button text switcher
         */
        $('body').on('change', 'input[name="chosen_onboarding"]:radio', function(e) {
            let buttonLabel = $(this).data('button-label');
            let imagePath = $(this).data('image-path');

            $('.js-complete-woo-onboarding').html(buttonLabel);
            $('.hostinger-woo-onboarding__column.hostinger-woo-onboarding__column--is-second img').attr('src', imagePath);
        });

        /**
         * Button AJAX
         */
        $('body').on('click', 'a.js-complete-woo-onboarding', function(e) {
            e.preventDefault();

            if($('.hostinger-woo-onboarding').hasClass('hostinger-woo-onboarding--is-loading')) {
                return;
            }

            $('.hostinger-woo-onboarding').addClass('hostinger-woo-onboarding--is-loading');

            let choice = $('input[name="chosen_onboarding"]:checked').val();

            $.ajax( {
                url: ajaxurl,
                method: 'POST',
                beforeSend: function(request) {
                    request.setRequestHeader('Cache-Control', 'no-cache');
                },
                data: {
                    action: 'hostinger_woo_onboarding_choice',
                    nonce: hostingerContainer.nonce,
                    choice: choice
                },
                success: function ( response ) {

                    if( response.data.redirect_url ) {
                        window.location.href = response.data.redirect_url;
                    } else {
                        $('.hostinger-woo-onboarding').removeClass('hostinger-woo-onboarding--is-loading');
                    }

                },
                error: function ( xhr, status, error ) {
                    console.log( 'AJAX request failed: ' + error );
                }
            } );
        });
    });

})(jQuery);
