<?php
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.SolicitacaoVO.php");
include("../../../class/DAO/class.SolicitacaoDAO.php");
// include("../../../../phpmail/PHPMailerAutoload.php");
// include("../../../class/config.php");
include("../../../class/class.Func.php");

$solicitacaoDAO = new SolicitacaoDAO();
// $mail = new PHPMailer;


$solicitacao = $solicitacaoDAO->getById($_POST['idsolicitacao']);

// $mail->isSMTP();
// $mail->Host = MAIL_HOST;
// $mail->SMTPAuth = true;
// $mail->Username = MAIL_USER;
// $mail->Password = MAIL_PASS;
// $mail->SMTPSecure = 'tls';
// $mail->Port = 587;
// $mail->setFrom(MAIL_USER, utf8_decode(MAIL_NAME));
// $mail->addAddress(MAIL_USER);


// if($_POST['inputUnidade'] != null) {

	$solicitacao->setStatus($_POST['inputStatus']);
	$solicitacao->setObs($_POST['inputObs']);
	$solicitacao->setIdusuario($_POST['idusuario']);
	if($_POST['inputStatus'] == "Finalizado") {
		$solicitacao->setData_final(date("Y-m-d H:i:s"));
	}
	$solicitacaoDAO->save($solicitacao);
	printf("<div class='alert alert-success' role='alert'>Solicitação alterada com sucesso!</div>");

	// if($_POST['inputStatus'] != "Aguardando atendimento") {
	// 	$cliente = $clienteDAO->getById($_POST['inputCliente']);
	// 	if($cliente->getEmail() != null && isMail($cliente->getEmail())) {
	// 		printf("<div class='alert alert-success' role='alert'>Enviamos um e-mail para o cliente sobre esta solicitação.</div>");
	// 		$mail->addBcc($cliente->getEmail());
	// 		$mail->isHTML(true);
	// 		$mail->Subject =  $cliente->getNome().', obrigado por entrar em contato!';
	// 		$msg = '<div style="max-width:600px;min-width:650px;margin:0 auto">';
	// 		$msg .= '<div style="background-color:#000;padding-top:20px;padding-bottom:20px;padding-left:20px;margin-bottom:20px;">';
	// 		$msg .= '<img src="http://www.moinhosfitness.com.br/imagens/logo.png" alt="Moinhos Fitness" style="max-height:60px;">';
	// 		$msg .= '</div>';
	// 		$msg .= 'Olá <strong>'.$cliente->getNome().',</strong><br /><br />';
	// 		$msg .= '<p>Agradecemos pelo seu interesse na Moinhos Fitness! Entre em contato sempre que quiser.<p>';
	// 		$msg .= '<p>Estamos abertos as suas opiniões e gostaríamos de saber como você se sente em relação a nossa academia!';
	// 		$msg .= '<p><a href="http://www.moinhosfitness.com.br/intranet/questionario.php?id='.$solicitacao->getIdsolicitacao().'&email='.$cliente->getEmail().'">Clique aqui</a> e responda nossa pesquisa de satisfação. <strong>É rapidinho!</strong></p>';
	// 		$msg .= '<div style="background-color:#000;margin-top:20px;padding:20px;color:#fff;min-height:80px;">';
	// 		$msg .= '<p>Siga a Moinhos Fitness</p>';
	// 		$msg .= '<a href="https://www.facebook.com/AcademiaMoinhosFitness" style="float:left;margin-right:10px;"><img src="http://moinhosfitness.com.br/imagens/facebook.png"></a>';
	// 		$msg .= '<a href="https://www.instagram.com/moinhosfitness" style="float:left;"><img src="http://moinhosfitness.com.br/imagens/instagram.png"></a>';
	// 		$msg .= '</div>';
	// 		$msg .= '</div>';
	// 		$mail->Body = utf8_decode($msg);
	// 		$mail->AltBody = 'Erro de HTML';
	// 		//$mail->SMTPDebug  = 2;
	// 		if(!$mail->send()) {
	// 			printf("<div class='alert alert-warning' role='alert'>Erro ao enviar e-mail!</div>");
	// 		}
  //  }
	// }


// }


?>
