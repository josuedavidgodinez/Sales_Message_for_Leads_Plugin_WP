<?php
$path = __DIR__ . '/..';
require_once $path . "/api/api-calls.php";
require_once $path . "/env/constant-env-v.php";

/**
 * tag Contact
 */
function wp_TagContact($contactID,$indexform)
{
    $labelid= wp_TagSearch_ReturnID($indexform);
    if(!is_null($labelid)){
        $response=wp_madePutAPI("/tags/contact/".$contactID."/".$labelid,null,true,array(),endpointSalesMessage());
        if (!is_null($response)) {
            return getBody($response);
        }
    }
    return null;
}

/**
 * tag search
 */
function wp_TagSearch_ReturnID($indexform)
{
    $settings = new LocationFormSettings($indexform);
    $qParams=array(
        'term'=>$settings->Tag(),
        'per_page'=>50,
        'page'=>1
    );
    $response=wp_madeGetAPI("/tags/search",true,$qParams,endpointSalesMessage());
    if (!is_null($response)) {
        $body= getBody($response);
        if(property_exists($body, 'results')){
            if(count($body->results)>0){
                if ($body->results[0]->label==$settings->Tag()) {
                    $tagid=$body->results[0]->tag_id;
                    return $tagid;
                }else{
                    return wp_CreateTag($indexform);
                }
            }else{
                return wp_CreateTag($indexform);
            }
        }
        return null;
    } 
}

/**
 * Create Tag
 */
function wp_CreateTag($indexform)
{
    $settings = new LocationFormSettings($indexform);

    $qParams=array(
        'label'=>$settings->Tag()
    );
    $response=wp_madePostAPI("/tags",null,true,$qParams,endpointSalesMessage());
    if (!is_null($response)) {
        $body= getBody($response);
        if(property_exists($body, 'id')){
            return $body->id;
        }
        return null;
    } 
}