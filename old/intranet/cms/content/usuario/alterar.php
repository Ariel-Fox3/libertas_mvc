<?php 
include("../class/class.Conexao.php");
include("../class/class.UsuarioVO.php");
include("../class/DAO/class.UsuarioDAO.php");

$idusuario = $_GET['idusuario'];

if(isset($idusuario)) {
	$usuarioDAO = new UsuarioDAO();
	$usuario = $usuarioDAO->getById($_GET['idusuario']);
}

?>
<script>
  function validaSenha(input) {
    let s = document.getElementById('inputSenha');
    if (input.value != s.value) {
      $('.help-block').text('As duas senhas não coincidem.').css('color', 'red');
      input.setCustomValidity('As duas senhas não coincidem');
      $(input).addClass('is-invalid');
      $(s).addClass('is-invalid');
    } else {
      $('.help-block').text('');
      input.setCustomValidity('');
      $(input).removeClass('is-invalid');
      $(s).removeClass('is-invalid');
    }
  }
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

    $('.btn-ver-senha').click(function() {
      let el = $(this);
      let i = el.parent().parent().find('input');
      if (i.attr('type') == 'password') {
        i.attr('type', 'text');
      } else {
        i.attr('type', 'password');
      }

    });

    $('#alterarSenha').change(function () {
      var senha = $('#inputSenha');
      var senha2 = $('#inputSenha2');
      if ($(this).prop('checked')) {
        senha.removeAttr('disabled').val('');
        senha2.removeAttr('disabled').val('');
      } else {
        senha.attr('disabled', true).val('');
        senha2.attr('disabled', true).val('');
      }
    });
  });
</script>


<div class="card">
  <div class="card-header">Alterar usuário</div>
  <div class="card-body">
    <form id="formCriarUsuario" action="usuario/ajax/ajax_alt.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
      <div class="form-group">
        <label for="inputNome">Nome completo *</label>
        <input type="text" class="form-control" name="inputNome" value="<?=$usuario->getNome();?>" id="inputNome" required>
        <input type="hidden" name="idusuario" value="<?=$idusuario;?>">
      </div>
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="inputUsuario">Email *</label>
            <input type="text" class="form-control" name="inputUsuario" value="<?=$usuario->getEmail();?>" id="inputUsuario" required>
          </div>
        </div>
      </div>
      <div class="checkbox mb-1 mt-2">
        <input type="checkbox" id="alterarSenha" name="alterarSenha" value="1" class="switch_1">
        <label for="alterarSenha">Alterar senha</label>
      </div>

      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="inputSenha">Senha </label>
            <div class="input-group">
              <input class="form-control" type="password" disabled name="inputSenha" id="inputSenha" placeholder="Senha" required>
              <div class="input-group-append">
                <a class="btn btn-dark text-white btn-ver-senha"><i class="fas fa-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="inputSenha2">Repita senha </label>
            <div class="input-group">
              <input class="form-control" type="password" disabled name="inputSenha2" id="inputSenha2" placeholder="Repetir senha" onkeyup="validaSenha(this)" required>
              <div class="input-group-append">
                <a class="btn btn-dark text-white btn-ver-senha"><i class="fas fa-eye"></i></a>
              </div>
            </div>
            <p class="help-block mb-n4 mt-1"></p>
          </div>
        </div>
      </div>
      
      <div class="row mt-4">
        <div class="col text-left">
          <div class="checkbox center mb-3">
            <input type="checkbox" name="inputAtivo" value="1" class="switch_1" <?=$usuario->getAtivo() == 1 ? "checked" : ""?>>
            <label for="inputAtivo">Ativo</label>
          </div>
        </div>
      </div>
      <button type="submit" id="criarUsuario" class="btn btn-labeled btn-success">
        <span class="btn-label"><i class="fas fa-check"></i></span>
        Alterar usuário
      </button>
    </form>
    <div id="resultado" class="mt-3"></div>
  </div>
</div>