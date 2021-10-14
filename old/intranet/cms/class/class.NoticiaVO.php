<?php
  class NoticiaVO {
    private $idnoticia = NULL;
    private $idcategoria = NULL;
    private $nome = NULL;
    private $conteudo = NULL;
    private $imagem = NULL;
    private $tags = NULL;
    private $data = NULL;
    private $ativo = NULL;
    private $ordem = NULL;
    private $destaque = NULL;
    private $mail_sent = NULL;
    
    public function getIdnoticia(){
      return $this->idnoticia;
    }
  
    public function setIdnoticia($idnoticia){
      $this->idnoticia = $idnoticia;
    }
  
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
  
    public function getConteudo(){
      return $this->conteudo;
    }
  
    public function setConteudo($conteudo){
      $this->conteudo = $conteudo;
    }
  
    public function getImagem(){
      return $this->imagem;
    }
  
    public function setImagem($imagem){
      $this->imagem = $imagem;
    }
  
    public function getTags(){
      return $this->tags;
    }
  
    public function setTags($tags){
      $this->tags = $tags;
    }
  
    public function getData(){
      return $this->data;
    }
  
    public function setData($data){
      $this->data = $data;
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

    public function getDestaque(){
      return $this->destaque;
    }
  
    public function setDestaque($destaque){
      $this->destaque = $destaque;
    }

    public function getMail_sent(){
      return $this->mail_sent;
    }
  
    public function setMail_sent($mail_sent){
      $this->mail_sent = $mail_sent;
    }

    public function getBanner1(){
      return $this->banner1;
    }
  
    public function setBanner1($banner1){
      $this->banner1 = $banner1;
    }

    public function getLink_b1(){
      return $this->link_b1;
    }
  
    public function setLink_b1($link_b1){
      $this->link_b1 = $link_b1;
    }

    public function getBanner2(){
      return $this->banner2;
    }
  
    public function setBanner2($banner2){
      $this->banner2 = $banner2;
    }

    public function getLink_b2(){
      return $this->link_b2;
    }
  
    public function setLink_b2($link_b2){
      $this->link_b2 = $link_b2;
    }

    public function getBanner3(){
      return $this->banner3;
    }
  
    public function setBanner3($banner3){
      $this->banner3 = $banner3;
    }

    public function getLink_b3(){
      return $this->link_b3;
    }
  
    public function setLink_b3($link_b3){
      $this->link_b3 = $link_b3;
    }
  }
?>