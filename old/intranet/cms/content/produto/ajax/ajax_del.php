<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.ProdutoVO.php");
include("../../../class/DAO/class.ProdutoDAO.php");

$produtoDAO = new ProdutoDAO();


if(isset($_POST['id'])) {
	$produto = $produtoDAO->getById($_POST['id']);
	if($produto->getIdproduto()) {
		$produtoDAO->delete($produto);
	}
}

?>