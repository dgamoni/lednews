<?php

// API so you don't have to use "new"
function deco_tlc_transient( $key ) {
	$transient = new TLC_Transient( $key );

	return $transient;
}