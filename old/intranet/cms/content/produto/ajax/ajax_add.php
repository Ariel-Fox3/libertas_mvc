<?php 
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.Func.php");
include("../../../class/class.ProdutoVO.php");
include("../../../class/DAO/class.ProdutoDAO.php");

$capa_ext = array('jpeg', 'jpg', 'png', 'gif');
$capa_path = '../../../../uploads/';
$capa_size = 9999999;

$produtoDAO = new ProdutoDAO();
$produto = new ProdutoVO();
$produto->setNome($_POST['inputNome']);
$produto->setDescricao($_POST['inputDescricao']);
$c = $_POST['inputIdcategoria'];
if (is_array($c) && sizeof($c) > 0) {
	$c = json_encode($c);
}
$produto->setIdcategoria($c);
$produto->setUrl_video($_POST['inputUrlVideo']);
$produto->setJoined(date('Y-m-d H:i:s'));
if (isset($_POST['inputAtivo']) && $_POST['inputAtivo'] == '1') {
	$produto->setAtivo(1);
} else {
	$produto->setAtivo(0);
}

$count = $produtoDAO->getCount();
$produto->setOrdem($count);

if(isset($_FILES['inputImagem']['name'])) {
	$ext_capa = strtolower(pathinfo($_FILES['inputImagem']['name'], PATHINFO_EXTENSION));
	if (in_array($ext_capa , $capa_ext)) {
		if ($_FILES['inputImagem']['size'] < $capa_size) {
			$img_capa = "img_produto_".getStringLink($produto->getNome()).'_'.time() . "." . $ext_capa;
			if (move_uploaded_file($_FILES['inputImagem']['tmp_name'], $capa_path . $img_capa)) { 				
				$produto->setImagem($img_capa);
			}
		}
	}
}

$produto = $produtoDAO->save($produto);
printf('<div class="alert alert-success">Produto <b>%s</b> cadastrado com sucesso.</div>', $produto->getNome());
?>