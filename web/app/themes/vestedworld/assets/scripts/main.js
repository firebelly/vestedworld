// VestedWorld - Firebelly 2016
/*jshint latedef:false*/

// Good Design for Good Reason for Good Namespace
var VestedWorld = (function($) {

  var screen_width = 0,
      breakpoint_sm = false,
      breakpoint_md = false,
      breakpoint_lg = false,
      breakpoint_array = [480,768,1200],
      $document,
      $sidebar,
      $load_more,
      headerOffset,
      loadingTimer,
      page_at,
      wpAdminBar = false,
      feedback_message_timer,
      History = window.History,
      State,
      root_url = History.getRootUrl(),
      relative_url,
      original_url,
      original_page_title = document.title,
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

    _setHeaderOffset();

    //initialize sliders
    _initSliders();

    //put the svg icons inline to grab with use
    _injectSvgSprite();

    // Fit them vids!
    $('main').fitVids();

    _initAccordions();
    _initLazyLoadImages();
    _initNav();
    _initPageNav();
    // _initSearch();
    _initReadMore();
    _initStickySections();
    _initLoadMore();
    _initNewsFilter();
    // _initParallaxBackgrounds();
    _initGifPlay();
    _initItemGrid();
    _initDropdownInvestorForm();
    _initApplicationForms();
    _initStateHandling();
    _initFaqs();

    // Esc handlers
    $(document).keyup(function(e) {
      if (e.keyCode === 27) {
        if ($('.active-grid-item-container.-active').length) {
          History.pushState({}, document.title, original_url);
        } else {
          _hideSearch();
          _hideMobileNav();
          _closeGridItem();
          _hideInvestorForm();
        }
      }
    });

    // Grid item nav arrow handlers
    // Next
    $(document).keydown(function(e) {
      if (e.keyCode === 39) {
        if ($('.grid-item.-active').length) {
          _nextGridItem();
        }
      }
    });
    // Previous
    $(document).keydown(function(e) {
      if (e.keyCode === 37) {
        if ($('.grid-item.-active').length) {
          _prevGridItem();
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

      if (State.url !== original_url && relative_url.match(/^\/(company|person|\d{0,4})\//)) {

        // Standard post modals
        if (page_cache[encodeURIComponent(State.url)]) {
          _showGridItem();
        } else {
          _loadGridItem();
        }

      } else if (relative_url.match(/^\/resources\/news\//)) {

        // News filtering
        _filterNews();

      } else {

        // URL isn't handled as a modal or in-page filtering
        if (State.url !== original_url) {
          // Just load URL if isn't original_url
          location.href = State.url;
        } else {
          // Hide modals etc
          _closeGridItem();
          _hideOverlay();
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

  // FAQ behavior
  function _initFaqs() {
    // Filter list
    $('.filter-select').change(function(){
      var targetSection = "#" + $(this).val();
      _scrollBody($(targetSection), 250, 0);
    });

    // Feedback behavior
    if ($('.post-feedback').length) {
      var postFeedback = $('.post-feedback');

      postFeedback.each(function() {
        // Has this post already been rated?
        if (!$(this).find('.post-ratings img').length) {
          $(this).addClass('user-already-voted');
        }
      });

      // Display the text in place of the img
      $('.post-ratings img').each(function() {
        var ratingText = $(this).attr('alt');
        $(this).wrap('<span class="post-ratings-trigger">');
        $(this).after('<span class="post-ratings-word">' + ratingText + '</span>');
      });

    }

    // Toggle a class when someone chooses a rating
    $document.on('click', '.post-ratings img', function() {
      $(this).closest('.post-feedback').addClass('rating-chosen');
    });
  }

  function _initAccordions() {
    var time = 350;
    $('.accordion-trigger').on('click', function(e) {
      e.preventDefault();
      var $thisGroup = $(this).closest('.accordion'),
          $thisItem = $(this).closest('.accordion-item');
      if ($thisItem.is('.active')) {
        $thisItem.removeClass('active');
        $thisItem.find('.accordion-content').velocity("slideUp", { duration: time });
      } else {
        $thisGroup.find('.accordion-item.active .accordion-content').velocity("slideUp", { duration: time });
        $thisGroup.find('.accordion-item.active').removeClass('active');
        $thisItem.find('.accordion-content').velocity("slideDown", { duration: time });
        $thisItem.addClass('active');
      }
    });
  }

  function _initLazyLoadImages() {
    [].forEach.call(document.querySelectorAll('img[data-src]'), function(img) {
      img.setAttribute('src', img.getAttribute('data-src'));
      img.onload = function() {
        img.removeAttribute('data-src');
      };
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
    _scrollBody($('body'), 250, 0);
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
        } else if ($(this).parent('li').is('.no-link')) {
          // Do nothing man!
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
      History.pushState({}, 'News - VestedWorld', url);
    });
    $document.on('change', 'select[name=cat]', function(e) {
      var url = $('.news-filter form').attr('action') + '?' + $('.news-filter form').serialize();
      History.pushState({}, 'News - VestedWorld', url);
    });
  }

  function _initStickySections() {

    // ¿Hay sticky sections?
    if ($('.sticky-section').length) {

      $('.sticky-section').each(function(i) {
        var $thisSection = $(this),
            thisTitleWidth;

        // Set the section title width depending on breakpoint
        function setTitleWidth() {
          if (breakpoint_md === true) {
            thisTitleWidth = $thisSection.find('.sticky-title').outerWidth();
          } else {
            thisTitleWidth = 32;
          }
        }

        setTitleWidth();
        $(window).on('resize', function() {
          setTitleWidth();
        });

        $(window).on('scroll', function() {
          var scrollPos = $(window).scrollTop();

          if (!$thisSection.is('.inView') && scrollPos + headerOffset >= $thisSection.offset().top && scrollPos + headerOffset + thisTitleWidth <= $thisSection.offset().top + $thisSection.outerHeight()) {
            $thisSection.removeClass('stick-to-bottom').addClass('inView');
          } else if ($thisSection.is('.inView') && scrollPos + headerOffset + thisTitleWidth > $thisSection.offset().top + $thisSection.outerHeight()) {
            $thisSection.removeClass('inView').addClass('stick-to-bottom');
          } else if ($thisSection.is('.inView') && scrollPos + headerOffset <= $thisSection.offset().top) {
            $thisSection.removeClass('inView');
          }
        });
      });

    }
  }

  // Load AJAX content to show in a modal & store in page_cache array
  function _loadGridItem() {
    $.ajax({
      url: ajax_handler_url,
      method: 'get',
      dataType: 'html',
      data: {
        'action': 'load_post_modal',
        'post_url': State.url
      },
      success: function(response) {
        page_cache[encodeURIComponent(State.url)] = $.parseHTML(response);
        _showGridItem();
      }
    });
  }

  // Function to update document title after state change
  function _updateTitle() {
    var title = '';
    if ($('.active-grid-item-container.-active [data-page-title]').length) {
      title = $('.active-grid-item-container [data-page-title]').first().attr('data-page-title');
    } else {
      title = original_page_title;
    }
    if (title === '') {
      title = 'VestedWorld';
    } else if (!title.match(/VestedWorld/)) {
      title = title + ' – VestedWorld';
    }
    document.title = title;
    try {
      document.getElementsByTagName('title')[0].innerHTML = document.title.replace('<','&lt;').replace('>','&gt;').replace(' & ',' &amp; ');
    } catch (Exception) {}
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

  function _initItemGrid() {
    // People have single post data included in initial grid items
    $('.grid-item.person article').each(function() {
      page_cache[encodeURIComponent($(this).attr('data-page-url'))] = $(this).clone();
    });

    // Use statechange to handle modals
    $document.on('click', '.grid-item-activate', function(e) {
      var $target = $(e.target);
      if (!$target.is('.no-ajaxy')) {
        e.preventDefault();
        History.pushState({}, '', $(this).attr('href') || $(this).attr('data-page-url'));
      }
    });

    // Initial post?
    $(window).load(function() {
      if (window.location.hash && $(window.location.hash).length) {
        var url = $(window.location.hash).attr('data-page-url');
        History.replaceState({ignore_change: true}, null, '##');
        original_url = root_url + 'community/';
        History.replaceState({}, document.title, original_url);
        setTimeout(History.pushState({}, '', url), 150);
      }
    });

    // Shut it down!
    $('html, body').on('click', '.grid-item-deactivate', function(e) {
      History.pushState({}, '', original_url);
    });
    // Close if user clicks outside modal
    $('html, body').on('click', '.global-overlay', function() {
      if($('.active-grid-item-container').is('.-active')) {
        History.pushState({}, '', original_url);
      }
    });

    // Item Grid navigation
    $document.on('click', '.next-item', function(e) {
      $('.active-grid-item-container .grid-item-data').addClass('exitLeft');
      setTimeout(function() {
        _nextGridItem();
      }, 200);
    });
    $document.on('click', '.previous-item', function(e) {
      $('.active-grid-item-container .grid-item-data').addClass('exitRight');
      setTimeout(function() {
        _prevGridItem();
      }, 200);
    });

  }

  function _showGridItem() {
    var $activeArticle = $('article[data-page-url="' + State.url + '"]');
    if ($activeArticle.length) {
      var $activeContainer = $('.active-grid-item-container'),
          $activeDataContainer = $activeContainer.find('.item-data-container'),
          $thisItem = $activeArticle.closest('.grid-item'),
          thisItemOffset = $thisItem.offset().top;

      $itemData = $(page_cache[encodeURIComponent(State.url)]);
      _showOverlay();

      // Is this the only item in their group?
      if (!$thisItem.next('.grid-item').length && !$thisItem.prev('.grid-item').length) {
        $activeContainer.addClass('solo');
      } else {
        $activeContainer.removeClass('solo');
      }

      $('.grid-item.-active, .grid-items.-active').removeClass('-active');
      $activeDataContainer.empty();
      $thisItem.addClass('-active');
      $thisItem.closest('.grid-items').addClass('-active');
      $itemData.clone().appendTo($activeDataContainer);
      $activeContainer.css('top', thisItemOffset);
      $activeContainer.addClass('-active');
      _scrollBody($activeContainer, 250, 0, headerOffset + 64);

      // Track page view
      _trackPage();
      // Update document title
      _updateTitle();
    }
  }

  function _nextGridItem() {
    var $active = $('.grid-items.-active').find('.grid-item.-active');
    // Find next or first item
    var $next = ($active.next('.grid-item').length > 0) ? $active.next('.grid-item') : $('.grid-items.-active .grid-item:first');
    if ($next[0] === $active[0]) { return; } // Just return if there's only one item
    $next.find('.grid-item-activate').trigger('click');
    $('.active-grid-item-container .grid-item-data').addClass('enterRight');
  }

  function _prevGridItem() {
    var $active = $('.grid-items.-active').find('.grid-item.-active');
    // Find prev or last item
    var $prev = ($active.prev('.grid-item').length > 0) ? $active.prev('.grid-item') : $('.grid-items.-active .grid-item:last');
    if ($prev[0] === $active[0]) { return; } // Just return if there's only one item
    $prev.find('.grid-item-activate').trigger('click');
    $('.active-grid-item-container .grid-item-data').addClass('enterLeft');
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

  function _closeGridItem() {
    var $activeContainer = $('.active-grid-item-container'),
        $activeDataContainer = $('.item-data-container');

    _hideOverlay();
    $activeContainer.removeClass('-active');
    $('.grid-item.-active').removeClass('-active');
    $('.grid-items.-active').removeClass('-active');
    $activeDataContainer.empty();
  }

  // Track ajax pages in Analytics
  function _trackPage() {
    if (typeof ga !== 'undefined') {
      ga('send', 'pageview', location.pathname);
    }
  }

  // Track events in Analytics
  function _trackEvent(category, action) {
    if (typeof ga !== 'undefined') {
      ga('send', 'event', category, action);
    }
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

    _setHeaderOffset();
  }

  // Header offset w/wo wordpress admin bar
  function _setHeaderOffset() {
    if (breakpoint_md === true) {
      if ($('body').hasClass('admin-bar')) {
        wpAdminBar = true;
        headerOffset = $('#wpadminbar').outerHeight() + $('.site-header').outerHeight();
      } else {
        headerOffset = $('.site-header').outerHeight();
      }
    } else {
      headerOffset = 0;
    }
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