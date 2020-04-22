<?php 
	/*
		Infobox content.
	*/
	$html5		= current_theme_supports( 'html5', 'gallery' );
	$aclass 	= !empty($a['link_class']) && isset($a['link_class']) ? 'class="'.$a['link_class'].'"' : '';
	$captiontag = $html5 && ( $a['thumb'] === 'yes' || slidr('g_shortcode') === 'yes' ) ? 'figcaption' : 'dd';
	$link 		= ( 'none' !== $a['link'] ) ? '<a href="' . $link . '" rel="bookmark"'. $aclass .'>'. $title . '</a>': $title;

	return '<'.$captiontag.' class="slidr-item-info">
				<div class="slidr-info-hover">
					<h2>
						' . $link . '
					</h2>' . $excerpt . 
				'</div>
			</'.$captiontag.'>';