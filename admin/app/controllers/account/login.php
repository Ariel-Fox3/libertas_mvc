<?php

namespace Mubbi;

class ControllerAccountLogin extends BaseController {
  public function index() {
    $this->document->setTitle('FOX3 Comunicação Digital');

    if ($this->user->isLogged()) {
      $this->response->redirect($this->url->link('common/home'));
    }

    $data['logo'] = DEFAULT_IMG_USER;

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if ($this->user->login($this->request->post['usuario'], html_entity_decode($this->request->post['senha'], ENT_QUOTES, 'UTF-8'))) {
        $this->session->data['idusuario'] = $this->user->getIdusuario();
        $this->session->data['user_info'] = $this->user->getUserInfo();
        $this->response->redirect($this->url->link('common/home'));
      } else {
        $data['error'] = 'Usuário ou senha incorretos!';
      }
    }

    $data['sidebar'] = ''; $data['navbar'] = '';
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer');


    $this->response->setOutput($this->load->view('account/login', $data));
  }
}
