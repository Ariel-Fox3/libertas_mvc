<?php
include("../class/class.Conexao.php");
include("../class/class.SolicitacaoVO.php");
include("../class/DAO/class.SolicitacaoDAO.php");
include("../class/class.UsuarioVO.php");
include("../class/DAO/class.UsuarioDAO.php");
include("../class/class.Func.php");
$solicitacaoDAO = new SolicitacaoDAO();
$usuarioDAO = new UsuarioDAO();

function getCor($cor) {
    switch ($cor) {
        case 'Aguardando atendimento':
            return "default";
            break;
        case 'Atendimento realizado':
            return "primary";
            break;
        case 'Visita marcada':
            return "success";
            break;
        case 'Cancelado':
            return "warning";
            break;
        default:
            return "default";
            break;
    }
}
@$status = $_GET['status'] ? $_GET['status'] : null;
@$l = isset($_GET['l']) && $_GET['l'] != '' ? $_GET['l'] : 25;
@$o = isset($_GET['o']) && $_GET['o'] != '' ? $_GET['o'] : 1;

if (isset($status)) {
  $listSolicitacao = $solicitacaoDAO->getByFiltro($status);
} else {
  if ($l == 'todos') {
    $listSolicitacao = $solicitacaoDAO->getAll(null, null, true);  
  } else {
    $listSolicitacao = $solicitacaoDAO->getAll($l, ($o-1) * $l, true);
  }
}
?>
<script>
function Refresh() {
  setTimeout("refreshPage();", 120000);
}

function refreshPage() {
  window.location = location.href;
}
$('document').ready(function() {

  $('#select-view').change(function () {
    location.href = '?pg=index&lc=solicitacao&l='+$(this).val()+'&o=1';
  });

  window.onload = Refresh;
  $('a.removeSolicitacao').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('solicitacao/ajax/ajax_del.php', {
      id: del_id
    }, function(data) {

      if (data == '') {
        location.reload();
      } else {
        alert(data);
      }
    });
  });
  $('a.finalSolicitacao').click(function(e) {
    e.preventDefault();
    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('solicitacao/ajax/ajax_final.php', {
      id: del_id
    }, function(data) {

      if (data == '') {
        location.reload();
      } else {
        alert(data);
      }
    });
  });
});
</script>

