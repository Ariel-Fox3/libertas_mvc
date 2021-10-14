<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.UsuarioVO.php");
include("../../../class/DAO/class.UsuarioDAO.php");

$usuarioDAO = new UsuarioDAO();
if(!empty($_POST['inputNome'])) {
	
	if (!$usuarioDAO->jaExiste($_POST['inputUsuario'])) {
		$usuario = new UsuarioVO();
		$usuario->setNome($_POST['inputNome']);
		$usuario->setEmail($_POST['inputUsuario']);
		$usuario->setSenha(md5($_POST['inputSenha']));
		// acesso switch
		if(isset($_POST['inputAtivo'])) {
			$usuario->setAtivo(1);
		} else {
			$usuario->setAtivo(0);
		}

		$usuario = $usuarioDAO->save($usuario);

		if($usuario->getIdUsuario() != null) {
			printf("<div class='alert alert-success' role='alert'>Usuário <b>%s</b> criado com sucesso!</div>", $usuario->getNome());
		}
	} else {
		printf("<div class='alert alert-danger' role='alert'><b>%s</b> já existe, tente outro usuário.</div>", $_POST['inputUsuario']);
	}

} else {
	printf("<div class='alert alert-danger' role='alert'>Nenhum campo obrigatório (*) deve ficar em branco.</div>");
}
?>