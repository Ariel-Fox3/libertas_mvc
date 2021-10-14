<?php

namespace Mubbi;

use DateTime;

class ControllerProdutosHome extends BaseController {
  public function index() {

    $this->load->language('common/home');

    $this->document->setTitle('Produtos');

    $data['user_info'] = $this->session->data['user_info'];

    $iddepartamento = isset($this->request->get['id']) && $this->request->get['id'] != '' ? $this->request->get['id'] : null;
    $data = array_merge($data, $this->getBreadcrumbs($iddepartamento));

    $data['produtos'] = $this->getList($iddepartamento);
    $data['pagina_atual'] = null;
    
    $data['url_order'] = $this->url->link('produtos/home/order');
    $data['url_duplicar'] = $this->url->link('produtos/home/duplicar');
    $data['url_desativar_pagina'] = $this->url->link('produtos/home/desativar_pagina');
    $data['add'] = $this->url->link('produtos/home/add');
    $data['add_departamento'] = $this->url->link('produtos/home/add_departamento');
    $data['edit'] = $this->url->link('produtos/home/edit');
    $data['edit_departamento'] = $this->url->link('produtos/home/edit_departamento');
    if (isset($data['selected_departamento'])) $data['add'] .= '&id=' . $data['selected_departamento']['iddepartamento'];

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('produtos/list', $data));
  }

  public function add($data = array()) {

    $this->load->language('common/home');

    $this->document->setTitle('Adicionar produto');

    $this->load->model('produto/departamento');
    $this->load->model('produto/produto');
    $this->load->model('produto/bloco');

    $iddepartamento = null;
    if (isset($this->request->get['id']) && $this->request->get['id'] !== '') {
      $data['pai'] = $this->request->get['id'];
      $iddepartamento = $this->request->get['id'];
    }

    $data = array_merge($data, $this->getBreadcrumbs($iddepartamento));

    $data['departamentos'] = $this->model_produto_departamento->getAll();

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $produto = array();

      $produto['nome'] = $this->request->post['inputNome'];
      $produto['marca'] = $this->request->post['marca'];
      $produto['ativo'] = 1;
      $produto['iddepartamento'] = $this->request->post['inputIddepartamento'];

      $produto['ordem'] = $this->model_produto_produto->getCount($produto['iddepartamento']);
      $produto = $this->model_produto_produto->add($produto);

      if (isset($this->request->post['obj']) && $this->request->post['obj'] !== '') {
        $blocos = json_decode(html_entity_decode($this->request->post['obj']), true);
        if (sizeof($blocos) > 0) {
          foreach($blocos as $key => $val) {
            $bloco = array(
              'idproduto' => $produto['idproduto'],
              'conteudo' => $val['conteudo'],
              'chave' => $val['name']
            );
            $this->model_produto_bloco->add($bloco);
          }
        }
      }

      $this->session->data['success'] = array('key' => 'novo_produto');
      $this->response->redirect($this->url->link('produtos/home/edit') . '&id=' . $produto['idproduto']);
    }

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('produtos/add', $data));
  }

  public function add_departamento($data = array()) {

    $this->load->language('common/home');
    $this->load->model('produto/departamento');

    $this->document->setTitle('Adicionar departamento');

    $data = array_merge($data, $this->getBreadcrumbs());

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $departamento = array();

      $departamento['nome'] = $this->request->post['inputNome'];
      $departamento['ativo'] = 1;
      $departamento['ordem'] = $this->model_produto_departamento->getCount();

      if (isset($this->request->files['inputImagem']['name']) && $this->request->files['inputImagem']['size'] > 0) {
        $capa_ext = array('jpeg', 'jpg', 'png', 'gif', 'pdf');
        $capa_size = 40000000000;
        $capa_path = UPLOADS;

        $arquivo = array();

        $ext_imagem = strtolower(pathinfo($this->request->files['inputImagem']['name'], PATHINFO_EXTENSION));

        if (in_array($ext_imagem , $capa_ext)) {
          if ($this->request->files['inputImagem']['size'] < $capa_size) {
            $uniq = uniqid();
            $img_capa = "img" . $uniq . "." . $ext_imagem;
            $img_thumb = "compressed/img".$uniq. ".jpg";

            $source = $this->request->files['inputImagem']['tmp_name'];
            $destination = $capa_path . $img_capa;

            if (move_uploaded_file($source, $destination)) {
              $thumb = $this->func->compressImage($destination, $capa_path . $img_thumb, 75);
              if ($thumb) {
                $arquivo['thumbnail'] = $img_thumb;
              } else {
                $data['error'] = array('key' => 'error_upload_arquivo_pagina');  
              }
              $arquivo['arquivo'] = $img_capa;

              $departamento['imagem'] = json_encode($arquivo);
            } else {
              $data['error'] = array('key' => 'error_upload_arquivo_pagina');    
            }
          } else {
            $data['error'] = array('key' => 'error_upload_arquivo_pagina');
          }
        } else {
          $data['error'] = array('key' => 'error_upload_arquivo_pagina');
        }
      }

      if (!isset($data['error'])) {
        $departamento = $this->model_produto_departamento->add($departamento);
        $this->session->data['success'] = array('key' => 'novo_departamento');
        $this->response->redirect($this->url->link('produtos/home'));
      }

      
    }

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('produtos/add_departamento', $data));
  }

  public function edit() {

    $this->load->language('common/home');

    $this->document->setTitle('Editar produto');
    $this->document->addScript('js/plugins/jquery.form');

    $this->load->model('produto/departamento');
    $this->load->model('produto/produto');
    $this->load->model('produto/bloco');

    $data['user_info'] = $this->session->data['user_info'];

    $data['url_upload_arquivo'] = $this->url->link('produtos/home/arquivos');
    $data['url_arquivos_produto'] = $this->url->link('produtos/home/arquivos');
    $data['url_arquivos_bloco'] = $this->url->link('produtos/home/arquivos_bloco');
    $data['url_remover_arquivo'] = $this->url->link('produtos/home/remover_arquivo');
    $data['url_order'] = $this->url->link('produtos/home/order_arquivos');

    $data['produto'] = $this->model_produto_produto->getById($this->request->get['id']);
    $data['produto']['blocos'] = $this->model_produto_bloco->getAll($this->request->get['id']);

    $data['departamentos'] = $this->model_produto_departamento->getAll();

    $data = array_merge($data, $this->getBreadcrumbs(null, $this->request->get['id']));

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['idproduto']) && $this->request->post['idproduto'] !== '') {
        $produto = $this->model_produto_produto->getById($this->request->post['idproduto']);
  
        $produto['nome'] = $this->request->post['inputNome'];
        $produto['marca'] = $this->request->post['marca'];
        $produto['ativo'] = 1;
        $produto['iddepartamento'] = $this->request->post['inputIddepartamento'];
        $produto = $this->model_produto_produto->edit($produto);

        $blocos_old = $this->model_produto_bloco->getAll($produto['idproduto']);
        $blocos_obj = array();

        if (isset($this->request->post['obj']) && $this->request->post['obj'] !== '') {
          $blocos = json_decode(html_entity_decode($this->request->post['obj']), true);
          if (sizeof($blocos) > 0) {
            foreach($blocos as $key => $val) {
              if (isset($val['idbloco_produto']) && $val['idbloco_produto'] !== '' && $val['idbloco_produto'] !== 0) {
                $bloco = $this->model_produto_bloco->getById($val['idbloco_produto']);
                $blocos_obj[] = $bloco['idbloco_produto'];
              } else {
                $bloco = array();
              }
              $bloco['idproduto'] = $produto['idproduto'];
              $bloco['conteudo'] = $val['conteudo'];
              $bloco['chave'] = $val['name'];
              $bloco = $this->model_produto_bloco->save($bloco);
            }
          }
        }

        if (sizeof($blocos_old) > 0) {
          foreach($blocos_old as $key => $val) {
            if (!in_array($val['idbloco_produto'], $blocos_obj)) {
              $this->model_produto_bloco->delete($val['idbloco_produto']);
            }
          }
        }
        $this->session->data['success'] = array('key' => 'editar_produto');
        $this->response->redirect($this->url->link('produtos/home/edit') . '&id=' . $produto['idproduto']);

      }
    }

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('produtos/edit', $data));
  }

  public function edit_departamento($data = array()) {

    $this->load->language('common/home');
    $this->load->model('produto/departamento');
    
    $this->document->setTitle('Editar departamento');
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      $data['url_remover_imagem'] = $this->url->link('produtos/home/remover_imagem') . '&id=' . $this->request->get['id'];

      if (isset($this->request->get['id']) && $this->request->get['id'] !== '') {
        $data = array_merge($data, $this->getBreadcrumbs($this->request->get['id']));

        $data['departamento'] = $this->model_produto_departamento->getById($this->request->get['id']);
        $this->document->setTitle('Editar ' . $data['departamento']['nome']);
        if ($data['departamento']['imagem'] !== null && $data['departamento']['imagem'] !== '') {
          $imagens = json_decode($data['departamento']['imagem'], true);
          $data['departamento']['imagem'] = UPLOADS . $imagens['thumbnail'];
        }
      }
    }

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $departamento = $this->model_produto_departamento->getById($this->request->post['iddepartamento']);

      $departamento['nome'] = $this->request->post['inputNome'];
      $departamento['ativo'] = 1;

      if (isset($this->request->files['inputImagem']['name']) && $this->request->files['inputImagem']['size'] > 0) {
        $capa_ext = array('jpeg', 'jpg', 'png', 'gif', 'pdf');
        $capa_size = 40000000000;
        $capa_path = UPLOADS;

        $arquivo = array();

        $ext_imagem = strtolower(pathinfo($this->request->files['inputImagem']['name'], PATHINFO_EXTENSION));

        if (in_array($ext_imagem , $capa_ext)) {
          if ($this->request->files['inputImagem']['size'] < $capa_size) {
            $uniq = uniqid();
            $img_capa = "img" . $uniq . "." . $ext_imagem;
            $img_thumb = "compressed/img".$uniq. ".jpg";

            $source = $this->request->files['inputImagem']['tmp_name'];
            $destination = $capa_path . $img_capa;

            if (move_uploaded_file($source, $destination)) {
              $thumb = $this->func->compressImage($destination, $capa_path . $img_thumb, 75);
              if ($thumb) {
                $arquivo['thumbnail'] = $img_thumb;
              } else {
                $data['error'] = array('key' => 'error_upload_arquivo_pagina');  
              }
              $arquivo['arquivo'] = $img_capa;

              $departamento['imagem'] = json_encode($arquivo);
            } else {
              $data['error'] = array('key' => 'error_upload_arquivo_pagina');    
            }
          } else {
            $data['error'] = array('key' => 'error_upload_arquivo_pagina');
          }
        } else {
          $data['error'] = array('key' => 'error_upload_arquivo_pagina');
        }
      }

      if (!isset($data['error'])) {
        $departamento = $this->model_produto_departamento->edit($departamento);
        $this->session->data['success'] = array('key' => 'editar_departamento');
        $this->response->redirect($this->url->link('produtos/home'));
      }

      
    }

    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('produtos/edit_departamento', $data));
  }

  public function getList($iddepartamento = null) {
    $this->load->model('produto/departamento');
    $this->load->model('produto/produto');

    $ret = array();
    if ($iddepartamento !== null) {
      $ret = $this->model_produto_produto->getByIddepartamento($iddepartamento);
    } else {
      $ret = $this->model_produto_departamento->getAll();
      if (sizeof($ret) > 0) {
        foreach($ret as $key => $departamento) {
          $ret[$key]['count_produtos'] = $this->model_produto_produto->getCount($departamento['iddepartamento']);
        }
      }
    }

    return $ret;
  }

  public function arquivos() {
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {

      $this->load->model('produto/produto');
      $this->load->model('produto/bloco');
      $this->load->model('arquivo/arquivo');
      $idproduto = $this->request->get['id'];

      $data['url_order'] = $this->url->link('produtos/home/order_arquivos');      
      $data['url_remover_arquivo'] = $this->url->link('produtos/home/remover_arquivo');
      $data['produto'] = $this->model_produto_produto->getById($idproduto);
      $data['arquivos'] = $this->model_arquivo_arquivo->getByIdproduto($idproduto);

      $this->document->setTitle('Arquivos de ' . $data['produto']['nome']);

      $data = array_merge($data, $this->getBreadcrumbs(null, $idproduto));

      
      if (sizeof($data['arquivos']) > 0) {
        foreach($data['arquivos'] as $key => $arquivo) {
          $data['arquivos'][$key]['original_data'] = DateTime::createFromFormat('Y-m-d H:i:s', $arquivo['data'])->format('Y-m-d');
          $data['arquivos'][$key]['data'] = DateTime::createFromFormat('Y-m-d H:i:s', $arquivo['data'])->format('d/m/Y');
          $data['arquivos'][$key]['arquivo'] = UPLOADS . $arquivo['arquivo'];
          if ($arquivo['idbloco'] !== null) {
            $data['arquivos'][$key]['bloco'] = $this->model_produto_bloco->getById($arquivo['idbloco']);
          }
        }
      }

      $data['sidebar'] = $this->load->controller('common/sidebar');
      $data['navbar'] = $this->load->controller('common/navbar');
      $data['header'] = $this->load->controller('common/header', $data);
      $data['footer'] = $this->load->controller('common/footer', $data);
  
      $this->response->setOutput($this->load->view('produtos/arquivos', $data));

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
      $arquivo['idproduto'] = $this->request->post['idproduto'];
      if (isset($this->request->post['idbloco']) && $this->request->post['idbloco'] !== '') {
        $arquivo['idbloco'] = $this->request->post['idbloco'];
      }

      if (isset($this->request->post['descricao']) && $this->request->post['descricao'] !== '') {
        $arquivo['descricao'] = html_entity_decode($this->request->post['descricao']);
      }

      $arquivo['data'] = $this->request->post['inputData'] . ' 00:00:00';
      $arquivo['url'] = $this->request->post['inputUrl'];
      $arquivo['tipo'] = 'arquivo_produto';
      $arquivo['joined'] = date('Y-m-d H:i:s');
      $arquivo['ativo'] = 1;

      if (isset($this->request->files['inputImagem']['name']) && is_array($this->request->files['inputImagem']['name']) && sizeof($this->request->files['inputImagem']['name']) > 0) {
        for ($i=0; $i<sizeof($this->request->files['inputImagem']['name']); $i++) {
          $ext_capa = strtolower(pathinfo($this->request->files['inputImagem']['name'][$i], PATHINFO_EXTENSION));
          if (in_array($ext_capa , $capa_ext)) {
            if ($this->request->files['inputImagem']['size'][$i] < $capa_size) {
              $uniq = uniqid();
              $img_capa = "img".$uniq. "." . $ext_capa;
              $img_thumb = "compressed/img".$uniq. ".jpg";

              $source = $this->request->files['inputImagem']['tmp_name'][$i];
              $destination = $capa_path . $img_capa;

              if (move_uploaded_file($source, $destination)) {
                // tudo certo com o envio da imagem, salvando arquivos
                $imagens_ext = array('png', 'jpg', 'jpeg');
                if (in_array($ext_capa, $imagens_ext)) {
                  $thumb = $this->func->compressImage($destination, $capa_path . $img_thumb, 75);
                  if ($thumb) {
                    $arquivo['thumbnail'] = $img_thumb;
                  } else {
                    if (!isset($this->request->post['modal'])) {
                      $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
                      $this->response->redirect($this->url->link('produtos/home/arquivos&id=' . $arquivo['idproduto']));
                    } else {
                      $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #5 - Erro ao comprimir - ' . $this->request->files['inputImagem']['tmp_name'][$i] . ' -> ' . $capa_path . $img_capa));
                      exit;
                    }  
                  }
                }
                $arquivo['arquivo'] = $img_capa;
                $this->model_arquivo_arquivo->add($arquivo);
              } else {
                if (!isset($this->request->post['modal'])) {
                  $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
                  $this->response->redirect($this->url->link('produtos/home/arquivos&id=' . $arquivo['idproduto']));
                } else {
                  $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #4 - Arquivo não enviado - ' . $this->request->files['inputImagem']['tmp_name'][$i] . ' -> ' . $capa_path . $img_capa));
                  exit;
                }  
              }
            } else {
              if (!isset($this->request->post['modal'])) {
                $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
                $this->response->redirect($this->url->link('produtos/home/arquivos&id=' . $arquivo['idproduto']));
              } else {
                $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #3 - Arquivo muito grande'));
                exit;
              }  
            }
          } else {
            if (!isset($this->request->post['modal'])) {
              $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
              $this->response->redirect($this->url->link('produtos/home/arquivos&id=' . $arquivo['idproduto']));
            } else {
              $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #2 - Extensão não suportada'));
              exit;
            }  
          }  
        }

      } else {
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
                $imagens_ext = array('png', 'jpg', 'jpeg');
                if (in_array($ext_capa, $imagens_ext)) {
                  $thumb = $this->func->compressImage($destination, $capa_path . $img_thumb, 75);
                  if ($thumb) {
                    $arquivo['thumbnail'] = $img_thumb;
                  } else {
                    if (!isset($this->request->post['modal'])) {
                      $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
                      $this->response->redirect($this->url->link('produtos/home/arquivos&id=' . $arquivo['idproduto']));
                    } else {
                      $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #5 - Erro ao comprimir - ' . $this->request->files['inputImagem']['tmp_name'] . ' -> ' . $capa_path . $img_capa));
                      exit;
                    }  
                  }
                }
                $arquivo['arquivo'] = $img_capa;
              } else {
                if (!isset($this->request->post['modal'])) {
                  $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
                  $this->response->redirect($this->url->link('produtos/home/arquivos&id=' . $arquivo['idproduto']));
                } else {
                  $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #4 - Arquivo não enviado - ' . $this->request->files['inputImagem']['tmp_name'] . ' -> ' . $capa_path . $img_capa));
                  exit;
                }  
              }
            } else {
              if (!isset($this->request->post['modal'])) {
                $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
                $this->response->redirect($this->url->link('produtos/home/arquivos&id=' . $arquivo['idproduto']));
              } else {
                $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #3 - Arquivo muito grande'));
                exit;
              }  
            }
          } else {
            if (!isset($this->request->post['modal'])) {
              $this->session->data['success'] = array('key' => 'error_upload_arquivo_pagina');
              $this->response->redirect($this->url->link('produtos/home/arquivos&id=' . $arquivo['idproduto']));
            } else {
              $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #2 - Extensão não suportada'));
              exit;
            }  
          }
        } else {
          if (!isset($this->request->post['idarquivo']) || $this->request->post['idarquivo'] == '' || $this->request->post['idarquivo'] == 0) {
            if (!isset($this->request->post['modal'])) {
              $this->session->data['error'] = array('key' => 'error_upload_arquivo_pagina');
              $this->response->redirect($this->url->link('produtos/home/arquivos&id=' . $arquivo['idproduto']));
            } else {
              $this->response->json(array('error' => true, 'msg' => 'ERRO INTERNO #1'));
              exit;
            }  
          }
        }
      
        $arquivo = $this->model_arquivo_arquivo->save($arquivo);
      }
    

      if (!isset($this->request->post['modal'])) {
        $this->session->data['success'] = array('key' => 'upload_arquivo_pagina');
        $this->response->redirect($this->url->link('produtos/home/arquivos&id=' . $arquivo['idproduto']));
      } else {
        $this->response->json(array('error' => false, 'msg' => $arquivo['idbloco']));
      }

      
    }
  }

  public function arquivos_bloco() {
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      $this->load->model('produto/produto');
      $this->load->model('arquivo/arquivo');
      $idbloco = $this->request->get['id'];
      $idproduto = $this->request->get['idproduto'];

      $this->document->setTitle('Produtos');

      $arquivos = $this->model_arquivo_arquivo->getByIdbloco($idbloco, null, $idproduto);

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
          $idproduto = $arquivo['idproduto'];
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

  public function remover_imagem() {
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      if (isset($this->request->get['id']) && $this->request->get['id'] !== '') {
        $this->load->model('produto/departamento');
        $departamento = $this->model_produto_departamento->getById($this->request->get['id']);
        if ($departamento['imagem'] !== null) {
          $imagens = json_decode($departamento['imagem'], true);
          if ($imagens['thumbnail'] && is_file(UPLOADS . $imagens['thumbnail'])) {
            unlink(UPLOADS . $imagens['thumbnail']);
          }
          if ($imagens['arquivo'] && is_file(UPLOADS . $imagens['arquivo'])) {
            unlink(UPLOADS . $imagens['arquivo']);
          }
        }
        $departamento['imagem'] = 'null';
        $departamento = $this->model_produto_departamento->edit($departamento);
        
        $this->session->data['success'] = array('key' => 'remover_arquivo_departamento');
        $this->response->redirect($this->url->link('produtos/home/edit_departamento') . '&id=' . $this->request->get['id']);
      }
    }
  }

  public function desativar_pagina() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['id']) && $this->request->post['id'] !== '') {
        $this->load->model('produto/produto');
        $produto = $this->model_produto_produto->getById($this->request->post['id']);
        if ($produto['ativo'] == 1) {
          $this->model_produto_produto->delete($this->request->post['id']);
        } else {
          $produto['ativo'] = 1;
          $this->model_produto_produto->edit($produto);
        }
        $this->response->json(array('error' => false));
      }
    }
  }

  public function order() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['str']) && $this->request->post['str'] !== '') {
        $this->load->model('produto/produto');
        $str = explode(',', $this->request->post['str']);
        if (sizeof($str) > 0) {
          foreach($str as $count => $id) {
            $p = $this->model_produto_produto->getById($id);
            $p['ordem'] = $count;
            $this->model_produto_produto->edit($p);
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

  private function getBreadcrumbs($iddepartamento = null, $idproduto = null) {
    // breadcrumbs
    $data['breadcrumbs'] = array();

    if ($this->request->get['route'] == 'produtos/home/add') {
      $data['breadcrumbs'][] = array(
        'name' => 'Adicionar produto',
        'url' => $this->url->link('produtos/home/add'),
        'active' => false
      );
    }

    if ($this->request->get['route'] == 'produtos/home/add_departamento') {
      $data['breadcrumbs'][] = array(
        'name' => 'Adicionar departamento',
        'url' => $this->url->link('produtos/home/add_departamento'),
        'active' => false
      );
    }

    if ($this->request->get['route'] == 'produtos/home/arquivos') {
      if (isset($idproduto)) {
        $data['breadcrumbs'][] = array(
          'name' => 'Arquivos',
          'url' => $this->url->link('produtos/home/arquivos') . '&id=' . $idproduto,
          'active' => false
        );
      }
    }
    
    if ($idproduto !== null) {
      $this->load->model('produto/produto');
      $data['selected_produto'] = $this->model_produto_produto->getById($idproduto);
      $iddepartamento = $data['selected_produto']['iddepartamento'];
      $data['breadcrumbs'][] = array(
        'name' => $data['selected_produto']['nome'],
        'url' => $this->url->link('produtos/home/edit') . '&id=' . $idproduto,
        'active' => false
      );
    }

    if ($iddepartamento !== null) {
      $this->load->model('produto/departamento');
      $data['selected_departamento'] = $this->model_produto_departamento->getById($iddepartamento);

      $data['breadcrumbs'][] = array(
        'name' => $data['selected_departamento']['nome'],
        'url' => $this->url->link('produtos/home') . '&id=' . $iddepartamento,
        'active' => false
      );
    }


    $data['breadcrumbs'][] = array(
      'name' => 'Departamentos',
      'url' => $this->url->link('produtos/home'),
      'active' => false
    );

    $data['breadcrumbs'] = array_reverse($data['breadcrumbs']);

    $data['breadcrumbs'][sizeof($data['breadcrumbs']) - 1]['active'] = true;

    return $data;
  }

  public function duplicar() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['idproduto']) && $this->request->post['idproduto'] !== '') {
        $this->load->model('produto/produto');
        $this->load->model('produto/bloco');
        $this->load->model('arquivo/arquivo');
        $produto = $this->func->getProduto($this->request->post['idproduto'], false);
        $new_produto = $produto['produto'];
        unset($new_produto['idproduto']);
        $new_produto['nome'] = $new_produto['nome'] . ' Cópia';
        $new_produto = $this->model_produto_produto->add($new_produto);
        $imagens = $this->model_arquivo_arquivo->getByIdproduto($this->request->post['idproduto']);

        if (sizeof($imagens) > 0) {
          foreach($imagens as $i => $imagem) {
            $new_imagem = $imagem;
            unset($imagem['idarquivo']);
            $new_imagem['idproduto'] = $new_produto['idproduto'];
          }
        }

        if (sizeof($produto['blocos']) > 0) {
          foreach($produto['blocos'] as $i => $bloco) {
            $new_bloco = $bloco;
            unset($new_bloco['idbloco_produto']);
            $arquivos = $new_bloco['arquivos'];
            unset($new_bloco['arquivos']);

            $new_bloco['idproduto'] = $new_produto['idproduto'];
            $new_bloco = $this->model_produto_bloco->add($new_bloco);

            if (sizeof($arquivos) > 0) {
              foreach($arquivos as $a => $arquivo) {
                $new_arquivo = $arquivo;
                unset($new_arquivo['idarquivo']);
                $new_arquivo['idproduto'] = $new_produto['idproduto'];
                $new_arquivo['idbloco'] = $new_bloco['idbloco_produto'];

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
