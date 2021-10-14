<?php
class UsuarioVO {

	private $idusuario = NULL;
	private $email = NULL;
	private $senha = NULL;
	private $nome = NULL;
	private $joined = NULL;
	private $ultimo_acesso = NULL;
	private $ativo = NULL;

	public function getIdusuario(){
		return $this->idusuario;
	}

	public function setIdusuario($idusuario){
		$this->idusuario = $idusuario;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getSenha(){
		return $this->senha;
	}

	public function setSenha($senha){
		$this->senha = $senha;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getJoined(){
		return $this->joined;
	}

	public function setJoined($joined){
		$this->joined = $joined;
	}

	public function getUltimo_acesso(){
		return $this->ultimo_acesso;
	}

	public function setUltimo_acesso($ultimo_acesso){
		$this->ultimo_acesso = $ultimo_acesso;
	}

	public function getAtivo(){
		return $this->ativo;
	}

	public function setAtivo($ativo){
		$this->ativo = $ativo;
	}

}
?>