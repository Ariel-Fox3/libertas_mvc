<?php
include("../class/class.Conexao.php");
include("../class/class.PaginaVO.php");
include("../class/DAO/class.PaginaDAO.php");
include("../class/class.ArquivoVO.php");
include("../class/DAO/class.ArquivoDAO.php");
$paginaDAO = new PaginaDAO();
$arquivoDAO = new ArquivoDAO();
$idpagina = $_GET['id'];
$pagina = $paginaDAO->getById($idpagina);

?>
<script>
$(document).ready(function() {

  var f = $('form');
  var b = $('#enviarProduto');
  var p = $('#resultado');

  b.click(function() {
    f.ajaxForm({
      beforeSend: function() {
        b.attr('disabled', 'disabled');
        p.fadeOut();
      },
      success: function(e) {
        b.removeAttr('disabled');
        p.html(e).fadeIn();
        location.reload();
      },
      error: function(e) {
        b.removeAttr('disabled');
        alert(e);
      }
    });
  });

  $('a.removeArquivo').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('pagina/ajax/ajax_arquivo_del.php', {
      id: del_id
    }, function(data) {

      if (data == '') {
        location.reload();
      } else {
        alert(data);
      }
    });
  });

});
</script>

<div class="card">
  <div class="card-header">Anexar imagens (<b><?=$pagina->getNome()?></b>)</div>
  <div class="card-body">
    <form id="formArquivo" action="pagina/ajax/ajax_arquivo.php" name="formCadastro" method="post" role="form"
      enctype="multipart/form-data">
      <input type="hidden" name="idusuario" value="<?=$id;?>">
      <input type="hidden" name="idpagina" value="<?=$idpagina;?>">
      <div class="form-group">
        <label for="inputNome">Nome do arquivo</label>
        <input type="text" id="inputNome" class="form-control" name="inputNome" required>
      </div>
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="inputData">Data publicacão</label>
            <input type="text" class="datepicker form-control" autocomplete="off" name="inputData" required>
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="inputUrl">URL</label>
            <input type="text" class="form-control" name="inputUrl" required>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="inputImagem">Arquivo</label>
        <div class="input-group mb-3">
          <input type="text" class="form-control" id="str-arquivo" placeholder="Procurar arquivo" readonly>
          <div class="input-group-append">
            <a class="btn btn-info text-white" id="btn-arquivo" type="button">Procurar</a>
          </div>
        </div>
        <input type="file" id="inputImagem" name="inputImagem" class="d-none" required>
        <p class="help-block">Selecione o arquivo (.jpg, .jpeg, .png, .gif).</p>
      </div>
      <button type="submit" id="enviarProduto" class="btn btn-labeled btn-success">
        <span class="btn-label"><i class="fas fa-check"></i></span>
        Enviar arquivo
      </button>
    </form>
  </div>
</div>


<div class="card mt-5">
  <div class="card-header">Gerenciar imagens <span id="ordem-salva" class="float-right text-success" style="display: none"><i class="fas fa-check"></i> Salvo</span></div>
  <div class="card-body">
    <div id="tabela-arquivos">
      <?php
        $listArquivos = $arquivoDAO->getByIdpagina($idpagina);
        if(sizeof($listArquivos) > 0) {
          foreach($listArquivos as $objVo) {
            printf('<div class="row hover" id="%s">', $objVo->getIdarquivo());
            printf('<div class="col-md-2 my-auto text-center"><div class="img-produto mx-auto" data-fancybox href="../../uploads/%s" style="cursor: pointer; background-image: url(../../uploads/%s);"></div></div>', $objVo->getFile(), $objVo->getFile());
            printf('<div class="col my-auto">');
            printf('<h3>%s</h3>', $objVo->getNome());
            printf('<span class="small text-muted">Link da imagem: <a href="%s">%s</a></span>', $objVo->getUrl(), $objVo->getUrl());
            printf('</div>');
            printf('<div class="col-md-1 my-auto text-center">');
            printf('<a href="javascript:void(0)" class="removeArquivo btn btn-danger" id="%s"><i class="fas fa-trash"></i></a> ', $objVo->getIdarquivo());
            printf('</div>');
            printf('<div class="col-md-1 my-auto text-center"><a id="sort-table"><i class="fas fa-arrows-alt-v"></i></a></div>');
            printf('</div>');
          }
        }
      ?>
    </div>
  </div>
</div>

<script>
$(function() {
  $("#datepicker").datepicker({
    dateFormat: 'yy-mm-dd'
  });

  $("#str-arquivo, #btn-arquivo").click(function() {
    $("#inputImagem").click();
  });

  $('#inputImagem').change(function() {
    $('#str-arquivo').val($(this).prop('files')[0].name);
  })

  $(function() {
    $("#tabela-arquivos").sortable({
      items: '.row',
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
        var itemOrder = $('#tabela-arquivos').sortable("toArray");
        var arr = [];
        for (var i = 0; i < itemOrder.length; i++) {
          arr.push(itemOrder[i]);
        }
        var strval = arr.join(',');

        $.ajax({
            url: "pagina/ajax/ajax_arquivo_order.php",
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
    $("#tabela-arquivos").disableSelection();

  });

});
</script>




