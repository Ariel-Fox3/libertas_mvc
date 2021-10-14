<?php 
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.Func.php");
include("../../../class/class.CategoriaVO.php");
include("../../../class/DAO/class.CategoriaDAO.php");

$capa_ext = array('jpeg', 'jpg', 'png', 'gif');
$capa_path = '../../../../uploads/';
$capa_size = 5000000;

$categoriaDAO = new CategoriaDAO();
$categoria = new CategoriaVO();
$categoria->setNome($_POST['inputNome']);
// if (isset($_POST['inputAtivo']) && $_POST['inputAtivo'] == '1') {
$categoria->setAtivo(1);
// } else {
// 	$categoria->setAtivo(0);
// }
$categoria->setDescricao($_POST['inputDescricao']);
$categoria->setJoined(date('Y-m-d H:i:s'));
if(isset($_FILES['inputImagem']['name'])) {
	$ext_capa = strtolower(pathinfo($_FILES['inputImagem']['name'], PATHINFO_EXTENSION));
	if (in_array($ext_capa , $capa_ext)) {
		if ($_FILES['inputImagem']['size'] < $capa_size) {
			$img_capa = "img_categoria_".getStringLink($categoria->getNome()).'_'.time() . "." . $ext_capa;
			if (move_uploaded_file($_FILES['inputImagem']['tmp_name'], $capa_path . $img_capa)) { 				
				$categoria->setImagem($img_capa);
			}
		}
	}
}

$categoria = $categoriaDAO->save($categoria);
printf('<div class="alert alert-success">Categoria <b>%s</b> cadastrada com sucesso.</div>', $categoria->getNome());
?>