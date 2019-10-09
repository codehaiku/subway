function subway_replaced_url_param(name) {
    const [head, tail] = location.href.split('?');
    return head + '?' + tail.replace(new RegExp(`&${name}=[^&]*|${name}=[^&]*&`), '');
}

jQuery(document).ready(function ($) {

    'use strict';
    var target = location.hash;
    var __target = target;

    if (__target) {
        $('a[data-section-target=' + __target.replace('#', '') + ']').addClass('active');
    }

    $(target).addClass('active');


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

        var url = subway_replaced_url_param('section');

        history.pushState('', '', 'url');
        history.replaceState('', '', url);

        location.hash = target;

        if (location.hash) {
            setTimeout(function () {
                window.scrollTo(0, 0);
            }, 5);
        }

    });
});