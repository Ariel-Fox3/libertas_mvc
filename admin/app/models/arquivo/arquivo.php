<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelArquivoArquivo extends BaseModel{
    public function getAll() {
        $q = sprintf('SELECT * FROM arquivo');
        return $this->db->query($q)->rows;
    }

    public function getById($idarquivo) {
      $q = sprintf('SELECT * FROM arquivo WHERE idarquivo = %s', (int)$idarquivo);
      return $this->db->query($q)->row;
    }

    public function getByIdproduto($idproduto) {
      $q = sprintf('SELECT * FROM arquivo WHERE idproduto = %s ORDER BY ordem ASC', (int)$idproduto);
      return $this->db->query($q)->rows;
    }

    public function getByIdpagina($idpagina) {
      $q = sprintf('SELECT * FROM arquivo WHERE idpagina = %s', (int)$idpagina);
      return $this->db->query($q)->rows;
    }

    public function getByIdbloco($idbloco, $idpagina = null, $idproduto = null) {
      $w = array();
      $w[] = "idbloco = $idbloco";
      if ($idpagina !== null) $w[] = "idpagina = $idpagina";
      if ($idproduto !== null) $w[] = "idproduto = $idproduto";
      $q = sprintf('SELECT * FROM arquivo WHERE %s ORDER BY ordem ASC', implode(' AND ', $w));
      return $this->db->query($q)->rows;
    }

    public function add($data) {
      $change = $this->db->formatChange($data);

      $q = sprintf('INSERT INTO arquivo SET %s ', $change);
      $this->db->query($q);
      $data['idarquivo'] = $this->db->getLastId();
      return $data;
    }

    public function edit($data) {
      if (!isset($data['idarquivo'])) return false;
      $change = $this->db->formatChange($data);

      $q = sprintf('UPDATE arquivo SET %s WHERE idarquivo = %s', $change, (int)$data['idarquivo']);
      $this->db->query($q);
      return $data;
    }

    public function save($data) {
      if (isset($data['idarquivo']) && $data['idarquivo'] !== '' && (int)$data['idarquivo'] !== 0) {
        return $this->edit($data);
      } else {
        return $this->add($data);
      }
    }
    
    public function delete($idarquivo) {
      $q = sprintf('DELETE FROM arquivo WHERE idarquivo = %s', (int)$idarquivo);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

}
