<?php
/**
 * Plugin Name: Country Code For Elementor Form Telephone Field
 * Plugin URI:
 * Description:This plugin simplifies mobile number entry for users by guiding them to select their country code while entering their mobile number, ensuring accurate and properly formatted data submissions.
 * Version: 1.3.10
 * Author:  Cool Plugins
 * Author URI: https://coolplugins.net/
 * License:GPL2
 * Text Domain:country-code-for-elementor-form-telephone-field
 * Elementor tested up to: 3.28.2
 * Elementor Pro tested up to: 3.28.2
 *
 * @package ccfef
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
if ( ! defined( 'CCFEF_VERSION' ) ) {
	define( 'CCFEF_VERSION', '1.3.10' );
}
/*** Defined constant for later use */
define( 'CCFEF_FILE', __FILE__ );
define( 'CCFEF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CCFEF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! class_exists( 'Country_Code_Field_For_Elementor_Form' ) ) {
	/**
	 * Main Class start here
	 */
	final class Country_Code_Field_For_Elementor_Form {
		/**
		 * Plugin instance.
		 *
		 * @var Country_Code_Field_For_Elementor_Form
		 *
		 * @access private
		 * @var null
		 */
		private static $instance = null;

		
		/**
		 * Get plugin instance.
		 *
		 * @return Country_Code_Field_For_Elementor_Form
		 * @static
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor function check compatible plugin before activating it
		 */
		private function __construct() {
			register_activation_hook( CCFEF_FILE, array( $this, 'ccfef_activate' ) );
			register_deactivation_hook( CCFEF_FILE, array( $this, 'ccfef_deactivate' ) );
			add_action( 'activated_plugin', array( $this, 'ccfef_plugin_redirection' ) );
			add_action( 'plugins_loaded', array( $this, 'ccfef_plugins_loaded' ) );
			add_action( 'init', array( $this, 'is_compatible' ) );
			add_action( 'init', array( $this, 'ccfef_load_add_on' ) );
		}

		/**
		 * Load plugin text domain for translation
		 */
		public function ccfef_plugins_loaded() {
			if ( ! is_plugin_active( 'elementor-pro/elementor-pro.php' ) && ! is_plugin_active( 'pro-elements/pro-elements.php' ) ) {
				return false;
			}
			load_plugin_textdomain( 'country-code-for-elementor-form-telephone-field', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

			if ( is_admin() ) {
				require_once CCFEF_PLUGIN_DIR . 'admin/feedback/ccfef-users-feedback.php';
			}
			require_once CCFEF_PLUGIN_DIR . '/includes/class-country-code-elementor-page.php';
			new Country_Code_Elementor_Page();
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'ccfef_plugin_license_link' ) );

		}

		/**
		 * Method for creating action links for the plugin.
		 */
		function ccfef_plugin_license_link( $links ) {
			$settings_link = '<a href="' . admin_url( 'admin.php?page=cool-formkit' ) . '">Cool Formkit</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		public function ccfef_plugin_redirection($plugin){
			if ( ! is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
				return false;
			}
			if ( is_plugin_active( 'cool-formkit-for-elementor-forms/cool-formkit-for-elementor-forms.php' ) ) {
				return false;
			}
			if ( $plugin == plugin_basename( __FILE__ ) ) {
				exit( wp_redirect( admin_url( 'admin.php?page=cool-formkit' ) ) );
			}	
		}
		/**
		 * Check if Elementor Pro is installed or activated
		 */
		public function is_compatible() {
			add_action( 'admin_init', array( $this, 'is_elementor_pro_exist' ) );
		}

		/**
		 * Include country field add-on register file
		 */
		public function ccfef_load_add_on() {
			load_plugin_textdomain( 'country-code-for-elementor-form-telephone-field', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

			include CCFEF_PLUGIN_DIR . 'includes/register/class-ccfef-country-code-register.php';
			CFEFP_COUNTRY_FIELD_REGISTER::get_instance();
		}

		/**
		 * Function used to deactivate the plugin if Elementor Pro does not exist
		 */
		public function is_elementor_pro_exist() {
			if (
				is_plugin_active('pro-elements/pro-elements.php') || 
				is_plugin_active('elementor-pro/elementor-pro.php') ||
				is_plugin_active('hello-plus/hello-plus.php')
			) {
				return true; // At least one plugin is active, the country code plugin can run.
			}
		
			// If neither plugin is active, show an admin notice.
			add_action('admin_notices', array($this, 'admin_notice_missing_main_plugin'));
			return false;
		}


	
		/**
		 * Include country field add-on register file
		 */
		public function cfefp_load_add_on() {
			include CCFEF_PLUGIN_DIR . 'includes/register/class-ccfef-country-code-register.php';
			CFEFP_COUNTRY_FIELD_REGISTER::get_instance( self::$instance);
		}

		/**
		 * Show notice to enable Elementor Pro
		 */
		public function admin_notice_missing_main_plugin() {
			$message = sprintf(
				// translators: %1$s replace with Country Code For Elementor Form Telephone Field & %2$s replace with Elementor Pro.
				esc_html__(
					'%1$s requires %2$s to be installed and activated.',
					'country-code-for-elementor-form-telephone-field'
				),
				esc_html__( 'Country Code For Elementor Form Telephone Field', 'country-code-for-elementor-form-telephone-field' ),
				esc_html__( 'Elementor Pro', 'country-code-for-elementor-form-telephone-field' ),
			);
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', esc_html( $message ) );
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}

		/**
		 * Add options for plugin details
		 */
		public static function ccfef_activate() {
			update_option( 'ccfef-v', CCFEF_VERSION );
			update_option( 'ccfef-type', 'free' );
			update_option( 'ccfef-installDate', gmdate( 'Y-m-d h:i:s' ) );
		}
		/**
		 * Function run on plugin deactivate
		 */
		public static function ccfef_deactivate() {
		}
	}
}
$ccfef_obj = Country_Code_Field_For_Elementor_Form::get_instance();
