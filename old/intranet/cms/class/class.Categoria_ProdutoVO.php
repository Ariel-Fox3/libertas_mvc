<?php
class Categoria_ProdutoVO {

	private $idcategoria_produto = NULL;
	private $nome = NULL;
	private $descricao = NULL;
	private $imagem = NULL;
	private $joined = NULL;
	private $ativo = NULL;
	private $ordem = NULL;
	private $excluido = NULL;

	public function getIdcategoria_produto(){
		return $this->idcategoria_produto;
	}

	public function setIdcategoria_produto($idcategoria_produto){
		$this->idcategoria_produto = $idcategoria_produto;
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

	public function getOrdem(){
		return $this->ordem;
	}

	public function setOrdem($ordem){
		$this->ordem = $ordem;
	}

	public function getExcluido(){
		return $this->excluido;
	}

	public function setExcluido($excluido){
		$this->excluido = $excluido;
	}

}
?>