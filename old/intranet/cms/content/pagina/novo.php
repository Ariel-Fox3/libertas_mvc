<?php
include("../class/class.Conexao.php");
include("../class/class.PaginaVO.php");
include("../class/DAO/class.PaginaDAO.php");
$paginaDAO = new PaginaDAO();

@$idpai = $_GET['id'];
?>

<div class="card">
  <div class="card-header">Nova página</div>
  <div class="card-body">
  <form id="formArquivo" action="pagina/ajax/ajax_add.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="idusuario" value="<?=$id;?>">
        <div class="form-group">
          <label for="inputCapa">Nome da página *</label>
          <input type="text" id="inputTitulo" placeholder="Ex: Quem somos" class="form-control" name="inputNome" required>
        </div>
        <div class="row">
          <div class="col">
            <div class="checkbox">
              <input type="checkbox" name="chkExterno" id="chkExterno" class="switch_1">
              <label> Link externo</label>
            </div>

            <div class="input-group mt-2 mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">URL</span>
              </div>
              <input class="form-control" type="text" disabled id="inputUrl" name="inputUrl" placeholder="https://www.site.com">
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col">
              <label for="inputCapa">Pagina do sublink</label>
              <select class="form-control" name="inputIdsublink">
                <option value="0">-- Não é sublink --</option>
                <?php
                  $listPaginas = $paginaDAO->getAll();
                  if(sizeof($listPaginas)>0) {
                    foreach($listPaginas as $objVo) {
                      if ($objVo->getIdsublink() != 0) {
                        $pagina_sub = $paginaDAO->getById($objVo->getIdsublink());
                      } else {
                        $pagina_sub = null;
                      }
                      printf('<option value="%s" %s>%s %s</option>', $objVo->getIdpagina(), isset($idpai) && $idpai == $objVo->getIdpagina() ? 'selected' : '', $objVo->getNome(), isset($pagina_sub) ? "[".$pagina_sub->getNome()."]" : "");
                    }
                  }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="inputCapa">Conteúdo página *</label>
          <textarea class="summernote" rows="8" name="inputConteudo"></textarea>
        </div>

        <button type="submit" id="enviarProduto" class="btn btn-labeled btn-success">
          <span class="btn-label"><i class="fas fa-check"></i></span>
          Nova página
        </button>
      </form>
      <div id="resultado" class="mt-3"></div>
  </div>
</div>

<script>
$(document).ready(function() {


  $("#str-arquivo, #btn-arquivo").click(function () {
    $("#inputImagem").click();
  });

  $('#inputImagem').change(function () {
    $('#str-arquivo').val($(this).prop('files')[0].name);
  })


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
      },
      error: function(e) {
        b.removeAttr('disabled');
        p.html(e).fadeIn();
      }
    });
  });

  $('#chkExterno').click(function () {
    if ($(this).prop('checked')) {
      $('#inputUrl').removeAttr('disabled');
    } else {
      $('#inputUrl').attr('disabled', true).val('');
    }
  });
});
</script>