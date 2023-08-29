<?php
$path = __DIR__ . '/..';
require_once $path . "/api/api-calls.php";
require_once $path . "/services/tag-service.php";
require_once $path . "/services/message-service.php";
require_once $path . "/services/timezone-service.php";
require_once $path . "/services/send-mail-service.php";

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
function wp_CreateConversation($contactID)
{
    $qParams=array(
        'contact_id'=>$contactID
    );
    $response=wp_madePostAPI('/conversations',null,true,$qParams,endpointSalesMessage());
    if (!is_null($response)) {
        return getBody($response);
    }
    return null;
}

/**
 * initial flow for leads
 */
function wp_InitialFlowForLeads($record,$ajax_handler)
{

    $leadsProcessResponse= new stdClass();
    $form_name = $record->get_form_settings('form_name');
    if ($form_name == 'ContactUs') {
        $raw_fields = $record->get('fields');
        $fields = [];
        foreach ($raw_fields as $id => $field) {
            $fields[$id] = $field['value'];
        }
        $firstname=$fields['firstname'];
        $lastname=$fields['lastname'];
        $email=$fields['email'];
        $phone=  $fields['phone'];

        $reponseCreateContact=wp_CreateContact($firstname,$lastname,$phone,$email);
        $leadsProcessResponse->reponseCreateContact=$reponseCreateContact;
        if($reponseCreateContact){
            $reponseTagContact=wp_TagContact($reponseCreateContact->id);
            $leadsProcessResponse->reponseTagContact=$reponseTagContact;

            //Send mail 
            $SendMail=wp_SendMail( $email,mailSubject(),smsTemplate(0,$firstname.' '.$lastname.' ',userFirstName(),userLastName(),brandName(),websiteURL(),phoneNumber()));
            $leadsProcessResponse->SendMail=$SendMail;

            $reponseCreateConversation=wp_CreateConversation($reponseCreateContact->id);
            $leadsProcessResponse->reponseCreateConversation=$reponseCreateConversation;

            if($reponseCreateConversation){

                $landingTimezone=TimeZone();
                //FIRST SMS
                $send_at1=wp_getCurrentUTCwithAddSeconds(120);//2 minutes after
                $message=smsTemplate(1,$firstname.' '.$lastname.' ',userFirstName(),userLastName(),brandName(),websiteURL(),phoneNumber());
                $responseFirtSMS=wp_SendMessage($reponseCreateConversation->id,$message,$send_at1,false);
                $leadsProcessResponse->responseFirtSMS=$responseFirtSMS;

                //SECOND SMS
                $send_at2=GetAdjustedTimeZonewithAddedSeconds(1,$landingTimezone);//24 hours after
                $message=smsTemplate(2,$firstname.' '.$lastname.' ',userFirstName(),userLastName(),brandName(),websiteURL(),phoneNumber());
                $responseSecondSMS=wp_SendMessage($reponseCreateConversation->id,$message,$send_at2,true);
                $leadsProcessResponse->responseSecondSMS=$responseSecondSMS;

                //THIRD SMS
                $send_at3=GetAdjustedTimeZonewithAddedSeconds(3,$landingTimezone);//3 days after
                $message=smsTemplate(3,$firstname.' '.$lastname.' ',userFirstName(),userLastName(),brandName(),websiteURL(),phoneNumber());
                $responseThirdSMS=wp_SendMessage($reponseCreateConversation->id,$message,$send_at3,true);
                $leadsProcessResponse->responseThirdSMS=$responseThirdSMS;

                //FORTH SMS
                $send_at4=GetAdjustedTimeZonewithAddedSeconds(7,$landingTimezone);//7 days after
                $message=smsTemplate(4,$firstname.' '.$lastname.' ',userFirstName(),userLastName(),brandName(),websiteURL(),phoneNumber());
                $responseForthSMS=wp_SendMessage($reponseCreateConversation->id,$message,$send_at4,true); 
                $leadsProcessResponse->responseForthSMS=$responseForthSMS;

                $ajax_handler->add_response_data(true, $leadsProcessResponse);
            }
            
        }


    }
}

