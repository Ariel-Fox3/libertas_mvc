var editors = [];

function getEditors() {
    return editors;
}

$(document).ready(function() {

    $('[data-toggle="popover"]').popover();

    $("#inputSearchHeader").keyup(function(e) {
        e.preventDefault();
        $(this).autocomplete({
                minLength: 1,
                source: function(request, response, e) {
                    $.post('pneu/ajax/ajax_search_header.php', {
                        term: $("#inputSearchHeader").val()
                    }, function(data) {
                        response(JSON.parse(data));
                    });
                },
                select: function(event, ui) {
                    $("#inputSearchHeader").val(ui.item.nome);
                    location.href = "?pg=alterar&lc=pneu&id=" + ui.item.id;
                    return false;
                }
            })
            .autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append(`<div class="w-100"><b class="pl-2">${item.nome}</b><small class="pl-2">${item.tipo}</small><br><small class="pl-3">Código: ${item.codigo} <span class="pl-3">Referência: ${item.ref}</span></small></div>`)
                    .appendTo(ul);
            };

    })

    $('select:not([class])').selectpicker({
        style: '',
        styleBase: 'form-control w-100'
    });

    $('.summernote').summernote({
        height: 200,
        styleTags: [
            'p', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
        ]
    });

    function imageHandler() {
        var _this = this;
        let fileInput = this.container.querySelector('input.ql-image[type=file]');
        if (fileInput == null) {
            fileInput = document.createElement('input');
            fileInput.setAttribute('type', 'file');
            fileInput.setAttribute('accept', 'image/png, image/gif, image/jpeg, image/bmp, image/x-icon');
            fileInput.classList.add('ql-image');
            fileInput.addEventListener('change', function () {
            if (fileInput.files != null && fileInput.files[0] != null) {
                var fData = new FormData();
                fData.append('img', fileInput.files[0]);
                $.ajax({
                    url: '_ajax/send_file.php',
                    type: 'POST',
                    processData: false, // important
                    contentType: false, // important
                    dataType : 'json',
                    data: fData
                }).done(function (res) {
                    var img = res.url;
                    img = `<img src="${img}" class="img-fluid" data-id="${res.id}"/>`;
                    
                    let rang = _this.quill.getSelection(true);

                    console.log(_this.quill);
                    console.log(rang);
                    _this.quill.insertEmbed(rang.index, 'image', res.url);
                    _this.quill.setSelection(rang.index + 1, Quill.sources.SILENT);
                    
                });
            }
            });
            this.container.appendChild(fileInput);
        }
        fileInput.click();
    }

    if (!window.Quill) {
        return $(".quill-editor,.quill-toolbar").remove();
    }
    var clearfix = $('.clearfix');
    for (var i = 0; i < clearfix.length; i++) {
        let atual_cl = $(clearfix[i]);
        let editor = $(atual_cl).find('.quill-editor');
        let toolbar = $(atual_cl).find('.quill-toolbar');
        let ed = new Quill(editor[0], {
            modules: {
                toolbar: {
                    container: toolbar[0],
                    handlers: {
                        'image': imageHandler
                    }
                }
            },
            placeholder: "Digite aqui...",
            theme: "snow"
        });
    
        function loop() {
            // let html = $(ed.container).children().html();
            let html = $(atual_cl).find('.ql-editor').html();
            // let clearfix = $(ed.container).parent()
            let n;
            if (atual_cl.attr('data-name')) {
                n = atual_cl.attr('data-name');
            } else {
                n = atual_cl.attr('name');
                atual_cl.attr('data-name', n).removeAttr('name');
            }
            let i = atual_cl.find('input[name="' + n + '"]');
            if (i.length == 0) {
                i = $(`<input type="text" class="d-none" name="${n}">`);
                atual_cl.append(i);
            }
            i.val('');
            i.val(html);
        }
        ed.on('editor-change', function(delta, oldDelta, source) {
            // console.log($(ed.container), atual_cl);
            loop();
            // console.log(clearfix, n, i);
        });
        loop();
        editors.push(ed);
    }

    $('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        language: 'pt-BR',
        todayHighlight: true,
        orientation: 'bottom'
    });

    $('.datepicker-up').datepicker({
        dateFormat: 'dd-mm-yy',
        language: 'pt-BR',
        todayHighlight: true,
        orientation: 'top'
    });

    // $('#datepicker').datepicker({
    //     orientation: 'bottom',
    //     todayHighlight: true,
    //     format: 'dd/mm/yyyy',
    //     dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
    //     dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    //     dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    //     monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    //     monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    //     // language: 'pt-BR'
    // });

    // $('#datepicker-ano').datepicker({
    //   maxViewMode: 4,
    //   minViewMode: 'years',
    //   format: 'yyyy'
    // });

    $('[data-toggle="tooltip"]').tooltip()
});


function removeAcento(text) {
    text = text.toLowerCase();
    text = text.replace(new RegExp('[ÁÀÂÃ]', 'gi'), 'a');
    text = text.replace(new RegExp('[ÉÈÊ]', 'gi'), 'e');
    text = text.replace(new RegExp('[ÍÌÎ]', 'gi'), 'i');
    text = text.replace(new RegExp('[ÓÒÔÕ]', 'gi'), 'o');
    text = text.replace(new RegExp('[ÚÙÛ]', 'gi'), 'u');
    text = text.replace(new RegExp('[Ç]', 'gi'), 'c');
    return text;
}

function UrlDelParam(sourceURL, key) {
    // var rtn = sourceURL.split("?")[0],
    //     param,
    //     params_arr = [],
    //     queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    // if (queryString !== "") {
    //     params_arr = queryString.split("&");
    //     for (var i = params_arr.length - 1; i >= 0; i -= 1) {
    //         param = params_arr[i].split("=")[0];
    //         if (param === key) {
    //             params_arr.splice(i, 1);
    //         }
    //     }
    //     rtn = rtn + "?" + params_arr.join("&");
    // }
    // return rtn;

    // http://localhost/live/oralbrasil/cms/content/index.php?pg=index&lc=financeiro#comissoes
    var params = sourceURL.split('?');
    var param_list = params[1].split('#');
    var param_arr = param_list[0].split('&');

    for (var i = 0; i < param_arr.length; i++) {
        var p = param_arr[i].split('=');
        if (p[0] == key) {
            param_arr.splice(i, 1);
        }
    }

    var url = params[0] + '?' + param_arr.join('&');
    if (param_list[1]) {
        url = url + '#' + param_list[1];
    }
    return url;


}

function UrlAddParam(url, param, value) {
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
}

function getBrowserSize() {
    var div = document.createElement('div');
    div.style.position = 'absolute';
    div.style.top = 0;
    div.style.left = 0;
    div.style.width = '100%';
    div.style.height = '100%';
    document.documentElement.appendChild(div);
    var results = {
        width: div.offsetWidth,
        height: div.offsetHeight
    };
    div.parentNode.removeChild(div); // remove the `div`
    return results;
}