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
      $load_more,
      headerOffset,
      loadingTimer,
      page_at,
      feedback_message_timer,
      History = window.History,
      State,
      root_url = History.getRootUrl(),
      relative_url,
      original_url,
      page_cache = {},
      ajax_handler_url = '/app/themes/vestedworld/lib/ajax-handler.php';

  function _init() {
    // touch-friendly fast clicks
    FastClick.attach(document.body);

    // Cache some common DOM queries
    $document = $(document);
    $load_more = $('.load-more');
    $('body').addClass('loaded');

    // Set screen size vars
    _resize();

    // Header offset w/wo wordpress admin bar
    if ($('body').hasClass('admin-bar')) {
      headerOffset = $('#wpadminbar').outerHeight() + $('.site-header').outerHeight();
    } else {
      headerOffset = $('.site-header').outerHeight();
    }

    //initialize sliders
    _initSliders();

    //put the svg icons inline to grab with use
    _injectSvgSprite();

    // Fit them vids!
    $('main').fitVids();

    _initNav();
    _initPageNav();
    // _initSearch();
    _initReadMore();
    _initLoadMore();
    _initNewsFilter();
    // _initParallaxBackgrounds();
    _initGifPlay();
    _initPeopleModals();
    _initDropdownInvestorForm();
    _initApplicationForms();
    _initStateHandling();

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

  // Bind to state changes and handle back/forward
  function _initStateHandling() {
    // Initial state
    State = History.getState();
    relative_url = '/' + State.url.replace(root_url,'');
    original_url = State.url;

    $(window).bind('statechange',function(){
      State = History.getState();
      relative_url = '/' + State.url.replace(root_url,'');

      if (State.data.ignore_change) {
        return;
      }

      if (relative_url.match(/^\/resources\/news\//)) {

        // News filtering
        _filterNews();

      } else {

        // URL isn't handled as a modal or in-page filtering
        if (State.url !== original_url) {
          // Just load URL if isn't original_url
          location.href = State.url;
        } else {
          // Hide modals etc here if applicable...
        }

      }

      // Track AJAX URL change in analytics
      _trackPage();

      // Update Facebook tags for any share buttons on the page
      _updateOGTags();
    });
  }
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
          $('.site-wrap').css('opacity', '.5');
          _showLoading();
          $.ajax({
            url: ajax_handler_url,
            method: 'post',
            dataType: 'json',
            data: $(form).serialize()
          }).done(function(response) {
            _hideLoading();
            $('.site-wrap').css('opacity', '1');
            if (response.success) {
              _feedbackMessage('Your application was submitted successfully!');
              form.reset();
              if ($(form).parent('.investor-form-container')) {
                _hideInvestorForm();
              }
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
    _scrollBody($('body'), 250, 0, 0);
    $('.investor-form-container').addClass('-active');
    setTimeout(function() {
      $('.investor-form-container').addClass('-show');
    }, 50);
    $('.investor-form-container form input:first').focus();
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

  function _scrollBody(element, duration, delay, offset) {
    if (offset === undefined) {
      offset = headerOffset - 1;
    }

    element.velocity('scroll', {
      duration: duration,
      delay: delay,
      offset: -offset,
    }, 'easeOutSine');
  }

  function _feedbackMessage(message) {

    $('body').append('<div class="flash-message"><h2>'+message+'</h2></div>');

    setTimeout(function(){
      $('.flash-message').addClass('show-message');
    }, 250);

    if (feedback_message_timer) { clearTimeout(feedback_message_timer); }
    feedback_message_timer = setTimeout(hideFeedbackMessage, 3000);

    $('.flash-message').on('mouseenter', function() {
      if (feedback_message_timer) { clearTimeout(feedback_message_timer); }
    }).on('mouseleave', function() {
      if (feedback_message_timer) { clearTimeout(feedback_message_timer); }
      feedback_message_timer = setTimeout(hideFeedbackMessage, 1000);
    });
  }
  function hideFeedbackMessage() {
    $('.flash-message').removeClass('show-message');
    setTimeout(function() { $('.flash-message').remove(); }, 250);
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

  function _initPageNav() {
    // Is ther page-nav sections on the page?
    if ($('.page-nav-section').length) {
      var activeSectionIndex = 0,
          pageNavSections = $('.page-nav-section'),
          pageSectionTitles = $('.page-nav-title'),
          $activeSection = $(pageNavSections[activeSectionIndex]),
          $nextSection = $(pageNavSections[activeSectionIndex + 1]),
          pageNav = $('.site-wrap').append('<nav class="page-nav"><ul></ul><div class="top">top &gt;</div><div class="next-section">&lt; Next section</div></nav>'),
          sectionPositions = [];

      // Fix for header offset discrepency
      headerOffset = headerOffset + 1;

      // Start off with the next section text in next link
      $('.page-nav .next-section').html('&lt; ' + $(pageSectionTitles[activeSectionIndex + 1]).html());

      // Build dots nav
      pageNavSections.each(function() {
        var thisId = $(this).attr('id'),
            thisTitle = $(this).find('.page-nav-title').html();
        $('.page-nav ul').append('<li><a href="#' + thisId + '" class="smoothscroll"><span>' + thisTitle + '</span></a></li>');
      });
      $navDots = $('.page-nav li');

      // Cache heights and positions
      for( var i = 0; i < pageNavSections.length; i++ ) {
        var $element = $(pageNavSections[i]),
            height = $element.offset().top;
        sectionPositions.push(height);
      }

      // Start off with active section dot active
      $navDots.eq(activeSectionIndex).addClass( '-active' );

      $(window).on('scroll', function(e) {
        var scrollPos = $(window).scrollTop() + headerOffset;

        for( var i = 0; i < pageNavSections.length; i++ ) {
          if(scrollPos > sectionPositions[i]) {
            activeSectionIndex = i;
            $('.page-nav li.-active').removeClass('-active');
            $navDots.eq( activeSectionIndex ).addClass( '-active' );
            updatePageNav(activeSectionIndex + 1);

            if (activeSectionIndex < pageNavSections.length - 1) {
              $nextSection = $(pageNavSections[activeSectionIndex + 1]);
            } else {
              $('.page-nav .next-section').html('');
            }
          } else {
            activeSectionIndex = i;
            $navDots.eq( activeSectionIndex ).removeClass( '-active' );
          }
        }
      });

      // Go back to top
      $document.on('click', '.page-nav .top', function() {
        _scrollBody($('.page-nav-section:first'), 250);
      });
      // Go to next section
      $document.on('click', '.page-nav .next-section', function() {
        _scrollBody($nextSection, 250);
      });

    }

    function updatePageNav(activeSectionIndex) {
      $('.page-nav .next-section').html('&lt; ' + $(pageSectionTitles[activeSectionIndex]).html());
    }
  }

  function _injectSvgSprite() {
    boomsvgloader.load('/app/themes/vestedworld/assets/svgs/build/svgs-defs.svg');
  }

  // Read More buttons on news posts
  function _initReadMore() {
    $document.on('click', 'article.post a.read-more', function(e) {
      e.preventDefault();
      var $this = $(this);
      var $article = $this.closest('.article.post').toggleClass('more-active');
      $article.find('.post-extended').slideToggle();
      if ($article.hasClass('more-active')) {
        $this.text('Read Less');
      } else {
        $this.text('Read More');
      }
    });
  }

  // Extract params from URL
  function _getParam(name) {
    var match = new RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
  }

  // Filter News based on current URL
  function _filterNews() {
    var cat = _getParam('cat') || -1;
    var s = _getParam('s');
    $('select[name=cat]').val(cat);
    $('input[name=s]').val(s);
    $load_more.attr('data-category',cat).attr('data-s', s).attr('data-page-at', 0);
    $('.load-more-container').empty();
    $load_more.find('a').trigger('click');
    _trackEvent('In-Page Interactions','Filter News, cat: '+cat+' search: '+s);
  }

  // Init js handling of News filter form
  function _initNewsFilter() {
    $document.on('submit', '.news-filter form', function(e) {
      e.preventDefault();
      var url = $('.news-filter form').attr('action') + '?' + $('.news-filter form').serialize();
      History.pushState({}, 'News - Vested World', url);
    });
    $document.on('change', 'select[name=cat]', function(e) {
      var url = $('.news-filter form').attr('action') + '?' + $('.news-filter form').serialize();
      History.pushState({}, 'News - Vested World', url);
    });
  }

  // Load More buttons on News listings
  function _initLoadMore() {
    $document.on('click', '.load-more a', function(e) {
      e.preventDefault();
      var $load_more = $(this).closest('.load-more');
      var post_type = $load_more.attr('data-post-type') ? $load_more.attr('data-post-type') : 'news';
      var page = parseInt($load_more.attr('data-page-at'));
      var per_page = parseInt($load_more.attr('data-per-page'));
      var category = $load_more.attr('data-category');
      var s = $load_more.attr('data-s');
      var more_container = $load_more.parents('section,main').find('.load-more-container');
      loadingTimer = setTimeout(function() {
        $('body').addClass('working');
        _showLoading();
      }, 250);
      $.ajax({
        url: ajax_handler_url,
        method: 'get',
        dataType: 'json',
        data: {
            action: 'load_more_posts',
            post_type: post_type,
            page: page+1,
            per_page: per_page,
            category: category,
            s: s
        }
      }).done(function(response) {
        if (response.success) {
          var $data = $(response.data.posts_html);
          if (loadingTimer) { clearTimeout(loadingTimer); }
          $data.appendTo(more_container).hide().fadeIn();
          _hideLoading();
          $('body').removeClass('working');
          $load_more.attr('data-page-at', page+1).attr('data-total-pages', response.data.total_pages);
          _checkLoadMore();
        }
      });
    });
  }

  function _showLoading() {
    $('body').append('<div class="loading"></div>');
  }
  function _hideLoading() {
    $('div.loading').remove();
  }

  // Hide "Load More" if there are no more pages
  function _checkLoadMore() {
    $('.load-more').toggleClass('hide', parseInt($('.load-more').attr('data-page-at')) >= parseInt($('.load-more').attr('data-total-pages')));
  }

  // Update og: tags after state change
  function _updateOGTags() {
    $('meta[property="og:url"]').attr('content', State.url);
    $('meta[property="og:title"]').attr('content', document.title);
    $('meta[property="og:type"]').attr('content', ($('body').is('.modal-active') ? 'article' : 'website') );
    // If page has a hidden div with id="og-updates" extract these
    if ($('#og-updates').length) {
      $('meta[property="og:description"]').attr('content', $('#og-updates').attr('data-description'));
      $('meta[property="og:image"]').attr('content', $('#og-updates').attr('data-image'));
    }
  }

  function _initParallaxBackgrounds() {
    // Performance techniques from http://kristerkari.github.io/adventures-in-webkit-land/blog/2013/08/30/fixing-a-parallax-scrolling-website-to-run-in-60-fps/

    var parallaxImages = [],
        speed = 0.1;

    $('.parallax-this').each(function(i) {
      var parallaxImage = {};
      parallaxImage.element = $(this);
      parallaxImage.parent = parallaxImage.element.closest('.parallax-parent');
      parallaxImage.offsetTop = parallaxImage.parent.offset().top;

      parallaxImages.push(parallaxImage);
    });

    // window.requestAnimationFrame(backgroundScroll);

    $(window).on('scroll', function() {
      window.requestAnimationFrame(backgroundScroll);
    });

    function backgroundScroll() {

      $.each(parallaxImages, function(i, parallaxImage) {
        var scrollPos = $(window).scrollTop();
        // Subtract the top position of the parent from the scroll position
        scrollRate = scrollPos - (parallaxImage.offsetTop);

        if (scrollPos + $(window).height() > parallaxImage.offsetTop) {
          parallaxImage.element.css({
            'transform': 'translate3d(-50%,' + scrollRate * speed + '%, 0)'
          });
        }

      });
    }

  }

  function _initGifPlay() {

    if ($('.gif-to-play').length) {
      var delayOffset = 0.75;
      $('.gif-to-play').each(function() {
        var $gif = $(this),
            $gifParent = $gif.parent(),
            gifOffset = $gif.offset().top,
            gifSrc = $gif.attr('src'),
            fired;

        isGifInView(this, gifSrc);

        $(window).on('scroll', function() {
          if ($(window).scrollTop() + ($(window).height() * delayOffset) > gifOffset && !$gif.is('.in-view')) {
            showGifSrc($gif, gifSrc);
          }
        });

        // When hovering over the parent article start the animation over
        $gifParent.on('mouseenter', function() {
          showGifSrc($gif, gifSrc);
        });
      });

    }

  }

  function showGifSrc(gif, gifSrc) {
    var $gif = gif;
    $gif.addClass('in-view');
    $gif.attr('src', gifSrc);
    fired = true;
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
      _scrollBody($activeContainer, 250, 0, headerOffset + 64);
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
    // Find next or first person
    var $next = ($active.next('.person').length > 0) ? $active.next('.person') : $('.people-grid.-active .person:first');
    if ($next[0] === $active[0]) { return; } // Just return if there's only one person
    $next.find('.person-activate').trigger('click');
    $('.active-person-container .person-data').addClass('enterRight');
  }

  function _prevPerson() {
    var $active = $('.people-grid.-active').find('.person.-active');
    // Find prev or last person
    var $prev = ($active.prev('.person').length > 0) ? $active.prev('.person') : $('.people-grid.-active .person:last');
    if ($prev[0] === $active[0]) { return; } // Just return if there's only one person
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
      speed: 800,
      adaptiveHeight: true,
      lazyLoad: 'ondemand'
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
    scrollBody: function(section, duration, delay, offset) {
      _scrollBody(section, duration, delay, offset);
    }
  };

})(jQuery);

// Fire up the mothership
jQuery(document).ready(VestedWorld.init);

// Zig-zag the mothership
jQuery(window).resize(VestedWorld.resize);