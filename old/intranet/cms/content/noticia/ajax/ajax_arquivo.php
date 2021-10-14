<?php
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.ArquivoVO.php");
include("../../../class/DAO/class.ArquivoDAO.php");

$arquivoDAO = new ArquivoDAO();


$capa_ext = array('jpeg', 'jpg', 'png', 'gif', 'pdf');
$capa_size = 40000000;
$capa_path = '../../../../uploads/';


if($_POST['inputNome'] != null) {

	$arquivo = new ArquivoVO();
	$arquivo->setNome($_POST['inputNome']);
	$arquivo->setIdnoticia($_POST['idnoticia']);
	$data = DateTime::createFromFormat('d/m/Y', $_POST['inputData']);
	$arquivo->setData($data->format('Y-m-d ') . '00:00:00');
	$arquivo->setUrl($_POST['inputUrl']);
	$arquivo->setTipo('arquivo_pagina');
	$arquivo->setJoined(date('Y-m-d H:i:s'));
	$arquivo->setAtivo(1);
	if (isset($_FILES['inputImagem']['name'])) {
		$ext_capa = strtolower(pathinfo($_FILES['inputImagem']['name'], PATHINFO_EXTENSION));
		if (in_array($ext_capa , $capa_ext)) {
			if ($_FILES['inputImagem']['size'] < $capa_size) {
				$img_capa = "img".time() . "." . $ext_capa;
				if (move_uploaded_file($_FILES['inputImagem']['tmp_name'], $capa_path . $img_capa)) {
					// tudo certo com o envio da imagem, salvando arquivos
					$arquivo->setFile($img_capa);
				}
			}
		}
	}
}

$arquivoDAO->save($arquivo);
printf("<div class='alert alert-success' role='alert'>Arquivo enviado com sucesso!</div><script>location.reload();</script>");
?>
