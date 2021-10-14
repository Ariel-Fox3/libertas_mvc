<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.CategoriaVO.php");
include("../../../class/DAO/class.CategoriaDAO.php");

$categoriaDAO = new CategoriaDAO();

if(isset($_POST['id'])) {
	$categoria = $categoriaDAO->getById($_POST['id']);
	if($categoria->getIdcategoria()) {
    if ($categoria->getAtivo() == 1) {
      $categoria->setAtivo(0);
      echo 'Desativado com sucesso.';
    } else {
      $categoria->setAtivo(1);
      echo 'Ativado com sucesso.';
    }
		$categoriaDAO->save($categoria);
	}
}

?>