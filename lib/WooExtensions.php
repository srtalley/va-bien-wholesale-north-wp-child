<?php

// Check if WooCommerce is active
if ( ! WooExtensions::is_woocommerce_active() ) {
	return;
}

// Make sure we're loaded after WC and fire it up!
add_action( 'init', 'wc_extensions' );
add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

class WooExtensions {

	protected static $_instance;

	public function __construct() {

		remove_action( 'wp_head', 'wc_generator_tag' );
		remove_filter( 'the_title', 'wc_page_endpoint_title' );
		remove_action( 'admin_notices', 'woothemes_updater_notice' );

		add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'bra_size_calculator_popup' ) );
		add_filter( 'woocommerce_default_address_fields', array( $this, 'override_address_fields' ), 10, 1 );
		add_action( 'save_post', array( $this, 'save_extra_product_meta_boxes' ), 10, 2 );
		add_action( 'woocommerce_product_options_sku', array( $this, 'render_extra_product_meta_boxes' ) );
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_item_on_checkout' ), 10, 4 );
	}

	public static function init() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function is_woocommerce_active() {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}

	public function bra_size_calculator_popup() {
		?>
		<div class="sizing-help">
			<a href="#bc-popup" id="popup-bc-calculator" style="cursor: pointer;">Sizing Help</a>
		</div>
		<div id="bc-popup" class="theme-popup bra-size-popup mfp-hide">
			<div class="bc-content">
				<?php echo do_shortcode( '[bra_size_calculator]' ); ?>
			</div>
			<button title="Close (Esc)" class="mfp-close">
				<svg xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="12" height="12" viewBox="1.1 1.1 12 12"
				     enable-background="new 1.1 1.1 12 12"
				     xml:space="preserve"><path
						d="M8.3 7.1l4.6-4.6c0.3-0.3 0.3-0.8 0-1.2 -0.3-0.3-0.8-0.3-1.2 0L7.1 5.9 2.5 1.3c-0.3-0.3-0.8-0.3-1.2 0 -0.3 0.3-0.3 0.8 0 1.2L5.9 7.1l-4.6 4.6c-0.3 0.3-0.3 0.8 0 1.2s0.8 0.3 1.2 0L7.1 8.3l4.6 4.6c0.3 0.3 0.8 0.3 1.2 0 0.3-0.3 0.3-0.8 0-1.2L8.3 7.1z"></path></svg>
			</button>
		</div>

		<?php
	}

	public function override_address_fields( $address_fields ) {

		$address_fields['address_1']['placeholder'] = 'House number and street - No PO boxes please';

		return $address_fields;

	}

	/**
	 * Save  meta box data.
	 */
	public function save_extra_product_meta_boxes( $post_id, $post ) {

		if ( $post_id === null ) {
			return false;
		}

		if ( isset( $_POST['_custom_pn'] ) ) {
			// Update post meta
			update_post_meta( $post_id, '_custom_pn', wc_clean( $_POST['_custom_pn'] ) );
		}
	}

	public function render_extra_product_meta_boxes() {

		woocommerce_wp_text_input( array(
			'id'    => '_custom_pn',
			'class' => 'form-field ',
			'label' => __( 'Vabien Style Number:' ),
		) );
	}

	public function save_item_on_checkout( WC_Order_Item_Product $item, $cart_item_key, $values, $order ) {

		// save unique Vabien code to the meta


		// set the product ID to retrieve the session discount data
		$_product_id = $item->get_product_id();
		$_product    = wc_get_product( $_product_id );

		// save the original price
		$item->add_meta_data( __( 'Style Number' ), $_product->get_meta('_custom_pn') );

	}
}

function wc_extensions() {
	return WooExtensions::init();
}