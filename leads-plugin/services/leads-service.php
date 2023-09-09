<?php
$path = __DIR__ . '/..';
require_once $path . "/api/api-calls.php";
require_once $path . "/services/tag-service.php";
require_once $path . "/services/message-service.php";
require_once $path . "/services/timezone-service.php";
require_once $path . "/services/number-service.php";

require_once $path . "/services/send-mail-service.php";
require_once $path . "/db/qty-data-service.php";

require_once $path . "/utils/response-utils.php";
require_once $path . "/env/constant-env-v.php";


/**
 * Create contact in sales message
 */
function wp_CreateContact($first_name, $last_name, $phone, $mail)
{

    $qParams=array(
        'number'=>$phone,
        'first_name'=>$first_name,
        'last_name'=>$last_name,
        'email'=>$mail
    );
    $response=wp_madePostAPI('/contacts',null,true,$qParams,endpointSalesMessage());
    if (!is_null($response)) {
        return getBody($response);
    }
    return null;
}

/**
 * Create conversation in sales message
 */
function wp_CreateConversation($contactID,$number_id,$numerable_id)
{
    $qParams=array(
        'contact_id'=>$contactID,
        'number_id'=>$number_id,
        'team_id'=>$numerable_id
    );
    $response=wp_madePostAPI('/conversations',null,true,$qParams,endpointSalesMessage());
    if (!is_null($response)) {
        return getBody($response);
    }
    return null;
}

function SearchIndexForm($form_name,$main_form_drpdown_value)  {
    $qty=getQtyLocationsTable();
    $indexSearch=null;
    for ($i=1; $i <= $qty; $i++) { 
        if (is_null($form_name)) {
            $form_location_S=get_option('main_form_drpdown_value'.$i);
            if ($main_form_drpdown_value==$form_location_S) {
                $indexSearch=$i;
                break;
            }
        }else{
            $form_name_S=get_option('elementor_form_name'.$i);
            if ($form_name==$form_name_S) {
                $indexSearch=$i;
                break;
            }
        }
    }
    return $indexSearch;
}

/**
 * initial flow for leads
 */
function wp_InitialFlowForLeads($record,$ajax_handler)
{

    //define response
    $leadsProcessResponse= new stdClass();
    //get form name
    $form_name = $record->get_form_settings('form_name');
    
    //get ids of the forms
    $IDFieldforFirstName=get_option('IDFieldforFirstName');
    $IDFieldforLastName=get_option('IDFieldforLastName');
    $IDFieldforEmail=get_option('IDFieldforEmail');
    $IDFieldforPhone=get_option('IDFieldforPhone');
    $IDFieldforDropdownSofLocations=get_option('IDFieldforDropdownSofLocations');
    //main form name
    $MainFormName=get_option('MainFormName');

    //get input fields
    $raw_fields = $record->get('fields');
    $fields = [];
    foreach ($raw_fields as $id => $field) {
        $fields[$id] = $field['value'];
    }

    //get index of the form
    $indexform=null;
    if ($MainFormName==$form_name) {
        $indexform=SearchIndexForm(null,$fields[$IDFieldforDropdownSofLocations]);
    }else{
        $indexform=SearchIndexForm($form_name,null);
    }

    //settings form enviroment
    $settings=new LocationFormSettings($indexform);
    $numberdata=wp_numberSearch_ReturnID($settings->PhoneNumber());
    error_log('number id :'.$numberdata->id);
    if (!is_null($indexform)) {
        $firstname=$fields[$IDFieldforFirstName];
        $lastname=$fields[$IDFieldforLastName];
        $email=$fields[$IDFieldforEmail];
        $phone=  $fields[$IDFieldforPhone];

        $reponseCreateContact=wp_CreateContact($firstname,$lastname,$phone,$email);
        $leadsProcessResponse->reponseCreateContact=$reponseCreateContact;
        if($reponseCreateContact){
            $reponseTagContact=wp_TagContact($reponseCreateContact->id,$indexform);
            $leadsProcessResponse->reponseTagContact=$reponseTagContact;

            //Send mail 
            $MailSubject=smsTemplate(0,$firstname,$lastname,userFirstName(),userLastName(),brandName(),$settings->websiteURL(),$settings->phoneNumber());

            $SendMail=wp_SendMail( $email,$MailSubject,smsTemplate(0,$firstname,$lastname,userFirstName(),userLastName(),brandName(),$settings->websiteURL(),$settings->phoneNumber()));
            $leadsProcessResponse->SendMail=$SendMail;

            $reponseCreateConversation=wp_CreateConversation($reponseCreateContact->id,$numberdata->id,$numberdata->numberableid);
            $leadsProcessResponse->reponseCreateConversation=$reponseCreateConversation;

            if($reponseCreateConversation){

                $landingTimezone=$settings->TimeZone();
                //FIRST SMS
                $send_at1=wp_getCurrentUTCwithAddSeconds(20);//20 sec after
                $message=smsTemplate(1,$firstname,$lastname,userFirstName(),userLastName(),brandName(),$settings->websiteURL(),$settings->phoneNumber());
                $responseFirtSMS=wp_SendMessage($reponseCreateConversation->id,$message,$send_at1,false);
                $leadsProcessResponse->responseFirtSMS=$responseFirtSMS;

                //SECOND SMS
                $send_at2=GetAdjustedTimeZonewithAddedSeconds(1,$landingTimezone);//24 hours after
                $message=smsTemplate(2,$firstname,$lastname,userFirstName(),userLastName(),brandName(),$settings->websiteURL(),$settings->phoneNumber());
                $responseSecondSMS=wp_SendMessage($reponseCreateConversation->id,$message,$send_at2,true);
                $leadsProcessResponse->responseSecondSMS=$responseSecondSMS;

                //THIRD SMS
                $send_at3=GetAdjustedTimeZonewithAddedSeconds(3,$landingTimezone);//3 days after
                $message=smsTemplate(3,$firstname,$lastname,userFirstName(),userLastName(),brandName(),$settings->websiteURL(),$settings->phoneNumber());
                $responseThirdSMS=wp_SendMessage($reponseCreateConversation->id,$message,$send_at3,true);
                $leadsProcessResponse->responseThirdSMS=$responseThirdSMS;

                //FORTH SMS
                $send_at4=GetAdjustedTimeZonewithAddedSeconds(7,$landingTimezone);//7 days after
                $message=smsTemplate(4,$firstname,$lastname,userFirstName(),userLastName(),brandName(),$settings->websiteURL(),$settings->phoneNumber());
                $responseForthSMS=wp_SendMessage($reponseCreateConversation->id,$message,$send_at4,true); 
                $leadsProcessResponse->responseForthSMS=$responseForthSMS;

                $ajax_handler->add_response_data(true, $leadsProcessResponse);
            }
            
        }


    }
}

