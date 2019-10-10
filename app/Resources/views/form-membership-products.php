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

<?php if ( "yes" === $new ): ?>

    <div class="wrap">

        <h1 class="wp-heading-inline">
			<?php esc_html_e( 'Add New Product', 'subway' ); ?>
        </h1>

        <hr class="wp-header-end">

		<?php $view->render( 'form-memberships-products-new', [] ); ?>

    </div>

<?php elseif ( "yes" === $edit ): ?>

    <div class="wrap">

        <h1 class="wp-heading-inline">
			<?php printf( esc_html__( 'Configure Membership Plan: #%d', 'subway' ), $_GET['product'] ); ?>
        </h1>

        <hr/>

		<?php $view->render( 'form-memberships-products-edit', [
		        'membership' => $products,
                'flash_messages' => $flash_message->get()
        ] ); ?>

    </div>

<?php else: ?>

    <div class="wrap">

        <h1 class="wp-heading-inline"><?php esc_html_e( 'Membership Plans', 'subway' ); ?></h1>

        <a href="?page=subway-membership&new=yes"

           class="page-title-action"><?php esc_html_e( 'Add New', 'subway' ); ?></a>

        <hr class="wp-header-end">

		<?php $SubwayListTableMembership->views(); ?>

		<?php $SubwayListTableMembership->prepare_items(); ?>

        <form method="GET">

            <input type="hidden" name="page" value="subway-membership" />

			<?php $SubwayListTableMembership->search_box( 'search', 'search_id' ); ?>

        </form>

		<?php if ( $is_deleted ): ?>

            <div class="notice notice-success is-dismissible">

                <p>
					<?php esc_html_e( 'Successfully moved to trash', 'subway' ); ?>
                </p>

            </div>

		<?php endif; ?>

        <form id="subway-products-list-table-form" method="post">

			<?php $SubwayListTableMembership->display(); ?>

        </form>

    </div>

<?php endif; ?>
