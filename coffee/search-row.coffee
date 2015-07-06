class WodstarSearchRow
  constructor: (jQuery) ->
    @$ = jQuery
    @use_ajax = true
    @search_query

  init: () ->
    @$(document).ready (=>
      @filter_input = @$('#wodstar-filter-input')
      @search_input = @$('#wodstar-search-input')
      @results_display = @$('#main .avada-row')
      @posts_container = @$('#posts-container')
      @pagination_container = @$('.pagination')
      @addListeners()
    )

  addListeners: () ->
    @filter_input.change (e) =>
      if e.target.value != '' then @getQuery(e.target.value, null)

    @search_input.keyup (e) =>
      if e.target.value.length > 2
        if @search_query then clearTimeout @search_query
        @search_query = setTimeout(=>
          query = document.location
          params =
            s:  encodeURI e.target.value
          @getQuery(query, params)
        , 500)

  getQuery: (url, params) ->
    @$('#posts-container').infinitescroll('destroy')
    if @use_ajax
      @$.get(url, params)
      .done ((data) =>
        ajax_dom = @$(data)
        display_posts = ajax_dom.find('.post')
        pagination = ajax_dom.find('.pagination')
        @display_flexsliders = @results_display.find('.flexslider')
        grid_width = Math.floor( 100 / 3 * 100 ) / 100  + '%';
        console.log pagination
        @pagination_container.html(pagination.html())
        @posts_container
        .html('')
        .append display_posts
        .find('.post')
        .flexslider()
        .imagesLoaded()
        .done(() =>
          @posts_container.isotope 'destroy'
          @isotopeify()
          @infilineScrollify()
        )
      )
    else
      document.location = url

  isotopeify: () ->
    if jQuery().isotope
      jQuery('.grid-layout').each ->
        columns = 2
        i = 0
        while i < 7
          if jQuery(this).hasClass('grid-layout-' + i)
            columns = i
          i++
        grid_width = Math.floor(100 / columns * 100) / 100 + '%'
        jQuery(this).find('.post').css 'width', grid_width
        jQuery(this).isotope
          layoutMode: 'masonry'
          itemSelector: '.post'
          transformsEnabled: false
          isOriginLeft: if jQuery('body.rtl').length then false else true
          resizable: true
        if (jQuery(this).hasClass('grid-layout-4') or jQuery(this).hasClass('grid-layout-5') or jQuery(this).hasClass('grid-layout-6')) and Modernizr.mq('only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)')
          grid_width = Math.floor(100 / 3 * 100) / 100 + '%'
          jQuery(this).find('.post').css 'width', grid_width
          jQuery(this).isotope
            layoutMode: 'masonry'
            itemSelector: '.post'
            transformsEnabled: false
            isOriginLeft: if jQuery('body.rtl').length then false else true
            resizable: true

        console.log 'isotopeify'
        return

  infilineScrollify: () ->
    @posts_container.infinitescroll
      navSelector: 'div.pagination'
      nextSelector: 'a.pagination-next'
      itemSelector: 'div.post, .timeline-date'
      loading:
        finishedMsg: js_local_vars.infinite_finished_msg
        msg: jQuery('<div class="loading-container"><div class="loading-spinner"><div class="spinner-1"></div><div class="spinner-2"></div><div class="spinner-3"></div></div><div class="loading-msg">'+js_local_vars.infinite_blog_text+'</div>')
      errorCallback: () ->
        if jQuery('#posts-container').hasClass('isotope')
          jQuery('#posts-container').isotope()

wodstar_sw = new WodstarSearchRow(jQuery)
wodstar_sw.init()