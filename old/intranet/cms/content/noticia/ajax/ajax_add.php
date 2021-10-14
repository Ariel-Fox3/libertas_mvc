<?php 
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.Func.php");
include("../../../class/class.NoticiaVO.php");
include("../../../class/DAO/class.NoticiaDAO.php");

$capa_ext = array('jpeg', 'jpg', 'png', 'gif');
$capa_path = '../../../../uploads/';
$capa_size = 5000000;

$noticiaDAO = new NoticiaDAO();
$noticia = new NoticiaVO();
$noticia->setNome($_POST['inputNome']);
if (isset($_POST['inputAtivo']) && $_POST['inputAtivo'] == '1') {
	$noticia->setAtivo(1);
} else {
	$noticia->setAtivo(0);
}
if (isset($_POST['inputDestaque']) && $_POST['inputDestaque'] == '1') {
	$noticia->setDestaque(1);
} else {
	$noticia->setDestaque(0);
}
$noticia->setConteudo($_POST['inputConteudo']);
$d = DateTime::createFromFormat('d/m/Y', $_POST['inputData']);
$noticia->setData($d->format('Y-m-d').' 00:00:00');
// $arr_tags = array();
// $tags = $_POST['inputTags'];
// $tags = explode(',', $tags);
// if (sizeof($tags) > 0) {
// 	foreach ($tags as $tag) {
// 		$arr_tags[] = sprintf('"%s"', $tag);
// 	}
// }
$noticia->setTags($_POST['inputTags']);
$noticia->setIdcategoria($_POST['inputCategoria']);
$noticia->setOrdem($noticiaDAO->countByIdcategoria($_POST['inputCategoria']));

if(isset($_FILES['inputImagem']['name'])) {
	$ext_capa = strtolower(pathinfo($_FILES['inputImagem']['name'], PATHINFO_EXTENSION));
	if (in_array($ext_capa , $capa_ext)) {
		if ($_FILES['inputImagem']['size'] < $capa_size) {
			$img_capa = "img_noticia_".getStringLink($noticia->getNome()).'_'.$d->format('dmY') . "." . $ext_capa;
			if (move_uploaded_file($_FILES['inputImagem']['tmp_name'], $capa_path . $img_capa)) { 				
				$noticia->setImagem($img_capa);
			}
		}
	}
}

$noticia = $noticiaDAO->save($noticia);
printf('<div class="alert alert-success">Notícia <b>%s</b> cadastrada com sucesso.</div>', $noticia->getNome());
?>