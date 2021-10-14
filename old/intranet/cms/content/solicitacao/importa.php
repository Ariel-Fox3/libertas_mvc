<?php
include("../class/class.Conexao.php");
include("../class/class.SolicitacaoVO.php");
include("../class/DAO/class.SolicitacaoDAO.php");
include("../class/class.ClienteVO.php");
include("../class/DAO/class.ClienteDAO.php");

$solicitacaoDAO = new SolicitacaoDAO();
$clienteDAO = new ClienteDAO();

dbcon();

$arquivo = fopen ('../lista12.csv', 'r');

while(!feof($arquivo)) {
  $linha = fgets($arquivo, 1024);
  $dados = explode(';', $linha);
  if($dados[0] != 'unidade' && !empty($linha)) {

    $idcliente = $clienteDAO->jaExiste($dados[2]);

    if($idcliente == null) {
      //echo "novo";
      $cliente = new ClienteVO();
      $cliente->setNome($dados[1]);
      $cliente->setFone_cel($dados[3]);
      $cliente->setEmail($dados[2]);
      $clienteDAO->save($cliente);
    } else {
      $cliente = $clienteDAO->getById($idcliente);
    }

    $cliente->getIdcliente();

    if($cliente != null) {

      $solicitacao = new SolicitacaoVO();
      $solicitacao->setIdunidade($dados[0]);
      $solicitacao->setIdcliente($cliente->getIdcliente());
      $solicitacao->setStatus("Aguardando atendimento");

      $addmsg = "CAMPANHA VIA FACEBOOK!\n";
      $addmsg .= "\n\n";
      $addmsg .= "Cliente tem o interesse em fazer matrícula na unidade - PLANO: R$ 89,90";

      $solicitacao->setDescricao($addmsg);
      $solicitacao->setIdusuario(13);
      $solicitacaoDAO->save($solicitacao);
    }
  }
}
fclose($arquivo);
