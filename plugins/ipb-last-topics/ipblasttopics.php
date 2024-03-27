<?php
/*
Plugin Name: Ipb Last Topics    
Plugin URI: http://wordpress.org/extend/plugins/ipb-last-topics/
Description: Show the last posts of ipb forum  
Version: 1.0
Author: Mahdi Khaksar
Author URI: http://www.progpars.com
License: iwordpress.ir
*/
define( 'MYPLUGINNAME_PATH', plugin_dir_path(__FILE__) );

load_plugin_textdomain('ipb','wp-content/plugins/ipb-last-topics/langs');
//require ipb_config.php
if(file_exists(MYPLUGINNAME_PATH .'/ipb_config.php')){
	require(MYPLUGINNAME_PATH .'/ipb_config.php');
}

// order by views
function lasttopics_popular($atts)
{
	//global variable
	global $mysql_connect,$wpdb,$ipb_mysqlquery,$row,$plugin_name,$ipburl,$dbhost,$dbname,$dbuser,$dbpass,$dbprifix,$limit;
	
	//cout
	 extract( shortcode_atts( array(
		  'count' => '5',
		  'views' => '0'
	 ), $atts ) );

	 $this_count = $atts['count'];

	//database connect and query
	$mysql_connect = mysql_connect($dbhost,$dbuser,$dbpass) or die("" . __('Error communicating with the database', 'ipb') . "");
	mysql_select_db($dbname) or die("" . __('Error communicating with the database', 'ipb') . "");
	mysql_query("SET NAMES 'latin1_swedish_ci'", $mysql_connect); 
	mysql_query("SET character_set_connection = 'latin1_swedish_ci'", $mysql_connect);

//html 
	$currentlang = get_bloginfo('language');


	// $ipb_mysqlquery = mysql_query("SELECT tid,title,posts,starter_id,start_date,last_poster_id,last_post,starter_name,last_poster_name,views,title_seo FROM ".$dbprifix."topics ORDER BY last_post DESC LIMIT $limit");
	$ipb_mysqlquery = mysql_query("SELECT tid,title,posts,starter_id,start_date,last_poster_id,last_post,starter_name,last_poster_name,views,title_seo FROM ".$dbprifix."topics ORDER BY views DESC LIMIT $limit");
	
	setlocale(LC_ALL, 'ru_RU.UTF-8');
		
		echo '<ul>';

			while($row = mysql_fetch_array($ipb_mysqlquery)) {
				$monthname_ru =  strftime('%m', $row['start_date']) ;
				$monthname_ru = month_full_name_ru_($monthname_ru);
				$ipb_title = "<a class=\"titlelinks\" href=\"".$ipburl."/index.php?showtopic=".$row['tid']."\" target=\"_blank\" >".$row['title']."</a>";
				$ipb_views = $row['views'];
				$ipb_posts = $row['posts'];
				//$ipb_last_poster_name = $row['last_poster_name'];
				$ipb_starter_name = $row['starter_name'];
				$ipb_date = strftime('%d', $row['start_date']) .' '.$monthname_ru.' '.strftime('в %g:%M', $row['start_date']);
				if ($atts['views'] == 1) { $ipb_views_on = '('.$ipb_views.')'; }

				  echo '<h4 class="entry-title">'. $ipb_title .' '.$ipb_views_on.'</h4>
				  		<div class="entry-meta">
				        	<span class="entry-date"><abbr class="published">'.$ipb_date.'</abbr></span>
							<span class="meta-sep">&#149;</span>
							<span class="meta-autor">'.$ipb_starter_name.'</span>

				        </div>';

			} //end while
		echo '</ul>';


	}


add_shortcode('lasttopics_popular','lasttopics_popular');

