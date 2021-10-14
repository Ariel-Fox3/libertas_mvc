<?php

class ConfigDAO extends Conexao {

	
	public function insert(ConfigVO $objVo) {
		$sql = sprintf('INSERT INTO config (nome, val)
						VALUES ("%s", "%s")', 
						addslashes($objVo->getNome()),
						addslashes($objVo->getVal()));
		$objVo->setIdconfig($this->insertDB($sql));
		return $objVo;
	}

	public function update(ConfigVO $objVo) {
		if (!$objVo -> getIdconfig())
			throw new Exception('Valor da chave primária inválido');

		$sql = sprintf('UPDATE config SET nome="%s", val="%s" 
						WHERE idconfig = "%s" ', 
						addslashes($objVo->getNome()),
						addslashes($objVo->getVal()),
						$objVo->getIdconfig());
		$this->updateDB($sql);
		return $objVo;
	}

	public function save(ConfigVO &$objVo) {
		if ($objVo -> getIdconfig() !== null) {
			return $this -> update($objVo);
		} else {
			return $this -> insert($objVo);
		}
	}

	public function getAll() {
		$listArquivos = $this->selectDB('SELECT * FROM config', null, 'ConfigVO');
    return $listArquivos;
	}

	public function getByNome($nome) {
		$w = array();
		$w[] = "nome = '$nome'";
		$sql = sprintf('SELECT * FROM config WHERE %s', implode(' AND ', $w));
		$listArquivos = $this->selectDB($sql, null, 'ConfigVO');
		return $listArquivos[0];
	}

	public function getById($id) {
		$arquivo = $this->selectDB('SELECT * FROM arquivo WHERE idarquivo = '.$id, null, 'ConfigVO');
    return $arquivo[0];
	}
	
  public function delete(ConfigVO &$objVo) {
    $ret = $this->deleteDB("DELETE FROM config WHERE idconfig = ". $objVo->getIdconfig());
    return (int)$ret;
  }
	
}
?>