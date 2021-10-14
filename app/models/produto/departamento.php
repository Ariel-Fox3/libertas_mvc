<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelProdutoDepartamento extends BaseModel{
    public function getAll($ativos = false) {
        $q = sprintf('SELECT * FROM departamento %s', $ativos == true ? 'WHERE ativo = 1' : '');
        return $this->db->query($q)->rows;
    }

    public function getById($iddepartamento) {
      $q = sprintf('SELECT * FROM departamento WHERE iddepartamento = %s', (int)$iddepartamento);
      return $this->db->query($q)->row;
    }

    public function getCount() {
      $q = sprintf('SELECT count(*) FROM departamento');
      return $this->db->query($q)->row['count(*)'];
    }

    public function getByChave($chave) {
      $w = array();
      $w[] = "chave = '$chave'";
      $sql = sprintf('SELECT * FROM departamento WHERE %s', implode(' AND ', $w));
      return $this->db->query($sql)->row;
    }

    public function add($data) {
      $change = $this->db->formatChange($data);

      $q = sprintf('INSERT INTO departamento SET %s ', $change);
      $this->db->query($q);
      $data['iddepartamento'] = $this->db->getLastId();
      return $data;
    }

    public function edit($data) {
      if (!isset($data['iddepartamento'])) return false;
      $change = $this->db->formatChange($data);

      $q = sprintf('UPDATE departamento SET %s WHERE iddepartamento = %s', $change, (int)$data['iddepartamento']);
      $this->db->query($q);
      return $data;
    }

    public function save($data) {
      if (isset($data['iddepartamento']) && $data['iddepartamento'] !== '' && (int)$data['iddepartamento'] !== 0) {
        return $this->edit($data);
      } else {
        return $this->add($data);
      }
    }
    
    public function delete($iddepartamento) {
      $q = sprintf('DELETE FROM departamento WHERE iddepartamento = %s', (int)$iddepartamento);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

}
