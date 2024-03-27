<?php

/**
 * Settings panel view for the plugin
 *
 * @link       http://www.69signals.com
 * @since      0.1
 * @package    Signals_Maintenance_Mode
 */

require_once 'header.php';

?>

	<form role="form" method="post" class="signals-admin-form">
		<div class="signals-body signals-clearfix">
			<?php

				// Display the message if $signals_csmm_err is set
				if ( isset( $signals_csmm_err ) ) {
					echo $signals_csmm_err;
				}

			?>

			<div class="signals-float-left">
				<div class="signals-mobile-menu">
					<a href="javascript:void;">
						<img src="<?php echo SIGNALS_CSMM_URL; ?>/framework/admin/img/toggle.png" />
					</a>
				</div>

				<ul class="signals-main-menu">
					<li><a href="#basic"><?php _e( 'Basic', 'signals' ); ?></a></li>
					<li><a href="#email"><?php _e( 'Email', 'signals' ); ?></a></li>
					<li><a href="#design"><?php _e( 'Design', 'signals' ); ?></a></li>
					<li><a href="#form"><?php _e( 'Form', 'signals' ); ?></a></li>
					<li><a href="#advanced"><?php _e( 'Advanced', 'signals' ); ?></a></li>
					<li><a href="#support"><?php _e( 'Support', 'signals' ); ?></a></li>
					<li><a href="#about"><?php _e( 'About', 'signals' ); ?></a></li>
				</ul>
			</div><!-- .signals-float-left -->

			<div class="signals-float-right">
				<?php

					// Including tabs content
					require_once 'settings-basic.php';		// basic
					require_once 'settings-email.php';		// email
					require_once 'settings-design.php';		// design
					require_once 'settings-form.php';		// form
					require_once 'settings-advanced.php';	// advanced
					require_once 'settings-support.php';	// support
					require_once 'settings-about.php';		// about

				?>
			</div><!-- .signals-float-right -->

			<div class="signals-fixed-save-btn">
				<div class="signals-tile-body">
					<p class="signals-form-help-block" style="margin: 0; padding: 0 20px 0 10px">
						<button type="submit" name="signals_csmm_submit" class="signals-btn signals-btn-red"><strong><?php _e( 'Save Changes', 'signals' ); ?></strong></button>
					</p>
				</div><!-- .signals-tile-body -->
			</div><!-- .signals-fixed-save-btn -->
		</div><!-- .signals-body -->
	</form><!-- form.signals-admin-form -->

<?php

require_once 'footer.php';