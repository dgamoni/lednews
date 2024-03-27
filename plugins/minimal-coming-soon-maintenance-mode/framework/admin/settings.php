<?php

/**
 * Settings page for the plugin
 *
 * @link       http://www.69signals.com
 * @since      0.1
 * @package    Signals_Maintenance_Mode
 */

function csmm_admin_settings() {

	// Including the mailchimp class
	require_once 'include/classes/class-mailchimp.php';


	// List of Google fonts
	require_once 'include/fonts.php';


	// Saving (updating) options over here
	if ( isset( $_POST['signals_csmm_submit'] ) ) {

		// Checking whether the status option is checked or not
		if ( isset( $_POST['signals_csmm_status'] ) ) :
			$tmp_options['status'] = absint( $_POST['signals_csmm_status'] );
		else :
			$tmp_options['status'] = '2';
		endif;


		// Checking whether the user logged in option is checked or not
		if ( isset( $_POST['signals_csmm_showlogged'] ) ) :
			$tmp_options['logged'] = absint( $_POST['signals_csmm_showlogged'] );
		else :
			$tmp_options['logged'] = '2';
		endif;


		// Checking whether the search engine exclusion option is checked or not
		if ( isset( $_POST['signals_csmm_excludese'] ) ) :
			$tmp_options['exclude_se'] = absint( $_POST['signals_csmm_excludese'] );
		else :
			$tmp_options['exclude_se'] = '2';
		endif;


		// For the MailChimp list ID
		if ( isset( $_POST['signals_csmm_list'] ) ) :
			$tmp_options['list'] = strip_tags( $_POST['signals_csmm_list'] );
		else :
			$tmp_options['list'] = '';
		endif;


		// For content overlay
		if ( isset( $_POST['signals_csmm_overlay'] ) ) :
			$tmp_options['overlay'] = absint( $_POST['signals_csmm_overlay'] );
		else :
			$tmp_options['overlay'] = '2';
		endif;


		// Checking whether the ignore form styles option is checked or not
		if ( isset( $_POST['signals_csmm_ignore_styles'] ) ) :
			$tmp_options['form_styles'] = absint( $_POST['signals_csmm_ignore_styles'] );
		else :
			$tmp_options['form_styles'] = '2';
		endif;


		// Checking whether the disable plugin option is checked or not
		if ( isset( $_POST['signals_csmm_disable'] ) ) :
			$tmp_options['disabled'] = absint( $_POST['signals_csmm_disable'] );
		else :
			$tmp_options['disabled'] = '2';
		endif;


		// Saving the record to the database
		$update_options = array(
			'status'				=> $tmp_options['status'],
			'title' 				=> strip_tags( $_POST['signals_csmm_title'] ),
			'header_text' 			=> strip_tags( $_POST['signals_csmm_header'] ),
			'secondary_text' 		=> $_POST['signals_csmm_secondary'],
			'antispam_text' 		=> strip_tags( $_POST['signals_csmm_antispam'] ),
			'custom_login_url' 		=> strip_tags( $_POST['signals_csmm_custom_login'] ),
			'show_logged_in' 		=> $tmp_options['logged'],
			'exclude_se'			=> $tmp_options['exclude_se'],
			'mailchimp_api'			=> strip_tags( $_POST['signals_csmm_api'] ),
			'mailchimp_list' 		=> $tmp_options['list'],
			'logo'					=> strip_tags( $_POST['signals_csmm_logo'] ),
			'favicon'				=> strip_tags( $_POST['signals_csmm_favicon'] ),

			'bg_cover' 				=> strip_tags( $_POST['signals_csmm_bg'] ),
			'content_overlay' 		=> $tmp_options['overlay'],
			'content_width'			=> absint( $_POST['signals_csmm_width'] ),
			'bg_color' 				=> strip_tags( $_POST['signals_csmm_color'] ),
			'content_position'		=> strip_tags( $_POST['signals_csmm_position'] ),
			'content_alignment'		=> strip_tags( $_POST['signals_csmm_alignment'] ),

			'header_font' 			=> strip_tags( $_POST['signals_csmm_header_font'] ),
			'secondary_font' 		=> strip_tags( $_POST['signals_csmm_secondary_font'] ),
			'header_font_size' 		=> strip_tags( $_POST['signals_csmm_header_size'] ),
			'secondary_font_size' 	=> strip_tags( $_POST['signals_csmm_secondary_size'] ),
			'header_font_color' 	=> strip_tags( $_POST['signals_csmm_header_color'] ),
			'secondary_font_color' 	=> strip_tags( $_POST['signals_csmm_secondary_color'] ),

			'antispam_font_size' 	=> strip_tags( $_POST['signals_csmm_antispam_size'] ),
			'antispam_font_color' 	=> strip_tags( $_POST['signals_csmm_antispam_color'] ),

			'input_text' 			=> strip_tags( $_POST['signals_csmm_input_text'] ),
			'button_text' 			=> strip_tags( $_POST['signals_csmm_button_text'] ),

			'ignore_form_styles' 	=> $tmp_options['form_styles'],

			'input_font_size'		=> strip_tags( $_POST['signals_csmm_input_size'] ),
			'button_font_size'		=> strip_tags( $_POST['signals_csmm_button_size'] ),
			'input_font_color'		=> strip_tags( $_POST['signals_csmm_input_color'] ),
			'button_font_color'		=> strip_tags( $_POST['signals_csmm_button_color'] ),

			'input_bg'				=> strip_tags( $_POST['signals_csmm_input_bg'] ),
			'button_bg'				=> strip_tags( $_POST['signals_csmm_button_bg'] ),
			'input_bg_hover'		=> strip_tags( $_POST['signals_csmm_input_bg_hover'] ),
			'button_bg_hover'		=> strip_tags( $_POST['signals_csmm_button_bg_hover'] ),

			'input_border'			=> strip_tags( $_POST['signals_csmm_input_border'] ),
			'button_border'			=> strip_tags( $_POST['signals_csmm_button_border'] ),
			'input_border_hover'	=> strip_tags( $_POST['signals_csmm_input_border_hover'] ),
			'button_border_hover'	=> strip_tags( $_POST['signals_csmm_button_border_hover'] ),

			'disable_settings' 		=> $tmp_options['disabled'],
			'custom_html'			=> $_POST['signals_csmm_html'], // Not sanitizing the HTML and CSS provided by the admin
			'custom_css'			=> $_POST['signals_csmm_css']  // Giving full freedom to them :)
		);


		// Updating the options in the database and showing message to the user
		update_option( 'signals_csmm_options', $update_options );
		$signals_csmm_err = '<div class="signals-alert signals-alert-info"><strong>Hey!</strong> Options have been updated.</div>';

	}


	// Grab options from the database
	$signals_csmm_options = get_option( 'signals_csmm_options' );


	// Grabbing admin_email from the database
	$signals_admin_email  = get_option( 'admin_email', '' );


	// View template for the settings panel
	require 'views/settings.php';

}



