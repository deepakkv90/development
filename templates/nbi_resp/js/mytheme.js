;(function($, window, undefined){

  navHovers();
  mobileNav();
  var _hTop = $("#header").offset().top;
  $(window).scroll(function(){
    if($(this).scrollTop()>=_hTop){
      $("#header").addClass('fixed');
    }else{
      $("#header").removeClass('fixed');
    }
  });

  // Instagram feed
  if ( $('#journal').length ) {
    console.log('here');
    instafeed();
  }

  // functions for Product Pages
  if ($('body').hasClass('single-product')) {
    pdpFunctions();
  }

	
  $(document).ready(function(){

    slickSliders();
    emailPopup();


//     $('.slidedown-trigger').click(slidedownToggle);
	  
    var isMobile = {
      Android: function() {
        return navigator.userAgent.match(/Android/i);
      },
      BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
      },
      iOS: function() {
        return navigator.userAgent.match(/iPhone|iPod/i);
      },
      Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
      },
      Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
      },
      any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
      }
    };


    $('.woocommerce-message').show().delay('500').animate({'top':0 +'px'}, 500, 'swing');


    setInputFieldFunctions();

    $('#nav-toggle').on('click', function() {
      this.addClass('active');
    });




    if(isMobile.any()) {
      $('#signup-cont-desktop').remove();
      $('#vmap').remove();
    }


    function setInputFieldFunctions(){
      $('input, textarea').each(function(){
        var $this = $(this);
        $this.data('placeholder', $this.attr('placeholder'))
        .focus(function(){$this.removeAttr('placeholder');})
        .blur(function(){$this.attr('placeholder', $this.data('placeholder'));});
      });
    }
    
    function isNewVisitor() {
	    if ($.cookie('visitCount') == 0 || $.cookie('visitCount') == null) {
		    $.cookie('visitCount', 1);
		    return true;
	    }
	    else {
		    var tmp = parseInt($.cookie('visitCount'));
		    tmp++;
		    $.cookie('visitCount', tmp);
		    return false;
	    }
    }

    function slickSliders() {
      var prev_arrow_1 = $('#slick-prev-1');
      var next_arrow_1 = $('#slick-next-1');
      var prev_arrow_2 = $('#slick-prev-2');
      var next_arrow_2 = $('#slick-next-2');

      $('body.home').find('ul.products').slick({
        //centerMode: true,
        centerPadding: '60px',
        slidesToShow: 3,
        prevArrow: prev_arrow_1,
        nextArrow: next_arrow_1,
        responsive: [
          {
            breakpoint: 800,
            settings: {
              slidesToShow: 2
            }
          },
          {
            breakpoint: 500,
            settings: {
              slidesToShow: 1
            }
          }
        ]
      });

      $('#as-seen-in').find('.wrapper').slick({
        centerMode: true,
        centerPadding: '60px',
        slidesToShow: 6,
        prevArrow: prev_arrow_2,
        nextArrow: next_arrow_2,
        responsive: [
          {
            breakpoint: 800,
            settings: {
              slidesToShow: 4,
            }
          },
          {
            breakpoint: 500,
            settings: {
              slidesToShow: 2,
            }
          }
        ]
      });
    }



    function emailPopup() {

      var $overlay = $('#main-wrap');
      var $modal = $('.new-customer-modal');
      var $response_message = $('#response-message');

      // Email popup modal
      if ( !isNewVisitor() ) {
        // do nothing
      }
      else if ( $('body').hasClass('home') ) {
        setTimeout(function() {
          $overlay.animate({opacity: .2});
        }, 800);

        setTimeout(function() {
          $modal.fadeIn(500).css({'display': 'block', 'top': '50%'});
        }, 1000);
      }

      // form submit
      $('#email-signup-form').submit(function() {
        console.log($('#email-signup-form').serialize());
        $.ajax({
          url: '../../wp-content/themes/jackrudy/php-scripts/store-address.php', // proper url to your "store-address.php" file
          type: 'POST', // <- IMPORTANT
          data: $('#email-signup-form').serialize() + '&ajax=true',
          success: function(msg) {
            var message = $.parseJSON(msg),
                result = '';

            if (message.status === 'pending') { // success
              result = 'Success! Please enjoy the site.';
              setTimeout(function() {
                $overlay.css('opacity', 1);
                $modal.fadeOut(500);
              }, 1500);
            }
            else if (message.title === 'Member Exists') {
              result = 'You have already signed up! Please enjoy the site.';
              setTimeout(function() {
                $overlay.css('opacity', 1);
                $modal.fadeOut(500);
              }, 1500);
            }
            else { // error
              result = 'Error: ' + message.detail;
            }
            $response_message.addClass('active').html(result); // display the message
            console.log(message);

          }
        });

        return false;
      });

      $('.newsletter-popup-trigger').click(function(e) {
        e.stopPropagation();
        $overlay.animate({opacity: .2});
        $modal.fadeIn(500).css({'display': 'table', 'top': '50%'});
      });

      $modal.click(function(e) {
        e.stopPropagation();
      });

      $(window).click(function() {
        $overlay.css('opacity', 1);
        $modal.fadeOut(500);
      });

      $('#close-popup').click(function() {
        $overlay.css('opacity', 1);
        $modal.fadeOut(500);
      });
    }

  });
/*
  var slidedownToggle = function() {
	
    var $this = $(this);
    var container  = $(this).data('slide-container');
    $this.toggleClass('slidedown-open');
    var selector = "div.slidedown[data-slide-container='" + container + "']";
    $(selector).slideToggle();
  };
*/

  function navHovers() {
    var $header_links = $('#header').find('.menu-item-wrapper');
    navHoverHelper($header_links);
  }

  function navHoverHelper($link_group) {
    $link_group.hover(function() {
      $(this).addClass('hover');
      $link_group.not('.hover').addClass('inactive');
    }, function() {
      $(this).removeClass('hover');
      $link_group.removeClass('inactive');
    });
  }

  function pdpFunctions() {
    if (isEmpty($('div.size-value'))) {
      $('div.spanner').remove()
    }

    var $overlay = $('#dark-overlay');
    var $wrapper = $overlay.find('.wrapper');

    $('.description a').click(function(e) {
      e.preventDefault();
      $wrapper.append('<img id="nutritional-facts" src="' + $(this).attr('href') + '" alt="nutritional facts">' );
      $overlay.fadeIn();
    });

    $wrapper.click(function(e) {
      e.stopPropagation();
    });

    $overlay.click(function() {
      $overlay.fadeOut();
      $('#nutritional-facts').remove();
    });
  }

  function isEmpty( el ){
    return !$.trim(el.html())
  }

  function instafeed() {
    console.log('here');
    var userFeed = new Instafeed({
      get: 'user',
      userId: '1537675698',
      accessToken: '1537675698.1677ed0.fa63920c84ba43cc8210f2270e665028',
      resolution: 'standard_resolution'
    });
    userFeed.run();
  }

  function mobileNav() {
    $('.mobile-menu-trigger').click(function() {
      $('.mobile-nav').slideToggle();
      $('body').toggleClass('no-overflow');
    });
  }

})(jQuery, window);

/*

    $('.mobile-menu-trigger').click(function() {
      $('.mobile-nav').slideToggle();
      $('body').toggleClass('no-overflow');
    });
*/



