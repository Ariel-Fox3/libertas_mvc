<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.ArquivoVO.php");
include("../../../class/DAO/class.ArquivoDAO.php");

$arquivoDAO = new ArquivoDAO();

if(isset($_POST['id'])) {
	$arquivo = $arquivoDAO->getById($_POST['id']);
	if($arquivo->getIdarquivo() != null) {
		unlink('../../../../uploads/'.$arquivo->getFile());
		$arquivoDAO->delete($arquivo);
	}
}

?>