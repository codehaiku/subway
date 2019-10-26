<?php

namespace Subway\Currency;

class Currency {

	public static function get_supported_currencies() {

		return apply_filters( 'subway\currency::get_supported_currencies', [
			'AUD' => 'Australian Dollar',
			'BRL' => 'Brazilian Real',
			'CAD' => 'Canadian Dollar',
			'CZK' => 'Czech Koruna',
			'DKK' => 'Danish Krone',
			'EUR' => 'Euro',
			'HKD' => 'Hong Kong Dollar',
			'HUF' => 'Hungarian Forint',
			'INR' => 'Indian Rupee',
			'ILS' => 'Israeli New Shekel',
			'JPY' => 'Japanese Yen',
			'MYR' => 'Malaysian Ringgit',
			'MXN' => 'Mexican Peso',
			'TWD' => 'New Taiwan Dollar',
			'NZD' => 'New Zealand Dollar',
			'NOK' => 'Norwegian Krone',
			'PHP' => 'Philippine Peso',
			'PLN' => 'Polish Złoty',
			'GBP' => 'Pound Sterling',
			'RUB' => 'Russian Ruble',
			'SGD' => 'Singapore Dollar',
			'SEK' => 'Swedish Krona',
			'CHF' => 'Swiss Franc',
			'THB' => 'Thai Baht',
			'USD' => 'United States Dollar',

		] );
	}

	public function format( $amount = 0, $currency = 'USD' ) {

		$fmt = new \NumberFormatter( get_locale(), \NumberFormatter::CURRENCY );

		$display_amount = $fmt->formatCurrency( $amount, trim( $currency ) );

		return apply_filters( 'subway\currency::format', $display_amount );

	}

}