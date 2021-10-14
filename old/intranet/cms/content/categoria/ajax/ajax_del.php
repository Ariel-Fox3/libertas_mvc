<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.CategoriaVO.php");
include("../../../class/DAO/class.CategoriaDAO.php");

$categoriaDAO = new CategoriaDAO();

if(isset($_POST['id'])) {
	$categoria = $categoriaDAO->getById($_POST['id']);
	if($categoria->getIdcategoria()) {
		// unlink('../../../../uploads/'.$categoria->getImagem());
		$categoriaDAO->delete($categoria);
	}
}

?>