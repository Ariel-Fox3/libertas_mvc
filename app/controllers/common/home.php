<?php

namespace Mubbi;

use DateTime;

class ControllerCommonHome extends BaseController {
  public function index($data = null) {

    $this->document->setTitle('Libertas');

    $data['pagina'] = $this->func->getPage(4); // ID DA PÁGINA NO ADMIN

    $data['servicos'] = $this->func->getPage(5); // ID DA PÁGINA NO ADMIN

    // echo "<pre>";
    //   print_r($data['servicos']['sub']);
    // echo "</pre>";
    // exit;


    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if(isset($this->request->post['g-recaptcha-response']) && !empty($this->request->post['g-recaptcha-response'])) {
        $secret = '6LeX_zQcAAAAAKNkrZT05L3j3ZHJGhZkfjSrSjB1';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$this->request->post['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if ($responseData->success) {
          $form = $this->request->post;
          unset($form['g-recaptcha-response']);
          
          @$req = array(
            'get' => $this->request->get,
            'info' => array(
              'user_agent' => $this->request->server['HTTP_USER_AGENT'], 
              'origin' => $this->request->server['HTTP_ORIGIN'], 
              'remote_addr' => $this->request->server['REMOTE_ADDR'], 
              'forwarded' => $this->request->server['HTTP_X_FORWARDED_FOR'], 
            )
          );
    
          $this->load->model('config/config');
          $gerais = $this->model_config_config->getByChave('gerais');
          $gerais = json_decode($gerais['val'], true);
          $status = $gerais['status'];
    
          $solicitacao = array(
            'joined' => date('Y-m-d H:i:s'),
            'form' => json_encode($form),
            'user' => json_encode($req),
            'status' => $status[0],
            'origem' => 'Agendar reunião'
          );
    
          $this->load->model('solicitacao/solicitacao');

          $this->model_solicitacao_solicitacao->add($solicitacao);

          $info =  array(
            'nome' => $form['nome'],
            'email' => $form['email'],
            'telefone' => $form['telefone'],
            'motivos' => $form['motivos']
          );
          $mail_template = $this->load->view('mail/solicitacao_reuniao', $info);
          $destinatarios = array('gabriel@foxthree.com.br');
          $r = $this->mail->send_mail($destinatarios, 'Novo contato no site Libertas', $mail_template);
          
    
          $this->response->json(array('error' => false, 'msg' => 'Solicitação de contato enviada com sucesso!'));
        } else {
          $this->response->json(array('error' => true, 'msg' => 'Falha na verificação do reCaptcha.'));
        }
      } else {
        $this->response->json(array('error' => true, 'msg' => 'Falha na verificação do reCaptcha.'));
      }
      
    }

    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('common/home', $data));

    
  }

  
  
}
