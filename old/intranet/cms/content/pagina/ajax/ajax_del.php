<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.PaginaVO.php");
include("../../../class/DAO/class.PaginaDAO.php");

$paginaDAO = new PaginaDAO();

if(isset($_POST['id'])) {
	$pagina = $paginaDAO->getById($_POST['id']);
	if($pagina->getIdpagina() != null) {
    if ($pagina->getAtivo() == '1') {
      //unlink('../../../../uploads/'.$vereador->getFoto());
      $paginaDAO->delete($pagina);
    } else {
      $pagina->setAtivo('1');
      $paginaDAO->save($pagina);
    }
	}
}

?>