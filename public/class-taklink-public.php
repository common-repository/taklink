<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       Takl.ink
 * @since      1.0.0
 *
 * @package    TakLink
 * @subpackage TakLink/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    TakLink
 * @subpackage TakLink/public
 * @author     Takl.ink <info@takl.ink>
 */
class TakLink_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	private $username;

	private $taklink_url = 'https://takl.ink';

	private $taklink_oembed_api_url = 'https://takl.ink/api/v1/oembed/';
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function replace_user_page_url( $html ) {
		$html = str_replace($this->taklink_url.'/'.$this->username,get_the_permalink(),$html);
		return $html;
	}

	public function clear_user_page_cache() {
		return delete_transient( 'taklink_user_page_cache' );
	}

	public function render_user_page( $token ) {
		$html = $this->get_user_page($token);
		ob_start();
		language_attributes();
		$lang_attrs = ob_get_contents();
		ob_end_clean();
		$html = ($html && !empty($html)) ? $this->replace_user_page_url($html) : "<html $lang_attrs><head></head><body>" . __("Can't connect to TakL.ink api, Please check Api token or contact us","taklink") . "</body></html>";
		return $html;
	}

	public function get_user_page($token){

		$response = wp_remote_get(
			esc_url_raw( $this->taklink_oembed_api_url ),
			array(
				'timeout'     => 100,
				'headers' => array(
					'Content-Type' => 'application/json',
					'Authorization' => sanitize_text_field( $token ),
				)
			)
		);
		
		if ( !is_wp_error( $response ) && $response['response']['code'] == 200 && is_array( $response ) ) {
			$body    = $response['body']; // use the content
			if( !empty($body) ){
				$obj = json_decode($body,true);
				$this->username = sanitize_text_field( $obj['username'] );
				return $obj['html'];
			}
		}

		return false;

	}

	public function pre_page_load(){

		$page_id = get_option( 'taklink_page_id' );

		if( !$page_id || empty($page_id)) {
			return;
		}

		$token = get_option( 'taklink_apitoken' );

		if( is_page( $page_id) ){
			echo ($token && !empty($token)) ? $this->render_user_page($token) : __('Api token not set in settings','taklink');
			die();
		}

	}

}
