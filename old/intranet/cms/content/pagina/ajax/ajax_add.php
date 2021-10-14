<?php
include("../../../class/class.Conexao.php");
include("../../../class/class.PaginaVO.php");
include("../../../class/DAO/class.PaginaDAO.php");

$paginaDAO = new PaginaDAO();

// dbcon();

$capa_ext = array('jpeg', 'jpg', 'png', 'gif', 'pdf');
$capa_size = 40000000;
$capa_path = '../../../../uploads/';


if($_POST['inputNome'] != null) {
	$pagina = new PaginaVO();
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
    $count = $paginaDAO->getCount($_POST['inputIdsublink']);
	} else {
    $pagina->setSublink(0);
    $pagina->setIdsublink(0);
    $pagina->setFixo('1');
    $count = $paginaDAO->getCount();
  }

  $pagina->setOrdem($count);
  
  // $pagina->setElements($_POST['jsonExtras']);

	$pagina->setConteudo($_POST['inputConteudo']);
  $paginaDAO->save($pagina);
  printf("<div class='alert alert-success' role='alert'>Página criada com sucesso!</div>");
}
?>
