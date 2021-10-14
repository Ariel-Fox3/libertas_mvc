<?php
include("../class/class.Conexao.php");
include("../class/class.SolicitacaoVO.php");
include("../class/DAO/class.SolicitacaoDAO.php");
include("../class/class.UnidadeVO.php");
include("../class/DAO/class.UnidadeDAO.php");
include("../class/class.UsuarioVO.php");
include("../class/DAO/class.UsuarioDAO.php");
include("../class/class.ClienteVO.php");
include("../class/DAO/class.ClienteDAO.php");
include("../class/class.Funcao.php");
dbcon();
$solicitacaoDAO = new SolicitacaoDAO();
$unidadeDAO = new UnidadeDAO();
$usuarioDAO = new UsuarioDAO();
$clienteDAO = new ClienteDAO();


function getCor($cor) {
    switch ($cor) {
        case 'Aguardando atendimento':
            return "default";
            break;
        case 'Em aberto':
            return "primary";
            break;
        case 'Matriculado':
            return "success";
            break;
        case 'Finalizado':
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
@$unidade = $_GET['unidade'] ? $_GET['unidade'] : "1";
@$status = $_GET['status'] ? $_GET['status'] : "Aguardando atendimento";

if(!empty($idunidade) || isset($idunidade)) {
  $listSolicitacao = $solicitacaoDAO->getByFiltro($status, $idunidade);
} else {
  $listSolicitacao = $solicitacaoDAO->getByFiltro($status, $unidade);
}
?>
<script>
/*function Refresh() {
 setTimeout("refreshPage();", 120000);
}
function refreshPage() {
    window.location = location.href;
}*/
$('document').ready(function() {
        //window.onload = Refresh;
        $('a.removeSolicitacao').click(function() {

            var del_id = $(this).attr('id');
            var parent = $(this).parent();
            $.post('solicitacao/ajax/ajax_del.php', {
                id : del_id
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
        $('a.finalSolicitacao').click(function(e) {
            e.preventDefault();
            var del_id = $(this).attr('id');
            var parent = $(this).parent();
            $.post('solicitacao/ajax/ajax_final.php', {
                id : del_id
            }, function(data) {

                if (data == '') {
                    parent.slideUp('slow', function() {
                        //$(this).remove();
                        //$(this).remove();
                        alert("Solicitação finalizada!");
                        //$(this).closest('tr').remove();
                        $(this).closest('tr').remove();

                    });
                } else {
                    alert(data);
                }
            });
        });
    });

</script>
<div id="usuario">
	        <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Gerenciar solicitações</b>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <form method="get">
                                    <input type="hidden" name="pg" value="index">
                                    <input type="hidden" name="lc" value="solicitacao">

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <select name="status" class="form-control">
                                                <option value="Todos" <?=$status == "" ? "selected" : ""?>>-- Todos --</option>
                                                <option value="Aguardando atendimento" <?=$status == "Aguardando atendimento" ? "selected" : ""?>>Aguardando atendimento</option>
                                                <option value="Em aberto" <?=$status == "Em aberto" ? "selected" : ""?>>Em aberto</option>
                                                <option value="Matriculado" <?=$status == "Matriculado" ? "selected" : ""?>>Matriculado</option>
                                                <option value="Finalizado" <?=$status == "Finalizado" ? "selected" : ""?>>Finalizado</option>
                                                <option value="Cancelado" <?=$status == "Cancelado" ? "selected" : ""?>>Cancelado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php if ($admin == 1 || $solicitacao == 1) { ?>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <select name="unidade" class="form-control">
                                                <option value="Todos" <?=$unidade == "" ? "selected" : "";?>>-- Todos --</option>

                                                <?php 
                                                $unidadesAll = $unidadeDAO->getAll();
                                                if(count($unidadesAll)>0) {
                                                    foreach ($unidadesAll as $objvoU) {
                                                        printf("<option value='%s' %s>%s</option>", $objvoU->getIdunidade(), $objvoU->getIdunidade() == $unidade ? "selected" : "", $objvoU->getNome());

                                                    }
                                                }

                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if ($admin == 1 || $solicitacao == 1) { ?>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <input type="text" placeholder="Data inc." class="form-control">
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if ($admin == 1 || $solicitacao == 1) { ?>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <input type="text" placeholder="Data fim" class="form-control">
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-sm-1">
                                        <button class="btn btn-block btn-primary">Filtrar</button>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Data</th>
                                            <th>Status</th>
                                            <th>Unidade</th>
                                            <th>Cliente</th>
                                            <th>Descrição</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php
                                     	if(sizeof($listSolicitacao) > 0) {
                                        $ag = 0;
                                     		foreach($listSolicitacao as $objVo) {
                                                //if($objVo->getData_final() == "" && $objVo->getStatus() != "Finalizado") {
                                                    $cliente = $clienteDAO->getById($objVo->getIdcliente());
                                                    $unidade = $unidadeDAO->getById($objVo->getIdunidade());
                                                    printf('<tr class="gradeU">');
                                                    printf('<td>');
                                                    printf('<a href="?pg=alterar&lc=solicitacao&id=%s" class="btn btn-dark"><i class="glyphicon glyphicon-info-sign"></i></a> ', $objVo->getIdsolicitacao());
                                                    if($admin == 1) {
                                                        printf('<a href="#" class="finalSolicitacao btn btn-dark" id="%s"><i class="fas fa-check-sign"></i></a> ', $objVo->getIdsolicitacao());
                                                        printf('<a href="#" class="removeSolicitacao btn btn-dark" id="%s"><i class="fas fa-trash"></i></a> ', $objVo->getIdsolicitacao());
                                                    }
                                                    printf('</td>');
                                                    printf('<td>%s<br><small>%s</small></td>', timeAgo($objVo->getData()), formatData($objVo->getData()));
                                                    printf('<td><span class="label label-%s">%s</span></td>', getCor($objVo->getStatus()), $objVo->getStatus());
                                                    printf('<td>%s</td>', $unidade->getNome());
                                                    if($cliente != null) {
                                                    printf('<td>%s</td>', $cliente->getNome());
                                                    } else {
                                                    printf('<td>%s</td>', "<small>Usuário desconhecido</small>");                                                        
                                                    } 
                                                    printf('<td>%s(...)</td>', substr($objVo->getDescricao(), 0, 20));
                                                    printf('</tr>');


                                                    if($objVo->getStatus() == "Aguardando atendimento") {
                                                      $ag++;
                                                    }
                                                //}
                                            }
                                         } else {
                                            printf('<p class="text-uppercase"><strong>Nenhuma solicitação no momento.</strong></p>');
                                         }


                                         if($ag > 15 && $status == "Aguardando atendimento") {
                                            printf('<div class="alert alert-danger" role="alert"><strong>Atenção:</strong> <strong>Existem muitos atendimentos em aberto.</strong></div>');
                                         } 

                                         ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

</div>
