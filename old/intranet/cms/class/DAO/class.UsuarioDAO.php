<?php

class UsuarioDAO extends Conexao {

	public function insert(UsuarioVO $objVo) {
		$sql = sprintf('INSERT INTO usuario (email, senha, nome, joined, ultimo_acesso, ativo)
						VALUES ( "%s", "%s", "%s","%s", "%s", "%s")', 
						addslashes($objVo->getEmail()), 
						addslashes($objVo->getSenha()),
						addslashes($objVo->getNome()),
						addslashes($objVo->getJoined()),
						addslashes($objVo->getUltimo_acesso()),
						addslashes($objVo->getAtivo()));
		$objVo->setIdusuario($this->insertDB($sql));
		return $objVo;
	}

	public function update(UsuarioVO $objVo) {
		if (!$objVo -> getIdUsuario())
			throw new Exception('Valor da chave primária inválido');

		$sql = sprintf('UPDATE usuario set email="%s", senha="%s", nome="%s", joined="%s", ultimo_acesso="%s", ativo="%s" 
						where idusuario = "%s" ', 
						addslashes($objVo->getEmail()), 
						addslashes($objVo->getSenha()),
						addslashes($objVo->getNome()),
						addslashes($objVo->getJoined()),
						addslashes($objVo->getUltimo_acesso()),
						addslashes($objVo->getAtivo()),
						$objVo->getIdUsuario());
		$this->updateDB($sql);
		return $objVo;
	}

	public function save(UsuarioVO &$objVo) {
		if ($objVo -> getIdUsuario() !== null) {
			return $this -> update($objVo);
		} else {
			return $this -> insert($objVo);
		}
	}

	public function getAll() {
		$listUsuarios = $this->selectDB('SELECT * FROM usuario', null, 'UsuarioVO');
    return $listUsuarios;
	}

	public function getById($id) {
		$usuario = $this->selectDB('SELECT * FROM usuario WHERE idusuario = '.$id, null, 'UsuarioVO');
    return $usuario[0];
	}


	public function logar(UsuarioVO $objVo) {
		$sql = sprintf('select * from usuario where email = "%s" AND senha = "%s" AND ativo = "1"', stripslashes(strip_tags($objVo->getEmail())), stripslashes(strip_tags($this->cryptMd5($objVo->getSenha()))));
		$resultado = $this->selectDB($sql, null, 'UsuarioVO');
		if (sizeof($resultado) > 0) {
			@session_start();

			$dados = $resultado[0];
			$id = $dados->getIdusuario();
      $login = $dados->getEmail();
      $nome = $dados->getNome();
			$chave = "1a2cf8g2k68gj67gf784kh69f123idddko6";
			$ip = $_SERVER["REMOTE_ADDR"];
			$hora = time();
			$chave = md5($login . $chave . $ip . $hora);
			
			// ok

			$_SESSION['usuarioSession'] = array("id" => $id, "admin"=>true, "login" => $login, "nome" => $nome, "chave" => $chave, "hora" => $hora);
			// $this->updateDB("UPDATE usuario SET ultimo_acesso=now() WHERE idusuario=$id");
			return true;
		} else {
			return false;
		}
	}

	private function cryptMd5($senha) {
		return md5($senha);	
	}
	
  public function jaExiste($email) {
    $sql = sprintf('SELECT * FROM usuario WHERE email = "%s"', $email);
    $usuario = $this->selectDB($sql, null, 'UsuarioVO');
    if (isset($usuario) && sizeof($usuario) > 0) {
      return true;
    } else {
      return false;
    }
  }


  public function delete(UsuarioVO &$objVo) {
    $ret = $this->deleteDB("UPDATE usuario SET ativo='0' WHERE idusuario = ". $objVo->getIdusuario());
    return (int)$ret;
  }
	
}
?>