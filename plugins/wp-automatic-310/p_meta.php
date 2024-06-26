<?php
 
//add novalidate to the form 
add_action('post_edit_form_tag', 'wp_automatic_post_edit_form_tag');
function wp_automatic_post_edit_form_tag() {
	global $post_type;

	if($post_type == 'wp_automatic'){
		echo ' novalidate="novalidate" ';
	}
	
	
}

/* Add a new meta box to the admin menu. */
	add_action( 'admin_menu', 'wp_automatic_create_meta_box' );
	
	function wp_automatic_create_meta_box() {
		add_meta_box( 'status-meta-boxes', 'Run & Status', 'wp_automatic_status_meta_boxes', 'wp_automatic', 'normal', 'high' );
		add_meta_box( 'page-meta-boxes', 'Campaign options', 'wp_automatic_meta_boxes', 'wp_automatic', 'normal', 'high' );
		add_meta_box( 'posted-meta-box', 'Posted posts', 'wp_automatic_posted_meta_boxe', 'wp_automatic', 'side', 'low' );
	}
	
/* Saves the meta box data. */
	add_action( 'save_post', 'wp_automatic_save_meta_data' );

function wp_automatic_posted_meta_boxe(){
	require_once('posted_metabox.php');
}
 
function wp_automatic_meta_boxes() {
	require_once('p_metabox.php');
}

function wp_automatic_status_meta_boxes(){
	require_once 'status_metabox.php';
} 
 
 
function wp_automatic_save_meta_data( $post_id ) {
 
global $wpdb;
$prefix=$wpdb->prefix ;

$post_type = get_post_type( $post_id );

if($post_type != 'wp_automatic') return false;


 
@$camp_post_every=$_POST['camp_post_every'];
@$camp_keywords=addslashes($_POST['camp_keywords']);
@$camp_cb_category=addslashes($_POST['camp_cb_category']);
@$camp_replace_link=addslashes($_POST['camp_replace_link']);
@$camp_add_star=addslashes($_POST['camp_add_star']);
@$camp_post_title=addslashes($_POST['camp_post_title']);
@$camp_post_content=addslashes($_POST['camp_post_content']);
@$camp_post_category=($_POST['camp_post_category']);


if(is_array($camp_post_category)){
	$camp_post_category = implode(',', $camp_post_category);
}


@$camp_post_status=addslashes($_POST['camp_post_status']);
@$post_title=addslashes($_POST['post_title']);
@$camp_amazon_cat=addslashes($_POST['camp_amazon_cat']);
@$camp_amazon_region=addslashes($_POST['camp_amazon_region']);
@$camp_options=serialize($_POST['camp_options']);
@$feeds=addslashes($_POST['feeds']);
@$camp_type=$_POST['camp_type'];
@$camp_search_order=$_POST['camp_search_order'];
@$camp_youtube_cat=$_POST['camp_youtube_cat'];
@$camp_youtube_order=$_POST['camp_youtube_order'];
@$camp_post_author=$_POST['camp_post_author'];
@$camp_post_type=$_POST['camp_post_type'];
@$camp_post_exact=$_POST['camp_post_exact'];
@$camp_post_execlude=$_POST['camp_post_execlude'];
@$camp_yt_user=$_POST['camp_yt_user'];
@$camp_translate_from=$_POST['camp_translate_from'];
@$camp_translate_to=$_POST['camp_translate_to'];
@$camp_translate_to_2=$_POST['camp_translate_to_2'];
@$camp_post_custom_k=serialize($_POST['camp_post_custom_k']);
@$camp_post_custom_v=serialize($_POST['camp_post_custom_v']);



if ( (trim($camp_keywords) == '' && !( $camp_type =='Feeds' ||  $camp_type =='Spintax' ||  $camp_type =='Facebook' ) )  || ($camp_type == 'Feeds' && trim($feeds) == '')  ) return false;


// adding keywords to the table
$keywords=explode(',',$camp_keywords);




	//loping keywords
	foreach($keywords as $keyword){

	
		
		$keyword=addslashes(trim($keyword));
		
		
		
		if( trim($keyword) != ''){
			$query="INSERT IGNORE  INTO {$prefix}automatic_keywords(keyword_name,keyword_camp) values('$keyword','$post_id')";
		

			
			@$wpdb->query($query);
			
			
			$query="INSERT IGNORE INTO {$prefix}automatic_articles_keys(keyword,camp_id) values('$keyword','$post_id')";
			@$wpdb->query($query);
		}
		
	
		
	}

	


//deleting current record for campaign
$query="delete from {$prefix}automatic_camps where camp_id = '$post_id'";
$wpdb->query($query); 
 
//building camp_general array
$camp_general=array(); 
foreach($_POST as $key=>$value){
	
	if(stristr($key,'cg')){
		 
		$camp_general[$key]= $value ;
	}
	
}

$camp_general['cg_eb_full_img_t'] = htmlentities($camp_general['cg_eb_full_img_t']);
 
$camp_general = base64_encode(serialize($camp_general));



// inserting new record for the campaign
$query="INSERT INTO  {$prefix}automatic_camps  (camp_general, camp_post_custom_k, camp_post_custom_v, camp_translate_from, camp_translate_to , camp_translate_to_2 , camp_yt_user,camp_post_exact,camp_post_execlude,camp_post_type,camp_post_author,camp_youtube_cat,camp_youtube_order,camp_search_order,camp_amazon_cat, camp_amazon_region ,camp_type, camp_id,camp_post_every,camp_options ,camp_keywords,camp_cb_category,camp_replace_link,camp_add_star,camp_post_title,camp_post_content,camp_post_category,camp_post_status,camp_name ,feeds)VALUES ( '$camp_general', '$camp_post_custom_k','$camp_post_custom_v', '$camp_translate_from', '$camp_translate_to', '$camp_translate_to_2', '$camp_yt_user',  '$camp_post_exact','$camp_post_execlude', '$camp_post_type', '$camp_post_author', '$camp_youtube_cat','$camp_youtube_order','$camp_search_order','$camp_amazon_cat','$camp_amazon_region','$camp_type','$post_id','$camp_post_every','$camp_options','$camp_keywords','$camp_cb_category','$camp_replace_link','$camp_add_star','$camp_post_title','$camp_post_content','$camp_post_category','$camp_post_status','$post_title','$feeds')";
 

$wpdb->query($query);

// adding new record foreach new feed
$feeds=$_POST['feeds'];
$feeds=explode("\n",$feeds);

foreach($feeds as $feed){
 
 	if( trim($feed) != ''){
			//appending 
			$feed=trim($feed);
			$feed=addslashes($feed);
			$query="INSERT INTO {$prefix}automatic_feeds_list(camp_id,feed) VALUES('$post_id','$feed')";
			$wpdb->query($query);

	}
}
 

}//end function
?>
