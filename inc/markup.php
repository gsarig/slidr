<?php 

	/* 
		Main variables.
	*/
	$size 			= $a['size'];
	$default_style 	= slidr('style') === 'default' && ( empty($a['template']) || !isset($a['template']) || $a['template'] !== 'no' )	? ' default' : ''; 
	$nav_prev 		= slidr('nav_prev') 	!== '&#8249;' 	? slidr('nav_prev') : '&#8249;'; 
	$nav_next 		= slidr('nav_next') 	!== '&#8250;'	? slidr('nav_next') : '&#8250;'; 
	$ie_fix 		= $a['info_box'] 		=== 'yes'		? '" aria-haspopup="true' : ''; 
	$nav_hidden 	= $a['nav'] 			=== 'hide'		? ' slidr-nav-hidden' : '';
	$thumb 			= ( $a['thumb'] === 'yes' || slidr('g_shortcode') == 1 )	? $ie_fix : ' no-thumb';

	$speed 			= filter_var($a['speed'], FILTER_SANITIZE_NUMBER_INT);
	if( $a['cycle'] !== 'no' && $a['cycle'] !== false ) {
		if( $a['cycle'] === 'auto' ) {
			$cycle 			= ' slidr-cycle slidr-autoscroll';
			$scroll_speed 	= ( $speed !== '4000' && $speed !== '' ) ? ' data-speed="' . $speed . '" ' : ''; 
		} else {
			$cycle 			= ' slidr-cycle';
			$scroll_speed 	= '';
		}	
	} else {
		$cycle 			= '';
		$scroll_speed 	= '';
	}

	/*
		Custom size per Carousel.
	*/
	$height = filter_var($a['height'], FILTER_SANITIZE_NUMBER_INT);
	if( slidr('height') !== $height && ( $a['template'] !== 'no' ) ) {
		$container_height 	= intval( $height ) + 40;
		$nav_position 		= intval( $height ) / 2 - 25;

		$car_style 	= ' style="height:' . $height . 'px;"';
		$con_style 	= ' style="height:' . $container_height . 'px;"';
		$nav_style 	= ' style="top:' . $nav_position . 'px;"';
		$loader		= $a['loader']	=== 'yes' ? '<div class="slidr-loader"><div' . $nav_style . '>'. __("Loading...", "slidr" ) . '</div></div>' : ''; 

		if( $a['thumb'] !== 'yes' && ( (empty($a['template']) || !isset($a['template'])) ) ) {
			$item_style 	= 'style="width:'.$height.'px; height:'.$height.'px;"';
			$img_max_height = '';
		} elseif( $a['thumb'] !== 'yes' && $a['template'] === 'no' ) {
			$item_style = '';
			$img_max_height = '';
		} else {
			$item_style 	= ' style="max-height:' . $height . 'px;"';
			$img_max_height =  ' style="height:' . $height . 'px;"';
		}
	} else {
		$car_style 		= '';
		$con_style 		= '';
		$nav_style 		= '';
		$item_style 	= '';
		$img_max_height = '';
		$loader	= $a['loader']	=== 'yes' ? '<div class="slidr-loader"><div>'. __("Loading...", "slidr" ) . '</div></div>' : ''; 

	}

	/*
		The HTML Markup.
	*/
	$html5		= current_theme_supports( 'html5', 'gallery' );
	$itemtag 	= $html5 && ( $a['thumb'] === 'yes' || slidr('g_shortcode') === 'yes' ) ? 'figure' 	: 'dl';
	$icontag 	= $html5 && ( $a['thumb'] === 'yes' || slidr('g_shortcode') === 'yes' ) ? 'div' 	: 'dt';

	$open_slidr 	= '<div class="slidr-container ' . $a['class'] . $default_style . $cycle . '"' . $scroll_speed . $car_style . '>'
						. $loader .
						'<span class="slidr-nav-prev slidr-nav' . $nav_hidden . '"' . $nav_style . '>
							' . $nav_prev . '
						</span>
						<div class="slidr-items-container"' . $con_style . '>
								<div class="slidr-items">';
	$o_slidr_item 	= '<'.$itemtag.' class="slidr-item' . $thumb . '"' . $item_style . '>
						<'.$icontag. $img_max_height . '>';
	$c_icontag 		= '</'.$icontag.'>';
	$c_slidr_item 	= '</'.$itemtag.'>';
	$close_slidr 	= '</div>
						</div>
						<span class="slidr-nav-next slidr-nav' . $nav_hidden . '"' . $nav_style . '>
							' . $nav_next . '
						</span>
					</div>';