<?php

class NoticiaDAO extends Conexao {

	public function insert(NoticiaVO $objVo) {
		$sql = sprintf('INSERT INTO noticia (idcategoria, nome, conteudo, imagem, tags, `data`, ativo, ordem, destaque, mail_sent)
						VALUES ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s")', 
						 addslashes($objVo->getIdcategoria()),
						 addslashes($objVo->getNome()),
						 addslashes($objVo->getConteudo()),
						 addslashes($objVo->getImagem()),
						 addslashes($objVo->getTags()),
						 addslashes($objVo->getData()),
						 addslashes($objVo->getAtivo()),
						 addslashes($objVo->getOrdem()),
						 addslashes($objVo->getDestaque()),
						 addslashes($objVo->getMail_sent()));
		$objVo->setIdnoticia($this->insertDB($sql));
		return $objVo;
	}

	public function update(NoticiaVO $objVo) {
		if (!$objVo -> getIdnoticia())
			throw new Exception('Valor da chave primária inválido');

		$sql = sprintf('UPDATE noticia SET idcategoria="%s", nome="%s", conteudo="%s", imagem="%s", tags="%s", `data`="%s", ativo="%s", ordem="%s", destaque="%s", mail_sent="%s" WHERE idnoticia = "%s" ', 
						addslashes($objVo->getIdcategoria()),
						addslashes($objVo->getNome()),
						addslashes($objVo->getConteudo()),
						addslashes($objVo->getImagem()),
						addslashes($objVo->getTags()),
						addslashes($objVo->getData()),
						addslashes($objVo->getAtivo()),
						addslashes($objVo->getOrdem()),
						addslashes($objVo->getDestaque()),
						addslashes($objVo->getMail_sent()),
						$objVo->getIdnoticia());
		$this->updateDB($sql);
		return $objVo;
	}

	public function save(NoticiaVO &$objVo) {
		if ($objVo -> getIdnoticia() !== null) {
			return $this -> update($objVo);
		} else {
			return $this -> insert($objVo);
		}
	}

	public function getUnsent($data) {
		$listNoticias = $this->selectDB('SELECT * FROM noticia WHERE mail_sent = 0 AND data <= "' . $data . '"', null, 'NoticiaVO');
    return $listNoticias;
	}

	public function getAll() {
		$listNoticias = $this->selectDB('SELECT * FROM noticia', null, 'NoticiaVO');
    return $listNoticias;
	}

	public function getSearch($termo) {
		$w = array();
		$d = date('Y-m-d');
		$w[] = 'tags LIKE \'%' . $termo . '%\'';
		$w[] = 'nome LIKE \'%' . $termo . '%\'';
		$w[] = 'conteudo LIKE \'%' . $termo . '%\'';
		$sql = sprintf('SELECT * FROM noticia WHERE (%s) AND (ativo = 1) AND date(data) <= \'%s\'', implode(' OR ', $w), $d);
		// echo $sql;
		$listNoticias = $this->selectDB($sql, null, 'NoticiaVO');
    return $listNoticias;
	}

	public function getTags($idcategoria) {
		$listNoticias = $this->selectDB('SELECT tags FROM noticia WHERE idcategoria = ' . $idcategoria, null);
    return $listNoticias;
	}

	public function getDestaques($limit = null) {
		$w = array();
		$w[] = 'destaque = 1';
		$w[] = 'ativo = 1';
		$d = date('Y-m-d');
		$w[] = "DATE(data) <= '$d'";
		$sql = sprintf('SELECT * FROM noticia WHERE %s', implode(' AND ', $w));
		$sql .= ' ORDER BY ordem ASC';
		if ($limit != null) $sql .= " LIMIT $limit";
		$listNoticias = $this->selectDB($sql, null, 'NoticiaVO');
    return $listNoticias;
	}

	public function getByIdcategoria($idcategoria = null, $limit = null, $destaques = false, $offset = null, $id_exclude = null, $tag = null) {
		$w = array();
		if ($idcategoria !== null) $w[] = "idcategoria = $idcategoria";
		$w[] = "ativo = 1";
		$d = date('Y-m-d').' 00:00:00';
		$w[] = "data <= '$d'";
		if ($id_exclude != null) $w[] = "NOT idnoticia = $id_exclude";
		if ($destaques) $w[] = 'destaque = 1';
		if ($tag != null) $w[] = "tags LIKE '%$tag%'";
		$sql = sprintf('SELECT * FROM noticia WHERE %s ORDER BY data DESC, destaque DESC', implode(' AND ', $w));
		if ($limit) $sql .= " LIMIT $limit";
		if ($offset) $sql .= " OFFSET $offset";
		// echo "<br>" . $sql;
		$listNoticias = $this->selectDB($sql, null, 'NoticiaVO');
		return $listNoticias;
	}

	public function countByIdcategoria($idcategoria) {
		$w = array();
		$w[] = "idcategoria = $idcategoria";
		$sql = sprintf('SELECT count(*) FROM noticia WHERE %s', implode(' AND ', $w));
		$listNoticias = $this->selectDB($sql, null);
		return $listNoticias[0]['count(*)'];
	}

	public function getById($id) {
		$noticia = $this->selectDB('SELECT * FROM noticia WHERE idnoticia = '.$id, null, 'NoticiaVO');
    return $noticia[0];
	}

  public function delete(NoticiaVO &$objVo) {
    $ret = $this->deleteDB("DELETE FROM noticia WHERE idnoticia = ". $objVo->getIdnoticia());
    return (int)$ret;
  }
	
}
?>