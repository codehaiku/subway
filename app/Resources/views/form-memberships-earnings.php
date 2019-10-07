<div class="wrap">

    <h1 class="wp-heading-inline">
		<?php esc_html_e( 'Earnings', 'subway' ); ?>
    </h1>


    <hr class="wp-header-end">

    <div class="earnings-box">

        <div class="earnings-box-column">

            <p>
				<?php echo sprintf( esc_html__( 'Total earnings this month including taxes (%s):', 'subway' ), 'October' ); ?>
            </p>

            <span class="amount month-total">
                <?php echo $currency->format( $earnings->get_monthly( date( 'F' ) ), 'USD' ); ?>
            </span>

        </div>

        <div class="earnings-box-column">
            <p>
				<?php echo sprintf( esc_html__( 'Sales Tax this month (%s) based on fixed percentage (%s):', 'subway' ), 'October', '3%' ); ?>
            </p>
            <span class="amount tax-total">
               <?php echo $currency->format( $earnings->get_monthly( date( 'F' ) ) * 0.03, 'USD' ); ?>
            </span>
        </div>

        <div class="earnings-box-column">
            <p>
				<?php esc_html_e( 'Overall Total Earnings Made:', 'subway' ); ?>
            </p>
            <br/>
            <span class="amount lifetime-total">
               <?php echo $currency->format( $earnings->get_lifetime(), 'USD' ); ?>
            </span>
        </div>
    </div>

    <div class="earnings-box">
        <div class="earnings-box-column one-third">
            <div class="earnings-chart">
                <div id="earnings-filter">
                    <form>
                        <select>
                            <option>This Week</option>
                            <option>January</option>
                            <option>February</option>
                            <option>March</option>
                            <option>April</option>
                            <option>May</option>
                            <option>June</option>
                            <option>July</option>
                            <option>August</option>
                            <option>September</option>
                            <option>October</option>
                            <option>November</option>
                            <option>December</option>
                            <option>All Time</option>
                        </select>
                        <select>
                            <option>Sales</option>
                        </select>
                        <select>
                            <option>All Membership Plans</option>
                            <option>Jerry's Pork & Meat</option>
                            <option>Lotion Subscription</option>
                            <option>Pro Plan</option>
                            <option>Product Lite</option>
                        </select>
                        <input type="submit" class="button button-secondary"
                               value="<?php esc_attr_e( 'Filter', 'subway' ); ?>"/>
                    </form>
                    <a href="#" class="subway-flag">
                        Tip: See the Plugin's API page to learn how to add your own filter.
                    </a>
                </div>
                <canvas id="myChart" width="10" height="5"></canvas>
            </div>
        </div>
        <div class="earnings-box-column one-fourth">
            <div class="earning-statistics">
                <h1 style="margin-top: 0;">Fun Statistics</h1>

                <h3>Sales</h3>
                <ul class="fun-facts">
                    <li>
						<?php echo $currency->format( $earnings->get_last_n_days( 30 ), 'USD' ); ?>
                        <small>
                            <em>in the last 30 days</em>
                        </small>
                    </li>
                    <li>
						<?php echo $currency->format( $earnings->get_last_n_days( 7 ), 'USD' ); ?>
                        <small>
                            <em>in the last 7 days</em>
                        </small>
                    </li>

                    <li>
						<?php echo $currency->format( $earnings->get_last_n_days( 1 ), 'USD' ); ?>
                        <small>
                            <em>in the last 24 hours</em>
                        </small>
                    </li>

                </ul>


                <h3>Your last sale was:</h3>
                <?php $last_sale = $earnings->get_last_sale(); ?>
                <?php
                  echo sprintf(esc_html__('%s %s ago', 'subway'),
                        $currency->format($last_sale->amount, 'USD'),
                        human_time_diff(strtotime( $last_sale->created, strtotime('now')))
                    );
                ?>
                <h3>Average Sales/Day (<small><?php echo date('F'); ?></small>)</h3>
                <?php $av_total = $earnings->get_monthly( date('F') ); ?>
                <?php $av_day_today = date('j'); ?>
                <?php echo $currency->format( $av_total / $av_day_today, 'USD'); ?>

                <h3>Top Grossing Membership Plan</h3>
                Subway Pro
            </div>
        </div>
    </div>

	<?php $retval = $earnings->get_current_month_daily_sales(); ?>
	<?php $daily_sales = $retval['daily_sales']; ?>
	<?php $rv_total_amount = $retval['total_amount']; ?>
	<?php $rv_total_sales = $retval['total_sales']; ?>


    <div class="earnings-box">
        <div class="earnings-table">
            <table class="widefat striped fixed">
                <thead>
                <tr>
                    <td>Date</td>
                    <td>Sales</td>
                    <td>Earnings</td>
                </tr>
                </thead>
                <tbody>
				<?php if ( ! empty( $daily_sales ) ): ?>

					<?php foreach ( $daily_sales as $daily_sale ): ?>
                        <tr>
                            <td><?php echo sprintf( '%s, %s', $daily_sale->day_week, $daily_sale->day_created ); ?></td>
                            <td><?php echo absint( $daily_sale->sales_count ); ?></td>
                            <td><?php echo $currency->format( $daily_sale->amount, 'USD' ); ?> </td>
                        </tr>
					<?php endforeach; ?>

				<?php endif; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Total</th>
                    <th><?php echo absint( $rv_total_sales ); ?></th>
                    <th>
						<?php echo $currency->format( $rv_total_amount, 'USD' ); ?>
                    </th>

                </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<?php $days = date( "t" ); ?>
<?php $days_array = []; ?>

<?php for ( $i = 1; $i <= $days; $i ++ ): ?>
	<?php $days_array[ $i ] = $i; ?>
<?php endfor; ?>

<?php $current_month_orders_amounts = $earnings->get_current_month_orders(); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php echo implode( ',', $days_array ); ?>],
            datasets: [{
                label: 'Total Earnings',
                data: [<?php echo implode( ',', $current_month_orders_amounts ); ?>],
                backgroundColor: [
                    'rgba(0, 212, 125, 0.68)',
                ],
                borderColor: [
                    'rgba(0, 212, 125, 0.90)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function (value, index, values) {
                            if (parseInt(value) >= 1000) {
                                return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            } else {
                                return '$' + value;
                            }
                        }
                    },

                }],

            }
        }
    });
</script>
</script>