<?php

class PaginaDAO extends Conexao {

	public function insert(PaginaVO $objVo) {
		$sql = sprintf('INSERT INTO pagina (fixo, sublink, idsublink, nome, conteudo, externo, link, ativo, ordem, idtipo_aula, idtipo_curso)
						VALUES ("%s", "%s", %s, "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s")',
						addslashes($objVo->getFixo()),
						addslashes($objVo->getSublink()),
						($objVo->getIdsublink() != null ? $objVo->getIdsublink() : "NULL"),
						addslashes($objVo->getNome()),
						addslashes($objVo->getConteudo()),
						addslashes($objVo->getExterno()),
            $objVo->getLink(),
						addslashes($objVo->getAtivo()),
						addslashes($objVo->getOrdem()),
						addslashes($objVo->getIdtipo_aula()),
						addslashes($objVo->getIdtipo_curso()));
		$objVo->setIdpagina($this->insertDB($sql));
		return $objVo;
	}

	public function tirarAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
	}

	public function update(PaginaVO $objVo) {
		if (!$objVo -> getIdpagina())
			throw new Exception('Valor da chave primária inválido');

		$sql = sprintf('UPDATE pagina set fixo="%s", sublink="%s", idsublink="%s", nome="%s", conteudo="%s", externo="%s", link="%s", ativo="%s", ordem="%s", idtipo_aula="%s", idtipo_curso="%s" where idpagina = "%s" ',
						addslashes($objVo->getFixo()),
						addslashes($objVo->getSublink()),
						($objVo->getIdsublink() != null ? $objVo->getIdsublink() : NULL),
            addslashes($objVo->getNome()),
						addslashes($objVo->getConteudo()),
						addslashes($objVo->getExterno()),
            $objVo->getLink(),
						addslashes($objVo->getAtivo()),
						addslashes($objVo->getOrdem()),
						addslashes($objVo->getIdtipo_aula()),
						addslashes($objVo->getIdtipo_curso()),
						addslashes($objVo->getIdpagina()));
		$this->updateDB($sql);
		return $objVo;
	}

	public function save(PaginaVO &$objVo) {
		if ($objVo -> getIdpagina() !== null) {
			return $this -> update($objVo);
		} else {
			return $this -> insert($objVo);
		}
	}

	public function getAll() {
		$sql = 'select * from pagina order BY ordem ASC';
		$listPaginas = $this->selectDB($sql, null, 'PaginaVO');
    return $listPaginas;
  }
  
	public function getByNome($nome) {
	$sql = 'select * from pagina WHERE nome = "' . $nome . '" order BY ordem ASC';
	$listPaginas = $this->selectDB($sql, null, 'PaginaVO');
	return $listPaginas;
	}

	public function getById($id) {
		$sql = sprintf('select * from pagina where idpagina = "%s"', $id);
		$listPaginas = $this->selectDB($sql, null, 'PaginaVO');
    return $listPaginas[0];
	}

	public function getByIdsublink($id) {
		$sql = sprintf('select * from pagina where idsublink = "%s" order by ordem ASC', $id);
		$listPaginas = $this->selectDB($sql, null, 'PaginaVO');
    return $listPaginas;
	}

	public function getByTipo_curso($idtipo_curso) {
		$sql = sprintf('select * from pagina where idtipo_curso = "%s" order by ordem ASC', $idtipo_curso);
		$listPaginas = $this->selectDB($sql, null, 'PaginaVO');
    return $listPaginas[0];
	}

	public function getByTipo_aula($idtipo_aula) {
		$sql = sprintf('select * from pagina where idtipo_aula = "%s" order by ordem ASC', $idtipo_aula);
		$listPaginas = $this->selectDB($sql, null, 'PaginaVO');
    return $listPaginas[0];
	}

	public function getCount($idsublink = null) {
		$w = array();
		if ($idsublink != null) $w[] = "idsublink = $idsublink";
		$sql = sprintf('SELECT count(*) FROM pagina %s', sizeof($w) > 0 ? 'WHERE ' . implode(' AND ', $w) : '');
		$listcurso = $this->selectDB($sql, null);
		return $listcurso[0]['count(*)'];
	}

	public function delete(PaginaVO $objVo) {
		if ($objVo -> getIdpagina() == null)
			throw new Exception('Valor da chave primária inválido.');
		$ret = $this->deleteDB("DELETE FROM pagina WHERE idpagina = " . $objVo->getIdpagina());
    return (int)$ret;
	}
}
?>
