<?php

namespace Mubbi;

/**
* Default Controller
*/
class ControllerCommonHome extends BaseController {
  public function index() {

    $this->load->language('common/home');

    $this->document->setTitle('Home');

    $data['pagina'] = $this->func->getPage(4); // ID DA PÁGINA NO ADMIN

        // echo "<pre>";
        //   print_r($data['pagina']);
        // echo "</pre>";
        // exit;


    $data['user_info'] = $this->session->data['user_info'];

    $data['titulo_dashboard'] = sprintf($this->language->get('titulo_dashboard'), $data['user_info']['nome']);

    
    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('common/home', $data));
  }
}
