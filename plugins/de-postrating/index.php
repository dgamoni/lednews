<?php

/*
Plugin Name: de:PostsRating
Plugin URI: http://decollete.com.ua
Description: Gather post popularity information
Author: decollete.com.ua
Author URI: http://decollete.com.ua
Version: 2.1.0
License: GPLv2 (or later)
*/

//*********************************************************************************
function RatingPluginDir(){
	return WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",
							plugin_basename(__FILE__));
}

require_once RatingPluginDir()."/includes/codes.php";

//*********************************************************************************
function GetLog(){

	global $oUniLog;

	if(is_object($oUniLog)){
		return $oUniLog;
	}else{

		require_once RatingPluginDir()."/includes/log.class.php";
		$oUniLog	= KLogger::instance( RatingPluginDir().'log/', KLogger::DEBUG);

		return $oUniLog;
	}

}

//*********************************************************************************
function GetTools(){

	global $oWordpressTools;

	if(is_object($oWordpressTools)){
		return $oWordpressTools;
	}else{

		require_once RatingPluginDir()."WordpressTools.class.php";
		$oWordpressTools	= new WordpressTools;

		return $oWordpressTools;
	}

}

//*********************************************************************************
function GetSocialTools(){

	global $oSocialTools;

	if(is_object($oSocialTools)){
		return $oSocialTools;
	}else{
		require_once RatingPluginDir()."Social.class.php";
		$aDbParams		= array(
									'DbLogin'		=> DB_USER,
									'DbPassword'	=> DB_PASSWORD,
									'DbName'		=> DB_NAME,
									'DbHost'		=> DB_HOST
							);
		$oSocialTools	= new Social($aDbParams);
		return $oSocialTools;
	}

}

//*********************************************************************************
function GetDeGAoAuth(){

	global $oDeGAoAuth;

	if(is_object($oDeGAoAuth)){
		return $oDeGAoAuth;
	}else{
		require_once RatingPluginDir()."DeGAoAuth.class.php";
		$oDeGAoAuth	= new DeGAoAuth;

		return $oDeGAoAuth;
	}

}

//*********************************************************************************
function RatingSavePostStats($aPostStats){

	$oTools		= GetTools();

	$sQuery		= "INSERT INTO pr_stats (PostId, Comments, Views) VALUES(?d, ?d, ?d)
					ON DUPLICATE KEY UPDATE
						Comments 	= ?d,
						Views		= ?d";
	return $oTools->oDb->Query($sQuery, $aPostStats['PostId'],
										$aPostStats['Comments'], $aPostStats['Views'],
										$aPostStats['Comments'], $aPostStats['Views']);

}

//*********************************************************************************
function GetWpStatsByPostId($iPostId){

	$oTools		= GetTools();
	$sQuery		= "SELECT * FROM pr_stats WHERE PostId	= ?d";

	$aResult	= $oTools->oDb->SelectRow($sQuery, $iPostId);

	if($aResult){
		return $aResult;
	}else{

		$aResult	= array();
		$aResult['PostId']		= $iPostId;
		$aResult['Comments']	= $oTools->GetCommentsCountByPostId($iPostId);
		$aResult['Views']		= 0;
		$aResult['Source']		= 'new';

		return $aResult;
	}

}

//*********************************************************************************
function GetStatsByPostId($iPostId){
    /**/ //$oUniLog	= GetLog();
	$oSocial	= GetSocialTools();
	$oTools		= GetTools();

	$oPost				= $oTools->GetPostById($iPostId);
	$sPostUrl			= $oTools->GetPostUrlById($iPostId);
	$iPostAddDatetime	= $oPost->post_date;

	// Додамо фільтр на тип посту і тут, щоб уникнути потрапляння чернеток і ккп
	// в базу з рейтингом
	if( ($oPost->post_status != 'publish') ) return false;
    $aChoosenTypes = get_option("de_ptypes");
    /**/ //$oUniLog->logInfo('post type: ',$oPost->post_type);
    if( !in_array($oPost->post_type, $aChoosenTypes) ) return false;

	$iTotalSocialCounts	= $oSocial->GetTotalVotesCountByPageId($iPostId, $sPostUrl, $iPostAddDatetime);
	$aWpStats			= GetWpStatsByPostId($iPostId);

	$aStats	= array(
						'PostId'		=> $iPostId,
						'Comments'		=> $oTools->GetCommentsCountByPostId($iPostId),
						'Views'			=> $aWpStats['Views'],
						'SocialActions'	=> $iTotalSocialCounts,
						'RawSocial'		=> $oSocial->GetStatsByPageId($iPostId),
						'Total'			=> $aWpStats['Comments'] + $aWpStats['Views'] + $iTotalSocialCounts,
			);
    /**/ //$oUniLog->logInfo('post stats: ',$aStats);
	return $aStats;
}

