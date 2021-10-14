<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelConfigConfig extends BaseModel{
    public function getAll() {
        $q = sprintf('SELECT * FROM config');
        return $this->db->query($q)->rows;
    }

    public function getById($idconfig) {
      $q = sprintf('SELECT * FROM config WHERE idconfig = %s', (int)$idconfig);
      return $this->db->query($q)->row;
    }

    public function getByChave($chave) {
      $w = array();
      $w[] = "chave = '$chave'";
      $sql = sprintf('SELECT * FROM config WHERE %s', implode(' AND ', $w));
      return $this->db->query($sql)->row;
    }

    public function add($data) {
      $change = $this->db->formatChange($data);

      $q = sprintf('INSERT INTO config SET %s ', $change);
      $this->db->query($q);
      $data['idconfig'] = $this->db->getLastId();
      return $data;
    }

    public function edit($data) {
      if (!isset($data['idconfig'])) return false;
      $change = $this->db->formatChange($data);

      $q = sprintf('UPDATE config SET %s WHERE idconfig = %s', $change, (int)$data['idconfig']);
      $this->db->query($q);
      return $data;
    }

    public function save($config) {
      if (isset($config['idconfig']) && $config['idconfig'] !== null) {
        return $this->edit($config);
      } else {
        return $this->add($config);
      }
    }
    
    public function delete($idconfig) {
      $q = sprintf('DELETE FROM config WHERE idconfig = %s', (int)$idconfig);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }

    /*
    public function getByFiltro(array $filtro) {
      $w = array();
      if (isset($filtro['status'])) $w[] = 'resposta_entrega.status = "' . $filtro['status'] . '"';
      if (isset($filtro['status_cliente'])) $w[] = 'resposta_entrega.status_cliente = "' . $filtro['status_cliente'] . '"';
      if (isset($filtro['idcliente'])) $w[] = 'resposta_entrega.idcliente = "' . $filtro['idcliente'] . '"';

      $q = sprintf('SELECT entrega.*, (SELECT COUNT(*) FROM arquivo WHERE arquivo.identrega = entrega.identrega) AS arquivos, resposta_entrega.*, cliente.nome AS nome_cliente FROM `resposta_entrega` INNER JOIN entrega ON (resposta_entrega.identrega = entrega.identrega) INNER JOIN cliente ON (entrega.idcliente = cliente.idcliente) %s' , sizeof($w) > 0 ? 'WHERE ' . implode(' AND ', $w) : '');
      $res = $this->db->query($q)->rows;
      $return = array();
      if (sizeof($res) > 0) {
        foreach ($res as $row) {
          if (!isset($return[$row['nome_cliente']])) $return[$row['nome_cliente']] = array();
          $tmp_r = array(
            'entrega' => array(),
            'resposta_entrega' => array(),
          );
          $index = 'entrega';
          foreach ($row as $key => $value) {
            if ($key == 'idresposta_entrega') $index = 'resposta_entrega';
            $tmp_r[$index][$key] = $value; 
          }
          $return[$row['nome_cliente']][] = $tmp_r;
        }
      }
      
      // echo "<pre>";
      //   print_r($return);
      // echo "</pre>";
      return $return;
    }

  
    
    public function getByIdcronograma($idcronograma) {
      $q = sprintf('SELECT * FROM resposta WHERE idcronograma = %s', (int)$idcronograma);
      return $this->db->query($q)->rows;
    }

    

    public function delete($identrega) {
      $q = sprintf("UPDATE entrega SET ativo = 0 WHERE identrega = %s", (int)$identrega);
      $this->db->query($q);
      return true;
    }

    public function add($data) {

        @$change = array(
            'nome' => $data['inputNome'],
            'cor' => $data['inputCor'],
            'bypass_atividade' => $data['inputBypass_atividade']
        );

        $q = sprintf('INSERT INTO grupo SET %s', $this->db->formatChange($change));
        $this->db->query($q);
        return $this->db->countAffected() > 0 ? true : false;
    }

    public function edit($data) {
        if (!$data['idgrupo']) {
            return false;
        }

        @$change = array(
            'nome' => $data['inputNome'],
            'cor' => $data['inputCor'],
            'bypass_atividade' => $data['inputBypass_atividade']
        );

        $q = sprintf('UPDATE grupo SET %s WHERE idgrupo = %s', $this->db->formatChange($change), (int)$data['idgrupo']);
        $this->db->query($q);
        return $this->db->countAffected() > 0 ? true : false;
    }
    */


}
