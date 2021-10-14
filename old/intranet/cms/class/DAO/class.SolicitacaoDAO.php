<?php

class SolicitacaoDAO  extends Conexao {

	public function insert(SolicitacaoVO $objVo) {
		$sql = sprintf('INSERT INTO solicitacao (idproduto, nome, email, telefone, mensagem, status, idusuario, obs, data, data_final)
						VALUES ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s")', 
						addslashes($objVo->getIdproduto()),
						addslashes($objVo->getNome()),
						addslashes($objVo->getEmail()),
						addslashes($objVo->getTelefone()),
						addslashes($objVo->getMensagem()),
						addslashes($objVo->getStatus()),
						addslashes($objVo->getIdusuario()),
						addslashes($objVo->getObs()),
						addslashes($objVo->getData()),
						addslashes($objVo->getData_final()));
		$objVo->setIdsolicitacao($this->insertDB($sql));
		return $objVo;
	}

	public function update(SolicitacaoVO $objVo) {
		if (!$objVo -> getIdsolicitacao())
			throw new Exception('Valor da chave primária inválido');

		$sql = sprintf('UPDATE solicitacao set idproduto="%s", nome="%s", email="%s", telefone="%s", mensagem="%s", status="%s", idusuario="%s", obs="%s", data="%s", data_final="%s" where idsolicitacao = "%s" ', 
					addslashes($objVo->getIdproduto()),
					addslashes($objVo->getNome()),
					addslashes($objVo->getEmail()),
					addslashes($objVo->getTelefone()),
					addslashes($objVo->getMensagem()),
					addslashes($objVo->getStatus()),
					addslashes($objVo->getIdusuario()),
					addslashes($objVo->getObs()),
					addslashes($objVo->getData()),
					addslashes($objVo->getData_final()),
					$objVo->getIdsolicitacao());
		$this->updateDB($sql);
		return $objVo;
	}

	public function save(SolicitacaoVO &$objVo) {
		if ($objVo -> getIdsolicitacao() !== null) {
			return $this -> update($objVo);
		} else {
			return $this -> insert($objVo);
		}
	} 

	public function getAll() {
    $w = array();

    $sql = sprintf('select * from solicitacao order by idsolicitacao DESC');
		$listSolicitacoes = $this->selectDB($sql, null, 'SolicitacaoVO');
    return $listSolicitacoes;
  }
  
  public function getCount() {
		$sql = 'select count(*) from solicitacao';
		$listSolicitacoes = $this->selectDB($sql, null);
    return $listSolicitacoes[0]['count(*)'];
	}

	public function getByFiltro($status = null) {    
		$sql = sprintf('SELECT * FROM solicitacao WHERE status="%s" order by idsolicitacao DESC', $status);
		$listSolicitacoes = $this->selectDB($sql, null, 'SolicitacaoVO');
    return $listSolicitacoes;
	}


	public function getById($id) {
		$sql = sprintf('select * from solicitacao where idsolicitacao = "%s"', $id);
		$listSolicitacoes = $this->selectDB($sql, null, 'SolicitacaoVO');
    return $listSolicitacoes[0];
	}

	public function getByIdunidade($id) {
		$sql = sprintf('select * from solicitacao where idunidade = "%s" ORDER by idsolicitacao DESC', $id);
		$listSolicitacoes = $this->selectDB($sql, null, 'SolicitacaoVO');
    return $listSolicitacoes;
	}


	public function delete(SolicitacaoVO $objVo) {
		if ($objVo -> getIdsolicitacao() == null)
			throw new Exception('Valor da chave primária inválido.');
		$sql = sprintf('delete from solicitacao where idsolicitacao = "%s"', $objVo -> getIdsolicitacao());
		$ret = $this->deleteDB($sql);
	}

}
?>