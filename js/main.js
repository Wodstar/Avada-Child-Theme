var Main;

Main = function(jQuery) {
  var $, background_image, background_image_src, calculated_height, header_wrapper, page_title_container, post_slideshow;
  if (!jQuery) {
    return;
  }
  $ = jQuery;
  background_image = $('.post-slideshow>.slides>li>img')[0];
  background_image_src = $(background_image).attr('src');
  page_title_container = $('.page-title-container');
  header_wrapper = $('.header-wrapper');
  post_slideshow = $('.post-slideshow');
  calculated_height = window.innerHeight - header_wrapper.height();
  page_title_container.css('height', calculated_height + 'px');
  page_title_container.css('background-image', 'url(' + background_image_src + ')');
  post_slideshow.css('display', 'none');
  $(window).resize(function() {
    calculated_height = window.innerHeight - header_wrapper.height();
    return page_title_container.css('height', calculated_height + 'px !important');
  });
  return console.log('Main loaded');
};

Main(jQuery);
