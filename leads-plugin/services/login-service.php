<?php
$path = __DIR__ . '/..';
require_once $path . "/api/api-calls.php";
require_once $path . "/env/constant-env-v.php";
require_once $path . "/env/constant-env-v.php";

/**
 * login to the API sales message service
 */
function wp_getDatafromLogin()
{
   $client_id = clientIDSalesMessage();
   $client_secret = clientSECRETSalesMessage();
   $grant_type = "client_credentials";

   $bodyReq = 
       array(
         "client_id" => $client_id,
         "client_secret" => $client_secret,
         "grant_type" => $grant_type

      );
   $response = wp_madePostAPI('/oauth/token',$bodyReq,false,array(),endpointSalesMessage());
   if(!is_null($response)){
      if(OkCreateResponse($response)){
         $bodyResp = wp_remote_retrieve_body($response);
         $data = json_decode($bodyResp);
         $_SESSION['token'] = $data->access_token;
      }
   }

}