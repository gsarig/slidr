<?php
	/*
		Conditionaly load the plugin's scripts and styles
		For even more control, leave the options under "Performance" unchecked and override the function like so:
		
			function slidr_conditional() {
				if( YOUR_CONDITIONS ) {
					slidr_dequeue();
				}
			}
		
	*/
			
	function slidr_load() {

		function slidr_dequeue() {
			add_action( 'wp_print_scripts', 'dequeue_slidr_def_script', 15 );
			add_action( 'wp_print_styles', 'dequeue_slidr_def_styles', 15 );
			add_action( 'wp_print_styles', 'dequeue_slidr_custom_styles', 15 );
			add_action( 'wp_print_styles', 'dequeue_slidr_default_styles', 15 );
		}
			function dequeue_slidr_def_script() {
				wp_dequeue_script( 'slidr-script' );
			}
			function dequeue_slidr_def_styles() {
				wp_dequeue_style( 'slidr-styles' );
			}
			function dequeue_slidr_custom_styles() {
				wp_dequeue_style( 'slidr-custom-styles' );
			}
			function dequeue_slidr_default_styles() {
				wp_dequeue_style( 'slidr-default-styles' );
			}

		if ( ! function_exists( 'slidr_conditional' ) ) {

			if( slidr('is_front_page') == 1 || slidr('is_home') == 1 || slidr('is_single') == 1 || slidr('is_page') == 1 || slidr('is_archive') == 1 ) :

				$front_page = ( slidr('is_front_page') 	== 1 ) ? is_front_page() : '';
				$home 		= ( slidr('is_home') 		== 1 ) ? is_home() : '';
				$single 	= ( slidr('is_single') 		== 1 ) ? is_single() : '';
				$page 		= ( slidr('is_page') 		== 1 ) ? is_page() : '';
				$archive	= ( slidr('is_archive') 	== 1 ) ? is_archive() : '';

				if( slidr('cond_type' ) == 'exclude' ) {
					( $front_page || $home || $single || $page || $archive ) ? slidr_dequeue() : '';
				} else {
					( !$front_page && !$home && !$single && !$page && !$archive  ) ? slidr_dequeue() : '';
				}

			endif;

		} else {
			slidr_conditional();
		}

	}

	add_action( 'wp_enqueue_scripts', 'slidr_load' );