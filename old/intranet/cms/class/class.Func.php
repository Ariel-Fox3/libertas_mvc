<?php

function getCatConfig($config, $nome_cat) {
  if ($nome_cat === 'tudo') {
    return null;
  } else {
    $json = json_decode($config, true);
    return $json['categorias'][$nome_cat];
  }
}

function getMarcaConfig($config, $nome_marca) {
  if ($nome_marca === 'tudo') {
    return null;
  } else {
    $json = json_decode($config, true);
    return $json['marcas'][$nome_marca];
  }
}

function getPages($id_page) {
  $paginaDAO = new PaginaDAO();
  $arquivoDAO = new ArquivoDAO();
  
  $page = $paginaDAO->getById($id_page);
  $pages = $paginaDAO->getByIdsublink($id_page);
  $elementos = array();
  if (sizeof($pages) > 0) {
    foreach ($pages as $objVoP) {
      $child = $paginaDAO->getByIdsublink($objVoP->getIdpagina());
      if ($child != null && sizeof($child) > 0) {
        if (!isset($elementos[$objVoP->getNome()])) $elementos[$objVoP->getNome()] = array();
        // $e = array();
        // foreach ($child as $objVoPC) {
        //   $e[$objVoPC->getNome()] = $objVoPC;
        // }
        $elementos[$objVoP->getNome()] = $child;
      } else {
        $elementos[$objVoP->getNome()] = clone $objVoP;
      }
    }
  }
  $arquivos = $arquivoDAO->getByIdpagina($id_page, null, true);
  return array(
    'pagina'=>$page,
    'arquivos'=>$arquivos,
    'elementos'=>$elementos
  );
}

function getConfigEmail() {
  $arr = array(
    'host'=>'mail.facilitandoavenda.com.br',
    'port'=>'587',
    'user'=>'nao-responda@facilitandoavenda.com.br',
    'pass'=>'f0x1990',
    'from-name'=>'Facilitando a Venda',
    'from'=>'nao-responda@facilitandoavenda.com.br'
  );

  return $arr;
}

function emailsToSend() {
  $arr = array('gabriel@foxthree.com.br');
  return $arr;
}

function getUrl() {
  // $url = "http://".$_SERVER['HTTP_HOST']."/live/italinea-lp/";
  $url = "http://".$_SERVER['HTTP_HOST']."/_sites/italinea-lp/";
  // $url = "https://".$_SERVER['HTTP_HOST']."/";
  return $url;
}

function getUrlPainel() {
  // $url = "http://".$_SERVER['HTTP_HOST']."/live/Grokker/site/";
  $url = "http://".$_SERVER['HTTP_HOST']."/";
  return $url;
}

function arrayToObject(array $array, string $class_name){
  $r = new ReflectionClass($class_name);
  $object = $r->newInstanceWithoutConstructor();
  $list = $r->getProperties();
  foreach($list as $prop){
    $prop->setAccessible(true);
    if(isset($array[$prop->name]))
      $prop->setValue($object, $array[$prop->name]);
  } 
  return $object;
}

function dateIntervalToSeconds($dateInterval) {
  $reference = new DateTimeImmutable;
  $endTime = $reference->add($dateInterval);
  return (int)$endTime->getTimestamp() - $reference->getTimestamp();
}


function getStatusContato($nome = null, $id = null) {
  $outros = array(
    1=>"Em aberto",
    2=>"Atendido",
    3=>"Cancelado"
  );
  if ($nome != null) {
    $k = array_search($nome, $outros);
    return $k;
  } else if ($id != null){
    $n = $outros[$id];
    return $n;
  } else {
    return $outros;
  }
}

