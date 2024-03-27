<script type='text/javascript' src='<?php
echo DESTAT_ASSETS_URL . '/js/angularjs/angular.js'; ?>'></script>


<script type='text/javascript' src='<?php
echo DESTAT_ASSETS_URL . '/js/angularjs/modules/ngtable/ng-table.js'; ?>'></script>

<script type='text/javascript' src='<?php
echo DESTAT_ASSETS_URL . '/js/angularjs/angular-mocks.js'; ?>'></script>

<script type='text/javascript' src='<?php
echo DESTAT_ASSETS_URL . '/js/angularjs/angular-resource.js'; ?>'></script>

<link rel="stylesheet" href="<?php
echo DESTAT_ASSETS_URL . '/js/angularjs/modules/ngtable/css/ng-table.css'; ?>" />

<link rel="stylesheet" href="<?php
echo DESTAT_ASSETS_URL . '/js/angularjs/modules/ngtable/css/styles.css'; ?>" />

<script>

	var date_from = '',
		date_to = '';


	jQuery(document).ready(function () {
		jQuery('.wrap .error').remove();

		jQuery("#date-from").datepicker({
			defaultDate   : "+1w",
//								changeMonth   : true,
			numberOfMonths: 2,
			onClose       : function (selectedDate) {
				jQuery("#date-to").datepicker("option", "minDate", selectedDate);
				date_from = selectedDate;
				console.log(date_from);
			}
		});
		jQuery("#date-to").datepicker({
			defaultDate   : "+1w",
//								changeMonth   : true,
			numberOfMonths: 2,
			onClose       : function (selectedDate) {
				jQuery("#date-from").datepicker("option", "maxDate", selectedDate);
				date_to = selectedDate;
			}
		});
	});

	var app = angular.module('destatistics', ['ngTable', 'ngResource']).controller('DestatList', function ($scope, $filter, $q, $timeout, $resource, ngTableParams, $http) {

		var Api = $resource('/wp-admin/admin-ajax.php');

		$scope.data = {};
		$scope.totals = {
			views_counts_total   : 0,
			comments_counts_total: 0,
			votes_sum_total      : 0,
			fb_counts_total      : 0,
			tw_counts_total      : 0,
			vk_counts_total      : 0,
			ln_counts_total      : 0,
			gplus_counts_total   : 0,
			ok_counts_total      : 0,
			pocket_counts_total  : 0,
			posts_total          : 0,
		};

		$scope.filter_authors = {};
		$scope.filter_category = {};

		$scope.tableParams = new ngTableParams({
			action   : 'get_destats_data_for_table_list',
			page     : 1,            // show first page
			count    : 10,          // count per page
			date_from: '',
			date_to  : '',
			author   : '',
			category : '',
			sorting  : {
				views_counts: 'desc'     // initial sorting
			}
		}, {
			total  : 0,           // length of data
			getData: function ($defer, params) {
				// ajax request to api
				console.log(params.url());

				var table_body = angular.element('#destat_list tbody');
				var filters_block = angular.element('#filters-block');

				table_body.css({
					'opacity': 0.1
				});

				Api.get(params.url(), function (data) {
					console.log(data);

					$scope.totals.views_counts_total = 0;
					$scope.totals.comments_counts_total = 0;
					$scope.totals.votes_sum_total = 0;
					$scope.totals.fb_counts_total = 0;
					$scope.totals.tw_counts_total = 0;
					$scope.totals.vk_counts_total = 0;
					$scope.totals.ln_counts_total = 0;
					$scope.totals.gplus_counts_total = 0;
					$scope.totals.ok_counts_total = 0;
					$scope.totals.pocket_counts_total = 0;

					angular.forEach(data.total_records, function (item) {
						if (parseInt(item.views_counts)) {
							$scope.totals.views_counts_total = parseInt($scope.totals.views_counts_total) + parseInt(item.views_counts);
						}

						if (item.comments_counts) {
							$scope.totals.comments_counts_total += parseInt(item.comments_counts);
						}

						if (item.votes_sum) {
							$scope.totals.votes_sum_total += parseInt(item.votes_sum);
						}

						if (item.fb_counts) {
							$scope.totals.fb_counts_total += parseInt(item.fb_counts);
						}

						if (item.tw_counts) {
							$scope.totals.tw_counts_total += parseInt(item.tw_counts);
						}

						if (item.vk_counts) {
							$scope.totals.vk_counts_total += parseInt(item.vk_counts);
						}

						if (item.ln_counts) {
							$scope.totals.ln_counts_total += parseInt(item.ln_counts);
						}

						if (item.gplus_counts) {
							$scope.totals.gplus_counts_total += parseInt(item.gplus_counts);
						}

						if (item.ok_counts) {
							$scope.totals.ok_counts_total += parseInt(item.ok_counts);
						}

						if (item.pocket_counts) {
							$scope.totals.pocket_counts_total += parseInt(item.pocket_counts);
						}
					});
					$scope.totals.posts_total = data.total;
					$timeout(function () {
						table_body.css({
							'display': ''
						})
						table_body.css({
							'opacity': ''
						});

						filters_block.css({'visibility': 'visible'});

						console.log($scope);
						$scope.data = data.result;

						// update table params
						params.total(data.total);

						// set new data
						$defer.resolve(data.result);
						angular.element('#destat_list').css({'display': 'block'});

					}, 500);
				});
			}
		});


		$scope.filterUpdateData = function () {
			var date_from = new Date(jQuery("#date-from").datepicker("getDate")),
				date_to = new Date(jQuery("#date-to").datepicker("getDate")),
				date_from_str,
				date_to_str,
				day,
				month,
				year;


			day = date_from.getDate();
			if (day < 10) {
				day = '0' + day;
			}


			month = date_from.getMonth() + 1;
			if (month < 10) {
				month = '0' + month;
			}
			year = date_from.getFullYear();

			date_from_str = year + '-' + month + '-' + day;

			// date_to
			day = date_to.getDate();
			if (day < 10) {
				day = '0' + day;
			}

			month = date_to.getMonth() + 1;
			if (month < 10) {
				month = '0' + month;
			}
			year = date_to.getFullYear();

			date_to_str = year + '-' + month + '-' + day;

			console.log(date_from_str);

			$scope.tableParams.$params.page = 1;
			if (date_from_str != '1970-01-1' || date_from_str != '1970-01-01') {
				$scope.tableParams.$params.date_from = date_from_str;
			}
			if (date_to_str != '1970-01-01' || date_to_str != '1970-01-1') {
				$scope.tableParams.$params.date_to = date_to_str;
			}

			$scope.tableParams.$params.author = jQuery('#post_author').val();
			$scope.tableParams.$params.category = jQuery('#post_cat').val();

			$scope.tableParams.reload();
			console.log($scope.tableParams.$params);
		}

		$scope.updateDestatsData = function (destat, $data, obj) {
			//console.log(element.parent());

			var params = {
//					action: 'deco_form_deletes',
//					id    : destat.ID
			}

			var o = angular.element(obj);
			o.parent().css({
				'opacity': 0.1
			});
			Api.get(params, function (data) {
				console.log(data);
				o.parent().css({
					'opacity': ''
				})
				$scope.tableParams.reload();
			});
			return false;
		};

		var inArray = Array.prototype.indexOf ?
			function (val, arr) {
				return arr.indexOf(val)
			} :
			function (val, arr) {
				var i = arr.length;
				while (i--) {
					if (arr[i] === val) return i;
				}
				return -1;
			}

		$scope.category_and_author = function () {
			var def = $q.defer(),
				arr_author = [],
				arr_category = [],
				author = [],
				category = [];


			var params = {
				action: 'destat_get_filters_data',
			}
			Api.get(params, function (data) {
				console.log(data);
				angular.forEach(data.result, function (item) {

					if (inArray(item.author, arr_author) === -1) {
						arr_author.push(item.author);
						author.push({
							'name': item.author
						});
					}

					if (inArray(item.category, arr_category) === -1) {
						arr_category.push(item.category);
						category.push({
							'name': item.category
						});
					}
				});
				$scope.filter_authors = author;
				$scope.filter_category = category;
			});
		};

		$scope.category_and_author();


	});


