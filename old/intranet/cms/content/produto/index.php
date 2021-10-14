<?php 
  include("../class/class.Conexao.php");
  include("../class/class.ProdutoVO.php");
  include("../class/DAO/class.ProdutoDAO.php");
  include("../class/class.Categoria_ProdutoVO.php");
  include("../class/DAO/class.Categoria_ProdutoDAO.php");
  include("../class/class.Func.php");

  // $img = generatePreview('../../video.mp4');
  $produtoDAO = new ProdutoDAO();
  $categoria_produtoDAO = new Categoria_ProdutoDAO();

  $idc = isset($_GET['idc']) && $_GET['idc'] != '' ? $_GET['idc'] : null;
?>

<div class="card">
  <div class="card-header">Gerenciar produtos</div>
  <div class="card-body">
    
    <form method="get">
      <input type="hidden" name="pg" value="index">
      <input type="hidden" name="lc" value="produto">
      <div class="form-group">
        <div class="row">
          <div class="col-4">
            <label for="idc">Categoria</label>
            <select data-live-search="true" name="idc" title="Selecione">
              <?php
                $listCategorias = $categoria_produtoDAO->getAll();
                if (sizeof($listCategorias) > 0) {
                  foreach ($listCategorias as $objVoC) {
                    printf('<option %s value="%s">%s</option>', $idc != null && $objVoC->getIdcategoria_produto() == $idc ? 'selected' : '', $objVoC->getIdcategoria_produto(), $objVoC->getNome());
                  }
                }
              ?>
            </select>
          </div>
          <div class="col-2 mt-auto">
            <button type="submit" class="btn btn-block btn-info">Filtrar</button>
          </div>
        </div>
      </div>
    </form>

    <ul class="list-inline">
      <li class="list-inline-item">
        <a href="?pg=novo&lc=produto<?=$idc != null ? "&idc=$idc" : ''?>" class="btn btn-labeled btn-success">
          <span class="btn-label"><i class="fas fa-plus"></i></span>
          Adicionar novo produto
        </a>
      </li>
    </ul>

    <style>

      #list_produtos {
        height: 70vh;
        overflow: auto;
      }

    </style>
    
    <span id="ordem-salva" class="float-right text-success" style="display: none"><i class="fas fa-check"></i> Salvo</span>
    <div id="list_produtos">
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th width="25px"></th>
            <th class="text-center">Opções</th>
            <th class="text-center">Imagem</th>
            <th class="text-center">Nome</th>
            <th class="text-center">Categoria</th>
            <th class="text-center">Ativo</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
$('document').ready(function() {

  $('#list_produtos tbody').ajaxLoad({
    url: 'produto/ajax/ajax_get_produtos.php', 
    debug: true,
    customEventListener: '#list_produtos',
    limit: 20,
    params: {
      idc: '<?=$idc?>',
    },
    onComplete: function (el) {
      // console.log(el);
      $('a.removeUsuario').click(function() {

        var del_id = $(this).attr('id');
        var parent = $(this).parent();
        $.post('produto/ajax/ajax_del.php', {
          id: del_id
        }, function(data) {

          if (data == '') {
            parent.slideUp('slow', function() {
              //$(this).remove();
              //$(this).remove();
              alert("Removido com sucesso!");
              $(this).closest('tr').remove();

            });
          } else {
            alert(data);
          }
        });
      });
      $('a.desativaUsuario').click(function() {

        var del_id = $(this).attr('id');
        var parent = $(this).parent();
        $.post('produto/ajax/ajax_desativa.php', {
          id: del_id
        }, function(data) {
          alert(data);
          location.reload();
        });
      });

      $( function() {
        $( "#tabela-aulas" ).sortable({
          items: 'tr',
          axis: 'y',
          cursor: 'n-resize',
          helper: 'clone',
          zIndex: 9999,
          placeholder: {
            element: function(currentItem) {
              return $(`<div class="drop-placeholder">
                        <div class="borda">
                          <span><i class="fas fa-clone"></i></span>
                        </div>
                      </div>`)[0];
            },
            update: function(container, p) {
              return;
            }
          },
          start: function (event, ui) {
              $(ui.helper).toggleClass("highlight");
              $(ui.item[0]).show().css('opacity','0');
          },
          stop: function (event, ui) {
            $(ui.helper).toggleClass("highlight");
            $(ui.item[0]).css('opacity','1');
            var itemOrder = $('#tabela-aulas').sortable("toArray");
            var arr = [];
            // console.log(ui);
            for (var i = 0; i < itemOrder.length; i++) {
              // console.log("Position: " + i + " ID: " + itemOrder[i]);
              arr.push(itemOrder[i]);
            }
            var strval = arr.join(',');
            
            $.ajax({
              url : "produto/ajax/ajax_produto_order.php",
              type : 'post',
              data : {
                str: strval
              },
              beforeSend : function(){
                $("#ordem-salva").fadeOut();
              }
            })
            .done(function(msg){
              $("#ordem-salva").fadeIn();
            })
            .fail(function(jqXHR, textStatus, msg){
                alert(msg);
            }); 
            // $(ui.item[0]).before(`<div class="drop-placeholder">
            //             <div class="borda">
            //               <span><i class="fas fa-clone"></i></span>
            //             </div>
            //           </div>`);
          }
        });
        $( "#tabela-aulas" ).disableSelection();
      });
    }
  });

});
</script>

