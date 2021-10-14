<?php
  namespace Mubbi;

  use Mubbi\BaseController;
  use Exception;
  use PHPMailer\PHPMailer;
  use PHPMailer\SMTP;

class Mail {
    private $registry = NULL;
    private $db = NULL;
    private $mail = NULL; 

    public function __construct($parent, $mailer){
      $this->registry = $parent;
      $this->db = $this->registry->get('db');      
      $this->mail = $mailer;
    }

    public function send_mail($to, $titulo, $html) {
      $mail = $this->mail;
      try {
        //Server settings
        $mail->CharSet = "UTF-8";
        // $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'contato@libertasconsultoria.com';                 // SMTP username
        $mail->Password = 'libertas2020';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('contato@libertasconsultoria.com', 'Libertas Consultoria');
        if (is_array($to)) {
          foreach ($to as $email) {
            $mail->addAddress($email);
          }
        } else {
          $mail->addAddress($to);
        }

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $titulo;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        // $msg = '';
        // $msg .= '<style>:root{--marrom:#4a381a}body,html{margin:0;background-color:#eee}*{font-family:"Open Sans",sans-serif}.header{width:100%;height:80px;background-color:var(--marrom);margin:0;padding:0;display:flex;justify-content:start;align-items:center}.header img{width:auto;height:60%;margin-left:15px}.container{width:100%;min-height:calc(100vh - 80px);display:flex;justify-content:center}.container .inner{width:70%;margin-top:70px;background:#fff;height:max-content;padding:40px;border-radius:15px;box-shadow:15px 15px 15px rgba(0,0,0,.2);font-size:14px}.container .inner h3{font-size:17px;text-transform:uppercase;text-align:center}.container .inner .small{font-size:12px}.container .inner .btn{text-align:center;display:block;width:100%}.container .inner .btn a{font-weight:700;background-color:var(--marrom);border-color:var(--marrom);border:1px solid;color:#fff;text-decoration:none;border-radius:5px;transition:.4s ease;padding:20px 0;display:block;max-width:350px;margin:0 auto}.container .inner a:hover{background-color:#fff;color:var(--marrom)}.container .inner .link{text-align:center;margin-bottom:0;margin-top:25px}</style>';
        // $msg .= '<div class="header">';
        //   $msg .= '<img src="http://beto.fox3.com.br/_sites/rrf_lp/assets/img/rrf-logo.png" />';
        // $msg .= '</div>';

        // $msg .= '<div class="container">';
        //   $msg .= '<div class="inner">';
        //     $msg .= '<h3>Baixe o E-book</h3>';
        //     $msg .= sprintf('<p>Olá, %s!</p>', $name);
        //     $msg .= '<p>Obrigado por se cadastrar. Aqui está sua cópia digital do e-book desenvolvido pela RRF Advogados</p>';
        //     $msg .= '<p>Este e-book apresenta a legislação mais relevante para os condomínios, com destaque para o Capítulo VII - Do Condomínio Edilício, do Código Civil Brasileiro e a Lei Nº 4.591, de 16 de dezembro de 1964.</p>';

        //     $msg .= '<p>O e-book traz ainda trechos do Código Civil relacionados ao Direito de Vizinhança, Mandatos (Procurações) e Reparação de Danos, que são reunidos em um único material para facilitar a vida de síndicos e gestores condominiais.</p>';
        //     $msg .= '<p class="small"><b>* Se você não solicitou o download, desconsidere este email.</b></p>';
        //     $msg .= '<div class="btn">';
        //       $msg .= '<a  href="https://drive.google.com/file/d/1u8h6b8ZtHzxC36-AfKTcwQL5YXkfb6o4/view?usp=sharing" target="_blank" >Baixe o E-book</a>';
        //     $msg .= '</div>';
        //   $msg .= '</div>';
        // $msg .= '</div>';
        
        $mail->Body    = $html;

        $mail->send();
        return true;
      } catch (Exception $e) {
        return $mail->ErrorInfo;
        // echo 'Message could not be sent.';
        // echo 'Mailer Error: ' . 
      }   
    }
  }

  ?>