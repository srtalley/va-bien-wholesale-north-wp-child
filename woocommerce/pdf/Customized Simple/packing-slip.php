<?php do_action( 'wpo_wcpdf_before_document', $this->type, $this->order ); ?>

	<div style="display:inline-block;width:22%;vertical-align:top;text-align:left;">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/Va-invoice.png" alt="VaBien" width="99" height="61">
	</div>

<?php do_action( 'wpo_wcpdf_before_order_data', $this->type, $this->order ); ?>

	<div style="display:inline-block;width:28%;vertical-align:top;">

		<p style="margin-bottom: 35px;font-size:14px;"><?php _e( 'Shipping To:', 'woocommerce-pdf-invoices-packing-slips' ); ?><br>
			<?php $this->shipping_address(); ?><br>
			<?php $this->billing_email(); ?><br>
			<?php $this->billing_phone(); ?></p>


		<p style="padding-bottom:0px;margin-bottom:0;font-size:14px;"><span><?php _e( 'Order No.', 'woocommerce-pdf-invoices-packing-slips' ); ?></span>
			<span><?php $this->order_number(); ?></span></p>

	</div>

	<div style="display:inline-block;width:28%;vertical-align:top;">

		<p style="margin-bottom: 35px;font-size:14px;"><span><?php _e( 'Invoice No.', 'woocommerce-pdf-invoices-packing-slips' ); ?></span>
			<span><?php $this->invoice_number(); ?></span><br>
			<span><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></span> <span><?php $this->invoice_date( "M d, Y" ); ?></span><br>
			<span><?php _e( 'Shipping Method:', 'woocommerce-pdf-invoices-packing-slips' ); ?></span><br>
			<span><?php $this->shipping_method(); ?></span></p>

	</div>

<?php do_action( 'wpo_wcpdf_after_order_data', $this->type, $this->order ); ?>

	<div style="display:inline-block;width:20%;vertical-align:bottom;text-align:right;">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/Bien-invoice.png" alt="VaBien" width="61" height="170">
	</div>

	<hr style="margin:50px 0;">

<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>

	<table class="order-details">
		<thead>
		<tr>
			<th class="product"><?php _e( 'Style', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
			<th class="product"><?php _e( 'Product', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
			<th class="quantity"><?php _e( 'Quantity', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		</tr>
		</thead>
		<tbody>

		<?php $items = $this->order->get_items();

		if ( sizeof( $items ) > 0 ) :

			foreach ( $items as $item ) : ?>

				<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item->get_id(), $this->type, $this->order, $item->get_id() ); ?>">

					<td style="width: 100px;">
						<?php if ( $style_number = wc_get_order_item_meta( $item->get_id(), 'Style Number' ) ) : ?>
							<p style="font-size: 12px;"><?php _e( $style_number ); ?></p>
						<?php endif; ?>
					</td>

					<td class="product">
						<?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation
						?>

						<span class="item-name"><?php echo $item->get_name(); ?></span>

						<?php do_action( 'wpo_wcpdf_before_item_meta', $this->type, $item, $this->order ); ?>

						<?php do_action( 'wpo_wcpdf_after_item_meta', $this->type, $item, $this->order ); ?>
					</td>
					<td class="quantity"><?php echo $item->get_quantity(); ?></td>
				</tr>
			<?php endforeach;
		endif;?>
		</tbody>
	</table>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>

<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->type, $this->order ); ?>
	<div class="customer-notes">
		<?php if ( $this->get_shipping_notes() ) : ?>
			<h3><?php _e( 'Customer Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
			<?php $this->shipping_notes(); ?>
		<?php endif; ?>
	</div>
<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->type, $this->order ); ?>

<?php if ( $this->get_footer() ): ?>
	<div id="footer">
		<!--<?php $this->footer(); ?>-->
	</div><!-- #letter-footer -->
<?php endif; ?>

<?php do_action( 'wpo_wcpdf_after_document', $this->type, $this->order ); ?>