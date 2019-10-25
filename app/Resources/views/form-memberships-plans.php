<?php
/**
 * Plan Table Template
 */

?>
<?php global $SubwayListTableMembership; ?>

<?php $new = filter_input( INPUT_GET, 'new', FILTER_SANITIZE_STRING ); ?>
<?php $edit = filter_input( INPUT_GET, 'edit', FILTER_SANITIZE_STRING ); ?>
<?php $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING ); ?>
<?php $product_id = filter_input( INPUT_GET, 'product', FILTER_SANITIZE_NUMBER_INT ); ?>

<?php $is_deleted = false; ?>

<?php if ( "yes" === $new ): ?>

	<?php $requested_product = filter_input( 1, 'product_id', 519 ); ?>

	<?php $attached_product = new \Subway\Memberships\Product\Controller(); ?>

	<?php $attached_product->set_id( $requested_product ); ?>

	<?php $attached_product = $attached_product->get(); ?>

    <div class="wrap">

		<?php if ( $attached_product ): ?>

            <h1 class="wp-heading-inline">
				<?php echo esc_html( $attached_product->get_name() ); ?>
            </h1>

            <h2>
                <span class="dashicons dashicons-plus"></span>
				<?php esc_html_e( 'New Membership Plan', 'subway' ); ?>
            </h2>

		<?php else: ?>

            <h2>
				<?php esc_html_e( 'New Membership Plan', 'subway' ); ?>
            </h2>

		<?php endif; ?>

        <hr class="wp-header-end">

		<?php
		$view->render( 'form-memberships-plans-create', [
			'flash_message_add' => $flash_message_add,
			'attached_product'  => $attached_product
		] );
		?>

    </div>

<?php elseif ( "yes" === $edit ): ?>

	<?php $id = filter_input( INPUT_GET, 'plan', FILTER_VALIDATE_INT ); ?>

	<?php $plan = $plans->get_plan( $id ); ?>

	<?php $controller = new \Subway\Memberships\Product\Controller(); ?>

	<?php $controller->set_id( $plan->get_product_id() ); ?>

	<?php $product = $controller->get(); ?>

    <div class="wrap">

		<?php if ( $product ): ?>
            <a href="<?php echo esc_url( $controller->get_product_url_edit() ); ?>">

                <h1 class="wp-heading-inline">
                    <span class="dashicons dashicons-category" style="margin-top: 4px;"></span>
					<?php printf( esc_html__( '%s', 'subway' ), $product->get_name() ); ?>
                </h1>
            </a>

		<?php endif; ?>

        <h2 id="box-membership-sub-title">
            <span class="dashicons dashicons-randomize"></span>
			<?php printf( esc_html__( '%s', 'subway' ), $plan->get_name() ); ?>
        </h2>

		<?php $view->render( 'form-memberships-plans-edit', [
			'plans'    => $plans,
			'plan'     => $plan,
			'id'       => $id,
			'messages' => $flash_message->get()
		] ); ?>

    </div>

<?php else: ?>

    <div class="wrap">

        <h1 class="wp-heading-inline">
			<?php esc_html_e( 'Membership Plans', 'subway' ); ?>
        </h1>

        <a href="?page=subway-membership-plans&new=yes" class="page-title-action">
			<?php esc_html_e( 'Add New', 'subway' ); ?>
        </a>

        <hr class="wp-header-end">

		<?php $SubwayListTableMembership->views(); ?>

		<?php $SubwayListTableMembership->prepare_items(); ?>

        <form method="GET">

            <input type="hidden" name="page" value="subway-membership-plans"/>

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
