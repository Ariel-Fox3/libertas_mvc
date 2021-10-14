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

    public function getByIdpagina($idpagina, $name_as_index = false) {
      $q = sprintf('SELECT * FROM arquivo WHERE idpagina = %s AND idbloco IS NULL', (int)$idpagina);
      $ret = $this->db->query($q)->rows;

      if ($name_as_index === false) {
        return $ret;
      } else {
        $files = array();
        if (sizeof($ret) > 0) {
          foreach($ret as $key => $val) {
            $files[$val['nome']] = $val;
          }
        }
        return $files;
      }
    }

    public function getByIdproduto($idproduto, $name_as_index = false) {
      $q = sprintf('SELECT * FROM arquivo WHERE idproduto = %s AND idbloco IS NULL', (int)$idproduto);
      $ret = $this->db->query($q)->rows;

      if ($name_as_index === false) {
        return $ret;
      } else {
        $files = array();
        if (sizeof($ret) > 0) {
          foreach($ret as $key => $val) {
            $files[$val['nome']] = $val;
          }
        }
        return $files;
      }
    }

    public function getByIdbloco($idbloco, $name_as_index = false) {
      $q = sprintf('SELECT * FROM arquivo WHERE idbloco = %s', (int)$idbloco);
      $ret = $this->db->query($q)->rows;

      if ($name_as_index === false) {
        return $ret;
      } else {
        $files = array();
        if (sizeof($ret) > 0) {
          foreach($ret as $key => $val) {
            $files[$val['nome']] = $val;
          }
        }
        return $files;
      }
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
      return $this->db->countAffected() > 0 ? true : false;
    }

    public function save($data) {
      if (isset($data['idarquivo']) && $data['idarquivo'] !== '' && (int)$data['idarquivo'] !== 0) {
        $this->edit($data);
      } else {
        $this->add($data);
      }
    }
    
    public function delete($idarquivo) {
      $q = sprintf('DELETE FROM arquivo WHERE idarquivo = %s', (int)$idarquivo);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

}
