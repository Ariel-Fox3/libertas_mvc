<?php

namespace Mubbi;

/**
* Default Controller
*/
class ControllerLeadsHome extends BaseController {
  public function index() {

    // $this->load->language('common/home');

    $this->document->setTitle('Leads');

    $data['user_info'] = $this->session->data['user_info'];

    
    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('leads/list', $data));
  }
}
