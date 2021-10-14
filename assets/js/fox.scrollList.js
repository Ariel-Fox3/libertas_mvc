(function ($) {

  $.fn.scrollList = function (options) {
    function _quit(msg) {
      console.error('scrollList: ' + msg);
      return false;
    }

    return $(this).each(function () {

      var list = $(this);

      var onClickItem = null;
      var vertical = false;
      if (typeof options.seletor === typeof undefined) _quit('seletor invalido');
      if (typeof options.buttonPrev === typeof undefined) _quit('buttonPrev invalido');
      if (typeof options.buttonNext === typeof undefined) _quit('buttonNext invalido');
      if (typeof options.onClickItem !== typeof undefined) onClickItem = options.onClickItem;
      if (typeof options.vertical !== typeof undefined && options.vertical === true) vertical = true;

      var itemSelector = options.seletor;
      var buttonPrev = options.buttonPrev;
      var buttonNext = options.buttonNext;

      let items = list.find(itemSelector);

      let stateButtons = function(tipo) {
        if (vertical === false) {
          let width_items = $(items[0]).outerWidth();
          let count_items = items.length;
          let width_list = count_items * width_items;
          let shown_items = list.outerWidth() / width_items;
    
          if (list.scrollLeft() >= width_list - (shown_items * width_items)) {
            buttonNext.removeClass('active');
          } else {
            if (!buttonNext.hasClass('active')) {
              buttonNext.addClass('active');
            }
          }
    
          if (list.scrollLeft() === 0) {
            buttonPrev.removeClass('active');
          } else {
            if (!buttonPrev.hasClass('active')) {
              buttonPrev.addClass('active');
            }
          }
        } else {
          let height_items = $(items[0]).outerHeight();
          let count_items = items.length;
          let height_list = count_items * height_items;
          let shown_items = list.outerHeight() / height_items;
    
          if (list.scrollTop() >= height_list - (shown_items * height_items)) {
            buttonNext.removeClass('active');
          } else {
            if (!buttonNext.hasClass('active')) {
              buttonNext.addClass('active');
            }
          }
    
          if (list.scrollTop() === 0) {
            buttonPrev.removeClass('active');
          } else {
            if (!buttonPrev.hasClass('active')) {
              buttonPrev.addClass('active');
            }
          }
        }
      }
  
      $(buttonNext).on('click', function () {
        if ($(this).hasClass('active')) {
          if (vertical === false) {
            let w = $(items[0]).outerWidth();
            let l = list.scrollLeft();
            list.animate({ 
              scrollLeft: l + w 
            }, 500, function() {
              stateButtons();
            });
          } else {
            let h = $(items[0]).outerHeight();
            let l = list.scrollTop();
            list.animate({ 
              scrollTop: l + h 
            }, 500, function() {
              stateButtons();
            });
          }
        }
      });
  
      $(buttonPrev).on('click', function () {
        if ($(this).hasClass('active')) {
          if (vertical === false) {
            let w = $(items[0]).outerWidth();
            let l = list.scrollLeft();
            list.animate({ 
              scrollLeft: l - w 
            }, 500, function() {
              stateButtons();
            });
          } else {
            let h = $(items[0]).outerHeight();
            let l = list.scrollTop();
            list.animate({ 
              scrollTop: l - h 
            }, 500, function() {
              stateButtons();
            });
          }
        }
      });
  
      $(list).on('scroll', function () {
        stateButtons();
      });
  
      if (onClickItem !== null) {
        list.find('li').on('click', function () {
          onClickItem($(this));
        });
      }
    });
  }

})(jQuery);