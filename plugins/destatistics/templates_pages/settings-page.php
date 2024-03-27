<div class="wrap">
	<h2><?php _e( 'Settings de:statistics', DESTAT_TEXTDOMAIN ); ?></h2>
	<?php $gp = new DESTAT_Google(); ?>
	<br><br>

	<div id="st-clicks-tags" class="postbox ">
		<h3 class="hndle"><span>&nbsp;&nbsp;&nbsp;<?php _e( 'Sync posts for Stats', DESTAT_TEXTDOMAIN ); ?></span>
		</h3>

		<div class="inside">

			<form method="POST">
				<input type="hidden" name="CopyTypePostsToStatsTable" value="1">

				<input type="submit" class="button button-primary" value="<?php _e( 'Go Sync', DESTAT_TEXTDOMAIN ); ?>">

			</form>
		</div>
	</div>

	<div id="st-clicks-tags" class="postbox ajax-setting-ga">
		<div id="ajax-setting-ga">
			<h3 class="hndle"><span>&nbsp;&nbsp;&nbsp;<?php _e( 'Google Analytics', DESTAT_TEXTDOMAIN ); ?></span>
			</h3>

			<div class="inside" style="position: relative;">
				<form method="POST">
					<input type="hidden" name="GaOptionsForm" value="1">
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e( 'Enable GA', DESTAT_TEXTDOMAIN ); ?></th>
							<td>
								<input type="checkbox" name="GaEnable" value="enable"<?php checked( 'enable', get_option( "GaEnable" ), true ); ?> />
							</td>
							<td>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php if ( ! $DESTAT_ga_auth_code = get_option( "GAAuthCode" ) ) { ?>
									<?php _e( 'Google Authentication Code', DESTAT_TEXTDOMAIN ); ?>
								<?php } ?>
							</th>
							<td>
								<?php if ( $DESTAT_ga_auth_code = get_option( "GAAuthCode" ) ) { ?>
									<!--							<input type="hidden" name="DeleteGAAccess" value="1">-->
									<a href="#" id="DeleteDeAccess" class="button button-primary" data-network="<?php echo is_network_admin() ? '1' : ''; ?>"><?php _e( 'Annul Access for GA', DESTAT_TEXTDOMAIN ); ?></a>
									<input type="hidden" name="GAAuthCode" id="DESTAT_ga_auth_code" value="<?php echo $DESTAT_ga_auth_code; ?>">

								<?php } else { ?>
									<input type="text" name="GAAuthCode" id="DESTAT_ga_auth_code" value="<?php echo $DESTAT_ga_auth_code; ?>">
								<?php } ?>
							</td>
							<td>
							</td>
							<td></td>
						</tr>


						<tr>
							<th scope="row"></th>
							<td>
							</td>
						</tr>

						<?php
						$oProfiles = $gp->GetProfiles();

						if ( count( $oProfiles->items ) > 0 ) {
							?>
							<tr>
								<th scope="row">
									<?php _e( 'Select a profile', DESTAT_TEXTDOMAIN ); ?>:
								</th>
								<td>
									<select name="GAUserProfile">

										<option value=""
											<?php selected( '', get_option( 'GAUserProfile' ) ); ?>>
											<?php _e( 'Choose profile ...', DESTAT_TEXTDOMAIN ); ?>
										</option>
										<?php foreach ( $oProfiles->items as $oItem ) { ?>
											<option value="<?php echo $oItem->id; ?>"
												<?php selected( $oItem->id, get_option( 'GAUserProfile' ) ) ?> > <?php echo $oItem->websiteUrl . ' (' . $oItem->id . ')'; ?>
											</option>
										<?php } ?>
									</select>
								</td>
								<td></td>
								<td></td>
							</tr>
						<?php } else { ?>
							<tr>
								<th scope="row"></th>
								<td>
									<?php $gp->ShowAuthLink(); ?>
								</td>
								<td></td>
								<td></td>
							</tr>
						<?php } ?>
					</table>
					<input type="submit" class="button button-primary" value="<?php _e( 'Save', DESTAT_TEXTDOMAIN ); ?>">
				</form>
			</div>
		</div>
	</div>

	<div id="st-clicks-tags" class="postbox ">
		<h3 class="hndle"><span>&nbsp;&nbsp;&nbsp;<?php _e( 'Comments Services', DESTAT_TEXTDOMAIN ); ?></span>
		</h3>

		<div class="inside">
			<form method="POST">
				<input type="hidden" name="CommentsServicesForm" value="1">
				<table class="form-table">
					<tr>
						<th scope="row">
							<?php _e( 'Disqus', DESTAT_TEXTDOMAIN ); ?>:
						</th>
						<td>
							<fieldset>
								<label><input name="DESTAT_disqus_enable" type="checkbox" id="DESTAT_disqus_enable" value="enable"<?php checked( 'enable', get_option( "DESTAT_disqus_enable" ), true ); ?> /> <?php _e( 'Enable', DESTAT_TEXTDOMAIN ); ?>
								</label><br />
							</fieldset>
						</td>

						<td>
							<fieldset>
								<label>
									<?php _e( 'Site ID', DESTAT_TEXTDOMAIN ); ?>
									<input name="DESTAT_disqus_site_id" type="text" id="DESTAT_disqus_site_id" value="<?php echo get_option( "DESTAT_disqus_site_id" ); ?>" />
								</label>
							</fieldset>
						</td>
					</tr>
				</table>
				<input type="submit" class="button button-primary" value="<?php _e( 'Save', DESTAT_TEXTDOMAIN ); ?>">
			</form>


		</div>
	</div>


	<div id="st-clicks-tags" class="postbox ">
		<h3 class="hndle"><span>&nbsp;&nbsp;&nbsp;<?php _e( 'Social', DESTAT_TEXTDOMAIN ); ?></span>
		</h3>

		<div class="inside">
			<form method="POST">
				<input type="hidden" name="OtherOptionsForm" value="1">
				<table class="form-table">
					<tr>
						<th scope="row">
							<?php _e( 'Facebook', DESTAT_TEXTDOMAIN ); ?>:
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Enable?</span></legend>
								<label><input name="de_fb_enable" type="checkbox" id="de_fb_enable" value="enable"<?php checked( 'enable', get_option( "de_fb_enable" ), true ); ?> /> <?php _e( 'Enable', DESTAT_TEXTDOMAIN ); ?>
								</label><br />
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e( 'Twitter', DESTAT_TEXTDOMAIN ); ?>:
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
								<span>
									<?php _e( 'Enable?', DESTAT_TEXTDOMAIN ); ?>
								</span>
								</legend>
								<label><input name="de_twi_enable" type="checkbox" id="de_twi_enable" value="enable"<?php checked( 'enable', get_option( "de_twi_enable" ), true ); ?> /> <?php _e( 'Enable', DESTAT_TEXTDOMAIN ); ?>
								</label><br />
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e( 'Vkontakte', DESTAT_TEXTDOMAIN ); ?>:
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Enable?</span></legend>
								<label><input name="de_vk_enable" type="checkbox" id="de_vk_enable" value="enable"<?php checked( 'enable', get_option( "de_vk_enable" ), true ); ?> /> <?php _e( 'Enable', DESTAT_TEXTDOMAIN ); ?>
								</label><br />
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e( 'Google Plus', DESTAT_TEXTDOMAIN ); ?>:
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
								<span>
									<?php _e( 'Enable?', DESTAT_TEXTDOMAIN ); ?>
								</span></legend>
								<label><input name="de_gplus_enable" type="checkbox" id="de_gplus_enable" value="enable"<?php checked( 'enable', get_option( "de_gplus_enable" ), true ); ?> /> <?php _e( 'Enable', DESTAT_TEXTDOMAIN ); ?>
								</label><br />
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Pocket', DESTAT_TEXTDOMAIN ); ?>:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>
									<?php _e( 'Enable?', DESTAT_TEXTDOMAIN ); ?>
								</span></legend>
								<label><input name="de_pocket_enable" type="checkbox" id="de_pocket_enable" value="enable"<?php checked( 'enable', get_option( "de_pocket_enable" ), true ); ?> /> <?php _e( 'Enable', DESTAT_TEXTDOMAIN ); ?>
								</label><br />
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e( 'LinkedIn', DESTAT_TEXTDOMAIN ); ?>:
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
								<span>
									<?php _e( 'Enable?', DESTAT_TEXTDOMAIN ); ?>
								</span></legend>
								<label><input name="de_li_enable" type="checkbox" id="de_li_enable" value="enable"<?php checked( 'enable', get_option( "de_li_enable" ), true ); ?> /> <?php _e( 'Enable', DESTAT_TEXTDOMAIN ); ?>
								</label><br />
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Odnoklassniki', DESTAT_TEXTDOMAIN ); ?>:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>
									<?php _e( 'Enable?', DESTAT_TEXTDOMAIN ); ?>
								</span></legend>
								<label><input name="de_ok_enable" type="checkbox" id="de_ok_enable" value="enable"<?php checked( 'enable', get_option( "de_ok_enable" ), true ); ?> /> <?php _e( 'Enable', DESTAT_TEXTDOMAIN ); ?>
								</label><br />
							</fieldset>
						</td>
					</tr>
					<?php
					$posts_type       = new DESTAT_Functions();
					$posts_type_arr   = $posts_type->getPostTypes();
					$DESTAT_Post_Type = get_option( "DESTAT_Post_Type" ); ?>
					<tr>
						<th scope="row"><?php _e( 'Choose type posts', DESTAT_TEXTDOMAIN ); ?>:</th>
						<td>
							<fieldset>
								<?php foreach ( $posts_type_arr as $Key => $Value ) { ?>
									<label><input name="DESTAT_Post_Type[]" type="checkbox" id="DESTAT_Post_Type" value="<?php echo $Key ?>"<?php if ( in_array( $Key, $DESTAT_Post_Type ) ) {
											echo ' checked="checked"';
										} ?> /> <?php echo $Value->labels->name; ?></label><br />
								<?php } ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Days new posts', DESTAT_TEXTDOMAIN ); ?>
							<br>
							<small>
								<i><?php _e( 'The number of days for which the post is new', DESTAT_TEXTDOMAIN ); ?></i>
							</small>
						</th>
						<td>
							<input style="width:50px;" name="DESTAT_New_Posts_Day" type="text" id="DESTAT_New_Posts_Day" value="<?php echo get_option( "DESTAT_New_Posts_Day" ); ?>" /><?php _e( 'days', DESTAT_TEXTDOMAIN ); ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Days old posts', DESTAT_TEXTDOMAIN ); ?>
							<br>
							<small>
								<i><?php _e( 'The number of days for which the post is old', DESTAT_TEXTDOMAIN ); ?></i>
							</small>
						</th>
						<td>
							<input style="width:50px;" name="DESTAT_Old_Posts_Day" type="text" id="DESTAT_Old_Posts_Day" value="<?php echo get_option( "DESTAT_Old_Posts_Day" ); ?>" /> <?php _e( 'days', DESTAT_TEXTDOMAIN ); ?>

						</td>
					</tr>

				</table>
				<input type="submit" class="button button-primary" value="<?php _e( 'Save', DESTAT_TEXTDOMAIN ); ?>">
			</form>


		</div>
	</div>


	<div id="st-clicks-tags" class="postbox ">
		<h3 class="hndle"><span>&nbsp;&nbsp;&nbsp;<?php _e( 'WP-Cron', DESTAT_TEXTDOMAIN ); ?></span>
		</h3>

		<div class="inside">


			<form method="POST">
				<input type="hidden" name="DeCronJobs" value="1">
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'Enable', DESTAT_TEXTDOMAIN ); ?></th>
						<td>
							<input type="checkbox" name="DESTAT_Cron_Enable" value="enable"<?php checked( 'enable', get_option( "DESTAT_Cron_Enable" ), true ); ?> />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Current day update posts', DESTAT_TEXTDOMAIN ); ?>:</th>
						<td>
							<select name="DESTAT_Cron_One_Day_Posts">
								<option value=""><?php _e( 'Disable', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_2_sec" <?php selected( 'every_2_sec', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 2 sec', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_13_sec" <?php selected( 'every_13_sec', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 13 sec', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_20_sec" <?php selected( 'every_20_sec', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 20 sec', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_30_sec" <?php selected( 'every_30_sec', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 30 sec', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_1_min" <?php selected( 'every_1_min', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 1 min', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_2_min" <?php selected( 'every_2_min', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 2 min', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_3_min" <?php selected( 'every_3_min', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 3 min', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_five_min"<?php selected( 'every_five_min', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 5 min', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_ten_min"<?php selected( 'every_ten_min', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 10 min', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_twelve_min"<?php selected( 'every_twelve_min', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 20 min', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_thirty_min"<?php selected( 'every_thirty_min', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 30 min', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_1_hours"<?php selected( 'every_1_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 1 hour', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_2_hours"<?php selected( 'every_2_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 2 hours', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_3_hours"<?php selected( 'every_3_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 3 hours', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_4_hours"<?php selected( 'every_4_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 4 hours', DESTAT_TEXTDOMAIN ); ?></option>
							</select>
						</td>
						<td></td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'New Update posts', DESTAT_TEXTDOMAIN ); ?>:</th>
						<td>
							<select name="DESTAT_Cron_New_Posts">
								<option value=""><?php _e( 'Disable', DESTAT_TEXTDOMAIN ); ?></option>


								<option value="every_2_sec" <?php selected( 'every_2_sec', get_option( "DESTAT_Cron_New_Posts" ), true ); ?>><?php _e( 'every 2 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_13_sec" <?php selected( 'every_13_sec', get_option( "DESTAT_Cron_New_Posts" ), true ); ?>><?php _e( 'every 13 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_20_sec" <?php selected( 'every_20_sec', get_option( "DESTAT_Cron_New_Posts" ), true ); ?>><?php _e( 'every 20 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_30_sec" <?php selected( 'every_30_sec', get_option( "DESTAT_Cron_New_Posts" ), true ); ?>><?php _e( 'every 30 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_1_min" <?php selected( 'every_1_min', get_option( "DESTAT_Cron_New_Posts" ), true ); ?>><?php _e( 'every 1 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_2_min" <?php selected( 'every_2_min', get_option( "DESTAT_Cron_New_Posts" ), true ); ?>><?php _e( 'every 2 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_3_min" <?php selected( 'every_3_min', get_option( "DESTAT_Cron_New_Posts" ), true ); ?>><?php _e( 'every 3 min', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_five_min"<?php selected( 'every_five_min', get_option( "DESTAT_Cron_New_Posts" ), true ); ?>><?php _e( 'every 5 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_ten_min"<?php selected( 'every_ten_min', get_option( "DESTAT_Cron_New_Posts" ), true ); ?>><?php _e( 'every 10 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_twelve_min"<?php selected( 'every_twelve_min', get_option( "DESTAT_Cron_New_Posts" ), true ); ?>><?php _e( 'every 20 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_thirty_min"<?php selected( 'every_thirty_min', get_option( "DESTAT_Cron_New_Posts" ), true ); ?>><?php _e( 'every 30 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_1_hours"<?php selected( 'every_1_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 1 hour', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_2_hours"<?php selected( 'every_2_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 2 hours', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_3_hours"<?php selected( 'every_3_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 3 hours', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_4_hours"<?php selected( 'every_4_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 4 hours', DESTAT_TEXTDOMAIN ); ?></option>

							</select>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Old update posts', DESTAT_TEXTDOMAIN ); ?>:</th>
						<td>
							<select name="DESTAT_Cron_Old_Posts">
								<option value=""><?php _e( 'Disable', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_2_sec" <?php selected( 'every_2_sec', get_option( "DESTAT_Cron_Old_Posts" ), true ); ?>><?php _e( 'every 2 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_13_sec" <?php selected( 'every_13_sec', get_option( "DESTAT_Cron_Old_Posts" ), true ); ?>><?php _e( 'every 13 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_20_sec" <?php selected( 'every_20_sec', get_option( "DESTAT_Cron_Old_Posts" ), true ); ?>><?php _e( 'every 20 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_30_sec" <?php selected( 'every_30_sec', get_option( "DESTAT_Cron_Old_Posts" ), true ); ?>><?php _e( 'every 30 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_1_min" <?php selected( 'every_1_min', get_option( "DESTAT_Cron_Old_Posts" ), true ); ?>><?php _e( 'every 1 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_2_min" <?php selected( 'every_2_min', get_option( "DESTAT_Cron_Old_Posts" ), true ); ?>><?php _e( 'every 2 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_3_min" <?php selected( 'every_3_min', get_option( "DESTAT_Cron_Old_Posts" ), true ); ?>><?php _e( 'every 3 min', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_five_min"<?php selected( 'every_five_min', get_option( "DESTAT_Cron_Old_Posts" ), true ); ?>><?php _e( 'every 5 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_ten_min"<?php selected( 'every_ten_min', get_option( "DESTAT_Cron_Old_Posts" ), true ); ?>><?php _e( 'every 10 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_twelve_min"<?php selected( 'every_twelve_min', get_option( "DESTAT_Cron_Old_Posts" ), true ); ?>><?php _e( 'every 20 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_thirty_min"<?php selected( 'every_thirty_min', get_option( "DESTAT_Cron_Old_Posts" ), true ); ?>><?php _e( 'every 30 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_1_hours"<?php selected( 'every_1_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 1 hour', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_2_hours"<?php selected( 'every_2_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 2 hours', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_3_hours"<?php selected( 'every_3_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 3 hours', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_4_hours"<?php selected( 'every_4_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 4 hours', DESTAT_TEXTDOMAIN ); ?></option>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'All old update posts', DESTAT_TEXTDOMAIN ); ?>:</th>
						<td>
							<select name="DESTAT_Cron_All_Old_Posts">
								<option value=""><?php _e( 'Disable', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_2_sec" <?php selected( 'every_2_sec', get_option( "DESTAT_Cron_All_Old_Posts" ), true ); ?>><?php _e( 'every 2 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_13_sec" <?php selected( 'every_13_sec', get_option( "DESTAT_Cron_All_Old_Posts" ), true ); ?>><?php _e( 'every 13 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_20_sec" <?php selected( 'every_20_sec', get_option( "DESTAT_Cron_All_Old_Posts" ), true ); ?>><?php _e( 'every 20 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_30_sec" <?php selected( 'every_30_sec', get_option( "DESTAT_Cron_All_Old_Posts" ), true ); ?>><?php _e( 'every 30 sec', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_1_min" <?php selected( 'every_1_min', get_option( "DESTAT_Cron_All_Old_Posts" ), true ); ?>><?php _e( 'every 1 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_2_min" <?php selected( 'every_2_min', get_option( "DESTAT_Cron_All_Old_Posts" ), true ); ?>><?php _e( 'every 2 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_3_min" <?php selected( 'every_3_min', get_option( "DESTAT_Cron_All_Old_Posts" ), true ); ?>><?php _e( 'every 3 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_five_min" <?php selected( 'every_five_min', get_option( "DESTAT_Cron_All_Old_Posts" ), true ); ?>><?php _e( 'every 5 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_ten_min" <?php selected( 'every_ten_min', get_option( "DESTAT_Cron_All_Old_Posts" ), true ); ?>><?php _e( 'every 10 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_twelve_min" <?php selected( 'every_twelve_min', get_option( "DESTAT_Cron_All_Old_Posts" ), true ); ?>><?php _e( 'every 20 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_thirty_min" <?php selected( 'every_thirty_min', get_option( "DESTAT_Cron_All_Old_Posts" ), true ); ?>><?php _e( 'every 30 min', DESTAT_TEXTDOMAIN ); ?></option>
								<option value="every_1_hours"<?php selected( 'every_1_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 1 hour', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_2_hours"<?php selected( 'every_2_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 2 hours', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_3_hours"<?php selected( 'every_3_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 3 hours', DESTAT_TEXTDOMAIN ); ?></option>

								<option value="every_4_hours"<?php selected( 'every_4_hours', get_option( "DESTAT_Cron_One_Day_Posts" ), true ); ?>><?php _e( 'every 4 hours', DESTAT_TEXTDOMAIN ); ?></option>
							</select>
						</td>
					</tr>
				</table>
				<input type="submit" class="button button-primary" value="<?php _e( 'Save', DESTAT_TEXTDOMAIN ); ?>">
			</form>

		</div>
	</div>

	<?php if ( ! is_network_admin() ) { ?>
		<div id="st-clicks-tags" class="postbox ">
			<h3 class="hndle"><span>&nbsp;&nbsp;&nbsp;<?php _e( 'Test mode', DESTAT_TEXTDOMAIN ); ?></span>
			</h3>

			<div class="inside">
				<form method="POST">
					<input type="hidden" name="DESTAT_test_mode_form" value="1">
					<table class="form-table">
						<tr>
							<th scope="row">
								<?php _e( 'Enable', DESTAT_TEXTDOMAIN ); ?>:
							</th>
							<td>
								<fieldset>
									<label><input name="DESTAT_test_mode_on" type="checkbox" id="de_fb_enable" value="1" <?php checked( '1', get_option( "DESTAT_test_mode_on" ), true ); ?> /> <?php _e( 'Enable', DESTAT_TEXTDOMAIN ); ?>
									</label><br />
								</fieldset>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e( 'Replace domain from', DESTAT_TEXTDOMAIN ); ?>
							</th>
							<td>
								<input style="width:500px;" name="DESTAT_test_mode_replace_domain_from" type="text" id="DESTAT_test_mode_replace_domain_from" value="<?php echo get_option( "DESTAT_test_mode_replace_domain_from" ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Replace domain to', DESTAT_TEXTDOMAIN ); ?>
							</th>
							<td>
								<input style="width:500px;" name="DESTAT_test_mode_replace_domain_to" type="text" id="DESTAT_test_mode_replace_domain_to" value="<?php echo get_option( "DESTAT_test_mode_replace_domain_to" ); ?>" />

							</td>
						</tr>

					</table>
					<input type="submit" class="button button-primary" value="<?php _e( 'Save', DESTAT_TEXTDOMAIN ); ?>">
				</form>
			</div>
		</div>
	<?php } ?>

	<div id="st-clicks-tags" class="postbox ">
		<h3 class="hndle"><span>&nbsp;&nbsp;&nbsp;<?php _e( 'Uninstall Plugin', DESTAT_TEXTDOMAIN ); ?></span>
		</h3>

		<div class="inside">
			<form method="POST" action="">
				<input type="hidden" name="DeleteOptionsForm" value="1">

				<p><?php _e( 'Pressing the "Uninstall" and lead to the complete removal of all bezvozratnomu plugin settings. Will also be deleted, all statistics Assembly plugin (views and social activity for the pages), as well as database tables themselves, in which information is stored.', DESTAT_TEXTDOMAIN ); ?></p>
				<input class="button button-primary" type="submit" value="<?php _e( 'Go', DESTAT_TEXTDOMAIN ); ?>">
			</form>
		</div>
	</div>
</div>