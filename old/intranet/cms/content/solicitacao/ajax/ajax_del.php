<?php 
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.SolicitacaoVO.php");
include("../../../class/DAO/class.SolicitacaoDAO.php");

$solicitacaoDAO = new SolicitacaoDAO();

if(isset($_POST['id'])) {
	$solicitacao = $solicitacaoDAO->getById($_POST['id']);
	if($solicitacao->getIdsolicitacao()) {
		$solicitacaoDAO->delete($solicitacao);
	}
}

?>