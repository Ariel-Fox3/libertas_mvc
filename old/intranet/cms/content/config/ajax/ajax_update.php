<?php 
  date_default_timezone_set('America/Sao_Paulo');
  include("../../../class/class.Conexao.php");
  include("../../../class/class.Func.php");
  include("../../../class/class.ConfigVO.php");
  include("../../../class/DAO/class.ConfigDAO.php");

  $configDAO = new ConfigDAO();

  @$frase = $configDAO->getByNome('FRASE_HEADER');
  if (!$frase) {
    $frase = new ConfigVO();
    $frase->setNome('FRASE_HEADER');
  }
  $frase->setVal($_POST['inputFrase']);
  $frase = $configDAO->save($frase);

  
  @$mail = $configDAO->getByNome('EMAIL_CONTATO');
  if (!$mail) {
    $mail = new ConfigVO();
    $mail->setNome('EMAIL_CONTATO');
  }
  $mail->setVal($_POST['inputEmailContato']);
  $mail = $configDAO->save($mail);

  @$telefone = $configDAO->getByNome('TELEFONE');
  if (!$telefone) {
    $telefone = new ConfigVO();
    $telefone->setNome('TELEFONE');
  }
  $telefone->setVal($_POST['inputTelefone']);
  $telefone = $configDAO->save($telefone);
  
  @$whatsapp = $configDAO->getByNome('WHATSAPP');
  if (!$whatsapp) {
    $whatsapp = new ConfigVO();
    $whatsapp->setNome('WHATSAPP');
  }
  $whatsapp->setVal($_POST['inputWhatsapp']);
  $whatsapp = $configDAO->save($whatsapp);


  @$endereco = $configDAO->getByNome('ENDERECO');
  if (!$endereco) {
    $endereco = new ConfigVO();
    $endereco->setNome('ENDERECO');
  }
  $endereco->setVal($_POST['inputEndereco']);
  $endereco = $configDAO->save($endereco);

  @$fb = $configDAO->getByNome('FB_URL');
  if (!$fb) {
    $fb = new ConfigVO();
    $fb->setNome('FB_URL');
  }
  $fb->setVal($_POST['inputFb']);
  $fb = $configDAO->save($fb);

  @$ig = $configDAO->getByNome('IG_URL');
  if (!$ig) {
    $ig = new ConfigVO();
    $ig->setNome('IG_URL');
  }
  $ig->setVal($_POST['inputIg']);
  $ig = $configDAO->save($ig);

  @$url_amigavel = $configDAO->getByNome('URL_AMIGAVEL');
  if (!$url_amigavel) {
    $url_amigavel = new ConfigVO();
    $url_amigavel->setNome('URL_AMIGAVEL');
  }
  $url_amigavel->setVal($_POST['inputUrlAmigavel']);
  $url_amigavel = $configDAO->save($url_amigavel);

?>