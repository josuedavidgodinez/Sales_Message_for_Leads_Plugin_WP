<?php

/**
 * Verify response status
 */
function OkCreateResponse( $response)
{
   $statusCode = wp_remote_retrieve_response_code($response);
   if ($statusCode != 200 && $statusCode != 201) {
      return false;
   }
   return true;
}

function getBody($response)  {
   if(OkCreateResponse($response)){
      $bodyResp = wp_remote_retrieve_body($response);
      $data = json_decode($bodyResp);
      return $data;
   }else {
      return null;
   }
}

