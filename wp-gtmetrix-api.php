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
		 * Username.
		 *
		 * @var string
		 */
		protected $email;
		
		/**
		 * API Key.
		 *
		 * @var string
		 */
		protected $api_key;
		/**
		 * BaseAPI Endpoint
		 *
		 * @var string
		 * @access protected
		 */
		protected $base_uri = 'https://gtmetrix.com/api/0.1/';

		/**
		 * Route being called.
		 *
		 * @var string
		 */
		protected $route = '';

		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct( $email, $api_key ) {
			$this->email = $email;
			$this->api_key = $api_key;
		}

		/**
		 * Set request headers.
		 */
		protected function set_headers() {
			// Set request headers.
			$this->args['headers'] = array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Basic ' . base64_encode( "{$this->email}:{$this->api_key}" ),
			);
		}

				/**
		 * Prepares API request.
		 *
		 * @param  string $route   API route to make the call to.
		 * @param  array  $args    Arguments to pass into the API call.
		 * @param  array  $method  HTTP Method to use for request.
		 * @return self            Returns an instance of itself so it can be chained to the fetch method.
		 */
		protected function build_request( $route, $args = array(), $method = 'GET' ) {
			// Start building query.
			$this->set_headers();
			$this->args['method'] = $method;
			$this->route          = $route;
			// Generate query string for GET requests.
			if ( 'GET' === $method ) {
				$this->route = add_query_arg( array_filter( $args ), $route );
			} elseif ( 'application/json' === $this->args['headers']['Content-Type'] ) {
				$this->args['body'] = wp_json_encode( $args );
			} else {
				$this->args['body'] = $args;
			}
			$this->args['timeout'] = 20;
			return $this;
		}


		/**
		 * Fetch the request from the API.
		 *
		 * @access private
		 * @return array|WP_Error Request results or WP_Error on request failure.
		 */
		protected function fetch() {
			// Make the request.
			$response = wp_remote_request( $this->base_uri . $this->route, $this->args );
			// Retrieve Status code & body.
			$code = wp_remote_retrieve_response_code( $response );
			$body = json_decode( wp_remote_retrieve_body( $response ) );
			$this->clear();
			// Return WP_Error if request is not successful.
			if ( ! $this->is_status_ok( $code ) ) {
				return new WP_Error( 'response-error', sprintf( __( 'Status: %d', 'wp-gtmetrix-api' ), $code ), $body );
			}
			return $body;
		}

		/**
		 * Clear query data.
		 */
		protected function clear() {
			$this->args       = array();
			$this->query_args = array();
		}
		/**
		 * Check if HTTP status code is a success.
		 *
		 * @param  int $code HTTP status code.
		 * @return boolean       True if status is within valid range.
		 */
		protected function is_status_ok( $code ) {
			return ( 200 <= $code && 300 > $code );
		}

		/**
		 * start_test function.
		 *
		 * @access public
		 * @param mixed $url
		 * @param array $args (default: array())
		 * @return void
		 */
		public function start_test( $url, $args = array() ) {
			$args = array(
				'body' => array( 'url' => $url )
			);

			return $this->build_request( 'test', $args, 'POST' )->fetch();
		}

		/**
		 * get_test_results function.
		 *
		 * @access public
		 * @param mixed $test_id
		 * @return void
		 */
		public function get_test_results( $test_id ) {

			$request = $this->base_uri . 'test/' . $test_id;
			return $this->fetch( $request );

		}

		/**
		 * get_test_resource function.
		 *
		 * @access public
		 * @param mixed $test_id
		 * @param mixed $resource
		 * @return void
		 */
		public function get_test_resource( $test_id, $resource ) {

		}

		/**
		 * get_locations function.
		 *
		 * @access public
		 * @return void
		 */
		public function get_locations() {

		}

		/**
		 * get_browsers function.
		 *
		 * @access public
		 * @return void
		 */
		public function get_browsers() {

		}

		/**
		 * get_browser_details function.
		 *
		 * @access public
		 * @param mixed $browser_id
		 * @return void
		 */
		public function get_browser_details( $browser_id ) {

		}

		/**
		 * get_acct_status function.
		 *
		 * @access public
		 * @return void
		 */
		public function get_acct_status() {
			$request = $this->base_uri . 'status';
			return $this->fetch( $request );
		}
	}
}
