<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.Categoria_ProdutoVO.php");
include("../../../class/DAO/class.Categoria_ProdutoDAO.php");

$categoria_produtoDAO = new Categoria_ProdutoDAO();

if(isset($_POST['id'])) {
	$categoria_produto = $categoria_produtoDAO->getById($_POST['id']);
	if($categoria_produto->getIdcategoria_produto()) {
    if ($categoria_produto->getAtivo() == 1) {
      $categoria_produto->setAtivo(0);
      echo 'Desativado com sucesso.';
    } else {
      $categoria_produto->setAtivo(1);
      echo 'Ativado com sucesso.';
    }
		$categoria_produtoDAO->save($categoria_produto);
	}
}

?>