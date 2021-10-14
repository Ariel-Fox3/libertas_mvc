<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.PaginaVO.php");
include("../../../class/DAO/class.PaginaDAO.php");

$paginaDAO = new PaginaDAO();


$pages = explode(',', $_POST['str']);
$cnt = 0;
foreach ($pages as $idp) {
  $pagina = $paginaDAO->getById($idp);
  if($pagina->getIdpagina() != null) {  
    $pagina->setOrdem($cnt);
    $paginaDAO->save($pagina);
    $cnt++;
  }
}

?>