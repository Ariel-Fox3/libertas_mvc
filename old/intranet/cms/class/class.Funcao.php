<?php
function isMail($email){
    $er = "/^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$/";
    if (preg_match($er, $email)){
	return true;
    } else {
	return false;
    }
}
function validaemail($email){
	if(!ereg('^([a-zA-Z0-9.-_])*([@])([a-z0-9]).([a-z]{2,3})',$email)){
		return false;
  } else {
	  $dominio = explode('@', $email);
		if(!checkdnsrr($dominio[1],'A')){
			return false;
		} else {
      return true;
    }
	}
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

function formatData($data) {
  $dataNova = explode(" ", $data);
  $dataNova2 = explode("-", $dataNova[0]);
  return $dataNova2[2]."/".$dataNova2[1]."/".$dataNova2[0];
}

 function embedYoutube($text)
{
	$search = '~
        # Match non-linked youtube URL in the wild. (Rev:20130823)
        (?:https?://)?    # Optional scheme.
        (?:[0-9A-Z-]+\.)? # Optional subdomain.
        (?:               # Group host alternatives.
          youtu\.be/      # Either youtu.be,
        | youtube         # or youtube.com or
          (?:-nocookie)?  # youtube-nocookie.com
          \.com           # followed by
          \S*             # Allow anything up to VIDEO_ID,
          [^\w\s-]        # but char before ID is non-ID char.
        )                 # End host alternatives.
        ([\w-]{11})       # $1: VIDEO_ID is exactly 11 chars.
        (?=[^\w-]|$)      # Assert next char is non-ID or EOS.
        (?!               # Assert URL is not pre-linked.
          [?=&+%\w.-]*    # Allow URL (query) remainder.
          (?:             # Group pre-linked alternatives.
            [\'"][^<>]*>  # Either inside a start tag,
          | </a>          # or inside <a> element text contents.
          )               # End recognized pre-linked alts.
        )                 # End negative lookahead assertion.
        [?=&+%\w.-]*      # Consume any URL (query) remainder.
        ~ix';

	$replace = '<object width="425" height="344">
        <param name="movie" value="http://www.youtube.com/v/$1?fs=1"</param>
        <param name="allowFullScreen" value="true"></param>
        <param name="allowScriptAccess" value="always"></param>
        <embed src="http://www.youtube.com/v/$1?fs=1"
            type="application/x-shockwave-flash" allowscriptaccess="always" width="100%" height="400">
        </embed>
        </object>';

	return preg_replace($search, $replace, $text);
}
function embedVimeo($text) {
  preg_match('/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/', $text, $matches);
  $id = $matches[2];
  $width = '400';
  $height = '300';
  return '<div class="vimeo-article"><iframe src="http://player.vimeo.com/video/'.$id.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
}
 function embedYoutube2($text)
{
  $search = '~
        # Match non-linked youtube URL in the wild. (Rev:20130823)
        (?:https?://)?    # Optional scheme.
        (?:[0-9A-Z-]+\.)? # Optional subdomain.
        (?:               # Group host alternatives.
          youtu\.be/      # Either youtu.be,
        | youtube         # or youtube.com or
          (?:-nocookie)?  # youtube-nocookie.com
          \.com           # followed by
          \S*             # Allow anything up to VIDEO_ID,
          [^\w\s-]        # but char before ID is non-ID char.
        )                 # End host alternatives.
        ([\w-]{11})       # $1: VIDEO_ID is exactly 11 chars.
        (?=[^\w-]|$)      # Assert next char is non-ID or EOS.
        (?!               # Assert URL is not pre-linked.
          [?=&+%\w.-]*    # Allow URL (query) remainder.
          (?:             # Group pre-linked alternatives.
            [\'"][^<>]*>  # Either inside a start tag,
          | </a>          # or inside <a> element text contents.
          )               # End recognized pre-linked alts.
        )                 # End negative lookahead assertion.
        [?=&+%\w.-]*      # Consume any URL (query) remainder.
        ~ix';

  $replace = '<object width="425" height="344">
        <param name="movie" value="http://www.youtube.com/v/$1?fs=1"</param>
        <param name="allowFullScreen" value="true"></param>
        <param name="allowScriptAccess" value="always"></param>
        <embed src="http://www.youtube.com/v/$1?fs=1"
            type="application/x-shockwave-flash" allowscriptaccess="always" width="400" height="300">
        </embed>
        </object>';

  return preg_replace($search, $replace, $text);
}
 function embedLink($text)
{
  $search = '~
        # Match non-linked youtube URL in the wild. (Rev:20130823)
        (?:https?://)?    # Optional scheme.
        (?:[0-9A-Z-]+\.)? # Optional subdomain.
        (?:               # Group host alternatives.
          youtu\.be/      # Either youtu.be,
        | youtube         # or youtube.com or
          (?:-nocookie)?  # youtube-nocookie.com
          \.com           # followed by
          \S*             # Allow anything up to VIDEO_ID,
          [^\w\s-]        # but char before ID is non-ID char.
        )                 # End host alternatives.
        ([\w-]{11})       # $1: VIDEO_ID is exactly 11 chars.
        (?=[^\w-]|$)      # Assert next char is non-ID or EOS.
        (?!               # Assert URL is not pre-linked.
          [?=&+%\w.-]*    # Allow URL (query) remainder.
          (?:             # Group pre-linked alternatives.
            [\'"][^<>]*>  # Either inside a start tag,
          | </a>          # or inside <a> element text contents.
          )               # End recognized pre-linked alts.
        )                 # End negative lookahead assertion.
        [?=&+%\w.-]*      # Consume any URL (query) remainder.
        ~ix';

  $replace = '<u><a href="https://www.youtube.com/watch?v=$1" target="_blank">https://www.youtube.com/watch?v=$1</a></u>';

  return preg_replace($search, $replace, $text);
}
 function embedImg($text)
{
  $search = '~
        # Match non-linked youtube URL in the wild. (Rev:20130823)
        (?:https?://)?    # Optional scheme.
        (?:[0-9A-Z-]+\.)? # Optional subdomain.
        (?:               # Group host alternatives.
          youtu\.be/      # Either youtu.be,
        | youtube         # or youtube.com or
          (?:-nocookie)?  # youtube-nocookie.com
          \.com           # followed by
          \S*             # Allow anything up to VIDEO_ID,
          [^\w\s-]        # but char before ID is non-ID char.
        )                 # End host alternatives.
        ([\w-]{11})       # $1: VIDEO_ID is exactly 11 chars.
        (?=[^\w-]|$)      # Assert next char is non-ID or EOS.
        (?!               # Assert URL is not pre-linked.
          [?=&+%\w.-]*    # Allow URL (query) remainder.
          (?:             # Group pre-linked alternatives.
            [\'"][^<>]*>  # Either inside a start tag,
          | </a>          # or inside <a> element text contents.
          )               # End recognized pre-linked alts.
        )                 # End negative lookahead assertion.
        [?=&+%\w.-]*      # Consume any URL (query) remainder.
        ~ix';

  $replace = 'http://img.youtube.com/vi/$1/hqdefault.jpg';

  return preg_replace($search, $replace, $text);
}

function compress_image($source_url, $destination_url, $quality) {
  $info = getimagesize($source_url);

  if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
  elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
  elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);

  //save it
  imagejpeg($image, $destination_url, $quality);

  //return destination file url
  return $destination_url;
}


function timeAgo($time_ago)
{
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

?>
