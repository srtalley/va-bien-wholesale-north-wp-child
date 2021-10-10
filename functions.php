<?php
/**
 * luxi functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package luxi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'THEME_DIR', __DIR__ );

require_once( THEME_DIR . '/lib/Theme.php' );
require_once( THEME_DIR . '/lib/WooExtensions.php' );
require_once( THEME_DIR . '/lib/WholeSaleExtensions.php' );


/*-----------------------------------------------------------------------------------*/
/* Quick Print Array functions for debugging
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'PrintArray' ) ) {
	function PrintArray( $array ) {

		if (WP_DEBUG) {
			echo "<pre>";
			print_r( $array );
			echo "</pre>";
		}

	}
}

// NOTHING CHOULD BE ADDED TO THE FUNCTIONS FILE - PLEASE PLACE IT IN THE CORRECT FILE ABOVE AS A CLASS ENTRY

