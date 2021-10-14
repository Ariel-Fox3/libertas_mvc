<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.Categoria_ProdutoVO.php");
include("../../../class/DAO/class.Categoria_ProdutoDAO.php");

$categoriaDAO = new Categoria_ProdutoDAO();


$pages = explode(',', $_POST['str']);
$cnt = 0;
foreach ($pages as $idp) {
  $categoria = $categoriaDAO->getById($idp);
  if($categoria->getIdcategoria_produto() != null) {  
    $categoria->setOrdem($cnt);
    $categoriaDAO->save($categoria);
    $cnt++;
  }
}

?>