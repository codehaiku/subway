<div class="wrap">

	<h1 class="wp-heading-inline">
        <?php esc_html_e( 'Earnings', 'subway' ); ?>
    </h1>


	<hr class="wp-header-end">

    <div class="earnings-box">

        <div class="earnings-box-column">
            <p>
                <?php echo sprintf(esc_html__('Total earnings this month including taxes (%s):', 'subway') ,'October'); ?>
            </p>

            <span class="amount month-total">
                $1,090.72
            </span>
        </div>

        <div class="earnings-box-column">
            <p>
                <?php echo sprintf(  esc_html__( 'Total taxes this month (%s) based on fixed percentage (%s):', 'subway' ), 'October', '12%');?>
            </p>
            <span class="amount tax-total">
               $130.88
            </span>
        </div>

        <div class="earnings-box-column">

            <p>

                <?php esc_html_e('Overall Total Earnings Made:', 'subway'); ?>
            </p>
            <br/>
            <span class="amount lifetime-total">
                $
                <?php echo number_format(1090.72 * 38, 2); ?>
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
                        <input type="submit" class="button button-secondary" value="<?php esc_attr_e('Filter', 'subway'); ?>" />
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
                   <ul>
                       <li>$30,324.23 <small><em>last 30 days</em></small></li>
                       <li>$10,324.23 <small><em>last 7 days</em></small></li>
                       <li>$324.23 <small><em>last 24 hours</em></small></li>
                   </ul>


                    <h3>Your last sale was:</h3>
                    7 Hours Ago
               <h3>Average Sales/Day</h3>
               $73.96
               <h3>Top Grossing Plan</h3>
               Subway Pro
           </div>
        </div>
    </div>

    <div class="earnings-box">
    <div class="earnings-table">
        <table class="widefat">
            <thead>
            <tr>
                <td>Date</td>
                <td>Sales</td>
                <td>Earnings</td>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>October 1, 2019</td>
                    <td>1</td>
                    <td>$89.99</td>
                </tr>
                <tr>
                    <td>October 2, 2019</td>
                    <td>4</td>
                    <td>$<?php echo 89.99 * 4 ; ?></td>
                </tr>
                <tr>
                    <td>October 3, 2019</td>
                    <td>5</td>
                    <td>$<?php echo 89.99 * 5 ; ?></td>
                </tr>
                <tr>
                    <td>October 4, 2019</td>
                    <td>2</td>
                    <td>$<?php echo 89.99 * 2 ; ?></td>
                </tr>
                <tr>
                    <td>October 5, 2019</td>
                    <td>3</td>
                    <td>$<?php echo 89.99 * 3 ; ?></td>
                </tr>
                <tr>
                    <td>October 6, 2019</td>
                    <td>0</td>
                    <td>$<?php echo 89.99 * 0 ; ?>.00</td>
                </tr>
                <tr>
                    <td>October 7, 2019</td>
                    <td>7</td>
                    <td>$<?php echo 89.99 * 7 ; ?></td>
                </tr>


            </tbody>
        </table>
    </div>
    </div>

</div>

<?php $days = date("t"); ?>
<?php $days_array = []; ?>

<?php for( $i = 1; $i<= $days; $i++ ): ?>
    <?php $days_array[$i] = $i; ?>
<?php endfor; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php echo implode( ',', $days_array ); ?>],
            datasets: [{
                label: 'Total Earnings',
                data: [169,88,1248.42,0,169, 0,231,23,423,123,423,423,423,23,1123,421,212,332,245],
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
                        callback: function( value, index, values ){
                            if(parseInt(value) >= 1000){
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