<?php

namespace Mubbi;

use DateTime;

class ControllerConfigUsuarios extends BaseController {
  public function index() {

    $this->load->language('usuarios/list');

    $this->document->setTitle('Usuários');

    $deleted = isset($this->request->get['deleted']) && $this->request->get['deleted'] == '1' ? true : false;
    $multi_unidade = isset($this->request->get['multi_unidade']) && $this->request->get['multi_unidade'] == '1' ? true : false;

    $data['deleted'] = $deleted;
    $data['multi_unidade'] = $multi_unidade;
    $data['users'] = $this->getList($deleted, $multi_unidade);

    $data['novo'] = $this->url->link('config/usuarios/add');
    $data['desativar'] = $this->url->link('config/usuarios/desativar');
    $data['alterar'] = $this->url->link('config/usuarios/edit');

    return ($this->load->view('usuario/list', $data));
  }

  public function add() {

    $this->load->language('usuarios/add');

    $this->load->model('usuario/usuario');

    $this->document->setTitle('Adicionar usuário');

    if ($this->request->server['REQUEST_METHOD'] == 'POST') { 
      $usuario = array();
      
      $usuario['nome'] = $this->request->post['inputNome'];
      $usuario['email'] = $this->request->post['inputEmail'];
      $usuario['joined'] = date('Y-m-d H:i:s');
      $usuario['ativo'] = isset($this->request->post['inputAtivo']) ? $this->request->post['inputAtivo'] : '0';
      $usuario['admin'] = isset($this->request->post['inputAdmin']) ? $this->request->post['inputAdmin'] : '0';
      
      if (isset($this->request->post['inputSenha']) && $this->request->post['inputSenha'] != '') {
        $usuario['senha'] = $this->db->escape(password_hash($this->request->post['inputSenha'], PASSWORD_DEFAULT));
        $usuario['salt'] = '';
      }

      $this->model_usuario_usuario->add($usuario);

      $this->session->data['success'] = array('key' => 'settings_usuario_add', 'data' => $this->request->post['inputNome']);
      $this->response->redirect($this->url->link('config/home#usuarios'));
    }

    $data['default_img'] = DEFAULT_IMG_USER;
    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('usuario/add', $data));
  }

  public function edit() {

    $this->load->language('usuarios/edit');

    $this->load->model('usuario/usuario');
    
    if (isset($this->request->get['id']) && $this->request->get['id'] !== '') {
      $usuario = $this->model_usuario_usuario->getById($this->request->get['id']);
      $usuario_old = $usuario;

      $this->document->setTitle($usuario['nome']);

      $data['usuario'] = $usuario;

    }

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {

      $usuario = $this->model_usuario_usuario->getById($this->request->post['idusuario']);

      $usuario['nome'] = $this->request->post['inputNome'];
      $usuario['email'] = $this->request->post['inputEmail'];
      $usuario['ativo'] = isset($this->request->post['inputAtivo']) ? $this->request->post['inputAtivo'] : '0';
      $usuario['admin'] = isset($this->request->post['inputAdmin']) ? $this->request->post['inputAdmin'] : '0';
      
      if (isset($this->request->post['alterarSenha']) && $this->request->post['alterarSenha'] == '1') {
        if (isset($this->request->post['inputSenha']) && $this->request->post['inputSenha'] != '') {
          $usuario['senha'] = $this->db->escape(password_hash($this->request->post['inputSenha'], PASSWORD_DEFAULT));
          $usuario['salt'] = '';
        }
      }
      
      $this->model_usuario_usuario->edit($usuario);

      $this->session->data['success'] = array('key' => 'settings_usuario_edit');
      $this->response->redirect($this->url->link('config/home#usuarios'));
    }

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('usuario/edit', $data));
  }

  public function getList($deleted = false, $multi_unidade = false) {
    $this->load->model('usuario/usuario');

    $usuarios = $this->model_usuario_usuario->getAll();
    return $usuarios;
  }

  public function desativar() {
    if (isset($this->request->get['id']) && $this->request->get['id'] !== '') {
      $this->load->model('usuario/usuario');
      $usuario = $this->model_usuario_usuario->getById($this->request->get['id']);
      $data = array(
        'idusuario' => $this->request->get['id']
      );
      if ($usuario) {
        $str = ''; $acao = '';
        if ($usuario['ativo'] == '1') {
          $data['ativo'] = '0';
          $str = 'inativo';
          $acao = 'desativar_usuario';
        } else {
          $data['ativo'] = '1';
          $str = 'ativo';
          $acao = 'ativar_usuario';
        }
        $this->model_usuario_usuario->edit($data);

        $this->session->data['success'] = array('key' => 'settings_usuario_desativar', 'data' => $str);
        $this->response->json(array('error' => false));
      } else {
        $this->response->json(array('error' => true, 'msg'=>'Usuário não encontrado'));
      }
    }
  }
}

?>