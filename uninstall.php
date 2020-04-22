<?php 

/**
 * Uninstall Slidr
 * 
 * @package Slash Admin
 * @since 1.0
 */

	// If uninstall is not called from WordPress, exit
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit();
	}

	$option_name = 'slidr_options';

	delete_option( $option_name );