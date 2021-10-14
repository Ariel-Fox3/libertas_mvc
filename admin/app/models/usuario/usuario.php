<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelUsuarioUsuario extends BaseModel{
    public function getAll(bool $ativos = false) {
        $q = sprintf('SELECT * FROM usuario %s', $ativos == true ? 'WHERE ativo = "1"' : '');
        $ret = $this->db->query($q)->rows;
        return $ret;
    }

    public function getById($idusuario) {
        $q = sprintf('SELECT * FROM usuario WHERE idusuario = %s', (int)$idusuario);
        return $this->db->query($q)->row;
    }

    public function add($data) {
        $change = $this->db->formatChange($data);

        $q = sprintf('INSERT INTO usuario SET %s ', $change);
        $this->db->query($q);
        $data['idusuario'] = $this->db->getLastId();
        return $data;
    }

    public function edit($data) {
        if (!isset($data['idusuario'])) return false;
        $change = $this->db->formatChange($data);

        $q = sprintf('UPDATE usuario SET %s WHERE idusuario = %s', $change, (int)$data['idusuario']);
        $this->db->query($q);
        return $data;
    }

    public function getPermissionsUser($idusuario) {
        $q = $this->db->query('SELECT permissoes FROM usuario WHERE idusuario = ' . (int)$idusuario);
        return json_decode($q->row['permissoes'], true);
    }
}
