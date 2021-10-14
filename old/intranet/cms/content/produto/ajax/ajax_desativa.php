<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.ProdutoVO.php");
include("../../../class/DAO/class.ProdutoDAO.php");

$produtoDAO = new ProdutoDAO();

if(isset($_POST['id'])) {
	$produto = $produtoDAO->getById($_POST['id']);
	if($produto->getIdproduto()) {
    if ($produto->getAtivo() == 1) {
      $produto->setAtivo(0);
      echo 'Desativado com sucesso.';
    } else {
      $produto->setAtivo(1);
      echo 'Ativado com sucesso.';
    }
		$produtoDAO->save($produto);
	}
}

?>