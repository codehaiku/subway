<?php global $SubwayListTableOrders; ?>

<div class="wrap">

    <h1 class="wp-heading-inline"><?php esc_html_e( 'Statements', 'subway' ); ?></h1>


    <hr class="wp-header-end">

	<?php $SubwayListTableOrders->prepare_items(); ?>

    <form method="post">
        <input type="hidden" name="page" value="my_list_test"/>
		<?php $SubwayListTableOrders->search_box( 'search', 'search_id' ); ?>
    </form>

    <form id="subway-products-list-table-form" method="post">
		<?php $SubwayListTableOrders->display(); ?>
    </form>

</div>