function generatePreview($video) {
    $url = "/srv/http/live/Grokker/site/courses/";
    require $url . 'vendor/autoload.php';
    $path = $url . 'uploads/videos/';
    $name = explode('/', $video);
    $name = end($name);
    $name = explode('.', $name);
    $name = $name[0] . '.png';
    
    $ffmpeg = FFMpeg\FFMpeg::create();
    $video = $ffmpeg->open($video);
    $video
        ->filters()
        ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
        ->synchronize();
    $video
        ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))
        ->save($path . $name);
    return $name;
  }


  function getConfig($cfg) {
    $configs = array(
      'fb_url'=>'https://www.facebook.com/AcademiaMoinhosFitness',
      'ig_url'=>'https://instagram.com/moinhosfitness',
      'wpp_url'=>'https://api.whatsapp.com/send?phone=5551991078245&text=Ol%C3%A1%2C%20gostaria%20de%20mais%20informa%C3%A7%C3%B5es.',
      'clube_url'=>'https://www.clubemoinhos.com.br/',
      'captcha_key'=>'6Lcj3WEUAAAAAG2jO4AhdMZ4xbEPdZ9otKSoywbO'
    );

    return $configs[$cfg];
  }

  // 'captcha_key'=>'97kOhU9jGX0_m27q3sRaWHUK902LOzu8MzooIEUGEew'
  function dias_feriados($ano = null) {
      if(empty($ano))
      {
          $ano = intval(date('Y'));
      }

      $pascoa = easter_date($ano); // Limite de 1970 ou após 2037 da easter_date PHP consulta http://www.php.net/manual/pt_BR/function.easter-date.php
      $dia_pascoa = date('j', $pascoa);
      $mes_pascoa = date('n', $pascoa);
      $ano_pascoa = date('Y', $pascoa);

      $feriados = array(
          // Tatas Fixas dos feriados Nacionail Basileiras
          mktime(0, 0, 0, 1, 1, $ano), // Confraternização Universal - Lei nº 662, de 06/04/49
          mktime(0, 0, 0, 4, 21, $ano), // Tiradentes - Lei nº 662, de 06/04/49
          mktime(0, 0, 0, 5, 1, $ano), // Dia do Trabalhador - Lei nº 662, de 06/04/49
          mktime(0, 0, 0, 9, 7, $ano), // Dia da Independência - Lei nº 662, de 06/04/49
          mktime(0, 0, 0, 10, 12, $ano), // N. S. Aparecida - Lei nº 6802, de 30/06/80
          mktime(0, 0, 0, 11, 2, $ano), // Todos os santos - Lei nº 662, de 06/04/49
          mktime(0, 0, 0, 11, 15, $ano), // Proclamação da republica - Lei nº 662, de 06/04/49
          mktime(0, 0, 0, 12, 25, $ano), // Natal - Lei nº 662, de 06/04/49

          // Essas Datas depem diretamente da data de Pascoa
          // mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48, $ano_pascoa), //2ºferia Carnaval
          mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47, $ano_pascoa), //3ºferia Carnaval
          mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2, $ano_pascoa), //6ºfeira Santa
          mktime(0, 0, 0, $mes_pascoa, $dia_pascoa, $ano_pascoa), //Pascoa
          mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60, $ano_pascoa), //Corpus Cirist

      );

      // sort($feriados);

      return $feriados;
  }

  function geraSenha($tamanho = 8, $minusculas = true, $maiusculas = true, $numeros = true, $simbolos = false) {
    $lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $simb = '!@#$%*-';
    $retorno = '';
    $caracteres = '';

    if ($minusculas) $caracteres .= $lmin;
    if ($maiusculas) $caracteres .= $lmai;
    if ($numeros) $caracteres .= $num;
    if ($simbolos) $caracteres .= $simb;

    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
      $rand = mt_rand(1, $len);
      $retorno .= $caracteres[$rand-1];
    }
    return $retorno;
  }

  function getDiaSemana($n) {
    $return = array();
    switch ($n) {
      case 'f':
        $return = array(
          'nome'=>'Feriados',
          'sigla'=>'Feriados'
        );
      break;
      case 0:
        $return = array(
          'nome'=>'Domingo',
          'sigla'=>'Dom'
        );
      break;
      case 1:
        $return = array(
          'nome'=>'Segunda feira',
          'sigla'=>'Seg'
        );
      break;
      case 2:
        $return = array(
          'nome'=>'Terça feira',
          'sigla'=>'Ter'
        );
      break;
      case 3:
        $return = array(
          'nome'=>'Quarta feira',
          'sigla'=>'Qua'
        );
      break;
      case 4:
        $return = array(
          'nome'=>'Quinta feira',
          'sigla'=>'Qui'
        );
      break;
      case 5:
        $return = array(
          'nome'=>'Sexta feira',
          'sigla'=>'Sex'
        );
      break;
      case 6:
        $return = array(
          'nome'=>'Sábado',
          'sigla'=>'Sab'
        );
      break;
    }
    return $return;
  }

  function getEstado($estado) {
    $estados = array("Acre"=>"AC",
    "Alagoas"=>"AL",
    "Amazonas"=>"AM",
    "Amapá"=>"AP",
    "Bahia"=>"BA",
    "Ceará"=>"CE",
    "Distrito Federal"=>"DF",
    "Espírito Santo"=>"ES",
    "Goiás"=>"GO",
    "Maranhão"=>"MA",
    "Mato Grosso"=>"MT",
    "Mato Grosso do Sul"=>"MS",
    "Minas Gerais"=>"MG",
    "Pará"=>"PA",
    "Paraíba"=>"PB",
    "Paraná"=>"PR",
    "Pernambuco"=>"PE",
    "Piauí"=>"PI",
    "Rio de Janeiro"=>"RJ",
    "Rio Grande do Norte"=>"RN",
    "Rondônia"=>"RO",
    "Rio Grande do Sul"=>"RS",
    "Roraima"=>"RR",
    "Santa Catarina"=>"SC",
    "Sergipe"=>"SE",
    "São Paulo"=>"SP",
    "Tocantins"=>"TO");

    $verEstado = $estados[$estado];
    return $verEstado;
  }

  function getStringLink($string) {
    $str = tirarAcentos($string);
    return strtolower(str_replace(" ", "-", $str));
  }

  function timeAgo($time_ago) {
      $time_ago = strtotime($time_ago);
      $cur_time   = time();
      $time_elapsed   = $cur_time - $time_ago;
      $seconds    = $time_elapsed ;
      $minutes    = round($time_elapsed / 60 );
      $hours      = round($time_elapsed / 3600);
      $days       = round($time_elapsed / 86400 );
      $weeks      = round($time_elapsed / 604800);
      $months     = round($time_elapsed / 2600640 );
      $years      = round($time_elapsed / 31207680 );
      // Seconds
      if($seconds <= 60){
          return "agora mesmo";
      }
      //Minutes
      else if($minutes <=60){
          if($minutes==1){
              return "um minuto atrás";
          }
          else{
              return "$minutes minutos atrás";
          }
      }
      //Hours
      else if($hours <=24){
          if($hours==1){
              return "uma hora atrás";
          }else{
              return "$hours hrs atrás";
          }
      }
      //Days
      else if($days <= 7){
          if($days==1){
              return "ontem";
          }else{
              return "$days dias atrás";
          }
      }
      //Weeks
      else if($weeks <= 4.3){
          if($weeks==1){
              return "à uma semana";
          }else{
              return "$weeks semanas atrás";
          }
      }
      //Months
      else if($months <=12){
          if($months==1){
              return "um mês atrás";
          }else{
              return "$months meses atrás";
          }
      }
      //Years
      else{
          if($years==1){
              return "um ano atrás";
          }else{
              return "$years anos atrás";
          }
      }
  }

  function tirarAcentos($string){
      $string = preg_replace("/[][><}{)(:\/;,!?*%~^`&#@]/", "", $string);
      $string = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$string);
      return $string;
  }

  function limitarTexto($texto, $limite){
    $contador = strlen($texto);
    if ( $contador >= $limite ) {
        $texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
        return $texto;
    }
    else{
      return $texto;
    }
  }

  function formataMoeda($valor) {
    return number_format($valor, 2, ',', '.'); 
  }

  function formatInt($valor) {
    if (preg_match('/\b,\b\d{2}/', $valor) || preg_match('/\b.\b\d{2}/', $valor)) {
      // NADA
    } elseif (preg_match('/\b.\b\d{1}/', $valor)) {
      $valor = $valor . '0';
    } else {
      $valor = $valor . ',00';
    }
    $value = str_replace('.', '', $valor);
    $value = str_replace(',', '', $valor);
    $value = preg_replace('/[^0-9]*/', '', $value);
    return (int)$value;
  }

  function formatData($data) {
    if (strlen($data)> 0) {
      $dataNova = explode(" ", $data);
      $dataNova2 = explode("-", $dataNova[0]);
      return $dataNova2[2]."/".$dataNova2[1]."/".$dataNova2[0];
    } else {
      return '';
    }
  }

  function formatHora($data) {
    if (strlen($data)> 0) {
      $dataNova = explode(" ", $data);
      // $dataNova2 = explode("-", $dataNova[0]);
      return $dataNova[1];
    } else {
      return '';
    }
  }

  function formatDataHora($data) {
    $dataNova = explode(" ", $data);
    $data = explode("-", $dataNova[0]);
    $hora = explode(':', $dataNova[1]);
    return $data[2]."/".$data[1]."/".$data[0].' '.$hora[0].':'.$hora[1];

  }

  function formatDataSQL($data) {
    $dataNova = explode("/", $data);
    return $dataNova[2]."-".$dataNova[1]."-".$dataNova[0];
  }

?>
