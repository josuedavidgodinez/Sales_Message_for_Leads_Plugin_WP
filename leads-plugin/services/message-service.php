<?php
$path = __DIR__ . '/..';
require_once $path . "/api/api-calls.php";
require_once $path . "/env/constant-env-v.php";

/**
 * send message in sales message
 */
function wp_SendMessage($conversationID,$message,$send_at,$stop_on_response)
{

    $qParams=array(
        'message'=>$message
        );
        
    if(!is_null($send_at)){
        $qParams['send_at'] = $send_at;
    }

    if($stop_on_response){
        $qParams['stop_on_response'] = "1";
    }
    $response=wp_madePostAPI("/messages/".$conversationID,null,true,$qParams,endpointSalesMessage());
    if (!is_null($response)) {
        return getBody($response);
    }
    return null;
    
}

