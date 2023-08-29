<?php

$path = __DIR__ . '/..';

require_once $path . "/utils/token-utils.php";
require_once $path . "/utils/response-utils.php";
require_once $path . "/env/constant-env-v.php";


/**
 * Create POST to the API endpoint
 */
function wp_madePostAPI(string $apiRoute, $body, bool $secured, array $Qparams = array(),$endpoint)
{
   $apiRequest= commonApiRequest($apiRoute,$secured,$body , $Qparams,$endpoint,false);
   if(is_null($apiRequest)){
      return null;
   }else {
      error_log($apiRequest['apiendpoint']);
      $response = wp_remote_post($apiRequest['apiendpoint'], $apiRequest['apiargs']);
      error_log(json_encode($response));
      return $response;
   }

}

/**
 * Create PUT to the API endpoint
 */
function wp_madePutAPI(string $apiRoute, $body, bool $secured, array $Qparams = array(),$endpoint)
{
   $apiRequest= commonApiRequest($apiRoute,$secured,$body , $Qparams,$endpoint,true);
   if(is_null($apiRequest)){
      return null;
   }else {
      error_log($apiRequest['apiendpoint']);
      $response = wp_remote_request($apiRequest['apiendpoint'], $apiRequest['apiargs']);
      error_log(json_encode($response));
      return $response;
   }

}



/**
 * Create POST to the API endpoint
 */
function wp_madeGetAPI(string $apiRoute,bool $secured , array $Qparams = array(),$endpoint)
{
   $apiRequest= commonApiRequest($apiRoute,$secured,null,$Qparams,$endpoint,false);
   if(is_null($apiRequest)){
      return null;
   }else {
      $response = wp_remote_get($apiRequest['apiendpoint'], $apiRequest['apiargs']);
      return $response;
   }

}

/**
 * Common API request params
 */
function commonApiRequest(string $apiRoute, bool $secured, $body ,array $Qparams = array(),$endpoint,$put){

   $params="";
   $index=0;
   foreach ($Qparams as $key => $value) {
      if($index==0){
         $params .= '?'.$key.'='.urlencode( $value);
      }else {
         $params .= '&'.$key.'='.urlencode( $value);      # code...
      }
      $index++;
    }
   $headers = array(
      'accept' => 'application/json',
      'Content-Type' => 'application/x-www-form-urlencoded'
   );
   if($secured){
      $token=getNoExpToken();
      if(is_null($token)){
         return null;
      }
      $headers['Authorization'] = 'Bearer '.$token;
   }

   $api_endpoint = $endpoint . $apiRoute . $params;
   $api_args = array(
      'headers' => $headers,
      'timeout' => 20
   );
   if (!is_null($body)){
      $api_args['body'] = $body;
   }

   if($put){
      $api_args['method'] = 'PUT';
   }

   return array(
      'apiargs' => $api_args,
      'apiendpoint' => $api_endpoint
   );
}