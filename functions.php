<?php

/**
 * boolval() wasn't introduced until PHP 5.5
 *
 * We need to make sure that the function exists for previous version of PHP.
 */
if ( ! function_exists( 'boolval' ) ) {
	function boolval( $value ) {
		return (bool) $value;
	}
}