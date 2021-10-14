(function( $ ) {

  let arrayCompare = function(a1, a2) {
    if (a1.length != a2.length) return false;
    var length = a2.length;
    for (var i = 0; i < length; i++) {
        if (a1[i] !== a2[i]) return false;
    }
    return true;
  }

  let inArray = function(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(typeof haystack[i] == 'object') {
            if(arrayCompare(haystack[i], needle)) return true;
        } else {
            if(haystack[i] == needle) return true;
        }
    }
    return false;
  }

  let close = function(popup, style = null, debug) {
    if (debug === true) console.info('removendo alert', popup);
    $(popup).find('.alert-popup').addClass('animate__backOutDown');
    setTimeout(() => {
      $(popup).fadeOut(500, function () {
        $(popup).remove();
        if (style != null) {
          $(style).remove();
        }
        if (debug === true) console.info('alert removido');
      });
    }, 500);
  }
  
  var elements = [];

  var html; var buttons; var style; var al;
  
  $.alertPopup = function(options) {

    let check = $('.alert-backdrop');
    if (check.length > 0) {
      console.error('já existe um alert aberto');
      return false;
    }

    style = `
    <style>
      .alert-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,.4);
      }
      .alert-popup {
        width: 80%;
        position: fixed;
        bottom: 15px;
        left: 10%;
        border-radius: 5px;
        box-shadow: 5px 5px 15px rgba(0,0,0,.3);
      }
      .alert-popup.hide, .alert-backdrop.hide {
        display: none;
      }
      .alert-popup.info { background-color: var(--info); }
      .alert-popup.warning { background-color: var(--warning); }
      .alert-popup.danger { background-color: var(--danger); }
      .alert-popup.success { background-color: var(--success); }
      .alert-popup.info h2, .alert-popup.info p, .alert-popup.info i, 
      .alert-popup.warning h2, .alert-popup.warning p, .alert-popup.warning i, 
      .alert-popup.danger h2, .alert-popup.danger p, .alert-popup.danger i, 
      .alert-popup.success h2, .alert-popup.success p, .alert-popup.success i {
        color: white;
      }
      .alert-popup .content {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        padding: 15px;
      }
      .alert-popup .content b {
        font-weight: 900;
      }
      .alert-popup .content .icon {
        width: 60px;
        height: 60px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        margin-right: 55px;
        margin-left: 40px;
      }
      .alert-popup .buttons {
        padding: 0 15px 15px 15px;
        text-align: center;
        margin-top: -20px;
      }
    </style>`;

    style = $(style).appendTo('body');

    var settings = $.extend({
      theme: 'info',
      icon: 'fas fa-exclamation fa-4x',
      title: 'Sucesso',
      text: 'Plugin instalado com sucesso!',
      buttons: [
        {
          text: 'Ok',
          class: 'btn btn-light',
          action: null
        }
      ],
      closeIcon: true,
      debug: true
    }, options );

    if (settings.debug === true) console.info('inicializando...');
    if (settings.debug === true) console.info(settings);

    html = `<div class="alert-backdrop" style="display: none">
              <div class="alert-popup ${settings.theme} animate__animated animate__backInUp hide">
                <div class="content">
                  <div class="icon">
                    <i class="${settings.icon}"></i>
                  </div>
                  <div>
                    <h2>${settings.title}</h2>
                    <p>${settings.text}</p>
                  </div>
                </div>
              </div>
            </div>`;
    html = $(html);
    if (settings.buttons.length > 0) {
      buttons = $('<div class="buttons"></div>').appendTo(html.find('.alert-popup'));
      for (let b of settings.buttons) {
        let btn = $(`<a href="javascript:;" class="${b.class}">${b.text}</a>`).appendTo(buttons);
        btn.on('click', function () {
          if (b.action !== null) {
            b.action.call(this, html);
          } else {
            close(html, style, settings.debug);
          }
        });
      }
    }

    al = html.appendTo('body');
    al.fadeIn(300, function () {
      al.find('.alert-popup').removeClass('hide');
      if (settings.debug === true) console.info('alert inserido', al);
    })
    // setTimeout(() => {
    //   al.find('.alert-popup').removeClass('hide');
    // }, 500);
  };

  $.fn.alertPopup = function(method) {
    if (method === 'close') {
      close(this, null, false);
    }
  }
}( jQuery ));