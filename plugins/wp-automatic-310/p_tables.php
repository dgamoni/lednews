<?php 

/* ------------------------------------------------------------------------*
 * Multisite add new blog
 * ------------------------------------------------------------------------*/
add_action( 'wpmu_new_blog', 'wp_automatic_new_blog', 10, 6);

function wp_automatic_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta ) {
	global $wpdb;


	if (is_plugin_active_for_network('wp-automatic/wp-automatic.php')) {
		$old_blog = $wpdb->blogid;
		switch_to_blog($blog_id);
		create_table_alb();
		switch_to_blog($old_blog);
	}
}


/* ------------------------------------------------------------------------*
 * Add Table when First activation Network activate
 * ------------------------------------------------------------------------*/



function create_table_all(){
	global $wpdb;

	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				create_table_alb();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	create_table_alb();

}



/* ------------------------------------------------------------------------*
 *Create a table for single blog
 * ------------------------------------------------------------------------*/

function create_table_alb()

{

	global $wpdb;
	$prefix=$wpdb->prefix ;

	//comments table
	if(!exists_table_alb("{$prefix}automatic_camps")){
		
		delete_option('wp_automatic_version');
		
		
		//$query=file_get_contents('wp1_002.sql');
		$querys="SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";


CREATE TABLE IF NOT EXISTS `{$prefix}automatic_articles_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `camp_id` int(11) NOT NULL,
  `keyword` varchar(200) NOT NULL,
  `page_num` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `last_page` int(11) NOT NULL DEFAULT '999',
  `articlesbase_lastadd` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_keywordID` (`camp_id`,`keyword`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

CREATE TABLE IF NOT EXISTS `{$prefix}automatic_articles_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(300) NOT NULL,
  `keyword` varchar(300) NOT NULL,
  `page_num` int(11) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  `title` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

CREATE TABLE IF NOT EXISTS `{$prefix}automatic_camps` (
  `camp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Campaign ID',
  `camp_name` varchar(100) NOT NULL,
  `camp_keywords` text NOT NULL,
  `camp_post_title` varchar(150) NOT NULL,
  `camp_post_content` text NOT NULL,
  `camp_cb_category` varchar(300) NOT NULL,
  `camp_replace_link` varchar(100) NOT NULL,
  `camp_post_status` varchar(20) NOT NULL,
  `camp_post_every` int(11) NOT NULL,
  `camp_add_star` varchar(20) NOT NULL,
  `camp_post_category` varchar(20) NOT NULL,
  `camp_options` text NOT NULL,
  `feeds` text NOT NULL,
  PRIMARY KEY (`camp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1951 ;

CREATE TABLE IF NOT EXISTS `{$prefix}automatic_categories` (
  `cat_id` int(11) NOT NULL,
  `cat_parent` int(11) NOT NULL DEFAULT '0',
  `cat_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `{$prefix}automatic_feeds_links` (
  `camp_id` varchar(150) NOT NULL,
  `link` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `{$prefix}automatic_feeds_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `camp_id` int(11) NOT NULL,
  `feed` varchar(300) NOT NULL,
  `feed_lastadd` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_feeds` (`camp_id`,`feed`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `{$prefix}automatic_keywords` (
  `keyword_id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword_name` varchar(300) NOT NULL,
  `keyword_camp` int(11) NOT NULL,
  `keyword_start` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`keyword_id`),
  UNIQUE KEY `keyword_name` (`keyword_name`,`keyword_camp`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

CREATE TABLE IF NOT EXISTS `{$prefix}automatic_links` (
  `link_url` varchar(500) NOT NULL,
  `link_title` varchar(500) NOT NULL,
  `link_keyword` varchar(300) NOT NULL,
  `link_status` varchar(20) NOT NULL,
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `{$prefix}automatic_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(50) NOT NULL,
  `data` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `camp` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=279 ;
			
			

			";

		//executing quiries
		$que=explode(';',$querys);
			
		foreach($que  as $query){
			if(trim($query)!=''){
				$wpdb->query($query);
			}
		}
	}//if not exist table
		
		
	//delete old links to remove duplicate if the new version installed
 	$version=get_option('wp_automatic_version',0);

	if($version == 0){
		$query="delete from {$prefix}automatic_articles_links ";
		$wpdb->query($query);
		update_option('wp_automatic_version',1.1);
	}

	//version 2 lets add
	if($version == 0 || $version == 1.1){
		//add clickbank cats
		$query="INSERT INTO `{$prefix}automatic_categories` (`cat_id`, `cat_parent`, `cat_name`) VALUES
(1253, 0, 'Arts &amp; Entertainment'),
(1510, 0, 'Betting Systems'),
(1266, 0, 'Business / Investing'),
(1283, 0, 'Computers / Internet'),
(1297, 0, 'Cooking, Food &amp; Wine'),
(1308, 0, 'E-business &amp; E-marketing'),
(1362, 0, 'Education'),
(1332, 0, 'Employment &amp; Jobs'),
(1338, 0, 'Fiction'),
(1340, 0, 'Games'),
(1344, 0, 'Green Products'),
(1347, 0, 'Health &amp; Fitness'),
(1366, 0, 'Home &amp; Garden'),
(1377, 0, 'Languages'),
(1392, 0, 'Mobile'),
(1400, 0, 'Parenting &amp; Families'),
(1408, 0, 'Politics / Current Events'),
(1410, 0, 'Reference'),
(1419, 0, 'Self-Help'),
(1432, 0, 'Software &amp; Services'),
(1461, 0, 'Spirituality, New Age &amp; Alternative Beliefs'),
(1472, 0, 'Sports'),
(1494, 0, 'Travel'),
(1265, 1253, 'Architecture'),
(1259, 1253, 'Art'),
(1254, 1253, 'Body Art'),
(1261, 1253, 'Dance'),
(1263, 1253, 'Fashion'),
(1255, 1253, 'Film &amp; Television'),
(1260, 1253, 'General'),
(1262, 1253, 'Humor'),
(1519, 1253, 'Magic Tricks'),
(1256, 1253, 'Music'),
(1257, 1253, 'Photography'),
(1258, 1253, 'Radio'),
(1264, 1253, 'Theater'),
(1515, 1510, 'Casino Table Games'),
(1517, 1510, 'Football'),
(1511, 1510, 'General'),
(1512, 1510, 'Horse Racing'),
(1514, 1510, 'Lottery'),
(1513, 1510, 'Poker'),
(1516, 1510, 'Soccer'),
(1275, 1266, 'Careers, Industries &amp; Professions'),
(1520, 1266, 'Commodities'),
(1277, 1266, 'Debt'),
(1271, 1266, 'Derivatives'),
(1280, 1266, 'Economics'),
(1268, 1266, 'Equities &amp; Stocks'),
(1267, 1266, 'Foreign Exchange'),
(1270, 1266, 'General'),
(1276, 1266, 'International Business'),
(1278, 1266, 'Management &amp; Leadership'),
(1273, 1266, 'Marketing &amp; Sales'),
(1279, 1266, 'Outsourcing'),
(1274, 1266, 'Personal Finance'),
(1269, 1266, 'Real Estate'),
(1272, 1266, 'Small Biz / Entrepreneurship'),
(1290, 1283, 'Databases'),
(1295, 1283, 'Email Services'),
(1292, 1283, 'General'),
(1285, 1283, 'Graphics'),
(1288, 1283, 'Hardware'),
(1291, 1283, 'Networking'),
(1293, 1283, 'Operating Systems'),
(1287, 1283, 'Programming'),
(1286, 1283, 'Software'),
(1296, 1283, 'System Administration'),
(1294, 1283, 'System Analysis &amp; Design'),
(1289, 1283, 'Web Hosting'),
(1284, 1283, 'Web Site Design'),
(1300, 1297, 'BBQ'),
(1298, 1297, 'Baking'),
(1303, 1297, 'Cooking'),
(1304, 1297, 'Drinks &amp; Beverages'),
(1305, 1297, 'General'),
(1299, 1297, 'Recipes'),
(1306, 1297, 'Regional &amp; Intl.'),
(1301, 1297, 'Special Diet'),
(1302, 1297, 'Special Occasions'),
(1307, 1297, 'Vegetables / Vegetarian'),
(1521, 1297, 'Wine Making'),
(1309, 1308, 'Affiliate Marketing'),
(1311, 1308, 'Article Marketing'),
(1326, 1308, 'Auctions'),
(1330, 1308, 'Banners'),
(1318, 1308, 'Blog Marketing'),
(1323, 1308, 'Classified Advertising'),
(1328, 1308, 'Consulting'),
(1327, 1308, 'Copywriting'),
(1325, 1308, 'Domains'),
(1312, 1308, 'E-commerce Operations'),
(1320, 1308, 'E-zine Strategies'),
(1321, 1308, 'Email Marketing'),
(1324, 1308, 'General'),
(1317, 1308, 'Market Research'),
(1319, 1308, 'Marketing'),
(1322, 1308, 'Niche Marketing'),
(1314, 1308, 'Paid Surveys'),
(1313, 1308, 'Pay Per Click Advertising'),
(1310, 1308, 'Promotion'),
(1315, 1308, 'SEM &amp; SEO'),
(1331, 1308, 'Social Media Marketing'),
(1316, 1308, 'Submitters'),
(1329, 1308, 'Video Marketing'),
(1364, 1362, 'Admissions'),
(1522, 1362, 'Educational Materials'),
(1523, 1362, 'Higher Education'),
(1524, 1362, 'K-12'),
(1365, 1362, 'Student Loans'),
(1363, 1362, 'Test Prep &amp; Study Guides'),
(1335, 1332, 'Cover Letter &amp; Resume Guides'),
(1337, 1332, 'General'),
(1334, 1332, 'Job Listings'),
(1336, 1332, 'Job Search Guides'),
(1333, 1332, 'Job Skills / Training'),
(1339, 1338, 'General'),
(1342, 1340, 'Console Guides &amp; Repairs'),
(1343, 1340, 'General'),
(1341, 1340, 'Strategy Guides'),
(1345, 1344, 'Alternative Energy'),
(1346, 1344, 'Conservation &amp; Efficiency'),
(1525, 1344, 'General'),
(1357, 1347, 'Addiction'),
(1361, 1347, 'Beauty'),
(1359, 1347, 'Dental Health'),
(1348, 1347, 'Diets &amp; Weight Loss'),
(1354, 1347, 'Exercise &amp; Fitness'),
(1360, 1347, 'General'),
(1526, 1347, 'Meditation'),
(1352, 1347, 'Men&#039;s Health'),
(1355, 1347, 'Mental Health'),
(1350, 1347, 'Nutrition'),
(1349, 1347, 'Remedies'),
(1527, 1347, 'Sleep and Dreams'),
(1356, 1347, 'Spiritual Health'),
(1351, 1347, 'Strength Training'),
(1353, 1347, 'Women&#039;s Health'),
(1358, 1347, 'Yoga'),
(1368, 1366, 'Animal Care &amp; Pets'),
(1371, 1366, 'Crafts &amp; Hobbies'),
(1374, 1366, 'Entertaining'),
(1367, 1366, 'Gardening &amp; Horticulture'),
(1373, 1366, 'General'),
(1376, 1366, 'Homebuying'),
(1369, 1366, 'How-to &amp; Home Improvements'),
(1375, 1366, 'Interior Design'),
(1372, 1366, 'Sewing'),
(1370, 1366, 'Weddings'),
(1391, 1377, 'Arabic'),
(1383, 1377, 'Chinese'),
(1385, 1377, 'English'),
(1378, 1377, 'French'),
(1381, 1377, 'German'),
(1389, 1377, 'Hebrew'),
(1390, 1377, 'Hindi'),
(1382, 1377, 'Italian'),
(1380, 1377, 'Japanese'),
(1387, 1377, 'Other'),
(1386, 1377, 'Russian'),
(1384, 1377, 'Sign Language'),
(1379, 1377, 'Spanish'),
(1388, 1377, 'Thai'),
(1518, 1392, 'Apps'),
(1397, 1392, 'Developer Tools'),
(1395, 1392, 'General'),
(1393, 1392, 'Ringtones'),
(1396, 1392, 'Security'),
(1394, 1392, 'Video'),
(1528, 1400, 'Divorce'),
(1402, 1400, 'Education'),
(1405, 1400, 'Genealogy'),
(1407, 1400, 'General'),
(1406, 1400, 'Marriage'),
(1403, 1400, 'Parenting'),
(1401, 1400, 'Pregnancy &amp; Childbirth'),
(1404, 1400, 'Special Needs'),
(1409, 1408, 'General'),
(1529, 1410, 'Automotive'),
(1412, 1410, 'Catalogs &amp; Directories'),
(1411, 1410, 'Consumer Guides'),
(1413, 1410, 'Education'),
(1418, 1410, 'Etiquette'),
(1416, 1410, 'Gay / Lesbian'),
(1417, 1410, 'General'),
(1414, 1410, 'Law &amp; Legal Issues'),
(1530, 1410, 'The Sciences'),
(1415, 1410, 'Writing'),
(1431, 1419, 'Abuse'),
(1423, 1419, 'Dating Guides'),
(1430, 1419, 'Eating Disorders'),
(1427, 1419, 'General'),
(1420, 1419, 'Marriage &amp; Relationships'),
(1422, 1419, 'Motivational / Transformational'),
(1426, 1419, 'Personal Finance'),
(1531, 1419, 'Public Speaking'),
(1532, 1419, 'Self Defense'),
(1429, 1419, 'Self-Esteem'),
(1421, 1419, 'Stress Management'),
(1425, 1419, 'Success'),
(1428, 1419, 'Time Management'),
(1438, 1432, 'Anti Adware / Spyware'),
(1436, 1432, 'Background Investigations'),
(1460, 1432, 'Communications'),
(1441, 1432, 'Dating'),
(1457, 1432, 'Developer Tools'),
(1456, 1432, 'Digital Photos'),
(1437, 1432, 'Drivers'),
(1453, 1432, 'Education'),
(1448, 1432, 'Email'),
(1433, 1432, 'Foreign Exchange Investing'),
(1445, 1432, 'General'),
(1452, 1432, 'Graphic Design'),
(1446, 1432, 'Hosting'),
(1444, 1432, 'Internet Tools'),
(1447, 1432, 'MP3 &amp; Audio'),
(1458, 1432, 'Networking'),
(1455, 1432, 'Operating Systems'),
(1443, 1432, 'Other Investment Software'),
(1459, 1432, 'Personal Finance'),
(1450, 1432, 'Productivity'),
(1434, 1432, 'Registry Cleaners'),
(1435, 1432, 'Reverse Phone Lookup'),
(1454, 1432, 'Screensavers &amp; Wallpaper'),
(1439, 1432, 'Security'),
(1440, 1432, 'System Optimization'),
(1449, 1432, 'Utilities'),
(1442, 1432, 'Video'),
(1451, 1432, 'Web Design'),
(1465, 1461, 'Astrology'),
(1470, 1461, 'General'),
(1463, 1461, 'Hypnosis'),
(1466, 1461, 'Magic'),
(1462, 1461, 'Numerology'),
(1468, 1461, 'Paranormal'),
(1467, 1461, 'Psychics'),
(1469, 1461, 'Religion'),
(1471, 1461, 'Tarot'),
(1464, 1461, 'Witchcraft'),
(1483, 1472, 'Automotive'),
(1476, 1472, 'Baseball'),
(1485, 1472, 'Basketball'),
(1473, 1472, 'Coaching'),
(1533, 1472, 'Cycling'),
(1491, 1472, 'Extreme Sports'),
(1488, 1472, 'Football'),
(1490, 1472, 'General'),
(1474, 1472, 'Golf'),
(1493, 1472, 'Hockey'),
(1481, 1472, 'Individual Sports'),
(1480, 1472, 'Martial Arts'),
(1492, 1472, 'Mountaineering'),
(1484, 1472, 'Other Team Sports'),
(1482, 1472, 'Outdoors &amp; Nature'),
(1479, 1472, 'Racket Sports'),
(1534, 1472, 'Running'),
(1477, 1472, 'Soccer'),
(1489, 1472, 'Softball'),
(1478, 1472, 'Training'),
(1475, 1472, 'Volleyball'),
(1486, 1472, 'Water Sports'),
(1487, 1472, 'Winter Sports'),
(1535, 1494, 'Africa'),
(1495, 1494, 'Asia'),
(1500, 1494, 'Canada'),
(1503, 1494, 'Caribbean'),
(1499, 1494, 'Europe'),
(1498, 1494, 'General'),
(1501, 1494, 'Latin America'),
(1502, 1494, 'Middle East'),
(1496, 1494, 'Specialty Travel'),
(1497, 1494, 'United States');
			";
		$wpdb->query($query);
			
		//add other fileds
		$querys="ALTER TABLE `{$prefix}automatic_camps` ADD `camp_type` VARCHAR( 20 ) NOT NULL DEFAULT 'Articles';

ALTER TABLE `{$prefix}automatic_camps` ADD `camp_search_order` VARCHAR( 100 ) NOT NULL ;

ALTER TABLE `{$prefix}automatic_camps` ADD `camp_amazon_cat` VARCHAR( 100 ) NOT NULL ;

ALTER TABLE `{$prefix}automatic_camps` ADD `camp_youtube_cat` VARCHAR( 100 ) NOT NULL ,
ADD `camp_youtube_order` VARCHAR( 100 ) NOT NULL ;


ALTER TABLE `{$prefix}automatic_keywords` ADD `clickbank_start` INT NOT NULL DEFAULT '1' ;
ALTER TABLE `{$prefix}automatic_keywords` ADD `amazon_start` INT NOT NULL DEFAULT '1' ;



CREATE TABLE IF NOT EXISTS `{$prefix}automatic_amazon_links` (
  `link_url` varchar(500) NOT NULL,
  `link_title` varchar(500) NOT NULL,
  `link_keyword` varchar(300) NOT NULL,
  `link_status` varchar(20) NOT NULL,
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `link_desc` text NOT NULL,
  `link_price` text NOT NULL,
  `link_img` varchar(500) NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `{$prefix}automatic_youtube_links` (
  `link_url` varchar(500) NOT NULL,
  `link_title` varchar(500) NOT NULL,
  `link_keyword` varchar(300) NOT NULL,
  `link_status` varchar(20) NOT NULL,
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `link_desc` text NOT NULL,
  `link_player` text NOT NULL,
  `link_img` varchar(500) NOT NULL,
  `link_rating` text NOT NULL,
  `link_views` varchar(20) NOT NULL,
  `link_time` varchar(10) NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;



CREATE TABLE IF NOT EXISTS `{$prefix}automatic_clickbank_links` (
  `link_url` varchar(500) NOT NULL,
  `link_title` varchar(500) NOT NULL,
  `link_keyword` varchar(300) NOT NULL,
  `link_status` varchar(20) NOT NULL,
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `link_desc` text NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;
			";
			
		//executing quiries
		$que=explode(';',$querys);
			
		foreach($que  as $query){
			if(trim($query)!=''){
				$wpdb->query($query);
			}
		}
			
		//update camp_type for old camps
		$query="update {$prefix}automatic_camps set camp_type = 'Feeds' where camp_options like '%OPT_POST_FEED%'";
		$wpdb->query($query);
			
		update_option('wp_automatic_version',2);
			
			
	}// version 2 adjustment


	//version 2.0.9 adjustment of using bing for articles other than ezine articles directly
	

	if($version == 0 || $version == 1.1 || $version == 2 ){
			
		//alter table
		$query="ALTER TABLE `{$prefix}automatic_articles_links` ADD `bing_cache` TEXT NOT NULL ";
		$wpdb->query($query);
			
		//delete EA links
		$query="delete from {$prefix}automatic_articles_links where status=0";
		$wpdb->query($query);
			
		//reset articles pointer to 0 to search bing from the start
		$query="update {$prefix}automatic_articles_keys set page_num=0 ,last_page=999 where status=0";
		$wpdb->query($query);
			
		//update version
		update_option('wp_automatic_version',3 ); //version 2.0.9
	}

	//version 2.0.11 : bug fix: charachterset of the wp_automatic_camps to utf to accept non english chars0
	if($version == 0 || $version == 1.1 || $version == 2 || $version == 3 ){
			
		//alter table
		$querys="ALTER TABLE {$prefix}automatic_camps CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
					ALTER TABLE {$prefix}automatic_amazon_links CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
					ALTER TABLE {$prefix}automatic_articles_keys CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
					ALTER TABLE {$prefix}automatic_articles_links CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
					ALTER TABLE {$prefix}automatic_categories CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
					ALTER TABLE {$prefix}automatic_clickbank_links CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
					ALTER TABLE {$prefix}automatic_feeds_links CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
					ALTER TABLE `{$prefix}automatic_feeds_list` CHANGE `feed` `feed` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;
					ALTER TABLE {$prefix}automatic_feeds_list CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
					ALTER TABLE `{$prefix}automatic_keywords` CHANGE `keyword_name` `keyword_name` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL; 
					ALTER TABLE {$prefix}automatic_keywords CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
					ALTER TABLE {$prefix}automatic_links CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
					ALTER TABLE {$prefix}automatic_log CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
					ALTER TABLE {$prefix}automatic_youtube_links CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
			";
		
	//executing quiries
		$que=explode(';',$querys);
			
		foreach($que  as $query){
			if(trim($query)!=''){
				$wpdb->query($query);
			}
		}
		
  
		//update version
		update_option('wp_automatic_version',4 ); //version 2.0.11
	}
	
	//version 5
	if($version == 0 || $version == 1.1 || $version == 2 || $version == 3  || $version == 4 ){

		//alter table
		$query="ALTER TABLE `{$prefix}automatic_camps` ADD `camp_amazon_region` varchar(50) ";
		$wpdb->query($query);
			
		
		//update version
		update_option('wp_automatic_version',5 ); //version 2.1
	}
	
	//table version 6
	if($version == 0 || $version == 1.1 || $version == 2 || $version == 3  || $version == 4 || $version == 5 ){
	
		//alter table wp_automaic_custom
		
		$query="ALTER TABLE `{$prefix}automatic_camps` ADD `camp_post_author` INT NOT NULL , ADD `camp_post_custom_k` TEXT NOT NULL , ADD `camp_post_custom_v` TEXT NOT NULL  , ADD `camp_post_exact` TEXT NOT NULL ,  ADD `camp_general` TEXT NOT NULL ,
ADD `camp_post_execlude` TEXT NOT NULL ,  ADD `camp_yt_user` VARCHAR( 59 ) NOT NULL  , ADD `camp_translate_to` VARCHAR( 59 ) NOT NULL  , ADD `camp_translate_from` VARCHAR( 59 ) NOT NULL , ADD `camp_translate_to_2` VARCHAR( 59 ) NOT NULL , ADD `camp_post_type` VARCHAR( 59 ) NOT NULL DEFAULT 'post' ;";
		$wpdb->query($query);
		
		//new camp general table 
		$query="CREATE TABLE IF NOT EXISTS `{$prefix}automatic_general` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` varchar(50) NOT NULL,
  `item_type` varchar(50) NOT NULL,
  `item_status` varchar(50) NOT NULL,
  `item_data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		";
		$wpdb->query($query);
		
		//new field to yoube links author 
		$query ="ALTER TABLE `{$prefix}automatic_youtube_links` ADD `link_author` VARCHAR( 40 ) NOT NULL ;";
		$wpdb->query($query);
		
		//clear logs 
		$query ="delete from {$prefix}automatic_log ";
		$wpdb->query($query);
		
		//update version
		update_option('wp_automatic_version',6 ); //version 2.1
	}
	
	
	if($version < 7 ){
		$query="UPDATE  `{$prefix}posts` SET  `post_type` =  'wp_automatic' WHERE  `post_type` = 'goldminer';";
		$wpdb->query($query);
		update_option('wp_automatic_version',7 );
	}
	
	//new version 8 fix char encoding of wp_auotmatic_general
	
	if($version < 8 ){
		$query="ALTER TABLE {$prefix}automatic_general CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;";
		$wpdb->query($query);
		update_option('wp_automatic_version',8 );
	}
	

	//new version 9 youtube duration
	
	if($version < 9 ){
		$query="ALTER TABLE `{$prefix}automatic_youtube_links` ADD `link_duration` VARCHAR( 40 ) NOT NULL DEFAULT '00:00';";
		$wpdb->query($query);
		update_option('wp_automatic_version',9 );
	}
	
	
	if($version < 10 ){
		$query="ALTER TABLE `{$prefix}automatic_amazon_links` ADD `link_review` text NOT NULL DEFAULT '';";
		$wpdb->query($query);
		update_option('wp_automatic_version',10 );
		
	}
	
	
	if($version < 12 ){
		$query="ALTER TABLE `{$prefix}automatic_camps` CHANGE `camp_post_title` `camp_post_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;";
		$wpdb->query($query);
		update_option('wp_automatic_version',11 );
	}
	
	if($version < 13 ){
		$query="CREATE TABLE IF NOT EXISTS `{$prefix}automatic_cached` (
  `img_id` int(11) NOT NULL AUTO_INCREMENT,
  `img_external` text NOT NULL,
  `img_internal` text NOT NULL,
  `img_path` text NOT NULL,
  `img_hash` varchar(50) NOT NULL,
  `img_data_hash` varchar(50) NOT NULL,
  PRIMARY KEY (`img_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;";
		$wpdb->query($query);
		update_option('wp_automatic_version',12 );
	}

}//end fun

/* ------------------------------------------------------------------------*
 * check if the table exists or return 0
 * ------------------------------------------------------------------------*/

function exists_table_alb($table)

{
	global $wpdb;
	$rows = $wpdb->get_row('show tables like "'.$table.'"', ARRAY_N);

	return (count($rows)>0);
}
?>