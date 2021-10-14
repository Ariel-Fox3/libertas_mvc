<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelConfigPlataforma extends BaseModel{
    public function getAll(bool $ativos = false) {
        $q = sprintf('SELECT * FROM plataforma_agendamento %s', $ativos === true ? 'WHERE ativo = 1' : '');
        return $this->db->query($q)->rows;
    }

    public function getById($idplataforma_agendamento) {
      $q = sprintf('SELECT * FROM plataforma_agendamento WHERE idplataforma_agendamento = %s', (int)$idplataforma_agendamento);
      return $this->db->query($q)->row;
    }

    public function add($data) {
      $data['ativo'] = 1;
      $change = $this->db->formatChange($data);

      $q = sprintf('INSERT INTO plataforma_agendamento SET %s ', $change);
      $this->db->query($q);
      $data['idplataforma_agendamento'] = $this->db->getLastId();
      return $data;
    }

    public function edit($data) {
      if (!isset($data['idplataforma_agendamento'])) return false;
      $change = $this->db->formatChange($data);

      $q = sprintf('UPDATE plataforma_agendamento SET %s WHERE idplataforma_agendamento = %s', $change, (int)$data['idplataforma_agendamento']);
      $this->db->query($q);
      return $this->db->countAffected() > 0 ? true : false;
    }
    
    public function delete($idplataforma_agendamento) {
      $q = sprintf('DELETE FROM plataforma_agendamento WHERE idplataforma_agendamento = %s', (int)$idplataforma_agendamento);
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
