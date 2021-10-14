<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelPaginaPagina extends BaseModel{
    public function getAll($ativos = false) {
        $q = sprintf('SELECT * FROM pagina %s ORDER BY ordem ASC', $ativos == true ? 'WHERE ativo = "1"' : '');
        return $this->db->query($q)->rows;
    }

    public function getById($idpagina) {
      $q = sprintf('SELECT * FROM pagina WHERE idpagina = %s', (int)$idpagina);
      return $this->db->query($q)->row;
    }

    public function getByIdSublink($sublink = null) {
      if ($sublink !== null) {
        $q = sprintf('SELECT * FROM pagina WHERE sublink = %s ORDER BY ordem ASC', (int)$sublink);
      } else {
        $q = sprintf('SELECT * FROM pagina WHERE sublink IS NULL OR sublink = 0 ORDER BY ordem ASC');
      }
      return $this->db->query($q)->rows;
    }

    public function add($data) {
      $data['ativo'] = 1;
      $change = $this->db->formatChange($data);

      $q = sprintf('INSERT INTO pagina SET %s ', $change);
      $this->db->query($q);
      $data['idpagina'] = $this->db->getLastId();
      return $data;
    }

    public function edit($data) {
      if (!isset($data['idpagina'])) return false;
      $change = $this->db->formatChange($data);

      $q = sprintf('UPDATE pagina SET %s WHERE idpagina = %s', $change, (int)$data['idpagina']);
      $this->db->query($q);
      return $data;
    }
    
    public function delete($idpagina) {
      $q = sprintf('UPDATE pagina SET ativo = 0 WHERE idpagina = %s', (int)$idpagina);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

    public function getCount($sublink = null) {
      $w = array();
      if ($sublink != null) $w[] = "sublink = $sublink";
      $sql = sprintf('SELECT count(*) FROM pagina %s', sizeof($w) > 0 ? 'WHERE ' . implode(' AND ', $w) : '');
      $count = $this->db->query($sql)->row;
      return $count['count(*)'];
    }

}