// order by comments
function lasttopics_comments($atts)
{
	//global variable
	global $mysql_connect,$wpdb,$ipb_mysqlquery,$row,$plugin_name,$ipburl,$dbhost,$dbname,$dbuser,$dbpass,$dbprifix,$limit;
	
	//cout
	 extract( shortcode_atts( array(
		  'count' => '5',
		  'comments' => '0'
	 ), $atts ) );

	 $this_count = $atts['count'];

	//database connect and query
	$mysql_connect = mysql_connect($dbhost,$dbuser,$dbpass) or die("" . __('Error communicating with the database', 'ipb') . "");
	mysql_select_db($dbname) or die("" . __('Error communicating with the database', 'ipb') . "");
	mysql_query("SET NAMES 'latin1_swedish_ci'", $mysql_connect); 
	mysql_query("SET character_set_connection = 'latin1_swedish_ci'", $mysql_connect);

//html 
	$currentlang = get_bloginfo('language');


	// $ipb_mysqlquery = mysql_query("SELECT tid,title,posts,starter_id,start_date,last_poster_id,last_post,starter_name,last_poster_name,views,title_seo FROM ".$dbprifix."topics ORDER BY last_post DESC LIMIT $limit");
	$ipb_mysqlquery = mysql_query("SELECT tid,title,posts,starter_id,start_date,last_poster_id,last_post,starter_name,last_poster_name,views,title_seo FROM ".$dbprifix."topics ORDER BY posts DESC LIMIT $limit");
	
	setlocale(LC_ALL, 'ru_RU.UTF-8');
		
		echo '<ul>';

			while($row = mysql_fetch_array($ipb_mysqlquery)) {
				$monthname_ru =  strftime('%m', $row['start_date']) ;
				$monthname_ru = month_full_name_ru_($monthname_ru);
				$ipb_title = "<a class=\"titlelinks\" href=\"".$ipburl."/index.php?showtopic=".$row['tid']."\" target=\"_blank\" >".$row['title']."</a>";
				$ipb_views = $row['views'];
				$ipb_posts = $row['posts'];
				//$ipb_last_poster_name = $row['last_poster_name'];
				$ipb_starter_name = $row['starter_name'];
				$ipb_date = strftime('%d', $row['start_date']) .' '.$monthname_ru.' '.strftime('в %g:%M', $row['start_date']);
				if ($atts['comments'] == 1) { $ipb_comments_on = '('.$ipb_posts.')'; }

				  echo '<h4 class="entry-title">'. $ipb_title .' '.$ipb_comments_on.'</h4>
						<div class="entry-meta">
				        	<span class="entry-date"><abbr class="published">'.$ipb_date.'</abbr></span>
							<span class="meta-sep">&#149;</span>
							<span class="meta-autor">'.$ipb_starter_name.'</span>
							
				        </div>';

			} //end while
		echo '</ul>';


	}


add_shortcode('lasttopics_comments','lasttopics_comments');


//last post for _led
function lasttopics_led($atts)
{
	//global variable
	global $mysql_connect,$wpdb,$ipb_mysqlquery,$row,$plugin_name,$ipburl,$dbhost,$dbname,$dbuser,$dbpass,$dbprifix,$limit;
	
	//cout
	 extract( shortcode_atts( array(
		  'count' => '5'
	 ), $atts ) );

	 $this_count = $atts['count'];

	//database connect and query
	$mysql_connect = mysql_connect($dbhost,$dbuser,$dbpass) or die("" . __('Error communicating with the database', 'ipb') . "");
	mysql_select_db($dbname) or die("" . __('Error communicating with the database', 'ipb') . "");
	mysql_query("SET NAMES 'latin1_swedish_ci'", $mysql_connect); 
	mysql_query("SET character_set_connection = 'latin1_swedish_ci'", $mysql_connect);

	//html 
	$currentlang = get_bloginfo('language');

		 //$ipb_mysqlquery = mysql_query("SELECT tid,title,posts,starter_id,start_date,last_poster_id,last_post,starter_name,last_poster_name,views,title_seo FROM ".$dbprifix."topics ORDER BY last_post DESC LIMIT $limit");
	       $ipb_mysqlquery = mysql_query(
	       	"SELECT m.id,m.name,p.forum_id,p.tid,p.title,p.posts,p.starter_id,p.start_date,p.last_poster_id,p.last_post,p.starter_name,p.last_poster_name,p.views,p.title_seo
	       	 FROM topics p
	       	 LEFT JOIN forums m
	       	 ON ( m.id = p.forum_id )
	       	 ORDER BY last_post 
	       	 DESC LIMIT $this_count"
	       	 );


	        //var_dump(mysql_fetch_array($ipb_mysqlquery));

		setlocale(LC_ALL, 'ru_RU.UTF-8');
		
		echo '<ul>';
			 
			while($row = mysql_fetch_array($ipb_mysqlquery)) {
				//echo $atts['count'];
			 	$ipb_cat = $row['name'];
				$monthname_ru =  strftime('%m', $row['start_date']) ;
				$monthname_ru = month_full_name_ru_($monthname_ru);
				$ipb_title = "<a class=\"titlelinks\" href=\"".$ipburl."/index.php?showtopic=".$row['tid']."\" target=\"_blank\" >".$row['title']."</a>";
				$ipb_views = $row['views'];
				$ipb_posts = $row['posts'];
				//$ipb_last_poster_name = $row['last_poster_name'];
				$ipb_starter_name = $row['starter_name'];
				$ipb_date = strftime('%d', $row['start_date']) .' '.$monthname_ru.' '.strftime('в %g:%M', $row['start_date']);
				
				  echo '<span class="entry-cat">'.$ipb_cat.'</span>
				  		<h4 class="entry-title">'. $ipb_title .'</h4>
						<div class="entry-meta">
				        	<span class="entry-date"><abbr class="published">'.$ipb_date.'</abbr></span>
							<span class="meta-sep">&#149;</span>
							<span class="meta-autor">'.$ipb_starter_name.'</span>
							
				        </div>';

			} //end while
		echo '</ul>';


	}
