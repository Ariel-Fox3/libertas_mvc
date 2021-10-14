
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require('meta.php')?>

  <title>Lixo Zero - Garopaba Sustentável</title>
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <script src="assets/js/jquery-3.5.1.min.js"></script>
  <script src="assets/js/jquery.form.js"></script>
  <script src="assets/js/jquery.mask.min.js"></script>
  <script src="assets/js/sweetalert2.all.min.js"></script>
  <link href="assets/css/sweetalert2.min.css">

  <script src="https://kit.fontawesome.com/d55f1e4280.js" crossorigin="anonymous"></script>

  <script type="text/javascript">
      // var onloadCallback = function() {
      //   grecaptcha.render('recaptcha_id', {
      //     'sitekey' : '6Lf0jQQcAAAAADSOvzqQZhrivFbDPAY-HCBqED4i'
      //   });
      // };
    </script>
</head>
<body>



<footer>
  <div class="container-fluid py-5">
    <div class="container">
      <h2>Informações, críticas e sugestões</h2>
      <div class="item">
        <div class="icone"><i class="far fa-envelope"></i></div>
        <span>contato@garopabalixozero.com.br</span>
      </div>
      <div class="item">
        <div class="icone"><i class="fab fa-instagram"></i></div>
        garopaba.lixo.zero
      </div>
    </div>
  </div>
  <div class="container-fluid rodape">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-6 py-3 text-center text-md-left">
          <span>Copyright @2021 Coletivo Garopaba Lixo Zero</span>
        </div>
        <div class="col-12 col-md-6 py-3 text-center text-md-right">
          <span>Um projeto Zao desenvolvido pela Fox3. Todos os direitos reservados.</span>
        </div>
      </div>
    </div>
  </div>
</footer>


  <script>

    $(".btn-abaixa").click(function() {
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#suplementos").offset().top - 180
        }, 2000);
    });

   function onSubmit1(token) {
    //  document.getElementById("form1").submit();
    var f1 = $('#form1');
    var b1 = $('#submitForm1');
    
    f1.ajaxForm({
      beforeSend : function() {
        b1.attr('disabled', 'disabled');
      },
      success : function(e) {
        var res = JSON.parse(e);
        if (res.erro == false) {
          Swal.fire({
            icon: 'success',
            title: 'Contato enviado',
            text: res.msg
          });
          b1.removeAttr('disabled');
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Erro ao enviar contato',
            text: res.msg
          });
          b1.removeAttr('disabled');
        }
      },
      error : function(e) {
        Swal.fire({
          icon: 'error',
          title: 'Erro ao enviar contato',
          text: e
        });
        b1.removeAttr('disabled');
      }
    });
    f1.submit();
   }

   function onSubmit2(token) {
    //  document.getElementById("form2").submit();
    var f2 = $('#form2');
    var b2 = $('#submitForm2');
    
    f2.ajaxForm({
      beforeSend : function() {
        b2.attr('disabled', 'disabled');
      },
      success : function(e) {
        var res = JSON.parse(e);
        if (res.erro == false) {
          Swal.fire({
            icon: 'success',
            title: 'Contato enviado',
            text: res.msg
          });
          b2.removeAttr('disabled');
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Erro ao enviar contato',
            text: res.msg
          });
          b2.removeAttr('disabled');
        }
      },
      error : function(e) {
        Swal.fire({
          icon: 'error',
          title: 'Erro ao enviar contato',
          text: e
        });
        b2.removeAttr('disabled');
      }
    });
    f2.submit();
   }

   $(document).ready(function () {
    var SPMaskBehavior = function (val) {
      return val.replace(/\D/g, '').length === 11 ? '(00) 0 0000-0000' : '(00) 0000-00009';
    },
    spOptions = {
      onKeyPress: function(val, e, field, options) {
        field.mask(SPMaskBehavior.apply({}, arguments), options);
      }
    };

    $('[name="inputTelefone"]').mask(SPMaskBehavior, spOptions);
   });
 </script>

  <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
      async defer>
  </script>
</body>
</html>