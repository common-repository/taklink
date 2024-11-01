<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       Takl.ink
 * @since      1.0.0
 *
 * @package    TakLink
 * @subpackage TakLink/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TakLink
 * @subpackage TakLink/admin
 * @author     Takl.ink <info@takl.ink>
 */
class TakLink_Admin {

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

	private $taklink_oembed_api_url = 'https://takl.ink/api/v1/oembed/';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TakLink_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The TakLink_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/taklink-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TakLink_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The TakLink_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/taklink-admin.js', array( 'jquery' ), $this->version, false );

	}

	
	public function addPluginAdminMenu(){

		add_submenu_page( 'options-general.php',
			$this->plugin_name, 
			__('TakL.ink','taklink'),
			'administrator',
			$this->plugin_name,
			array( $this, 'displayPluginAdminDashboard' ),
			99 
		);

	}

	public function displayPluginAdminDashboard() {
		// require_once(app_dir . 'admin/partials/'.$this->plugin_name.'-admin-display.php');
		require_once 'partials/'.$this->plugin_name.'-admin-display.php';
	}

	// public function displayPluginAdminSettings() {
	// 	// set this var to be used in the settings-display view
	// 	if(isset($_GET['error_message'])){
	// 		// add_action('admin_notices', array($this,'pluginNameSettingsMessages'));
	// 		// do_action( 'admin_notices', sanitize_text_field($_GET['error_message']) );
	// 	}
	// 	require_once 'partials/'.$this->plugin_name.'-admin-settings-display.php';
	// }

	// public function pluginNameSettingsMessages($error_message){
	// 	switch ($error_message) {
	// 		case '1':
	// 			$message = __( 'There was an error adding this setting. Please try again.  If this persists, shoot us an email.', 'taklink' );                 
	// 			$err_code = __( 'plugin_name_example_setting','taklink' );                 
	// 			$setting_field = 'plugin_name_example_setting';                 
	// 			break;
	// 	}
	// 	$type = 'error';
	// 	add_settings_error(
	// 		   $setting_field,
	// 		   $err_code,
	// 		   $message,
	// 		   $type
	// 	   );
	// }

	//Display admin notices 
	public function taklink_admin_notice(){
		//get the current screen
		$page_id = get_option( 'taklink_page_id' );
		$token = get_option( 'taklink_apitoken' );
		$admin_url = admin_url( '/admin.php?page=taklink' );

		if( !$page_id || empty($page_id) || !$token || empty($token) ) {
			?>
			<div class="notice notice-warning">
				<p><?php echo sprintf( __( 'Please config takl.ink <a href="%s">Plugin settings</a> now.', 'taklink'), $admin_url ); ?></p>
			</div>
			<?php
		}

	}

	public function registerAndBuildFields() {

		$token = get_option( 'taklink_apitoken' );

		add_settings_section(
			// ID used to identify this section and with which to register options
			'plugin_name_general_section', 
			// Title to be displayed on the administration page
			'',  
			// Callback used to render the description of the section
			array( $this, 'plugin_name_display_general_account' ),    
			// Page on which to add this section of options
			'taklink_general_settings'                   
		);

		add_settings_field(
			'taklink_apitoken',
			__( 'Api Token','taklink'),
			array( $this, 'plugin_name_render_settings_field' ),
			'taklink_general_settings',
			'plugin_name_general_section',
			array (
				'type'      => 'input',
				'subtype'   => 'text',
				'id'    => 'taklink_apitoken',
				'name'      => 'taklink_apitoken',
				'description'  => __( 'You can get your Api Token from your TakL.ink <a target="_blank" href="https://takl.ink/panel/">panel page</a>','taklink'),
				'after_input'  => $token && !empty($token) ? ( 
					$this->is_taklink_apitoken_valid() ? 
						'<span class="taklink-apitoken-status taklink-apitoken-valid"><span class="dashicons dashicons-yes-alt" title="'.__('Api Token is valid','taklink').'"></span> '.__('Api Token is valid','taklink').'</span>' : 
						'<label for="taklink_apitoken"><span class="taklink-apitoken-status taklink-apitoken-invalid"><span class="dashicons dashicons-no" title="'.__('This API Token is not valid, Please enter your valid API Token','taklink').'"></span> '.__('This API Token is not valid, Please enter your valid API Token','taklink').'</span></label>' 
				) : '<label for="taklink_apitoken"><span class="taklink-apitoken-status taklink-apitoken-empty"><span class="dashicons dashicons-edit-page"  title="'.__('Enter your API Token key','taklink').'"></span> '.__('Enter your API Token key','taklink').'</span></label>',
				'required' => false,
				'get_options_list' => '',
				'value_type'=>'normal',
				'wp_data' => 'option'
			)
		);

		register_setting(
			'taklink_general_settings',
			'taklink_apitoken'
		);

		$taklink_page_id = get_option( 'taklink_page_id' );

		add_settings_field(
			'taklink_page_id',
			__( 'Page','taklink'),
			array( $this, 'plugin_name_render_settings_field' ),
			'taklink_general_settings',
			'plugin_name_general_section',
			array (
				'type'      => 'input',
				'subtype'   => 'page',
				'id'    => 'taklink_page_id',
				'name'      => 'taklink_page_id',
				'description'  => __( 'Select page that you want show your Takl.ink in it.','taklink'), 
				'after_input'  =>
					$taklink_page_id && !empty($taklink_page_id) ? '<span class="taklink-pagelink"><a target="_blank" href="'.get_the_permalink( $taklink_page_id ).'"><span class="dashicons dashicons-admin-links"></span> '.get_the_permalink( $taklink_page_id ).'</a></span>' : ''
				, 
				'required' => 'true',
				'get_options_list' => '',
				'value_type'=>'normal',
				'wp_data' => 'option'
			)
		);

		register_setting(
			'taklink_general_settings',
			'taklink_page_id'
		);
	
	}

	public function plugin_name_display_general_account() {
	} 

	public function plugin_name_render_settings_field($args) {
	  
		if($args['wp_data'] == 'option'){
			$wp_data_value = get_option($args['name']);
		} elseif($args['wp_data'] == 'post_meta'){
			$wp_data_value = get_post_meta($args['post_id'], $args['name'], true );
		}

		switch ($args['type']) {

			case 'input':
				$value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
				$required = ($args['required']) ? ' required' : '';
				$description = ($args['description']) ? $args['description'] : '';
				$after_input = ($args['after_input']) ? $args['after_input'] : '';
				
				if($args['subtype'] == 'checkbox'){
					/*<input required="required" '.$disabled.' type="number" step="any" id="'.$this->plugin_name.'_cost2" name="'.$this->plugin_name.'_cost2" value="' . esc_attr( $cost ) . '" size="25" /><input type="hidden" id="'.$this->plugin_name.'_cost" step="any" name="'.$this->plugin_name.'_cost" value="' . esc_attr( $cost ) . '" />*/
					$checked = ($value) ? 'checked' : '';
					echo '<input type="'.$args['subtype'].'" id="'.$args['id'].'" '.$required.' name="'.$args['name'].'" size="40" value="1" '.$checked.' />';
				}elseif( $args['subtype'] == 'page' ){
					$pages = get_pages(); 
					if( isset($args['disabled']) ){
						echo '<select id="'.$args['id'].'_disabled" name="'.$args['name'].'_disabled" disabled />';
					} else {
						echo '<select id="'.$args['id'].'" '.$required.' name="'.$args['name'].'" />';
					}
				
						echo '<option disabled>' . esc_attr__( __( 'Select page','taklink' ) ) . '</option>';
			
						foreach ( $pages as $page ) {
							
							$selected = (esc_attr($value) == $page->ID) ? ' selected' : '';
							$option = '<option value="' . $page->ID . '" '.$selected.'>';
								$option .= $page->post_title;
							$option .= '</option>';

							echo $option;
						}
					echo '</select>';
				}elseif( $args['subtype'] == 'schedules' ){
					$schedules = wp_get_schedules(); 
					if( isset($args['disabled']) ){
						echo '<select id="'.$args['id'].'_disabled" name="'.$args['name'].'_disabled" disabled />';
					} else {
						echo '<select id="'.$args['id'].'" '.$required.' name="'.$args['name'].'" />';
					}
				
						echo '<option disabled>' . esc_attr__( __( 'Select schedule','taklink' ) ) . '</option>';
			
						foreach ( $schedules as $schedule ) {
							
							$selected = (esc_attr($value) == $schedule['interval']) ? ' selected' : '';
							$option = '<option value="' . $schedule['interval'] . '" '.$selected.'>';
								$option .= $schedule['display'];
							$option .= '</option>';

							echo $option;
						}
					echo '</select>';
				}
				
				else {
					$prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">'.$args['prepend_value'].'</span>' : '';
					$prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
					$step = (isset($args['step'])) ? 'step="'.$args['step'].'"' : '';
					$min = (isset($args['min'])) ? 'min="'.$args['min'].'"' : '';
					$max = (isset($args['max'])) ? 'max="'.$args['max'].'"' : '';
					if(isset($args['disabled'])){
						// hide the actual input bc if it was just a disabled input the informaiton saved in the database would be wrong - bc it would pass empty values and wipe the actual information
						echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'_disabled" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="'.$args['id'].'" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
					} else {
						echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'" '.$required.' '.$step.' '.$max.' '.$min.' name="'.$args['name'].'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
					}
				}
				break;
				
			default:
				break;
		}
		echo (!empty($after_input)) ? '<span class="after_input">'.$after_input.'</span>' : '';
		echo (!empty($description)) ? '<p class="description" id="tagline-description">'.$description.'</p>' : '';
	}

	public function update_option_taklink_apitoken($old_value, $value, $option) {
		$plugin_public = new TakLink_Public( $this->plugin_name, $this->version );
		$plugin_public->clear_user_page_cache();
	}

	public function is_taklink_apitoken_valid() {

		$token = get_option( 'taklink_apitoken' );

		if( empty($token) ){
			return false;
		}

		$response = wp_remote_get(
			esc_url_raw( $this->taklink_oembed_api_url ),
			array(+
				'timeout'     => 100,
				'headers' => array(
					'Content-Type' => 'application/json',
					'Authorization' => sanitize_text_field( $token ),
				),
				'body' => array(
					'donottrack' => '1',
				)
			)
		);
		
		if ( !is_wp_error( $response ) && $response['response']['code'] == 200 && is_array( $response ) && !empty($response['body']) ) {
			return true;
		}

		return false;

	}

}
