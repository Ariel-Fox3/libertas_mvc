<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.UsuarioVO.php");
include("../../../class/DAO/class.UsuarioDAO.php");

$usuarioDAO = new UsuarioDAO();

if(!empty($_POST['inputNome']) && !empty($_POST['inputUsuario'])) {	
	
	$usuario = $usuarioDAO->getById($_POST['idusuario']);
	// se o usuario for diferente do atual, verificar se ja nao existe...
	// utilizando strcasecmp para comparar as String (dave erro ao comprar 'Mario' e 'mario')
	if(strcasecmp($usuario->getEmail(), $_POST['inputUsuario']) != 0) {
		if (!$usuarioDAO->jaExiste($_POST['inputUsuario']) ) {

			//$usuario = new UsuarioVO();
			$usuario->setNome($_POST['inputNome']);
			$usuario->setEmail($_POST['inputUsuario']);
			if(isset($_POST['inputSenha']) && $_POST['inputSenha'] != '') {
				$usuario->setSenha(md5($_POST['inputSenha']));
			}
			// acesso switch
			if(isset($_POST['inputAtivo'])) {
				$usuario->setAtivo($_POST['inputAtivo']);
			} else {
				$usuario->setAtivo('0');
			}

			$usuarioDAO->save($usuario);
		if($usuario->getIdUsuario() != null) {
			printf("<div class='alert alert-success' role='alert'>Usuário <b>%s</b> alterado com sucesso!</div>", $usuario->getNome());
		}
	} else {
		printf("<div class='alert alert-danger' role='alert'><b>%s</b> já existe, tente outro usuário.</div>", $_POST['inputUsuario']);
	}
	// mantem o mesmo usuario...
} else {
			$usuario->setNome($_POST['inputNome']);
			$usuario->setEmail($_POST['inputUsuario']);
			if(isset($_POST['inputSenha'])) {
				$usuario->setSenha(md5($_POST['inputSenha']));
			}
			// acesso switch
			if(isset($_POST['inputAtivo'])) {
				$usuario->setAtivo($_POST['inputAtivo']);
			} else {
				$usuario->setAtivo('0');
			}

			if($usuario->getIdUsuario() != null) {
				printf("<div class='alert alert-success' role='alert'>Usuário <b>%s</b> alterado com sucesso!</div>", $usuario->getNome());
		}

		$usuarioDAO->save($usuario);
}
} else {
	printf("<div class='alert alert-danger' role='alert'>Nenhum campo obrigatório (*) deve ficar em branco.</div>");
}
?>