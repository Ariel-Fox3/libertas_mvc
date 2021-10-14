<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelProdutoProduto extends BaseModel{
    public function getAll($ativos = false) {
        $q = sprintf('SELECT * FROM produto %s ORDER BY ordem ASC', $ativos == true ? 'WHERE ativo = "1"' : '');
        return $this->db->query($q)->rows;
    }

    public function getById($idproduto) {
      $q = sprintf('SELECT * FROM produto WHERE idproduto = %s', (int)$idproduto);
      return $this->db->query($q)->row;
    }

    public function getByIddepartamento($iddepartamento = null) {
      if ($iddepartamento !== null) {
        $q = sprintf('SELECT * FROM produto WHERE iddepartamento = %s ORDER BY ordem ASC', (int)$iddepartamento);
      } else {
        $q = sprintf('SELECT * FROM produto WHERE iddepartamento IS NULL OR iddepartamento = 0 ORDER BY ordem ASC');
      }
      return $this->db->query($q)->rows;
    }

    public function add($data) {
      $data['ativo'] = 1;
      $change = $this->db->formatChange($data);

      $q = sprintf('INSERT INTO produto SET %s ', $change);
      $this->db->query($q);
      $data['idproduto'] = $this->db->getLastId();
      return $data;
    }

    public function edit($data) {
      if (!isset($data['idproduto'])) return false;
      $change = $this->db->formatChange($data);

      $q = sprintf('UPDATE produto SET %s WHERE idproduto = %s', $change, (int)$data['idproduto']);
      $this->db->query($q);
      return $data;
    }
    
    public function delete($idproduto) {
      $q = sprintf('UPDATE produto SET ativo = 0 WHERE idproduto = %s', (int)$idproduto);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

    public function getCount($iddepartamento = null) {
      $w = array();
      if ($iddepartamento != null) $w[] = "iddepartamento = $iddepartamento";
      $sql = sprintf('SELECT count(*) FROM produto %s', sizeof($w) > 0 ? 'WHERE ' . implode(' AND ', $w) : '');
      $count = $this->db->query($sql)->row;
      return $count['count(*)'];
    }

}