<div class="card">
  <div class="card-header">Gerenciar solicitações</div>
  <div class="card-body">
    <div class="alert alert-info" role="alert"><strong>Atualização automática:</strong> informações são atualizadas a
      cada <strong>2 minutos.</strong></div>

    <div class="row">
      <div class="col-md-2">
        <select class="form-control" id="select-view">
          <option value="">Mostrar</option>
          <option value="25" <?=$l == 25 ? 'selected' : ''?>>Mostrar 25</option>
          <option value="50" <?=$l == 50 ? 'selected' : ''?>>Mostrar 50</option>
          <option value="100" <?=$l == 100 ? 'selected' : ''?>>Mostrar 100</option>
          <option value="todos" <?=$l == 'todos' ? 'selected' : ''?>>Mostrar todos</option>
        </select>
      </div>
      <div class="col text-right">
        <nav aria-label="Page navigation">
          <ul class="pagination d-inline-flex">
            <li class="page-item <?=$o-1 > 0 ? '' : 'disabled'?>">
              <a class="page-link" href="?pg=index&lc=solicitacao&l=<?=$l?>&o=<?=$o-1?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
              </a>
            </li>
            <?php
              $countPneus = $solicitacaoDAO->getCount();
              // print_r($countPneus);
              if ($l == 'todos') {
                $list = 1;
              } else {
                $list = ceil($countPneus / $l);
              }

              for ($i = $o-4; $i < $o; $i++) {
                if ($i > 0) {
                  printf('<li class="page-item %s"><a class="page-link" href="?pg=index&lc=solicitacao&l=%s&o=%s">%s</a></li>', $o == $i ? 'active' : '', $l, $i, $i);
                }
              }
              for ($i = $o; $i <= $o+4; $i++) {
                if ($i <= $list) {
                  printf('<li class="page-item %s"><a class="page-link" href="?pg=index&lc=solicitacao&l=%s&o=%s">%s</a></li>', $o == $i ? 'active' : '', $l, $i, $i);
                }
              }
            ?>
            <li class="page-item <?=$o+1 <= $list ? '' : 'disabled'?>">
              <a class="page-link" href="?pg=index&lc=solicitacao&l=<?=$l?>&o=<?=$o+1?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <form methos="get">
      <input type="hidden" name="pg" value="index">
      <input type="hidden" name="lc" value="solicitacao">
      <div class="form-group">
        <div class="row">
          <div class="col-md-5">
            <select name="status" class="form-control">
              <option value="">Ver tudo</option>
              <option value="Aguardando atendimento" <?=$status == "Aguardando atendimento" ? "selected" : ""?>>
                Aguardando atendimento</option>
              <option value="Em aberto" <?=$status == "Em aberto" ? "selected" : ""?>>Em aberto</option>
              <option value="Finalizado" <?=$status == "Finalizado" ? "selected" : ""?>>Finalizado</option>
              <option value="Cancelado" <?=$status == "Cancelado" ? "selected" : ""?>>Cancelado</option>
            </select>
          </div>
          <div class="col-md-2">
            <button class="btn btn-labeled btn-primary btn-block">
              <span class="btn-label"><i class="fas fa-search"></i></span>
              Filtrar
            </button>
            <!-- <button class="btn btn-block btn-primary">Filtrar</button> -->
          </div>
        </div>
      </div>
    </form>

    <?php
          $ag = 0;
          if(sizeof($listSolicitacao) > 0) {
            printf('<table class="table table-striped table-bordered table-hover" id="dataTables-example">');
              printf('<thead>');
                printf('<tr>');
                  printf('<th style="min-width: 150px" class="text-center"></th>');
                  printf('<th class="text-center">Data</th>');
                  printf('<th class="text-center">Status</th>');
                  printf('<th class="text-center">Cliente</th>');
                  printf('<th class="text-center">Descrição</th>');
                printf('</tr>');
              printf('</thead>');
              printf('<tbody>');
              foreach($listSolicitacao as $objVo) {
                printf('<tr class="gradeU">');
                printf('<td class="text-center">');
                printf('<a href="?pg=alterar&lc=solicitacao&id=%s" class="btn btn-dark"><i class="fas fa-pencil-alt"></i></a> ', $objVo->getIdsolicitacao());
                if ($objVo->getStatus() != 'Finalizado' && $objVo->getStatus() != 'Cancelado') {
                  printf('<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Finalizar solicitação" class="finalSolicitacao btn btn-info" id="%s"><i class="fas fa-check"></i></a> ', $objVo->getIdsolicitacao());
                }
                printf('<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Remover solicitação" class="removeSolicitacao btn btn-danger" id="%s"><i class="fas fa-trash"></i></a> ', $objVo->getIdsolicitacao());
                printf('</td>');
                printf('<td class="text-center">%s<br><small>%s</small></td>', timeAgo($objVo->getData()), formatData($objVo->getData()));
                printf('<td class="text-center"><span class="label label-%s">%s</span></td>', getCor($objVo->getStatus()), $objVo->getStatus());
                printf('<td class="text-center">%s</td>', $objVo->getNome());
                if ($objVo->getIdproduto() == '0') {
                  printf('<td class="text-center">%s(...)</td>', substr(strip_tags($objVo->getMensagem()), 0, 60));
                } else {
                  printf('<td class="text-center"><div class="badge badge-primary">Contato para produto</div></td>');
                }
                printf('</tr>');


                if($objVo->getStatus() == "Aguardando atendimento") {
                  $ag++;
                }
              }
              printf('</tbody>');
            printf('</table>');
          } else {
            printf('<div class="alert alert-warning"><strong>Nenhuma solicitação no momento.</strong></div>');
          }
          if($ag > 15 && $status == "Aguardando atendimento") {
            printf('<div class="alert alert-danger" role="alert"><strong>Atenção:</strong> <strong>Existem muitos atendimentos em aberto.</strong></div>');
          } 

        ?>

  </div>
</div>