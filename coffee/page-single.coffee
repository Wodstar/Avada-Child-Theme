class WodstarPageSingle
  constructor: (jQuery) ->
    @$ = jQuery
    @docElem = window.document.documentElement
    @scrollVal = 0
    @isRevealed = false
    @noscroll = false
    @isAnimating = false
    @page_title = @$('.page-title-container')
    @page_title_div = @$('.page-title')
    @container = @$('#main')
    @header_wrapper = @$('.header-wrapper')
    @background_image = @$('.post-slideshow>.slides>li>a>img')[0]
    @background_image_src = @$(@background_image).attr('src')
    @post_slideshow = @$('.post-slideshow')
    @trigger = undefined
    @keys = [32, 37, 38, 39, 40]
    @wheelIter = 0
    @animation_duration = 1200

  init: () =>
    @pageScroll = @scrollY()
    @noscroll = @pageScroll == 0
    @disableScroll()
    if @pageScroll
      @isRevealed = true
      @container.addClass 'notrans'
      @container.addClass 'modify'
      @page_title.addClass 'modify'

    window.addEventListener 'scroll', @scrollPage
    window.addEventListener 'resize', @headerResize

    @headerResize()
    @swapHeaderImage()
    @hideMainSlideshow()
    @insertButton()

  headerResize: () =>
    calculated_height = window.innerHeight - @header_wrapper.height()
    @page_title_div.css 'height', calculated_height + 'px'
    @page_title.show()

  swapHeaderImage: () ->
    @page_title.css 'background-image', 'url(' + @background_image_src + ')'

  hideMainSlideshow: () ->
    @post_slideshow.css('display', 'none')

  insertButton: () ->
    @page_title.append('<button class=\'trigger\' data-info="Click or Scroll"><span>Scroll Down</span></button>')
    @trigger = @$('.trigger')
    @trigger.click () =>
      @toggle 1

  preventDefault: (e) ->
    e = e || window.event
    if e.preventDefault
      e.preventDefault()
      e.returnValue = false

  keydown: (e) =>
    for key in @keys
      if event.keyCode == key
        @preventDefault(e)
        return

  touchmove: (e) =>
    @preventDefault(e)

  wheel: (e) =>
    # @touchmove(e)

  disableScroll: () ->
    window.onmousewheel = document.onmousewheel = @wheel
    document.onkeydown = @keydown
    document.body.ontouchmove = @touchmove

  enableScroll: () ->
    window.onmousewheel = document.onmousewheel = document.onkeydown = document.body.ontouchmove = null

  scrollY: () ->
    return window.pageYOffset || @docElem.scrollTop

  scrollPage: () =>
    @scrollVal = @scrollY()

    if @noscroll
      return false if @scrollVal < 0
      window.scrollTo 0,0

    if @container.hasClass 'notrans'
      @container.removeClass 'notrans'
      return false

    if @page_title.hasClass 'notrans'
      @page_title.removeClass 'notrans'
      return false

    if @isAnimating
      return false

    if @scrollVal <= 0 && @isRevealed
      @toggle 0
    else if @scrollVal > 0 && !@isRevealed
      @toggle 1

  toggle: (reveal) =>
    @isAnimating = true

    if reveal
      @container.addClass 'modify'
      @page_title.addClass 'modify'
    else
      @noscroll = true
      @disableScroll()
      @container.removeClass 'modify'
      @page_title.removeClass 'modify'

    setTimeout () =>
      @isRevealed = !@isRevealed
      @isAnimating = false
      if reveal
        @noscroll = false
        @enableScroll()
    , @animation_duration

wodstar_pm = new WodstarPageSingle(jQuery)
wodstar_pm.init()
