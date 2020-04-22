<?php 
/* 
	A function with which you can use Slidr with your own custom loops.
	Check the documentation for more details.
*/

	function slidr_loop( $loop = null, $height = null, $class = null, $cycle = null, $loader = null, $nav = null, $larrow = null, $rarrow = null ) {

		$loop 	= !empty($loop) ? $loop() : '<div class="slidr-item">' . __( 'No content to show...', 'slidr' ) . '</div>';
		$height = !empty($height) && ctype_digit($height) ? $height : '300';
		$classs	= !empty($class) ? $class : 'default';
		$cycles = !empty($cycle) && $cycle !== false ? ' slidr-cycle' : '';
		$auto 	= ($cycle == 'auto' || ctype_digit($cycle)) && $cycle !== true ? ' slidr-autoscroll' : '';
		$speed 	= $cycle !== '4000' && ctype_digit($cycle) ? $cycle : '4000';
		$loadt 	= !empty($loader) && is_string($loader) ? $loader : __('Loading...', 'slidr');
		$loader = !empty($loader) && $loader !== false ? '<div class="slidr-loader"><div style="top: '. round($height / 2 - 25).'px;">' . $loadt . '</div></div>' : '';
		$nav 	= $nav === false ? ' slidr-nav-hidden' : '';
		$navs 	= $nav !== false && empty($class) ? ' style="top:' . round($height / 2 - 25) . 'px;"' : '';
		$left  	= !empty($larrow) && $larrow !== '&#8249;' ? $larrow : '&#8249;';
		$right 	= !empty($rarrow) && $rarrow !== '&#8250;' ? $rarrow : '&#8250;';

		return '<div class="slidr-container ' . $classs . $cycles . $auto . '" data-speed="' . $speed . '" style="height: ' . $height . 'px;">
				' . $loader . '
				<span class="slidr-nav-prev slidr-nav' . $nav . '"' . $navs . '>' . $left . '</span>
				<div class="slidr-items-container" style="height: '. round($height + 40) .'px;">
					<div class="slidr-items">
						' . $loop . '
					</div>
				</div>
				<span class="slidr-nav-next slidr-nav' . $nav . '"' . $navs . '>' . $right . '</span>
			</div>';
	}