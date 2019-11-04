function __subway_replaced_url_param(name) {
    const [head, tail] = location.href.split('?');
    return head + '?' + tail.replace(new RegExp(`&${name}=[^&]*|${name}=[^&]*&`), '');
}

jQuery(document).ready(function ($) {

    'use strict';

    var target = location.hash;
    var __target = target;

    if (__target) {
        $('a[data-section-target=' + __target.replace('#section-', '') + ']').addClass('active');
        $('input[name=active-section]').val(__target.replace('#section-', ''));
    }

    $(target.replace('section-', '')).addClass('active');

    $('#plan-tabs > li > a').on('click', function (e) {

        var target = $(this).attr('data-section-target');

        var targetSection = '#' + target;

        e.preventDefault();

        // Remove classes.
        $('#plan-tabs > li > a').removeClass('active');

        $('a[data-section-target=' + target + ']').addClass('active');

        $('.subway-plan-section').removeClass('active');

        $('input[name=active-section]').val(target);

        $(targetSection).addClass('active');

        var url = __subway_replaced_url_param('section');

        history.pushState('', '', 'url');
        history.replaceState('', '', url);

        location.hash = 'section-' + target;

        $('#update-plan').attr('disabled', 'disabled');

        setTimeout(function () {
            $('#update-plan').removeAttr('disabled');
        }, 500);

    });

    $('#publish-plan').on('click', function () {
        var el = $(this);
        setTimeout(function () {
            el.attr('disabled', 'disabled');
        }, 10)
        $(this).attr('value', 'Loading. Please wait...');
    });

    /**
     * Copies the value of #input-checkout-link to clipboard.
     */
    var copy_to_clipboard = function () {

        /* Get the text field */
        var copyText = document.getElementById("input-checkout-link");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        let copy_success_text = 'Checkout link successfully copied!';

        $('#copy-success').remove();
        $('#input-checkout-link').after('<p id="copy-success">' + copy_success_text + '</p>');
    };

    $('#btn-copy-checkout-link').on('click', function () {
        copy_to_clipboard();
    });

    /**
     * Listen for payment type changes.
     */
    var display_billing_fields = function (billing_type) {

        if ('recurring' === billing_type) {

            $('#billing-cycle, #billing-limit').css('display', 'block');
            $('#billing-amount').css('display', 'block');
            $('#free-trial').css('display', 'block');

        } else if ('fixed' === billing_type) {

            $('#billing-cycle, #billing-limit').css('display', 'none');
            $('#billing-amount').css('display', 'block');
            $('#free-trial').css('display', 'block');

        } else if ('free' === billing_type) {

            $('#billing-amount').css('display', 'none');
            $('#billing-cycle, #billing-limit').css('display', 'none');
            $('#free-trial').css('display', 'none');

        }

    };

    var show_free_trial_details = function () {

        var element = $('#input-free-trial-checkbox');
        var details = $('#trial-period-details');

        var is_checked = element.is(':checked');

        if (is_checked) {
            details.css('display', 'block');
        }

        element.on('change', function(){
            if ( $(this).is(':checked') ) {
                details.css('display', 'block');
            } else {
                details.css('display', 'none');
            }
        });

    }

    /**
     * Display fields on render.
     */
    show_free_trial_details();
    display_billing_fields($('#billing-type').val());

    $('#billing-type').on('change', function () {
        var selected = $(this).val();
        display_billing_fields(selected);
    });
});