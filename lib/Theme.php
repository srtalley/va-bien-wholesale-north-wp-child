<?php

class Theme {

	protected static $_instance;

	public function __construct() {


		/*-----------------------------------------------------------------------------------*/
		/*  Script Control  */
		/*-----------------------------------------------------------------------------------*/

		add_action( 'wp_enqueue_scripts', array( $this, '_load_scripts' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, '_load_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, '_unload_styles' ), 9999 );

		/*-----------------------------------------------------------------------------------*/
		/* Clean Up  */
		/*-----------------------------------------------------------------------------------*/

		add_filter( 'show_admin_bar', '__return_false' );
		remove_action( 'wp_head', 'rsd_link' );                                      // EditURI link
		remove_action( 'wp_head', 'wlwmanifest_link' );                              // windows live writer
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );        // links for adjacent posts
		remove_action( 'wp_head', 'wp_generator' );                                  // WP version
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		add_filter( 'the_generator', '__return_false' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'use_default_gallery_style', '__return_false' );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		add_action( 'wp_head', 'ob_start', 1, 0 );
		add_action( 'wp_head', function () {
			$pattern = '/.*' . preg_quote( esc_url( get_feed_link( 'comments_' . get_default_feed() ) ), '/' ) . '.*[\r\n]+/';
			echo preg_replace( $pattern, '', ob_get_clean() );
		}, 3, 0 );

		if ( ! class_exists( 'WPSEO_Frontend' ) ) {
			remove_action( 'wp_head', 'rel_canonical' );
			add_action( 'wp_head', array( $this, 'rel_canonical' ) );
		}
		add_filter( 'xmlrpc_methods', array( $this, 'remove_xmlrpc_pingback_ping' ) );


		/*-----------------------------------------------------------------------------------*/
		/* Theme Settings  */
		/*-----------------------------------------------------------------------------------*/

		add_action( 'login_enqueue_scripts', array( $this, '_my_login_logo' ) );
		add_shortcode( 'bra_size_calculator', array( $this, 'bra_size_calculator_shortcode' ) );
		add_action( 'template_redirect', array( $this,'_block_user_enumeration_attempts') );
	}

	public static function init() {
		static::$_instance = new static();
	}

	public static function get_instance() {
		if ( is_null( static::$_instance ) ) {
			throw new \RuntimeException( 'You must initialize this instance before gaining access to it' );
		}

		return static::$_instance;
	}

	/**
	 * Block User Enumeration
	 */
	public function _block_user_enumeration_attempts() {
		if ( is_admin() ) return;

		$author_by_id = ( isset( $_REQUEST['author'] ) && is_numeric( $_REQUEST['author'] ) );

		if ( $author_by_id )
			wp_die( 'Author archives have been disabled.' );
	}

	public function bra_size_calculator_shortcode( $atts ) {

		ob_start();

		get_template_part( 'views/content', 'bra-calculator-form' );

		return ob_get_clean();

	}

	public function remove_xmlrpc_pingback_ping( $methods ) {
		unset( $methods['pingback.ping'] );

		return $methods;
	}

	public function rel_canonical() {
		global $wp_the_query;

		if ( ! is_singular() ) {
			return;
		}

		if ( ! $id = $wp_the_query->get_queried_object_id() ) {
			return;
		}

		$link = get_permalink( $id );
		echo "\t<link rel=\"canonical\" href=\"$link\">\n";
	}


	public function _my_login_logo() { ?>
		<style type="text/css">
			.login h1 a {
				background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo.png) !important;
				background-size: 100% 100% !important;
				height: 68px !important;
				width: 300px !important;
			}
		</style>
	<?php }


	public function _load_scripts() {


		if ( ! is_admin() ) {

			//wp_enqueue_script( 'fastclick-js', get_stylesheet_directory_uri() . '/assets/js/fastclick.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'sitejs', get_stylesheet_directory_uri() . '/assets/js/site.js', array( 'jquery' ), '', true );

		}
	}


	public function _load_styles() {


	}

	public function _unload_styles() {


	}

	public function _unload_scripts() {

		if ( ! is_admin() ) {
			wp_deregister_script( 'wp-embed' );
		}

	}
}

Theme::init();

