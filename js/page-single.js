var WodstarPageSingle, wodstar_pm,
  __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

WodstarPageSingle = (function() {
  function WodstarPageSingle(jQuery) {
    this.toggle = __bind(this.toggle, this);
    this.scrollPage = __bind(this.scrollPage, this);
    this.wheel = __bind(this.wheel, this);
    this.touchmove = __bind(this.touchmove, this);
    this.keydown = __bind(this.keydown, this);
    this.headerResize = __bind(this.headerResize, this);
    this.init = __bind(this.init, this);
    this.$ = jQuery;
    this.docElem = window.document.documentElement;
    this.scrollVal = 0;
    this.isRevealed = false;
    this.noscroll = false;
    this.isAnimating = false;
    this.page_title = this.$('.page-title-container');
    this.page_title_div = this.$('.page-title');
    this.container = this.$('#main');
    this.header_wrapper = this.$('.header-wrapper');
    this.background_image = this.$('.post-slideshow>.slides>li>a>img')[0];
    this.background_image_src = this.$(this.background_image).attr('src');
    this.post_slideshow = this.$('.post-slideshow');
    this.trigger = void 0;
    this.keys = [32, 37, 38, 39, 40];
    this.wheelIter = 0;
    this.animation_duration = 1200;
  }

  WodstarPageSingle.prototype.init = function() {
    this.pageScroll = this.scrollY();
    this.noscroll = this.pageScroll === 0;
    this.disableScroll();
    if (this.pageScroll) {
      this.isRevealed = true;
      this.container.addClass('notrans');
      this.container.addClass('modify');
      this.page_title.addClass('modify');
    }
    window.addEventListener('scroll', this.scrollPage);
    window.addEventListener('resize', this.headerResize);
    this.headerResize();
    this.swapHeaderImage();
    this.hideMainSlideshow();
    return this.insertButton();
  };

  WodstarPageSingle.prototype.headerResize = function() {
    var calculated_height;
    calculated_height = window.innerHeight - this.header_wrapper.height();
    this.page_title_div.css('height', calculated_height + 'px');
    return this.page_title.show();
  };

  WodstarPageSingle.prototype.swapHeaderImage = function() {
    return this.page_title.css('background-image', 'url(' + this.background_image_src + ')');
  };

  WodstarPageSingle.prototype.hideMainSlideshow = function() {
    return this.post_slideshow.css('display', 'none');
  };

  WodstarPageSingle.prototype.insertButton = function() {
    this.page_title.append('<button class=\'trigger\' data-info="Click or Scroll"><span>Scroll Down</span></button>');
    this.trigger = this.$('.trigger');
    return this.trigger.click((function(_this) {
      return function() {
        return _this.toggle(1);
      };
    })(this));
  };

  WodstarPageSingle.prototype.preventDefault = function(e) {
    e = e || window.event;
    if (e.preventDefault) {
      e.preventDefault();
      return e.returnValue = false;
    }
  };

  WodstarPageSingle.prototype.keydown = function(e) {
    var key, _i, _len, _ref;
    _ref = this.keys;
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      key = _ref[_i];
      if (event.keyCode === key) {
        this.preventDefault(e);
        return;
      }
    }
  };

  WodstarPageSingle.prototype.touchmove = function(e) {
    return this.preventDefault(e);
  };

  WodstarPageSingle.prototype.wheel = function(e) {};

  WodstarPageSingle.prototype.disableScroll = function() {
    window.onmousewheel = document.onmousewheel = this.wheel;
    document.onkeydown = this.keydown;
    return document.body.ontouchmove = this.touchmove;
  };

  WodstarPageSingle.prototype.enableScroll = function() {
    return window.onmousewheel = document.onmousewheel = document.onkeydown = document.body.ontouchmove = null;
  };

  WodstarPageSingle.prototype.scrollY = function() {
    return window.pageYOffset || this.docElem.scrollTop;
  };

  WodstarPageSingle.prototype.scrollPage = function() {
    this.scrollVal = this.scrollY();
    if (this.noscroll) {
      if (this.scrollVal < 0) {
        return false;
      }
      window.scrollTo(0, 0);
    }
    if (this.container.hasClass('notrans')) {
      this.container.removeClass('notrans');
      return false;
    }
    if (this.page_title.hasClass('notrans')) {
      this.page_title.removeClass('notrans');
      return false;
    }
    if (this.isAnimating) {
      return false;
    }
    if (this.scrollVal <= 0 && this.isRevealed) {
      return this.toggle(0);
    } else if (this.scrollVal > 0 && !this.isRevealed) {
      return this.toggle(1);
    }
  };

  WodstarPageSingle.prototype.toggle = function(reveal) {
    this.isAnimating = true;
    if (reveal) {
      this.container.addClass('modify');
      this.page_title.addClass('modify');
    } else {
      this.noscroll = true;
      this.disableScroll();
      this.container.removeClass('modify');
      this.page_title.removeClass('modify');
    }
    return setTimeout((function(_this) {
      return function() {
        _this.isRevealed = !_this.isRevealed;
        _this.isAnimating = false;
        if (reveal) {
          _this.noscroll = false;
          return _this.enableScroll();
        }
      };
    })(this), this.animation_duration);
  };

  return WodstarPageSingle;

})();

wodstar_pm = new WodstarPageSingle(jQuery);

wodstar_pm.init();
