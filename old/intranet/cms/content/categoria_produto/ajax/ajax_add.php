<?php 
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.Func.php");
include("../../../class/class.Categoria_ProdutoVO.php");
include("../../../class/DAO/class.Categoria_ProdutoDAO.php");

$capa_ext = array('jpeg', 'jpg', 'png', 'gif');
$capa_path = '../../../../uploads/';
$capa_size = 5000000;

$categoria_produtoDAO = new Categoria_ProdutoDAO();
$categoria_produto = new Categoria_ProdutoVO();
$categoria_produto->setNome($_POST['inputNome']);
// if (isset($_POST['inputAtivo']) && $_POST['inputAtivo'] == '1') {
$categoria_produto->setAtivo(1);
// } else {
// 	$categoria_produto->setAtivo(0);
// }
$categoria_produto->setDescricao($_POST['inputDescricao']);
$categoria_produto->setJoined(date('Y-m-d H:i:s'));
if(isset($_FILES['inputImagem']['name'])) {
	$ext_capa = strtolower(pathinfo($_FILES['inputImagem']['name'], PATHINFO_EXTENSION));
	if (in_array($ext_capa , $capa_ext)) {
		if ($_FILES['inputImagem']['size'] < $capa_size) {
			$img_capa = "img_categoria_produto_".getStringLink($categoria_produto->getNome()).'_'.time() . "." . $ext_capa;
			if (move_uploaded_file($_FILES['inputImagem']['tmp_name'], $capa_path . $img_capa)) { 				
				$categoria_produto->setImagem($img_capa);
			}
		}
	}
}

$categoria_produto = $categoria_produtoDAO->save($categoria_produto);
printf('<div class="alert alert-success">Categoria <b>%s</b> cadastrada com sucesso.</div>', $categoria_produto->getNome());
?>