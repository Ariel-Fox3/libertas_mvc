<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.SolicitacaoVO.php");
include("../../../class/DAO/class.SolicitacaoDAO.php");
include("../../../class/class.ClienteVO.php");
include("../../../class/DAO/class.ClienteDAO.php");

$solicitacaoDAO = new SolicitacaoDAO();
$clienteDAO = new ClienteDAO();

dbcon();

if($_POST['inputUnidade'] != null) {

	$solicitacao = new SolicitacaoVO();
	$solicitacao->setIdunidade($_POST['inputUnidade']);
	$solicitacao->setIdcliente($_POST['inputCliente']);
	$solicitacao->setStatus($_POST['inputStatus']);
	$solicitacao->setDescricao($_POST['inputDesc']);
	$solicitacao->setIdusuario($_POST['idusuario']);
}

$solicitacaoDAO->save($solicitacao);
printf("<div class='alert alert-success' role='alert'>Solicitação criada com sucesso!</div>");
?>