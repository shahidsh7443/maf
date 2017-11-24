<?php
/*
Plugin Name: Ultimate WordPress Preloader
Plugin URI: http://magniumthemes.com/
Description: Add CSS3 crossbrowser animated preloader to your site
Version: 1.1
Author: MagniumThemes
Author URI: http://magniumthemes.com/
Copyright MagniumThemes.com. All rights reserved.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Register hook */
@session_start();

class MGUP {

	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'ob_install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'ob_uninstall' ) );

		/**
		 * add action of plugin
		 */

		add_action( 'admin_init', array( $this, 'obScriptInit' ) );
		add_action( 'init', array( $this, 'obScriptInitFrontend' ) );

		/*Setting*/
		add_action( 'plugins_loaded', array( $this, 'init_mgupreloader' ) );

		add_action( 'wp_footer', array( $this, 'show_preloader') );

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

	}

	/**
	 * This is an extremely useful function if you need to execute any actions when your plugin is activated.
	 */
	function ob_install() {
		global $wp_version;
		If ( version_compare( $wp_version, "2.9", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 2.9 or higher." );
		}
	}

	/**
	 * This function is called when deactive.
	 */
	function ob_uninstall() {
		//do something
	}

	/**
	 * Function set up include javascript, css.
	 */
	function obScriptInit() {
		wp_enqueue_script( 'mgwb-script-admin', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/js/mgupreloader-admin.js', array(), false, true );
		wp_enqueue_style( 'mgwb-style-admin', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/css/mgupreloader-admin.css' );
		wp_enqueue_style( 'mgwb-style-frontend', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/css/mgupreloader.css' );
	}

	function obScriptInitFrontend() {
		wp_enqueue_script( 'mgwb-script-frontend', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/js/mgupreloader.js', array(), false, true );
		wp_enqueue_style( 'mgwb-style-frontend', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/css/mgupreloader.css' );
	}

	/**
	 * Init when plugin load
	 */
	function init_mgupreloader() {
		load_plugin_textdomain( 'mgupreloader' );
		$this->load_plugin_textdomain();
		require_once( 'mgupreloader-admin.php' );
		$init = new mgupreloaderadmin();
	}

	/*Load Language*/
	function replace_mgupreloader_default_language_files() {

		$locale = apply_filters( 'plugin_locale', get_locale(), 'mgupreloader' );

		return WP_PLUGIN_DIR . plugins_url( "languages/mgupreloader-$locale.mo", __FILE__ ) ;

	}

	/**
	 * Function load language
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'mgupreloader' );

		// Admin Locale
		if ( is_admin() ) {

			load_textdomain( 'mgupreloader', WP_PLUGIN_DIR . plugins_url( "languages/mgupreloader-$locale.mo", __FILE__ ) );
		}

		// Global + Frontend Locale
		load_textdomain( 'mgupreloader', WP_PLUGIN_DIR . plugins_url( "languages/mgupreloader-$locale.mo", __FILE__ ) );
		load_plugin_textdomain( 'mgupreloader', false, WP_PLUGIN_DIR . plugins_url( "languages/", __FILE__ ) );
	}

	public function show_preloader() {
		global $mgupreloader_styles_list;

		$mgupreloader_options = get_option( 'mgupreloader_options' );

		if(is_front_page() && isset($mgupreloader_options['enable']) && $mgupreloader_options['enable'] == 1) {

			// Build preloader preview
			$preloader_style = $mgupreloader_options['style'];
			$preloder_inline_html = $mgupreloader_styles_list[$preloader_style];


			$preloader_html = '<div class="la-dark la-'.$mgupreloader_options['style'].' la-'.$mgupreloader_options['size'].'">'.$preloder_inline_html.'</div>';
			?>

			<div class="mask">
				<div id="mgtup-preloader">
				<?php echo wp_kses_post($preloader_html); ?>
				</div>
			</div>
			<?php
		}
	}

	/*
	 * Function Setting link in plugin manager
	 */

	public function plugin_action_links( $links ) {
		$action_links = array(
			'settings'	=>	'<a href="options-general.php?page=mgupreloader_settings" title="' . __( 'Settings', 'mgupreloader' ) . '">' . __( 'Settings', 'mgupreloader' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

	public function plugin_row_meta( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$row_meta = array(

				'about'	=>	'<a href="http://magniumthemes.com/" target="_blank" style="color: red;font-weight:bold;">' . __( 'Premium WordPress themes', 'mgupreloader' ) . '</a>',

			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

}

// All available preloader styles and inner HTML
$mgupreloader_styles_list['ball-8bits'] = '<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>';
$mgupreloader_styles_list['ball-beat'] = '<div></div><div></div><div></div>';
$mgupreloader_styles_list['ball-climbing-dot'] = '<div></div><div></div><div></div><div></div>';
$mgupreloader_styles_list['ball-elastic-dots'] = ' <div></div><div></div><div></div><div></div><div></div>';
$mgupreloader_styles_list['ball-fall'] = '<div></div><div></div><div></div>';


$mgupreloader = new MGUP();
?>