add_shortcode('lasttopics_led','lasttopics_led');


//statistic forum cat
function lasttopics_statistic($atts)
{
	//global variable
	global $mysql_connect,$wpdb,$ipb_mysqlquery,$row,$plugin_name,$ipburl,$dbhost,$dbname,$dbuser,$dbpass,$dbprifix,$limit;
	
	//cout
	 extract( shortcode_atts( array(
		  'count' => '5'
	 ), $atts ) );

	 $this_count = $atts['count'];

	//database connect and query
	$mysql_connect = mysql_connect($dbhost,$dbuser,$dbpass) or die("" . __('Error communicating with the database', 'ipb') . "");
	mysql_select_db($dbname) or die("" . __('Error communicating with the database', 'ipb') . "");
	mysql_query("SET NAMES 'latin1_swedish_ci'", $mysql_connect); 
	mysql_query("SET character_set_connection = 'latin1_swedish_ci'", $mysql_connect);

	//html 
	$currentlang = get_bloginfo('language');

		 //$ipb_mysqlquery = mysql_query("SELECT tid,title,posts,starter_id,start_date,last_poster_id,last_post,starter_name,last_poster_name,views,title_seo FROM ".$dbprifix."topics ORDER BY last_post DESC LIMIT $limit");
	       $ipb_mysqlquery = mysql_query(
	       	"SELECT topics, posts, name, id, name_seo
	       	 FROM forums
	       	 ORDER BY posts 
	       	 DESC LIMIT $this_count"
	       	 );


	        //var_dump(mysql_fetch_array($ipb_mysqlquery));

		setlocale(LC_ALL, 'ru_RU.UTF-8');
		
		echo '<ul>';
			 echo '<h2>Статистика</h2>';

			while($row = mysql_fetch_array($ipb_mysqlquery)) {
				//echo $atts['count'];
			 	$ipb_cat = $row['name'];

				$ipb_title = "<a class=\"titlelinks\" href=\"".$ipburl."/forum/".$row['id']."-".$row['name_seo']."\" target=\"_blank\" >".$row['name']."</a>";
				$ipb_topics = $row['topics'];
				$ipb_posts = $row['posts'];
				
				

				  echo '<h4 class="entry-title">'.$ipb_title.'</h4>
				  		<div class="entry-meta">
				        	<span class="entry-date"><abbr class="published">тем </abbr></span><span class="meta-autor">'.$ipb_topics.'</span>
							<span class="meta-sep">&#149;</span>
							<span class="meta-autor">'.$ipb_posts.'</span><span class="entry-date"> ответов</span>
							
				        </div>';

			} //end while
		echo '</ul>';


	}
add_shortcode('lasttopics_statistic','lasttopics_statistic');



