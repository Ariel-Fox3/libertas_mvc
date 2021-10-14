<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelSolicitacaoInscricao extends BaseModel{
    public function getAll($idinscricao) {
        $q = sprintf('SELECT * FROM inscricao WHERE idinscricao = %s', $idinscricao);
        return $this->db->query($q)->rows;
    }

    public function getById($idinscricao) {
      $q = sprintf('SELECT * FROM inscricao WHERE idinscricao = %s', (int)$idinscricao);
      return $this->db->query($q)->row;
    }

    public function add($data) {
      $change = $this->db->formatChange($data);

      $q = sprintf('INSERT INTO inscricao SET %s ', $change);
      $this->db->query($q);
      $data['idinscricao'] = $this->db->getLastId();
      return $data;
    }

    public function edit($data) {
      if (!isset($data['idinscricao'])) return false;
      $change = $this->db->formatChange($data);

      $q = sprintf('UPDATE inscricao SET %s WHERE idinscricao = %s', $change, (int)$data['idinscricao']);
      $this->db->query($q);
      return $data;
    }

    public function save($data) {
      if (isset($data['idinscricao']) && $data['idinscricao'] !== '' && (int)$data['idinscricao'] !== 0) {
        return $this->edit($data);
      } else {
        return $this->add($data);
      }
    }
    
    public function delete($idinscricao) {
      $q = sprintf('DELETE FROM inscricao WHERE idinscricao = %s', (int)$idinscricao);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

}
