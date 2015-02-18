class WodstarMain
  constructor: (jQuery) ->
    @$ = jQuery
    @init()

  init: () ->
    @login_link = @$('.wodstar-modal-link a')
    @login_link.attr('data-toggle', 'modal')
    @login_link.attr('data-target', '#wodstarModal')
    console.log @login_link
    
wodstar_mm = new WodstarMain(jQuery)