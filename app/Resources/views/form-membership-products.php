<?php
/**
 * Products Table Template
 */

?>
<?php global $SubwayListTableMembership; ?>

<?php $new = filter_input( INPUT_GET, 'new', FILTER_SANITIZE_STRING ); ?>
<?php $edit = filter_input( INPUT_GET, 'edit', FILTER_SANITIZE_STRING ); ?>
<?php $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING ); ?>
<?php $product_id = filter_input( INPUT_GET, 'product', FILTER_SANITIZE_NUMBER_INT ); ?>

<?php $is_deleted = false; ?>

<?php if ( "delete" === $action ): ?>

	<?php check_admin_referer( 'trash_product_' . $product_id ); ?>

	<?php $is_deleted = $products->delete( $product_id ); ?>

<?php endif; ?>

<?php if ( "yes" === $new ): ?>

    <div class="wrap">

        <h1 class="wp-heading-inline">
            <?php esc_html_e( 'Add New Product', 'subway' ); ?>
        </h1>

        <hr class="wp-header-end">

        <?php $view->render('form-memberships-products-new', [] ); ?>

    </div>

<?php elseif ( "yes" === $edit ): ?>

    <div class="wrap">

        <h1 class="wp-heading-inline">
            <?php esc_html_e( 'Edit Product', 'subway' ); ?>
        </h1>

        <hr class="wp-header-end">

        <?php $membership = new Products(); ?>

	    <?php $view->render('form-memberships-products-edit', ['membership' => $membership] ); ?>

    </div>

<?php else: ?>

    <div class="wrap">

        <h1 class="wp-heading-inline"><?php esc_html_e( 'Products', 'subway' ); ?></h1>

        <a href="?page=subway-membership&new=yes"
           class="page-title-action"><?php esc_html_e( 'Add New', 'subway' ); ?></a>

        <hr class="wp-header-end">

		<?php $SubwayListTableMembership->prepare_items(); ?>

        <form method="post">
            <input type="hidden" name="page" value="my_list_test"/>
			<?php $SubwayListTableMembership->search_box( 'search', 'search_id' ); ?>
        </form>

		<?php if ( $is_deleted ): ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'Successfully moved to trash', 'subway' ); ?></p>
            </div>
		<?php endif; ?>

        <form id="subway-products-list-table-form" method="post">
			<?php $SubwayListTableMembership->display(); ?>
        </form>

    </div>

<?php endif; ?>
