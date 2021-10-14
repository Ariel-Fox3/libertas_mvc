<?php
class PaginaVO {

	private $idpagina = NULL;
	private $fixo = NULL;
	private $sublink = NULL;
	private $idsublink = NULL;
	private $nome = NULL;
	private $conteudo = NULL;
	private $externo = NULL;
  private $link = NULL;
  private $ativo = NULL;
	private $elements = NULL;
	private $ordem = NULL;
	private $idtipo_aula = NULL;
	private $idtipo_curso = NULL;

  public function getIdpagina(){
		return $this->idpagina;
	}

	public function setIdpagina($idpagina){
		$this->idpagina = $idpagina;
	}

	public function getFixo(){
		return $this->fixo;
	}

	public function setFixo($fixo){
		$this->fixo = $fixo;
	}

	public function getSublink(){
		return $this->sublink;
	}

	public function setSublink($sublink){
		$this->sublink = $sublink;
	}

	public function getIdsublink(){
		return $this->idsublink;
	}

	public function setIdsublink($idsublink){
		$this->idsublink = $idsublink;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getConteudo(){
		return $this->conteudo;
	}

	public function setConteudo($conteudo){
		$this->conteudo = $conteudo;
	}

	public function getExterno(){
		return $this->externo;
	}

	public function setExterno($externo){
		$this->externo = $externo;
	}

	public function getLink(){
		return $this->link;
	}

	public function setLink($link){
		$this->link = $link;
  }
  
  public function getAtivo(){
		return $this->ativo;
	}

	public function setAtivo($ativo){
		$this->ativo = $ativo;
  }
  
  public function getElements(){
		return $this->elements;
	}

	public function setElements($elements){
		$this->elements = $elements;
	}

	public function getOrdem(){
		return $this->ordem;
	}

	public function setOrdem($ordem){
		$this->ordem = $ordem;
	}

	public function getIdtipo_aula(){
		return $this->idtipo_aula;
	}

	public function setIdtipo_aula($idtipo_aula){
		$this->idtipo_aula = $idtipo_aula;
	}

	public function getIdtipo_curso(){
		return $this->idtipo_curso;
	}

	public function setIdtipo_curso($idtipo_curso){
		$this->idtipo_curso = $idtipo_curso;
	}
}
?>
