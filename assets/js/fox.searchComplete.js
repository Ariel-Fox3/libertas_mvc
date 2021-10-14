(function($) {

    $.fn.searchComplete = function(options) {
        /*
          options: 
            url,
            onComplete, deve retornar um array de li
            customUl
        */

        function render(el, arr) {
            var pos = $(el).offset();
            var h = $(el).outerHeight();
            var w = $(el).outerWidth();
            var pos_t = pos.top + h;
            var pos_l = pos.left;
            var ul = $(`ul[data-input="${$(el).attr('id')}"]`);
            if (ul.length == 0) {
                $('body').append(`<ul class="ul-search-complete" data-input="${$(el).attr('id')}">`);
                ul = $(`ul[data-input="${$(el).attr('id')}"]`);
                if (options.customUl !== undefined) {
                    if (options.customUl.addLeft !== undefined) pos_l += options.customUl.addLeft;
                    if (options.customUl.addTop !== undefined) pos_t += options.customUl.addTop;
                    if (options.customUl.addWidth !== undefined) w += options.customUl.addWidth;
                }
                ul.attr('style', 'position: absolute; left: ' + pos_l + 'px; top: ' + pos_t + 'px; width: ' + w + 'px; ');
            } else {
                ul.show();
            }
            ul.html('');
            if (!Array.isArray(arr)) {
                arr = JSON.parse(arr.trim());
            }
            if (arr.length > 0) {
                arr.forEach(li => {
                    ul.append(li);
                });
            }
            $('body').on('click', function(e) {
                if ((!ul.is(e.target) && ul.has(e.target).length === 0)) {
                    ul.hide();
                }

            });
        }

        var style = `
        <style>
            .ul-search-complete {
                padding: 0;
                list-style: none;
                background: white;
                z-index: 9999;
                max-height: 350px;
                overflow-y: auto;
            }
            
            .ul-search-complete li a {
                width: 100%;
                background: white;
                display: block;
                color: #000;
                padding: 2px 25px;
            }
            
            .ul-search-complete li a img {
                width: 30px;
                margin-right: 15px;
            }
            
            .ul-search-complete li a:hover {
                background: #000;
                color: #fff;
                text-decoration: none;
            }
            
            .ul-search-complete li a small {
                display: block;
            }
        </style>
        `
        $('body').append(style);

        return this.each(function() {
            var $this = $(this);

            if (!options.url) {
                console.error('searchComplete: você precisa especificar a url.');
                return false;
            }

            if (!options.onComplete) {
                console.error('searchComplete: você precisa especificar a função de sucesso.');
                return false;
            }

            
            $this.on('keyup', function () {
                var term = $(this).val();
                $.post(options.url, {
                    term: term
                }, function(res) {
                    var ret = options.onComplete(res);
                    render($this, ret);
                });
            });


        });

    }

})(jQuery)