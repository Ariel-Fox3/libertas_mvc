<?php
include("../../../class/class.Conexao.php");
include("../../../class/class.PaginaVO.php");
include("../../../class/DAO/class.PaginaDAO.php");

$paginaDAO = new PaginaDAO();

$capa_ext = array('jpeg', 'jpg', 'png', 'gif', 'pdf');
$capa_size = 40000000;
$capa_path = '../../../../uploads/';


$pagina = $paginaDAO->getById($_POST['idpagina']);
if($pagina != null) {
  $pagina->setNome($_POST['inputNome']);
  $pagina->setAtivo('1');

  if (isset($_POST['chkExterno'])) {
    $pagina->setExterno('1');
    $pagina->setLink($_POST['inputUrl']);
  } else {
    $pagina->setExterno('0');
    $pagina->setLink('');
  }
  
	if($_POST['inputIdsublink'] != 0) {
    $pagina->setSublink(1);
    $pagina->setIdsublink($_POST['inputIdsublink']);
    $pagina->setFixo('0');
	} else {
    $pagina->setSublink(0);
    $pagina->setIdsublink(0);
    $pagina->setFixo('1');
  }

	$pagina->setConteudo($_POST['inputConteudo']);
  $paginaDAO->save($pagina);
  printf("<div class='alert alert-success' role='alert'>Página alterada com sucesso!</div>");
}
?>
