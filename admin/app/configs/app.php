<?php
// App Config
define('USE_TWIG', true);
define('USE_SSL', false);

// define('URL_PAINEL', 'http://localhost/live/painel/');
// define('HTTP_SERVER', 'http://localhost/live/libertas_mvc/admin/');
// define('HTTPS_SERVER', 'https://localhost/live/libertas_mvc/admin/');
define('URL_PAINEL', 'http://beto.fox3.com.br/painel/');
define('HTTP_SERVER', 'http://beto.fox3.com.br/_sites/libertas_mvc/admin/');
define('HTTPS_SERVER', 'https://beto.fox3.com.br/_sites/libertas_mvc/admin/');

define('OLD_SERVER', 'http://beto.fox3.com.br/beto-v2/');

if (USE_SSL == true) {
  // define('UPLOADS', 'https://localhost/live/libertas_mvc/framework/storage/uploads/');
  // define('DEFAULT_IMG_USER', 'https://localhost/live/libertas_mvc/assets/img/default-avatar.png');
  define('UPLOADS', 'https://beto.fox3.com.br/_sites/libertas_mvc/framework/storage/uploads/');
  define('DEFAULT_IMG_USER', 'https://beto.fox3.com.br/_sites/libertas_mvc/assets/img/default-avatar.png');
} else {
  // define('UPLOADS', 'http://localhost/live/libertas_mvc/framework/storage/uploads/');
  // define('DEFAULT_IMG_USER', 'http://localhost/live/libertas_mvc/assets/img/default-avatar.png');
  define('UPLOADS', 'http://beto.fox3.com.br/_sites/libertas_mvc/framework/storage/uploads/');
  define('DEFAULT_IMG_USER', 'http://beto.fox3.com.br/_sites/libertas_mvc/assets/img/default-avatar.png');
}

// DB Config
define('DB_TYPE', "mysql");
define('DB_HOST', "189.126.106.70");
define('DB_PORT', "3306");
define('DB_USER', "fox3");
define('DB_PASSWORD', "f0x1990");
define('DB_NAME', "libertas");