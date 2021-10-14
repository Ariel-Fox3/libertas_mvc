<?php 
include("../class/class.Conexao.php");
include("../class/class.NoticiaVO.php");
include("../class/DAO/class.NoticiaDAO.php");
include("../class/class.CategoriaVO.php");
include("../class/DAO/class.CategoriaDAO.php");

$noticiaDAO = new NoticiaDAO();
$categoriaDAO = new CategoriaDAO();
$idnoticia = $_GET['id'];
$noticia = $noticiaDAO->getById($idnoticia);
$d_noticia = DateTime::createFromFormat('Y-m-d H:i:s', $noticia->getData());
?>
<script>

$(document).ready(function() {

	var f = $('form');
	var b = $('#criarNoticia');
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

  $('.btn-arquivo').click(function () {
    $(this).parent().parent().parent().find('input.d-none').click();
  });

  $('input.d-none').change(function () {
    var i_str = $(this).parent().find('.input-str');
    var file = $(this).prop('files')[0];
    console.log(file);
    var n_f = file.name;
    i_str.val(n_f);
  });

  $('.chk_padrao').change(function () {
    var $t = $(this);
    if ($(this).prop('checked') == true) {
      $t.parent().parent().find('.input-group').addClass('d-none');
      $t.parent().parent().find('.form-group').addClass('d-none');
    } else {
      $t.parent().parent().find('.input-group').removeClass('d-none');
      $t.parent().parent().find('.form-group').removeClass('d-none');
    }
  })
});
</script>

<div class="card">
  <div class="card-header">Alterar notícia <b></b></div>
  <div class="card-body">
    <form id="formCriarNoticia" action="noticia/ajax/ajax_alt.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
      <input type="hidden" name="idnoticia" value="<?=$idnoticia?>">
      
      <div class="row">
        <div class="col-8">
          <div class="form-group">
            <label for="inputNome">Nome *</label>
            <input type="text" class="form-control" name="inputNome" id="inputNome" required value="<?=$noticia->getNome()?>">
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="inputCategoria">Categoria *</label>
                <select name="inputCategoria" required>
                  <?php
                    $listCategorias = $categoriaDAO->getAll(true);
                    if (sizeof($listCategorias) > 0) {
                      foreach ($listCategorias as $objVoC) {
                        printf('<option %s value="%s">%s</option>', $objVoC->getIdcategoria() == $noticia->getIdcategoria() ? 'selected' : '', $objVoC->getIdcategoria(), $objVoC->getNome());
                      }
                    }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="inputTags">Tags *</label>
                <?php
                  $tags = str_replace('"', '', $noticia->getTags());
                  $tags = explode(',', $tags);
                ?>
                <input type="text" class="form-control" placeholder="Palavras separadas por vírgula." name="inputTags" id="inputTags" required value="<?=implode(', ', $tags)?>">
              </div>
            </div>

          </div>

          <div class="form-group">
            <label for="inputData">Data *</label>
            <input type="text" autocomplete="off" class="form-control datepicker pl-2" name="inputData" id="inputData" required value="<?=$d_noticia->format('d/m/Y')?>">
          </div>

        </div>
        <div class="col-4">
          <div class="text-center">
            <?php if ($noticia->getImagem() != '') { ?>
              <label>Imagem *</label><br>
              <img alt="" src="../../uploads/<?=$noticia->getImagem()?>" class="img-responsive mt-2" width="200" height="200" />
              <div class="mt-3">
                <input type="file" class="d-none" name="inputImagem" id="inputImagem">
                <a href="javascript:;" class="btn btn-primary btn-upload"><i class="fas fa-upload"></i> Substituir</a>
                <div id="res_upload" class="small mt-2"></div>
              </div>
            <?php } else { ?>
              <label>Imagem *</label><br>
              <img alt="" src="../../imagens/default-avatar.png" class="rounded-circle img-responsive mt-2" width="128" height="128" />
              <div class="mt-3">
                <input type="file" class="d-none" name="inputImagem" id="inputImagem">
                <a href="javascript:;" class="btn btn-primary btn-upload"><i class="fas fa-upload"></i> Enviar</a>
                <div id="res_upload" class="small mt-2"></div>
              </div>
            <?php } ?>
          </div>

          
        </div>
      </div>
     
      <div class="row">
        <div class="col text-left">
          <div class="form-group">
            <label for="inputConteudo">Conteúdo *</label>
            <div class="clearfix" name="inputConteudo">
              <div class="quill-toolbar">
                <span class="ql-formats">
                  <select class="ql-font"></select>
                  <select class="ql-size"></select>
                </span>
                <span class="ql-formats">
                  <button class="ql-bold"></button>
                  <button class="ql-italic"></button>
                  <button class="ql-underline"></button>
                  <button class="ql-strike"></button>
                </span>
                <span class="ql-formats">
                  <select class="ql-color"></select>
                  <select class="ql-background"></select>
                </span>
                <span class="ql-formats">
                  <button class="ql-script" value="sub"></button>
                  <button class="ql-script" value="super"></button>
                </span>
                <span class="ql-formats">
                  <button class="ql-header" value="1"></button>
                  <button class="ql-header" value="2"></button>
                  <button class="ql-blockquote"></button>
                  <button class="ql-code-block"></button>
                </span>
                <span class="ql-formats">
                  <button class="ql-list" value="ordered"></button>
                  <button class="ql-list" value="bullet"></button>
                  <button class="ql-indent" value="-1"></button>
                  <button class="ql-indent" value="+1"></button>
                </span>
                <span class="ql-formats">
                  <button class="ql-direction" value="rtl"></button>
                  <select class="ql-align"></select>
                </span>
                <span class="ql-formats">
                  <button class="ql-link"></button>
                  <button class="ql-image"></button>
                  <button class="ql-video"></button>
                </span>
                <span class="ql-formats">
                  <button class="ql-clean"></button>
                </span>
              </div>
              <div class="quill-editor"><?=$noticia->getConteudo()?></div>
            </div>
          </div>
          <div class="checkbox center mt-3 mb-3">
            <input type="checkbox" name="inputAtivo" value="1" class="switch_1" <?=$noticia->getAtivo() == 1 ? 'checked' : ''?>>
            <label for="inputAtivo">Ativo</label>
          </div>
          <div class="checkbox center mt-3 mb-3">
            <input type="checkbox" name="inputDestaque" value="1" class="switch_1 success" <?=$noticia->getDestaque() == 1 ? 'checked' : ''?>>
            <label for="inputDestaque">Destaque</label>
          </div>
        </div>
      </div>

      <button type="submit" id="criarNoticia" class="mt-3 btn btn-labeled btn-success">
        <span class="btn-label"><i class="fas fa-check"></i></span>
         Alterar notícia
      </button>
    </form>
    <div id="resultado" class="mt-3"></div>
  </div>
</div>

