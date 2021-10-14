<?php 
include("../class/class.Conexao.php");
include("../class/class.Categoria_ProdutoVO.php");
include("../class/DAO/class.Categoria_ProdutoDAO.php");
include("../class/class.Func.php");

$categoria_produtoDAO = new Categoria_ProdutoDAO();

?>
<script>
$('document').ready(function() {
  $('a.removeCategoria').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('categoria_produto/ajax/ajax_del.php', {
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
  $('a.desativaCategoria').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('categoria_produto/ajax/ajax_desativa.php', {
      id: del_id
    }, function(data) {
      alert(data);
      location.reload();
    });
  });

  $(function() {
    $("#tabela-categorias").sortable({
      items: 'tbody tr',
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
      start: function(event, ui) {
        $(ui.helper).toggleClass("highlight");
        $(ui.item[0]).show().css('opacity', '0');
      },
      stop: function(event, ui) {
        $(ui.helper).toggleClass("highlight");
        $(ui.item[0]).css('opacity', '1');
        var itemOrder = $('#tabela-categorias').sortable("toArray");
        var arr = [];
        for (var i = 0; i < itemOrder.length; i++) {
          arr.push(itemOrder[i]);
        }
        var strval = arr.join(',');

        $.ajax({
          url: "categoria_produto/ajax/ajax_categoria_order.php",
          type: 'post',
          data: {
            str: strval
          },
          beforeSend: function() {
            $("#ordem-salva").fadeOut();
          }
        })
        .done(function(msg) {
          $("#ordem-salva").fadeIn();
        })
        .fail(function(jqXHR, textStatus, msg) {
          alert(msg);
        });
        }
    });
    $("#tabela-categorias").disableSelection();

  });
});
</script>

<div class="card">
  <div class="card-header">Gerenciar categorias de produtos</div>
  <div class="card-body">
    <ul class="list-inline">
      <li class="list-inline-item">
        <a href="?pg=novo&lc=categoria_produto" class="btn btn-labeled btn-success">
          <span class="btn-label"><i class="fas fa-plus"></i></span>
          Adicionar nova categoria
        </a>
      </li>
    </ul>
    <span id="ordem-salva" class="float-right text-success" style="display: none"><i class="fas fa-check"></i> Salvo</span>
    <table class="table table-striped table-bordered table-hover" id="tabela-categorias">
      <thead>
        <tr>
          <th width="25px"></th>
          <th class="text-center">Imagem</th>
          <th class="text-center">Nome</th>
          <th class="text-center">Ativo</th>
          <th class="text-center">Opções</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $listCategorias = $categoria_produtoDAO->getAll();
          if(sizeof($listCategorias) > 0) {
            foreach($listCategorias as $objVo) {
              printf('<tr class="gradeU %s" id="%s">', $objVo->getAtivo() == '1' ? '' : 'bg-warning', $objVo->getIdcategoria_produto());
              printf('<td><a class="btn btn-secondary text-white btn-move"><i class="fas fa-arrows-alt-v"></i></a></td>');
              if ($objVo->getImagem() != '') {
                printf('<td width="100" class="text-center"><img src="../../uploads/%s" width="100"></td>', $objVo->getImagem());
              } else {
                printf('<td></td>');
              }
              printf('<td class="text-center">%s</td>', $objVo->getNome());
              printf('<td class="text-center">%s</td>', $objVo->getAtivo() ? "<i class='fas fa-check'>" : "não"); 
              printf('<td class="text-center">');
                printf('<a href="?pg=alterar&lc=categoria_produto&id=%s" class="btn btn-dark" data-toggle="tooltip" alt="Editar categoria" title="Editar categoria"><i class="fas fa-pencil-alt"></i></a> ', $objVo->getIdcategoria_produto());
                if ($admin == '1') {
                  printf('<a href="#" class="btn btn-danger removeCategoria" id="%s" data-toggle="tooltip" alt="Remover categoria" title="Remover categoria"><i class="fas fa-trash"></i></a> ', $objVo->getIdcategoria_produto());
                }
                printf('<a href="#" class="btn btn-%s desativaCategoria" id="%s" data-toggle="tooltip" alt="Desativar categoria" title="Desativar categoria"><i class="fas fa-%s"></i></a> ', $objVo->getAtivo() == '1' ? 'warning' : 'success', $objVo->getIdcategoria_produto(), $objVo->getAtivo() == '1' ? 'times' : 'check');
              printf('</td>');
              printf('</tr>');
            }
          }
        ?>
      </tbody>
    </table>
  </div>
</div>