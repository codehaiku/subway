<?php

namespace Subway\FlashMessage;

class FlashMessage {

	protected $user_id = 0;

	protected $unique_key = '';

	/**
	 * FlashMessage constructor.
	 *
	 * @param $user_id
	 * @param $unique_key
	 */
	public function __construct( $user_id, $unique_key ) {

		$this->user_id = $user_id;

		$this->unique_key = $unique_key;

		return $this;

	}

	/**
	 * @param array $messages
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function add( $messages = [] ) {

		if ( empty ( $this->user_id ) || empty ( $this->unique_key ) ) {

			wp_die( __( 'Error: user id and unique key must not be empty', 'subway' ) );

		}

		update_user_meta( $this->user_id, $this->unique_key, $messages );

		return $this;

	}

	/**
	 * @return mixed
	 */
	public function get() {

		$message = get_user_meta( $this->user_id, $this->unique_key );

		// Immediately delete the flash message after getting it.
		delete_user_meta( $this->user_id, $this->unique_key );

		return $message;

	}

}