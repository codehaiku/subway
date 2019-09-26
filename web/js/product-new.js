/*
 * Copyright (c) 2019. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

jQuery(document).ready( function($) {

    'use strict';

    $('#publish-product').on('click', function(e) {

        e.preventDefault();

        var element = $(this);

        element.attr('disabled', 'disabled');

        $.ajax( {
            url: subway_api_settings.root + 'subway/v1/membership/new-product',
            method: 'POST',
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader( 'X-WP-Nonce', subway_api_settings.nonce );
            },
            data:{
                'title' : $('#input-title').val(),
                'description' : $('#input-description').val(),
            }
        } ).success( function ( response )
        {

            if ( response.is_error )
            {
                alert( response.message );
            } else
            {
                alert( response.message );
            }

        } ).error( function( response, status, message )
        {

            console.log( message );


        }).done( function(){
            element.removeAttr('disabled');
        });
    });
});