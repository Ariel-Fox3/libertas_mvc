<?php

class ArquivoDAO extends Conexao {

	
	public function insert(ArquivoVO $objVo) {
		$sql = sprintf('INSERT INTO arquivo (idproduto, idservico, idpagina, idnoticia, data, tipo, url, nome, file, ordem, thumbnail, joined, ativo)
						VALUES ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s")', 
						addslashes($objVo->getIdproduto()),
						addslashes($objVo->getIdservico()),
						addslashes($objVo->getIdpagina()),
						addslashes($objVo->getIdnoticia()),
						addslashes($objVo->getData()),
						addslashes($objVo->getTipo()),
						addslashes($objVo->getUrl()),
						addslashes($objVo->getNome()),
						addslashes($objVo->getFile()),
						addslashes($objVo->getOrdem()),
						addslashes($objVo->getThumbnail()),
						addslashes($objVo->getJoined()),
						addslashes($objVo->getAtivo()));
		$objVo->setIdarquivo($this->insertDB($sql));
		return $objVo;
	}

	public function update(ArquivoVO $objVo) {
		if (!$objVo -> getIdarquivo())
			throw new Exception('Valor da chave primária inválido');

		$sql = sprintf('UPDATE arquivo SET idproduto="%s", idservico="%s", idpagina="%s", idnoticia="%s", data="%s", tipo="%s", url="%s", nome="%s", file="%s", ordem="%s", thumbnail="%s", joined="%s", ativo="%s" 
						WHERE idarquivo = "%s" ', 
						addslashes($objVo->getIdproduto()),
						addslashes($objVo->getIdservico()),
						addslashes($objVo->getIdpagina()),
						addslashes($objVo->getIdnoticia()),
						addslashes($objVo->getData()),
						addslashes($objVo->getTipo()),
						addslashes($objVo->getUrl()),
						addslashes($objVo->getNome()),
						addslashes($objVo->getFile()),
						addslashes($objVo->getOrdem()),
						addslashes($objVo->getThumbnail()),
						addslashes($objVo->getJoined()),
						addslashes($objVo->getAtivo()),
						$objVo->getIdarquivo());
		$this->updateDB($sql);
		return $objVo;
	}

	public function save(ArquivoVO &$objVo) {
		if ($objVo -> getIdarquivo() !== null) {
			return $this -> update($objVo);
		} else {
			return $this -> insert($objVo);
		}
	}

	public function getAll() {
		$listArquivos = $this->selectDB('SELECT * FROM arquivo', null, 'ArquivoVO');
    return $listArquivos;
	}

	public function getByIdproduto($idproduto, $tipo = null) {
		$w = array();
		$w[] = "idproduto = $idproduto";
		if ($tipo != null) $w[] = "tipo = '$tipo'";
		$sql = sprintf('SELECT * FROM arquivo WHERE %s  ORDER BY ordem ASC', implode(' AND ', $w));
		$listArquivos = $this->selectDB($sql, null, 'ArquivoVO');
		return $listArquivos;
	}
	

	public function getByIdservico($idservico, $tipo = null) {
		$w = array();
		$w[] = "idservico = $idservico";
		if ($tipo != null) $w[] = "tipo = '$tipo'";
		$sql = sprintf('SELECT * FROM arquivo WHERE %s  ORDER BY ordem ASC', implode(' AND ', $w));
		$listArquivos = $this->selectDB($sql, null, 'ArquivoVO');
		return $listArquivos;
	}

	public function getByNome($nome, $tipo = null) {
		$w = array();
		$w[] = "nome = '$nome'";
		if ($tipo != null) $w[] = "tipo = '$tipo'";
		$sql = sprintf('SELECT * FROM arquivo WHERE %s  ORDER BY ordem ASC', implode(' AND ', $w));
		$listArquivos = $this->selectDB($sql, null, 'ArquivoVO');
		return $listArquivos;
	}

	public function getByIdpagina($idpagina, $tipo = null, $name_as_id = false) {
		$w = array();
		$w[] = "idpagina = $idpagina";
		if ($tipo != null) $w[] = "tipo = '$tipo'";
		$sql = sprintf('SELECT * FROM arquivo WHERE %s  ORDER BY ordem ASC', implode(' AND ', $w));
		$listArquivos = $this->selectDB($sql, null, 'ArquivoVO');
		if ($name_as_id == false) {
			return $listArquivos;
		} else {
			$arquivos = array();
			if (sizeof($listArquivos) > 0) {
				foreach ($listArquivos as $objVoA) {
					if (isset($arquivos[$objVoA->getNome()])) {
						$old = clone $arquivos[$objVoA->getNome()];
						$arquivos[$objVoA->getNome()] = array();
						$arquivos[$objVoA->getNome()][] = $old;
						$arquivos[$objVoA->getNome()][] = clone $objVoA; 
					} else {
						$arquivos[$objVoA->getNome()] = clone $objVoA;
					}
				}
			}
			return $arquivos;
		}
	}

	public function getByIdnoticia($idnoticia, $tipo = null) {
		$w = array();
		$w[] = "idnoticia = $idnoticia";
		if ($tipo != null) $w[] = "tipo = '$tipo'";
		$sql = sprintf('SELECT * FROM arquivo WHERE %s  ORDER BY ordem ASC', implode(' AND ', $w));
		$listArquivos = $this->selectDB($sql, null, 'ArquivoVO');
		return $listArquivos;
	}

	public function getArquivoPrincipal($idaula = null, $idservico = null) {
		$w = array();
		if ($idaula != null) {
			$w[] = "idaula = $idaula";
			$w[] = "tipo = 'video_aula'";
		}
		if ($idservico != null) {
		$w[] = "idservico = $idservico";
		$w[] = "tipo = 'video_outro'";
		}
			
		$w[] = 'ativo = 1';
		$sql = sprintf("SELECT * FROM arquivo WHERE %s", implode(' AND ', $w));
		$listArquivos = $this->selectDB($sql, null, 'ArquivoVO');
		if (sizeof($listArquivos) > 0) {
			return $listArquivos[0];
		} else {
			return null;
		}
	}

	public function getById($id) {
		$arquivo = $this->selectDB('SELECT * FROM arquivo WHERE idarquivo = '.$id, null, 'ArquivoVO');
    return $arquivo[0];
	}

	public function disable(ArquivoVO &$objVo) {
    $ret = $this->updateDB("UPDATE arquivo SET ativo='0' WHERE idarquivo = ". $objVo->getIdarquivo());
    return (int)$ret;
	}
	
  public function delete(ArquivoVO &$objVo) {
    $ret = $this->deleteDB("DELETE FROM arquivo WHERE idarquivo = ". $objVo->getIdarquivo());
    return (int)$ret;
  }
	
}
?>