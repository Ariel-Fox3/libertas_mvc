<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.UsuarioVO.php");
include("../../../class/DAO/class.UsuarioDAO.php");

$usuarioDAO = new UsuarioDAO();

if(isset($_POST['id'])) {
	$usuario = $usuarioDAO->getById($_POST['id']);
	if($usuario->getIdUsuario()) {
    if ($usuario->getAtivo() == 1) {
      $usuario->setAtivo(0);
      echo 'Desativado com sucesso.';
    } else {
      $usuario->setAtivo(1);
      echo 'Ativado com sucesso.';
    }
		$usuarioDAO->save($usuario);
	}
}

?>