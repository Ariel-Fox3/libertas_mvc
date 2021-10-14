<?php

  class ConfigVO {
    private $idconfig = NULL;
    private $nome = NULL;
    private $val = NULL;

    public function getIdconfig(){
      return $this->idconfig;
    }
  
    public function setIdconfig($idconfig){
      $this->idconfig = $idconfig;
    }
  
    public function getNome(){
      return $this->nome;
    }
  
    public function setNome($nome){
      $this->nome = $nome;
    }
  
    public function getVal(){
      return $this->val;
    }
  
    public function setVal($val){
      $this->val = $val;
    }
  }

?>