//*********************************************************************************
function RatingBuildQueryStatementByFilter($aFilter){

	$sWhere = " WHERE 	post_status = 'publish'";

    $aChoosenTypes = get_option("de_ptypes");
        $sWhere = $sWhere . " AND post_type IN (";
    $iCount = count($aChoosenTypes);
    $i = 1;
    foreach ( $aChoosenTypes as $sType ) {
        $sWhere = $sWhere . "'$sType'";
        if ( $i < $iCount ) $sWhere = $sWhere . ", ";
        $i++;
    }
        $sWhere = $sWhere . ")";

	if( isset($aFilter['Author']) ){
		$sWhere = $sWhere . " AND post_author = {$aFilter['Author']}";
	}

	if( isset($aFilter['DateFrom']) ){
		$sWhere = $sWhere . " AND post_date >= '{$aFilter['DateFrom']}'";
	}

	if( isset($aFilter['DateFor']) ){
		$sWhere = $sWhere . " AND post_date <= '{$aFilter['DateFor']}'";
	}

	if( isset($aFilter['Category']) ){
		$sWhere = $sWhere . " AND ID IN (SELECT object_id FROM wp_term_relationships WHERE wp_term_relationships.term_taxonomy_id
											IN (SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE term_id = {$aFilter['Category']})
										)";
	}

	if( isset($aFilter['CategoryNOTIN']) ){
		$sWhere = $sWhere . " AND ID NOT IN (SELECT object_id FROM wp_term_relationships WHERE wp_term_relationships.term_taxonomy_id
											IN (SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE term_id = {$aFilter['CategoryNOTIN']})
										)";
	}
    //print_r($sWhere);
    return $sWhere;

}

//*********************************************************************************
function RatingGetRatedPostsByFilter($aFilter = null){
	if( empty($aFilter) ) $aFilter = array();

	$oTools		= GetTools();
	$oWpDb		= $oTools->GetWpDb();

	if(isset($aFilter['Limit'])) $iLimit = $aFilter['Limit'];
	else $iLimit = 10;

	if(isset($aFilter['OrderBy'])) $sOrderBy = $aFilter['OrderBy'];
	else $sOrderBy = 'post_date';


	$sPostsTable	= $oWpDb->posts;
	$sQueryPart		= RatingBuildQueryStatementByFilter($aFilter);
	$sQuery			= "	SELECT 	ID, post_author,
								post_date,
								post_status,
								post_type,

						(SELECT Comments 		FROM pr_stats 		WHERE pr_stats.PostId = ID) 	AS Comments,
						(SELECT Views			FROM pr_stats 		WHERE pr_stats.PostId = ID) 	AS Views,
						(SELECT page_votes_sum	FROM social_rank	WHERE social_rank.page_id = ID)	AS SocialActions,
						(SELECT Comments+ Views+ SocialActions )	AS Total

						FROM $sPostsTable

						$sQueryPart

						ORDER BY $sOrderBy DESC
						LIMIT $iLimit";

	$aQResult	= $oTools->oDb->Select($sQuery);

	if($aQResult){

		$aResult	= array();
		foreach($aQResult as $aQResultRow){

			$iPostId	= $aQResultRow['ID'];
			$oPost		= $oTools->GetPostById($iPostId);
			$aStats		= GetStatsByPostId($iPostId);

			$aStats['Post']	= $oPost;
			$aResult[]		= $aStats;
		}
		return $aResult;

	}else return array();


}

//*********************************************************************************
function RatingHandleNewComment($iCommentId){

    $oComment       = get_comment( $iCommentId );
	$iPostId		= $oComment->comment_post_ID;
	$aPostStats		= GetWpStatsByPostId($iPostId);
	$oTools			= GetTools();

	if( !empty($aPostStats['Source']) )
		$aPostStats['Comments']	= $oTools->GetCommentsCountByPostId($iPostId);

	RatingSavePostStats($aPostStats);

}

