<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.NoticiaVO.php");
include("../../../class/DAO/class.NoticiaDAO.php");

$noticiaDAO = new NoticiaDAO();

if(isset($_POST['id'])) {
	$noticia = $noticiaDAO->getById($_POST['id']);
	if($noticia->getIdnoticia()) {
    if ($noticia->getAtivo() == 1) {
      $noticia->setAtivo(0);
      echo 'Desativado com sucesso.';
    } else {
      $noticia->setAtivo(1);
      echo 'Ativado com sucesso.';
    }
		$noticiaDAO->save($noticia);
	}
}

?>