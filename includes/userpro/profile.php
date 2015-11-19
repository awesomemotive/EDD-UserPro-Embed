<?php
/**
 * Add fields to UserPro profile
 *
 * @package     EDD\UserPro_Embed\UserPro\Profile
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}


function edd_userpro_embed_profile_fields( $hook_args ) {
	if( ! current_user_can( 'edit_user', $hook_args['user_id'] ) ) {
		return;
	}

	echo '<div class="userpro-section userpro-column userpro-collapsible-1 userpro-collapsed-1">' . __( 'Purchase History', 'edd-userpro-embed' ) . '</div>';
	echo '<div class="userpro-field userpro-field-edd-purchase-history userpro-field-view" data-key="edd-purchase-history">';

	$purchases = edd_get_users_purchases( $hook_args['user_id'], 99999, true, 'any' );

	if( $purchases ) {
		do_action( 'edd_before_purchase_history' ); ?>
		<table id="edd_user_history">
			<thead>
				<tr class="edd_purchase_row">
					<?php do_action('edd_purchase_history_header_before'); ?>
					<th class="edd_purchase_id"><?php _e('ID','easy-digital-downloads' ); ?></th>
					<th class="edd_purchase_date"><?php _e('Date','easy-digital-downloads' ); ?></th>
					<th class="edd_purchase_amount"><?php _e('Amount','easy-digital-downloads' ); ?></th>
					<th class="edd_purchase_details"><?php _e('Details','easy-digital-downloads' ); ?></th>
					<?php do_action('edd_purchase_history_header_after'); ?>
				</tr>
			</thead>
			<?php foreach ( $purchases as $post ) : setup_postdata( $post ); ?>
				<?php $purchase_data = edd_get_payment_meta( $post->ID ); ?>
				<tr class="edd_purchase_row">
					<?php do_action( 'edd_purchase_history_row_start', $post->ID, $purchase_data ); ?>
					<td class="edd_purchase_id">#<?php echo edd_get_payment_number( $post->ID ); ?></td>
					<td class="edd_purchase_date"><?php echo date_i18n( get_option('date_format'), strtotime( get_post_field( 'post_date', $post->ID ) ) ); ?></td>
					<td class="edd_purchase_amount">
						<span class="edd_purchase_amount"><?php echo edd_currency_filter( edd_format_amount( edd_get_payment_amount( $post->ID ) ) ); ?></span>
					</td>
					<td class="edd_purchase_details">
						<?php if( $post->post_status != 'publish' ) : ?>
						<span class="edd_purchase_status <?php echo $post->post_status; ?>"><?php echo edd_get_payment_status( $post, true ); ?></span>
						<a href="<?php echo esc_url( add_query_arg( 'payment_key', edd_get_payment_key( $post->ID ), edd_get_success_page_uri() ) ); ?>">&raquo;</a>
						<?php else: ?>
						<a href="<?php echo esc_url( add_query_arg( 'payment_key', edd_get_payment_key( $post->ID ), edd_get_success_page_uri() ) ); ?>"><?php _e( 'View Details', 'edd-userpro-embed' ); ?></a>
						<?php endif; ?>
					</td>
					<?php do_action( 'edd_purchase_history_row_end', $post->ID, $purchase_data ); ?>
				</tr>
			<?php endforeach; ?>
		</table>
		<?php do_action( 'edd_after_purchase_history' ); ?>
		<?php wp_reset_postdata(); ?>
	<?php } else { ?>
		<p class="edd-no-purchases"><?php _e('You have not made any purchases','edd-userpro-embed' ); ?></p>
	<?php
	}

	echo '</div>';
}
add_action( 'userpro_after_fields', 'edd_userpro_embed_profile_fields' );