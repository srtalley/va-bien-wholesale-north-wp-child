<?php do_action( 'wpo_wcpdf_before_document', $this->type, $this->order ); ?>

	<div style="display:block;width:100%;">

		<div style="display:inline-block;width:69%;vertical-align:bottom;">

			<?php do_action( 'wpo_wcpdf_before_order_data', $this->type, $this->order ); ?>

			<div style="display:inline-block;width:100%;margin-bottom:20px;">

				<div style="display:inline-block;width:49%;vertical-align:top;text-align:left;">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/Va-invoice.png" alt="VaBien" width="99" height="61">
				</div>

				<div style="display:inline-block;width:49%;vertical-align:top;text-align:left;">
					<p style="margin-bottom: 0px;font-size:16px;"><?php if ( isset( $this->settings['display_number'] ) ) { ?><span><?php _e( 'Invoice No.',
							'woocommerce-pdf-invoices-packing-slips' ); ?></span> <span><?php $this->invoice_number(); ?></span><?php } ?><br>
						<span><?php _e( 'Order No.', 'woocommerce-pdf-invoices-packing-slips' ); ?></span> <span><?php $this->order_number(); ?></span><br>
						<?php if ( isset( $this->settings['display_date'] ) ) { ?><span><?php _e( 'Invoice Date:',
							'woocommerce-pdf-invoices-packing-slips' ); ?></span> <span><?php $this->invoice_date( "M d, Y" ); ?></span><?php } ?></p>
				</div>

			</div>

			<div></div>

			<div style="display:inline-block;width:100%;">

				<div style="display:inline-block;width:49%;vertical-align:top;text-align:left;">
					<p style="margin-bottom: 0;font-size:14px;"><?php _e( 'Ship To:', 'woocommerce-pdf-invoices-packing-slips' ); ?><br>
						<?php $this->shipping_address(); ?></p>
				</div>

				<div style="display:inline-block;width:49%;vertical-align:top;text-align:left;">
					<p style="margin-bottom: 0;font-size:14px;"><?php _e( 'Bill To:', 'woocommerce-pdf-invoices-packing-slips' ); ?><br>
						<?php $this->billing_address(); ?><br>
						<?php $this->billing_email(); ?><br>
						<?php $this->billing_phone(); ?></p>
				</div>

			</div>

			<?php do_action( 'wpo_wcpdf_after_order_data', $this->type, $this->order ); ?>

		</div>

		<div style="display:inline-block;width:30%;vertical-align:bottom;text-align:right;">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/Bien-invoice.png" alt="VaBien" width="61" height="170">
		</div>

	</div>

	<hr style="margin:50px 0;">

<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>


	<div class="item-list">

		<div class="col-header" style="margin-bottom: 25px;">

			<div style="display:inline-block;width:15%;vertical-align:top;">
				<p style="font-size: 18px;">#</p>
			</div>

			<div style="display:inline-block;width:50%;vertical-align:top;">
				<p style="font-size: 18px;">Item</p>
			</div>

			<div style="display:inline-block;width:15%;vertical-align:top;">
				<p style="font-size: 18px;">Qty</p>
			</div>

			<div style="display:inline-block;width:15%;vertical-align:top;">
				<p style="font-size: 18px;">Price</p>
			</div>

		</div>

		<div class="col-items">

			<?php $items = $this->order->get_items();

			if ( sizeof( $items ) > 0 ) :

				foreach ( $items as $item ) : ?>

					<div class="col-item" style="margin-bottom: 25px;">

						<div style="display:inline-block;width:15%;vertical-align:top;">


							<?php if ( $style_number = wc_get_order_item_meta( $item->get_id(), 'Style Number' ) ) : ?>
								<p style="font-size: 15px;"><?php _e( $style_number ); ?></p>

							<?php endif; ?>

						</div>

						<div style="display:inline-block;width:50%;vertical-align:top;">
							<?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>

							<p style="font-size: 14px;"><span><?php echo $item->get_name(); ?></span></p>

							<?php do_action( 'wpo_wcpdf_before_item_meta', $this->type, $item, $this->order ); ?>


						</div>

						<div style="display:inline-block;width:15%;vertical-align:top;">
							<p style="font-size: 15px;"><?php echo $item->get_quantity(); ?></p>
						</div>

						<div style="display:inline-block;width:15%;vertical-align:top;">
							<p style="font-size: 14px;"><?php echo $item->get_total(); ?></p>
						</div>

					</div>

				<?php endforeach;

			endif; ?>

		</div>

	</div>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>

<?php if ( $this->get_footer() ): ?>
	<div id="footer">

		<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>

		<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->type, $this->order ); ?>
		<?php if ( $this->get_shipping_notes() ) : ?>
			<p style="font-size: 14px;text-align:left;margin-bottom:0;"><?php _e( 'Customer Notes:', 'woocommerce-pdf-invoices-packing-slips' ); ?></span>
				<span><?php $this->shipping_notes(); ?></span></p>
		<?php endif; ?>
		<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->type, $this->order ); ?>

		<hr style="margin:20px 0;">

		<div class="footer-section">

			<div class="returns-col" style="display:inline-block;width:50%;vertical-align:top;">

				<p style="font-size: 14px;text-align:left;margin-bottom:0;">Returns & Exchanges:<br>Please contact us at 1 (888) 482 2436 or<br>hello@vabienlingerie.com
					for assistance</p>

			</div>

			<div class="totals-col" style="display:inline-block;width:50%;vertical-align:top;">

				<table class="totals">
					<tfoot>
					<?php foreach ( $this->get_woocommerce_totals() as $key => $total ) : ?>
						<tr class="<?php echo $key; ?>">
							<td class="no-borders"></td>
							<th class="description" style="width:30%;"><?php echo $total['label']; ?></th>
							<td class="price"><span class="totals-price"><?php echo $total['value']; ?></span></td>
						</tr>
					<?php endforeach; ?>
					</tfoot>
				</table>

			</div>

		</div>

		<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>
	</div><!-- #letter-footer -->
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_document', $this->type, $this->order ); ?>