</script>


<div class="wrap" ng-app="destatistics" ng-controller="DestatList">
	<h2><?php _e( 'de:statistics', DESTAT_TEXTDOMAIN ); ?></h2>

	<br><br>

	<div id="filters-block" class="postbox" style="visibility: hidden;">
		<h3 class="hndle"><span>&nbsp;&nbsp;&nbsp;<?php _e( 'Filters', DESTAT_TEXTDOMAIN ); ?></span></h3>

		<div class="inside">
			<input type="hidden" name="filter-wp_nonce" id="filter-wp_nonce" value="<?php echo wp_create_nonce( 'depostrating' ); ?>" />

			<table class="form-table">
				<tr>
					<th style="position: relative; width: 338px;">
						<strong>Date from</strong>
						<input id="date-from" style="width: 100px;" name="post_from" value="">
						<strong>Date to</strong>
						<input id="date-to" style="width: 100px;" name="post_for" value="">
					</th>

					<th>
						<label><?php _e( 'Category', DESTAT_TEXTDOMAIN ); ?></label>
						<select name="post_cat" id="post_cat">
							<option value=""><?php _e( 'All categories', DESTAT_TEXTDOMAIN ); ?></option>
							<option value="{{ category.name }}" ng-repeat="category in filter_category | orderBy: 'name'">
								{{ category.name }}
							</option>

						</select>

					</th>

					<th>
						<label><?php _e( 'Author', DESTAT_TEXTDOMAIN ); ?></label>
						<select name="post_author" id="post_author">
							<option value=""><?php _e( 'All authors', DESTAT_TEXTDOMAIN ); ?></option>
							<option value="{{ author.name }}" ng-repeat="author in filter_authors | orderBy: 'name'">
								{{ author.name }}
							</option>

						</select>

					</th>
				</tr>
			</table>
			<input type="button" class="button button-primary" onclick="Destatistics.filter(); return false;" value="<?php _e( 'Go', DESTAT_TEXTDOMAIN ); ?>" ng-click="filterUpdateData()">


		</div>
	</div>


	<div loading-container="tableParams.settings().$loading">
		<style>
			.filter {
				float:  none !important;
				margin: -5px 0 0 10px;
			}

			#destat_list tbody {
				/*display: none;*/
			}

			.ng-table th.sortable {
				padding: 7px !important;
			}
		</style>
		<table ng-table="tableParams" id="destat_list" show-filter="true" class="wp-list-table widefat fixed users">
			<tr ng-repeat="destat in $data track by $index" ng-class="{alternate: $even, odd: $odd}">

				<?php if ( is_network_admin() ) { ?>
					<td data-title="'<?php _e( 'Blog name', DESTAT_TEXTDOMAIN ); ?>'">
						{{destat.blogname}}
					</td>
				<?php } ?>

				<td data-title="'<?php _e( 'Title', DESTAT_TEXTDOMAIN ); ?>'" style="width: 30%;">
					<a target="_blank" href="{{destat.href}}">{{destat.title}}</a>

					<h3>- <strong>{{destat.category}}</strong></h3>
				</td>

				<td data-title="'<?php _e( 'Author', DESTAT_TEXTDOMAIN ); ?>'">
					{{destat.author}}
				</td>

				<!--				<td data-title="'<?php /*_e( 'Post type', DESTAT_TEXTDOMAIN ); */ ?>'" filter="{ 'post_type': 'text' }">
									{{destat.post_type}}
								</td>
				-->
				<td data-title="'Views'" sortable="views_counts">
					{{destat.views_counts}}
				</td>

				<td data-title="'Comments'" sortable="comments_counts">
					{{destat.comments_counts}}
				</td>

				<td data-title="'Likes'" sortable="votes_sum">
					{{destat.votes_sum}}
				</td>

				<?php if ( get_option( "de_fb_enable" ) ) { ?>
					<td data-title="'FB'" sortable="fb_counts">
						{{destat.fb_counts}}
					</td>
				<?php } ?>


				<?php if ( get_option( "de_twi_enable" ) ) { ?>
					<td data-title="'Twitter'" sortable="tw_counts">
						{{destat.tw_counts}}
					</td>

				<?php } ?>

				<?php if ( get_option( "de_vk_enable" ) ) { ?>
					<td data-title="'VK'" sortable="vk_counts">
						{{destat.vk_counts}}
					</td>

				<?php } ?>

				<?php if ( get_option( "de_gplus_enable" ) ) { ?>
					<td data-title="'Google+'" sortable="gplus_counts">
						{{destat.gplus_counts}}
					</td>
				<?php } ?>

				<?php if ( get_option( "de_pocket_enable" ) ) { ?>
					<td data-title="'Pocket'" sortable="pocket_counts">
						{{destat.pocket_counts}}
					</td>
				<?php } ?>

				<?php if ( get_option( "de_li_enable" ) ) { ?>
					<td data-title="'LinkedIn'" sortable="ln_counts">
						{{destat.ln_counts}}
					</td>
				<?php } ?>

				<?php if ( get_option( "de_ok_enable" ) ) { ?>
					<td data-title="'OK'" sortable="likes" sortable="ok_counts">
						{{destat.ok_counts}}
					</td>
				<?php } ?>

				<td data-title="'<?php _e( 'Published', DESTAT_TEXTDOMAIN ); ?>'" sortable="created_time">
					{{destat.created_time}}
				</td>

				<td data-title="'<?php _e( 'Updated', DESTAT_TEXTDOMAIN ); ?>'" sortable="updated_time">
					<strong>{{destat.updated_time}}</strong>
				</td>

				<!--				<td data-title="'Actions'">
									<button class="button button-primary" ng-click="updateDestatsData(destat, $data, this)">Update</button>
								</td>
				-->            </tr>

			<tfooter>
				<tr class="alternate">
					<?php if ( is_network_admin() ) { ?>
						<td></td>
					<?php } ?>

					<td><?php _e( 'Total posts:', DESTAT_TEXTDOMAIN ); ?>
						<strong>{{totals.posts_total | number }}</strong></td>

					<td data-title="'<?php _e( 'Author', DESTAT_TEXTDOMAIN ); ?>'">
						<strong><?php _e( 'Total:', DESTAT_TEXTDOMAIN ); ?></strong>
					</td>

					<td>
						<strong>{{totals.views_counts_total | number }}</strong>
					</td>

					<td>
						<strong>{{totals.comments_counts_total | number }}</strong>
					</td>

					<td>
						<strong>{{ totals.votes_sum_total | number }}</strong>
					</td>

					<?php if ( get_option( "de_fb_enable" ) ) { ?>
						<td>
							<strong>{{totals.fb_counts_total | number }}</strong>
						</td>
					<?php } ?>


					<?php if ( get_option( "de_twi_enable" ) ) { ?>
						<td>
							<strong>{{totals.tw_counts_total | number }}</strong>
						</td>

					<?php } ?>

					<?php if ( get_option( "de_vk_enable" ) ) { ?>
						<td>
							<strong>{{totals.vk_counts_total | number }}</strong>
						</td>

					<?php } ?>

					<?php if ( get_option( "de_gplus_enable" ) ) { ?>
						<td>
							<strong>{{totals.gplus_counts_total | number }}</strong>
						</td>
					<?php } ?>

					<?php if ( get_option( "de_pocket_enable" ) ) { ?>
						<td>
							<strong>{{totals.pocket_counts_total | number }}</strong>
						</td>
					<?php } ?>

					<?php if ( get_option( "de_li_enable" ) ) { ?>
						<td>
							<strong>{{totals.ln_counts_total | number }}</strong>
						</td>
					<?php } ?>

					<?php if ( get_option( "de_ok_enable" ) ) { ?>
						<td>
							<strong>{{totals.ok_counts_total | number }}</strong>
						</td>
					<?php } ?>

					<td>
					</td>

					<td>
					</td>
				</tr>
			</tfooter>
		</table>
	</div>

</div>