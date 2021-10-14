<?php
namespace Mubbi;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

use DateTime;
use User\User;

// CUSTOM FUNCTIONS
$custom_functions = array(
  'json_decode' => function ($str, $returnAsClass = false) {
    $str = stripslashes(html_entity_decode($str));
    $ret = json_decode($str, $returnAsClass);   
    return $ret;
  },
  'get_compressed_link' => function ($str) {
    $ext = explode('.', $str);
    unset($ext[sizeof($ext) - 1]); 
    return UPLOADS . 'compressed/' . implode('.', $ext) . '.jpg';
  },
  'get_upload_link' => function ($str) {
    return UPLOADS . $str;
  },
  'get_string_link' => function ($str) {
    $func = new Func();
    return $func->getStringLink($str);
  },
  'twig_print_r' => function ($arr) {
    return print_r($arr, true);
  },
  'twig_format_datetime' => function ($string, $show_hora = false, $format = 'd/m/Y H:i:s') {
    $func = new Func();
    return $func->formatDatetime($string, $show_hora, $format);
  },
  'twig_datetime' => function ($string, $format, $formatTo) {
    $date = DateTime::createFromFormat($format, $string);
    return $date->format($formatTo);
  },
  'twig_time_ago' => function ($string) {
    $func = new Func();
    return $func->timeAgo($string);
  },
  'twig_substr' => function ($string, $offset, $length, $sufix = null) {
    $contador = strlen($string);
    if ($contador >= $length) {
        $string = substr($string, 0, strrpos(substr($string, $offset, $length), ' '));
        if ($sufix !== null) $string .= $sufix;
        return $string;
    } else {
      return $string;
    }
  },
  'twig_strip_tags' => function ($string) {
    return strip_tags($string);
  },
  'twig_decode_html' => function ($string) {
    return html_entity_decode($string);
  },
  'twig_join_json' => function ($string, $separator = ", ") {
    $result = "";
    @$array = json_decode($string, true);
    if (is_array($array) && sizeof($array) > 0) {
      foreach ($array as $item) {
        if ($result != "") { $result .= $separator; }           
        $result .= $item;
      }
      return $result;
    } else {
      return $string;
    }
  }
);


$data = array(
  'assets' => HTTP_SERVER . ASSETS
);

// Registry
$registry = new Registry();
$registry->set('custom_functions', $custom_functions);

$config = new Config($registry);
$registry->set('config', $config);

$document = new Document($registry);
$registry->set('document', $document);

$request = new Request();
$registry->set('request', $request);

$url = new Url();
$registry->set('url', $url);

$response = new Response($registry);
$registry->set('response', $response);

$db = new Database(DB_TYPE, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
$registry->set('db', $db);

$session = new Session($config->get('session_engine'), $registry);
$session_id = $session->get_cookie();
$session->start($session_id);
$session->set_cookie();
$registry->set('session', $session);


$perms = new Permissions($registry);
$registry->set('permissions', $perms);

$user = new User($registry);
$registry->set('user', $user);

$loader = new Loader($data, $registry);
$registry->set('load', $loader);

$mailer = new PHPMailer(false);
$mail = new Mail($registry, $mailer);
$registry->set('mail', $mail);


// Instantiate Controller Class
$objController = new $controllerClass($registry);

// Verify Action Method
if (!method_exists($objController, $function)) {
	die('Class Method: ' . $function . ' does not exists');
}

$func = new Func($registry);
$registry->set('func', $func);

$language = new Language();
$registry->set('language', $language);

$log = new Log($registry);
$registry->set('log', $log);



// Start the App
$objController->$function();
?>