<?php

  require_once('../intranet/cms/class/class.Conexao.php');
  require_once('../intranet/cms/class/class.Func.php');
  require_once('../intranet/cms/class/API/class.WhatsApp.php');
  require_once('../intranet/cms/class/class.SolicitacaoVO.php');
  require_once('../intranet/cms/class/DAO/class.SolicitacaoDAO.php');

  $solicitacaoDAO = new SolicitacaoDAO();
  $wpp = new WhatsAppAPI();

  function _quit($msg, $erro = true) {
    $t = array('msg'=>$msg, 'erro'=>$erro);
    echo json_encode($t);
    exit;
  }

  $dados = $_POST;
  @$captcha = $dados['g-recaptcha-response'];

  
  if(!$captcha) _quit('Você deve passar na verificação reCaptcha!');
  
  $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Le70QUbAAAAAPXNkL28gnHkw1Z1hyU5dDwjWuff&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
  
  if($response['success'] == false) _quit('Você deve passar na verificação reCaptcha!');

  $solicitacao = new SolicitacaoVO();
	$solicitacao->setNome($dados['inputNome']);
	$solicitacao->setEmail($dados['inputEmail']);
	$solicitacao->setTelefone($dados['inputTelefone']);
  $solicitacao->setMensagem('Contato enviado através da landing page Italínea Class');
	$solicitacao->setStatus('Aguardando atendimento');
	$solicitacao->setData(date('Y-m-d H:i:s'));

  $solicitacao = $solicitacaoDAO->save($solicitacao);

  $link = getUrl() . 'intranet/cms/content/index.php?pg=alterar&lc=solicitacao&id=' . $solicitacao->getIdsolicitacao();
  $mensagem = "Olá,\nAcabamos de receber uma solicitação de contato através da Landing Page Italínea Class!\n\nPara ver mais detalhes, acesse o link. \n" . $link;

  $wpp->sendLink($link, 'Nova solicitação de contato!', $mensagem, null, '555199060590-1622643056@g.us');

  _quit('Seu contato foi enviado com sucesso! Entraremos em contato via telefone ou e-mail.', false);

?>