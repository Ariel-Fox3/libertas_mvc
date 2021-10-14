(function ($) {
  jQuery.fn.grupfy = function (param1, param2 = null) {
    var settings = null; var method = null;
    if (param2 !== null) {
      method = param1;
      settings = param2;
    } else {
      if (typeof param1 === 'object' && param1 !== null) {
        settings = param1;
      } else {
        method = param1;
      }
    }
    var config = {
      'host': 'http://beto.fox3.com.br/_sites/base27/api/request/'
      // 'host': 'http://localhost/live/base27/api/request/'
    };

    if (settings) jQuery.extend(config, settings);

    this.each(function () {

      function req(url) {
        let params = null;
        if (config.params) {
          params = config.params
        }
        $.post(config.host + url, params, function (res) {
          if (res.erro === false) {
            if (config.onComplete) {
              console.log(JSON.parse(res.msg));
              config.onComplete.call(null, JSON.parse(res.msg));
            }
          } else {
            console.log(res);
          }
        })
      }

      if (method === 'eventos') {
        req('eventos');
      } else if (method == 'noticias') {
        req('noticias');
      } else if (method == 'noticia') {
        req('noticia');
      }
    });

    return this;
  };
})(jQuery);