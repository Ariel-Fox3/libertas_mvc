<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelSolicitacaoSolicitacao extends BaseModel{
    public function getAll($idsolicitacao) {
        $q = sprintf('SELECT * FROM solicitacao WHERE idsolicitacao = %s', $idsolicitacao);
        return $this->db->query($q)->rows;
    }

    public function getById($idsolicitacao) {
      $q = sprintf('SELECT * FROM solicitacao WHERE idsolicitacao = %s', (int)$idsolicitacao);
      return $this->db->query($q)->row;
    }

    public function add($data) {
      $change = $this->db->formatChange($data);

      $q = sprintf('INSERT INTO solicitacao SET %s ', $change);
      $this->db->query($q);
      $data['idsolicitacao'] = $this->db->getLastId();
      return $data;
    }

    public function edit($data) {
      if (!isset($data['idsolicitacao'])) return false;
      $change = $this->db->formatChange($data);

      $q = sprintf('UPDATE solicitacao SET %s WHERE idsolicitacao = %s', $change, (int)$data['idsolicitacao']);
      $this->db->query($q);
      return $data;
    }

    public function save($data) {
      if (isset($data['idsolicitacao']) && $data['idsolicitacao'] !== '' && (int)$data['idsolicitacao'] !== 0) {
        return $this->edit($data);
      } else {
        return $this->add($data);
      }
    }
    
    public function delete($idsolicitacao) {
      $q = sprintf('DELETE FROM solicitacao WHERE idsolicitacao = %s', (int)$idsolicitacao);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

}
