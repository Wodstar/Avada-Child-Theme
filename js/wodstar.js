var WodstarMain, wodstar_mm;

WodstarMain = (function() {
  function WodstarMain(jQuery) {
    this.$ = jQuery;
    this.init();
  }

  WodstarMain.prototype.init = function() {
    this.login_link = this.$('.wodstar-modal-link a');
    this.login_link.attr('data-toggle', 'modal');
    this.login_link.attr('data-target', '#wodstarModal');
    return console.log(this.login_link);
  };

  return WodstarMain;

})();

wodstar_mm = new WodstarMain(jQuery);
