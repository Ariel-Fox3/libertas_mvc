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
  <div class="card-header">Novo serviço</div>
  <div class="card-body">
    <form id="formCriarCategoria" action="servico/ajax/ajax_add.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
      
      <div class="row">
        <div class="col-8">
          <div class="form-group">
            <label for="inputNome">Nome *</label>
            <input type="text" class="form-control" name="inputNome" id="inputNome" required>
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
          <div class="form-group">
            <label for="inputDescricao">Descrição</label>
            <textarea class="summernote" name="inputDescricao"></textarea>
          </div>
        </div>
        <div class="col-4">
          <div class="text-center">
            <label>Imagem</label><br>
            <img alt="" src="../../imagens/default-avatar.png" class="rounded-circle img-responsive mt-2" width="128" height="128" />
            <div class="mt-3">
              <input type="file" class="d-none" name="inputImagem" id="inputImagem">
              <a href="javascript:;" class="btn btn-primary btn-upload"><i class="fas fa-upload"></i> Enviar</a>
              <div id="res_upload" class="small mt-2"></div>
            </div>
          </div>

          
        </div>
      </div>
     
      <div class="row">
        <div class="col text-left">
          <div class="checkbox center mb-3">
            <input type="checkbox" name="inputAtivo" value="1" class="switch_1" checked>
            <label for="inputAtivo">Ativo</label>
          </div>
        </div>
      </div>
      <button type="submit" id="criarCategoria" class="btn btn-labeled btn-success">
        <span class="btn-label"><i class="fas fa-check"></i></span>
         Novo servico
      </button>
    </form>
    <div id="resultado" class="mt-3"></div>
  </div>
</div>
