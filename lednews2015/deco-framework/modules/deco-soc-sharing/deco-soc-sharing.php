<?php

function deco_top_soc_sharing( $id, $type = '', $counters = array() ) {
//	$deco_counters     = deco_get_soc_counters();
	$deco_sharing_data = deco_get_data_for_sharing( $id );
	?>
	<ul class="float-socials">
		<li class="is-fb">
			<a href="#" class="is-fb" onclick="deco_soc_sharing_window('http://www.facebook.com/sharer/sharer.php?s=100&amp;p[title]=<?php echo $deco_sharing_data['title']; ?>&amp;p[summary]=<?php echo $deco_sharing_data['content']; ?>&amp;p[url]=<?php echo $deco_sharing_data['link']; ?>&amp;p[images][0]=<?php echo $deco_sharing_data['image']; ?>','<?php echo $deco_text_button; ?>'); return false;"><?php echo isset( $counters['fb'] ) ? $counters['fb'] : '0'; ?></a>
		</li>
		<li class="is-vk">
			<a href="#" class="is-vk" onclick="deco_soc_sharing_window('http://vkontakte.ru/share.php?url=<?php echo $deco_sharing_data['link']; ?>','<?php echo $deco_text_button; ?>'); return false;"><?php echo isset( $counters['vk'] ) ? $counters['vk'] : '0'; ?></a>
		</li>
		<li class="is-tw">
			<a href="#" class="is-tw" onclick="deco_soc_sharing_window('https://twitter.com/intent/tweet?text=<?php echo $deco_sharing_data['title']; ?>&amp;url=<?php echo $deco_sharing_data['link']; ?>','<?php echo $deco_text_button; ?>'); return false;"><?php echo isset( $counters['tw'] ) ? $counters['tw'] : '0'; ?></a>
		</li>
		<li class="is-ln">
			<a href="#" class="is-ln" onclick="deco_soc_sharing_window('https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $deco_sharing_data['link']; ?>&title=<?php echo $deco_sharing_data['title']; ?>&summary=<?php echo $deco_sharing_data['content']; ?>&source=<?php echo $deco_sharing_data['image']; ?>','<?php echo $deco_text_button; ?>'); return false;"><?php echo isset( $counters['ln'] ) ? $counters['ln'] : '0'; ?></a>

		</li>
	</ul>
	<?php
}

function deco_get_data_for_sharing( $id ) {

	$title   = urlencode( html_entity_decode( get_the_title( $id ) ) );
	$content = urlencode( html_entity_decode( get_the_excerpt( $id ) ) );
	$image   = wp_get_attachment_url( get_post_thumbnail_id( $id ) );
	$image   = deco_bfi_thumb( $image, array( 'width' => 317, 'crop' => true ) );
	$link    = urlencode( get_permalink( $id ) );


	$result = array(
		'title'   => $title,
		'content' => $content,
		'image'   => $image,
		'link'    => urlencode( get_permalink( $post->ID ) )
	);

	return $result;
}

function deco_get_soc_counters( $id, $type = 'post', $tax = '' ) {
	global $wpdb;


	if ( empty( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
	}

	$current_blog_id = get_current_blog_id();
	$stats           = $wpdb->get_row( "SELECT * FROM $wpdb->de_statistics WHERE post_id = $post_id and blog_id = $current_blog_id" );
	$result          = array(
		'fb' => $stats->fb_counts,
		'gp' => $stats->gplus_counts,
		'tw' => $stats->tw_counts,
		'vk' => $stats->tw_counts
	);

	return $result;
}

