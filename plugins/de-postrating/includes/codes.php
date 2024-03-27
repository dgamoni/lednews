<?php
	//error_reporting(E_ALL);
	//
	if ( ! defined( 'DEPOSTRATE_WP_PLUGIN_DIR' ) ) {
		define( 'DEPOSTRATE_WP_PLUGIN_DIR', WP_CONTENT_URL . '/plugins/de-postrating' );
	}

	//*********************************************************************************
	add_action( 'init', 'depostrating', 10 );
	function depostrating() {

		if ( ! is_admin() ) {
			//wp_enqueue_style( 'depostrating-style', UNIPOSTRATE_WP_PLUGIN_DIR.'/includes/depostrating-style.css', false, '1.1.2', 'all');
		} elseif ( is_admin() ) {
			wp_enqueue_style( 'depostrating-style', DEPOSTRATE_WP_PLUGIN_DIR . '/includes/depostrating-admin-style.css', false, '1.1.2', 'all' );
			wp_register_style( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
			wp_enqueue_style( 'jquery-ui' );
		}

		// Обробка форми зміни налаштувань

		if ( $_POST['GaOptionsForm'] == 1 ) {
			update_option( "GaEnable", $_POST["GaEnable"] );
			if ( isset( $_POST["GAAuthCode"] ) && ! empty( $_POST["GAAuthCode"] ) ) {
				update_option( "GAAuthCode", $_POST["GAAuthCode"] );
			} else {
				delete_option( "GAaccess_token" );
				delete_transient( "GAaccess_token" );
				delete_option( "GArefresh_token" );
				delete_option( "GAUserProfile" );
				delete_option( "GAAuthCode" );
			}
			update_option( "GAUserProfile", $_POST["GAUserProfile"] );
		}

		if ( $_POST['GaNewToken'] == 1 ) {
			delete_option( "GAaccess_token" );
			delete_transient( "GAaccess_token" );
			delete_option( "GArefresh_token" );
			delete_option( "GAUserProfile" );
			$oGetDeGAoAuth = GetDeGAoAuth();
			$oGetDeGAoAuth->GetAndSaveFirstGoogleAccesToken();
		}

		if ( $_POST['OtherOptionsForm'] == 1 ) {
			update_option( "de_fb_enable", $_POST["de_fb_enable"] );
			update_option( "de_fb_method", $_POST["de_fb_method"] );
			update_option( "de_fbAppId", $_POST["de_fbAppId"] );
			update_option( "de_fbSecretKey", $_POST["de_fbSecretKey"] );

			update_option( "de_vk_enable", $_POST["de_vk_enable"] );
			update_option( "de_vk_type", $_POST["de_vk_type"] );
			update_option( "de_vk_method", $_POST["de_vk_method"] );
			update_option( "de_vkAppId", $_POST["de_vkAppId"] );
			update_option( "de_vkSecretKey", $_POST["de_vkSecretKey"] );

			update_option( "de_twi_enable", $_POST["de_twi_enable"] );
			update_option( "de_li_enable", $_POST["de_li_enable"] );
			update_option( "de_gplus_enable", $_POST["de_gplus_enable"] );
			update_option( "de_ok_enable", $_POST["de_ok_enable"] );
			if ( ! empty( $_POST["de_ptypes"] ) ) {
				update_option( "de_ptypes", $_POST["de_ptypes"] );
			}
			update_option( "de_new_posts_day", $_POST["de_new_posts_day"] );
		}

		if ( $_POST['DeCronJobs'] == 1 ) {
			update_option( "DeCronEnable", $_POST["DeCronEnable"] );

			if ( get_option( 'DeCronEnable' ) === 'enable' ) {
				//запобігаємо дублікації крон івентів
				DeUnscheduleCronJobs();

				if ( get_option( 'DeCronNewPosts' ) === '' ) {
					update_option( "DeCronNewPosts", 'every_five_min' );
				} else {
					update_option( "DeCronNewPosts", $_POST["DeCronNewPosts"] );
				}
				if ( get_option( 'DeCronOldPosts' ) === '' ) {
					update_option( "DeCronOldPosts", 'every_five_min' );
				} else {
					update_option( "DeCronOldPosts", $_POST["DeCronOldPosts"] );
				}
			}
		}

		if ( $_POST['DeleteGAAccess'] == 1 ) {
			update_option( "GAAuthCode", '' );
			update_option( "GAUserProfile", '' );
		}

		if ( $_POST['DeleteOptionsForm'] == 1 ) {
			DeletePluginOptionsAndTables();
		}

	}

	//
	add_action( 'wp', 'de_theme_depostrating_styles_load' );
	function de_theme_depostrating_styles_load() {

		wp_enqueue_style( 'depostrating-style', DEPOSTRATE_WP_PLUGIN_DIR . '/includes/depostrating-style.css', false, '1.1.2', 'all' );
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'datepicker', DEPOSTRATE_WP_PLUGIN_DIR . '/includes/datepicker.js', array( 'jquery' ), '1.0' );
		wp_enqueue_script( 'datepicker' );
		wp_register_script( 'jquery.dataTables.min', DEPOSTRATE_WP_PLUGIN_DIR . '/includes/jquery.dataTables.min.js', array( 'jquery' ), '1.0' );
		wp_enqueue_script( 'jquery.dataTables.min' );
		wp_register_script( 'depostrating-front-js', DEPOSTRATE_WP_PLUGIN_DIR . '/includes/depostrating-front-js.js', array( 'jquery' ), '1.1.2' );
		wp_enqueue_script( 'depostrating-front-js' );
	}

	//*********************************************************************************
	function depostrating_scripts() {

		if ( $_GET['page'] == 'depostrating-stats' ) {
			wp_enqueue_script( 'jquery' );
			wp_register_script( 'jquery.dataTables.min', DEPOSTRATE_WP_PLUGIN_DIR . '/includes/jquery.dataTables.min.js', array( 'jquery' ), '1.0' );
			wp_enqueue_script( 'jquery.dataTables.min' );
			//wp_register_script('datepicker', DEPOSTRATE_WP_PLUGIN_DIR.'/includes/datepicker.js', array('jquery'), '1.0' );
			//wp_enqueue_script('datepicker');
			//wp_register_script('bootstrap-datepicker', DEPOSTRATE_WP_PLUGIN_DIR.'/includes/bootstrap-datepicker.js', array('jquery'), '1.0' );
			//wp_enqueue_script('bootstrap-datepicker');
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_register_script( 'depostrating-js', DEPOSTRATE_WP_PLUGIN_DIR . '/includes/depostrating-js.js', array( 'jquery' ), '1.1.2' );
			wp_enqueue_script( 'depostrating-js' );
		}
	}

	//add_action( 'wp_enqueue_scripts', 'depostrating_scripts' );
	add_action( 'admin_enqueue_scripts', 'depostrating_scripts' );

	//*********************************************************************************
	include( 'widget-toprecommended.php' );
	include( 'widget-toppopular.php' );
	include( 'widget-toptabs.php' );

	//*********************************************************************************
	add_action( 'admin_menu', 'register_depostrating_menu_page' );
	function register_depostrating_menu_page() {

		add_menu_page( 'Рейтинг постов', 'Рейтинг постов', 'moderate_comments', 'depostrating-stats', 'depostrating_stats_display', plugins_url( 'de-postrating/includes/images/statistics.png' ), 3.2 );
		add_submenu_page( 'options-general.php', 'Настройки рейтинга постов', 'Настройки рейтинга постов', 'manage_options', 'depostrating-options', 'depostrating_options_display' );
	}

	//*********************************************************************************
	function depostrating_stats_display() {

		echo '<div class="wrap"><h2>Рейтинг постов</h2>';
		echo do_shortcode( '[depr_stats]' );
		echo '</div>';
	}

	function depostrating_options_display() {

		?>
		<div class="wrap"><h2>Настройки рейтинга постов</h2>
			<?php $oGetDeGAoAuth = GetDeGAoAuth(); ?>
			<h3>Настройки Google Analytics</h3>

			<form method="POST">
				<input type="hidden" name="GaOptionsForm" value="1">
				<table class="form-table">
					<tr>
						<th scope="row">Enable GA?</th>
						<td>
							<input type="checkbox" name="GaEnable" value="enable"<?php checked( 'enable', get_option( "GaEnable" ), true ); ?> />
						</td>
					</tr>
					<tr>
						<th scope="row">Google Authentication Code</th>
						<td><input type="text" name="GAAuthCode" value="<?php print get_option( "GAAuthCode" ); ?>">
						</td>
					</tr>
					<?php $oGetDeGAoAuth->ShowProfiles(); ?>
					<?php if ( get_option( "GAUserProfile" ) ) { ?>
						<tr>
							<th scope="row"></th>
							<td><p style="color:green;">Выбран профайл: <?php echo get_option( "GAUserProfile" ) ?></p>
							</td>
						</tr>
					<?php } ?>
				</table>
				<input type="submit" value="Сохранить настройки GA">
			</form>
			<?php $oGetDeGAoAuth->ShowAuthLink(); ?>
			<?php $oGetDeGAoAuth->ShowTokenLink(); ?>

			<h3>Другие настройки</h3>

			<form method="POST">
				<input type="hidden" name="OtherOptionsForm" value="1">
				<table class="form-table">
					<tr>
						<th scope="row">Facebook:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Enable?</span></legend>
								<label><input name="de_fb_enable" type="checkbox" id="de_fb_enable" value="enable"<?php checked( 'enable', get_option( "de_fb_enable" ), true ); ?> /> Check for enable</label><br />
								<legend class="screen-reader-text"><span>Choose method:</span></legend>
								<label><input name="de_fb_method" type="radio" id="de_fb_method" value="clean"<?php checked( 'clean', get_option( "de_fb_method" ), true ); ?> /> Clean (need App ID and Secret Key!)</label><br />
								<label><input name="de_fb_method" type="radio" id="de_fb_method" value="dirty"<?php checked( 'dirty', get_option( "de_fb_method" ), true ); ?> /> Dirty (no need nothing)</label><br />
								<legend class="screen-reader-text"><span>Add Facebook App ID and Secret Key:</span>
								</legend>
								<label><input type="text" name="de_fbAppId" value="<?php print get_option( "de_fbAppId" ); ?>"> FB App ID</label><br />
								<label><input type="text" name="de_fbSecretKey" value="<?php print get_option( "de_fbSecretKey" ); ?>"> FB Secret Key</label><br />
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Vkontakte:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Enable?</span></legend>
								<label><input name="de_vk_enable" type="checkbox" id="de_vk_enable" value="enable"<?php checked( 'enable', get_option( "de_vk_enable" ), true ); ?> /> Check for enable</label><br />
								<legend class="screen-reader-text"><span>What we do?</span></legend>
								<label><input name="de_vk_type" type="radio" id="de_vk_type" value="share"<?php checked( 'share', get_option( "de_vk_type" ), true ); ?> /> Gathering shares (no need nothing)</label><br />
								<label><input name="de_vk_type" type="radio" id="de_vk_type" value="like"<?php checked( 'like', get_option( "de_vk_type" ), true ); ?> /> Gathering likes (need app ID and/or secret key, depend on selected method)</label><br />

								<div id="vk_method_box" style="display:none;">
									<legend class="screen-reader-text"><span>Choose method:</span></legend>
									<label><input name="de_vk_method" type="radio" id="de_fb_method" value="clean"<?php checked( 'clean', get_option( "de_vk_method" ), true ); ?> /> Clean (need App ID and Secret Key!)</label><br />
									<label><input name="de_vk_method" type="radio" id="de_fb_method" value="dirty"<?php checked( 'dirty', get_option( "de_vk_method" ), true ); ?> /> Dirty (need only app ID)</label><br />
									<legend class="screen-reader-text"><span>Add Facebook App ID and Secret Key:</span>
									</legend>
									<label><input type="text" name="de_vkAppId" value="<?php print get_option( "de_vkAppId" ); ?>"> VK App ID</label><br />
									<label><input type="text" name="de_vkSecretKey" value="<?php print get_option( "de_vkSecretKey" ); ?>"> VK Secret Key</label><br />
								</div>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Twitter:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Enable?</span></legend>
								<label><input name="de_twi_enable" type="checkbox" id="de_twi_enable" value="enable"<?php checked( 'enable', get_option( "de_twi_enable" ), true ); ?> /> Check for enable</label><br />
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">LinkedIn:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Enable?</span></legend>
								<label><input name="de_li_enable" type="checkbox" id="de_li_enable" value="enable"<?php checked( 'enable', get_option( "de_li_enable" ), true ); ?> /> Check for enable</label><br />
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Google Plus:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Enable?</span></legend>
								<label><input name="de_gplus_enable" type="checkbox" id="de_gplus_enable" value="enable"<?php checked( 'enable', get_option( "de_gplus_enable" ), true ); ?> /> Check for enable</label><br />
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Odnoklassniki:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Enable?</span></legend>
								<label><input name="de_ok_enable" type="checkbox" id="de_ok_enable" value="enable"<?php checked( 'enable', get_option( "de_ok_enable" ), true ); ?> /> Check for enable</label><br />
							</fieldset>
						</td>
					</tr>
					<?php $aPostTypes  = DepostratingGetPostTypes();
						$aChoosenTypes = get_option( "de_ptypes" ); ?>
					<tr>
						<th scope="row">Выберите типы постов:</th>
						<td>
							<fieldset>
								<?php foreach ( $aPostTypes as $Key => $Value ) { ?>
									<label><input name="de_ptypes[]" type="checkbox" id="de_ptypes" value="<?php echo $Key ?>"<?php if ( in_array( $Key, $aChoosenTypes ) ) {
											echo ' checked="checked"';
										} ?> /> <?php echo $Value ?></label><br />
								<?php } ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Срок для новых постов</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>К-во дней, за которые пост считается новым:</span></legend>
								<label><input name="de_new_posts_day" type="text" id="de_new_posts_day" value="<?php print get_option( "de_new_posts_day" ); ?>" /></label><br />
							</fieldset>
						</td>
					</tr>
				</table>
				<input type="submit" value="Сохранить другие настройки">
			</form>

			<h3>Настройки WP Cron</h3>

			<form method="POST">
				<input type="hidden" name="DeCronJobs" value="1">
				<table class="form-table">
					<tr>
						<th scope="row">Обновлять с помощью wp-cron?</th>
						<td>
							<input type="checkbox" name="DeCronEnable" value="enable"<?php checked( 'enable', get_option( "DeCronEnable" ), true ); ?> />
						</td>
					</tr>
					<tr>
						<th scope="row">Обновлять новые посты:</th>
						<td>
							<select name="DeCronNewPosts">
								<option value="every_five_min"<?php selected( 'every_five_min', get_option( "DeCronNewPosts" ), true ); ?>>каждые 5 минут</option>
								<option value="every_ten_min"<?php selected( 'every_ten_min', get_option( "DeCronNewPosts" ), true ); ?>>каждые 10 минут</option>
								<option value="every_twelve_min"<?php selected( 'every_twelve_min', get_option( "DeCronNewPosts" ), true ); ?>>каждые 20 минут</option>
								<option value="every_thirty_min"<?php selected( 'every_thirty_min', get_option( "DeCronNewPosts" ), true ); ?>>каждые 30 минут</option>
								<option value="hourly"<?php selected( 'hourly', get_option( "DeCronNewPosts" ), true ); ?>>каждый час</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">Обновлять старые посты:</th>
						<td>
							<select name="DeCronOldPosts">
								<option value="every_five_min"<?php selected( 'every_five_min', get_option( "DeCronOldPosts" ), true ); ?>>каждые 5 минут</option>
								<option value="every_ten_min"<?php selected( 'every_ten_min', get_option( "DeCronOldPosts" ), true ); ?>>каждые 10 минут</option>
								<option value="every_twelve_min"<?php selected( 'every_twelve_min', get_option( "DeCronOldPosts" ), true ); ?>>каждые 20 минут</option>
								<option value="every_thirty_min"<?php selected( 'every_thirty_min', get_option( "DeCronOldPosts" ), true ); ?>>каждые 30 минут</option>
								<option value="hourly"<?php selected( 'hourly', get_option( "DeCronOldPosts" ), true ); ?>>каждый час</option>
							</select>
						</td>
					</tr>
				</table>
				<input type="submit" value="Сохранить настройки WP Cron">
			</form>

			<h3>Log (today only log):</h3>
<pre class="log_wrapper">
<?php
	$aResponse = wp_remote_get( plugins_url( 'de-postrating/log/log_' . date( 'Y-m-d', time() ) . '.txt' ) );
	if ( $aResponse['response']['code'] != '404' ) {
		$sLogFile = wp_remote_retrieve_body( $aResponse );
		print $sLogFile;
	} else {
		echo 'Sorry, no logs for today';
	}
?>
</pre>

			<h3>FAQ:</h3>

			<p>Для вывода даных используйте шорткоды. Например, укажите
				<code>[depr-pageviews]</code> в нужном месте в посте или
				<code>&lt;?php echo do_shortcode('[depr-pageviews]') ?&gt;</code>
				в шаблоне темы. Шорткод в шаблоне темы должен быть указан в цикле.</p>

			<p>Всего доступно 3 шорткода:
				<code>[depr-pageviews]</code> - для отображение к-ва просмотров даного поста (даные ГА);
				<code>[depr-socialactions]</code> - для отображения сумарного к-ва
				лайков по всем соц. сетям для даного поста;
				<code>[depr-total]</code> - для отображения сумы всех социальных действий для даного поста (просмотры+лайки+комментарии).
			</p>

			<h3>Known issues:</h3>

			<p>W3 Total Cache с включеной опцией кеширования Object Cache ломает нормальною работу функции
				<i>set_transient()</i>.</p>

			<?php if ( get_option( "GAAuthCode" ) ) { ?>
				<h3>Анулировать доступ для GA</h3>
				<form method="POST">
					<input type="hidden" name="DeleteGAAccess" value="1">
					<input id="DeleteDeAccess" type="submit" value="Анулировать доступ">
				</form>
			<?php } ?>

			<h3>Деинсталировать</h3>

			<form method="POST">
				<input type="hidden" name="DeleteOptionsForm" value="1">

				<p>Нажатие кнопки "Деинсталировать" приведет к полному и безвозратному удалению всех настроек плагина. Также будет удалена вся статистика собраная плагином
					(просмотры и социальная активность для страниц), а также сами таблицы БД, в которых хранится эта информация.</p>
				<input id="DeleteDePr" type="submit" value="Деинсталировать">
			</form>
		</div>
		<script type="text/javascript">
			(function ($) {
				$(document).ready(function () {

					var vk_type_checkbox = $("input[name=de_vk_type]");
					var vk_method_box = $("#vk_method_box");

					vk_type_checkbox.on("click", function () {
						if ($(this).val() === "like") {
							vk_method_box.slideDown(500);
						} else {
							vk_method_box.slideUp(300);
						}
					});

					var delete_button = $("#DeleteDePr");
					delete_button.on("click", function (e) {
						e.preventDefault();

						var form = $(this).parent();
						$.prompt("Результат не возможно будет отменить!", {
							title  : "Вы уверены?",
							buttons: {"Да, я готов.": true, "Нет, я передумал!": false},
							submit : function (e, v, m, f) {
								if (v) {
									form.submit();
								}
							}
						});
					});

				});
			})(jQuery);
		</script>
	<?php
	}

	function DepostratingGetPostTypes() {

		$aPostPypes = get_post_types( array( 'public' => true, 'publicly_queryable' => true ), 'names' );

		return $aPostPypes;
	}

	//*********************************************************************************

	function de_admin_bar_add_rating_link() {

		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array(
			                         'parent' => false,
			                         'id'     => 'depostrating-stats',
			                         'title'  => __( 'Рейтинг постов' ),
			                         'href'   => admin_url( 'admin.php?page=depostrating-stats' ),
			                         'meta'   => false
		                         ) );
	}

	add_action( 'wp_before_admin_bar_render', 'de_admin_bar_add_rating_link' );

	//*********************************************************************************
	add_shortcode( 'depr_stats', 'DepostratingStatsTable' );
	function DepostratingStatsTable( $atts, $content = null ) {

		$aAllCats      = get_categories( array( 'type' => 'post' ) );
		$aAllowedUsers = RatingGetContributorsArraySortedByFirstName( 'ASC' );

		$aFilter            = array();
		$aFilter['Limit']   = 1000;
		$sMonthAgoTimestamp = time() - ( 60 * 60 * 24 * 30 );
		//echo date('Y-m-d H:i:s', $sMonthAgoTimestamp);
		$aFilter['DateFrom'] = date( 'Y-m-d H:i:s', $sMonthAgoTimestamp );
		$aFilter['DateFor']  = date( 'Y-m-d H:i:s', time() );
		$aFilter['OrderBy']  = 'Views';

		if ( isset( $_POST['filter_submit'] ) && ! empty( $_POST['filter_submit'] ) ) {

			if ( ! wp_verify_nonce( $_POST['filter-depostrating'], 'depostrating' ) ) {
				exit( "Читер!" );
			}

			$aFilter['DateFrom'] = null;
			$aFilter['DateFor']  = null;

			$aReordered = array();
			$aReordered = $_POST;
			$aReordered = array_slice( $aReordered, 1, - 1 );

			if ( $aReordered['post_cat'] ) {
				$aFilter['Category'] = $aReordered['post_cat'];
			}

			if ( $aReordered['post_author'] ) {
				$aFilter['Author'] = $aReordered['post_author'];
			}

			if ( $aReordered['post_from'] && $aReordered['post_for'] ) {
				$aFilter['DateFrom'] = $aReordered['post_from'] . ' 00:00:00';
				$aFilter['DateFor']  = $aReordered['post_for'] . ' 24:59:59';
			}

			//print_r($aFilter);

		}
		?>

		<form id="depostrating-form" method="POST" enctype="multipart/form-data">

			<input type="hidden" name="filter-depostrating" value="<?php echo wp_create_nonce( 'depostrating' ); ?>" />

			<div>
				<label>Новости по дате</label>
				<input id="dedate-from" name="post_from" data-date-format="yyyy-mm-dd" value="<?php if ( $aReordered['post_from'] ) {
					echo $aReordered['post_from'];
				} ?>" />

				<input id="dedate-for" name="post_for" data-date-format="yyyy-mm-dd" value="<?php if ( $aReordered['post_for'] ) {
					echo $aReordered['post_for'];
				} ?>" />
			</div>

			<div>
				<label>Категория</label>
				<select name="post_cat">
					<?php
						echo '<option value="" ' . selected( $oCat->term_id, $aReordered['post_cat'] ) . '>Все категории</option>';
						foreach ( $aAllCats as $oCat ) {
							echo '<option value="' . $oCat->term_id . '" ' . selected( $oCat->term_id, $aReordered['post_cat'] ) . '>' . $oCat->name . '</option>';
						}
					?>
				</select>

				<label>Автор</label>
				<select name="post_author">
					<?php
						echo '<option value="" ' . selected( $oUser->ID, $aReordered['post_author'] ) . '>Все авторы</option>';
						foreach ( $aAllowedUsers as $oUser ) {
							$aUserMeta = get_user_meta( $oUser->ID );
							//print_r($aUserMeta);
							echo '<option value="' . $oUser->ID . '" ' . selected( $oUser->ID, $aReordered['post_author'] ) . '>' . $aUserMeta['first_name']['0'] . ' ' . $aUserMeta['last_name']['0'] . '</option>';
						}
					?>
				</select>
			</div>

			<input class="btn filter_submit" type="submit" name="filter_submit" value="Фильтровать" />

		</form>
		<?php $sStatsPageURL = get_bloginfo( 'url' ) . '/wp-admin/admin.php?page=depostrating-stats'; ?>
		<a class="update_now_link" target="_blank" href="<?php echo add_query_arg( array( 'ManualRatingUpdate' => 1 ), $sStatsPageURL ) ?>">Обновить данные сейчас же!</a>
		<div class="clear"></div>

		<table id="depostratingtable" class="tablesorter">
			<thead>
			<tr>
				<th>#</th>
				<th>Заголовок</th>
				<th>Просмотров</th>
				<th>Комментариев</th>
				<th>Likes</th>
				<th>FB Likes</th>
				<th>Tweets</th>
				<th>VK Likes</th>
				<th>G Plus</th>
				<th>Рубрика</th>
				<th>Автор</th>
				<th>Время обновления</th>
			</tr>
			</thead>
			<tbody>

			<?php
				$aData = RatingGetRatedPostsByFilter( $aFilter );
				$i     = 1;
				foreach ( $aData as $aItem ) {
					$aPostCats       = get_the_category( $aItem['PostId'] );
					$oPostAuthorData = get_user_meta( $aItem['Post']->post_author );
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td>
							<a href="<?php echo get_permalink( $aItem['PostId'] ) ?>" title="<?php echo $aItem['Post']->post_title ?>"><?php echo $aItem['Post']->post_title ?></a>
						</td>
						<td><?php echo $aItem['Views'] ?></td>
						<td><?php echo $aItem['Comments'] ?></td>
						<td><?php echo $aItem['RawSocial']['page_votes_sum'] ?></td>
						<td><?php echo $aItem['RawSocial']['page_fb_votes'] ?></td>
						<td><?php echo $aItem['RawSocial']['page_tw_votes'] ?></td>
						<td><?php echo $aItem['RawSocial']['page_vk_votes'] ?></td>
						<td><?php echo $aItem['RawSocial']['page_gp_votes'] ?></td>
						<td><?php echo $aPostCats['0']->name ?></td>
						<td><?php echo $oPostAuthorData['first_name']['0'] . ' ' . $oPostAuthorData['last_name']['0'] ?></td>
						<td><?php echo date( 'd.m.Y H:i:s', $aItem['RawSocial']['page_update_datetime'] + 10800 ) ?></td>
					</tr>
					<?php
					$sViewsSum  = $sViewsSum + $aItem['Views'];
					$sCommSum   = $sCommSum + $aItem['Comments'];
					$sSocialSum = $sSocialSum + $aItem['RawSocial']['page_votes_sum'];
					$sFBSum     = $sFBSum + $aItem['RawSocial']['page_fb_votes'];
					$sTwiSum    = $sTwiSum + $aItem['RawSocial']['page_tw_votes'];
					$sVkSum     = $sVkSum + $aItem['RawSocial']['page_vk_votes'];
					$sGpSum     = $sGpSum + $aItem['RawSocial']['page_gp_votes'];
					$i ++;
				}
			?>
			</tbody>
			<tfoot>
			<tr>
				<td></td>
				<td>Всего:</td>
				<td><?php echo $sViewsSum ?></td>
				<td><?php echo $sCommSum ?></td>
				<td><?php echo $sSocialSum ?></td>
				<td><?php echo $sFBSum ?></td>
				<td><?php echo $sTwiSum ?></td>
				<td><?php echo $sVkSum ?></td>
				<td><?php echo $sGpSum ?></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			</tfoot>
		</table>

	<?php

	}

?>