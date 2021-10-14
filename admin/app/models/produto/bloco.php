<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelProdutoBloco extends BaseModel{
    public function getAll($idproduto) {
        $q = sprintf('SELECT * FROM bloco_produto WHERE idproduto = %s', $idproduto);
        return $this->db->query($q)->rows;
    }

    public function getById($idbloco_produto) {
      $q = sprintf('SELECT * FROM bloco_produto WHERE idbloco_produto = %s', (int)$idbloco_produto);
      return $this->db->query($q)->row;
    }

    public function getByIdproduto($idproduto) {
      $q = sprintf('SELECT * FROM bloco_produto WHERE idproduto = %s', (int)$idproduto);
      $ret = $this->db->query($q)->rows;

      if (sizeof($ret) > 0) {
        foreach($ret as $key => $bloco) {
          $s = sprintf('SELECT * FROM arquivo WHERE idproduto = %s AND idbloco = %s', (int)$idproduto, (int)$bloco['idbloco_produto']);
          $arquivos = $this->db->query($s)->rows;
          $arquivos_arr = array();
          if (sizeof($arquivos) > 0) {
            foreach($arquivos as $i => $arquivo) {
              if (!isset($arquivos_arr[$arquivo['nome']])) {
                $arquivos_arr[$arquivo['nome']] = $arquivo;
              } else {
                $old_arquivo = $arquivos_arr[$arquivo['nome']];
                $arquivos_arr[$arquivo['nome']] = array();
                $arquivos_arr[$arquivo['nome']][] = $old_arquivo;
                $arquivos_arr[$arquivo['nome']][] = $arquivo;
              }
            }
          }
          $bloco['arquivos'] = $arquivos_arr;
          $ret[$key] = $bloco;
        }
      }

      return $ret;
    }

    public function getByChave($chave) {
      $w = array();
      $w[] = "chave = '$chave'";
      $sql = sprintf('SELECT * FROM bloco_produto WHERE %s', implode(' AND ', $w));
      return $this->db->query($sql)->row;
    }

    public function add($data) {
      $change = $this->db->formatChange($data);

      $q = sprintf('INSERT INTO bloco_produto SET %s ', $change);
      $this->db->query($q);
      $data['idbloco_produto'] = $this->db->getLastId();
      return $data;
    }

    public function edit($data) {
      if (!isset($data['idbloco_produto'])) return false;
      $change = $this->db->formatChange($data);

      $q = sprintf('UPDATE bloco_produto SET %s WHERE idbloco_produto = %s', $change, (int)$data['idbloco_produto']);
      $this->db->query($q);
      return $data;
    }

    public function save($data) {
      if (isset($data['idbloco_produto']) && $data['idbloco_produto'] !== '' && (int)$data['idbloco_produto'] !== 0) {
        return $this->edit($data);
      } else {
        return $this->add($data);
      }
    }
    
    public function delete($idbloco_produto) {
      $q = sprintf('DELETE FROM bloco_produto WHERE idbloco_produto = %s', (int)$idbloco_produto);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

}
