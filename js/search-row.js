var WodstarSearchRow, wodstar_sw;

WodstarSearchRow = (function() {
  function WodstarSearchRow(jQuery) {
    this.$ = jQuery;
    this.use_ajax = true;
    this.search_value;
    this.search_query;
  }

  WodstarSearchRow.prototype.init = function() {
    return this.$(document).ready(((function(_this) {
      return function() {
        _this.filter_input = _this.$('#wodstar-filter-input');
        _this.search_input = _this.$('#wodstar-search-input');
        _this.results_display = _this.$('#main .avada-row');
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
        _this.search_value = e.target.value;
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
          var ajax_dom, display_dom, display_posts;
          ajax_dom = _this.$(data);
          display_dom = ajax_dom.find('#posts-container');
          display_posts = ajax_dom.find('.post');
          _this.results_display.html(display_dom);
          _this.display_flexsliders = _this.results_display.find('.flexslider');
          _this.posts_container = _this.results_display.find('#posts-container');
          return _this.results_display.imagesLoaded().done(function() {
            _this.grid_width = Math.floor(100 / 3) + '%';
            _this.display_flexsliders.flexslider({
              start: function(el) {
                return el.parent().parent().css({
                  'width': _this.grid_width
                });
              }
            });
            return _this.posts_container.isotope({
              layoutMode: 'masonry',
              itemSelector: '.post',
              transformsEnabled: false,
              resizable: true,
              masonry: {
                columnWidth: 33,
                gutter: 30
              }
            });
          });
        };
      })(this)));
    } else {
      return document.location = url;
    }
  };

  return WodstarSearchRow;

})();

wodstar_sw = new WodstarSearchRow(jQuery);

wodstar_sw.init();
