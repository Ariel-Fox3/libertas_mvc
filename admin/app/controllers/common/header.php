<?php

namespace Mubbi;

/**
* Default Controller
*/
class ControllerCommonHeader extends BaseController {
  public function index($data) {   
    

    if (isset($this->session->data['user_info'])) {
      $data['user_info'] = $this->session->data['user_info'];
    } else {
      if ($this->request->get['route'] !== 'account/login') {
        $this->response->redirect($this->url->link('account/login'));
      }
    }
   

    $data['login_page'] = false;
    if ($this->request->get['route'] == 'account/login') $data['login_page'] = true;

    return $this->load->view('common/header', $data);
  }
}
