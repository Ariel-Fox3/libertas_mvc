<?php
  include("../class/class.Conexao.php");
  include("../class/class.PaginaVO.php");
  include("../class/DAO/class.PaginaDAO.php");

  $paginaDAO = new PaginaDAO();

  $idpagina = $_GET['id'];
  if(isset($idpagina)) {
    $pagina = $paginaDAO->getById($idpagina);
  }

  // $filtros_e = unserialize($pagina->getElements());
  // $arr_filtros = array();
  // if ($filtros_e[0] != '') {
  //   foreach ($filtros_e as $i => $filtro) {
  //     $arr_filtros[] = '"<' . $filtro . '"';
  //     $arr_filtros[] = '"</' . $filtro . '>"';
  //   }
  // }
?>
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
      if (!$(this).attr('disabled')) {
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
      }
    });

    $('#chkExterno').click(function () {
      if ($(this).prop('checked')) {
        $('#inputUrl').removeAttr('disabled');
      } else {
        $('#inputUrl').attr('disabled', true).val('');
      }
    });

    $('#inputConteudo').summernote({
      height: 200,
      styleTags: [
        'p',
        { title: 'asdasdas de serviço', tag: 'h4', value: 'h4' },
        'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
      ]
    });
  });
</script>
<div class="card">
  <div class="card-header">Editar página</div>
  <div class="card-body">
  <form id="formArquivo" action="pagina/ajax/ajax_alt.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="idusuario" value="<?=$id;?>">
        <input type="hidden" name="idpagina" value="<?=$idpagina;?>">
        <div class="form-group">
          <label for="inputCapa">Nome da página *</label>
          <input type="text" id="inputTitulo" placeholder="Ex: Quem somos" value='<?=$pagina->getNome()?>' class="form-control" name="inputNome" required>
        </div>
        <div class="row">
          <div class="col">
            <div class="checkbox">
              <input type="checkbox" name="chkExterno" id="chkExterno" class="switch_1" <?=$pagina->getExterno() == '1' ? 'checked' : ''?>>
              <label> Link externo</label>
            </div>

            <div class="input-group mt-2 mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">URL</span>
              </div>
              <input class="form-control" type="text" <?=$pagina->getExterno() == '1' ? '' : 'disabled'?> value="<?=$pagina->getLink()?>" id="inputUrl" name="inputUrl" placeholder="https://www.site.com">
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
                      printf('<option value="%s" %s>%s %s</option>', $objVo->getIdpagina(), $pagina->getIdsublink() == $objVo->getIdpagina() ? 'selected' : '', $objVo->getNome(), isset($pagina_sub) ? "[".$pagina_sub->getNome()."]" : "");
                    }
                  }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="inputCapa">Conteúdo página *</label>
          <textarea rows="8" name="inputConteudo" id="inputConteudo"><?=$pagina->getConteudo()?></textarea>
        </div>

        <div class="form-group">
          <p>
            <?php
              // $setter = array("h1","h4","a");
              // echo (serialize($setter));
              // echo '<br>';
              // $elm = unserialize($pagina->getElements());
              // if ($elm[0] != '') {
              //   printf('<b>Instruções para configurar a página:</b><br>');
              //   foreach($elm as $e) {
              //     printf('A página precisa ter: <b>%s</b> <br>', $e);
              //   }
              // }
            ?>
          </p>
        </div>
        <button type="submit" id="enviarProduto" class="btn btn-labeled btn-success">
          <span class="btn-label"><i class="fas fa-check"></i></span>
          Alterar página
        </button>
      </form>
      <div id="resultado" class="mt-3"></div>
  </div>
</div>