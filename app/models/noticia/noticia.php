<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelNoticiaNoticia extends BaseModel{
    public function getAll($ativos = false) {
        $q = sprintf('SELECT * FROM noticia %s', $ativos == true ? 'WHERE ativo = "1"' : '');
        return $this->db->query($q)->rows;
    }

    public function getById($idnoticia) {
      $q = sprintf('SELECT * FROM noticia WHERE idnoticia = %s', (int)$idnoticia);
      return $this->db->query($q)->row;
    }

    public function getByTag($tag) {
      $sql = sprintf('SELECT * FROM noticia WHERE tag LIKE "%s" and ativo = 1 ORDER BY idnoticia ASC', '%' . $tag . '%');
      $listNoticia = $this->db->query($sql)->rows;
      return $listNoticia;
    }

    public function getBySearch($q) {
      $sql = sprintf('SELECT * FROM noticia WHERE (nome LIKE "%s" OR autores LIKE "%s") AND ativo = 1 AND `data` <= "%s" ORDER BY idnoticia ASC', '%' . $q . '%', '%' . $q . '%', date('Y-m-d'));
      $listNoticia = $this->db->query($sql)->rows;
      return $listNoticia;
    }

    public function getByData($data, $limit = null) {
      $sql = sprintf('SELECT * FROM noticia WHERE `data` <= "%s" and ativo = 1 ORDER BY idnoticia ASC %s', $data, $limit !== null ? 'LIMIT ' . $limit : '');
      $listNoticia = $this->db->query($sql)->rows;
      return $listNoticia;
    }

    public function add($data) {
      $data['ativo'] = 1;
      $change = $this->db->formatChange($data);

      $q = sprintf('INSERT INTO noticia SET %s ', $change);
      $this->db->query($q);
      $data['idnoticia'] = $this->db->getLastId();
      return $data;
    }

    public function edit($data) {
      if (!isset($data['idnoticia'])) return false;
      $change = $this->db->formatChange($data);

      $q = sprintf('UPDATE noticia SET %s WHERE idnoticia = %s', $change, (int)$data['idnoticia']);
      $this->db->query($q);
      return $data;
    }
    
    public function delete($idnoticia) {
      $q = sprintf('UPDATE noticia SET ativo = 0 WHERE idnoticia = %s', (int)$idnoticia);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

    public function save($noticia) {
      if (isset($noticia['idnoticia']) && $noticia['idnoticia'] !== 0 && $noticia['idnoticia'] !== '') {
        return $this->edit($noticia);
      } else {
        return $this->add($noticia);
      }
    }

    public function getCount($sublink = null) {
      $w = array();
      if ($sublink != null) $w[] = "sublink = $sublink";
      $sql = sprintf('SELECT count(*) FROM noticia %s', sizeof($w) > 0 ? 'WHERE ' . implode(' AND ', $w) : '');
      $count = $this->db->query($sql)->row;
      return $count['count(*)'];
    }

}
