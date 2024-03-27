<?php
add_action( 'wp_ajax_wp_automatic_reactivate_key', 'wp_automatic_reactivate_key_callback' );

function wp_automatic_reactivate_key_callback() {
 
	if(! isset($_POST['id'])  || ! isset($_POST['key'])){
		echo 'Not valid request';
		die();
	}
	
		$pid = $_POST['id'];
		$key = $_POST['key'];
		
		//deleting field 
		delete_post_meta($pid, $key);
		
		echo 'Keyword Reactivated successfully. You can run the campaign again';
		
 die();
}

add_action( 'wp_ajax_wp_automatic_yt_playlists', 'wp_automatic_yt_playlists_callback' );

function wp_automatic_yt_playlists_callback() {
 
	//return ini
	$ret= array();
	$ret['status'] = 'error';
	$ret['message'] = '';
	$ret['data'] = '';
	
	//user channerl
	$user = trim($_POST['user']);
	
	//if empty user
	if(trim($user) == ''){
		$ret['message'] = 'empty user';
		print_r(json_encode($ret));
		die();
	}
	
	
	$start=1;
	$playlists=array();
	$playlist = array();
	
	for($i = 0;$i<5;$i++){ 
	
		//get user playlists feed page like: https://gdata.youtube.com/feeds/api/users/NAHBTV/playlists
		$wp_automatic_yt_tocken = get_option('wp_automatic_yt_tocken','');
		$url="https://www.googleapis.com/youtube/v3/search?part=snippet&type=playlist&key=".trim($wp_automatic_yt_tocken)."&maxResults=50&channelId=".trim($user);
		 
		//page token
		if(isset($json_result->nextPageToken)){
			$url.= '&pageToken='.$json_result->nextPageToken;
		}
		
		//curl ini
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT,20);
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.bing.com/');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8');
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Good leeway for redirections.
		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Many login forms redirect at least once.
	
		//curl get
		$x='error';
	 	curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_URL, trim($url));
	 	$exec=curl_exec($ch);
		$x=curl_error($ch);
	 
		//if no response back
		if(trim($exec) == ''){
			$ret['message'] = 'Empty response from YT '.$x;
			print_r(json_encode($ret));
			die();
		}
		
		//extracting

		$json_result = json_decode($exec);
		
		 
		
		$items = $json_result->items;	
		
	 
		 
		$singlePlayCount = 0;
		foreach ($items as $entry){
		
			$playlist_id = $entry->id->playlistId;
			$playlist['id'] = $playlist_id;
			$playlist['title'] =$entry->snippet->title ;
		
			$playlists[] = $playlist;
			
			$singlePlayCount++;
		
		}
		
		 
		
		if( $singlePlayCount < 50 ){
			 
			break;
		}  
		
		$start = $start +50;
	}
	
	
	$ret['status'] = 'success';
	$ret['data'] = $playlists;
	
	
	
	//save list 
	update_post_meta($_POST['pid'], 'wp_automatic_yt_playlists', $playlists);
 	
	print_r(json_encode($ret));
	
	 
	die();
	
	
	
	
	
	
 die();
}