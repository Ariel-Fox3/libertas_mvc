<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.NoticiaVO.php");
include("../../../class/DAO/class.NoticiaDAO.php");

$noticiaDAO = new NoticiaDAO();

if(isset($_POST['id'])) {
	$noticia = $noticiaDAO->getById($_POST['id']);
	if($noticia->getIdnoticia()) {
		unlink('../../../../uploads/'.$noticia->getImagem());
		$noticiaDAO->delete($noticia);
	}
}

?>