//*********************************************************************************
function RatingPluginActivate(){

	$oTools		= GetTools();
	$oSocial	= GetSocialTools();

	$sQuery		= "CREATE TABLE IF NOT EXISTS `pr_stats` (
							`PostId`				INT NOT NULL,
							`Comments`				INT NOT NULL,
							`Views`					INT NOT NULL,

						  PRIMARY KEY  (`PostId`),
							KEY				`Comments`				(`Comments`),
							KEY 			`Views` 				(`Views`),
							KEY 			`CommentsViews` 		(`Comments`, `Views`)
						)
								ENGINE = InnoDB	DEFAULT CHARACTER SET = utf8
								COLLATE = utf8_general_ci;";
	$oTools->oDb->Query($sQuery);

	$oSocial->Install();

    //initial plugin options added
    UpdateDefaultPluginOptions();

}

//*********************************************************************************
function RatingGetContributorsArraySortedByFirstName($sDirection = 'ASC'){
	$oTools		= GetTools();

	$sQuery		= "	SELECT id,
						(SELECT meta_value FROM wp_usermeta WHERE wp_usermeta.user_id = id AND wp_usermeta.meta_key = 'first_name') as f_name
					FROM 		wp_users
					ORDER BY 	f_name $sDirection";
	$aResult 	= $oTools->oDb->Select($sQuery);

	if($aResult){
		$aUsers = array();
		foreach($aResult as $aRow){
			$iUserId 	= $aRow['id'];
			$oUser		= get_userdata($iUserId);
			if(!in_array( 'subscriber', $oUser->roles ) ){
				$aUsers[]	= $oUser;
			}
		}

		return $aUsers;
	}else return array();


}

//*********************************************************************************
function RatingHandlePostView($oWp){

	$aRequest	= $oWp->query_vars;
	$oTools		= GetTools();

	if( !empty($aRequest['p']) or !empty($aRequest['name']) ){

		if( !empty($aRequest['p']) ){
			$iPostId	= $aRequest['p'];
			$oPost		= get_post($iPostId);
		}

		if( !empty($aRequest['name']) ){
			$sPostSlug	= $aRequest['name'];
			$oPost		= $oTools->GetPostBySlug($sPostSlug);
		}

		if($oPost){
			$iPostId	= $oPost->ID;
			$aWpStats	= GetWpStatsByPostId($iPostId);

			$aWpStats['Views']	= $aWpStats['Views'] + 1;
			RatingSavePostStats($aWpStats);
		}
	}

}

//*********************************************************************************
function RatingUpdateDataByFilter($aFilter){

    $sGoogleEnable			= get_option("GaEnable");
	$sGoogleApiMail			= get_option("GaLogin");
	$sGoogleApiPassword		= get_option("GaPassword");
	$sGoogleApiProfileId	= get_option("GaProfileId");

    /**/ $oUniLog	= GetLog();
	$oSocial	= GetSocialTools();
	$aPages		= $oSocial->GetRecordsByFilter($aFilter);

	// Обновляем данные по соц. сетям
	$oSocial->UpdateByFilter($aFilter);

	// Обновляем данные просмотров из GAApi
    if ( $sGoogleEnable === 'enable' ) {

	    $sSiteUrl	= site_url();

		foreach($aPages as $aPage){
			$sPostUrl	= $aPage['page_url'];
			$iPostId	= $aPage['page_id'];
			$sPath		= str_replace($sSiteUrl, "", $sPostUrl);
			$aWpStats	= GetWpStatsByPostId($iPostId);
            /**/ //$oUniLog->logInfo('Page URI: ',$aPage['page_url']);
            /**/ //$oUniLog->logInfo('Page ID: ',$aPage['page_id']);
            /**/ //$oUniLog->logInfo('Page Added: ',date('d.m.Y',$aPage['page_add_datetime']));

            $oGetDeGAoAuth = GetDeGAoAuth();
            $iTotalPageviews = $oGetDeGAoAuth->GetPageviewsByPagePath($sPath);

            if ( $iTotalPageviews ) {
                /**/ //$oUniLog->logInfo('GAPI response: ',$iTotalPageviews);
                $aWpStats['Views']	= $iTotalPageviews;
                $Result = RatingSavePostStats($aWpStats);
                /**/ //$oUniLog->logInfo($Result);
            }

        }

    } else /**/ $oUniLog->logInfo('GA is disabled'); //enable GA
}

