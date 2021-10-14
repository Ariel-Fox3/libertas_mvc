<?php

namespace Mubbi;

/**
* Default Controller
*/
class ControllerCommonNavbar extends BaseController {
  public function index() {
    $data['user_info'] = $this->session->data['user_info'];

    $data['logout'] = $this->url->link('account/logout');
    
    return $this->load->view('common/navbar', $data);
  }
}
