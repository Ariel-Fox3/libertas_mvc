<?php 
include("../class/class.Conexao.php");
include("../class/class.ConfigVO.php");
include("../class/DAO/class.ConfigDAO.php");
include("../class/class.Func.php");

$configDAO = new ConfigDAO();

@$frase = $configDAO->getByNome('FRASE_HEADER');
$str_frase = '';
if ($frase) {
  $str_frase = $frase->getVal();
}

@$mail = $configDAO->getByNome('EMAIL_CONTATO');
$str_mail = '';
if ($mail) {
  $str_mail = $mail->getVal();
}

$str_telefone = '';
@$telefone = $configDAO->getByNome('TELEFONE');
if ($telefone) {
  $str_telefone = $telefone->getVal();
}

$str_whatsapp = '';
@$whatsapp = $configDAO->getByNome('WHATSAPP');
if ($whatsapp) {
  $str_whatsapp = $whatsapp->getVal();
}

$str_endereco = '';
@$endereco = $configDAO->getByNome('ENDERECO');
if ($endereco) {
  $str_endereco = $endereco->getVal();
}

$str_fb = '';
@$fb = $configDAO->getByNome('FB_URL');
if ($fb) {
  $str_fb = $fb->getVal();
}

$str_ig = '';
@$ig = $configDAO->getByNome('IG_URL');
if ($ig) {
  $str_ig = $ig->getVal();
}

$str_msg_whatsapp = '';
@$msg_whatsapp = $configDAO->getByNome('MSG_WHATSAPP');
if ($msg_whatsapp) {
  $str_msg_whatsapp = $msg_whatsapp->getVal();
}

$str_url_amigavel = '';
@$url_amigavel = $configDAO->getByNome('URL_AMIGAVEL');
if ($url_amigavel) {
  $str_url_amigavel = $url_amigavel->getVal();
}


?>

<div class="card">
  <div class="card-header">Configurações do site</div>
  <div class="card-body">
    <form id="formCriarNoticia" action="config/ajax/ajax_update.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">


      <div class="form-group">
        <label>Frase do cabeçalho</label>
        <input type="tel" value="<?=$str_frase?>" class="form-control" name="inputFrase">
      </div>

      <div class="row">
        <div class="col">
          <div class="form-group">
            <label>WhatsApp</label>
            <input type="tel" value="<?=$str_whatsapp?>" class="form-control" name="inputWhatsapp">
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label>Telefone</label>
            <input type="tel" value="<?=$str_telefone?>" class="form-control" name="inputTelefone">
          </div>
        </div>
      </div>


      <div class="form-group">
        <label>Email de contato</label>
        <input type="text" value="<?=$str_mail?>" class="form-control" name="inputEmailContato" placeholder="Email">
        <small>* Este email receberá avisos quando algum usuário entrar em contato através do site.</small>
      </div>

      <div class="form-group">
        <label>Endereço</label>
        <input type="tel" value="<?=$str_endereco?>" class="form-control" name="inputEndereco">
      </div>

      <div class="row">
        <div class="col">
          <div class="form-group">
            <label>Link Facebook</label>
            <input type="text" value="<?=$str_fb?>" class="form-control" name="inputFb">
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label>Link Instagram</label>
            <input type="text" value="<?=$str_ig?>" class="form-control" name="inputIg">
          </div>
        </div>
      </div>


      <div id="code" class="mt-3">
        <label>URLs amigaveis</label>
        <textarea class="summernote" name="inputUrlAmigavel"><?=$str_url_amigavel?></textarea>
      </div>

      <button type="submit" id="criarNoticia" class="btn btn-labeled btn-success">
        <span class="btn-label"><i class="fas fa-check"></i></span>
         Salvar configurações
      </button>
    </form>
    <div id="resultado" class="mt-3"></div>
  </div>
</div>


<script>

  $(document).ready(function () {

    $('.summernote').summernote('codeview.toggle');


    // $('input[name="inputWhatsapp"]').mask('(00) 0 0000-0000');

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
  });

</script>