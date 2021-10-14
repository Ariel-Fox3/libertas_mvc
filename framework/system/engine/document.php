<?php
  namespace Mubbi;

  class Document {
    private $title = NULL;
    private $content = NULL;
    private $url = NULL;
    private $registry = array();

    public function __construct($parent) {
      $this->registry = $parent;  
    }

    public function getTitle(){
      return $this->title;
    }
  
    public function setTitle($title){
      $this->title = $title;
      $this->registry->set('heading_title', $title);
    }

    
    public function addStyle($stylesheet, $front_end_file = false){
      @$styles = $this->registry->get('custom_styles');
      if (!isset($styles)) $styles = array();
      if ($front_end_file == true) $stylesheet = 'PARENT::' . $stylesheet;
      $styles[] = $stylesheet;
      $this->registry->set('custom_styles', $styles);
    }

    public function addScript($script){
      @$scripts = $this->registry->get('custom_scripts');
      if (!isset($scripts)) $scripts = array();
      $scripts[] = $script;
      $this->registry->set('custom_scripts', $scripts);
    }
  
    public function getContent(){
      return $this->content;
    }
  
    public function setContent($content){
      $this->content = $content;
    }
  
    public function getUrl(){
      return $this->url;
    }
  
    public function setUrl($url){
      $this->url = $url;
    }

    // public function render() {
    //   $params = array(
    //     'heading_title' => $this->getTitle()
    //   );
  
    //   // Swap template engine based on user config
    //   if (USE_TWIG) {
    //     $loader = new Twig_Loader_Array([
    //       'Temp_File.html' => $this->getContent(),
    //     ]);
    //     $twig = new Twig_Environment($loader);
        
    //     echo $twig->render('Temp_File.html', $params);
    //   } else {
    //     // Load View
    //     include VIEWS . $view.'.twig';
    //   }
    // }
  }
?>