var Libertas = {
  // infinite_row: function (el) {
  //   el = $(el);
  //   el.addClass('infinite-el');
  //   var row = el.children('.row');

  //   row.addClass('infinite-row');
  //   let btn_prev = `<a href="javascript:;" class="control prev"><i class="fas fa-chevron-left"></i></a>`;
  //   let btn_next = `<a href="javascript:;" class="control next active"><i class="fas fa-chevron-right"></i></a>`;

  //   var items = row.children('div');

  //   var prev = $(btn_prev).prependTo(el);
  //   var next = $(btn_next).appendTo(el);

  //   let stateButtons = function(tipo) {
  //     let width_items = $(items[0]).outerWidth();
  //     let count_items = items.length;
  //     let width_list = parseInt(count_items) * parseInt(width_items);
  //     let shown_items = row.outerWidth() / width_items;
    
  //     if (row.scrollLeft() === width_list - (shown_items * width_items)) {
  //       next.removeClass('active');
  //     } else {
  //       if (!next.hasClass('active')) {
  //         next.addClass('active');
  //       }
  //     }

  //     if (row.scrollLeft() === 0) {
  //       prev.removeClass('active');
  //     } else {
  //       if (!prev.hasClass('active')) {
  //         prev.addClass('active');
  //       }
  //     }
  //   }

  //   $(next).on('click', function () {
  //     if ($(this).hasClass('active')) {
  //       let w = $(items[0]).outerWidth();
  //       let l = row.scrollLeft();
  //       row.animate({ 
  //         scrollLeft: l + w 
  //       }, 500, function() {
  //         stateButtons();
  //       });
  //     }
  //   });

  //   $(prev).on('click', function () {
  //     if ($(this).hasClass('active')) {
  //       let w = $(items[0]).outerWidth();
  //       let l = row.scrollLeft();
  //       row.animate({ 
  //         scrollLeft: l - w 
  //       }, 500, function() {
  //         stateButtons();
  //       });
  //     }
  //   });

  //   $(row).on('scroll', function () {
  //     stateButtons();
  //   });
    

  // }

  infinite_row: function (el, onChange = null) {
    el = $(el);
    el.addClass('infinite-el');
    var row = el.children('.row');
    var last_click = '';

    row.addClass('infinite-row');
    let btn_prev = `<a href="javascript:;" class="control prev"><i class="fas fa-chevron-left"></i></a>`;
    let btn_next = `<a href="javascript:;" class="control next active"><i class="fas fa-chevron-right"></i></a>`;

    var items = row.children('div');

    var prev = $(btn_prev).prependTo(el);
    var next = $(btn_next).appendTo(el);

    let stateButtons = function(tipo) {
      let width_items = $(items[0]).outerWidth();
      let count_items = items.length;
      let width_list = parseInt(count_items) * parseInt(width_items);
      let shown_items = row.outerWidth() / width_items;
    
      if (row.scrollLeft() === width_list - (shown_items * width_items)) {
        next.removeClass('active');
      } else {
        if (!next.hasClass('active')) {
          next.addClass('active');
        }
      }

      if (row.scrollLeft() === 0) {
        prev.removeClass('active');
      } else {
        if (!prev.hasClass('active')) {
          prev.addClass('active');
        }
      }

      $(document).unbind('infiniterow.change');
        $(document).on('infiniterow.change', function () {
          if (onChange !== null) {
            onChange.call(null, row, last_click);
            last_click = '';
          }
        });
    }

    $(next).on('click', function () {
      if ($(this).hasClass('active')) {
        let w = $(items[0]).outerWidth();
        let l = row.scrollLeft();
        row.animate({ 
          scrollLeft: l + w 
        }, 500, function() {
          last_click = 'next';
          $(document).trigger('infiniterow.change');
          stateButtons();
        });
      }
    });

    $(prev).on('click', function () {
      if ($(this).hasClass('active')) {
        let w = $(items[0]).outerWidth();
        let l = row.scrollLeft();
        row.animate({ 
          scrollLeft: l - w 
        }, 500, function() {
          last_click = 'prev';
          $(document).trigger('infiniterow.change');
          stateButtons();
        });
      }
    });

    $(row).on('scroll', function () {
      stateButtons();
    });
    

  }
}