<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.UsuarioVO.php");
include("../../../class/DAO/class.UsuarioDAO.php");

$usuarioDAO = new UsuarioDAO();

if(isset($_POST['id'])) {
	$usuario = $usuarioDAO->getById($_POST['id']);
	if($usuario->getIdUsuario()) {
		$usuarioDAO->delete($usuario);
	}
}

?>