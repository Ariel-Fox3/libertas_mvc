<?php

  class ServicoVO {
    private $idservico = NULL;
    private $nome = NULL;
    private $descricao = NULL;
    private $imagem = NULL;
    private $url_video = NULL;
    private $ativo = NULL;
    private $joined = NULL;
    private $ordem = NULL;

    public function expose() {
     return get_object_vars($this);
    }

    public function getIdservico(){
      return $this->idservico;
    }
  
    public function setIdservico($idservico){
      $this->idservico = $idservico;
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

    public function getUrl_video(){
      return $this->url_video;
    }
  
    public function setUrl_video($url_video){
      $this->url_video = $url_video;
    }
  
    public function getAtivo(){
      return $this->ativo;
    }
  
    public function setAtivo($ativo){
      $this->ativo = $ativo;
    }
  
    public function getJoined(){
      return $this->joined;
    }
  
    public function setJoined($joined){
      $this->joined = $joined;
    }
  
    public function getOrdem(){
      return $this->ordem;
    }
  
    public function setOrdem($ordem){
      $this->ordem = $ordem;
    }
    
  }
?>