<?php 
	/*
		Pass defaults for [gallery] shortcode directly in your theme.
		That way you don't need to instruct your users to go and set 
		the appropriate values at the Plugin's options or mess with 
		the shortcode. You can use it like this:

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'slidr/slidr.php' ) ) {
			function slidr_gallery_defaults() {
				$default['enable'] 		= 'yes'; 	// Enable or disable Slidr for Gallery option by default
				$default['height'] 		= '500'; 	// The gallery height
				$default['size'] 		= 'medium'; // Thumbnail size
				$default['speed'] 		= '4000'; 	// Carousel speed if "cycle" mode is set to "auto"
				$default['info_box'] 	= 'yes'; 	// Show or hide infobox
				$default['excerpt'] 	= 'no'; 	// Show or hide excerpt
				$default['loader'] 		= 'yes'; 	// Use loading animation
				$default['nav'] 		= 'show'; 	// Show or hide navigation buttons 
				$default['cycle'] 		= 'no'; 	// Enable or disable "cycle" mode (options are "yes", "no" and "auto")
				$default['template'] 	= 'no'; 	// Disable the default template
				$default['class']		= 'myclass' // Pass your class to the container
				$default['link_class'] 	= 'myclass' // Pass class to the link
				$default['img_link'] 	= 'no' 		// Enable or disable image link
				
				return $default;
			}
		}

	*/

	// Set the defaults
	$o_enabled_def 	= false;
	$o_height_def 	= '150';
	$o_size_def		= 'thumbnail';
	$o_speed_def	= '4000';
	$o_info_box_def = 'yes';
	$o_excerpt_def 	= 'no';
	$o_loader_def 	= 'yes';
	$o_nav_def 		= false;
	$o_cycle_def 	= false;
	$o_class_def 	= '';
	$o_tmpl_def		= false;
	$o_aclass_def 	= false;
	$o_img_link_def	= 'yes';

	if ( ! function_exists( 'slidr_gallery_defaults' ) ) {
		//Get the defaults from the plugin's options
		$g_enabled_def 	= slidr('g_shortcode') === 'yes' ? true : $o_enabled_def;  
		$g_height_def 	= slidr('g_height') !== '150' && !slidr('g_height') ? filter_var(slidr('g_height'), FILTER_SANITIZE_NUMBER_INT) : $o_height_def;
		$g_size_def		= $o_size_def;
		$g_speed_def	= slidr('g_speed') !== '4000' && !slidr('g_speed') ? filter_var(slidr('g_speed'), FILTER_SANITIZE_NUMBER_INT) : $o_speed_def;
		$g_info_box_def = slidr('g_info_box') !== 'yes' ? slidr('g_info_box') : $o_info_box_def;
		$g_excerpt_def 	= slidr('g_excerpt') !== 'no' ? slidr('g_excerpt') : $o_excerpt_def;
		$g_loader_def 	= slidr('g_loader') == 0 ? 'no' : $o_loader_def;
		$g_nav_def 		= slidr('g_nav') == 0 ? 'hide' : $o_nav_def;
		$g_cycle_def 	= slidr('g_cycle') !== 'no' ? slidr('g_cycle') : $o_cycle_def;
		$g_class_def 	= $o_class_def;
		$g_tmpl_def 	= $o_tmpl_def;
		$g_aclass_def 	= $o_aclass_def;
		$g_img_link_def	= $o_img_link_def;

	} else {
		$default = slidr_gallery_defaults();
		// Get the defaults from slidr_gallery_defaults() function if it is set in the theme's functions.php
		$g_enabled_def 	= !empty( $default['enable'] ) && isset( $default['enable'] ) && $default['enable'] === 'yes' ? true : $o_enabled_def;
		$g_height_def 	= !empty( $default['height'] ) && isset( $default['height'] ) ? filter_var($default['height'], FILTER_SANITIZE_NUMBER_INT) : $o_height_def;
		$g_size_def		= !empty( $default['size'] ) && isset( $default['size'] ) ? $default['size'] : $o_size_def;
		$g_speed_def	= !empty( $default['speed'] ) && isset( $default['speed'] ) ? filter_var($default['speed'], FILTER_SANITIZE_NUMBER_INT) : $o_speed_def;
		$g_info_box_def = !empty( $default['info_box'] ) && isset( $default['info_box'] ) ? $default['info_box'] : $o_info_box_def;
		$g_excerpt_def 	= !empty( $default['excerpt'] ) && isset( $default['excerpt'] ) ? $default['excerpt'] : $o_excerpt_def;
		$g_loader_def 	= !empty( $default['loader'] ) && isset( $default['loader'] ) ? $default['loader'] : $o_loader_def;
		$g_nav_def 		= !empty( $default['nav'] ) && isset( $default['nav'] ) ? $default['nav'] : $o_nav_def;
		$g_cycle_def 	= !empty( $default['cycle'] ) && isset( $default['cycle'] ) ? $default['cycle'] : $o_cycle_def;
		$g_class_def 	= !empty( $default['class'] ) && isset( $default['class'] ) ? $default['class'] : $o_class_def;
		$g_tmpl_def 	= !empty( $default['template'] ) && isset( $default['template'] ) && $default['template'] === 'no' ? $default['template'] : $o_tmpl_def;
		$g_aclass_def 	= !empty( $default['link_class'] ) && isset( $default['link_class'] ) ? $default['link_class'] : $o_aclass_def;
		$g_img_link_def = !empty( $default['img_link'] ) && isset( $default['img_link'] ) ? $default['img_link'] : $o_img_link_def; 
	}