<?php
/**
 * Plugin Name:     Easy Digital Downloads - UserPro Embed
 * Plugin URI:      https://easydigitaldownloads.com/extensions/userpro-embed/
 * Description:     Directly embed EDD profiles in UserPro
 * Version:         1.0.0
 * Author:          Daniel J Griffiths
 * Author URI:      http://section214.com
 * Text Domain:     edd-userpro-embed
 *
 * @package         EDD\UserPro_Embed
 * @author          Daniel J Griffiths <dgriffiths@section214.com>
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( ! class_exists( 'EDD_UserPro_Embed' ) ) {


	/**
	 * Main EDD_UserPro_Embed class
	 *
	 * @since       1.0.0
	 */
	class EDD_UserPro_Embed {


		/**
		 * @var         EDD_UserPro_Embed $instance The one true EDD_UserPro_Embed
		 * @since       1.0.0
		 */
		private static $instance;


		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      self::$instance The one true EDD_UserPro_Embed
		 */
		public static function instance() {
			if( ! self::$instance ) {
				self::$instance = new EDD_UserPro_Embed();
				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->load_textdomain();
				self::$instance->hooks();
			}

			return self::$instance;
		}


		/**
		 * Setup plugin constants
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function setup_constants() {
			// Plugin version
			define( 'EDD_USERPRO_EMBED_VER', '1.0.0' );

			// Plugin path
			define( 'EDD_USERPRO_EMBED_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin URL
			define( 'EDD_USERPRO_EMBED_URL', plugin_dir_url( __FILE__ ) );
		}


		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function includes() {
			require_once EDD_USERPRO_EMBED_DIR . 'includes/userpro/profile.php';
		}


		/**
		 * Run action and filter hooks
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function hooks() {
			// Handle licensing
			if( class_exists( 'EDD_License' ) ) {
				$license = new EDD_License( __FILE__, 'UserPro Embed', EDD_USERPRO_EMBED_VER, 'Daniel J Griffiths' );
			}
		}


		/**
		 * Internationalization
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 */
		public function load_textdomain() {
			// Set filter for language directory
			$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
			$lang_dir = apply_filters( 'edd_userpro_embed_language_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), '' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'edd-userpro-embed', $locale );

			// Setup paths to current locale file
			$mofile_local   = $lang_dir . $mofile;
			$mofile_global  = WP_LANG_DIR . '/edd-userpro-embed/' . $mofile;

			if( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/edd-userpro-embed/ folder
				load_textdomain( 'edd-userpro-embed', $mofile_global );
			} elseif( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/edd-userpro-embed/ folder
				load_textdomain( 'edd-userpro-embed', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'edd-userpro-embed', false, $lang_dir );
			}
		}
	}
}


/**
 * The main function responsible for returning the one true EDD_UserPro_Embed
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      EDD_UserPro_Embed The one true EDD_UserPro_Embed
 */
function edd_userpro_embed() {
	if( ! class_exists( 'Easy_Digital_Downloads' ) ) {
		if( ! class_exists( 'S214_EDD_Activation' ) ) {
			require_once 'includes/libraries/class.s214-edd-activation.php';
		}

		$activation = new S214_EDD_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation = $activation->run();

		return EDD_UserPro_Embed::instance();
	} else {
		return EDD_UserPro_Embed::instance();
	}
}
add_action( 'plugins_loaded', 'edd_userpro_embed' );
