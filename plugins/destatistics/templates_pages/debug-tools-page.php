<div class="wrap">
	<h2><?php _e( 'Logs Tools', DESTAT_TEXTDOMAIN ); ?></h2>
	<?php
		//		$comments = new DESTAT_Comments();
		//		$comments->getDisqusCommentsCountByUrl( 'http://ain.ua/2014/06/13/528549' );
	?>
	<div id="st-clicks-tags" class="postbox ajax-setting-ga">
		<h3 class="hndle">
			<span>&nbsp;&nbsp;&nbsp;<?php _e( 'Test Google Analytics Page Views', DESTAT_TEXTDOMAIN ); ?></span>
		</h3>

		<div class="inside" style="position: relative;">
			<form method="POST">
				<input type="hidden" name="ga_debug_pageviews" value="1">
				<input type="text" name="url" id="DESTAT_ga_debug_pageviews" style="width: 500px;" value="<?php echo $_POST['url']; ?>">
				<br><br>

				<p>
					<input type="submit" class="button button-primary" value="<?php _e( 'Get data', DESTAT_TEXTDOMAIN ); ?>">
				</p>
			</form>
		</div>

		<div class="inside ga_debug_results" style="position: relative;overflow: hidden;word-wrap: break-word;">

			<?php
				if ( $_POST['ga_debug_pageviews'] ) {
					$gp  = new DESTAT_Google();
					$url = $_POST['url'];
					/*					$url     = str_replace( 'http://', '', $url );
										$url     = str_replace( 'https://', '', $url );
										$url_arr = explode( '/', $url );
										array_shift( $url_arr );
										$url = implode( '/', $url_arr );*/

					$res = $gp->getPageviewsByUrl( $url, true );

				}
			?>
			<h2><strong><?php _e( 'Page views', DESTAT_TEXTDOMAIN ); ?>:
					<?php
						if ( ! is_wp_error( $res ) ) {
							$ResponseBody = wp_remote_retrieve_body( $res );
							$oResponse    = json_decode( $ResponseBody, true );


							echo $oResponse['totalsForAllResults']['ga:pageviews'];

						}

					?>
				</strong>
			</h2>
			<hr>
			<h2><?php _e( 'Details request', DESTAT_TEXTDOMAIN ); ?>:</h2>
			<pre>
<?php print_r( $res ); ?>

			</pre>
		</div>

	</div>

</div>