//last post
function lasttopics()
{
	//global variable
	global $mysql_connect,$wpdb,$ipb_mysqlquery,$row,$plugin_name,$ipburl,$dbhost,$dbname,$dbuser,$dbpass,$dbprifix,$limit;
	
	//database connect and query
	$mysql_connect = mysql_connect($dbhost,$dbuser,$dbpass) or die("" . __('Error communicating with the database', 'ipb') . "");
	mysql_select_db($dbname) or die("" . __('Error communicating with the database', 'ipb') . "");
	mysql_query("SET NAMES 'latin1_swedish_ci'", $mysql_connect); 
	mysql_query("SET character_set_connection = 'latin1_swedish_ci'", $mysql_connect);

//html 
	$currentlang = get_bloginfo('language');
if($currentlang=="fa-IR")
{
	echo '<!doctype html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>'. __('Recent Forum Posts', 'ipb') .'</title>	
	<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/'. basename(dirname(__FILE__)) .'/ipblasttopics_css_rtl.css" />
	</head>
	<body>
	<div class="ForumLastTopic">
		  <div class="Head">
		   <span class="col1">'. __('Title Latest Posts Forum', 'ipb') .'</span>
			<span class="col2">'. __('Reply', 'ipb') .'</span>
			 <span class="col3">'. __('Visit', 'ipb') .'</span>
			  <span class="col4">'. __('Last Post', 'ipb') .'</span>
			<div class="Clear"></div>
		  </div>
		  <div id="MTForumBlock">
		   <table cellspacing="1" cellpadding="0" class="Forumsbox">
						<tbody>';
	$ipb_mysqlquery = mysql_query("SELECT tid,title,posts,starter_id,start_date,last_poster_id,last_post,starter_name,last_poster_name,views,title_seo FROM ".$dbprifix."topics ORDER BY last_post DESC LIMIT $limit");
	
	while($row = mysql_fetch_array($ipb_mysqlquery)) {

	echo "
			<tr>
				<td class=\"col1\"><img class=\"titleicon\" src='" . get_bloginfo('wpurl') . '/wp-content/plugins/'. basename(dirname(__FILE__)) ."/images/topic.png' /><a class=\"titlelinks\" href=\"".$ipburl."/index.php?showtopic=".$row['tid']."\" target=\"_blank\" >".$row['title']."</a></td>
				<td class=\"col2\">".$row['posts']."</td>
				<td class=\"col3\">".$row['views']."</td>
				<td class=\"col4\">".$row['last_poster_name']."</td>
			</tr>";
	}

	echo "
		</tbody>
		  </table>
		</div>
	</div>
	</body>
	</html>";
}
else
{
	echo '<!doctype html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>'.__('Recent Forum Posts', 'ipb').'</title>	
	<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/'. basename(dirname(__FILE__)) .'/ipblasttopics_css_ltr.css" />
	</head>
	<body>
	<div class="ForumLastTopic">
		  <div class="Head">			 
		   <span class="col4">'. __('Last Post', 'ipb') .'</span>
		     <span class="col3">'. __('Visit', 'ipb') .'</span>
			<span class="col2">'. __('Reply', 'ipb') .'</span>
		    <span class="col1">'. __('Title Latest Posts Forum', 'ipb') .'</span>
			<div class="Clear"></div>
		  </div>
		  <div id="MTForumBlock">
		   <table cellspacing="1" cellpadding="0" class="Forumsbox">
						<tbody>';
	$ipb_mysqlquery = mysql_query("SELECT tid,title,posts,starter_id,start_date,last_poster_id,last_post,starter_name,last_poster_name,views,title_seo FROM ".$dbprifix."topics ORDER BY last_post DESC LIMIT $limit");
	
	while($row = mysql_fetch_array($ipb_mysqlquery)) {

	echo "
			<tr>
				<td class=\"col1\"><img class=\"titleicon\" src='" . get_bloginfo('wpurl') . '/wp-content/plugins/'. basename(dirname(__FILE__)) ."/images/topic.png' /><a class=\"titlelinks\" href=\"".$ipburl."/index.php?showtopic=".$row['tid']."\" target=\"_blank\" >".$row['title']."</a></td>
				<td class=\"col2\">".$row['posts']."</td>
				<td class=\"col3\">".$row['views']."</td>
				<td class=\"col4\">".$row['last_poster_name']."</td>
			</tr>";
	}

	echo "
		</tbody>
		  </table>
		</div>
	</div>
	</body>
	</html>";
}
	}
add_shortcode('lasttopics','lasttopics');

//plugin admin options
function admin_options_lasttopics()
{
	$plugin_name = MYPLUGINNAME_PATH .'/panel_ipblasttopics.php';
	if(is_admin())
	{
		add_menu_page(''. __(' Latest Posts Forum Ipb', 'ipb') .'', ''. __('IPB', 'ipb') .'', 'administrator',$plugin_name,'',plugins_url('images/icon.png',__FILE__),26);
	}
	
}
//add_action('admin_menu', 'admin_options_lasttopics');

 	function month_full_name_ru_( $m ) {

		$month = array(
			"01"  => "Января",
			"02"  => "Февраля",
			"03"  => "Марта",
			"04"  => "апреля",
			"05"  => "Мая",
			"06"  => "Июня",
			"07"  => "Июля",
			"08"  => "Августа",
			"09"  => "Сентября",
			"10" => "Октября",
			"11" => "Ноября",
			"12" => "Декабря"
		);

		return strtr( $m, $month );
	}

?>