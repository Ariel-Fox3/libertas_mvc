<?php
  namespace Painel;

  use Mubbi\BaseController;
  use Mubbi\Database;

  class Painel {
    private $registry = NULL;
    private $db = NULL;

    public function __construct() {
      $this->db = new Database(PAINEL_DB_TYPE, PAINEL_DB_HOST, PAINEL_DB_USER, PAINEL_DB_PASSWORD, PAINEL_DB_NAME, PAINEL_DB_PORT);
    }

    public function save($data) {
      $sql = null;
      $sql_search = sprintf('SELECT * FROM usuario WHERE idcliente = %s', $data['idcliente']);
      $exist = $this->db->query($sql_search)->row;
      $data['configs'] = json_encode($data['configs']);
      if ($exist) {
        $sql = sprintf('UPDATE usuario SET %s WHERE idusuario = %s', $this->db->formatChange($data), $data['idusuario']);
      } else {
        $sql = sprintf('INSERT INTO usuario SET %s', $this->db->formatChange($data));
      }

      $this->db->query($sql);
      return $this->db->countAffected() > 0 ? true : false;

    }

    public function getInfo($idcliente) {
      $sql = sprintf('SELECT * FROM usuario WHERE idcliente = %s', $idcliente);
      return $this->db->query($sql)->row;
    }
  }

?>