//*********************************************************************************
function RatingUpdateSocialStatsForNewPosts(){
	error_log( __FUNCTION__ );

	$aFilter	= array();
    $iNewPostsDay = ( get_option('de_new_posts_day') ? get_option('de_new_posts_day') : '5');
	$aFilter['Limit']			= 20;
	$aFilter['add_date_from']	= (time()- (24*60*60 *$iNewPostsDay));

	if(isset($_GET['page_id'])){
		$aFilter['page_id'] = (int) $_GET['page_id'];
	}

    $oUniLog = GetLog();
    $oUniLog->logDebug(' ');
    $oUniLog->logDebug(' ');
    $oUniLog->logDebug('***************************Cron posts update started***************************');
	RatingUpdateDataByFilter($aFilter);
}

//*********************************************************************************
function RatingUpdateSocialStatsForOldPosts(){

	$aFilter	= array();
    $iNewPostsDay = ( get_option('de_new_posts_day') ? get_option('de_new_posts_day') : '5');
	$aFilter['Limit']			= 10;
	$aFilter['add_date_for']	= (time()- (24*60*60 *$iNewPostsDay));

	if(isset($_GET['page_id'])){
		$aFilter['page_id'] = (int) $_GET['page_id'];
	}

    $oUniLog = GetLog();
	RatingUpdateDataByFilter($aFilter);
    $oUniLog->logDebug('***************************Cron posts update ended***************************');
}

//*********************************************************************************
// Для дебагу оновлюватимемо данні соц. показників по заходу на спеціальний лінк
// Після запуску в прод., код можна прибрати
function ManualRatingUpdate(){
	if($_GET['ManualRatingUpdate'] == 1){
	    $oUniLog = GetLog();
        $oUniLog->logDebug(' ');
        $oUniLog->logDebug(' ');
        $oUniLog->logDebug('***************************Manual update started***************************');
		RatingUpdateSocialStatsForNewPosts();
		RatingUpdateSocialStatsForOldPosts();
        $oUniLog->logDebug('***************************Manual update ended*****************************');
        DeleteOldLogs();
		die();
	}
}

//*********************************************************************************
// Чисто потестити дірті метод для вк
function DebugOnly(){
	if($_GET['deprdebug'] == 1){
	    //$oGetDeGAoAuth = GetDeGAoAuth();
        //$profiles = $oGetDeGAoAuth->GetProfiles();
        //print_r($profiles);
        //$oGetDeGAoAuth->GetAndSaveFirstGoogleAccesToken();
        $t = wp_next_scheduled('DePrNewPostsEvent');
        echo date('Y-m-d H:i', time()).'/';
        echo date('Y-m-d H:i', $t);
    die();
	}
}

//*********************************************************************************
function DeleteOldLogs(){
    $sLogDir = RatingPluginDir().'log/';

    if ($handle = opendir($sLogDir)) {
        //Under Windows System and Apache, denied access to file is an usual error to unlink file.
        //To delete file you must to change file's owner.
        //chown($sLogDir.$file,666);
        while (false !== ($file = readdir($handle))) {
            if ( filemtime($sLogDir.$file) <= time() - 60*60*24*2 ) {
                chmod($sLogDir.$file, 0777);
                unlink($sLogDir.$file);
            }
        }

        closedir($handle);

    }
}

//*********************************************************************************
function RatingPluginDeactivate(){
    wp_clear_scheduled_hook('RatingUpdateSocialStatsForNewPosts');
    wp_clear_scheduled_hook('RatingUpdateSocialStatsForOldPosts');
    wp_clear_scheduled_hook('DePrNewPostsEvent');
    wp_clear_scheduled_hook('DePrOldPostsEvent');
}

//*********************************************************************************
function RatingDebug(){
	$aFilter['Category'] = 3;
	$aResult	= GetStatsByPostId(1);
	print_r($aResult);
	die();
}

//*********************************************************************************
function RatingAddNewCronSchedule($schedules){
 	$schedules['every_five_min'] = array(
 		'interval' => 300,
 		'display' => __( 'Every 5 minutes' )
 	);

 	$schedules['every_ten_min'] = array(
 		'interval' => 600,
 		'display' => __( 'Every 10 minutes' )
 	);

 	$schedules['every_twelve_min'] = array(
 		'interval' => 1200,
 		'display' => __( 'Every 20 minutes' )
 	);

 	$schedules['every_thirty_min'] = array(
 		'interval' => 1800,
 		'display' => __( 'Every 30 minutes' )
 	);

 	return $schedules;
}

