<?php

function slidr_get_gallery_shortcode_callback($content) {
	$content = add_shortcode('gallery', 'slidr_gallery_shortcode');
	return $content;
}

if(!has_action('slidr_get_gallery_shortcode')) {
	if( slidr('g_shortcode') == 1 ) {
		add_shortcode('gallery', 'slidr_gallery_shortcode');
	}
}

function slidr_gallery_shortcode( $a ) {


	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $a['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $a['orderby'] ) ) {
			$a['orderby'] = 'post__in';
		}
		$a['include'] = $a['ids'];
	}

	/*
		Filter the default gallery shortcode output.
	 */
	$output = apply_filters( 'post_gallery', '', $a, $instance );
	if ( $output != '' ) {
		return $output;
	}

	$html5 = current_theme_supports( 'html5', 'gallery' );

	include dirname(__FILE__) . '/gallery-defaults.php'; // Get the defaults

	/*
		Gallery shortcode attributes
	*/
	$a = shortcode_atts( array(
		'height'		=> slidr('g_height'),
		'speed' 		=> slidr('g_speed'),
		'info_box' 		=> slidr('g_info_box'),
		'excerpt' 		=> slidr('g_excerpt'),
		'loader' 		=> slidr('g_loader'),
		'nav' 			=> slidr('g_nav'),
		'cycle' 		=> slidr('g_cycle'),
		'class' 		=> $g_class_def,
		'template' 		=> $g_tmpl_def,
		'thumb' 		=> 'yes',
		'order'      	=> 'ASC',
		'orderby'    	=> 'menu_order ID',
		'id'         	=> $post ? $post->ID : 0,
		'itemtag'   	=> $html5 ? 'figure'     : 'dl',
		'icontag'    	=> $html5 ? 'div'        : 'dt',
		'captiontag' 	=> $html5 ? 'figcaption' : 'dd',
		'columns'    	=> 1,
		'size'       	=> slidr('g_size'),
		'include'    	=> '',
		'exclude'    	=> '',
		'link'       	=> '',
		'link_class'	=> $g_aclass_def,
		'img_link' 		=> slidr('g_img_link')
	), $a, 'gallery' );

	include dirname(__FILE__) . '/markup.php'; // Main variables and HTML markup


	$id = intval( $a['id'] );

	if ( ! empty( $a['include'] ) ) {
		$_attachments = get_posts( array( 'include' => $a['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $a['order'], 'orderby' => $a['orderby'] ) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $a['exclude'] ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $a['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $a['order'], 'orderby' => $a['orderby'] ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $a['order'], 'orderby' => $a['orderby'] ) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $a['size'], true ) . "\n";
		}
		return $output;
	}


	$output = $open_slidr;

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {

		$media_url 	= wp_get_attachment_image_src( $attachment->ID, 'original' );
		$noLinkImg 	= wp_get_attachment_image( $id, $a['size'], false );

		if ( ! empty( $a['link'] ) && 'file' === $a['link'] ) {
			$image_output 	= ( $a['img_link'] !== 'no' ) ? wp_get_attachment_link( $id, $a['size'], false, false, false ) : $noLinkImg;
			$img_link 		= $media_url[0];
		} elseif ( ! empty( $a['link'] ) && 'none' === $a['link'] ) {
			$image_output 	= $noLinkImg;
			$img_link 		= '';
		} else {
			$image_output 	= ( $a['img_link'] !== 'no' ) ? wp_get_attachment_link( $id, $a['size'], true, false, false ) : $noLinkImg;
			$img_link 		= get_attachment_link( $attachment->ID );
		}
		$image_meta = wp_get_attachment_metadata( $id );
		
		$filename 	= pathinfo( get_attached_file( $id ), PATHINFO_FILENAME );
		$get_title 	= apply_filters('the_title', $attachment->post_title);
		if( slidr('g_title') === 'no' ) {
			if( !empty($get_title) ) {
				$empty_title = $get_title;	
			} else {
				$empty_title = '';
			}	
		} else {
			if( !empty($get_title) && ($filename !== $get_title) ) {
				$empty_title = $get_title;
			} else {
				$empty_title = '';
			}
		}
		$get_excerpt 	= $attachment->post_excerpt;
		$title 			= ( ($filename === $get_title) || empty($get_title) ) && !empty($get_excerpt) ? $get_excerpt : $empty_title;
		$excerpt		= ( $a['excerpt'] === 'yes' && ($title !== $get_excerpt) ) ? '<p>' . $get_excerpt . '</p>' : ''; 
		$info_box 		= ( $a['info_box'] === 'yes' && !empty($title) ) ? slidr_gallery_infobox( $img_link, $title, $excerpt, $a ) : '';

		$output .= $o_slidr_item . 
						$image_output . 
					$c_icontag . 
					$info_box . 
				$c_slidr_item;
	}

	$output .= $close_slidr;

	return $output;
}