jQuery(document).ready(function($) {

  'use strict';

  $('.slidr-container').each(function() {

    var totalWidth   = 0,
        $slidr       = $(this),
        $carousel    = $slidr.find('.slidr-items-container'),
        $container   = $carousel.find('.slidr-items'),
        $item        = $container.find('.slidr-item'),
        $nav         = $(this).find('.slidr-nav'),
        $navPrev     = $(this).find('.slidr-nav-prev'),
        $navNext     = $(this).find('.slidr-nav-next'),
        carHeight    = parseFloat( $carousel.css('height') );

    /* = Prepare the Carousel container
    ----------------------------------------------- */
    function containerWidth() { // Set container width
      $(window).load(function() { // We run it on window.load() because we need all our images loaded before starting calculate the items' widths.
        $item.each(function() {
          var img   = $(this).find('img'),
              width = parseFloat( img.css('width') );
          parseFloat( $(this).css('width', width) ); // Each image container should have the same width as the image (otherwise long titles might disrupt it).
          totalWidth += $(this).outerWidth(true);
        });
        parseFloat( $container.css('width', totalWidth) );

        missingPixel();

        if( $slidr.find('div').first().hasClass('slidr-loader') ) { // If loader is enabled,
          $('.slidr-loader').delay(200).fadeOut(); // hide it when all images are loaded.
        }
        if( parseFloat( $slidr.css('width') ) >= $container.outerWidth() ) { // If the items width is shorter that the carousel width, hide the next button
          $navNext.hide();
        }
      }); // $(window).load(); 
    }

    /* = Fix missing pixels
    ----------------------------------------------- */
    function missingPixel() {
      if( parseFloat( $container.css('height') ) > carHeight ) { // On some occassions the carousel's width is one pixel short.
        $container.css('width', parseFloat( $container.css('width') ) + 1); // When that happens, add the missing pixel.
      }
    }

    /* = Slide items
    ----------------------------------------------- */
    function slideItems(direction) {

      var classToRemove = (direction === 'prev') ? 'slidr-click-next' : 'slidr-click-prev',
          classToAdd    = (direction === 'prev') ? 'slidr-click-prev' : 'slidr-click-next',
          condition     = (direction === 'prev') ? ($carousel.scrollLeft() === 0) : ($carousel.scrollLeft() !== 0),
          navDir        = (direction === 'prev') ? '-=' : '+=';

      $container.removeClass( classToRemove ).addClass( classToAdd );
      if( condition ) {
        slideCarousel();
      }
    
      var GetScrollDist = $item.filter( function(index) {
          var position = $(this).position();
          return position.left === 0;
        }),
        scrollFromL = (direction === 'prev') ? GetScrollDist.prev().outerWidth(true) : GetScrollDist.outerWidth(true),
        scrollDist = GetScrollDist.outerWidth(true) === null ? $item.last().outerWidth(true) : scrollFromL;
        $carousel.animate({ 'scrollLeft': navDir+scrollDist });
    }

    /* = Autoscroll
    ----------------------------------------------- */
    function autoScroll() {
      if( $slidr.hasClass('slidr-autoscroll') ) {
        var autoSlide = (function() { slideItems(); }),
            delay     = $slidr.data('speed') !== undefined ? $slidr.data('speed') : 4000,
            timer     = setInterval(autoSlide, delay);
        $slidr.on('mouseover', function(){
          clearInterval(timer);
        }).on('mouseout', function(){
          timer = setInterval(autoSlide, delay);
        });
      }    
    } // autoScroll()

    /* = Cycle through items
    ----------------------------------------------- */
    function slideCarousel() {
      var carouselWidth   = $carousel.outerWidth(),
          containerWidth  = $container.outerWidth(),
          grabNum         = ( ($carousel.scrollLeft() + carouselWidth) >= (containerWidth - 1) ) ? 0 : -1,
          $item           = $container.find('.slidr-item'),
          grabNext        = $item.eq(grabNum), // For multiple: item.filter(':eq(0), :eq(1)')
          $setFirst       = $item.first(),
          $setLast        = $item.last(),
          nextWidth       = grabNext.outerWidth(),
          setNextImg      = function(){
            grabNext.find('img').css({'min-width': nextWidth + 'px'});
          };
      if( $slidr.hasClass('slidr-cycle') ) { // Check if cycle items is enabled

        if( ($carousel.scrollLeft() + carouselWidth) >= (containerWidth - 1) ) { // If we are at the end (-1 goes for Chrome which tends to miss a pixel)
          setNextImg();
          grabNext.css({'width': '0'}).insertAfter($setLast).animate({'width': nextWidth + 'px'});
          missingPixel();
        }
        if( $carousel.scrollLeft() === 0 ) { // If we are at the beginning 
          setNextImg();
          grabNext.css({'width': '0'}).insertBefore($setFirst).animate({'width': nextWidth + 'px'});
        }
      } else { // If cycle items isn't enabled...
        if ( ($carousel.scrollLeft() + carouselWidth) >= (containerWidth - 1) ) { // ...and if we are at the end
          $navNext.hide(); // hide the "next" button
        } else if ( $carousel.scrollLeft() < 10 ) { // If we are approaching at the beginning...
          $navPrev.hide(); // ...hide the "previous" button
        } else { // else show the nav buttons.
          $nav.fadeIn();
        }
      }
    } // slideCarousel() 

    /* = Set the interactions
    ----------------------------------------------- */
    function interactions() { // Set the interactions on click of a nav button and on scroll (or swipe)
      $navNext.on('click', function() { // Click right button
        slideItems();
      });
      $navPrev.on('click', function() { // Click left button
        slideItems('prev');
      });

      if( ! $slidr.hasClass('slidr-cycle') ) { // If cycle items isn't enabled...
        $carousel.on('scroll', function() { // Scroll (swipe on touch devices)
          slideCarousel();
        });
        if ( $carousel.outerWidth() > $container.outerWidth() ) { // ...and if the carousel width is bigger than the items' container,
          $nav.hide(); // hide nav buttons
        }
        if ( $carousel.scrollLeft() === 0  ) { // Also, if we are at the beginnining, 
          $navPrev.hide(); // hide the "previous" button.
        }
      }
    } // interactions();

    /* = Touch events
    ----------------------------------------------- */
    function swipeToCycle() { // When at the carousel's start or end, cycle through items on swipe
      var swipeStart  = window.navigator.pointerEnabled ? 'pointerdown' : 'touchstart',
          swipeEnd    = window.navigator.pointerEnabled ? 'pointerup'   : 'touchend',
          touchStart;
      $carousel.on( swipeStart, function (e) { 
        touchStart    = e.pageX || e.originalEvent.touches[0].pageX;
      }).on( swipeEnd, function (e) { 
        var touchEnd  = e.pageX || e.originalEvent.changedTouches[0].pageX,
            carW      = $carousel.outerWidth(),
            conW      = $container.outerWidth(),
            noNext    = window.navigator.pointerEnabled ? true : $carousel.scrollLeft() + carW >= (conW - 1),
            noPrev    = window.navigator.pointerEnabled ? true : $carousel.scrollLeft() === 0;
        if( touchStart > touchEnd && noNext ) {
          slideItems();
        } else if( touchStart < touchEnd && noPrev ) {
          slideItems('prev');
        }
      });
    } // swipeToCycle();

    /* = Call the functions
    ----------------------------------------------- */
    containerWidth();
    autoScroll();
    interactions();
    swipeToCycle();

    /* = Refresh on carousel's height change
    ----------------------------------------------- */
    var lastHeight = $slidr.css('height');
    function refresh() {
      if( $slidr.css('height') != lastHeight ) {
        location.reload(false);
      }
    }
    var tOut;
    $(window).resize(function() {
      clearTimeout(tOut);
      tOut = setTimeout(refresh, 300);
    });

  }); // $('.slidr-container').each();
});