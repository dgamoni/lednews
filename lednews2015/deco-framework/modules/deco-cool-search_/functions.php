<?php

function deco_form_search_ajax() {
	?>
	<div class="header-search-container">
		<h2>
			<!-- <div class="search-inp"> -->
			<span class="header-search-icon"></span>
			<input type="text" class="header-search-input" placeholder="я ищу">
            <!-- </div> -->
			<button type="button" onClick="dc_close_search()" class="pull-right dc_close">×</button>

		</h2>

		<div class="al-center">
        	<a class="btn-yellow header-search-all btn-cool-search hidden-xs hide informer-title" href="#">Все результаты</a>
        </div>

		<div class="header-search-results search-tiles-list">
		</div>

<!--         <div class="al-center">
        	<a class="btn-yellow header-search-all btn-cool-search hidden-xs hide" href="#">Все результаты</a>
        </div> -->

	</div>
	<?php

}

add_action( 'wp_footer', 'deco_form_search_ajax', 999 );