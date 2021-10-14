<?php
  date_default_timezone_set("America/Sao_Paulo");
  require_once ("../logado.php");
	@$pg = $_GET['pg'];
	@$lc = $_GET['lc'];
	if (!isset($pg))
		$pagina_load = "home.php";
	else {
		if (isset($lc))
			$pagina_load = $lc . "/" . $pg . ".php";
		else
			$pagina_load = $pg . ".php";
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Responsive sidebar template with sliding effect and dropdown menu based on bootstrap 3">
  <title>Italínea Class - Painel Administrativo</title>

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="../../css/bootstrap.min.css" rel="stylesheet">
  <link href="../../css/style.css" rel="stylesheet">
  <link href="../../css/hover.css" rel="stylesheet">
  
  <!-- JQUERY -->
  <script src="../../js/jquery-3.5.0.min.js"></script>
  <script type="text/javascript" src="../../js/jquery.form.js"></script>
  <!-- <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
  
  <!-- JQUERY UI -->
  <script src="../../js/jquery-ui.min.js"></script>
  <link href="../../css/jquery-ui.min.css" rel="stylesheet">

  <!-- BOOTSTRAP -->
  <script src="../../js/bootstrap.bundle.min.js"></script>
  
  
  <!-- FONTAWESOME -->
  <!-- <script src="https://kit.fontawesome.com/2fab917988.js" crossorigin="anonymous"></script> -->
  <!-- <script src="https://kit.fontawesome.com/688cfe5e94.js" crossorigin="anonymous"></script> -->
  <link href="../../../assets/css/fontawesome.css" rel="stylesheet">

  <!-- DATEPICKER -->
  <script src="../../js/datepicker/js/bootstrap-datepicker.min.js"></script>
  <link href="../../js/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <script src="../../js/datepicker/js/bootstrap-datepicker.pt-BR.min.js"></script>

  <!-- BOOTSTRAP SELECT -->
  <script src="../../js/bootstrap-select.min.js"></script>
  <script src="../../js/bootstrap-select-pt-br.min.js"></script>
  <link href="../../css/bootstrap-select.min.css" rel="stylesheet">

  <!-- FANCYBOX -->
  <script src="../../js/jquery.fancybox.min.js"></script>
  <link href="../../css/jquery.fancybox.min.css" rel="stylesheet">

  <!-- SUMMERNOTE -->
  <script src="../../js/summernote-master/summernote-bs4.min.js"></script>
  <link href="../../js/summernote-master/summernote-bs4.css" rel="stylesheet">

  <script src="../../../assets/js/fox.ajaxLoad.js"></script>



  <!-- Theme included stylesheets -->
  <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <!-- <link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet"> -->
  
  <!-- Main Quill library -->
  <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
  <!-- <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script> -->

  <!-- FUNCOES.JS -->
  <script src="../../js/funcoes.js"></script>

  <!-- MASK -->
  <script src="../../js/jquery.mask.min.js"></script>
  <!-- <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script> -->    

  <link href="../../css/new.css" rel="stylesheet">


</head>

<body>
  <div class="page-wrapper chiller-theme toggled">
    <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
      <i class="fas fa-bars"></i>
    </a>
    <nav id="sidebar" class="sidebar-wrapper">
      <div class="sidebar-content">
        <div class="sidebar-brand">
          <a href="#"><img src="../../../assets/img/logo_claro.png" class="img-fluid pr-4"></a>
          <div id="close-sidebar">
            <i class="fas fa-times"></i>
          </div>
        </div>
        <div class="sidebar-header">
          <div class="user-pic">
            <img class="img-responsive img-rounded"
              src="https://raw.githubusercontent.com/azouaoui-med/pro-sidebar-template/gh-pages/src/img/user.jpg"
              alt="User picture">
          </div>
          <div class="user-info">
            <span class="user-name"><?=$nome?>
            </span>
            <span class="user-role"><?=$admin == 1 ? 'Administrador' : 'Moderador'?></span>
            <span class="user-status">
              <i class="fa fa-circle"></i>
              <span>Online</span>
            </span>
          </div>
        </div>
        <!-- sidebar-header  -->
        <!-- <div class="sidebar-search">
          <div>
            <div class="input-group">
              <input type="text" class="form-control search-menu" placeholder="Pesquisar...">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </span>
              </div>
            </div>
          </div>
        </div> -->
        <!-- sidebar-search  -->
        <div class="sidebar-menu">
          <ul>
            <li class="header-menu">
              <span>Administração</span>
            </li>
            <li class="<?=$pagina_load == 'home.php' ? 'active' : '' ?>">
              <a href="?pg=home">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="<?=$lc == 'solicitacao' ? 'active' : '' ?>">
              <a href="?pg=index&lc=solicitacao">
                <i class="far fa-address-book"></i>
                <span>Solicitações</span>
              </a>
            </li>
            <?php if ($admin == '1') { ?>
              <li class="sidebar-dropdown <?=$lc == 'pagina' || $lc == 'usuario' || $lc == 'config' ? 'active' : '' ?>">
                <a href="#">
                  <i class="fas fa-cogs"></i>
                  <span>Ajustes</span>
                  <span class="badge badge-warning">ADMIN</span>
                </a>
                <div class="sidebar-submenu" <?=$lc == 'pagina' || $lc == 'usuario' || $lc == 'config' ? 'style="display: block"' : '' ?>>
                  <ul>
                    <li class="<?=$lc == 'usuario' ? 'active' : '' ?>">
                      <a href="?pg=index&lc=usuario"><i class="fas fa-users"></i> Usuários</a>
                    </li>
                  </ul>
                </div>
              </li>
            <?php } ?>
            <li class="header-menu">
              <span>Extra</span>
            </li>
            <li>
              <a href="../../../" target="_blank">
              <i class="fas fa-link"></i>
                <span>Landing Page Italínea</span>
              </a>
            </li>
            <li>
              <a href="https://foxthree.com.br/contato.php" target="_blank">
                <i class="fas fa-link"></i>
                <span>Contato FoxThree</span>
              </a>
            </li>
          </ul>
        </div>
        <!-- sidebar-menu  -->
      </div>
      <!-- sidebar-content  -->
      <div class="sidebar-footer">
        <!-- <a href="#">
          <i class="fa fa-bell"></i>
          <span class="badge badge-pill badge-warning notification">3</span>
        </a>
        <a href="#">
          <i class="fa fa-envelope"></i>
          <span class="badge badge-pill badge-success notification">7</span>
        </a>
        <a href="#">
          <i class="fa fa-cog"></i>
          <span class="badge-sonar"></span>
        </a> -->
        <a href="../logoff.php">
          <i class="fa fa-power-off"></i>
        </a>
      </div>
    </nav>
    <!-- sidebar-wrapper  -->
    <main class="page-content">
      <div class="container-fluid animate__animated animate__backInRight animate__slow">
        <?php 
          if ($admin != '1' && ($lc == 'pagina' || $lc == 'usuario' || $lc == 'config')) {
            exit;
          }
          include($pagina_load);
        ?>
      </div>
    </main>
    <!-- page-content" -->
  </div>

  <script>

    jQuery(function ($) {

    $(".sidebar-dropdown > a").click(function() {
    $(".sidebar-submenu").slideUp(200);
    if (
    $(this)
      .parent()
      .hasClass("active")
    ) {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .parent()
      .removeClass("active");
    } else {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .next(".sidebar-submenu")
      .slideDown(200);
    $(this)
      .parent()
      .addClass("active");
    }
    });

    $("#close-sidebar").click(function() {
    $(".page-wrapper").removeClass("toggled");
    });
    $("#show-sidebar").click(function() {
    $(".page-wrapper").addClass("toggled");
    });




    });

  </script>

</body>

</html>