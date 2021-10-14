<?php

  class WhatsAppAPI {
    private $url = 'https://eu236.chat-api.com/instance224302/';
    private $token = 'o77tiyq10ceicprb';

    private function quit($error, $msg, $ext = null) {
      $ret = array(
        "error"=>$error,
        "msg"=>$msg
      );
      if ($ext != null) $ret["ext"] = $ext;
      echo json_encode($ret);
      exit;
    }

    public function formatCel($cel) {
      $cel = preg_replace('/[\(\)\.\-\+\s]+/', '', $cel);
      $area = substr($cel, 0, 2);
      $len = strlen($cel);
      if ($len < 13) {
        if ($area != '55') {
          $cel = '55' . $cel;
        }
      }
      return $cel;
    }

    public function htmlToUtf($text) {
      $reg = array(
        array('/<strong\s?[\w\s\d,:;"=0-9()]*?>/', '</strong>'),
        array('/<s[^pan|strong]\s?[\w\s\d,:;"=0-9()]*?>?/', '</s>'),
        array('/<em\s?[\w\s\d,:;"=0-9()]*?>/', '</em>'),
        array('/<pre\s?[\w\s\d,:;"=0-9()]*?>/', '</pre>')
      );

      $rep = array(
        '*',
        '~',
        '_',
        '```'
      );

      for ($i = 0; $i < sizeof($reg); $i++) {
        $text = preg_replace($reg[$i][0], $rep[$i], $text);
        $text = str_replace($reg[$i][1], $rep[$i], $text);
      }

      preg_match_all('/<p(|\s+[^>]*)>(.*?)<\/p\s*>/', $text, $matches); // inserir \n
      $ret = '';
      foreach ($matches[0] as $linha) {
        if ($linha !== '<br>') {
          $ret .= $linha . "\n";
        } else {
          $ret .= "\n";
        }
      }
      $ret = strip_tags($ret);
      return $ret;
    }

    public function getStatus($full = false) {
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url . 'status?token=' . $this->token . ($full === true ? '&full=true' : ''),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET'
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      $r = json_decode($response);
      $auth = $r->accountStatus;
      $data = $r->statusData;
      $res = array();
      if ($auth === 'authenticated') {
        if ($data->title === 'Telefone desconectado' || $data->title === 'Phone not connected' ) {
          $res['erro'] = true;
          $res['msg'] = 'Telefone desconectado.';
        } else {
          $res['erro'] = false;
        }
      } else {
        $res['erro'] = true;
        $res['msg'] = 'Sistema não autenticado.';
      }

      return $res;
    }

    public function getDialogs() {
      $status = $this->getStatus(true);
      if ($status['erro'] === true) quit(true, $status['msg']);
    
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url . 'dialogs?token=' . $this->token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET'
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      $r = json_decode($response, true);
      return $r;
    }

    public function sendMessage($msg, $num = null, $chatId = null) {

      $status = $this->getStatus(true);
      if ($status === false) quit(true, 'Sistema não autenticado', $status);
      
      $curl = curl_init();
      $data = array(
        "body"=>$msg
      );

      if ($num != null) {
        $data['phone'] = $this->formatCel($num);
      } else if ($chatId != null) {
        $data['chatId'] = $chatId;
      }

      curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url . 'sendMessage?token=' . $this->token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      $response = json_decode($response);
      if ($response->sent === true) {
        return true;
      } else {
        return $response;
      }
    }

    public function sendLink($link, $title, $msg, $num = null, $chatId = null) {

      $status = $this->getStatus(true);
      if ($status === false) $this->quit(true, 'Sistema não autenticado', $status);
      
      $curl = curl_init();
      $data = array(
        "body"=>$link,
        "previewBase64"=>"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEBLAEsAAD/4QCWRXhpZgAASUkqAAgAAAACAA4BAgBdAAAAJgAAAJiCAgALAAAAgwAAAAAAAABDb25jZXB0IG9mIGVtYWlsIG5vdGlmaWNhdGlvbiBpY29uLiBMZXR0ZXIgaW4geWVsbG93IGNvdmVyLiBGbGF0IGRlc2lnbiwgdmVjdG9yIGlsbHVzdHJhdGlvbi5WZWN0b3JTdG9yef/hBZZodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+Cjx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iPgoJPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KCQk8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iIHhtbG5zOklwdGM0eG1wQ29yZT0iaHR0cDovL2lwdGMub3JnL3N0ZC9JcHRjNHhtcENvcmUvMS4wL3htbG5zLyIgICB4bWxuczpHZXR0eUltYWdlc0dJRlQ9Imh0dHA6Ly94bXAuZ2V0dHlpbWFnZXMuY29tL2dpZnQvMS4wLyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bWxuczpwbHVzPSJodHRwOi8vbnMudXNlcGx1cy5vcmcvbGRmL3htcC8xLjAvIiAgeG1sbnM6aXB0Y0V4dD0iaHR0cDovL2lwdGMub3JnL3N0ZC9JcHRjNHhtcEV4dC8yMDA4LTAyLTI5LyIgeG1sbnM6eG1wUmlnaHRzPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvcmlnaHRzLyIgZGM6UmlnaHRzPSJWZWN0b3JTdG9yeSIgcGhvdG9zaG9wOkNyZWRpdD0iR2V0dHkgSW1hZ2VzL2lTdG9ja3Bob3RvIiBHZXR0eUltYWdlc0dJRlQ6QXNzZXRJRD0iNTg5MTIyNTUwIiB4bXBSaWdodHM6V2ViU3RhdGVtZW50PSJodHRwczovL3d3dy5pc3RvY2twaG90by5jb20vbGVnYWwvbGljZW5zZS1hZ3JlZW1lbnQ/dXRtX21lZGl1bT1vcmdhbmljJmFtcDt1dG1fc291cmNlPWdvb2dsZSZhbXA7dXRtX2NhbXBhaWduPWlwdGN1cmwiID4KPGRjOmNyZWF0b3I+PHJkZjpTZXE+PHJkZjpsaT5WZWN0b3JTdG9yeTwvcmRmOmxpPjwvcmRmOlNlcT48L2RjOmNyZWF0b3I+PGRjOmRlc2NyaXB0aW9uPjxyZGY6QWx0PjxyZGY6bGkgeG1sOmxhbmc9IngtZGVmYXVsdCI+Q29uY2VwdCBvZiBlbWFpbCBub3RpZmljYXRpb24gaWNvbi4gTGV0dGVyIGluIHllbGxvdyBjb3Zlci4gRmxhdCBkZXNpZ24sIHZlY3RvciBpbGx1c3RyYXRpb24uPC9yZGY6bGk+PC9yZGY6QWx0PjwvZGM6ZGVzY3JpcHRpb24+CjxwbHVzOkxpY2Vuc29yPjxyZGY6U2VxPjxyZGY6bGkgcmRmOnBhcnNlVHlwZT0nUmVzb3VyY2UnPjxwbHVzOkxpY2Vuc29yVVJMPmh0dHBzOi8vd3d3LmlzdG9ja3Bob3RvLmNvbS9waG90by9saWNlbnNlLWdtNTg5MTIyNTUwLT91dG1fbWVkaXVtPW9yZ2FuaWMmYW1wO3V0bV9zb3VyY2U9Z29vZ2xlJmFtcDt1dG1fY2FtcGFpZ249aXB0Y3VybDwvcGx1czpMaWNlbnNvclVSTD48L3JkZjpsaT48L3JkZjpTZXE+PC9wbHVzOkxpY2Vuc29yPgoJCTwvcmRmOkRlc2NyaXB0aW9uPgoJPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KPD94cGFja2V0IGVuZD0idyI/Pgr/7QC8UGhvdG9zaG9wIDMuMAA4QklNBAQAAAAAAJ8cAlAAC1ZlY3RvclN0b3J5HAJ4AF1Db25jZXB0IG9mIGVtYWlsIG5vdGlmaWNhdGlvbiBpY29uLiBMZXR0ZXIgaW4geWVsbG93IGNvdmVyLiBGbGF0IGRlc2lnbiwgdmVjdG9yIGlsbHVzdHJhdGlvbi4cAnQAC1ZlY3RvclN0b3J5HAJuABhHZXR0eSBJbWFnZXMvaVN0b2NrcGhvdG8A/9sAQwAK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",
        "title"=>$title,
        "text"=>$msg
      );

      if ($num != null) {
        $data['phone'] = $this->formatCel($num);
      } else if ($chatId != null) {
        $data['chatId'] = $chatId;
      }

      curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url . 'sendLink?token=' . $this->token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      $response = json_decode($response);
      if ($response->sent === true) {
        return true;
      } else {
        return $response;
      }
    }

    private function getLegendaWhatsapp($acao) {
      $return = '';
      switch($acao) {
        case 'novo_job': 
          $return = '<p><strong>#USER#</strong> criou uma nova tarefa para o seu grupo.</p><p><br></p><p>#LINK#</p>';
        break;
        case 'alterar_job': 
          $return = '<p><strong>#USER#</strong> editou uma tarefa do seu grupo.</p><p><br></p><p>#LINK#</p>';
        break;
        case 'configurar_cronograma': 
          $return = '<p><strong>#USER#</strong> configurou um cronograma do cliente <strong>#CLIENTE#</strong> para você!</p><p><br></p><p>#LINK#</p>';
        break;
        case 'alterar_status_job': 
          $return = '<p><strong>#USER#</strong> alterou o status de uma tarefa do seu grupo.</p><p><br></p><p>#LINK#</p>';
        break;
        case 'novo_comentario_job':
          $return = '<p><strong>#USER#</strong> adicionou um comentário a uma tarefa do seu grupo.</p><p><br></p><p>#LINK#</p>';
        break;
        case 'finalizar_job':
          $return = '<p><strong>#USER#</strong> finalizou uma tarefa do seu grupo.</p><p><br></p><p>#LINK#</p>';
        break;
        case 'cancelar_contrato_cliente':
          $return = '<p><strong>#USER#</strong> cancelou o cronograma <strong>#CONTRATO#</strong> do cliente <strong>#CLIENTE#</strong>.</p>';
        break;
        case 'nova_entrega':
            $return = '<p><strong>#USER#</strong> criou uma nova entrega no cronograma de <strong>#CLIENTE#</strong> para o dia <strong>#DATA_ENTREGA#</strong>.</p><p><br></p><p>#LINK#</p>';
        break;
      }
      return $return;
    }

    private function tratarLog($msg, $log) {
      $ret = array('text'=>'', 'link'=>'');
      $txt = $msg;
      if (strpos($msg, '#USER#') !== false) {
        $usuarioDAO = new UsuarioDAO();
        $u = $usuarioDAO->getById($log->getIdusuario())->getNome();
        $txt = str_replace('#USER#', $u, $txt);
      }

      if (strpos($msg, '#CLIENTE#') !== false) {
        $json = strlen($log->getObj_old()) > 0 ? $log->getObj_old() : $log->getObj_new();
        $obj = json_decode($json, true);
        $idcliente = $obj['idcliente'];
        $clienteDAO = new ClienteDAO();
        $c = $clienteDAO->getById($idcliente)->getNome();
        $txt = str_replace('#CLIENTE#', $c, $txt);
      }

      if (strpos($msg, '#CONTRATO#') !== false) {
        $json = strlen($log->getObj_old()) > 0 ? $log->getObj_old() : $log->getObj_new();
        $obj = json_decode($json, true);
        $n = $obj['nome'];
        $txt = str_replace('#CONTRATO#', $n, $txt);
      }

      if (strpos($msg, '#DATA_ENTREGA#') !== false) {
        $json = strlen($log->getObj_old()) > 0 ? $log->getObj_old() : $log->getObj_new();
        $obj = json_decode($json, true);
        $dt = DateTime::createFromFormat('Y-m-d', $obj['data_entrega'])->format('d/m/Y');
        $txt = str_replace('#DATA_ENTREGA#', $dt, $txt);
      }

      if (strpos($txt, '#LINK#') !== false) {
        // $url_base = 'http://beto.fox3.com.br/beto-v2/';
        $url_base = getUrl();
        if ($log->getAcao() == 'novo_job') {
          $link = $url_base . 'cms/content/index.php?pg=comments&lc=jobs&id=' . $log->getId_alteracao();
          $txt = str_replace('#LINK#', $link, $txt);
        } else if ($log->getAcao() == 'alterar_job') {
          $link = $url_base . 'cms/content/index.php?pg=comments&lc=jobs&id=' . $log->getId_alteracao();
          $txt = str_replace('#LINK#', $link, $txt);
        } else if ($log->getAcao() == 'configurar_cronograma') {
          $link = $url_base . 'cms/content/index.php?pg=editor&lc=cronograma&idc=' . $log->getId_alteracao();
          $txt = str_replace('#LINK#', $link, $txt);
        } else if ($log->getAcao() == 'alterar_status_job') {
          $link = $url_base . 'cms/content/index.php?pg=comments&lc=jobs&id=' . $log->getId_alteracao();
          $txt = str_replace('#LINK#', $link, $txt);
        } else if ($log->getAcao() == 'novo_comentario_job') {
          $obj = json_decode($log->getObj_new(), true);
          $idj = $obj['idjob'];
          $link = $url_base . 'cms/content/index.php?pg=comments&lc=jobs&id=' . $idj . '&idc=' . $log->getId_alteracao();
          $txt = str_replace('#LINK#', $link, $txt);
        } else if ($log->getAcao() == 'finalizar_job') {
          $link = $url_base . 'cms/content/index.php?pg=comments&lc=jobs&id=' . $log->getId_alteracao();
          $txt = str_replace('#LINK#', $link, $txt);
        } else if ($log->getAcao() == 'nova_entrega') {
          $obj = json_decode($log->getObj_new(), true);
          $idc = $obj['idcronograma'];
          $link = $url_base . 'cms/content/index.php?pg=editor&lc=cronograma&idc=' . $idc;
          $txt = str_replace('#LINK#', $link, $txt);
        } 

        $ret['link'] = $link;
      }

      $ret['text'] = $txt;
      return $ret;
    }

    private function tratarMsg($text, $usuario = null) {
      if ($usuario != null) {
        $text = str_replace('%NOME_USUARIO%', $usuario->getNome(), $text);
      }
  
      return $text;
    }

    function verifyUserHour($usuario) {
      $h_u = explode(',', $usuario->getHorarios());
      $h_i = null; $h_f = null;
      if (strpos($h_u[0], '.') !== false) {
        $h_i = explode('.', $h_u[0]);
        $tmp_h = intval($h_i[0]) < 10 ? '0'.$h_i[0] : $h_i[0];
        $h_i = $tmp_h.':30';
      } else {
        $tmp_h = intval($h_u[0]) < 10 ? '0'.$h_u[0] : $h_u[0];
        $h_i = $tmp_h.':00';
      }

      if (strpos($h_u[1], '.') !== false) {
        $h_f = explode('.', $h_u[1]);
        $tmp_h = intval($h_f[0]) < 10 ? '0'.$h_f[0] : $h_f[0];
        $h_f = $tmp_h.':30';
      } else {
        $tmp_h = intval($h_u[1]) < 10 ? '0'.$h_u[1] : $h_u[1];
        $h_f = $tmp_h.':00';
      }

      $dt_i_u = DateTime::createFromFormat('H:i', $h_i);
      $dt_f_u = DateTime::createFromFormat('H:i', $h_f);
      $dt_f_u->add(date_interval_create_from_date_string('1 hour'));

      $now = DateTime::createFromFormat('H:i', date('H:i'));
      return ($now < $dt_f_u && $now > $dt_i_u);
    }
  
    function startupUser($usuario) {
      if (!class_exists('UsuarioVO')) $this->quit(true, 'Classe UsuarioVO não carregada.');
      if (!class_exists('UsuarioDAO')) $this->quit(true, 'Classe UsuarioDAO não carregada.');
      if (!class_exists('ConfigVO')) $this->quit(true, 'Classe ConfigVO não carregada.');
      if (!class_exists('ConfigDAO')) $this->quit(true, 'Classe ConfigDAO não carregada.');

      $boas_vindas = null; $enviar_boas_vindas = false; $notif = null; $enviar_notif = false;
      $num_usuario = $usuario->getWhatsapp();
  
      $configDAO = new ConfigDAO();
      
      $intro = $configDAO->getByNome('APRESENTACAO_WPP_USER');
      $intro = $this->htmlToUtf($intro->getVal());
      $intro = $this->tratarMsg($intro, $usuario);
  
      $desativar = $configDAO->getByNome('DESATIVAR_WPP_USER');
      $desativar = $this->htmlToUtf($desativar->getVal());
      $desativar = $this->tratarMsg($desativar, $usuario);
  
      $reativar = $configDAO->getByNome('REATIVAR_WPP_USER');
      $reativar = $this->htmlToUtf($reativar->getVal());
      $reativar = $this->tratarMsg($reativar, $usuario);
      
      @$log = json_decode($usuario->getLog_whatsapp(), true);
      @$init = $log['init'];
  
      // enviar boas vindas
      if ($log !== null && isset($init)) {
        $num_sent = $init['num_config'];
  
        if ($num_sent !== $num_usuario) {
          $enviar_boas_vindas = true;
          $boas_vindas = $intro;
        } else {
          $enviar_boas_vindas = false;
        }
      } else {
        $enviar_boas_vindas = true;
        $boas_vindas = $intro;
      }
  
  
      if ($usuario->getNotif_whatsapp() == 1) {
  
        if ($log !== null && isset($init) && $init['ativo'] === true && $num_sent === $num_usuario) {
          $enviar_notif = false;
        } else {
          $enviar_notif = true;
          $notif = $reativar;
  
          $init['ativo'] = true;
        }
  
      } else {
        if ($log !== null && isset($init) && $init['ativo'] === true) {
          $enviar_notif = true;
          $notif = $desativar;
  
          $init['ativo'] = false;
        } else {
          $enviar_notif = false;
          $notif = null;
        }
      }
  
  
      if ($enviar_boas_vindas === true) {
        $this->sendMessage($boas_vindas, $num_usuario);
      }
  
      if ($enviar_notif === true) {
        $this->sendMessage($notif, $num_usuario);
      }
  
  
      if ($log === null || !isset($init)) {
        $log = array(
          'init' => array(
            'data'=>date('Y-m-d H:i:s'),
            'boas_vindas'=>$enviar_boas_vindas,
            'num_config'=>$num_usuario,
            'ativo'=>$usuario->getNotif_whatsapp() == 1 ? true : false
          )
        );
      } else {
        $init['num_config'] = $num_usuario;
        $log['init'] = $init;
      }
      
      $usuario->setLog_whatsapp(json_encode($log));
      return $usuario;
    }
  
    function notifyUserLog($idusuario = null, $usuario = null, $log) {
      $acao = $log->getAcao();
      if ($acao === 'novo_job' || $acao === 'alterar_job' || $acao === 'configurar_cronograma' || $acao === 'alterar_status_job' || $acao === 'novo_comentario_job' || $acao === 'finalizar_job' || $acao === 'cancelar_contrato_cliente' || $acao === 'nova_entrega') {
        if (!class_exists('UsuarioVO')) $this->quit(true, 'Classe UsuarioVO não carregada.');
        if (!class_exists('UsuarioDAO')) $this->quit(true, 'Classe UsuarioDAO não carregada.');
        if (!class_exists('ConfigVO')) $this->quit(true, 'Classe ConfigVO não carregada.');
        if (!class_exists('ConfigDAO')) $this->quit(true, 'Classe ConfigDAO não carregada.');
        if (!class_exists('ClienteVO')) $this->quit(true, 'Classe ClienteVO não carregada.');
        if (!class_exists('ClienteDAO')) $this->quit(true, 'Classe ClienteDAO não carregada.');
  
        if ($usuario === null && $idusuario !== null) {
          $usuarioDAO = new UsuarioDAO();
          $usuario = $usuarioDAO->getById($idusuario);
        }

        
        if ($usuario->getNotif_whatsapp() == '1') {
          if ($this->verifyUserHour($usuario) === true) {
            $l_whats = json_decode($usuario->getLog_whatsapp(), true);
            if ($l_whats['init']['ativo'] === true) {
  
              $ret = $this->getLegendaWhatsapp($log->getAcao());
              $ret = "<p>Olá %NOME_USUARIO%,</p>" . $ret;
              $ret = $this->tratarLog($ret, $log);
              $ret['text'] = $this->tratarMsg($ret['text'], $usuario);
              $ret['text'] = $this->htmlToUtf($ret['text']);
  
              if ($log->getAcao() !== 'cancelar_contrato_cliente') {
                $this->sendLink($ret['link'], 'Beto FOX3', $ret['text'], $usuario->getWhatsapp());
              } else {
                $this->sendMessage($ret['text'], $usuario->getWhatsapp());
              }
            }
          }



        }
      }
    }

  }

?>