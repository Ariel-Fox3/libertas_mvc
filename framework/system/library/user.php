<?php
  namespace User;

  use Mubbi\BaseController;

  class User{
    private $registry = NULL;
    private $db = NULL;
    private $permissions = NULL;

    public $data = array();

    public function __construct($parent){
      $this->registry = $parent;
      $this->db = $this->registry->get('db');
      $this->permissions = $this->registry->get('permissions');
      $this->session = $this->registry->get('session');
      $request = $parent->get('request');
      
      if (isset($this->session->data['idusuario'])) {
        $q = $this->db->query('SELECT * FROM `usuario` WHERE idusuario = "' . (int)$this->session->data['idusuario'] . '"');
        
        $this->data = $q->row;
        $this->session->data['user_info'] = $this->data;
        $this->session->data['current_url'] = $request->server['SERVER_NAME'] . $request->server['REQUEST_URI'];
        $this->db->query("UPDATE `usuario` SET ultima_atividade = '" . date('Y-m-d H:i:s') . "' WHERE idusuario = '" . (int)$this->getIdusuario() . "'");
      } else {
        // if ($request->get['route'] !== 'account/login') {
        //   $response = $parent->get('response');
        //   $url = $parent->get('url');
        //   $response->redirect($url->link('account/login'));
        // }
      }
    }

    public function login($email, $password) {
      $q = $this->db->query('SELECT * FROM `usuario` WHERE email = "'.$email.'" AND ativo="1"');
      if ($q->num_rows > 0) {
        $user = $q->row;
        
        if (password_verify($password, $user['senha'])) {
					$rehash = password_needs_rehash($user['senha'], PASSWORD_DEFAULT);
				} elseif ($user['senha'] == sha1($user['salt'] . sha1($user['salt'] . sha1($password)))) {
					$rehash = true;
				} elseif ($user['senha'] == md5($password)) {
					$rehash = true;
				} else {
					return false;
				}

				if ($rehash) {
					$this->db->query("UPDATE `usuario` SET salt = '', senha = '" . $this->db->escape(password_hash($password, PASSWORD_DEFAULT)) . "' WHERE idusuario = '" . (int)$user['idusuario'] . "'");
				}

        
        $this->data = $q->row;
        $this->session->data['idusuario'] = $this->data['idusuario'];
        
        $this->session->data['user_info'] = $this->data;       

        $this->db->query("UPDATE `usuario` SET ultimo_acesso = '" . date('Y-m-d H:i:s') . "' WHERE idusuario = '" . (int)$this->getIdusuario() . "'");

        return true;
      } else {
        return false;
      }
    }

    public function logout() {
      unset($this->session->data['idusuario']);
  
      foreach ($this->data as $key => $value) {
        $this->data[$key] = NULL;
      }
    }

    public function getUserInfo() {
      if (isset($this->session->data['user_info'])) {
        return $this->session->data['user_info'];
      } else {
        return false;
      }
    }

    public function isLogged() {
      return isset($this->data['idusuario']) && $this->data['idusuario'] !== NULL ? true : false;
    }

    public function getIdusuario() {
      return $this->data['idusuario'];
    }

    public function getIdunidade() {
      return $this->data['idunidade'];
    }
  
    public function getIdgrupo() {
      return $this->data['idgrupo'];
    }
  
    public function getUsuario() {
      return $this->data['usuario'];
    }
  
    public function getSenha() {
      return $this->data['senha'];
    }
  
    public function getAtivo() {
      return $this->data['ativo'];
    }
  
    public function getUltimo_acesso() {
      return $this->data['ultimo_acesso'];
    }
  
    public function getUltima_atividade() {
      return $this->data['ultima_atividade'];
    }
  
    public function getNome() {
      return $this->data['nome'];
    }
  
    public function getEmail() {
      return $this->data['email'];
    }
  
    public function getIdcliente() {
      return $this->data['idcliente'];
    }
  
    public function getImagem() {
      return $this->data['imagem'];
    }
    
    public function getPermissions() {
      if (isset($this->data['permissions']) && $this->data['permissions'] == null) {
        $p = json_decode($this->data['permissoes'], true);
        $this->data['permissions'] = $this->permissions->getPermissions($p);
        return $this->data['permissions'];
      } else {
        return null;
      }
    }
  
    public function getHorarios() {
      return $this->data['horarios'];
    }
  
    public function getWhatsApp() {
      return $this->data['whatsapp'];
    }
  
    public function getNotif_whatsapp() {
      return $this->data['notif_whatsapp'];
    }
  
    public function getLog_whatsapp() {
      return $this->data['log_whatsapp'];
    }
  
    public function getAniversario() {
      return $this->data['aniversario'];
    }

    
  }
