<?php 
include("../class/class.Conexao.php");
include("../class/class.UnidadeVO.php");
include("../class/DAO/class.UnidadeDAO.php");
include("../class/class.ClienteVO.php");
include("../class/DAO/class.ClienteDAO.php");
dbcon();

$unidadeDAO = new UnidadeDAO();
$clienteDAO = new ClienteDAO();

@$idclienteLoad = $_GET['idcliente'];

?>
<script>
$(document).ready(function() {

	var f = $('form');
	var l = $('#loader');
	// loder.gif image
	var b = $('#enviarProduto');
	// upload button
	var p = $('#resultado');
	// preview area
	var bar = $('.bar');
	var percent = $('.percent');
	var status = $('#status');

	b.click(function() {
		// implement with ajaxForm Plugin
		f.ajaxForm({
			beforeSend : function() {
				status.empty();
        		var percentVal = '0%';
        		bar.width(percentVal)
        		percent.html(percentVal);
				l.show();
				b.attr('disabled', 'disabled');
				p.fadeOut();
			},
			 uploadProgress: function(event, position, total, percentComplete) {
       			var percentVal = percentComplete + '%';
		        bar.width(percentVal)
		        percent.html(percentVal);
   			 },
			success : function(e) {
				l.hide();
				//f.resetForm();
				b.removeAttr('disabled');
				p.html(e).fadeIn();
				var percentVal = '100%';
        		bar.width(percentVal)
        		percent.html(percentVal);
			},
			complete: function(xhr) {
		status.html(xhr.responseText);
	},
			error : function(e) {
				b.removeAttr('disabled');
				p.html(e).fadeIn();
			}
		});
	});
});
</script>

<div class="produto-novo">
	<div id="resultado"></div>
	<div class="panel panel-default">
  <div class="panel-heading">Nova solicitação</div>
  <div class="panel-body">
    <form id="formArquivo" action="solicitacao/ajax/ajax_add.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
      <input type="hidden" name="idusuario" value="<?=$id;?>">
      <div class="form-group">
        <label for="inputCapa">Unidade</label>
		<select class="form-control" name="inputUnidade" required>
			<option value="">SELECIONE</option>
			<?php 
			$listUnidade = $unidadeDAO->getAll();
			if(sizeof($listUnidade)>0) {
				foreach ($listUnidade as $objVo) {
					printf('<option value="%s">%s</option>', $objVo->getIdunidade(), $objVo->getNome());
				}
			}
			?>
		</select>
      </div>
      <div class="form-group">
        <label for="inputCapa">Cliente</label>
		<!--<select class="form-control" name="inputCliente" required>
			<option value="">SELECIONE</option>
			<?php 
			$listCliente = $clienteDAO->getAll();
			if(sizeof($listCliente)>0) {
				foreach ($listCliente as $objVo) {
					printf('<option value="%s">%s (%s)</option>', $objVo->getIdcliente(), $objVo->getNome(), $objVo->getEmail());
				}
			}
			?>
		</select>-->


		<?php 
		$clienteLoad = $clienteDAO->getById($idclienteLoad);
		if($clienteLoad != null) {
			printf('<input type="hidden" name="inputCliente" value="%s">', $clienteLoad->getIdcliente());
	        printf('<div class="row">');
	        printf('<div class="col-md-3"><strong>Nome:</strong> %s</div>', $clienteLoad->getNome());
	        printf('<div class="col-md-3"><strong>Celular:</strong> %s</div>', $clienteLoad->getFone_cel());
	        printf('<div class="col-md-3"><strong>Fixo:</strong> %s</div>', $clienteLoad->getFone_fixo());
	        printf('</div>');			
		} else {
			printf('<button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Adicionar cliente</button>');	
		}

		?>


		
      </div>
      <div class="form-group">
        <label for="inputCapa">Status</label>
		<select class="form-control" name="inputStatus" required>
			<option value="">SELECIONE</option>
			<option selected value="Aguardando atendimento">Aguardando atendimento</option>
			<option value="Matriculado">Matriculado</option>
			<option value="Em aberto">Em aberto</option>
			<option value="Finalizado">Finalizado</option>
			<option value="Cancelado">Cancelado</option>
		</select>
      </div>
      <div class="form-group">
        <label for="inputCapa">Descrição</label>
		<textarea class="form-control" name="inputDesc"></textarea>
      </div>
      <img src="../../../imagens/loader.gif" id="loader" style="display:none;">
      <button type="submit" class="btn btn-success" id="enviarProduto">Novo</button>

      <div class="progress">
      <br />
        <div class="bar"></div >
        <div class="percent text-center">0%</div >
      </div>
    </form>
  </div>
</div>
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Novo cliente</h4>
      </div>
      <div class="modal-body">
      	<div id="resultadoCliente"></div>
        <form action="cliente/ajax/ajax_add.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
        	<div class="form-group">
        		<input type="text" name="inputNome" class="form-control" placeholder="Nome completo" required>
        	</div>
        	<div class="form-group">
        		<input type="text" name="inputEmail" class="form-control" placeholder="E-mail" required>
        	</div>
        	<div class="form-group">
        		<input type="text" name="inputFone" class="form-control" placeholder="Fone Cel." required>
        	</div>
        	<div class="form-group">
        		<input type="text" name="inputFixo" class="form-control" placeholder="Fone Fixo">
        	</div>
        	<div class="form-group">
        		<textarea name="inputObs" class="form-control"></textarea>
        	</div>
        	<button type="submit" class="btn btn-success" id="novoCliente">Novo</button>
			
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {

	var f = $('form');
	//var l = $('#loader');
	var b = $('#novoCliente');
	var p = $('#resultadoCliente');

	b.click(function() {
		// implement with ajaxForm Plugin
		f.ajaxForm({
			beforeSend : function() {
				//l.show();
				b.attr('disabled', 'disabled');
				p.fadeOut();
			},
			success : function(e) {
				//l.hide();
				//f.resetForm();
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
