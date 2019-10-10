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
    }

    $(target.replace('section-', '')).addClass('active');

    $('#product-tabs > li > a').on('click', function (e) {

        var target = $(this).attr('data-section-target');

        var targetSection = '#' + target;

        e.preventDefault();

        // Remove classes.
        $('#product-tabs > li > a').removeClass('active');

        $('a[data-section-target=' + target + ']').addClass('active');

        $('.subway-product-section').removeClass('active');

        $('input[name=active-section]').val(target);

        $(targetSection).addClass('active');

        var url = __subway_replaced_url_param('section');

        history.pushState('', '', 'url');
        history.replaceState('', '', url);

        location.hash = 'section-'+target;

        $('#update-product').attr('disabled', 'disabled');
        setTimeout(function(){
            $('#update-product').removeAttr('disabled');
        }, 500);

    });
});