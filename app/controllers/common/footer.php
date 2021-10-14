<?php

namespace Mubbi;

/**
* Default Controller
*/
class ControllerCommonFooter extends BaseController {
  public function index($data) {

    
    // $data['modal'] = $this->load->controller('common/modal', $data);
    
    $this->document->addScript('scripts/index');
    
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
