<?php 
include("../class/class.Conexao.php");
include("../class/class.SolicitacaoVO.php");
include("../class/DAO/class.SolicitacaoDAO.php");
include("../class/class.Func.php");

$solicitacaoDAO = new SolicitacaoDAO();

?>
<script>
$('document').ready(function() {
  $('a.removeSolicitacao').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('contato/ajax/ajax_del.php', {
      id: del_id
    }, function(data) {

      if (data == '') {
        parent.slideUp('slow', function() {
          //$(this).remove();
          //$(this).remove();
          alert("Removido com sucesso!");
          $(this).closest('tr').remove();

        });
      } else {
        alert(data);
      }
    });
  });
});
</script>

<div class="card">
  <div class="card-header">Gerenciar solicitações de contato</div>
  <div class="card-body">
    <!-- <ul class="list-inline">
      <li class="list-inline-item">
        <a href="?pg=novo&lc=categoria" class="btn btn-labeled btn-success">
          <span class="btn-label"><i class="fas fa-plus"></i></span>
          Adicionar nova categoria
        </a>
      </li>
    </ul> -->
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
      <thead>
        <tr>
          <th class="text-center">Opções</th>
          <th class="text-center">Nome</th>
          <th class="text-center">Contato</th>
          <th class="text-center">Mensagem</th>
          <th class="text-center">Data</th>
          <th class="text-center">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $listSolicitacoes = $solicitacaoDAO->getAll();
          if(sizeof($listSolicitacoes) > 0) {
            foreach($listSolicitacoes as $objVo) {
              printf('<tr class="gradeU">');
                printf('<td class="text-center">');
                  printf('<a href="?pg=alterar&lc=contato&id=%s" class="btn btn-dark" data-toggle="tooltip" alt="Ver solicitação" title="Ver solicitação"><i class="fas fa-pencil-alt"></i></a> ', $objVo->getIdsolicitacao());
                  // printf('<a href="#" class="btn btn-danger removeSolicitacao" id="%s" data-toggle="tooltip" alt="Remover aluno" title="Remover aluno"><i class="fas fa-trash"></i></a> ', $objVo->getIdsolicitacao());
                  // printf('<a href="#" class="btn btn-%s desativaCategoria" id="%s" data-toggle="tooltip" alt="Desativar aluno" title="Desativar aluno"><i class="fas fa-%s"></i></a> ', $objVo->getAtivo() == '1' ? 'warning' : 'success', $objVo->getIdsolicitacao(), $objVo->getAtivo() == '1' ? 'times' : 'check');
                printf('</td>');
                printf('<td class="text-center">%s</td>', $objVo->getNome());
                printf('<td class="text-center">%s<br><small>%s</small></td>', $objVo->getEmail(), $objVo->getTelefone());
                printf('<td class="text-center">%s...</td>', substr($objVo->getMensagem(), 0, 50));
                $d = DateTime::createFromFormat('Y-m-d H:i:s', $objVo->getData());
                printf('<td class="text-center">%s</td>', $d->format('d/m/Y \á\s H:i:s'));
                printf('<td class="text-center font-weight-bolder">%s</td>', getStatusContato(null, $objVo->getStatus()));
              printf('</tr>');
            }
          }
        ?>
      </tbody>
    </table>
  </div>
</div>