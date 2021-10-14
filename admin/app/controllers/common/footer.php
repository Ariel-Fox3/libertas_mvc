<?php

namespace Mubbi;

class ControllerCommonFooter extends BaseController {
  public function index($data) {

    $this->load->model('config/config');
    $cfg = $this->model_config_config->getByChave('cfg');
    $data['painel_info'] = json_decode($cfg['val'], true);

    if (isset($this->session->data['success']) && $this->session->data['success'] !== '') {
      $this->load->language('common/notify');

      $notify = $this->language->get($this->session->data['success']['key']);
      $data['notify'] = array(
        'type'=>$notify['type'],
        'title'=>$notify['title'],
        'text'=>@vsprintf($notify['text'], $this->session->data['success']['data']),
      );
      unset($this->session->data['success']);
    }

    return $this->load->view('common/footer', $data);
  }
}
