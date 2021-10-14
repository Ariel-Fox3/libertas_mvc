<?php
  namespace Mubbi;

  class Url {
    private $link = NULL;

    public function link($route) {
      if (USE_SSL == true) {
        $this->link = HTTPS_SERVER . 'index.php?route=' . $route;
      } else {
        $this->link = HTTP_SERVER . 'index.php?route=' . $route;
      }
      return $this->link;
    }

    public function file($route) {
      
    }
  }
?>