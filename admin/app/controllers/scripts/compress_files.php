<?php

namespace Mubbi;

use DateTime;

class ControllerScriptsCompress_files extends BaseController {
  public function index() {
    if (isset($this->request->get['pass']) && $this->request->get['pass'] == 'fox') {

      $this->load->model('arquivo/arquivo');
      $arquivos = $this->model_arquivo_arquivo->getAll();

      echo "<pre>";
      
      echo "Total de arquivos: " . sizeof($arquivos) . "\n\n";
      if (sizeof($arquivos) > 0) {
        foreach($arquivos as $key => $arquivo) {
          if ($arquivo['arquivo'] !== '') {
            $source = UPLOADS . $arquivo['arquivo'];
            $destination = UPLOADS . 'compressed/' . $arquivo['arquivo'];
            echo "Comprimir arquivo " . $arquivo['nome'] . ' - ' . $source . ' -> ' . $destination . ' : ';
            $compress = $this->func->compressImage($source, $destination, 75);
            echo $compress ? 'true' : 'false';
            echo "\n\n";
          }

        }
      }

      echo "</pre>";
      

    }
  }
}

?>