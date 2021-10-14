<?php
include("../class/class.Conexao.php");
include("../class/class.PaginaVO.php");
include("../class/DAO/class.PaginaDAO.php");

dbcon();
$paginaDAO = new PaginaDAO();
$paginas = $paginaDAO->getAll();
if(sizeof($paginas)>0) {
  foreach($paginas as $objVo) {
    $paginaDAO->save($objVo);
  }
}

?>
