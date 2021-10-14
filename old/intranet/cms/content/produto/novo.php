<?php 
  include("../class/class.Conexao.php");
  include("../class/class.Categoria_ProdutoVO.php");
  include("../class/DAO/class.Categoria_ProdutoDAO.php");
  include("../class/class.ProdutoVO.php");
  include("../class/DAO/class.ProdutoDAO.php");

  $categoria_produtoDAO = new Categoria_ProdutoDAO();
  $produtoDAO = new ProdutoDAO();

?>

<div class="card">
  <div class="card-header">Adicionar produto</div>
  <div class="card-body">
    <form id="formNovoCurso" action="produto/ajax/ajax_add.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
      <div class="row">
        <div class="col-8">
          <div class="form-group">
            <label for="inputNome">Nome do produto</label>
            <input type="text" class="form-control" name="inputNome" required>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-4">
                <label for="inputIdcategoria[]">Categoria</label>
                <select data-live-search="true" name="inputIdcategoria[]" multiple title="Selecione" data-selected-text-format="count > 1">
                  <?php
                    $listCategorias = $categoria_produtoDAO->getAll();
                    if (sizeof($listCategorias) > 0) {
                      foreach ($listCategorias as $objVoC) {
                        printf('<option value="%s">%s</option>', $objVoC->getIdcategoria_produto(), $objVoC->getNome());
                      }
                    }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-6">
                <label for="inputUrlVideo">ID do vídeo</label>
                <input type="text" class="form-control" name="inputUrlVideo">
                <small class="text-black-50">https://www.youtube.com/watch?v=<b>ID-DO-VIDEO</b></small>
              </div>
            </div>
          </div>

        </div>

        <div class="col-4">
          <div class="text-center">
            <label>Imagem *</label><br>
            <img alt="" src="../../imagens/default-avatar.png" class="rounded-circle img-responsive mt-2" width="128" height="128" />
            <div class="mt-3">
              <input type="file" class="d-none" name="inputImagem" id="inputImagem">
              <a href="javascript:;" class="btn btn-primary btn-upload"><i class="fas fa-upload"></i> Enviar</a>
              <div id="res_upload" class="small mt-2"></div>
            </div>
          </div>

          
        </div>
      </div>

      <div class="form-group">
        <label for="inputDescricao">Descrição do produto</label>
        <textarea class="summernote" name="inputDescricao"></textarea>
      </div>

      <div class="form-group mb-1">
        <div class="checkbox center mr-3 d-inline">
          <input type="checkbox" name="inputAtivo" value="1" checked class="switch_1">
          <label for="inputAtivo">Ativo</label>
        </div>
      </div>

      <button type="submit" id="criarUsuario" class="btn btn-labeled btn-success">
        <span class="btn-label"><i class="fas fa-check"></i></span>
         Novo produto
      </button>
    </form>
    <div id="resultado" class="mt-3"></div>
  </div>
</div>

<script>
  $(document).ready(function() {
    var f = $('form');
    var b = $('#criarUsuario');
    var p = $('#resultado');
    
    b.click(function() {
      f.ajaxForm({
        beforeSend : function() {
          b.attr('disabled', 'disabled');
          p.fadeOut();
        },
        success : function(e) {
          b.removeAttr('disabled');
          p.html(e).fadeIn();
        },
        error : function(e) {
          b.removeAttr('disabled');
          p.html(e).fadeIn();
        }
      });
    });

    $('.btn-upload').click(function () {
      $('#inputImagem').click();
    });

    $('#inputImagem').change(function () {
      var file = $(this).prop('files')[0];
      console.log(file);
      var res = $('#res_upload');
      var n_f = file.name;
      res.addClass('text-success').html(`Arquivo <b>${n_f}</b> será enviado.`);
    });

  });
</script>