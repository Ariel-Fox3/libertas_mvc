<?php 
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.Func.php");
include("../../../class/class.ServicoVO.php");
include("../../../class/DAO/class.ServicoDAO.php");

$capa_ext = array('jpeg', 'jpg', 'png', 'gif');
$capa_path = '../../../../uploads/';
$capa_size = 5000000;

$servicoDAO = new ServicoDAO();
$idservico = $_POST['idservico'];
$servico = $servicoDAO->getById($idservico);
$servico->setNome($_POST['inputNome']);
$servico->setDescricao($_POST['inputDescricao']);
// if (isset($_POST['inputAtivo']) && $_POST['inputAtivo'] == '1') {
$servico->setAtivo(1);
$servico->setUrl_video($_POST['inputUrlVideo']);
// } else {
// 	$servico->setAtivo(0);
// }
if(isset($_FILES['inputImagem']['name'])) {
	$ext_capa = strtolower(pathinfo($_FILES['inputImagem']['name'], PATHINFO_EXTENSION));
	if (in_array($ext_capa , $capa_ext)) {
		if ($_FILES['inputImagem']['size'] < $capa_size) {
			$img_capa = "img_servico_".getStringLink($servico->getNome()).'_'.time() . "." . $ext_capa;
			if (move_uploaded_file($_FILES['inputImagem']['tmp_name'], $capa_path . $img_capa)) { 				
				$servico->setImagem($img_capa);
			}
		}
	}
}

$servico = $servicoDAO->save($servico);
printf('<div class="alert alert-success">Serviço <b>%s</b> alterado com sucesso.</div>', $servico->getNome());
?>