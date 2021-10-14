<?php

namespace Mubbi;

/**
* Default Controller
*/
class ControllerSolicitacaoHome extends BaseController {
  public function index() {

    // $this->load->language('common/home');

    $this->document->setTitle('Solicitações');
    $this->load->model('config/config');

    $data['user_info'] = $this->session->data['user_info'];

    $data['url_get_solicitacao'] = $this->url->link('solicitacao/home/get');
    $data['url_alterar_status'] = $this->url->link('solicitacao/home/alterar_status');

    $data['status'] = $this->model_config_config->getByChave('gerais');
    $data['status'] = json_decode($data['status']['val'], true);

    $data['solicitacoes'] = $this->getList();

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('solicitacao/list', $data));
  }

  private function getList() {
    $this->load->model('solicitacao/solicitacao');
    $s = $this->model_solicitacao_solicitacao->getAll();

    if (sizeof($s) > 0) {
      foreach($s as $key => $val) {
        $val['form'] = json_decode($val['form'], true);
        $val['user'] = json_decode($val['user'], true);
        $val['log'] = json_decode($val['log'], true);
        $s[$key] = $val;
      }
    }
    // echo "<pre>";
    //   print_r($s);
    // echo "</pre>";
    // exit;

    return $s;

  }

  public function get() {
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      if (isset($this->request->get['idsolicitacao']) && $this->request->get['idsolicitacao'] !== '') {
        $this->load->model('solicitacao/solicitacao');
        $s = $this->model_solicitacao_solicitacao->getById($this->request->get['idsolicitacao']);
        $s['form'] = json_decode($s['form'], true);
        $s['user'] = json_decode($s['user'], true);
        $s['log'] = json_decode($s['log'], true);
        $this->response->json(array('error' => false, 'msg' => $s));
      }
    }
  }

  public function alterar_status() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['idsolicitacao']) && $this->request->post['idsolicitacao'] !== '') {
        $this->load->model('solicitacao/solicitacao');
        $s = $this->model_solicitacao_solicitacao->getById($this->request->post['idsolicitacao']);
        $s['log'] = json_decode($s['log'], true);
        if ($s['log'] == null) {
          $s['log'] = array();
          $s['log'][] = array(
            'date' => $s['joined'],
            'stt' => $s['status'],
            'usuario' => null
          );
        }

        $s['status'] = $this->request->post['status'];

        $s['log'][] = array(
          'date' => date('Y-m-d H:i:s'),
          'stt' => $this->request->post['status'],
          'usuario' => $this->user->getIdusuario()
        );

        $s['log'] = json_encode($s['log']);

        $this->model_solicitacao_solicitacao->edit($s);
        $this->session->data['success'] = array('key' => 'alterar_solicitacao');
        $this->response->json(array('error' => false));
      }
    }
  }
}
