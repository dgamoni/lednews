<?php

/**
 * Provide a admin header view for the plugin
 *
 * @link       http://www.69signals.com
 * @since      0.1
 * @package    Signals_Maintenance_Mode
 */

?>

<div class="signals-cnt-fix">
	<div class="signals-fix-wp38">
		<div class="signals-header signals-clearfix">
			<img src="<?php echo SIGNALS_CSMM_URL; ?>/framework/admin/img/lrg-icon.png" class="signals-logo">
			<p>
				<strong><?php _e( 'Maintenance Mode', 'signals' ); ?></strong>
				<span><?php _e( 'by', 'signals' ); ?> <a href="http://www.69signals.com/" target="_blank"><?php _e( '69signals', 'signals' ); ?></a></span>
			</p>

			<?php if ( isset( $signals_header_addon ) ) { echo $signals_header_addon; } ?>
		</div><!-- .signals-header -->