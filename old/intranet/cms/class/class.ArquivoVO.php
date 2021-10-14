<?php
class ArquivoVO {

	private $idarquivo = NULL;
	private $idproduto = NULL;
	private $idservico = NULL;
	private $idpagina = NULL;
	private $idnoticia = NULL;
	private $data = NULL;
	private $tipo = NULL;
	private $url = NULL;
	private $nome = NULL;
	private $file = NULL;
	private $ordem = NULL;
	private $thumbnail = NULL;
	private $joined = NULL;
	private $ativo = NULL;

	public function getIdarquivo(){
		return $this->idarquivo;
	}

	public function setIdarquivo($idarquivo){
		$this->idarquivo = $idarquivo;
	}

	public function getIdproduto(){
		return $this->idproduto;
	}

	public function setIdproduto($idproduto){
		$this->idproduto = $idproduto;
	}

	public function getIdservico(){
		return $this->idservico;
	}

	public function setIdservico($idservico){
		$this->idservico = $idservico;
	}

	public function getIdpagina(){
		return $this->idpagina;
	}

	public function setIdpagina($idpagina){
		$this->idpagina = $idpagina;
	}

	public function getIdnoticia(){
		return $this->idnoticia;
	}

	public function setIdnoticia($idnoticia){
		$this->idnoticia = $idnoticia;
	}

	public function getData(){
		return $this->data;
	}

	public function setData($data){
		$this->data = $data;
	}

	public function getTipo(){
		return $this->tipo;
	}

	public function setTipo($tipo){
		$this->tipo = $tipo;
	}

	public function getUrl(){
		return $this->url;
	}

	public function setUrl($url){
		$this->url = $url;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getFile(){
		return $this->file;
	}

	public function setFile($file){
		$this->file = $file;
	}

	public function getOrdem(){
		return $this->ordem;
	}

	public function setOrdem($ordem){
		$this->ordem = $ordem;
	}

	public function getThumbnail(){
		return $this->thumbnail;
	}

	public function setThumbnail($thumbnail){
		$this->thumbnail = $thumbnail;
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
