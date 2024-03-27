<?php 

global $post;
global  $wpdb;
$prefix=$wpdb->prefix;
$post_id=$post->ID;

$query="SELECT * FROM {$prefix}automatic_log where action='Posted:$post_id' order by id DESC";
$rows=$wpdb->get_results($query);

$count=count($rows);
echo '<p>Total number of posts <strong>('.$count.')</strong> post.</p>'; 

foreach ($rows as $row){
	
	echo '<div class="posted_itm">'. str_replace('New post posted:','',$row->data) .'<br>on <small>'.$row->date .'</small><br></div>';
	
}

?>