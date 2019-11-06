<h3>
	<?php esc_html_e( 'My Invoices', 'subway' ); ?>
</h3>
<div class="subway-flex-wrap">

    <div class="subway-flex-column-100">

        <table class="subway-membership-lists subway-mg-top-zero">

            <thead>

            <tr>
                <th><?php esc_html_e( 'Date', 'subway' ); ?></th>
                <th><?php esc_html_e( 'Product - Plan', 'subway' ); ?></th>
                <th><?php esc_html_e( 'Invoice Number', 'subway' ); ?></th>
                <th><?php esc_html_e( 'Amount Paid', 'subway' ); ?></th>
                <th><?php esc_html_e( 'Status', 'subway' ); ?></th>
            </tr>

            </thead>

            <tbody>
			<?php if ( ! empty( $invoices ) ): ?>
				<?php foreach ( $invoices as $invoice ): ?>
                    <tr>
                        <td>
                            <?php echo esc_html( date( 'F j, Y', strtotime( $invoice->created ) ) ); ?><br/>
	                        <?php echo esc_html( date( 'g:i a', strtotime( $invoice->created ) ) ); ?><br/>
                        </td>
                        <td>
                            <strong>
								<?php echo esc_html( $invoice->recorded_plan_name ); ?>
                            </strong>
                        </td>
                        <td>
							<?php
							$invoice_url = add_query_arg( [
								'account-page' => 'invoice',
								'invoice_id'   => $invoice->id
							], $options->get_accounts_page_url() );
							?>
                            <a href="<?php echo esc_url( $invoice_url ); ?>"
                               title="<?php echo esc_html( $invoice->invoice_number ); ?>">
								<?php echo esc_html( $invoice->invoice_number ); ?>
                            </a>
                        </td>
                        <td>
							<?php $currency = new Subway\Currency\Currency(); ?>
							<?php echo esc_html( $currency->format( $invoice->amount, $invoice->currency ) ); ?>
                        </td>
                        <td>
							<?php echo esc_html( $invoice->status ); ?>
                        </td>
                    </tr>
				<?php endforeach; ?>
			<?php else: ?>
                <tr>
                    <td colspan="4">
						<?php esc_html_e( 'There are no invoices found.', 'subway' ); ?>
                    </td>
                </tr>
			<?php endif; ?>
            </tbody>

            <tfoot>
            <tr>
                <td colspan="5">
					<?php $invoice_count = count( $invoices ); ?>
					<?php printf( _n( 'Found %d Invoice', 'Found %d Invoices', $invoice_count, 'subway' ), number_format_i18n( $invoice_count ) ); ?>
                </td>
            </tr>
            </tfoot>

        </table>

    </div>

</div>