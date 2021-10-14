var Beto = {

  changeHashUrl: function(url, newHash) {
    let n_url = url.split('#');
    url = n_url[0] + '#' + newHash;
    return url;
  },

  delHashUrl: function(sourceURL, key) {
    let hash = sourceURL.split('#');
    if (hash.length > 1) {
        sourceURL = hash[0];
        hash = hash[1]
    } else {
        hash = null;
    }
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    if (hash != null) rtn = rtn + '#' + hash;
    return rtn;
  },

  addHashUrl: function(url, param, value) {
    var hash = {};
    var parser = document.createElement('a');

    parser.href = url;

    var parameters = parser.search.split(/\?|&/);

    for (var i = 0; i < parameters.length; i++) {
        if (!parameters[i])
            continue;

        var ary = parameters[i].split('=');
        hash[ary[0]] = ary[1];
    }

    hash[param] = value;

    var list = [];
    Object.keys(hash).forEach(function(key) {
        list.push(key + '=' + hash[key]);
    });

    parser.search = '?' + list.join('&');
    return parser.href;
  },

  notify: function (type, title = null, msg = null, duration = 7000) {
    let message = '';
    if (title !== null && title !== 'null' && title !== '') message = title;
    if (title !== null && title !== 'null' && title !== '' && msg !== null && msg !== 'null' && msg !== '') message += '<br>';
    if (msg !== null && msg !== 'null' && msg !== '') message += msg;

    const notyf = new Notyf({
        types: [
          {
            type: 'info',
            background: 'var(--info)',
            className: 'bg-info',
            icon: '<i class="fas fa-info"></i>'
          }
        ]
      });

    notyf.open({
        type: type,
        message: message,
        duration: duration,
        ripple: true,
        dismissible: true,
        position: {
            x: 'right',
            y: 'bottom'
        }
    });
  }

};