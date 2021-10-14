<?php

namespace Mubbi;

use DateTime;

class ControllerNoticiasHome extends BaseController {
  public function index() {

    $this->load->language('common/home');

    $this->document->setTitle('Notícias');

    $data['user_info'] = $this->session->data['user_info'];
    $data['noticias'] = $this->getList();
    
    
    $data['url_order'] = $this->url->link('paginas/home/order');
    $data['url_desativar_pagina'] = $this->url->link('paginas/home/desativar_pagina');
    $data['add'] = $this->url->link('noticias/home/add');
    $data['edit'] = $this->url->link('noticias/home/edit');

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('noticias/list', $data));
  }

  public function getList() {
    $this->load->model('noticia/noticia');
    $noticias = $this->model_noticia_noticia->getAll(true);
    return $noticias;
  }

  public function add() {
    $this->load->language('common/home');

    $this->document->setTitle('Adicionar notícia');
    $this->document->addScript('js/plugins/fox.pageBuilder');
    $this->document->addStyle('js/plugins/fox.pageBuilder');
    $this->document->addStyle('css/fontawesome_pro');

    $data['user_info'] = $this->session->data['user_info'];
    
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->load->model('noticia/noticia');
      echo "<pre>";
        print_r($this->request->post);
      echo "</pre>";
      
      $capa_ext = array('jpeg', 'jpg', 'png', 'gif', 'mp3', 'mpeg', 'wma');
      $capa_path = UPLOADS_DIR . 'noticias/';
      $capa_size = 999999;

      
      $noticia = array();
      $noticia['nome'] = $this->request->post['inputNome'];
      $noticia['tag'] = $this->request->post['inputTag'];
      $noticia['autores'] = $this->request->post['inputAutores'];
      $noticia['ativo'] = '1';
      $d = DateTime::createFromFormat('Y-m-d', $this->request->post['inputData']);
      $noticia['data'] = $d->format('Y-m-d');

      if(isset($this->request->files['inputBanner']['name'])) {
        $ext_capa = strtolower(pathinfo($this->request->files['inputBanner']['name'], PATHINFO_EXTENSION));
        // if (in_array($ext_capa , $capa_ext)) {
          if ($this->request->files['inputBanner']['size'] < $capa_size) {
            $img_capa = "img_banner_".$this->func->getStringLink($noticia['nome']).'_'.time() . "." . $ext_capa;
            if (move_uploaded_file($this->request->files['inputBanner']['tmp_name'], $capa_path . $img_capa)) { 				
              $noticia['banner'] = $img_capa;
            }
          }
        // }
      }

      if(isset($this->request->files['inputImagem']['name'])) {
        $ext_capa = strtolower(pathinfo($this->request->files['inputImagem']['name'], PATHINFO_EXTENSION));
        // if (in_array($ext_capa , $capa_ext)) {
          if ($this->request->files['inputImagem']['size'] < $capa_size) {
            $img_capa = "img_imagem_".$this->func->getStringLink($noticia['nome']).'_'.time() . "." . $ext_capa;
            if (move_uploaded_file($this->request->files['inputImagem']['tmp_name'], $capa_path . $img_capa)) { 				
              $noticia['imagem'] = $img_capa;
            }
          }
        // }
      }

      $obj = json_decode(html_entity_decode($this->request->post['obj']), true);
      $new_obj = array();
      if (sizeof($obj) > 0) {
        foreach ($obj as $i => $item) {
          $tmp = $item;
          if ($item['type'] == 'image') {
            if (strpos($item['val'], 'http') === false) {
              $output_file = "img_noticia_".$item['id'] . ".jpg";
              $data = explode(',', $item['val']);
              if (isset($data[1])) {
                $ifp = fopen($capa_path . $output_file, 'wb'); 
                fwrite($ifp, base64_decode($data[ 1 ]));
                fclose($ifp); 

              }
              $tmp['val'] = $output_file;  
            }
          } else if ($item['type'] == 'carousel') {
            $new_files = array(); $changed = false;
            $files = $item['val'];
            if (sizeof($files) > 0) {
              foreach($files as $key => $file) {
                if (strpos($file, 'http') === false) {
                  $changed = true;
                  $output_file = "img_noticia_".$item['id'] . '_' . $key .  ".jpg";
                  $data = explode(',', $file);
                  if (isset($data[1])) {
                    $ifp = fopen($capa_path . $output_file, 'wb'); 
                    fwrite($ifp, base64_decode($data[ 1 ]));
                    fclose($ifp); 
          
                  }
                  $new_files[] = UPLOADS . 'noticias/' . $output_file;  
                }
                
              }
            }
            if ($changed === true) $tmp['val'] = $new_files;
          }


          $new_obj[] = $tmp;
        }
      }

      $noticia['obj'] = json_encode($new_obj);
      $noticia = $this->model_noticia_noticia->save($noticia);

      $this->session->data['success'] = array('key' => 'nova_noticia');
      $this->response->redirect($this->url->link('noticias/home/edit') . '&id=' . $noticia['idnoticia']);
    }

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('noticias/add', $data));
  }

  public function edit() {
    $this->load->language('common/home');

    $this->document->setTitle('Editar notícia');
    $this->document->addScript('js/plugins/fox.pageBuilder');
    $this->document->addStyle('js/plugins/fox.pageBuilder');
    $this->document->addStyle('css/fontawesome_pro');

    $data['user_info'] = $this->session->data['user_info'];

    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      $this->load->model('noticia/noticia');
      if (isset($this->request->get['id']) && $this->request->get['id'] !== '') {
        $data['noticia'] = $this->model_noticia_noticia->getById($this->request->get['id']);
        $data['noticia']['autores'] = html_entity_decode($data['noticia']['autores']);
        $data['noticia']['obj'] = html_entity_decode($data['noticia']['obj']);
        $data['noticia']['banner'] = $data['noticia']['banner'] !== '' ? UPLOADS_DIR . 'noticias/' . $data['noticia']['banner'] : '';
        $data['noticia']['imagem'] = $data['noticia']['imagem'] !== '' ? UPLOADS_DIR . 'noticias/' . $data['noticia']['imagem'] : '';
      }
    }
    
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->load->model('noticia/noticia');
      
      $capa_ext = array('jpeg', 'jpg', 'png', 'gif', 'mp3', 'mpeg', 'wma');
      $capa_path = UPLOADS_DIR . 'noticias/';
      $capa_size = 9999999999999;

      
      $idnoticia = $this->request->post['idnoticia'];
      $noticia = $this->model_noticia_noticia->getById($idnoticia);
      $noticia['nome'] = $this->request->post['inputNome'];
      $noticia['tag'] = $this->request->post['inputTag'];
      $noticia['autores'] = $this->request->post['inputAutores'];
      $noticia['ativo'] = '1';
      $d = DateTime::createFromFormat('Y-m-d', $this->request->post['inputData']);
      $noticia['data'] = $d->format('Y-m-d');


      if(isset($this->request->files['inputBanner']['name'])) {
        $ext_capa = strtolower(pathinfo($this->request->files['inputBanner']['name'], PATHINFO_EXTENSION));
        // if (in_array($ext_capa , $capa_ext)) {
          if ($this->request->files['inputBanner']['size'] < $capa_size) {
            $img_capa = "img_banner_".$this->func->getStringLink($noticia['nome']).'_'.time() . "." . $ext_capa;
            if (move_uploaded_file($this->request->files['inputBanner']['tmp_name'], $capa_path . $img_capa)) { 				
              $noticia['banner'] = $img_capa;
            }
          }
        // }
      }

      if(isset($this->request->files['inputImagem']['name'])) {
        $ext_capa = strtolower(pathinfo($this->request->files['inputImagem']['name'], PATHINFO_EXTENSION));
        // if (in_array($ext_capa , $capa_ext)) {
          if ($this->request->files['inputImagem']['size'] < $capa_size) {
            $img_capa = "img_imagem_".$this->func->getStringLink($noticia['nome']).'_'.time() . "." . $ext_capa;
            if (move_uploaded_file($this->request->files['inputImagem']['tmp_name'], $capa_path . $img_capa)) { 				
              $noticia['imagem'] = $img_capa;
            }
          }
        // }
      }

      $obj = json_decode(html_entity_decode($this->request->post['obj']), true);
      $new_obj = array();
      if (sizeof($obj) > 0) {
        foreach ($obj as $i => $item) {
          $tmp = $item;
          if ($item['type'] == 'image') {
            if (strpos($item['val'], 'http') === false) {
              $output_file = "img_noticia_".$item['id'] . ".jpg";
              $data = explode(',', $item['val']);
              if (isset($data[1])) {
                $ifp = fopen($capa_path . $output_file, 'wb'); 
                fwrite($ifp, base64_decode($data[ 1 ]));
                fclose($ifp); 

              }
              $tmp['val'] = UPLOADS . 'noticias/' . $output_file;  
            }
          } else if ($item['type'] == 'carousel') {
            $new_files = array(); $changed = false;
            $files = $item['val'];
            if (sizeof($files) > 0) {
              foreach($files as $key => $file) {
                if (strpos($file, 'http') === false) {
                  $changed = true;
                  $output_file = "img_noticia_".$item['id'] . '_' . $key .  ".jpg";
                  $data = explode(',', $file);
                  if (isset($data[1])) {
                    $ifp = fopen($capa_path . $output_file, 'wb'); 
                    fwrite($ifp, base64_decode($data[ 1 ]));
                    fclose($ifp); 
          
                  }
                  $new_files[] = UPLOADS . 'noticias/' . $output_file;  
                }
                
              }
            }
            if ($changed === true) $tmp['val'] = $new_files;
          }


          $new_obj[] = $tmp;
        }
      }

      $noticia['obj'] = json_encode($new_obj);
      $noticia = $this->model_noticia_noticia->save($noticia);

      $this->session->data['success'] = array('key' => 'nova_noticia');
      $this->response->redirect($this->url->link('noticias/home/edit') . '&id=' . $noticia['idnoticia']);
    }

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('noticias/edit', $data));
  }

}
?>