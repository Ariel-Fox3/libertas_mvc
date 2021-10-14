<?php
date_default_timezone_set('America/Sao_Paulo');
include_once("../class/class.Conexao.php");
include_once("../class/class.Func.php");
include_once("../class/class.SolicitacaoVO.php");
include_once("../class/DAO/class.SolicitacaoDAO.php");

$solicitacaoDAO = new SolicitacaoDAO();

$idsolicitacao = $_GET['id'];
if(isset($idsolicitacao)) {
  $solicitacao = $solicitacaoDAO->getById($idsolicitacao);
}

$off = "readonly='readonly'";
if($admin == 1) {
	$off = "";
}
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
      },
      error: function(e) {
        b.removeAttr('disabled');
        p.html(e).fadeIn();
      }
    });
  });
});
</script>

<div class="card">
  <div class="card-header">Alterar solicitação</div>
  <div class="card-body">
    <form id="formArquivo" action="solicitacao/ajax/ajax_alt.php" name="formCadastro" method="post" role="form"
      enctype="multipart/form-data">
      <input type="hidden" name="idusuario" value="<?=$id;?>">
      <input type="hidden" name="idsolicitacao" value="<?=$idsolicitacao;?>">
      <span class="float-right text-info font-weight-bold">Data da solicitação: <?=DateTime::createFromFormat('Y-m-d H:i:s', $solicitacao->getData())->format('d/m/Y H:i:s')?></span>
      <div class="form-group">
        <label for="inputCapa">Status</label>
        <select class="form-control" name="inputStatus" required>
          <option value="">SELECIONE</option>
          <option <?=$solicitacao->getStatus() == "Aguardando atendimento" ? "selected" : "";?> value="Aguardando atendimento">Aguardando atendimento</option>
          <option <?=$solicitacao->getStatus() == "Atendimento realizado" ? "selected" : "";?> value="Atendimento realizado">Atendimento realizado</option>
          <option <?=$solicitacao->getStatus() == "Visita marcada" ? "selected" : "";?> value="Visita marcada">Visita marcada</option>
          <option <?=$solicitacao->getStatus() == "Cancelado" ? "selected" : "";?> value="Cancelado">Cancelado</option>
        </select>
      </div>
      <hr>
      <style>
        #dados-cliente span {
          font-size: 14px; 
        }

        .desc-solicitacao {
          font-size: 13px;
          border: 1px solid #eee;
          padding: 30px;
          width: 500px;
          margin-left: auto;
          margin-right: auto;
          margin-top: 20px;
        }

        #produto-solicitacao .img {
          width: 100%;
          height: 200px;
          background-size: contain;
          background-position: center;
          background-repeat: no-repeat;
        }
      </style>

      <div class="form-group mt-3" id="dados-cliente">
        <h4 class="text-center">Dados do cliente</h4>
        <div class="row mt-2">
          <div class="col-md-4 text-center">
            <span><b>Nome: </b><?=$solicitacao->getNome()?></span>
          </div>
          <div class="col-md-4 text-center">
            <span><b>Email: </b><?=$solicitacao->getEmail()?></span>
          </div>
          <div class="col-md-4 text-center">
            <span><b>Telefone: </b><?=$solicitacao->getTelefone()?></span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="desc-solicitacao"><?=$solicitacao->getMensagem()?></div>
      </div>
      <div class="form-group">
        <label for="inputCapa">Observações</label>
        <textarea class="form-control" name="inputObs"><?php echo $solicitacao->getObs();?></textarea>
      </div>
      <button type="submit" id="enviarProduto" class="btn btn-labeled btn-success">
          <span class="btn-label"><i class="fas fa-check"></i></span>
          Alterar solicitação
        </button>
      <div id="resultado" class="mt-3"></div>
    </form>
  </div>
</div>
