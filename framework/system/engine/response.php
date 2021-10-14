<?php
  namespace Mubbi;

  use Twig_Environment;
  use Twig_Loader_Array;
  use Twig_Filter;

  /**
  * Response class
  */
  class Response {
    private $headers = array();
    private $level = 0;
    private $output;
    private $registry = array();

    public function __construct($parent){
      $this->registry = $parent;
    } 

    /**
     * Constructor
     *
     * @param	string	$header
     *
    */
    public function addHeader($header) {
      $this->headers[] = $header;
    }

    /**
     *
     *
     * @param	string	$url
     * @param	int		$status
     *
    */
    public function redirect($url, $status = 302) {
      header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url), true, $status);
      exit();
    }

    /**
     *
     *
     * @param	int		$level
    */
    public function setCompression($level) {
      $this->level = $level;
    }

    /**
     *
     *
     * @return	array
    */
    public function getOutput() {
      return $this->output;
    }

    /**
     *
     *
     * @param	string	$output
    */
    public function setOutput($output) {
      $this->output = $output;
      $document = $this->registry->get('document');
      $document->setContent($this->output);
      $this->output();
    }

    /**
     *
     *
     * @param	string	$data
     * @param	int		$level
     * 
     * @return	string
    */
    private function compress($data, $level = 0) {
      if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
        $encoding = 'gzip';
      }

      if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
        $encoding = 'x-gzip';
      }

      if (!isset($encoding) || ($level < -1 || $level > 9)) {
        return $data;
      }

      if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
        return $data;
      }

      if (headers_sent()) {
        return $data;
      }

      if (connection_status()) {
        return $data;
      }

      $this->addHeader('Content-Encoding: ' . $encoding);

      return gzencode($data, (int)$level);
    }

    
    /**
     *
    */
    public function output() {
      if ($this->output) {
        $document = $this->registry->get('document');

        $output = $this->level ? $this->compress($this->output, $this->level) : $this->output;

        if (!headers_sent()) {
          foreach ($this->headers as $header) {
            header($header, true);
          }
        }

        $params = array(
          'heading_title' => $document->getTitle() !== null && $document->getTitle() !== '' ? $document->getTitle() : 'heading_title'
        );
    
        // Swap template engine based on user config
        if (USE_TWIG) {
          $loader = new \Twig\Loader\ArrayLoader([
            'front' => $document->getContent(),
          ]);
          $twig = new \Twig\Environment($loader, array(
            'cache' => false,
            //'cache' => CACHE
          ));

          $f = $this->registry->get('custom_functions');    
          foreach ($f as $key => $value) {
            $name = str_replace('twig_', '', $key);
            $filter = new \Twig\TwigFilter($name, $value);
            $twig->addFilter($filter);
          }
          
          $res = $twig->render('front', $params);
          $res = str_replace('&#039;', '\'', $res);
          echo $res;
        } else {
          echo $output;
        }
      }
    }

    public function json(array $array) {
      header('Content-type: application/json');
      echo json_encode($array);
    }
  }
