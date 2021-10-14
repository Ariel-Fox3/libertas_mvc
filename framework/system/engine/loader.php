<?php
  namespace Mubbi;

  use Exception;
  // use Twig_Filter;
  use Twig\Loader\FilesystemLoader;

  class Loader {
    private $default = array();
    private $registry = NULL;
    private $document = NULL;
    private $user = NULL;
    
    public function __construct($default = array(), $base) {
      $this->default = $default;
      $this->registry = $base;
      $this->document = $this->registry->get('document');
      $this->user = $this->registry->get('user');
    }
    
    public function view($view, $params = []) {
      $restricted = array(
        'atividades/home', 
        'reuniao/home', 
        'cronograma/home', 
        'cronograma/andamento', 
        'cronograma/home', 
        'cronograma/andamento', 
        'jobs/home', 
        'admin_atividade/home', 
        'settings/cliente', 
        'settings/contrato', 
        'settings/usuario', 
        'config/home', 
        'admin_atividade/home', 
        'settings/cliente', 
        'settings/contrato', 
        'settings/usuario', 
        'config/home', 
        'relatorio/clientes', 
        'relatorio/cronogramas', 
        'relatorio/clientes', 
        'relatorio/cronogramas', 
      );

      $permissions = $this->user->getPermissions();
      if ($permissions !== null) {
        if (in_array($view, $restricted)) {
          if ($permissions[$view]['access'] === false) {
            $view = 'common/no_permission';
          }
        }
      }

      $hasModifyPermissions = false;
      if (isset($permissions[$view]) && $permissions[$view]['modify'] === true) {
        $hasModifyPermissions = true;
      }

      // Check if view exists
      if (!file_exists(VIEWS . $view.'.twig')) {
        die('View: ' . $view . ' file not found');
      }
      // Get Variables
      if (is_array($params) && count($params)) {
        extract($params);
      } else {
        $params = array();
      }

      if (!isset($params['heading_title'])) $params['heading_title'] = $this->document->getTitle() !== null ? $this->document->getTitle() : 'heading_title';
      if (isset($hasModifyPermissions)) {
        $params['modify_p'] = $hasModifyPermissions;
      }

      if (sizeof($this->default) > 0) {
        $params = array_merge($params, $this->default);
      }

      if (sizeof($this->registry->data) > 0) {
        foreach ($this->registry->data as $key => $val) {
          $params[$key] = $val;
        }
      }
      
      // Swap template engine based on user config
      if (USE_TWIG) {
        $loader = new \Twig\Loader\FilesystemLoader(VIEWS);
        
        $twig = new \Twig\Environment($loader, array(
          //'cache' => CACHE,
          'cache' => false,
          'debug' => true,
          'auto_reload' => true,
          'strict_variables' => true
        ));

        $f = $this->registry->get('custom_functions');
        foreach ($f as $key => $value) {
          $name = str_replace('twig_', '', $key);
          
          $filter = new \Twig\TwigFilter($name, $value);
          $twig->addFilter($filter);
        }
        
        $html = $twig->render($view.'.twig', $params);
        return htmlspecialchars_decode($html);
      } else {
        // Load View
        include_once VIEWS . $view.'.twig';
      }
    }
  
    public function controller($controller, $params = null) {
      $parts = explode('/', $controller);

      // Check if controller exists
      if (!file_exists(CONTROLLER . strtolower($parts[0]) . '/' . strtolower($parts[1]) .'.php')) {
        die('CONTROLLER: ' . strtolower($parts[0]) . '/' . strtolower($parts[1]) . ' file not found');
      }
      // Load Controller
      include_once CONTROLLER . strtolower($parts[0]) . '/' . strtolower($parts[1]) . '.php';
      // Return Controller object
      $class = 'Mubbi\\Controller' . ucfirst($parts[0]) . ucfirst($parts[1]);
            
      $c = new $class($this->registry);

      if (isset($parts[2]) && $parts[2] !== '') {
        $f = $parts[2];
        return ($c->$f($params));
      } else {
        return ($c->index($params));
      }
    }

    public function model($model) {
      // Check if model exists
      if (!file_exists(MODELS . $model.'.php')) {
        die('MODEL: ' . $model . ' file not found');
      }
      // Load Model
      include_once MODELS . $model.'.php';
      // Return Model object
      $parts = explode('/', $model);
      $class = 'Mubbi\\Model' . ucfirst($parts[0]) . ucfirst($parts[1]);
      $str = 'model_' . strtolower($parts[0]) . '_' . strtolower($parts[1]);
      $class = new $class($this->registry);
      $this->registry->set($str, $class);
    }

    public function language($route, $key = '') {
      $language = $this->registry->get('language');
      $d =  $language->load($route, $key);
      
      if ($key !== '') {
        return $d;
      } else {
        if (sizeof($d) > 0) {
          foreach ($d as $key => $val) {
            $this->registry->set($key, $val);
          }
        }
      }
    }
  
  }
?>