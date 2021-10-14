<?php

namespace Mubbi;

/**
* Default Controller
*/
class ControllerCommonHeader_startups extends BaseController {
  public function index($data) {   
    // $data['menus'] = $this->load->controller('common/menus');
    return $this->load->view('common/header_startups', $data);
  }
}
