<?php
/**
 * WP-GTmetrix-API (https://gtmetrix.com/api/)
 *
 * @package WP-GTmetrix-API
 */

/*
* Plugin Name: WP GT Metrix API
* Plugin URI: https://github.com/wp-api-libraries/wp-gtmetrix-api
* Description: Perform API requests to GT Metrix in WordPress.
* Author: imFORZA
* Version: 1.0.0
* Author URI: https://www.imforza.com
* GitHub Plugin URI: https://github.com/wp-api-libraries/wp-gtmetrix-api
* GitHub Branch: master
*/

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Check if class exists. */
if ( ! class_exists( 'GTmetrixAPI' ) ) {

	/**
	 * GTmetrix API Class.
	 */
	class GTmetrixAPI {

		/**
		 * API Key.
		 *
		 * @var string
		 */
		static private $api_key;

		/**
		 * BaseAPI Endpoint
		 *
		 * @var string
		 * @access protected
		 */
		protected $base_uri = 'https://gtmetrix.com/api/0.1/';


		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct( $api_key ) {
			static::$api_key = $api_key;
		}

		/**
		 * Fetch the request from the API.
		 *
		 * @access private
		 * @param mixed $request Request URL.
		 * @return $body Body.
		 */
		private function fetch( $request ) {

			$response = wp_remote_get( $request );
			$code = wp_remote_retrieve_response_code( $response );

			if ( 200 !== $code ) {
				return new WP_Error( 'response-error', sprintf( __( 'Server response code: %d', 'text-domain' ), $code ) );
			}

			$body = wp_remote_retrieve_body( $response );

			return json_decode( $body );

		}


		/**
		 * Run Test.
		 *
		 * @access public
		 * @param mixed $url URL.
		 * @param string $location (default: '')
		 * @param string $browser (default: '')
		 * @param string $login_user (default: '')
		 * @param string $login_pass (default: '')
		 * @param string $x_metrix_adblock (default: 0)
		 * @param string $x_metrix_cookies (default: '')
		 * @param string $x_metrix_video (default: 0)
		 * @param string $x_metrix_throttle (default: '')
		 * @param string $x_metrix_whitelist (default: '')
		 * @param string $x_metrix_blacklist (default: '')
		 * @return void
		 */
		function run_test( $url, $location ='', $browser = '', $login_user = '', $login_pass = '', $x_metrix_adblock = '0', $x_metrix_cookies = '', $x_metrix_video = '0', $x_metrix_throttle = '', $x_metrix_whitelist = '', $x_metrix_blacklist = '' ) {

			if ( empty( $url ) ) {
				return new WP_Error( 'response-error', __( "Please provide a URL.", "text-domain" ) );
			}

			$request = $this->base_uri . $test;

			return $this->fetch( $request );

		}

	}
}
