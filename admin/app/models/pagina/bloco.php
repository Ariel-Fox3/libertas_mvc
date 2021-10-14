<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelPaginaBloco extends BaseModel{
    public function getAll($idpagina) {
        $q = sprintf('SELECT * FROM bloco_pagina WHERE idpagina = %s', $idpagina);
        return $this->db->query($q)->rows;
    }

    public function getById($idbloco_pagina) {
      $q = sprintf('SELECT * FROM bloco_pagina WHERE idbloco_pagina = %s', (int)$idbloco_pagina);
      return $this->db->query($q)->row;
    }

    public function getByChave($chave) {
      $w = array();
      $w[] = "chave = '$chave'";
      $sql = sprintf('SELECT * FROM bloco_pagina WHERE %s', implode(' AND ', $w));
      return $this->db->query($sql)->row;
    }

    public function add($data) {
      $change = $this->db->formatChange($data);

      $q = sprintf('INSERT INTO bloco_pagina SET %s ', $change);
      $this->db->query($q);
      $data['idbloco_pagina'] = $this->db->getLastId();
      return $data;
    }

    public function edit($data) {
      if (!isset($data['idbloco_pagina'])) return false;
      $change = $this->db->formatChange($data);

      $q = sprintf('UPDATE bloco_pagina SET %s WHERE idbloco_pagina = %s', $change, (int)$data['idbloco_pagina']);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

    public function save($data) {
      if (isset($data['idbloco_pagina']) && $data['idbloco_pagina'] !== '' && (int)$data['idbloco_pagina'] !== 0) {
        $this->edit($data);
      } else {
        $this->add($data);
      }
    }
    
    public function delete($idbloco_pagina) {
      $q = sprintf('DELETE FROM bloco_pagina WHERE idbloco_pagina = %s', (int)$idbloco_pagina);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

}
