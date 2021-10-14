<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.ServicoVO.php");
include("../../../class/DAO/class.ServicoDAO.php");

$servicoDAO = new ServicoDAO();

if(isset($_POST['id'])) {
	$servico = $servicoDAO->getById($_POST['id']);
	if($servico->getIdservico()) {
    if ($servico->getAtivo() == 1) {
      $servico->setAtivo(0);
      echo 'Desativado com sucesso.';
    } else {
      $servico->setAtivo(1);
      echo 'Ativado com sucesso.';
    }
		$servicoDAO->save($servico);
	}
}

?>