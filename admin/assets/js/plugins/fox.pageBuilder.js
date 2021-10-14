(function($) {

  $.fn.pageBuilder = function(options) {

    function uniqid(prefix = "", random = false) {
      const sec = Date.now() * 1000 + Math.random() * 1000;
      const id = sec.toString(16).replace(/\./g, "").padEnd(14, "0");
      return `${prefix}${id}${random ? `.${Math.trunc(Math.random() * 100000000)}`:""}`;
    };

    var elements = new Array();
    elements['actions'] = `
      <div class="btn">
        <a class="cor"><i class="fas fa-tint"></i></a>
        <a class="edit"><i class="fas fa-pen-alt"></i></a>
        <a class="remove"><i class="fas fa-trash"></i></a>
      </div>`;

    elements['carousel'] = `<div class="element" data-empty="true" data-type="carousel"></div>`;
    elements['text'] = `<div class="element" data-empty="true" data-type="text"></div>`;
    elements['image'] = `<div class="element" data-empty="true" data-type="image"></div>`;
    elements['video'] = `<div class="element" data-empty="true" data-type="video"></div>`;
    elements['page'] = `<div class="page"></div>`;

    elements['modal'] = new Array();
    elements['modal']['modal'] = `
      <div class="modal fade" id="modal_edit" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary salvar">Salvar</button>
            </div>
          </div>
        </div>
      </div>`;

    elements['modal']['ordem'] = { 
      "html": `<div class="form-group w-25 mx-auto">
                <input type="number" class="form-control">
              </div>`,
      "title": 'Editar ordem'
    }

    elements['modal']['carousel'] = {
      "html": `<div class="form-group text-center">
                <input type="file" multiple>
              </div>`,
      "title": 'Editar carousel de imagens'
    }

    elements['modal']['text'] = { 
      "html": `<div class="form-group">
                <textarea></textarea>
              </div>`,
      "title": 'Editar texto'
    }

    elements['modal']['image'] = {
      "html": `<div class="form-group text-center">
                <input type="file">
              </div>`,
      "title": 'Editar imagem'
    }

    elements['modal']['video'] = {
      "html": `<div class="form-group">
                <input type="text" class="form-control" placeholder="URL">
              </div>`,
      "title": 'Editar vídeo'
    }
      

    return this.each(function() {
      var obj = new Object(); var count = 0;
      var $this = $(this);

      var settings = $.extend({
          // These are the defaults.
          color: "#556b2f",
          backgroundColor: "white"
      }, options );

      var toolbar = $this.find('.toolbar');
      var page = $this.find('.page');

      if (!toolbar.length) {
        console.error('pageBuilder: Toolbar não encontrada.');
        return false;
      }

      if (!page.length) {
        page = $(elements['page']).appendTo($this);
      }

      function mount(json = null) {
        let this_obj = obj;
        if (json !== null) {
          this_obj = json;
          for (let i of json) {
            obj[i['id']] = i;
          }
        }
        var ret = new Array();
        $.each(this_obj, function (i, val) { 
          if (val.val && val.val !== '') {
            let el = $(`.element[data-id="${i}"]`);
            if (!el.length) {
              el = $(elements[val.type]).appendTo('.page');
              el.attr('data-id', val.id);
            }
  
            el.attr('data-empty', "false");

            if (val.color !== false) {
              el.addClass(val.color);
            }
  
            switch (val.type) {
              case 'text':
                el.html('<div>' + val.val + '</div>');
              break;
              case 'carousel':
                let tmp_html = '';
                for (let img of val.val) {
                  tmp_html += '<img src="' + img + '" class="img-fluid" width="300">'
                }
                el.html(tmp_html);
              break;
              case 'image':
                el.html('<img src="' + val.val + '" class="img-fluid" width="300">');
              break;
              case 'video':
                el.html(`<a data-fancybox="gallery" href="${val.val}"><i class="fas fa-play-circle fa-4x"></i></a>`);
              break;
            }
  
            el.removeClass('focused');

            ret.push({
              "id": val.id, 
              "type": val.type,
              "val": val.val,
              "color": val.color
            });
          }
          $(settings.form).find($('input[name="obj"]')).val(JSON.stringify(ret));
        });
      }

      function remove(element) {
        let id = element.attr('data-id');
        delete obj[id];
        element.remove();
        mount();
      }

      function edit(element) {
        let id = element.attr('data-id');
        let f = element.attr('data-type');
        let modal = $(elements['modal']['modal']).appendTo($('body'));
        let input = $(elements['modal'][f].html).appendTo(modal.find('.modal-body'));
        modal.find('.modal-title').text(elements['modal'][f].title);

        
        input.find('textarea').summernote({
          height: 200,
          styleTags: [
            'p', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
          ]
        });
        
        if (f == 'text') {
          modal.find('.note-editable').html(element.find('div:not(.btn)').html());
        } else if (f == 'video') {
          modal.find('input').val(element.find('[data-fancybox]').attr('href'));
        }

        modal.modal('show');

        modal.on('hidden.bs.modal', function (e) {
          modal.remove();
        });

        modal.find('.salvar').on('click', function () {
          var res = null;
          switch (f) {
            case 'text':
              res = modal.find('.note-editable').html();
              res = res.replace("'", '');
              obj[id]['val'] = res;
            break;
            case 'carousel':
              var inp = modal.find('input')[0];
              let files = inp.files;
              let readers = new Array();
              for (let file of files) {
                readers.push(new FileReader());
                let reader_car = readers[readers.length - 1];

                reader_car.onload = function () {
                  var img = reader_car.result
                  obj[id]['val'].push(img);
                };
                reader_car.onerror = function (error) {
                  alert(error);
                };

                reader_car.readAsDataURL(file);
              }
            break;
            case 'image':
              var inp = modal.find('input')[0];
              var reader = new FileReader();
              reader.readAsDataURL(inp.files[0]);
              reader.onload = function () {
                var img = reader.result
                obj[id]['val'] = img;
              };
              reader.onerror = function (error) {
                alert(error);
              };
            break;
            case 'video':
              var res = modal.find('input').val();
              obj[id]['val'] = res;
            break;
          }
          
          modal.modal('hide');
          setTimeout(() => {
            mount();
          }, 500);
        });
      }

      function cor(element) {
        let id = element.attr('data-id');
        if (!element.hasClass('color-1') && !element.hasClass('color-2')) { 
          element.addClass('color-1');
          obj[id].color = 'color-1';
        } else if (element.hasClass('color-1')) { 
          element.removeClass('color-1').addClass('color-2');
          obj[id].color = 'color-2';
        } else if (element.hasClass('color-2')) { 
          element.removeClass('color-2');
          obj[id].color = false;
        } 
        mount();
      }
  
      function actions(element) {
        if (element.find('.btn').length) {
          element.find('.btn').remove();
        } else {
          let a = $(elements['actions']).appendTo(element);
  
          a.find('a').on('click', function () {
            let f = eval($(this).attr('class'));
            f.call(null, element);
          });
        }
      }
  
      function events() {
        $('.element').on('click', function () {
          actions($('.element.focused'));
          $('.element.focused').removeClass('focused');
          $(this).addClass('focused');
          actions($(this));
        });
      }

      toolbar.find('a').on('click', function () {
        let f = $(this).attr('class');
        let el = $(elements[f]).appendTo(page);
        let id = uniqid();
        let tmp = new Object();
        tmp.order = count;
        tmp.type = f;
        tmp.id = id;
        tmp.el = el
        tmp.color = false
        if (tmp.type == 'carousel') tmp.val = new Array();
        obj[id] = tmp;
        count++;
        el.attr('data-id', id);
        events();
      });

      if (settings.old_obj) {
        let json = settings.old_obj;
        console.log(json);
        mount(json);
        events();
      }

        

    });

  }

})(jQuery)