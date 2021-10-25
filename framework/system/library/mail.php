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
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        
        $mail->Host = 'smtp.uni5.net';  // Specify main and backup SMTP servers
        $mail->Username = 'suporte@fox3.com.br';                 // SMTP username
        $mail->Password = 'f0x@2020';                           // SMTP password

        // $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        // $mail->Username = 'contato@libertasconsultoria.com';                 // SMTP username
        // $mail->Password = 'libertas2020';                           // SMTP password

        //Recipients
        // $mail->setFrom('contato@libertasconsultoria.com', 'Libertas Consultoria');
        $mail->setFrom('libertasconsultoria@foxthree.com.br', 'Libertas Consultoria');
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