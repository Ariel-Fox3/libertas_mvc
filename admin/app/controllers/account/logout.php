<?php

namespace Mubbi;

class ControllerAccountLogout extends BaseController {
  public function index() {

    if ($this->user->isLogged()) {
      unset($this->session->data['idusuario']);
      $this->user->logout();

      // Cleanup
			$this->session->destroy();
			$this->session->forget();

    }
    $this->response->redirect($this->url->link('account/login'));

  }
}
