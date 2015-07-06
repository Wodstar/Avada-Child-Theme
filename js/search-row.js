var WodstarSearchRow, wodstar_sw;

WodstarSearchRow = (function() {
  function WodstarSearchRow(jQuery) {
    this.$ = jQuery;
    this.use_ajax = true;
    this.search_query;
  }

  WodstarSearchRow.prototype.init = function() {
    return this.$(document).ready(((function(_this) {
      return function() {
        _this.filter_input = _this.$('#wodstar-filter-input');
        _this.search_input = _this.$('#wodstar-search-input');
        _this.results_display = _this.$('#main .avada-row');
        _this.posts_container = _this.$('#posts-container');
        _this.pagination_container = _this.$('.pagination');
        return _this.addListeners();
      };
    })(this)));
  };

  WodstarSearchRow.prototype.addListeners = function() {
    this.filter_input.change((function(_this) {
      return function(e) {
        if (e.target.value !== '') {
          return _this.getQuery(e.target.value, null);
        }
      };
    })(this));
    return this.search_input.keyup((function(_this) {
      return function(e) {
        if (e.target.value.length > 2) {
          if (_this.search_query) {
            clearTimeout(_this.search_query);
          }
          return _this.search_query = setTimeout(function() {
            var params, query;
            query = document.location;
            params = {
              s: encodeURI(e.target.value)
            };
            return _this.getQuery(query, params);
          }, 500);
        }
      };
    })(this));
  };

  WodstarSearchRow.prototype.getQuery = function(url, params) {
    this.$('#posts-container').infinitescroll('destroy');
    if (this.use_ajax) {
      return this.$.get(url, params).done(((function(_this) {
        return function(data) {
          var ajax_dom, display_posts, grid_width, pagination;
          ajax_dom = _this.$(data);
          display_posts = ajax_dom.find('.post');
          pagination = ajax_dom.find('.pagination');
          _this.display_flexsliders = _this.results_display.find('.flexslider');
          grid_width = Math.floor(100 / 3 * 100) / 100 + '%';
          console.log(pagination);
          _this.pagination_container.html(pagination.html());
          return _this.posts_container.html('').append(display_posts).find('.post').flexslider().imagesLoaded().done(function() {
            _this.posts_container.isotope('destroy');
            _this.isotopeify();
            return _this.infilineScrollify();
          });
        };
      })(this)));
    } else {
      return document.location = url;
    }
  };

  WodstarSearchRow.prototype.isotopeify = function() {
    if (jQuery().isotope) {
      return jQuery('.grid-layout').each(function() {
        var columns, grid_width, i;
        columns = 2;
        i = 0;
        while (i < 7) {
          if (jQuery(this).hasClass('grid-layout-' + i)) {
            columns = i;
          }
          i++;
        }
        grid_width = Math.floor(100 / columns * 100) / 100 + '%';
        jQuery(this).find('.post').css('width', grid_width);
        jQuery(this).isotope({
          layoutMode: 'masonry',
          itemSelector: '.post',
          transformsEnabled: false,
          isOriginLeft: jQuery('body.rtl').length ? false : true,
          resizable: true
        });
        if ((jQuery(this).hasClass('grid-layout-4') || jQuery(this).hasClass('grid-layout-5') || jQuery(this).hasClass('grid-layout-6')) && Modernizr.mq('only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)')) {
          grid_width = Math.floor(100 / 3 * 100) / 100 + '%';
          jQuery(this).find('.post').css('width', grid_width);
          jQuery(this).isotope({
            layoutMode: 'masonry',
            itemSelector: '.post',
            transformsEnabled: false,
            isOriginLeft: jQuery('body.rtl').length ? false : true,
            resizable: true
          });
        }
        console.log('isotopeify');
      });
    }
  };

  WodstarSearchRow.prototype.infilineScrollify = function() {
    console.log('infinitescrollify');
    return this.posts_container.infinitescroll({
      navSelector: 'div.pagination',
      nextSelector: 'a.pagination-next',
      itemSelector: 'div.post, .timeline-date',
      loading: {
        finishedMsg: js_local_vars.infinite_finished_msg,
        msg: jQuery('<div class="loading-container"><div class="loading-spinner"><div class="spinner-1"></div><div class="spinner-2"></div><div class="spinner-3"></div></div><div class="loading-msg">' + js_local_vars.infinite_blog_text + '</div>')
      },
      errorCallback: function() {
        if (jQuery('#posts-container').hasClass('isotope')) {
          return jQuery('#posts-container').isotope();
        }
      }
    });
  };

  return WodstarSearchRow;

})();

wodstar_sw = new WodstarSearchRow(jQuery);

wodstar_sw.init();
