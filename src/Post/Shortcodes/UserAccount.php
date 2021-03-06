<?php

namespace Subway\Post\Shortcodes;

use Subway\FlashMessage\FlashMessage;
use Subway\Helpers\Helpers;
use Subway\Memberships\Orders\Invoices;
use Subway\Memberships\Plan\Plan;
use Subway\Options\Options;
use Subway\User\User;
use Subway\View\View;

class UserAccount {

	protected $view;

	public function __construct( View $view ) {

		$this->view = $view;

	}

	public function display() {

		wp_enqueue_style( 'subway-general' );

		$current_page = filter_input( 1, 'account-page', 516 );

		$user = new User();

		$user->set_id( get_current_user_id() );

		$options = new Options();

		$args = [
			'options' => $options,
			'user'    => $user,
			'wp_user' => wp_get_current_user()
		];

		// Set default template to user account.
		$template = 'home';

		switch ( $current_page ):

			case 'update-email-address':

				$flash           = new FlashMessage( get_current_user_id(), 'subway-user-edit-email' );
				$template        = 'email-edit';
				$args['message'] = $flash->get();

				break;

			case 'update-personal-information':

				$flash           = new FlashMessage( get_current_user_id(), 'subway-user-edit-profile' );
				$template        = 'edit';
				$args['message'] = $flash->get();

				break;

			case 'invoice':

				$id = filter_input( 1, 'invoice_id', 516 );

				$invoices = new Invoices();
				$invoices->set_user( get_current_user_id() );
				$invoices->set_id( $id );

				$invoice         = $invoices->get_user_invoice();
				$args['invoice'] = $invoice;

				$template = 'invoice-single';

				break;

			case 'cancel-membership':

				$template = 'cancel-membership';

				$plan = new Plan();

				$plan_id = filter_input( 1, 'plan-id', 519 );

				$args['plan'] = $plan->get_plan( $plan_id );

				break;

			default:

				$invoices = new Invoices();

				$invoices->set_user( get_current_user_id() );

				$args['invoices'] = $invoices->get_user_invoices();

				$args['plan'] = new Plan();

		endswitch;

		return $this->view->render( $template, $args, true, 'user-account' );

	}

	public function attach_hooks() {

		$this->define_hooks();

	}

	protected function define_hooks() {

		add_shortcode( 'subway_user_account', array( $this, 'display' ) );

	}
}