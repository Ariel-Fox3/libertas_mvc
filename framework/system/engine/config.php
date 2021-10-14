<?php
  namespace Mubbi;

  class Config {
    private $parent = NULL;
    private $configs = array();

    public function __construct($parent) {
      if (sizeof(CFG) > 0) {
        foreach (CFG as $key => $val) {
          $this->configs[$key] = $val;
        }
      }
    }

    public function get($key) {
      return $this->configs[$key];
    }
  }

?>