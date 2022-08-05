<?php
namespace App\Http\Controllers;

Class RestCurl {
  
  public static function exec($method, $url = null, $header = [], $fields = []) 
  {
     
    $ch           = curl_init();
    $curl_options = array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL            => $url,
                        CURLOPT_HTTPHEADER     => $header,
                        CURLOPT_HEADER         => TRUE,
                        CURLOPT_SSL_VERIFYPEER => TRUE,
                    );
    switch($method) {
      case 'GET':
        if(strrpos($url, "?") === FALSE) { $url .= '?' . http_build_query($fields); }
        break;
      case 'POST': 
        $curl_options[CURLOPT_POST]          = 1; 
        $curl_options[CURLOPT_POSTFIELDS]    = $fields;
        break;
      case 'PUT':
      case 'DELETE':
      default:
        $curl_options[CURLOPT_CUSTOMREQUEST] = $method; 
        $curl_options[CURLOPT_POSTFIELDS]    = $fields;
    
    }
    // Exec
    curl_setopt_array($ch, $curl_options);
    $resp = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    // Data
    // 
    // $header = substr($resp, 0, $info['header_size']);
    // dd(parseHeaders($resp));
    // $header = trim(substr($resp, 0, $info['header_size']));

    
    $header = (object)self::get_headers_from_curl_response($resp);
    $body   = substr($resp, $info['header_size']);
    return (object)array('status' => $info['http_code'], 'header' => $header, 'data' => json_decode($body));
  }

  public static function get_headers_from_curl_response($response)
  {
      $headers = array();
      $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
      foreach (explode("\r\n", $header_text) as $i => $line) {
          if ($i === 0) {
              $headers['http_code'] = $line;
          }
          else {
              list ($key, $value) = explode(': ', $line);
              $headers[$key] = $value;
          }
      }
      return $headers;
  }

  function parseHeaders( $headers )
  {
      $head = array();
      foreach( $headers as $k=>$v )
      {
          $t = explode( ':', $v, 2 );
          if( isset( $t[1] ) )
              $head[ trim($t[0]) ] = trim( $t[1] );
          else
          {
              $head[] = $v;
              if( preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out ) )
                  $head['reponse_code'] = intval($out[1]);
          }
      }
      return $head;
  }
  public static function get($url, $header, $obj = array()) 
  {
     return RestCurl::exec("GET", $url, $header, $obj);
  }
  public static function post($url, $header, $obj = array()) 
  {
     return RestCurl::exec("POST", $url, $header, $obj);
  }
  public static function put($url, $header, $obj = array()) 
  {
     return RestCurl::exec("PUT", $url, $header, $obj);
  }
  public static function delete($url, $header, $obj = array()) 
  {
     return RestCurl::exec("DELETE", $url, $header, $obj);
  }
}
?>