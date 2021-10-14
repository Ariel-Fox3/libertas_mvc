<?php

class ServicoDAO extends Conexao {
  public function insert(ServicoVO $objVo) {
    $sql = sprintf('INSERT INTO servico (nome, descricao, imagem, url_video, ativo, joined, ordem)
              VALUES ("%s", "%s", "%s", "%s", "%s", "%s", "%s")', 
              addslashes($objVo->getNome()),
              addslashes($objVo->getDescricao()),
              addslashes($objVo->getImagem()),
              addslashes($objVo->getUrl_video()),
              addslashes($objVo->getAtivo()),
              addslashes($objVo->getJoined()),
              addslashes($objVo->getOrdem()));
    $objVo->setIdservico($this->insertDB($sql));
    return $objVo;
  }
  
  public function update(ServicoVO $objVo) {
    if (!$objVo -> getIdservico())
      throw new Exception('Valor da chave primária inválido');
  
    $sql = sprintf('UPDATE servico SET nome="%s", descricao="%s", imagem="%s", url_video="%s", ativo="%s", joined="%s", ordem="%s" 
              WHERE idservico = "%s" ', 
              addslashes($objVo->getNome()),
              addslashes($objVo->getDescricao()),
              addslashes($objVo->getImagem()),
              addslashes($objVo->getUrl_video()),
              addslashes($objVo->getAtivo()),
              addslashes($objVo->getJoined()),
              addslashes($objVo->getOrdem()),
              $objVo->getIdservico());
    $this->updateDB($sql);
    return $objVo;
  }
  
  public function save(ServicoVO &$objVo) {
    if ($objVo -> getIdservico() !== null) {
      return $this -> update($objVo);
    } else {
      return $this -> insert($objVo);
    }
  }
  
  public function getAll($ativos = false, $limit = null) {
		$sql = sprintf('SELECT * FROM servico %s %s', $ativos == true ? 'WHERE ativo = 1' : '', $limit ? 'LIMIT ' . $limit : '');
		$listServicos = $this->selectDB($sql, null, 'ServicoVO');
    return $listServicos;
	}
  
  public function getById($id) {
    $servico = $this->selectDB('SELECT * FROM servico WHERE idservico = '.$id, null, 'ServicoVO');
    return $servico[0];
  }
  
  public function delete(ServicoVO &$objVo) {
    $ret = $this->deleteDB("DELETE FROM servico WHERE idservico = ". $objVo->getIdservico());
    return (int)$ret;
  }
}

?>