<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.ServicoVO.php");
include("../../../class/DAO/class.ServicoDAO.php");

$servicoDAO = new ServicoDAO();

if(isset($_POST['id'])) {
	$servico = $servicoDAO->getById($_POST['id']);
	if($servico->getIdservico()) {
		// unlink('../../../../uploads/'.$categoria->getImagem());
		$servicoDAO->delete($servico);
	}
}

?>