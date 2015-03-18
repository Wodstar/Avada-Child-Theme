class WodstarSearchRow
  constructor: (jQuery) ->
    @$ = jQuery
    @use_ajax = true

  init: () ->
    @$(document).ready (=>
      @filter_input = @$('#wodstar-filter-input')
      @search_input = @$('#wodstar-search-input')
      @results_display = @$('#main .avada-row')
      @addListeners()
    )

  addListeners: () ->
    @filter_input.change (e) =>
      if e.target.value != '' then @getQuery(e.target.value, null)

    @search_input.keyup (e) =>
      if e.target.value.length > 2
        query = document.location
        params =
          s:  encodeURI e.target.value
        @getQuery(query, params)

  getQuery: (url, params) ->
    @$('#posts-container').infinitescroll('destroy')
    if @use_ajax
      @$.get(url, params)
      .done ((data) =>
        ajax_dom = @$(data)
        display_dom = ajax_dom.find('#posts-container')
        display_posts = ajax_dom.find('.post')
        @results_display.html display_dom
        @display_flexsliders = @results_display.find('.flexslider')
        @posts_container = @results_display.find('#posts-container')
        @results_display.imagesLoaded()
        .done(() =>
          @grid_width = Math.floor((100 / 3)) + '%'

          @display_flexsliders.flexslider
            start: (el) =>
              el.parent().parent().css
                'width': @grid_width

          # @$('.isotope')
          # .isotope
          #   layoutMode: 'fitRows'
            # masonry:
            #   columnWidth: @grid_width
            #   gutter: 30

          @posts_container.isotope
            layoutMode: 'masonry'
            itemSelector: '.post'
            transformsEnabled: false
            resizable: true
            masonry:
              columnWidth: 33
              gutter: 30
          .isotope('layout')
        )
      )
    else
      document.location = url

wodstar_sw = new WodstarSearchRow(jQuery)
wodstar_sw.init()