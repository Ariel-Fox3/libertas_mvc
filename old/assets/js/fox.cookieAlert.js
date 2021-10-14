(function($) {

  $.fn.cookieAlert = function(options) {
      /*
        options: 
          theme - required,
          cookie - required true|false (true),
          closeOnClick - required true|false (true),
          content - required string,
          decline - required true|false (true),
          textAccept - required string,
          textDecline - decline == true ? required string,
          urlAccept - optional,
          urlDecline - optional,
          onAccept - optional,
          onDecline - optional,
      */

      function _setCookie(name, value, days) {
        var d = new Date;
        d.setTime(d.getTime() + 24*60*60*1000*days);
        document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
      }

      function _getCookie(name) {
        var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
        return v ? v[2] : null;
      }

      function _deleteCookie(name) { _setCookie(name, '', -1); }

      function _close(cookie, el) {
        if (cookie === true) {
          _setCookie('privacy_fox_deisicon', '1', 30);
        }

        $(el).removeClass('show');
        
        setTimeout(() => {
          $('body').removeClass('show');
        }, 500);

        setTimeout(() => {
          $('body').removeClass('cookiealert-drop');
        }, 700);
      }

      return this.each(function() {
          var $this = $(this);

          if (options === 'resetCookies') {
            console.log('Zerando cookies no navegador...');
            _deleteCookie('privacy_fox_deisicon');
            console.log('Cookies limpos com sucesso!');
            return false;
          }

          if (_getCookie('privacy_fox_deisicon') !== null && _getCookie('privacy_fox_deisicon') == '1') {
            console.log('Você já aceitou a Política de privacidade deste site.');
            return false;
          }

          if (options.theme === undefined) {
            options.theme = {
              bg: '#000',
              textColor: '#fff',
              backdrop: true,
              classBtnAccept: 'btn-accept',
              classBtnDecline: 'btn-decline',
              contentPadding: '0',
            };
          } 

          var style = `
          body.cookiealert-drop:after {
            content: " ";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            transition: background-color .5s linear;
          }
          
          body.cookiealert-drop.show:after {
            background-color: rgba(0,0,0,.4);
          }

          .cookiealert {
            position: fixed;
            left: 0;
            bottom: -100%;
            width: 100%;
            min-height: 150px;
            background-color: ${options.theme.bg};
            z-index: 999999999;
            padding: 40px;
            transition: 1s ease;
          }

          .cookiealert.show {
            bottom: 0;
          }

          .cookiealert .content {
            color: ${options.theme.textColor};
            text-align: center;
            font-size: 13px;
            padding: ${options.theme.contentPadding};
          }

          .cookiealert .content .btn-accept {
            background-color: #4bc1e1;
            color: #fff;
            font-size: 14px;
            padding: 10px 40px;
          }

          .cookiealert .content .btn-decline {
            background-color: #8d999d;
            color: #fff;
            font-size: 12px;
            padding: 10px 40px;
            margin-right: 10px;
          }`;


          if (options.cookie === undefined) options.cookie = true;
          if (options.closeOnClick === undefined) options.closeOnClick = true;
          if (options.decline === undefined) options.decline = true;

          if (options.content === undefined) {
            console.error('cookieAlert: você precisa especificar o conteúdo do popup.');
            return false;
          }

          if (options.textAccept === undefined) {
            console.error('cookieAlert: você precisa especificar o texto do botão accept do popup.');
            return false;
          }

          if (options.decline === true && options.textDecline === undefined) {
            console.error('cookieAlert: você precisa especificar o texto do botão decline do popup.');
            return false;
          }

          $('body').append(`<style>${style}</style>`);

          if (options.theme.backdrop === true) {
            $('body').addClass('cookiealert-drop');
            setTimeout(() => {
              $('body').addClass('show');
            }, 300);
          }

          if (!$this.hasClass('cookiealert')) {
            $this.addClass('cookiealert');
          }
            
          let html_decline = '';
          if (options.decline === true) {
            html_decline = `<a href="${options.urlDecline !== undefined ? options.urlDecline : 'javascript:;'}" class="btn ${options.theme.classBtnDecline}">${options.textDecline}</a>`
          }
          let html = `
            <div class="content">
              <p>${options.content}</p>
              <div class="btns">
                ${options.decline === true ? html_decline : ''}
                <a href="${options.urlAccept !== undefined ? options.urlAccept : 'javascript:;'}" class="btn ${options.theme.classBtnAccept}">${options.textAccept}</a>
              </div>
            </div>
          `;

          var content = $(html).appendTo($this);

          content.find(`.${options.theme.classBtnAccept}`).on('click', function () {
            if (options.onAccept !== undefined && typeof options.onAccept === 'function') {
              options.onAccept();
            }
            _close(options.cookie, $this);
          });

          setTimeout(() => {
            $this.addClass('show');
          }, 500);


      });

  }

})(jQuery)