<?php 
include("../class/class.Conexao.php");
include("../class/class.SolicitacaoVO.php");
include("../class/DAO/class.SolicitacaoDAO.php");

$solicitacaoDAO = new SolicitacaoDAO();
$idsolicitacao = $_GET['id'];
$solicitacao = $solicitacaoDAO->getById($idsolicitacao);
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

<div class="card">
  <div class="card-header">Solicitação de contato de <b><?=$solicitacao->getNome()?></b></div>
  <div class="card-body">
    <form id="formCriarCategoria" action="contato/ajax/ajax_alt.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
      <input type="hidden" name="idsolicitacao" value="<?=$idsolicitacao?>">
      <input type="hidden" name="idusuario" value="<?=$id?>">
      
      <div class="form-group">
        <label for="inputStatus">Alterar status</label>
        <select name="inputStatus">
          <option value="1" <?=$solicitacao->getStatus() == '1' ? 'selected' : ''?>>Em aberto</option>
          <option value="2" <?=$solicitacao->getStatus() == '2' ? 'selected' : ''?>>Atendido</option>
          <option value="3" <?=$solicitacao->getStatus() == '3' ? 'selected' : ''?>>Cancelado</option>
        </select>
      </div>

      <div class="jumbotron py-4">
        <div class="row">
          <div class="col">
            <div class="text-center">
              <span class="font-weight-bold m-0">Nome: </span>
              <span class="m-0"><?=$solicitacao->getNome()?></span>
            </div>
          </div>
          <div class="col">
            <div class="text-center">
              <span class="font-weight-bold m-0">Email: </span>
              <span class="m-0"><?=$solicitacao->getEmail()?></span>
            </div>
          </div>
          <div class="col">
            <div class="text-center">
              <span class="font-weight-bold m-0">Telefone: </span>
              <span class="m-0"><?=$solicitacao->getTelefone()?></span>
            </div>
          </div>
        </div>
      </div>

      <h5 class="small text-center mt-4">Mensagem</h5>
      <div class="row mb-5">
        <div class="col-8 mx-auto">
          <div class="border bg-white p-3">
            <?=$solicitacao->getMensagem()?>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="inputObs">Observação</label>
        <textarea class="summernote" name="inputObs"><?=$solicitacao->getObs()?></textarea>
      </div>


      <button type="submit" id="criarCategoria" class="btn btn-labeled btn-success">
        <span class="btn-label"><i class="fas fa-check"></i></span>
         Salvar solicitação
      </button>
    </form>
    <div id="resultado" class="mt-3"></div>
  </div>
</div>
