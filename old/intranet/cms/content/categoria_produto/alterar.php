<?php 
include("../class/class.Conexao.php");
include("../class/class.Categoria_ProdutoVO.php");
include("../class/DAO/class.Categoria_ProdutoDAO.php");

$categoria_produtoDAO = new Categoria_ProdutoDAO();
$idcategoria_produto = $_GET['id'];
$categoria_produto = $categoria_produtoDAO->getById($idcategoria_produto);
?>
<script>

$(document).ready(function() {

	var f = $('form');
	var b = $('#criarCategoria');
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

  $('#btn-arquivo').click(function () {
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

<div class="card">
  <div class="card-header">Alterar categoria <b><?=$categoria_produto->getNome()?></b></div>
  <div class="card-body">
    <form id="formCriarCategoria" action="categoria_produto/ajax/ajax_alt.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
      <input type="hidden" name="idcategoria_produto" value="<?=$idcategoria_produto?>">
      
      <div class="row">
        <div class="col-8">
          <div class="form-group">
            <label for="inputNome">Nome *</label>
            <input type="text" class="form-control" name="inputNome" id="inputNome" required value="<?=$categoria_produto->getNome()?>">
          </div>
          <div class="form-group">
            <label for="inputDescricao">Descrição</label>
            <textarea class="summernote" name="inputDescricao"><?=$categoria_produto->getDescricao()?></textarea>
          </div>
        </div>
        <div class="col-4">
          <div class="form-group">
            <label for="inputImagem">Imagem</label>
            <div class="input-group mb-1">
              <?php if ($categoria_produto->getImagem() != '') { ?>
                <div class="input-group-prepend">
                  <a class="btn btn-secondary" data-fancybox="gallery" href="../../uploads/<?=$categoria_produto->getImagem()?>" data-toggle="tooltip" title="Ver imagem atual" type="button" id="button-addon1"><i class="fas fa-eye"></i></a>
                </div>
              <?php } ?>
              <input type="text" class="form-control" id="str-arquivo" placeholder="Procurar arquivo" readonly>
              <div class="input-group-append">
                <a class="btn btn-info text-white" id="btn-arquivo" type="button">Procurar</a>
              </div>
            </div>
            <input type="file" id="inputImagem" name="inputImagem" class="d-none">
            <p class="help-block small">Selecione o arquivo (.jpg, .jpeg, .png, .gif).</p>
          </div>
        </div>
      </div>
     
      <!-- <div class="row">
        <div class="col text-left">
          <div class="checkbox center mb-3">
            <input type="checkbox" name="inputAtivo" value="1" class="switch_1" <?=$categoria_produto->getAtivo() == 1 ? 'checked' : ''?>>
            <label for="inputAtivo">Ativo</label>
          </div>
        </div>
      </div> -->
      <button type="submit" id="criarCategoria" class="btn btn-labeled btn-success">
        <span class="btn-label"><i class="fas fa-check"></i></span>
         Alterar categoria
      </button>
    </form>
    <div id="resultado" class="mt-3"></div>
  </div>
</div>
