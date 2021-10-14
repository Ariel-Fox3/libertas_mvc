<?php

  class ProdutoDAO extends Conexao {
    public function insert(ProdutoVO $objVo) {
      $sql = sprintf('INSERT INTO produto (idcategoria, idmarca, url_video, nome, descricao, imagem, ativo, joined, ordem)
                VALUES ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s")', 
                addslashes($objVo->getIdcategoria()),
                addslashes($objVo->getIdmarca()),
                addslashes($objVo->getUrl_video()),
                addslashes($objVo->getNome()),
                addslashes($objVo->getDescricao()),
                addslashes($objVo->getImagem()),
                addslashes($objVo->getAtivo()),
                addslashes($objVo->getJoined()),
                addslashes($objVo->getOrdem()));
      $objVo->setIdproduto($this->insertDB($sql));
      return $objVo;
    }
    
    public function update(ProdutoVO $objVo) {
      if (!$objVo -> getIdproduto())
        throw new Exception('Valor da chave primária inválido');
    
      $sql = sprintf('UPDATE produto SET idcategoria="%s", idmarca="%s", url_video="%s", nome="%s", descricao="%s", imagem="%s", ativo="%s", joined="%s", ordem="%s" 
                WHERE idproduto = "%s" ', 
                addslashes($objVo->getIdcategoria()),
                addslashes($objVo->getIdmarca()),
                addslashes($objVo->getUrl_video()),
                addslashes($objVo->getNome()),
                addslashes($objVo->getDescricao()),
                addslashes($objVo->getImagem()),
                addslashes($objVo->getAtivo()),
                addslashes($objVo->getJoined()),
                addslashes($objVo->getOrdem()),
                $objVo->getIdproduto());
      $this->updateDB($sql);
      return $objVo;
    }
    
    public function save(ProdutoVO &$objVo) {
      if ($objVo -> getIdproduto() !== null) {
        return $this -> update($objVo);
      } else {
        return $this -> insert($objVo);
      }
    }
    
    public function getAll() {
      $listproduto = $this->selectDB('SELECT * FROM produto ORDER BY ordem ASC', null, 'ProdutoVO');
      return $listproduto;
    }

    public function getByFiltro($idcategoria = null, $limit = null, $offset = null, $ativos = true) {
      $w = array();
      if ($idcategoria != null) $w[] = "idcategoria LIKE '%\"$idcategoria\"%'";
      if ($ativos === true) $w[] = 'ativo = 1';
      $sql = sprintf('SELECT * FROM produto %s ORDER BY ordem ASC', sizeof($w) > 0 ? 'WHERE ' . implode(' AND ', $w) : '');
      if ($limit != null && $offset != null) {
        $sql .= " LIMIT $limit OFFSET " . intval($offset) * intval($limit);
      }
      // echo $sql;
      $listproduto = $this->selectDB($sql, null, 'ProdutoVO');
      return $listproduto;
    }

    public function getBySearch($term) {
      $w = array();
      $w[] = 'ativo = 1';
      $w[] = "nome LIKE '%$term%'";
      $sql = sprintf('SELECT * FROM produto WHERE %s ORDER BY ordem ASC', implode(' AND ', $w));
      // echo $sql;
      $listproduto = $this->selectDB($sql, null, 'ProdutoVO');
      return $listproduto;
    }

    public function getCount() {
      $w = array();
      // if ($idcategoria != null) $w[] = "idcategoria LIKE '%\"$idcategoria\"%'";
      // if ($idmarca != null) $w[] = "idmarca LIKE '%\"$idmarca\"%'";
      $sql = sprintf('SELECT count(*) FROM produto %s', sizeof($w) > 0 ? 'WHERE ' . implode(' AND ', $w) : '');
      $listproduto = $this->selectDB($sql, null);
      return $listproduto[0]['count(*)'];
    }
    
    public function getById($id) {
      $produto = $this->selectDB('SELECT * FROM produto WHERE idproduto = '.$id, null, 'ProdutoVO');
      return $produto[0];
    }
    
    public function delete(ProdutoVO &$objVo) {
      $ret = $this->deleteDB("DELETE FROM produto WHERE idproduto = ". $objVo->getIdproduto());
      return (int)$ret;
    }
  }

?>