/**
 * Global JS.
 */
jQuery(document).ready(function ($) {

    $('.product-single-product-plan').on('change',function () {

        let plan_id = $(this).val();
        let ripple = '<div id="subway_preloader"><div class="subway-ripple-css" style="transform:scale(0.14);"><div></div><div></div></div></div>';

        $('.product-single-product-plan').attr('disabled', 'disabled');
        $('#box-membership-plan-details-context').css('opacity', '0.25');

        $(this).parent().append( ripple );

        // Remove .checked class in table row.
        $('table.subway-membership-product-plans tr').removeClass('checked');
        // Apply .checked class to current option's tr.
        $( this ).closest('tr').addClass('checked');

        let user_has_plan = false;

        $.ajax({
            url: subway_config.ajax_url,
            data: {
                action: 'get_plan_details',
                plan_id: plan_id
            },
            dataType: 'json',
            success: function (response) {

                if ('success' === response.type) {
                    $('#plan-name').text( response.plan.name );
                    $('#plan-description').html( response.plan.description );
                    $('#plan-sku').text( response.plan.sku );
                    $('#plan-displayed-price').text( response.plan.displayed_price );

                    user_has_plan = response.user.has_plan;

                    if ( user_has_plan ){
                        $('#product-plans-submit > button').attr('disabled', 'disabled');
                    } else {
                        $('#product-plans-submit > button').attr('disabled', false );
                    }
                }
            },
            error: function() {
              console.log('error');
            }
        }).done(function(){
            $('#subway_preloader').remove();
            $('.product-single-product-plan').removeAttr('disabled');
            $('#box-membership-plan-details-context').css('opacity', '1');
        });
    });

});