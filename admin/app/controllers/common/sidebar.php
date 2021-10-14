<?php

namespace Mubbi;

/**
* Default Controller
*/
class ControllerCommonSidebar extends BaseController {
  public function index() {

    $this->load->language('common/sidebar');

    if (!$this->user->isLogged()) {
      $this->response->redirect($this->url->link('account/logout'), 307);
    }

    $menu = array();

    $menu[] = array(
      'name' => 'Página inicial',
      'url' => $this->url->link('common/home'),
      'icon' => '<i class="align-middle" data-feather="home"></i>'
    );

    // $menu[] = array(
    //   'name' => 'Leads',
    //   'url' => $this->url->link('leads/home'),
    //   'icon' => '<i class="align-middle" data-feather="message-square"></i>'
    // );

    $menu[] = array(
      'name' => 'Gerenciar páginas',
      'url' => $this->url->link('paginas/home'),
      'icon' => '<i class="align-middle" data-feather="layout"></i>'
    );

    $menu[] = array(
      'name' => 'Gerenciar produtos',
      'url' => $this->url->link('produtos/home'),
      'icon' => '<i class="align-middle" data-feather="layout"></i>'
    );

    $menu[] = array(
      'name' => 'Gerenciar notícias',
      'url' => $this->url->link('noticias/home'),
      'icon' => '<i class="align-middle" data-feather="layout"></i>'
    );

    $menu[] = array(
      'name' => 'Solicitações',
      'url' => $this->url->link('solicitacao/home'),
      'icon' => '<i class="align-middle" data-feather="message-square"></i>'
    );

    $menu[] = array(
      'name' => 'Configurações',
      'url' => $this->url->link('config/home'),
      'icon' => '<i class="align-middle" data-feather="settings"></i>'
    );

    $data['menu'] = $menu;

    
    
    $data['user_info'] = $this->session->data['user_info'];
    $data['user_info']['logo'] = DEFAULT_IMG_USER;
    
    $data['cronograma'] = $this->url->link('cronograma/home');
    $data['andamentocrono'] = $this->url->link('cronograma/andamento');
    $data['reuniao'] = $this->url->link('reuniao/home');

    return $this->load->view('common/sidebar', $data);
  }
}
