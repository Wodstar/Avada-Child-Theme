Main = (jQuery) ->
  if !jQuery then return

  $ = jQuery
  background_image = $('.post-slideshow>.slides>li>img')[0]
  background_image_src = $(background_image).attr('src')
  page_title_container = $('.page-title-container')
  header_wrapper = $('.header-wrapper')
  post_slideshow = $('.post-slideshow')

  # Calculate the height the div needs to be and set it.
  calculated_height = window.innerHeight - header_wrapper.height()
  page_title_container.css('height', calculated_height + 'px')

  # Set the background image to the first image in the post slideshow
  page_title_container.css('background-image', 'url(' + background_image_src + ')')

  # Hide the post slideshow
  post_slideshow.css('display', 'none')

  $(window).resize(() ->
    calculated_height = window.innerHeight - header_wrapper.height()
    page_title_container.css('height', calculated_height + 'px !important')
    )

  console.log 'Main loaded'

Main(jQuery);