<?php


add_action('wp_enqueue_scripts', 'cool_search_stylesheets');
function cool_search_stylesheets()
{
    wp_enqueue_style("cool-search", COOL_SEARCH_URL . "/assets/css/cool-search.css", false, "1.0");
}


add_action('wp_head', 'cool_search_js');

function cool_search_js()
{
    wp_enqueue_script("cool-search-plugins", COOL_SEARCH_URL . "/assets/js/cool-search-plugins.js", array(), '', TRUE);
    wp_enqueue_script("cool-search", COOL_SEARCH_URL . "/assets/js/cool-search.js", array(), '', TRUE);

}


