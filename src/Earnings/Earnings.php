<?php

namespace Subway\Earnings;

class Earnings {

	protected $wpdb;

	protected $orders_table = '';

	public function __construct( \wpdb $wpdb ) {
		$this->wpdb         = $wpdb;
		$this->orders_table = $this->wpdb->prefix . 'subway_memberships_orders';
	}

	/**
	 * @param string $month
	 *
	 * @return float
	 */
	public function get_monthly( $month = '' ) {

		if ( empty ( $month ) ) {
			return 0.00;
		}

		$stmt = $this->wpdb->prepare( "SELECT SUM(amount) FROM $this->orders_table
		        WHERE MONTHNAME(created) = %s AND status = %s", $month, 'approved' );

		$total = $this->wpdb->get_var( $stmt );

		return doubleval( $total );

	}

	/**
	 * @return float
	 */
	public function get_lifetime() {

		$stmt = $this->wpdb->prepare( "SELECT SUM(amount) FROM $this->orders_table
		        WHERE status = %s", 'approved' );

		$total = $this->wpdb->get_var( $stmt );

		return doubleval( $total );

	}

	/**
	 * @param int $n_of_days
	 *
	 * @return float
	 */
	public function get_last_n_days( $n_of_days = 30 ) {

		$stmt = $this->wpdb->prepare( "SELECT SUM(amount) FROM $this->orders_table
			WHERE created BETWEEN CURDATE() - INTERVAL %d DAY AND CURDATE()", $n_of_days );

		$total = $this->wpdb->get_var( $stmt );

		return doubleval( $total );

	}

	public function get_current_month_orders() {

		$days = [];

		$stmt = $this->wpdb->prepare( "SELECT DAY(created) as day_created, status, SUM(amount) as amount 
				FROM $this->orders_table WHERE status = %s
				AND YEAR(created) = YEAR(NOW()) AND MONTH(created) = MONTH(NOW()) 
				GROUP BY day_created ORDER BY day_created ASC", 'approved' );

		$rows = $this->wpdb->get_results( $stmt, OBJECT );

		$curr_month_no_days = date( 't' );

		// Make all days zero earnings by default.
		for ( $i = 1; $i <= $curr_month_no_days; $i ++ ) {
			$days[ $i ] = 0.00;
		}
		// Assign each earning to the index of corresponding day.
		foreach ( $rows as $row ):
			$days[ $row->day_created ] = $row->amount;
		endforeach;


		return $days;

	}

	public function get_current_month_daily_sales() {

		$days_week = [];

		$stmt = $this->wpdb->prepare( "SELECT MIN( DAYNAME(created) ) as day_week, DAY(created) as day_created, status, 
		COUNT(amount) as sales_count, SUM(amount) as amount FROM wp_subway_memberships_orders 
		WHERE status = %s AND YEAR(created) = YEAR(NOW()) AND MONTH(created) = MONTH(NOW()) 
		GROUP BY day_created ORDER BY day_created ASC", 'approved' );

		$rows = $this->wpdb->get_results( $stmt, OBJECT );

		$curr_month_no_days = date( 't' );
		$total_amount       = 0.00;
		$total_sales        = 0;
		// Make all days zero earnings by default.
		for ( $i = 1; $i <= $curr_month_no_days; $i ++ ) {
			$days[ $i ] = 0.00;
		}
		// Assign each earning to the index of corresponding day.
		foreach ( $rows as $row ):
			$days_week[ $row->day_created ] = $row;
			$total_amount                   += $row->amount;
			$total_sales                    += $row->sales_count;
		endforeach;

		$retval['total_amount'] = $total_amount;
		$retval['total_sales']  = $total_sales;
		$retval['daily_sales']  = $days_week;

		return apply_filters( 'subway\earnings.get_current_month_daily_sales', $retval );

	}

	public function get_last_sale() {

		$stmt = $this->wpdb->prepare("SELECT id, amount, created FROM $this->orders_table
			ORDER BY id DESC LIMIT %d", 1);

		return $this->wpdb->get_row( $stmt, OBJECT );

	}

}