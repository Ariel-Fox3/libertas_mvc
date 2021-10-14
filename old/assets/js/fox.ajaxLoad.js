(function($) {

  $.fn.ajaxLoad = function(options) {
    /*
      options: 
        loading: element to insert icon
        url: required | url ajax
        params: optional | object
        maxHeight: defult: 500
        onComplete: function
        
    */
    var running = false;
    
    function _request(url, params, container) {
      let loading = `
        <div class="ajax_loading" style="width: 100%; height: 40px; display: flex; justify-content: center; align-items: center; font-size: 40px;">
          <i class="fas fa-spin fa-spinner"></i>
        </div>
        `;
      var load = $(loading).appendTo(container);
      $.post(url, params, function (res) {
        $(container).append(res);
        $(container).trigger('ajax.complete');
        load.remove();
      });
    }

    return this.each(function() {
      var $this = $(this);
      

      if (options.url === undefined || options.url === '') {
        console.error('ajaxLoad: você precisa especificar a URL do ajax.');
        return false;
      }
    
      if (options.loading === undefined) options.loading = false;
      if (options.debug === undefined) options.debug = false;
      if (options.limit === undefined) options.limit = 10;
    
      if (options.params === undefined) {
        options.params = new Object;
      } else {
        var arr = new Object;
        $.each(options.params, function (i, v) { 
          if (v !== 'null' && v !== '') {
            arr[i] = v;
          }
        });
        options.params = arr;
      }
      
      options.params.index = 0;
      options.params.limit = options.limit;

      let evListener = $(window);
      let changed = false;
      if (options.customEventListener !== undefined) {
        evListener = $(options.customEventListener);
        changed = true;
      }

      evListener.on('scroll', function () {

        if (running === false) {
          if (options.debug === true) console.log('scroll');
          if (changed == false) {
            if($(window).scrollTop() + $(window).height() >= $(document).height() - $('footer').outerHeight()) {
              
              if (options.debug === true) console.log('end reached');
              _request(options.url, options.params, $this);
              running = true;

            }
          } else {
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
              
              if (options.debug === true) console.log('end reached');
              _request(options.url, options.params, $this);
              running = true;

            }
          }
        }
      });

      $this.on('ajax.complete', function () {
        if (options.debug === true) console.log('append');
        running = false;
        options.params.index = options.params.index + 1;
        if (options.onComplete) {
          options.onComplete.call(this, $this);
        }
        if (options.debug === true) console.log(options.params.index);
      });

      _request(options.url, options.params, $this);

    });

  }

})(jQuery)