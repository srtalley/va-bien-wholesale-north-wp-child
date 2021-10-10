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

		// The code for displaying WooCommerce Product Custom Fields
		add_action( 'woocommerce_product_options_sku', array( $this, 'woocommerce_product_custom_price_fields') ); 

		// Following code Saves  WooCommerce Product Custom Fields
		add_action( 'woocommerce_process_product_meta', array( $this, 'woocommerce_product_custom_price_fields_save') );

		add_filter( 'woocommerce_get_price_html', array( $this, 'vb_change_product_price_html'), 10, 2 );
		add_filter( 'woocommerce_cart_item_price', array( $this, 'vb_change_product_price_cart'), 10, 3 );

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
		echo '<div class="vb-price-description"><p>' . get_post_meta($post->ID, '_vb_wholesale_price_description', true) . '</p></div>';

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

	/**
	 * Display a custom field
	 */
	public function woocommerce_product_custom_price_fields () {
		global $woocommerce, $post;
		echo '<div class="vb_wholesale_price_description">';
		// This function has the logic of creating custom field
		//  This function includes input text field, Text area and number field
		// Custom Product Text Field
		woocommerce_wp_text_input(
			array(
			  'id'          => '_vb_wholesale_price_description',
			  'label'       => __( 'Wholesale Price Description:', 'woocommerce' ),
			  'placeholder' => '',
			  'desc_tip'    => 'true'
			)
		);
		echo '</div>';
	}
	/**
	 * Save the custom field data
	 */
	public function woocommerce_product_custom_price_fields_save($post_id) {
    // Custom Product Text Field
    $woocommerce_vb_wholesale_price_description = $_POST['_vb_wholesale_price_description'];
    if (!empty($woocommerce_vb_wholesale_price_description))
        update_post_meta($post_id, '_vb_wholesale_price_description', esc_attr($woocommerce_vb_wholesale_price_description));
	}

	/**
	 * Change the price display on product pages and shop
	 */
	public function vb_change_product_price_html( $price_html, $product ) {

		$wholesale_price_description = get_post_meta($product->get_id(), '_vb_wholesale_price_description', true);
		if ( $wholesale_price_description != '' ) {
			$price_html = '<span class="amount vb-custom-price-description">' . $wholesale_price_description . '</span>';
		}
		return $price_html;
	}
	
	/**
	 * Change the price display in the cart
	 */
	public function vb_change_product_price_cart( $price, $cart_item, $cart_item_key ) {

		$wholesale_price_description = get_post_meta($cart_item['product_id'], '_vb_wholesale_price_description', true);
		if ( $wholesale_price_description != '' ) {
			$price = $wholesale_price_description;
		}
		return $price;
	}
}

return WholeSaleExtensions::init();
