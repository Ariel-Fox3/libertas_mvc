<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.Categoria_ProdutoVO.php");
include("../../../class/DAO/class.Categoria_ProdutoDAO.php");

$categoria_produtoDAO = new Categoria_ProdutoDAO();

if(isset($_POST['id'])) {
	$categoria_produto = $categoria_produtoDAO->getById($_POST['id']);
	if($categoria_produto->getIdcategoria_produto()) {
		// unlink('../../../../uploads/'.$categoria->getImagem());
		$categoria_produtoDAO->delete($categoria_produto);
	}
}

?>