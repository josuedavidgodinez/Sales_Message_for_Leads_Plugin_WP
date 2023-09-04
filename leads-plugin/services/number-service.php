<?php
$path = __DIR__ . '/..';
require_once $path . "/api/api-calls.php";
require_once $path . "/env/constant-env-v.php";

/**
 * number search
 */
function wp_numberSearch_ReturnID($number)
{
    $qParams = array(
        'query' => $number,
        'page' => 1,
        'limit' => 1
    );
    $response = wp_madeGetAPI("/numbers/", true, $qParams, endpointSalesMessage());
    if (!is_null($response)) {
        $body = getBody($response);
        error_log(json_encode($body));
        if (property_exists($body, 'data')) {
            if (count($body->data) > 0) {
                $numberdata=new stdClass();
                $numberdata->id = $body->data[0]->id;
                $numberdata->numberableid = $body->data[0]->numberable->id;
                return $numberdata;
            }
            return null;
        }
    }
}
