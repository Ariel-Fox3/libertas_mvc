<?php

class CategoriaDAO extends Conexao {

	public function insert(CategoriaVO $objVo) {
		$sql = sprintf('INSERT INTO categoria (nome, descricao, imagem, joined, ativo)
						VALUES ( "%s", "%s", "%s", "%s", "%s")', 
						addslashes($objVo->getNome()),
						addslashes($objVo->getDescricao()),
						addslashes($objVo->getImagem()),
						addslashes($objVo->getJoined()),
						addslashes($objVo->getAtivo()));
		$objVo->setIdcategoria($this->insertDB($sql));
		return $objVo;
	}

	public function update(CategoriaVO $objVo) {
		if (!$objVo -> getIdcategoria())
			throw new Exception('Valor da chave primária inválido');

		$sql = sprintf('UPDATE categoria SET nome="%s", descricao="%s", imagem="%s", joined="%s", ativo="%s" 
						WHERE idcategoria = "%s" ', 
						addslashes($objVo->getNome()),
						addslashes($objVo->getDescricao()),
						addslashes($objVo->getImagem()),
						addslashes($objVo->getJoined()),
						addslashes($objVo->getAtivo()),
						$objVo->getIdcategoria());
		$this->updateDB($sql);
		return $objVo;
	}

	public function save(CategoriaVO &$objVo) {
		if ($objVo -> getIdcategoria() !== null) {
			return $this -> update($objVo);
		} else {
			return $this -> insert($objVo);
		}
	}

	public function getHome($limit = null) {
		$sql = sprintf('SELECT c.*, count(*) FROM noticia AS n INNER JOIN categoria AS c ON (n.idcategoria = c.idcategoria) GROUP BY n.idcategoria ORDER BY count(*) DESC');
		if ($limit != null) $sql .= " LIMIT $limit";
		$listcategorias = $this->selectDB($sql, null, 'CategoriaVO');
    return $listcategorias;
	}

	public function getAll($ativos = false, $limit = null) {
		$sql = sprintf('SELECT * FROM categoria %s %s', $ativos == true ? 'WHERE ativo = 1' : '', $limit ? 'LIMIT ' . $limit : '');
		$listcategorias = $this->selectDB($sql, null, 'CategoriaVO');
    return $listcategorias;
	}
	
	public function getById($id) {
		$categoria = $this->selectDB('SELECT * FROM categoria WHERE idcategoria = '.$id, null, 'CategoriaVO');
    return $categoria[0];
	}

	public function getTags($idcategoria) {
		$sql = sprintf('SELECT DISTINCT(n.tags) FROM noticia AS n INNER JOIN categoria AS c ON (n.idcategoria = c.idcategoria) WHERE n.idcategoria = %s', $idcategoria);
		$listTags = $this->selectDB($sql, null);
    return $listTags;
	}

  public function delete(CategoriaVO &$objVo) {
    $ret = $this->deleteDB("UPDATE categoria SET ativo='0' WHERE idcategoria = ". $objVo->getIdcategoria());
    return (int)$ret;
  }
	
}
?>