//*********************************************************************************
function DeDoCron(){
    if ( get_option('DeCronEnable') === 'enable' ) {

        DeScheduleCronJobs();

    } elseif ( get_option('DeCronEnable') === '' ) {

        DeUnscheduleCronJobs();

    }
}

//*********************************************************************************
function DeUnscheduleCronJobs(){
    wp_clear_scheduled_hook('DePrNewPostsEvent');
    wp_clear_scheduled_hook('DePrOldPostsEvent');

}

//*********************************************************************************
function DeScheduleCronJobs(){
    $sRecurenciesForNewPosts = get_option('DeCronNewPosts');
    $sRecurenciesForOldPosts = get_option('DeCronOldPosts');

    if ( !wp_next_scheduled('DePrNewPostsEvent') )
		    $bCronNew = wp_schedule_event(time(), $sRecurenciesForNewPosts, 'DePrNewPostsEvent');

    if ( !wp_next_scheduled('DePrOldPostsEvent') )
		    wp_schedule_event(time(), $sRecurenciesForOldPosts, 'DePrOldPostsEvent');

}

//*********************************************************************************
function UpdateDefaultPluginOptions(){
    update_option('de_fb_method', 'dirty');
 	update_option('de_vk_type', 'share');
    update_option('de_ptypes', array('post'));
}

//*********************************************************************************
function DeletePluginOptionsAndTables(){

	delete_option("GaEnable");
	delete_option("GAClientID");
	delete_option("GAClientSecret");
	delete_option("GARedirectUri");
	delete_option("GAAuthorizationCode");
	delete_option("GAaccess_token");
	delete_option("GAcurrent_site_acc");

	delete_option("de_fb_enable");
	delete_option("de_fb_method");
	delete_option("de_fbAppId");
    delete_option("de_fbSecretKey");

	delete_option("de_vk_enable");
    delete_option("de_vk_type");
	delete_option("de_vk_method");
	delete_option("de_vkAppId");
    delete_option("de_vkSecretKey");

	delete_option("de_twi_enable");
	delete_option("de_li_enable");
	delete_option("de_gplus_enable");
    delete_option("de_ok_enable");
    delete_option("de_new_posts_day");

	global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS `pr_stats`");
    $wpdb->query("DROP TABLE IF EXISTS `social_rank`");
}

//*********************************************************************************
function ShowAfterContent($content){
    global $post;
    $iPostId = $post->ID;
    GetStatsByPostId($iPostId);
    return $content;
}

//*********************************************************************************
function DePrGetTotalShortcode( $atts ) {
    global $post;
    $iPostId = $post->ID;
    $aData = GetStatsByPostId($iPostId);
    return $aData['Total'];
}
add_shortcode('depr-total', 'DePrGetTotalShortcode');

//*********************************************************************************
function DePrGetSocialActionsShortcode( $atts ) {
    global $post;
    $iPostId = $post->ID;
    $aData = GetStatsByPostId($iPostId);
    return $aData['SocialActions'];
}
add_shortcode('depr-socialactions', 'DePrGetSocialActionsShortcode');

//*********************************************************************************
function DePrGetPageviewsShortcode( $atts ) {
    global $post;
    $iPostId = $post->ID;
    $aData = GetStatsByPostId($iPostId);
    return $aData['Views'];
}
add_shortcode('depr-pageviews', 'DePrGetPageviewsShortcode');

//*********************************************************************************

register_activation_hook( __FILE__, 	'RatingPluginActivate');
register_deactivation_hook( __FILE__, 	'RatingPluginDeactivate');

add_action('wp_insert_comment', 'RatingHandleNewComment');
add_filter('the_content', 'ShowAfterContent', 10, 1);

add_action('pr_update_new_posts',	'RatingUpdateSocialStatsForNewPosts');

//cron
add_filter('cron_schedules', 'RatingAddNewCronSchedule');
add_action('init', 'DeDoCron');
add_action( 'DePrNewPostsEvent', 'RatingUpdateSocialStatsForNewPosts' );
add_action( 'DePrOldPostsEvent', 'RatingUpdateSocialStatsForOldPosts' );

add_action('init', 'ManualRatingUpdate');
add_action('init', 'DebugOnly');

?>
