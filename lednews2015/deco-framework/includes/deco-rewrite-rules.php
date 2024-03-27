<?php

function deco_create_new_url_querystring() {
	add_rewrite_rule(
		'diyalnist/([^/]+)/?$',
		'index.php?pagename=diyalnist&po_tipam=$matches[2]',
		'top'
	);

	add_rewrite_rule(
		'diyalnist/all-types/([^/]+)/?$',
		'index.php?pagename=diyalnist&po_tipam=all-types&po_temam=$matches[3]',
		'top'
	);

	add_rewrite_rule(
		'diyalnist/([^/]+)/([^/]+)/?$',
		'index.php?pagename=diyalnist&po_tipam=$matches[2]&po_temam=$matches[3]',
		'top'
	);

	add_rewrite_tag( '%po_tipam%', '([^/]*)' );
	add_rewrite_tag( '%po_temam%', '([^/]*)' );
}

add_action( 'init', 'deco_create_new_url_querystring' );