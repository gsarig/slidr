<?php
	/*
	Plugin Name: Slidr
	Plugin URI: http://wordpress.org/plugins/slidr/
	Description: A clean, simple, responsive and touch-friendly Carousel with no bells and whistles but plenty of flexibility.
	Version: 1.4.1
	Author: Giorgos Sarigiannidis
	Author URI: http://www.gsarigiannidis.gr
	Text Domain: slidr
	Domain Path: /languages
	*/

	define( 'SLIDR_VERSION', '1.4.1' );

	load_plugin_textdomain('slidr', false, basename( dirname( __FILE__ ) ) . '/languages' ); // Localize it

	if ( is_admin() ) // Display the plugin's options at the backend
		require_once dirname( __FILE__ ) . '/options.php';

	function slidr( $option ) { // enables us to reference any saved option we want via "echo slidr('option_id');".
		$options = get_option( 'slidr_options' );
		if ( isset( $options[$option] ) )
			return $options[$option];
		else
			return false;
	}

	function slidr_settings_link($links) { // Add settings link on plugin page
	  $settings_link = '<a href="tools.php?page=slidr-options">'. __('Settings', 'slidr').'</a>';
	  array_unshift($links, $settings_link);
	  return $links;
	}

	$plugin = plugin_basename(__FILE__);
	add_filter("plugin_action_links_$plugin", 'slidr_settings_link' );

	function slidr_plugin_scripts() {
		wp_enqueue_style( 'slidr-styles', plugins_url( '/css/slidr.css' , __FILE__ ) );
		wp_enqueue_script( 'slidr-script', plugins_url('/js/slidr.js', __FILE__ ), array('jquery'), SLIDR_VERSION, true );
	}
	add_action( 'wp_enqueue_scripts', 'slidr_plugin_scripts' );

	// The shortcode
	include_once dirname(__FILE__) . '/inc/shortcode.php';

	// Replace default WordPress gallery with Slidr
	include_once dirname(__FILE__) . '/inc/gallery-shortcode.php';

	// Shortcode's info box content
	include_once dirname(__FILE__) . '/inc/infobox.php';

	// Custom styles
	include_once dirname(__FILE__) . '/css/slidr-styles.php';

	// Conditional loading of scripts and styles
	include_once dirname(__FILE__) . '/inc/conditional.php';

	// Slidr loop
	include_once dirname(__FILE__) . '/inc/slidr-loop.php';
