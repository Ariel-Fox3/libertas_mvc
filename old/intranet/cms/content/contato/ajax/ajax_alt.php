<?php 
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.Func.php");
include("../../../class/class.SolicitacaoVO.php");
include("../../../class/DAO/class.SolicitacaoDAO.php");

$solicitacaoDAO = new SolicitacaoDAO();
$idsolicitacao = $_POST['idsolicitacao'];
$solicitacao = $solicitacaoDAO->getById($idsolicitacao);
$solicitacao->setStatus($_POST['inputStatus']);
$solicitacao->setObs($_POST['inputObs']);
$solicitacao->setIdusuario($_POST['idusuario']);
$solicitacao->setData_final(date('Y-m-d H:i:s'));
$solicitacao = $solicitacaoDAO->save($solicitacao);
printf('<div class="alert alert-success">Solicitação de <b>%s</b> alterada com sucesso.</div>', $solicitacao->getNome());
?>