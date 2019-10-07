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

}