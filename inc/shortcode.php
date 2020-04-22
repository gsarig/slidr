<?php

	function slidr_shortcode( $atts ) {

		ob_start();

		include dirname(__FILE__) . '/attributes.php'; // Set the defaults and shortcode attributes
		include dirname(__FILE__) . '/markup.php'; // Main variables and HTML markup
		include_once dirname(__FILE__) . '/item_content.php'; // Get the content of each item

		if( $a['gallery'] !== false ) { // If we want to display specific images

			if( $a['gallery'] !== 'yes' && (empty($a['parent']) || !isset($a['parent'])) ) { // Specific images (by image IDs)
				$gallery 	= explode( ',', $a['gallery'] );
				$parent 	= false;
			} elseif( $a['gallery'] === 'yes' && !empty($a['parent']) && isset($a['parent']) ) { // Images attached to a specific post (by parent post ID)
				$gallery 	= $a['parent'];
				$parent 	= $gallery;
			} else { // Images of the post in which the shortcode is called
				$gallery 	= 'inherit';
				$parent 	= get_the_ID();
			}
			$thumb_ID 	= ( is_singular() && $a['gallery'] === 'yes' ) ? get_post_thumbnail_id() : false; // Featured image
			$parent 	= !empty( $a['parent'] ) && isset( $a['parent'] ) ? $a['parent'] : false;
			$args = array(
				'post_type' 			=> 'attachment',
				'posts_per_page' 	=> filter_var($a['number'], FILTER_SANITIZE_NUMBER_INT),
				'post_parent' 		=> $parent,
				'post__in' 				=> is_array( $gallery ) ? $gallery : false,
				'orderby' 				=> $gallery == 'inherit' ? 'date' : 'post__in',
				'exclude'					=> $thumb_ID,
			);
			$attachments = get_posts($args);
			if ( $attachments ) :
				echo $open_slidr;
				foreach ($attachments as $attachment) :
					$media_url 	= wp_get_attachment_image_src( $attachment->ID, 'original' );
					$img_link 	= $a['gallery_link'] === 'attachment' ? get_attachment_link( $attachment->ID ) : $media_url[0];

					$filename 	= pathinfo( get_attached_file( $attachment->ID ), PATHINFO_FILENAME );
					$get_title 	= apply_filters('the_title', $attachment->post_title);
					if( slidr('smart_title') === 'no' ) {
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
					$title 				= ( ($filename === $get_title) || empty($get_title) ) && !empty($get_excerpt) ? $get_excerpt : $empty_title;
					$excerpt			= ( $a['excerpt'] === 'yes' && ($title !== $get_excerpt) ) ? '<p>' . $get_excerpt . '</p>' : '';
					$content 			= wp_get_attachment_image( $attachment->ID, $size );
					$info_box 		= ( $a['info_box'] === 'yes'  && !empty($title) ) ? slidr_infobox( $img_link, $title, $excerpt, $a ) : '';
					echo $o_slidr_item;
					if($a['thumb'] === 'yes') {
						echo item_content( $img_link, $content, $a ) .
							$c_icontag;
					}
					echo $info_box .
						$c_slidr_item;
				endforeach;
				echo $close_slidr;
			endif;
		} else { // Else we get a custom query
			$sticky = ($a['sticky'] === 'yes') ? get_option( 'sticky_posts' ) : '';
			$args	= array(
				'post_type' 			=> $a['type'],
				'posts_per_page' 	=> filter_var($a['number'], FILTER_SANITIZE_NUMBER_INT),
				'post_parent' 		=> $a['parent'],
				'post__in'        => $sticky,
				'cat' 						=> $a['category'],
				'orderby' 				=> $a['orderby'],
				'order'						=> $a['order'],
			);
			$slidr_items = new WP_Query( $args );

			if ( $slidr_items->have_posts() ) :
				echo $open_slidr;
					while ( $slidr_items->have_posts() ) :
						$slidr_items->the_post();

						$slidr_excerpt	= $a['excerpt'] 	=== 'yes' ? '<p>' . get_the_excerpt() . '</p>' : '';
						$info_box 		= $a['info_box'] 	=== 'yes' ? slidr_infobox( get_the_permalink(), get_the_title(), $slidr_excerpt, $a ) : '';

						echo $o_slidr_item;
							if ( has_post_thumbnail() ) :
								if($a['thumb'] === 'yes') {
									$item_image = get_the_post_thumbnail( get_the_ID(), $size );
									echo item_content(get_the_permalink(), $item_image, $a );
								}
							endif;
						echo $c_icontag .
								$info_box .
							$c_slidr_item;
					endwhile;

					wp_reset_postdata();

				echo $close_slidr;
			endif;
		}

		return ob_get_clean();

	}
	add_shortcode( 'slidr', 'slidr_shortcode' );