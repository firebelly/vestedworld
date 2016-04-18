// VestedWorld - Firebelly 2016
/*jshint latedef:false*/

// Good Design for Good Reason for Good Namespace
var VestedWorld = (function($) {

  var screen_width = 0,
      breakpoint_sm = false,
      breakpoint_md = false,
      breakpoint_lg = false,
      breakpoint_array = [480,1000,1200],
      $document,
      $sidebar,
      loadingTimer,
      page_at,
      ajax_handler_url = '/app/themes/vestedworld/lib/ajax-handler.php';

  function _init() {
    // touch-friendly fast clicks
    FastClick.attach(document.body);

    // Cache some common DOM queries
    $document = $(document);
    $('body').addClass('loaded');

    // Set screen size vars
    _resize();

    //initialize sliders
    _initSliders();

    //put the svg icons inline to grab with use
    _injectSvgSprite();

    // Fit them vids!
    $('main').fitVids();

    _initNav();
    // _initSearch();
    // _initLoadMore();
    _initParallaxBackgrounds();
    _initGifPlay();
    _initPeopleModals();
    _initDropdownInvestorForm();
    _initApplicationForms();

    // Esc handlers
    $(document).keyup(function(e) {
      if (e.keyCode === 27) {
        _hideSearch();
        _hideMobileNav();
        _closePerson();
        _hideInvestorForm();
      }
    });

    // People/post nav arrow handlers
    // Next
    $(document).keydown(function(e) {
      if (e.keyCode === 39) {
        if ($('.person.-active').length) {
          _nextPerson();
        }
      }
    });
    // Previous
    $(document).keydown(function(e) {
      if (e.keyCode === 37) {
        if ($('.person.-active').length) {
          _prevPerson();
        }
      }
    });

    // Smoothscroll links
    $('a.smoothscroll, .smoothscroll a').click(function(e) {
      e.preventDefault();
      var href = $(this).attr('href');
      _scrollBody($(href));
    });

    // Scroll down to hash afer page load
    $(window).load(function() {
      if (window.location.hash) {
        _scrollBody($(window.location.hash));
      }
    });

  } // end init()

  // AJAX Application form submissions
  function _initApplicationForms() {
    // Handle application form submissions
    $('form.application-form').each(function() {
      var $form = $(this);

      $form.validate({
        messages: {
          application_first_name: 'Please leave us your first name',
          application_last_name: 'Please leave us your last name',
          application_email: 'We will need a valid email to contact you at',
          application_phone: 'In case we need to call you'
        },
        submitHandler: function(form) {
          $.ajax({
            url: ajax_handler_url,
            method: 'post',
            dataType: 'json',
            data: $(form).serialize()
          }).done(function(response) {
            if (response.success) {
              _feedbackMessage('Your application was submitted successfully!');
              form.reset();
            } else {
              _feedbackMessage(response.data.message);
            }
          }).fail(function(response) {
            _feedbackMessage('Sorry, there was an error signing up.');
          });
        }
      });
    });
  }

  function _scrollBody(element, duration, delay, offset) {
    if ($('#wpadminbar').length) {
      offset = $('#wpadminbar').height() + offset;
    }
    element.velocity('scroll', {
      duration: duration,
      delay: delay,
      offset: -offset
    }, 'easeOutSine');
  }

  function _feedbackMessage(message) {
    alert(message);
  }

  function _initSearch() {
    $('.search-form:not(.mobile-search) .search-submit').on('click', function (e) {
      if ($('.search-form').hasClass('active')) {

      } else {
        e.preventDefault();
        $('.search-form').addClass('active');
        $('.search-field:first').focus();
      }
    });
    $('.search-form .close-button').on('click', function() {
      _hideSearch();
      _hideMobileNav();
    });
  }

  function _hideSearch() {
    $('.search-form').removeClass('active');
  }

  // Handles main nav
  function _initNav() {
    // SEO-useless nav toggler
    // $('<div class="menu-toggle"><div class="menu-bar"><span class="sr-only">Menu</span></div></div>')
    $('<button class="menu-toggle"><span class="lines"></span></button>')
      .prependTo('.site-header')
      .on('click', function(e) {
        if (!$('.site-nav').is('.-active')) {
          _showMobileNav();
        } else {
          _hideMobileNav();
        }
      });
    // var mobileSearch = $('.search-form').clone().addClass('mobile-search');
    // mobileSearch.prependTo('.site-nav');

    // Close it when clicking off!
    $document.on('click', '.global-overlay, .site-nav a', function() {
      if ($('.site-nav').is('.-active')) {
        if ($(this).is('.sign-up')) {
          $('body, .menu-toggle').removeClass('menu-open');
          $('.site-nav').removeClass('-active');
        } else {
          _hideMobileNav();
        }
      }
    });
  }

  function _showMobileNav() {
    setTimeout(function() {
      $('body').addClass('menu-open');
    }, 50);
    $('.menu-toggle').addClass('menu-open');
    $('.site-nav').addClass('-active');
    _showOverlay();
  }

  function _hideMobileNav() {
    $('body, .menu-toggle').removeClass('menu-open');
    $('.site-nav').removeClass('-active');
    _hideOverlay();
  }

  function _injectSvgSprite() {
    boomsvgloader.load('/app/themes/vestedworld/assets/svgs/build/svgs-defs.svg');
  }

  function _initLoadMore() {
    $document.on('click', '.load-more a', function(e) {
      e.preventDefault();
      var $load_more = $(this).closest('.load-more');
      var post_type = $load_more.attr('data-post-type') ? $load_more.attr('data-post-type') : 'news';
      var page = parseInt($load_more.attr('data-page-at'));
      var per_page = parseInt($load_more.attr('data-per-page'));
      var category = $load_more.attr('data-category');
      var more_container = $load_more.parents('section,main').find('.load-more-container');
      loadingTimer = setTimeout(function() { more_container.addClass('loading'); }, 500);

      $.ajax({
          url: ajax_handler_url,
          method: 'post',
          data: {
              action: 'load_more_posts',
              post_type: post_type,
              page: page+1,
              per_page: per_page,
              category: category
          },
          success: function(data) {
            var $data = $(data);
            if (loadingTimer) { clearTimeout(loadingTimer); }
            more_container.append($data).removeClass('loading');
            if (breakpoint_md) {
              more_container.masonry('appended', $data, true);
            }
            $load_more.attr('data-page-at', page+1);

            // Hide load more if last page
            if ($load_more.attr('data-total-pages') <= page + 1) {
                $load_more.addClass('hide');
            }
          }
      });
    });
  }

  function _initParallaxBackgrounds() {
    // Performance techniques from http://kristerkari.github.io/adventures-in-webkit-land/blog/2013/08/30/fixing-a-parallax-scrolling-website-to-run-in-60-fps/

    var parallaxImages = [],
        speed = 0.01;

    $('.parallax-this').each(function(i) {
      var parallaxImage = {};
      parallaxImage.element = $(this);
      parallaxImage.parent = parallaxImage.element.closest('.parallax-parent');
      parallaxImage.offsetTop = parallaxImage.parent.offset().top;

      parallaxImages.push(parallaxImage);
    });

    $(window).on('scroll', function() {
      window.requestAnimationFrame(backgroundScroll);
    });

    function backgroundScroll() {
      $.each(parallaxImages, function(i, parallaxImage) {
        var scrollRate = $(window).scrollTop();
        // Subtract the top position of the parent from the scroll position
        scrollRate = scrollRate - (parallaxImage.offsetTop);

        parallaxImage.element.css({
          'transform': 'translate(-50%,' + (-70 + (scrollRate * speed)) + '%)'
        });
      });
    }

  }

  function _initGifPlay() {

    if ($('.gif-to-play').length) {
      var delayOffset = 0.75;
      $('.gif-to-play').each(function() {
        var $gif = $(this),
            gifOffset = $gif.offset().top,
            gifSrc = $gif.attr('src'),
            fired;

        isGifInView(this, gifSrc);

        $(window).on('scroll', function() {
          if ($(window).scrollTop() + ($(window).height() * delayOffset) > gifOffset && !$gif.is('.in-view')) {
            $gif.addClass('in-view');
            $gif.attr('src', gifSrc);
            fired = true;
          }
        });
      });
    }

    function isGifInView(element, gifSrc) {
      var $element = $(element),
          elementOffsetTop = $element.offset().top;

      if ($(window).scrollTop() + $(window).height() < elementOffsetTop) {
        fired = false;
      } else {
        $element.addClass('in-view');
        $element.attr('src', gifSrc);
        fired = true;
      }
    }

  }

  function _initDropdownInvestorForm() {
    $('.site-header .sign-up').on('click', function(e) {
      e.preventDefault();

      if (!$('.investor-form-container').is('.-active')) {
        _showInvestorForm();
      } else {
        _hideInvestorForm();
      }
    });

    // Shut it down!
    $document.on('click', '.global-overlay, .investor-form-container .close, .menu-toggle', function() {
      if ($('.investor-form-container').is('.-active')) {
        if ($(this).is('.menu-toggle')) {
          $('.investor-form-container').removeClass('-show');
          setTimeout(function() {
           $('.investor-form-container').removeClass('-active');
          }, 400);
        } else {
          _hideInvestorForm();
        }
      }
    });
  }

  function _showInvestorForm() {
    _showOverlay();
    $('.investor-form-container').addClass('-active');
    setTimeout(function() {
      $('.investor-form-container').addClass('-show');
    }, 50);
  }

  function _hideInvestorForm() {
    $('.investor-form-container').removeClass('-show');
    setTimeout(function() {
      _hideOverlay();
    }, 200);
    setTimeout(function() {
     $('.investor-form-container').removeClass('-active');
    }, 400);
  }

  function _initPeopleModals() {
    $('.person-activate').on('click', function(e) {
      var $activeContainer = $('.active-person-container'),
          $activeDataContainer = $activeContainer.find('.person-data-container'),
          $thisPerson = $(this).closest('.person'),
          $personData = $thisPerson.find('.person-data'),
          thisPersonOffset = -(($('.people-sections').offset().top) - ($thisPerson.offset().top));

      _showOverlay();

      // Is this the only person in their group?
      if (!$thisPerson.next('.person').length && !$thisPerson.prev('.person').length) {
        $activeContainer.addClass('solo');
      } else {
        $activeContainer.removeClass('solo');
      }

      $('.person.-active, .people-grid.-active').removeClass('-active');
      $activeDataContainer.empty();
      $thisPerson.addClass('-active');
      $thisPerson.closest('.people-grid').addClass('-active');
      $personData.clone().appendTo($activeDataContainer);
      $activeContainer.css('top', thisPersonOffset);
      $activeContainer.addClass('-active');
      _scrollBody($activeContainer, 250, 0, 60);
    });

    // Shut it down!
    $('html, body').on('click', '.person-deactivate', function(e) {
      _closePerson();
      _hideOverlay();
    });
    // Close if user clicks outside modal
    $('html, body').on('click', '.global-overlay', function() {
      if($('.active-person-container').is('.-active')) {
        _closePerson();
      }
    });

    // People Grid navigation
    $('.next-person').on('click', function(e) {
      $('.active-person-container .person-data').addClass('exitLeft');
      setTimeout(function() {
        _nextPerson();
      }, 200);
    });
    $('.previous-person').on('click', function(e) {
      $('.active-person-container .person-data').addClass('exitRight');
      setTimeout(function() {
        _prevPerson();
      }, 200);
    });

  }

  function _nextPerson() {
    var $active = $('.people-grid.-active').find('.person.-active');
    // find next or first person
    var $next = ($active.next('.person').length > 0) ? $active.next('.person') : $('.people-grid.-active .person:first');
    $next.find('.person-activate').trigger('click');
    $('.active-person-container .person-data').addClass('enterRight');
  }

  function _prevPerson() {
    var $active = $('.people-grid.-active').find('.person.-active');
    // find prev or last person
    var $prev = ($active.prev('.person').length > 0) ? $active.prev('.person') : $('.people-grid.-active .person:last');
    $prev.find('.person-activate').trigger('click');
    $('.active-person-container .person-data').addClass('enterLeft');
  }

  function _showOverlay() {
    if (!$('.global-overlay').length) {
      $('body').addClass('overlay-open');
      $('<div class="global-overlay"></div>').appendTo($('body'));
      setTimeout(function() {
        $('.global-overlay').addClass('-active');
      }, 50);
    }
  }

  function _hideOverlay() {
    $('body').removeClass('overlay-open');
    $('.global-overlay').removeClass('-active');
    setTimeout(function() {
      $('.global-overlay').remove();
    }, 250);
  }

  function _closePerson() {
    var $activeContainer = $('.active-person-container'),
        $activeDataContainer = $('.person-data-container');

    _hideOverlay();
    $activeContainer.removeClass('-active');
    $('.person.-active').removeClass('-active');
    $('.person-grid.-active').removeClass('-active');
    $activeDataContainer.empty();
  }

  // Track ajax pages in Analytics
  function _trackPage() {
    if (typeof ga !== 'undefined') { ga('send', 'pageview', document.location.href); }
  }

  // Track events in Analytics
  function _trackEvent(category, action) {
    if (typeof ga !== 'undefined') { ga('send', 'event', category, action); }
  }

  //Initialize Slick Sliders
  function _initSliders(){
    $('.slider').slick({
      slide: '.slide-item',
      autoplay: true,
      arrows: false,
      dots: true,
      autoplaySpeed: 6000,
      speed: 800
    });
  }

  // Called in quick succession as window is resized
  function _resize() {
    screenWidth = document.documentElement.clientWidth;
    breakpoint_sm = (screenWidth > breakpoint_array[0]);
    breakpoint_md = (screenWidth > breakpoint_array[1]);
    breakpoint_lg = (screenWidth > breakpoint_array[2]);
  }

  // Called on scroll
  // function _scroll(dir) {
  //   var wintop = $(window).scrollTop();
  // }

  // Public functions
  return {
    init: _init,
    resize: _resize,
    scrollBody: function(section, duration, delay) {
      _scrollBody(section, duration, delay);
    }
  };

})(jQuery);

// Fire up the mothership
jQuery(document).ready(VestedWorld.init);

// Zig-zag the mothership
jQuery(window).resize(VestedWorld.resize);