// AJAX request for user support
function csmm_ajax_support() {

	// We are going to store the response in the $response() array
	$response = array(
		'code' 		=> 'error',
		'response' 	=> __( 'Please fill in both the fields to create your support ticket.', 'signals' )
	);


	// Filtering and sanitizing the support issue
	if ( ! empty( $_POST['signals_support_email'] ) && ! empty( $_POST['signals_support_issue'] ) ) {
		$admin_email 	= sanitize_text_field( $_POST['signals_support_email'] );
		$issue 			= $_POST['signals_support_issue'];

		$subject 		= '[Maintenance Mode Ticket] by '. $admin_email;
		$body 			= "Email: $admin_email \r\nIssue: $issue";
		$headers 		= 'From: ' . $admin_email . "\r\n" . 'Reply-To: ' . $admin_email;


		// Sending the mail to the support email
		if ( true === wp_mail( 'support@69signals.com', $subject, $body, $headers ) ) {
			// Sending the success response
			$response = array(
				'code' 		=> 'success',
				'response' 	=> __( 'We have received your support ticket. We will get back to you shortly!', 'signals' )
			);
		} else {
			// Sending the failure response
			$response = array(
				'code' 		=> 'error',
				'response' 	=> __( 'There was an error creating the support ticket. You can try again later or send us an email directly to <strong>support@69signals.com</strong>', 'signals' )
			);
		}
	}


	// Sending proper headers and sending the response back in the JSON format
	header( "Content-Type: application/json" );
	echo json_encode( $response );


	// Exiting the AJAX function. This is always required
	exit();

}
add_action( 'wp_ajax_signals_csmm_support', 'csmm_ajax_support' );