<?php
@session_start();
include ('class/class.Conexao.php');
include ('class/class.UsuarioVO.php');
include ('class/DAO/class.UsuarioDAO.php');
//echo md5("fud3ncio");

if (isset($_SESSION['usuarioSession'])) {
  echo "<script>window.location.href ='content/index.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">

		<title>Moinhos Fitness | CMS</title>

		<link href="../css/bootstrap.css" rel="stylesheet">
		<script src="../js/jquery.min.js"></script>
		<script type="text/javascript" src="../js/jquery.form.js"></script>

	</head>

	<body>
		<style>
			body {
				padding-top: 40px;
				padding-bottom: 40px;
				background-color: #eee;
			}

			.form-signin {
				max-width: 330px;
				padding: 15px;
				margin: 0 auto;
			}
			.form-signin .form-signin-heading, .form-signin .checkbox {
				margin-bottom: 10px;
			}
			.form-signin .checkbox {
				font-weight: normal;
			}
			.form-signin .form-control {
				position: relative;
				height: auto;
				-webkit-box-sizing: border-box;
				-moz-box-sizing: border-box;
				box-sizing: border-box;
				padding: 10px;
				font-size: 16px;
			}
			.form-signin .form-control:focus {
				z-index: 2;
			}
			.form-signin input[type="email"] {
				margin-bottom: -1px;
				border-bottom-right-radius: 0;
				border-bottom-left-radius: 0;
			}
			.form-signin input[type="password"] {
				margin-bottom: 10px;
				border-top-left-radius: 0;
				border-top-right-radius: 0;
			}

		</style>
		<div class="container">
			<form class="form-signin" method="post" role="form">
				<h2 class="form-signin-heading">Faça seu login</h2>
				<?php
				if ($_POST) {
					dbcon();
					$usuario = new UsuarioVO();
					$usuario -> setUsuario($_POST['usuario']);
					$usuario -> setSenha($_POST['senha']);
					$usuarioDAO = new UsuarioDAO();
					if ($usuarioDAO->logar($usuario)) {
						echo "<script>window.location.href ='content/index.php'</script>";
						//header("Location: content/index.php");
					} else {
						printf('<p>Erro: Usuario/Senha incorreto(s).</p>');
					}
				}
				?>
				<input type="text" class="form-control" name="usuario" placeholder="usuario" required autofocus>
				<input type="password" class="form-control" name="senha" placeholder="senha" required>
				<button class="btn btn-lg btn-primary btn-block" type="submit">
					Entrar
				</button>
			</form>
			<p class="text-center">Desenvolvido por <a href="http://www.foxthree.com.br" target="_blank">Foxthree</a></p>
		</div>
	</body>
</html>