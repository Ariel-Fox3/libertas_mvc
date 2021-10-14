<?php
  @session_start();
  include ('class/class.Conexao.php');
  include ('class/class.UsuarioVO.php');
  include ('class/DAO/class.UsuarioDAO.php');

  if (isset($_SESSION['usuarioSession'])) {
    echo "<script>window.location.href ='content/index.php'</script>";
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Italínea Class - Painel Administrativo</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/new.css" rel="stylesheet">
    <link href="../css/hover.css" rel="stylesheet">
    
    <!-- JQUERY -->
    <script src="../js/jquery-3.5.0.min.js"></script>
    <script type="text/javascript" src="../js/jquery.form.js"></script>
    <!-- <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    
    <!-- JQUERY UI -->
    <script src="../js/jquery-ui.min.js"></script>
    <link href="../css/jquery-ui.min.css" rel="stylesheet">

    <!-- BOOTSTRAP -->
    <script src="../js/bootstrap.bundle.min.js"></script>
    
    
    <!-- FONTAWESOME -->
    <!-- <script src="https://kit.fontawesome.com/2fab917988.js" crossorigin="anonymous"></script> -->
    <!-- <script src="https://kit.fontawesome.com/688cfe5e94.js" crossorigin="anonymous"></script> -->
    <link href="../../assets/css/fontawesome.css" rel="stylesheet">

    <!-- DATEPICKER -->
    <script src="../js/datepicker/js/bootstrap-datepicker.min.js"></script>
    <link href="../js/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <script src="../js/datepicker/js/bootstrap-datepicker.pt-BR.min.js"></script>

    <!-- BOOTSTRAP SELECT -->
    <script src="../js/bootstrap-select.min.js"></script>
    <script src="../js/bootstrap-select-pt-br.min.js"></script>
    <link href="../css/bootstrap-select.min.css" rel="stylesheet">

    <!-- SUMMERNOTE -->
    <script src="../js/summernote-master/summernote-bs4.min.js"></script>
    <link href="../js/summernote-master/summernote-bs4.css" rel="stylesheet">

    <!-- FUNCOES.JS -->
    <script src="../js/funcoes.js"></script>

    <!-- MASK -->
    <script src="../js/jquery.mask.min.js"></script>
    <!-- <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script> -->    
    
    <!-- CALENDARIO -->
    <link href='../js/fullcalendar2/core/main.css' rel='stylesheet' />
    <link href='../js/fullcalendar2/daygrid/main.css' rel='stylesheet' />
    <link href='../js/fullcalendar2/timegrid/main.css' rel='stylesheet' />
    <link href='../js/fullcalendar2/list/main.css' rel='stylesheet' />
    <link href='../js/fullcalendar2/bootstrap/main.css' rel='stylesheet' />
    
    <script src='../js/fullcalendar2/core/main.js'></script>
    <script src='../js/fullcalendar2/interaction/main.js'></script>
    <script src='../js/fullcalendar2/daygrid/main.js'></script>
    <script src='../js/fullcalendar2/timegrid/main.js'></script>
    <script src='../js/fullcalendar2/list/main.js'></script>
    <script src='../js/fullcalendar2/core/locales/pt-br.js'></script>
    <script src='../js/fullcalendar2/bootstrap/main.js'></script>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
          <div class="row w-100 m-0">
            <div class="col-lg-4 mx-auto">
              <div class="auto-form-wrapper">
                <div class="text-center mb-4">
                  <img src="../../assets/img/logo_claro.png" class="img-fluid">
                </div>
                <form method="post" role="form">
                  <?php
                    if ($_POST) {
                      $usuario = new UsuarioVO();
                      $usuario -> setEmail($_POST['usuario']);
                      $usuario -> setSenha($_POST['senha']);
                      $usuarioDAO = new UsuarioDAO();
                      if ($usuarioDAO->logar($usuario)) {
                        // echo "<script>window.location.href ='content/index.php'</script>";
                        //header("Location: content/index.php");
                        if (isset($_COOKIE['usuarioSessionUrl'])) {
                          $url = $_COOKIE['usuarioSessionUrl'];
                          unset($_COOKIE['usuarioSessionUrl']); 
                          setcookie('usuarioSessionUrl', null, -1, '/'); 
                          printf("<script>window.location.href ='%s'</script>", $url);  
                        } else {
                          echo "<script>window.location.href ='content/index.php'</script>";
                        }
                      } else {
                        printf('<p>Erro: Usuario/Senha incorreto(s).</p>');
                      }
                    }
                    ?>
                  <div class="form-group">
                    <label class="label">Usuário</label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="usuario">
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mx-auto fas fa-user"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="label">Senha</label>
                    <div class="input-group">
                      <input type="password" class="form-control" name="senha">
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mx-auto fas fa-lock"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary submit-btn btn-block">Login</button>
                  </div>
                  <!-- <div class="form-group d-flex justify-content-between">
                    <div class="form-check form-check-flat mt-0">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked> Keep me signed in </label>
                    </div>
                    <a href="#" class="text-small forgot-password text-black">Forgot Password</a>
                  </div> --> 
                  <!-- <div class="form-group">
                    <button class="btn btn-block g-login">
                      <img class="mr-3" src="../../assets/images/file-icons/icon-google.svg" alt="">Log in with Google</button>
                  </div> -->
                  <!-- <div class="text-block text-center my-3">
                    <span class="text-small font-weight-semibold">Not a member ?</span>
                    <a href="register.html" class="text-black text-small">Create new account</a>
                  </div> -->
                </form>
                <ul class="auth-footer">
                  <!-- <li>
                    <a href="https://www.foxthree.com.br/contato" target="_blank">Ajuda</a>
                  </li> -->
                </ul>
                <p class="footer-text text-white text-center">copyright © 2021 <a href="https://www.foxthree.com.br" target="_blank">FoxThree</a>.</p>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <script>

      $(document).ready(function () {
        $('.input-group-append').click(function () {
          $(this).prev().focus();
        });

        $('.form-control').on('focus', function () {
          $(this).next().find('.input-group-text').addClass('active');
        });

        $('.form-control').on('blur', function () {
          if (($(this).attr('name') == 'senha') || ($(this).attr('name') == 'usuario') && $(this).val() == '') {
            $(this).next().find('.input-group-text').removeClass('active');
          }
        });
      });

    </script>
  </body>
</html>