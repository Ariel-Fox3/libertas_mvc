<?php

class Categoria_ProdutoDAO extends Conexao {

	public function insert(Categoria_ProdutoVO $objVo) {
		$sql = sprintf('INSERT INTO categoria_produto (nome, descricao, imagem, joined, ativo, ordem, excluido)
						VALUES ( "%s", "%s", "%s", "%s", "%s", "%s", "%s")', 
						addslashes($objVo->getNome()),
						addslashes($objVo->getDescricao()),
						addslashes($objVo->getImagem()),
						addslashes($objVo->getJoined()),
						addslashes($objVo->getAtivo()),
						addslashes($objVo->getOrdem()),
						addslashes($objVo->getExcluido()));
		$objVo->setIdcategoria_produto($this->insertDB($sql));
		return $objVo;
	}

	public function update(Categoria_ProdutoVO $objVo) {
		if (!$objVo -> getIdcategoria_produto())
			throw new Exception('Valor da chave primária inválido');

		$sql = sprintf('UPDATE categoria_produto SET nome="%s", descricao="%s", imagem="%s", joined="%s", ativo="%s", ordem="%s", excluido="%s" 
						WHERE idcategoria_produto = "%s" ', 
						addslashes($objVo->getNome()),
						addslashes($objVo->getDescricao()),
						addslashes($objVo->getImagem()),
						addslashes($objVo->getJoined()),
						addslashes($objVo->getAtivo()),
						addslashes($objVo->getOrdem()),
						addslashes($objVo->getExcluido()),
						$objVo->getIdcategoria_produto());
		$this->updateDB($sql);
		return $objVo;
	}

	public function save(Categoria_ProdutoVO &$objVo) {
		if ($objVo -> getIdcategoria_produto() !== null) {
			return $this -> update($objVo);
		} else {
			return $this -> insert($objVo);
		}
	}

	public function getAll($ativos = false, $limit = null) {
		$sql = sprintf('SELECT * FROM categoria_produto %s ORDER BY ordem ASC %s', $ativos == true ? 'WHERE ativo = 1 AND excluido = 0' : 'WHERE excluido = 0', $limit ? 'LIMIT ' . $limit : '');
		$listcategorias = $this->selectDB($sql, null, 'Categoria_ProdutoVO');
    return $listcategorias;
	}
	
	public function getById($id) {
		$categoria_produto = $this->selectDB('SELECT * FROM categoria_produto WHERE idcategoria_produto = '.$id, null, 'Categoria_ProdutoVO');
    return $categoria_produto[0];
	}

	public function getByArray($array) {
		$w = array();
		$ids = json_decode($array);
		foreach ($ids as $ida) {
			$w[] = "idcategoria_produto = $ida";
		}
		$sql = sprintf('SELECT * FROM categoria_produto WHERE %s', implode(' OR ', $w));
		$categoria_produto = $this->selectDB($sql, null, 'Categoria_ProdutoVO');
    return $categoria_produto;
	}

  public function delete(Categoria_ProdutoVO &$objVo) {
    $ret = $this->deleteDB("UPDATE categoria_produto SET ativo='0', excluido='1' WHERE idcategoria_produto = ". $objVo->getIdcategoria_produto());
    return (int)$ret;
  }
	
}
?>