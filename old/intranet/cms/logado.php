<?php
@session_start();

if (isset($_SESSION['usuarioSession'])) {

  @$login = $_SESSION['usuarioSession']['login'];
  @$nome = $_SESSION['usuarioSession']['nome'];
	@$hora = $_SESSION['usuarioSession']['hora'];
	@$id = $_SESSION['usuarioSession']['id'];
	@$admin = $_SESSION['usuarioSession']['admin'];
	@$solicitacao = $_SESSION['usuarioSession']['solicitacao'];
	@$idunidade = $_SESSION['usuarioSession']['idunidade'];
	@$chave = "1a2cf8g2k68gj67gf784kh69f123idddko6";
	@$ip = $_SERVER['REMOTE_ADDR'];
	
	if ($_SESSION['usuarioSession']['chave'] != md5($login . $chave . $ip . $hora)) {
		echo "<script>window.location.href ='../index.php'</script>";
		//header("Location: ../index.php");
	}

	$hora = time();
	$chaveSession = md5($login . $chave . $ip . $hora);
	$_SESSION['usuarioSession'] = array("id" => $id, "login" => $login, "nome"=>$nome, "chave" => $chaveSession, "hora" => $hora, "admin" => $admin, "solicitacao" => $solicitacao, "idunidade" => $idunidade);
	//header("Location: index.php");


} else {
	setcookie('usuarioSessionUrl', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", time()+60*60*24*7, '/');
	unset($_SESSION['usuarioSession']);
	echo "<script>window.location.href ='../index.php'</script>";
}
?>