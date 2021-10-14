<?php

namespace Mubbi;

/**
* Default Controller
*/
class ControllerConfigHome extends BaseController {
  public function index($data = null) {

    // $this->load->language('common/home');

    $this->document->setTitle('Configurações');
    $this->load->model('config/config');
    
    $data['user_info'] = $this->session->data['user_info'];

    $data['configs'] = array();

    $configs = $this->model_config_config->getAll();
    if (sizeof($configs) > 0) {
      foreach($configs as $key => $val) {
        $val['val'] = json_decode($val['val'], true);
        $data['configs'][$val['chave']] = $val;
      }
    }

    // echo "<pre>";
    //   print_r($data['configs']);
    // echo "</pre>";
    // exit;

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['func']) && $this->request->post['func'] !== '') {
        // echo "<pre>";
        //   print_r($this->request->post);
        // echo "</pre>";
        // exit;
        $config = $this->model_config_config->getByChave($this->request->post['func']);
        if (!$config) $config = array();
        $post = $this->request->post;
        unset($post['func']);
        $config['val'] = json_encode($post);
        $config['chave'] = $this->request->post['func'];

        $config = $this->model_config_config->save($config);

        $this->session->data['success'] = array('key' => 'edit_config');
        $this->response->redirect($this->url->link('config/home'));
      }
    }

    $data['gerais'] = $this->load->view('config/gerais', $data);
    if ($this->session->data['user_info']['admin'] == true) {
      $data['usuarios'] = $this->load->controller('config/usuarios', $data);
    }
    
    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('config/home', $data));
  }
}
