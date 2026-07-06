<?php

namespace Leadin\api;

use Leadin\api\Base_Api_Controller;
use Leadin\auth\OAuth;
use Leadin\auth\OAuthCryptoError;

/**
 * REST endpoint to retrieve the OAuth refresh token for authenticated users.
 */
class Refresh_Token_Api_Controller extends Base_Api_Controller {

	/**
	 * Class constructor, register route.
	 */
	public function __construct() {
		self::register_leadin_route(
			'/refresh-token',
			\WP_REST_Server::READABLE,
			array( $this, 'get_refresh_token' )
		);
	}

	/**
	 * Callback for refresh token endpoint.
	 * leadin/v1/refresh-token Method:GET.
	 *
	 * @return WP_REST_Response The refresh token or an error response.
	 */
	public function get_refresh_token() {
		$refresh_token = OAuth::get_refresh_token();

		if ( false === $refresh_token ) {
			return new \WP_REST_Response( array( 'error' => OAuthCryptoError::DECRYPT_FAILED ), 500 );
		}

		if ( empty( $refresh_token ) ) {
			return new \WP_REST_Response( array( 'error' => 'not_connected' ), 403 );
		}

		return new \WP_REST_Response( array( 'refreshToken' => $refresh_token ), 200 );
	}

}
