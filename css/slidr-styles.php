<?php

  function slidr_templates() {
    if( slidr('style') === 'default' ) {
      wp_enqueue_style( 'slidr-default-styles', plugins_url( '/default.css' , __FILE__ ) ); 
    }
  }
  add_action( 'wp_enqueue_scripts', 'slidr_templates' );


  function slidr_custom_styles() { 
    wp_enqueue_style( 'slidr-custom-styles', plugins_url( '/slidr.css' , __FILE__ ) );

    $def_height       = '150';
    $carousel_height  = slidr('height') != 0 ? slidr('height') : $def_height;
    $item_max_height  = slidr('style') === 'default' ? $carousel_height -10 : $carousel_height;
    $container_height = $carousel_height + 40;
    $nav_position     = $carousel_height / 2 - 25;
    $custom_styles    = slidr('custom_css');

    $custom_css = "
      .slidr-container,
      .slidr-container.default {
        height: {$carousel_height}px;
      }
      .slidr-items-container,
      .slidr-container.default .slidr-items-container {
        height: {$container_height}px;
      }
      .slidr-item,
      .slidr-container.default .slidr-item {
        max-height: {$item_max_height}px;
      }
      .slidr-item > div,
      .slidr-container.default .slidr-item > div {
        height: {$item_max_height}px;
      }
      .slidr-item.no-thumb,
      .slidr-container.default .slidr-item.no-thumb {
        width: {$carousel_height}px;
        height: {$carousel_height}px;
      }
      .slidr-nav,
      .slidr-container.default .slidr-nav,
      .slidr-loader > div {
        top: {$nav_position}px;
      }
      {$custom_styles}
      ";
      if( ( slidr('height') == $def_height || slidr('height') == 0 ) && slidr('custom_css') == '' && slidr('nav_buttons') != 0 ) {
        return;
      } else {
        wp_add_inline_style( 'slidr-custom-styles', $custom_css );
      }
    
  }
  add_action( 'wp_enqueue_scripts', 'slidr_custom_styles' );

?>