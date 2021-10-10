<?php


class WholeSaleExtensions {

	protected static $_instance;

	public function __construct() {

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

		add_action( 'woocommerce_single_product_summary', array( $this, 'wholesale_single_product_description' ), 50 );
		add_action( 'wholesale_after_shop_loop_title', array( $this, 'wholesale_secondary_loop_title' ), 10 );

		add_action( 'save_post', array( $this, 'wholesale_save_extra_product_meta_boxes' ), 10, 2 );
		add_action( 'woocommerce_product_options_sku', array( $this, 'wholesale_render_extra_product_meta_boxes' ) );

		add_action( 'content_section', array( $this, 'custom_login_form' ) );
		add_action( 'load_custom_style', array( $this, 'add_custom_login_style' ), 20 );
	}

	public static function init() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function wholesale_single_product_description() {

		global $post;

		the_content();

	}

	public function custom_login_form() {

		?>
		<div class="wholesale-login"><?php

		$args = array(
				'form_id' => 'loginform',
				'label_username' => __( 'Username' ),
				'label_password' => __( 'Password' ),
				'label_log_in' => __( 'Enter' ),
				'id_submit' => 'wp-submit',
				'remember' => false
		);

		wp_login_form($args);

		?></div><?php

	}

	public function add_custom_login_style() {

		global $wp_styles;

		wp_register_style( 'wholesale-login', get_stylesheet_directory_uri() . '/wholesale.css' );
		$wp_styles->do_items( 'wholesale-login' );

	}

	public function wholesale_secondary_loop_title() {

		global $post;

		$secondary_title = get_post_meta( $post->ID, '_secondary_title', true );

		if ( $secondary_title && $secondary_title != '' ) : ?>
			<h3 class="secondary-product-title">
				<?php echo $secondary_title; ?>
			</h3>
		<?php endif;
	}


	/**
	 * Save  meta box data.
	 */
	public function wholesale_save_extra_product_meta_boxes( $post_id, $post ) {

		if ( $post_id === null ) {
			return false;
		}

		if ( isset( $_POST['_secondary_title'] ) ) {
			// Update post meta
			update_post_meta( $post_id, '_secondary_title', wc_clean( $_POST['_secondary_title'] ) );
		}

		return true;
	}

	public function wholesale_render_extra_product_meta_boxes() {

		woocommerce_wp_text_input( array(
			'id'    => '_secondary_title',
			'class' => 'form-field ',
			'label' => __( 'Secondary Grid Field:' ),
		) );
	}

}

return WholeSaleExtensions::init();
