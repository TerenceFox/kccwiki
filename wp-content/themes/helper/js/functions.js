jQuery(document).ready(function($) {
  'use strict';
  // Forms
  $('.form-group input, .form-group textarea').focus(function() {
    var spanWidth = $(this).parent().find('span').width();
    var    lefty = $(this).width()-spanWidth
    $(this).parent().find('span').animate({left: lefty, opacity: 0.5});
  });

  $('.form-group input, .form-group textarea').blur(function() {
    if( !$(this).val().length < 1 ) {
          $(this).parent().find('span').fadeOut();
    } else
    {
      $(this).parent().find('span').animate({left: 10, opacity: 1});
    }
  });

  $('.form-group').hover(function() {
    $(this).find('span').css('pointer-events', 'none');
  });

  $('form br').remove();
  $('p.form-submit input[type=submit]').addClass('button');


  // Slick Nav
  $('.menu-header.default ul.main-menu').slicknav({
    prependTo : '.mobile-menu',
    label: 'Menu'
  });

  // Sticky menu 
  var menu = jQuery('.menu-header'),
      pos = menu.offset(),
      elementOffset = menu.offset().top;

  $(window).scroll(function() {
      // Sticky menu

      if( $(window).scrollTop() > elementOffset && !($(menu).hasClass('sticky'))){
        $(menu).addClass('sticky');
      } else if ($(window).scrollTop() == 0){
        $(menu).removeClass('sticky');
      }

      // Back to top
      var winHeight = 100
      if ($(this).scrollTop() > winHeight) {
           $('#scroll-top').fadeIn();
      } else {
           $('#scroll-top').fadeOut();
      }
  });

  function getCurrentScroll() {
      return window.pageYOffset || document.documentElement.scrollTop;
  }
  
  $("input#s").focus(function() {
    $('#warrior-advanced-search i.live-search-reset').fadeIn();
  });
  
  $("input#s").blur(function() {
    $('#warrior-advanced-search i.live-search-reset').fadeOut();
  });

  $('#warrior-advanced-search i.live-search-reset').click(function() {
    $("input#s").val('');
  });

  // Visual Composer
  $( ".page-area" ).find( $( ".vc_row" ) ).removeClass('container').removeAttr("style");
  $( ".home #colophone" ).appendTo( "div.page" );

  $('.vc_element .vc_controls-container .vc_controls-out-tl').removeClass('vc_controls-out-tl').addClass('vc_controls-cc');

  // Go to Top
  $('a[href="#top"]').click(function() {
    $('html, body').animate({ scrollTop: 0 }, 'slow');
    return false;
  });


  // Strecth header background
  if ( _warrior.bg_header !== '' )
    if( _warrior.backstretch_status == 1) {
      $('#slider-section').backstretch( _warrior.bg_header, {centeredY: false} );
    }
  var height = $('#slider-section').height(); 

  $(".menu-trigger").click(function() {
      $("#main-menu ul.main-menu").slideToggle();
  })

  // Tabs

  $(".simple-tab-nav a").click(function(event) {
        event.preventDefault();
        $(this).parent().addClass("current");
        $(this).parent().siblings().removeClass("current");
        var tab = $(this).attr("href");
        $(".tab-content").not(tab).css("display", "none");
        $(tab).fadeIn();
    });

  // Statistics
  $('#statistic-widget').waypoint(function(direction) {
    setTimeout(function(){ 
      $('.odometer').each(function() {
        var odoNumber = $(this).attr("data-number");
        $(this).animateNumber({ number: odoNumber });
      });
    });
  }, {
    offset: '90%'
  });

  // Equal Height Columns
  var equalheight;

  equalheight = function(container){
    var currentTallest = 0,
         currentRowStart = 0,
         currentDiv = 0,
         rowDivs = new Array(),
         $el,
         topPosition = 0;
    
    $(container).each(function() {
       $el = $(this);
       $($el).height('auto');
       topPosition = $el.position().top;

       if (currentRowStart != topPosition) {
         for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
           rowDivs[currentDiv].height(currentTallest);
         }
         rowDivs.length = 0; // empty the array
         currentRowStart = topPosition;
         currentTallest = $el.height();
         rowDivs.push($el);
       } else {
         rowDivs.push($el);
         currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
      }
       for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
         rowDivs[currentDiv].height(currentTallest);
       }
     });
  }

  $(window).load(function() {
    equalheight('#featured-widget article.featured-post');

    // Partnert Slider
    $('.image-carousel').flexslider({
      animation: "slide",
      animationLoop: false,
      controlNav: true,
      directionNav: false,
      itemWidth: 180,
      itemMargin: 18,
      minItems: 1,
      maxItems: 6
    });
  });


  $(window).resize(function(){
    equalheight('#featured-widget article.featured-post');
  });

  // you want to enable the pointer events only on click;

  $('.wpb_map_wraper iframe').addClass('scrolloff'); // set the pointer events to none on doc ready
  // you want to disable pointer events when the mouse leave the canvas area;

  $(".wpb_map_wraper iframe").mouseleave(function () {
      $('.wpb_map_wraper iframe').addClass('scrolloff'); // set the pointer events to none when mouse leaves the map area
  });
});