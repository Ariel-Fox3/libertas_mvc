<?php

namespace Mubbi;

use DateTime;

class ControllerPaginasHome extends BaseController {
  public function index() {

    $this->load->language('common/home');

    $this->document->setTitle('Páginas');

    $data['user_info'] = $this->session->data['user_info'];

    $idpagina = isset($this->request->get['id']) && $this->request->get['id'] != '' ? $this->request->get['id'] : null;
    $data['paginas'] = $this->getList($idpagina);
    
    $data['pagina_atual'] = null;
    
    $data = array_merge($data, $this->getBreadcrumbs($idpagina));
    
    $data['url_order'] = $this->url->link('paginas/home/order');
    $data['url_desativar_pagina'] = $this->url->link('paginas/home/desativar_pagina');
    $data['url_duplicar'] = $this->url->link('paginas/home/duplicar');
    $data['add'] = $this->url->link('paginas/home/add');
    $data['edit'] = $this->url->link('paginas/home/edit');
    if (is_array($data['pagina_atual'])) $data['add'] .= '&id=' . $data['pagina_atual']['idpagina'];

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('paginas/list', $data));
  }

  public function add($data = array()) {

    $this->load->language('common/home');

    $this->document->setTitle('Adicionar página');

    $this->load->model('pagina/pagina');
    $this->load->model('pagina/bloco');

    $idpagina = null;
    if (isset($this->request->get['id']) && $this->request->get['id'] !== '') {
      $data['pai'] = $this->request->get['id'];
      $idpagina = $this->request->get['id'];
    }

    $data = array_merge($data, $this->getBreadcrumbs($idpagina));

    $data['paginas'] = $this->model_pagina_pagina->getAll(true);

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $pagina = array();

      $pagina['nome'] = $this->request->post['inputNome'];
      $pagina['externo'] = isset($this->request->post['inputLinkExterno']) && $this->request->post['inputLinkExterno'] == '1' ? $this->request->post['inputUrl'] : 0;
      $pagina['ativo'] = 1;
      if (isset($this->request->post['inputIdsublink']) && $this->request->post['inputIdsublink'] != 0) {
        $pagina['sublink'] = $this->request->post['inputIdsublink'];
      }

      $pagina['ordem'] = $this->model_pagina_pagina->getCount(isset($pagina['sublink']) ? $pagina['sublink'] : null);
      $pagina = $this->model_pagina_pagina->add($pagina);

      if (isset($this->request->post['obj']) && $this->request->post['obj'] !== '') {
        $blocos = json_decode(html_entity_decode($this->request->post['obj']), true);
        if (sizeof($blocos) > 0) {
          foreach($blocos as $key => $val) {
            $bloco = array(
              'idpagina' => $pagina['idpagina'],
              'conteudo' => $val['conteudo'],
              'chave' => $val['name']
            );
            $this->model_pagina_bloco->add($bloco);
          }
        }
      }

      $this->session->data['success'] = array('key' => 'nova_pagina');
      $this->response->redirect($this->url->link('paginas/home/edit') . '&id=' . $pagina['idpagina']);
    }

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('paginas/add', $data));
  }

  public function edit() {

    $this->load->language('common/home');

    $this->document->setTitle('Editar página');
    $this->document->addScript('js/plugins/jquery.form');
    $this->document->addStyle('css/pages/quemsomos', true);

    $this->load->model('pagina/pagina');
    $this->load->model('pagina/bloco');

    $data['user_info'] = $this->session->data['user_info'];

    $data['url_upload_arquivo'] = $this->url->link('paginas/home/arquivos');
    $data['url_arquivos_pagina'] = $this->url->link('paginas/home/arquivos');
    $data['url_arquivos_bloco'] = $this->url->link('paginas/home/arquivos_bloco');
    $data['url_remover_arquivo'] = $this->url->link('paginas/home/remover_arquivo');
    $data['url_order'] = $this->url->link('paginas/home/order_arquivos');

    $data['pagina'] = $this->model_pagina_pagina->getById($this->request->get['id']);
    $data['pagina']['blocos'] = $this->model_pagina_bloco->getAll($this->request->get['id']);

    $data['paginas'] = $this->model_pagina_pagina->getAll(true);

    $data = array_merge($data, $this->getBreadcrumbs($this->request->get['id']));

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['idpagina']) && $this->request->post['idpagina'] !== '') {
        $pagina = $this->model_pagina_pagina->getById($this->request->post['idpagina']);
  
        $pagina['nome'] = $this->request->post['inputNome'];
        $pagina['externo'] = isset($this->request->post['inputLinkExterno']) && $this->request->post['inputLinkExterno'] == '1' ? $this->request->post['inputUrl'] : 0;
        $pagina['ativo'] = 1;
        if (isset($this->request->post['inputIdsublink']) && $this->request->post['inputIdsublink'] != 0) {
          $pagina['sublink'] = $this->request->post['inputIdsublink'];
        }
  
        //$pagina['ordem'] = $this->model_pagina_pagina->getCount(isset($pagina['sublink']) ? $pagina['sublink'] : null);
        $pagina = $this->model_pagina_pagina->edit($pagina);

        $blocos_old = $this->model_pagina_bloco->getAll($pagina['idpagina']);
        $blocos_obj = array();

        if (isset($this->request->post['obj']) && $this->request->post['obj'] !== '') {
          $blocos = json_decode(html_entity_decode($this->request->post['obj']), true);
          if (sizeof($blocos) > 0) {
            foreach($blocos as $key => $val) {
              if (isset($val['idbloco_pagina']) && $val['idbloco_pagina'] !== '' && $val['idbloco_pagina'] !== 0) {
                $bloco = $this->model_pagina_bloco->getById($val['idbloco_pagina']);
                $blocos_obj[] = $bloco['idbloco_pagina'];
              } else {
                $bloco = array();
              }
              $bloco['idpagina'] = $pagina['idpagina'];
              $bloco['conteudo'] = $val['conteudo'];
              $bloco['chave'] = $val['name'];
              $this->model_pagina_bloco->save($bloco);
            }
          }
        }

        if (sizeof($blocos_old) > 0) {
          foreach($blocos_old as $key => $val) {
            if (!in_array($val['idbloco_pagina'], $blocos_obj)) {
              $this->model_pagina_bloco->delete($val['idbloco_pagina']);
            }
          }
        }
        $this->session->data['success'] = array('key' => 'editar_pagina');
        $this->response->redirect($this->url->link('paginas/home/edit') . '&id=' . $pagina['idpagina']);

      }
    }

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('paginas/edit', $data));
  }

  public function getList($idpagina = null) {
    $this->load->model('pagina/pagina');
    $this->load->model('pagina/bloco');

    $paginas = $this->model_pagina_pagina->getByIdSublink($idpagina);
    if (sizeof($paginas) > 0) {
      foreach($paginas as $key => $pagina) {       
        $pagina['subs'] = $this->model_pagina_pagina->getByIdSublink($pagina['idpagina']);
        $paginas[$key] = $pagina;
      }
    }

    return $paginas;
  }

  public function arquivos() {
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {

      $this->load->model('pagina/pagina');
      $this->load->model('pagina/bloco');
      $this->load->model('arquivo/arquivo');
      $idpagina = $this->request->get['id'];

      $data['url_remover_arquivo'] = $this->url->link('paginas/home/remover_arquivo');
      $data['pagina'] = $this->model_pagina_pagina->getById($idpagina);
      $data['arquivos'] = $this->model_arquivo_arquivo->getByIdpagina($idpagina);

      $this->document->setTitle('Arquivos de ' . $data['pagina']['nome']);

      $data = array_merge($data, $this->getBreadcrumbs($idpagina));

      if (sizeof($data['arquivos']) > 0) {
        foreach($data['arquivos'] as $key => $arquivo) {
          $data['arquivos'][$key]['original_data'] = DateTime::createFromFormat('Y-m-d H:i:s', $arquivo['data'])->format('Y-m-d');
          $data['arquivos'][$key]['data'] = DateTime::createFromFormat('Y-m-d H:i:s', $arquivo['data'])->format('d/m/Y');
          $data['arquivos'][$key]['arquivo'] = UPLOADS . $arquivo['arquivo'];
          if ($arquivo['idbloco'] !== null) {
            $data['arquivos'][$key]['bloco'] = $this->model_pagina_bloco->getById($arquivo['idbloco']);
          }
        }
      }

      $data['sidebar'] = $this->load->controller('common/sidebar');
      $data['navbar'] = $this->load->controller('common/navbar');
      $data['header'] = $this->load->controller('common/header', $data);
      $data['footer'] = $this->load->controller('common/footer', $data);
  
      $this->response->setOutput($this->load->view('paginas/arquivos', $data));

    } else if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->load->model('arquivo/arquivo');
      $capa_ext = array('jpeg', 'jpg', 'png', 'gif', 'pdf');
      $capa_size = 40000000000;
      $capa_path = UPLOADS_DIR;

      if (isset($this->request->post['idarquivo']) && $this->request->post['idarquivo'] !== '' && $this->request->post['idarquivo'] !== 0) {
        $arquivo = $this->model_arquivo_arquivo->getById($this->request->post['idarquivo']);
      } else {
        $arquivo = array();
      }

      $arquivo['nome'] = $this->request->post['inputNome'];
      $arquivo['idpagina'] = $this->request->post['idpagina'];
      if (isset($this->request->post['idbloco']) && $this->request->post['idbloco'] !== '') {
        $arquivo['idbloco'] = $this->request->post['idbloco'];
      }

      if (isset($this->request->post['descricao']) && $this->request->post['descricao'] !== '') {
        $arquivo['descricao'] = html_entity_decode($this->request->post['descricao']);
      }

      $arquivo['data'] = $this->request->post['inputData'] . ' 00:00:00';
      $arquivo['url'] = $this->request->post['inputUrl'];
      $arquivo['tipo'] = 'arquivo_pagina';
      $arquivo['joined'] = date('Y-m-d H:i:s');
      $arquivo['ativo'] = 1;

      
      if (isset($this->request->files['inputImagem']['name']) && $this->request->files['inputImagem']['size'] > 0) {
        $ext_capa = strtolower(pathinfo($this->request->files['inputImagem']['name'], PATHINFO_EXTENSION));
        if (in_array($ext_capa , $capa_ext)) {
          if ($this->request->files['inputImagem']['size'] < $capa_size) {
            $uniq = uniqid();
            $img_capa = "img".$uniq. "." . $ext_capa;
            $img_thumb = "compressed/img".$uniq. ".jpg";

            $source = $this->request->files['inputImagem']['tmp_name'];
            $destination = $capa_path . $img_capa;

            if (move_uploaded_file($source, $destination)) {
              // tudo certo com o envio da imagem, salvando arquivos
              $thumb = $this->func->compressImage($destination, $capa_path . $img_thumb, 75);
              if ($thumb) {
                $arquivo['thumbnail'] = $img_thumb;
              } else {
                if (!isset($this->request->post['modal'])) {
                  $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
                  $this->response->redirect($this->url->link('paginas/home/arquivos&id=' . $arquivo['idpagina']));
                } else {
                  $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #5 - Erro ao comprimir - ' . $this->request->files['inputImagem']['tmp_name'] . ' -> ' . $capa_path . $img_capa));
                  exit;
                }  
              }
              $arquivo['arquivo'] = $img_capa;
            } else {
              if (!isset($this->request->post['modal'])) {
                $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
                $this->response->redirect($this->url->link('paginas/home/arquivos&id=' . $arquivo['idpagina']));
              } else {
                $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #4 - Arquivo não enviado - ' . $this->request->files['inputImagem']['tmp_name'] . ' -> ' . $capa_path . $img_capa));
                exit;
              }  
            }
          } else {
            if (!isset($this->request->post['modal'])) {
              $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
              $this->response->redirect($this->url->link('paginas/home/arquivos&id=' . $arquivo['idpagina']));
            } else {
              $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #3 - Arquivo muito grande'));
              exit;
            }  
          }
        } else {
          if (!isset($this->request->post['modal'])) {
            $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
            $this->response->redirect($this->url->link('paginas/home/arquivos&id=' . $arquivo['idpagina']));
          } else {
            $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #2 - Extensão não suportada'));
            exit;
          }  
        }
      } else {
        if (!isset($this->request->post['idarquivo']) || $this->request->post['idarquivo'] == '' || $this->request->post['idarquivo'] == 0) {
          if (!isset($this->request->post['modal'])) {
            $this->session->data['error'] = array('key' => 'error_upload_arquivo_pagina');
            $this->response->redirect($this->url->link('paginas/home/arquivos&id=' . $arquivo['idpagina']));
          } else {
            $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #1'));
            exit;
          }  
        }
      }
    
      $arquivo = $this->model_arquivo_arquivo->save($arquivo);

      if (!isset($this->request->post['modal'])) {
        $this->session->data['success'] = array('key' => 'upload_arquivo_pagina');
        $this->response->redirect($this->url->link('paginas/home/arquivos&id=' . $arquivo['idpagina']));
      } else {
        $this->response->json(array('error' => false, 'msg' => $arquivo['idbloco']));
      }

      
    }
  }

  public function arquivos_bloco() {
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      $this->load->model('pagina/pagina');
      $this->load->model('arquivo/arquivo');
      $idbloco = $this->request->get['id'];
      $idpagina = $this->request->get['idpagina'];

      $this->document->setTitle('Páginas');

      $arquivos = $this->model_arquivo_arquivo->getByIdbloco($idbloco, $idpagina);

      if (sizeof($arquivos) > 0) {
        foreach($arquivos as $key => $arquivo) {
          $arquivos[$key]['arquivo'] = UPLOADS . $arquivo['arquivo'];
          $arquivos[$key]['original_data'] = DateTime::createFromFormat('Y-m-d H:i:s', $arquivo['data'])->format('Y-m-d');
          $arquivos[$key]['data'] = DateTime::createFromFormat('Y-m-d H:i:s', $arquivo['data'])->format('d/m/Y');
        }
      }

      $data['sidebar'] = $this->load->controller('common/sidebar');
      $data['navbar'] = $this->load->controller('common/navbar');
      $data['header'] = $this->load->controller('common/header', $data);
      $data['footer'] = $this->load->controller('common/footer', $data);
  
      $this->response->json(array('error' => false, 'msg' => $arquivos));
    }
  }

  public function remover_arquivo() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['id']) && $this->request->post['id'] !== '') {
        $this->load->model('arquivo/arquivo');
        $arquivo = $this->model_arquivo_arquivo->getById($this->request->post['id']);
        if($arquivo['idarquivo'] !== null && $arquivo['idarquivo'] !== '' && $arquivo['idarquivo'] !== 0) {
          $idpagina = $arquivo['idpagina'];
          $this->model_arquivo_arquivo->delete($arquivo['idarquivo']);

          if (isset($this->request->post['modal'])) {
            $this->response->json(array('error' => false));
          } else {
            $this->session->data['success'] = array('key' => 'remover_arquivo_pagina');
            $this->response->json(array('error' => false));
          }
        }
      }
    }
  }

  public function desativar_pagina() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['id']) && $this->request->post['id'] !== '') {
        $this->load->model('pagina/pagina');
        $pagina = $this->model_pagina_pagina->getById($this->request->post['id']);
        if ($pagina['ativo'] == 1) {
          $this->model_pagina_pagina->delete($this->request->post['id']);
        } else {
          $pagina['ativo'] = 1;
          $this->model_pagina_pagina->edit($pagina);
        }
        $this->response->json(array('error' => false));
      }
    }
  }

  public function order() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['str']) && $this->request->post['str'] !== '') {
        $this->load->model('pagina/pagina');
        $str = explode(',', $this->request->post['str']);
        if (sizeof($str) > 0) {
          foreach($str as $count => $id) {
            $p = $this->model_pagina_pagina->getById($id);
            $p['ordem'] = $count;
            $this->model_pagina_pagina->edit($p);
          }
        } else {
          $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #1'));
        }

        $this->response->json(array('error' => false));
      } else {
        $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #2'));
      }
    } else {
      $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #3'));
    }
  }

  public function order_arquivos() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['str']) && $this->request->post['str'] !== '') {
        $this->load->model('arquivo/arquivo');
        $str = explode(',', $this->request->post['str']);
        if (sizeof($str) > 0) {
          foreach($str as $count => $id) {
            $p = $this->model_arquivo_arquivo->getById($id);
            $p['ordem'] = $count;
            $this->model_arquivo_arquivo->edit($p);
          }
        } else {
          $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #1'));
        }

        $this->response->json(array('error' => false));
      } else {
        $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #2'));
      }
    } else {
      $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #3'));
    }
  }

  private function getBreadcrumbs($idpagina = null) {
    // breadcrumbs
    $data['breadcrumbs'] = array();

    if ($this->request->get['route'] == 'paginas/home/add') {
      $data['breadcrumbs'][] = array(
        'name' => 'Adicionar página',
        'url' => $this->url->link('paginas/home/add'),
        'active' => false
      );
    }

    if ($this->request->get['route'] == 'paginas/home/edit') {
      $data['breadcrumbs'][] = array(
        'name' => 'Editar página',
        'url' => $this->url->link('paginas/home/edit'),
        'active' => false
      );
    }

    if ($this->request->get['route'] == 'paginas/home/arquivos') {
      if (isset($idpagina)) {
        $data['breadcrumbs'][] = array(
          'name' => 'Arquivos',
          'url' => $this->url->link('paginas/home/arquivos') . '&id=' . $idpagina,
          'active' => false
        );
      }
    }

    if ($idpagina !== null) {
      $this->load->model('pagina/pagina');
      $data['pagina_atual'] = $this->model_pagina_pagina->getById($idpagina);

      $data['breadcrumbs'][] = array(
        'name' => $data['pagina_atual']['nome'],
        'url' => $this->url->link('paginas/home') . '&id=' . $idpagina,
        'active' => false
      );

      $idpai = $data['pagina_atual']['sublink'];
      while ($idpai !== null) {
        $pai = $this->model_pagina_pagina->getById($idpai);
        $data['breadcrumbs'][] = array(
          'name' => $pai['nome'],
          'url' => $this->url->link('paginas/home') . '&id=' . $idpai,
          'active' => false
        );

        $idpai = $pai['sublink'];
      }
    }


    $data['breadcrumbs'][] = array(
      'name' => 'Páginas',
      'url' => $this->url->link('paginas/home'),
      'active' => false
    );

    $data['breadcrumbs'] = array_reverse($data['breadcrumbs']);

    $data['breadcrumbs'][sizeof($data['breadcrumbs']) - 1]['active'] = true;

    return $data;
  }

  public function duplicar() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['idpagina']) && $this->request->post['idpagina'] !== '') {
        $this->load->model('pagina/pagina');
        $this->load->model('pagina/bloco');
        $this->load->model('arquivo/arquivo');
        $pagina = $this->func->getPage($this->request->post['idpagina'], false);
        $new_pagina = $pagina['pagina'];
        unset($new_pagina['idpagina']);
        $new_pagina['nome'] = $new_pagina['nome'] . ' Cópia';
        $new_pagina = $this->model_pagina_pagina->add($new_pagina);
        $imagens = $this->model_arquivo_arquivo->getByIdpagina($this->request->post['idpagina']);

        if (sizeof($imagens) > 0) {
          foreach($imagens as $i => $imagem) {
            $new_imagem = $imagem;
            unset($imagem['idarquivo']);
            $new_imagem['idpagina'] = $new_pagina['idpagina'];
          }
        }

        if (sizeof($pagina['blocos']) > 0) {
          foreach($pagina['blocos'] as $i => $bloco) {
            $new_bloco = $bloco;
            unset($new_bloco['idbloco_pagina']);
            $arquivos = $new_bloco['arquivos'];
            unset($new_bloco['arquivos']);

            $new_bloco['idpagina'] = $new_pagina['idpagina'];
            $new_bloco = $this->model_pagina_bloco->add($new_bloco);

            if (sizeof($arquivos) > 0) {
              foreach($arquivos as $a => $arquivo) {
                $new_arquivo = $arquivo;
                unset($new_arquivo['idarquivo']);
                $new_arquivo['idpagina'] = $new_pagina['idpagina'];
                $new_arquivo['idbloco'] = $new_bloco['idbloco_pagina'];

                $new_arquivo = $this->model_arquivo_arquivo->add($new_arquivo);
              }
            }
          }
        }

        
        
        $this->response->json(array('error' => false));
        
      } else {
        $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #2'));
      }
    } 
  }
  
}
