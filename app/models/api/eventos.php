<?php
namespace Mubbi;

class ModelApiEventos {

  private function createUrl($url) {
    $new_url = '';
    if (strpos('clubId=', $url) === false) {
      if (strpos($url, '?') === false) {
        $new_url = $url . '?clubId=' . API_CLUBID;
      } else {
        $new_url = $url . '&clubId=' . API_CLUBID;
      }
    }
    return API_HOST . $new_url;
  }

  private function _get($url) {
    $curl = curl_init();

    $url = $this->createUrl($url);

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'x-access-token: ' . API_TOKEN
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
  }


  public function getAll() {
    return json_decode($this->_get('events'), true);
  }

  public function evento($idevento) {
    return json_decode($this->_get('events/' . $idevento), true);
  }
}
