<?php
class CategoriaVO {

	private $idcategoria = NULL;
	private $nome = NULL;
	private $descricao = NULL;
	private $imagem = NULL;
	private $joined = NULL;
	private $ativo = NULL;

	public function getIdcategoria(){
		return $this->idcategoria;
	}

	public function setIdcategoria($idcategoria){
		$this->idcategoria = $idcategoria;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getDescricao(){
		return $this->descricao;
	}

	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}

	public function getImagem(){
		return $this->imagem;
	}

	public function setImagem($imagem){
		$this->imagem = $imagem;
	}

	public function getJoined(){
		return $this->joined;
	}

	public function setJoined($joined){
		$this->joined = $joined;
	}

	public function getAtivo(){
		return $this->ativo;
	}

	public function setAtivo($ativo){
		$this->ativo = $ativo;
	}

}
?>