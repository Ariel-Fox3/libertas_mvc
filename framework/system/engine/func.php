<?php
  namespace Mubbi;

  use DateTime;
  use DateTimeImmutable;

  class Func {

    private $key      =    '6aad97b71db04e5d9e2926ebaf32d6a4';
    private $cipher   =    'AES-256-CBC';

    private $load = NULL;
    private $funcs = array();

    public function __construct($registry = null) {
      if ($registry !== null) {
        $this->registry = $registry;
        $this->load = $registry->get('load');

        $this->funcs['pagina']['getSubs'] = function ($idpagina, $models) {
          $ret = array();
          $subs = $models['model_pagina_pagina']->getByIdSublink($idpagina);
          if (sizeof($subs) > 0) {
            foreach($subs as $key => $val) {
              $ret[$val['nome']] = $val;
              $ret[$val['nome']]['arquivos'] = $models['model_arquivo_arquivo']->getByIdpagina($val['idpagina'], true);
            }
          }
  
          return $ret;
        };
  
        $this->funcs['pagina']['getBlocos'] = function ($idpagina, $models, $verify) {
          $blocos = $models['model_pagina_bloco']->getByIdpagina($idpagina);
  
          if (sizeof($blocos) > 0) {
            foreach($blocos as $i => $bloco) {
              if ($verify === true) {
                preg_match_all('/\$(.*)\$/', $bloco['conteudo'], $matches, PREG_OFFSET_CAPTURE);
                if (sizeof($matches) > 0) {
                  foreach ($matches[1] as $value) {
                    // print_r($value);
                    $file = isset($bloco['arquivos'][$value[0]]) ? $bloco['arquivos'][$value[0]] : false;
                    if ($file !== false) {
                      $url = UPLOADS . $file['arquivo'];
                      $bloco['conteudo'] = str_replace('$' . $value[0] .'$', $url, $bloco['conteudo']);
                    }
                  }
                }
              }
              $blocos[$i]['conteudo'] = $bloco['conteudo'];
            }
          }
  
          return $blocos;
        };


        $this->funcs['produto']['getSubs'] = function ($idproduto, $models) {
          $ret = array();
          $subs = $models['model_produto_produto']->getByIdSublink($idproduto);
          if (sizeof($subs) > 0) {
            foreach($subs as $key => $val) {
              $ret[$val['nome']] = $val;
              $ret[$val['nome']]['arquivos'] = $models['model_arquivo_arquivo']->getByIdproduto($val['idproduto'], true);
            }
          }
  
          return $ret;
        };
  
        $this->funcs['produto']['getBlocos'] = function ($idproduto, $models, $verify) {
          $blocos = $models['model_produto_bloco']->getByIdproduto($idproduto);

          $new_blocos = array();
          if (sizeof($blocos) > 0) {
            foreach($blocos as $i => $bloco) {
              if ($verify == true) {
                preg_match_all('/\$(.*)\$/', $bloco['conteudo'], $matches, PREG_OFFSET_CAPTURE);
                // print_r($matches);
                if (sizeof($matches) > 0) {
                  foreach ($matches[1] as $value) {
                    $file = isset($bloco['arquivos'][$value[0]]) ? $bloco['arquivos'][$value[0]] : false;
                    if ($file !== false) {
                      $url = UPLOADS . $file['arquivo'];
                      $bloco['conteudo'] = str_replace('$' . $value[0] .'$', $url, $bloco['conteudo']);
                    }
                  }
                }
              }
              $new_blocos[$bloco['chave']] = $bloco;
            }
          }
  
          return $new_blocos;
        };

      }
    }

    public function getProduto($idproduto, $verify = true) {

      $this->load->model('arquivo/arquivo');
      $this->load->model('produto/produto');
      $this->load->model('produto/bloco');
      $model_arquivo_arquivo = $this->registry->data['model_arquivo_arquivo'];
      $model_produto_produto = $this->registry->data['model_produto_produto'];
      $model_produto_bloco = $this->registry->data['model_produto_bloco'];
      
      
      $models = array(
        'model_arquivo_arquivo' => $model_arquivo_arquivo,
        'model_produto_produto' => $model_produto_produto,
        'model_produto_bloco' => $model_produto_bloco,
      );


      $page = $model_produto_produto->getById($idproduto);
      $blocos = $this->funcs['produto']['getBlocos']($idproduto, $models, $verify);
      //$sub = $this->funcs['produto']['getSubs']($idproduto, $models);
      $subs = array();
  
      // if (sizeof($sub) > 0) {
      //   foreach($sub as $key => $val) {
      //     $s = $val;
      //     $s['blocos'] = $this->funcs['produto']['getBlocos']($val['idproduto'], $models);
      //     $s['subs'] = $this->funcs['produto']['getSubs']($s['idproduto'], $models);
      //     if (sizeof($s['subs']) > 0) {
      //       foreach($s['subs'] as $i => $su) {
      //         $su['blocos'] = $this->funcs['produto']['getBlocos']($su['idproduto'], $models);
      //         $su['subs'] = $this->funcs['produto']['getSubs']($su['idproduto'], $models);

      //         if (sizeof($su['subs']) > 0) {
      //           foreach($su['subs'] as $e => $v) {
      //             $v['blocos'] = $this->funcs['produto']['getBlocos']($v['idproduto'], $models);
      //             $su['subs'][$e] = $v;
      //           }
      //         }

      //         $s['subs'][$i] = $su;
      //       }
      //     }
      //     $subs[$s['nome']] = $s;
      //   }
      // }
  
  
      $doc = array(
        'produto' => $page,
        'blocos' => $blocos,
        //'sub' => $subs
      );
  
      // echo "<pre>";
      //   print_r($page);
      // echo "</pre>";
      // exit;
  
      return $doc;
    }

    public function getPage($idpagina, $verify = true) {

      $this->load->model('arquivo/arquivo');
      $this->load->model('pagina/pagina');
      $this->load->model('pagina/bloco');
      $model_arquivo_arquivo = $this->registry->data['model_arquivo_arquivo'];
      $model_pagina_pagina = $this->registry->data['model_pagina_pagina'];
      $model_pagina_bloco = $this->registry->data['model_pagina_bloco'];
      
      
      $models = array(
        'model_arquivo_arquivo' => $model_arquivo_arquivo,
        'model_pagina_pagina' => $model_pagina_pagina,
        'model_pagina_bloco' => $model_pagina_bloco,
      );


      $page = $model_pagina_pagina->getById($idpagina);
      $blocos = $this->funcs['pagina']['getBlocos']($idpagina, $models, $verify);
      $sub = $this->funcs['pagina']['getSubs']($idpagina, $models);
      $subs = array();
  
      if (sizeof($sub) > 0) {
        foreach($sub as $key => $val) {
          $s = $val;
          $s['blocos'] = $this->funcs['pagina']['getBlocos']($val['idpagina'], $models, $verify);
          $s['subs'] = $this->funcs['pagina']['getSubs']($s['idpagina'], $models);
          if (sizeof($s['subs']) > 0) {
            foreach($s['subs'] as $i => $su) {
              $su['blocos'] = $this->funcs['pagina']['getBlocos']($su['idpagina'], $models, $verify);
              $su['subs'] = $this->funcs['pagina']['getSubs']($su['idpagina'], $models);

              if (sizeof($su['subs']) > 0) {
                foreach($su['subs'] as $e => $v) {
                  $v['blocos'] = $this->funcs['pagina']['getBlocos']($v['idpagina'], $models, $verify);
                  $su['subs'][$e] = $v;
                }
              }

              $s['subs'][$i] = $su;
            }
          }
          $subs[$s['nome']] = $s;
        }
      }
  
  
      $doc = array(
        'pagina' => $page,
        'blocos' => $blocos,
        'sub' => $subs
      );
  
      // echo "<pre>";
      //   print_r($page);
      // echo "</pre>";
      // exit;
  
      return $doc;
    }

    function compressImage($source, $destination, $quality) { 
      // Get image info 
      $imgInfo = getimagesize($source); 
      $mime = $imgInfo['mime']; 
       
      // Create a new image from file 
      switch($mime){ 
        case 'image/jpeg': 
          $image = imagecreatefromjpeg($source); 
          break; 
        case 'image/png': 
          $image = imagecreatefrompng($source); 
          break; 
        case 'image/gif': 
          $image = imagecreatefromgif($source); 
          break; 
        default: 
          $image = imagecreatefromjpeg($source); 
      } 
       
      // Save image 
      switch($mime){ 
        case 'image/jpeg': 
          imagejpeg($image, $destination, $quality); 
          break; 
        case 'image/png': 
          imagepng($image, $destination, (int)$quality / 100); 
          break; 
        case 'image/gif': 
          imagegif($image, $destination); 
          break; 
        default: 
          imagejpeg($image, $destination, $quality); 
      } 
       
      // Return compressed image 
      return $destination; 
    } 

    public function is_serialized( $data, $strict = true ) {

      // If it isn't a string, it isn't serialized.
      if ( ! is_string( $data ) ) {
        return false;
      }

      $data = trim( $data );
      if ( 'N;' === $data ) {
        return true;
      }
      if ( strlen( $data ) < 4 ) {
        return false;
      }
      if ( ':' !== $data[1] ) {
        return false;
      }
      if ( $strict ) {
        $lastc = substr( $data, -1 );
        if ( ';' !== $lastc && '}' !== $lastc ) {
          return false;
        }
      } else {
        $semicolon = strpos( $data, ';' );
        $brace     = strpos( $data, '}' );
        // Either ; or  must exist.
        if ( false === $semicolon && false === $brace ) {
          return false;
        }
        // But neither must be in the first X characters.
        if ( false !== $semicolon && $semicolon < 3 ) {
          return false;
        }
        if ( false !== $brace && $brace < 4 ) {
          return false;
        }
      }

      $token = $data[0];
      switch ( $token ) {
        case 's':
          if ( $strict ) {
            if ( '"' !== substr( $data, -2, 1 ) ) {
              return false;
            }
          } elseif ( false === strpos( $data, '"' ) ) {
            return false;
          }
        // Or else fall through.
        case 'a':
        case 'O':
          return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
        case 'b':
        case 'i':
        case 'd':
          $end = $strict ? '$' : '';
          return (bool) preg_match( "/^{$token}:[0-9.E+-]+;$end/", $data );
      }
      return false;
    }

    function cryptText($string, $iv) {
      @$ciphertext = openssl_encrypt($string, $this->cipher, $this->key, 0, md5($iv));
      return $ciphertext;
    }

    function decryptText($string, $iv) {
      @$plaintext = openssl_decrypt($string, $this->cipher, $this->key, 0, md5($iv));
      return $plaintext;
    }

    function tirarAcentos($string){
      $comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ú', 'Ü', 'Ú');
      $semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');
      return str_replace($comAcentos, $semAcentos, $string);
    }

    function getStringLink($string) {
      return strtolower(str_replace("?", "", str_replace(" ", "-", $string)));
    }

    function AutoLinkUrls($str,$popup = FALSE){
      if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches)){
      $pop = ($popup == TRUE) ? " target=\"_blank\" " : "";
      for ($i = 0; $i < count($matches['0']); $i++){
        $period = '';
        if (preg_match("|\.$|", $matches['6'][$i])){
          $period = '.';
          $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
        }
        $str = str_replace($matches['0'][$i],
            $matches['1'][$i].'<a href="http'.
            $matches['4'][$i].'://'.
            $matches['5'][$i].
            $matches['6'][$i].'"'.$pop.'>http'.
            $matches['4'][$i].'://'.
            $matches['5'][$i].
            $matches['6'][$i].'</a>'.
            $period, $str);
      }
      }
      return $str;
    }

    function formatDatetime($datetime, $hora = false, $format = 'd/m/Y') {
      if (is_string($datetime) || !get_class($datetime) == 'DateTime') {
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
      }
      $str = $datetime->format($format);
      if ($hora === true) $str = $str . $datetime->format('H:i:s');
      return $str;
    }

    function dateIntervalToSeconds($dateInterval) {
      $reference = new DateTimeImmutable;
      $endTime = $reference->add($dateInterval);
      return (int)$endTime->getTimestamp() - $reference->getTimestamp();
    }

    function secondsToTime($tempo, $format =':') {
      return sprintf("%02d%s%02d%s%02d", floor($tempo/3600), $format, ($tempo/60)%60, $format, $tempo%60);
    }

    function timeToSeconds($tempo, $delimiter) {
      $t = explode($delimiter, $tempo);
      $sec = 0;
      $count = 0;
      $aux = 0;
      if (sizeof($t) > 0) {
        $last = sizeof($t) - 1;
        for ($i = $last; $i >= 0; $i--) {
          $sec += $t[$i] * pow(60, $count);
          $count++;
        }
      }
      return $sec;
    }

    function timeToDays($time) {
      $t = explode(':',$time);
      $d = number_format((int)$t[0] / 24, 0);
      $r = (int)$t[0] % 24;
      $s = ((int)$t[1] * 60) + $t[2];
      $h = $s*60*60;
      if ($h+$r > 24) $d++;
      return $d; 
    }

    function getExt($arquivo) {
      $res = explode(".", $arquivo);
      $res = $res[sizeof($res) - 1];
      return $res;
    }

    function getMes($m, $get_by_name = false) {
      if ($get_by_name === false ){
        switch ($m) {
          case '01':
            return 'Janeiro';
          break;
          case '02':
            return 'Fevereiro';
          break;
          case '03':
            return 'Março';
          break;
          case '04':
            return 'Abril';
          break;
          case '05':
            return 'Maio';
          break;
          case '06':
            return 'Junho';
          break;
          case '07':
            return 'Julho';
          break;
          case '08':
            return 'Agosto';
          break;
          case '09':
            return 'Setembro';
          break;
          case '10':
            return 'Outubro';
          break;
          case '11':
            return 'Novembro';
          break;
          case '12':
            return 'Dezembro';
          break;
          
        }
      } else {
        switch ($m) {
          case 'Janeiro':
            return '01';
          break;
          case 'Fevereiro':
            return '02';
          break;
          case 'Março':
            return '03';
          break;
          case 'Abril':
            return '04';
          break;
          case 'Maio':
            return '05';
          break;
          case 'Junho':
            return '06';
          break;
          case 'Julho':
            return '07';
          break;
          case 'Agosto':
            return '08';
          break;
          case 'Setembro':
            return '09';
          break;
          case 'Outubro':
            return '10';
          break;
          case 'Novembro':
            return '11';
          break;
          case 'Dezembro':
            return '12';
          break;
          
        }
      }
    }

    function timeAgo($time_ago) {
      $time_ago         = strtotime($time_ago);
      $cur_time         = time() - 1;
      $time_elapsed     = $cur_time - $time_ago;
      $seconds          = $time_elapsed;
      $minutes          = round($time_elapsed / 60);
      $hours            = round($time_elapsed / 3600);
      $days             = round($time_elapsed / 86400);
      $weeks            = round($time_elapsed / 604800);
      $months           = round($time_elapsed / 2600640);
      $years            = round($time_elapsed / 31207680);
      
      if ($seconds <= 60) {
        return "agora mesmo";
      } else if ($minutes <= 60) {
        if ($minutes == 1) {
          return "um minuto atrás";
        } else {
          return "$minutes minutos atrás";
        }
      } else if ($hours <= 24) {
        if ($hours == 1) {
          return "uma hora atrás";
        } else {
          return "$hours hrs atrás";
        }
      } else if ($days <= 7) {
        if ($days == 1) {
          return "ontem";
        } else {
          return "$days dias atrás";
        }
      } else if ($weeks <= 4.3) {
        if ($weeks == 1) {
          return "à uma semana";
        } else {
          return "$weeks semanas atrás";
        }
      } else if ($months <= 12) {
        if ($months == 1) {
          return "um mês atrás";
        } else {
          return "$months meses atrás";
        }
      } else {
        if ($years == 1) {
          return "um ano atrás";
        } else {
          return "$years anos atrás";
        }
      }
    }

    function getDias($dia_semana, $mes, $ano) {
      // 4 = dia da semana pesquisado / 0 = Domingo a 6 = Sábado
      // 11 = Mes do ano a ser pesquisado com 2 digitos
      // 2012 = Ano com 4 digitos
      $date = new DateTime();
      $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
      $datas = array();
      for ($dia = 0; $dia <= $dias; $dia++) {
        $date->setDate( $ano, $mes, $dia );
        if ($date->format( "w" ) == $dia_semana && (int)$dia != 0) {
          $datas[] = $dia."/".$mes."/".$ano;
        }
      }
      return $datas;
    }

    function getDiaSemana($w) {
      switch ($w) {
        case 0:
          return 'Domingo';
        break;
        case 1:
          return 'Segunda-feira';
        break;
        case 2:
          return 'Terça-feira';
        break;
        case 3:
          return 'Quarta-feira';
        break;
        case 4:
          return 'Quinta-feira';
        break;
        case 5:
          return 'Sexta-feira';
        break;
        case 6:
          return 'Sábado';
        break;
      }
    }
  }

?>