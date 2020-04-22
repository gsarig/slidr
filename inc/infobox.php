<?php 
	/*
		The infobox content. You can override the output by redeclaring the function in your theme's functions.php like so:

			function slidr_custom_content( $link, $title, $excerpt, $a ) {
				echo "Your custom output here.";
			}

		For more flexibility, the gallery shortcode has a similar but separate function slidr_gallery_custom_content() which works exactly the same way.
	*/
	function slidr_infobox( $link = null, $title = null, $excerpt = null, $a = null ) {
		if ( ! function_exists( 'slidr_custom_content' ) ) {

			return include dirname(__FILE__) . '/infobox-content.php';

		} else {
			ob_start();
				slidr_custom_content( $link, $title, $excerpt, $a );
			return ob_get_clean();
		}
	}
	function slidr_gallery_infobox( $link = null, $title = null, $excerpt = null, $a = null ) {
		if ( ! function_exists( 'slidr_gallery_custom_content' ) ) {
			
			return include dirname(__FILE__) . '/infobox-content.php';

		} else {
			ob_start();
				slidr_gallery_custom_content( $link, $title, $excerpt, $a );
			return ob_get_clean();
		}
	}