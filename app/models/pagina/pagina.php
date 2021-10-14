<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelPaginaPagina extends BaseModel{
    public function getAll($ativos = false) {
        $q = sprintf('SELECT * FROM pagina %s ORDER BY ordem ASC', $ativos == true ? 'WHERE ativo = "1"' : '');
        return $this->db->query($q)->rows;
    }

    public function getNear($func, $idatual, $idsublink = null) {
      $w = array();
      if ($func == 'proximo') {
        $w[] = "idpagina > $idatual";
      } else if ($func == 'anterior') {
        $w[] = "idpagina < $idatual";
      } else {
        echo 'func deve ser proximo ou anterior';
        exit;
      }
      if ($idsublink !== null) $w[] = "sublink = $idsublink";
      $q = sprintf('SELECT * FROM pagina WHERE %s ORDER BY idpagina %s', implode(' AND ', $w), $func == 'proximo' ? 'ASC' : 'DESC');
      return $this->db->query($q)->rows;
    }

    public function getById($idpagina) {
      $q = sprintf('SELECT p.idpagina, p.sublink, p.nome as nome_pagina, p.externo, p.ativo, p.ordem, a.* FROM pagina AS p LEFT JOIN arquivo AS a ON (p.idpagina = a.idpagina) WHERE p.idpagina = %s AND a.idbloco IS NULL', (int)$idpagina);
      // echo $q;
      // exit;
      $ret = $this->db->query($q)->rows;
      $pagina = null;
      $arquivos = array();
      if (sizeof($ret) > 0) {
        foreach($ret as $row) {
          $p = array();
          $a = array();
          $switch = 'p';
          foreach ($row as $key => $value) {
            if ($key == 'idarquivo') $switch = 'a';
            if ($key == 'nome_pagina') $key = 'nome';
            $$switch[$key] = $value; 
          }

          if ($pagina == null) $pagina = $p;
          $arquivos[$a['nome']] = $a;
        }

        $pagina['arquivos'] = $arquivos;
      } else {
        $q = sprintf('SELECT * FROM pagina WHERE idpagina = %s', (int)$idpagina);
        $pagina = $this->db->query($q)->row;
        $pagina['arquivos'] = array();
      }

      return $pagina;
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
