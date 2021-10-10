<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

// global $product;
// BEGIN CUSTOM
global $post, $product, $woocommerce_loop;
// END CUSTOM

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
// BEGIN CUSTOM 
$shop_product_listing_layout = isset($_GET['shop_product_listing_layout']) ? $_GET['shop_product_listing_layout'] : ot_get_option('shop_product_listing_layout', 'style1');
$shop_product_listing = isset($_GET['shop_product_listing']) ? $_GET['shop_product_listing'] : ot_get_option('shop_product_listing', 'style1');
$shop_product_hover = ot_get_option('shop_product_hover', 'on');
$columns = isset($_GET['products_per_row']) ? $_GET['products_per_row'] : ot_get_option('products_per_row', 'large-3');
$vars = $wp_query->query_vars;

if ( in_array($shop_product_listing_layout, array('style2', 'style3', 'style4', 'style5', 'style6', 'style7', 'style8' )) && is_shop()) {
	$columns = thb_get_product_size($shop_product_listing_layout, $woocommerce_loop['loop']);
}


$columns = array_key_exists('thb_columns', $vars) && $vars['thb_columns'] ? $vars['thb_columns'] : $columns;

$classes[] = 'small-6';
$classes[] = $columns;
$classes[] = 'columns';
$classes[] = 'thb-listing-'.$shop_product_listing;

?>
<?php
	$featured = wp_get_attachment_url( get_post_thumbnail_id(), 'shop_catalog' );
	$attachment_ids = $product->get_gallery_image_ids();
	if ( $attachment_ids ) {
		$loop = 0;
		foreach ( $attachment_ids as $attachment_id ) {
			$image_link = wp_get_attachment_url( $attachment_id );
			if (!$image_link) continue;
			$loop++;
			$thumbnail_second = wp_get_attachment_image_src($attachment_id, 'shop_catalog');
			if ($image_link !== $featured) {
				if ($loop == 1) break;
			}
		}
	}
	$style = $class = '';
	if (isset($thumbnail_second[0])) {            
		$style = 'background-image:url(' . $thumbnail_second[0] . ')';
		$thumbnail_class = 'thb_hover';     
	}
// END CUSTOM
?>
<li <?php post_class($classes); ?>>

	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	// BEGIN CUSTOM
	?>
	
	<figure class="product_thumbnail <?php echo esc_attr($thumbnail_class); ?>">	
		<?php do_action( 'thb_product_badge'); ?>
		<?php if ($shop_product_listing === 'style1') { thb_wishlist_button(); } ?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<?php if ($shop_product_hover === 'on') { ?>
			<span class="product_thumbnail_hover" style="<?php echo esc_attr($style); ?>"></span>
			<?php } ?>
			<?php
				if ( has_post_thumbnail( $product->get_id() ) ) { 	
					echo  get_the_post_thumbnail( $product->get_id(), 'shop_catalog');
				}else{
					 echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', wc_placeholder_img_src() ), $product->get_id() );
				}
			?>
		</a>
	</figure>
	<h3>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
		<?php if ($shop_product_listing === 'style2') { thb_wishlist_button(); } ?>
	</h3>
	<?php 
	// END CUSTOM
	/**
	 * woocommerce_before_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	//do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * woocommerce_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	//do_action( 'woocommerce_shop_loop_item_title' );

	/**
	 * woocommerce_after_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	//do_action( 'woocommerce_after_shop_loop_item_title' ); 
	// BEGIN CUSTOM
	?>
	<?php do_action( 'wholesale_after_shop_loop_title' ); ?>
	<div class="product_after_title">
		<div class="product_after_shop_loop_price">
			<?php do_action( 'woocommerce_after_shop_loop_item_title_loop_price' ); ?>
		</div>

		<div class="product_after_shop_loop_buttons">
		<?php
		
		// END CUSTOM
	/**
	 * woocommerce_after_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item' ); 
	// BEGIN CUSTOM ?>
		</div>
	</div>
	<?php // END CUSTOM ?>
</li>