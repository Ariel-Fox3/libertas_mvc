<?php
class SolicitacaoVO {

	private $idsolicitacao = NULL;
	private $idproduto = NULL;
	private $nome = NULL;
	private $email = NULL;
	private $telefone = NULL;
	private $mensagem = NULL;
	private $status = NULL; // 0 = em aberto, 1 = atendido, 2 = cancelado
	private $idusuario = NULL;
	private $obs = NULL;
	private $data = NULL;
	private $data_final = NULL;


	public function getIdsolicitacao(){
		return $this->idsolicitacao;
	}

	public function setIdsolicitacao($idsolicitacao){
		$this->idsolicitacao = $idsolicitacao;
	}

	public function getIdproduto(){
		return $this->idproduto;
	}

	public function setIdproduto($idproduto){
		$this->idproduto = $idproduto;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getTelefone(){
		return $this->telefone;
	}

	public function setTelefone($telefone){
		$this->telefone = $telefone;
	}

	public function getMensagem(){
		return $this->mensagem;
	}

	public function setMensagem($mensagem){
		$this->mensagem = $mensagem;
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){
		$this->status = $status;
	}

	public function getIdusuario(){
		return $this->idusuario;
	}

	public function setIdusuario($idusuario){
		$this->idusuario = $idusuario;
	}

	public function getObs(){
		return $this->obs;
	}

	public function setObs($obs){
		$this->obs = $obs;
	}

	public function getData(){
		return $this->data;
	}

	public function setData($data){
		$this->data = $data;
	}

	public function getData_final(){
		return $this->data_final;
	}

	public function setData_final($data_final){
		$this->data_final = $data_final;
	}
}
?>