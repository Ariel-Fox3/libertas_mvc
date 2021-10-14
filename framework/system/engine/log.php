<?php
  namespace Mubbi;

  use DateTime;
  use DateTimeImmutable;
  use SqlFormatter;

  class Log {

    private $db = NULL;
    private $user = NULL;
    private $request = NULL;
    private $func = NULL;

    public function __construct($registry) {
      $this->db = $registry->get('db');
      $this->user = $registry->get('user');
      $this->request = $registry->get('request');
      $this->func = $registry->get('func');

      // echo "<pre>";
      //   print_r($this->get(14));
      // echo "</pre>";
      // exit;
    }

    public function save($key, $obj, $idusuario = null) {
      if ($idusuario == null) $idusuario = $this->user->getIdusuario();

      $req = array(
        'referer' => $this->request->server['HTTP_REFERER'], 
        'user_ip' => $this->request->server['REMOTE_ADDR'], 
        'url' => $this->request->server['REQUEST_SCHEME'] . '://' . $this->request->server['REQUEST_SCHEME'] . $this->request->server['REQUEST_URI'], 
      );

      $log = array(
        'date' => date('Y-m-d H:i:s'),
        'idusuario' => $idusuario, 
        'req' => serialize($req), 
        'key' => $key, 
        'obj' => is_array($obj) ? serialize($obj) : $obj, 
      );
      

      $sql = sprintf('INSERT INTO `log` SET %s', $this->db->formatChange($log));
      $this->db->query($sql);
    }

    public function get($idlog) {
      $sql = sprintf('SELECT * FROM `log` WHERE idlog = %s', $idlog);

      $log = $this->db->query($sql)->row;
      if ($this->func->is_serialized($log['obj'])) $log['obj'] = unserialize($log['obj']);
      if ($this->func->is_serialized($log['req'])) $log['req'] = unserialize($log['req']);
      if (isset($log['obj']['old'])) {
        if ($this->func->is_serialized($log['obj']['old'])) $log['obj']['old'] = unserialize($log['obj']['old']);
      }
      if (isset($log['obj']['new'])) {
        if ($this->func->is_serialized($log['obj']['new'])) $log['obj']['new'] = unserialize($log['obj']['new']);
      }
      return $log;
    }
  }

?>