<?php

class LocationFormSettings
{
    public $indexform;


    // Constructor method
    public  function __construct($indexform)
    {
        $this->indexform = $indexform;
    }

   
    public function Tag()
    {
        return get_option('Tag'.$this->indexform);
    }


    public function websiteURL()
    {
        return get_option('websiteURL'.$this->indexform);
    }


    public function PhoneNumber()
    {
        return get_option('phoneNumber'.$this->indexform);
    }

    //VALID TIMEZONE VALUES GMT-4 GMT-5 GMT-6 GMT-7 GMT-8 GMT-9 GMT-10 
    public function TimeZone()
    {
        return get_option('timeZone'.$this->indexform);
    }

   
}

 //GLOBAL SETTINGS
 // ----------------------------------------------------------------
    //RETURN ENDPOINT OF SALES MESSAGE
     function endpointSalesMessage()
    {
        return "https://api.salesmessage.com/pub/v2.1";
    }

     function clientIDSalesMessage()
    {
        return get_option('clientID');
    }


     function clientSECRETSalesMessage()
    {
        return get_option('clientSecrect');
    }

     function userFirstName()
    {
        return get_option('userFirstName');
    }

     function userLastName()
    {
        return get_option('userLastName');
    }

     function brandName()
    {
        return get_option('brandName');
    }

    function instagram()
    {
        return get_option('instagram');
    }

     function RangeAvailableHours()
    {
        $range = new stdClass();
        $range->minHour = intval(get_option('minAvailableHour'));
        $range->maxHour = intval(get_option('maxAvailableHour'));
        return $range;
    }
     function mailSubject()
    {
        return get_option('mailSubject');
    }

    
    function mailMsg() {
        return get_option('mailMsg');
    }
    
    function firstSMSMsg() {
        return get_option('firstSMSMsg');
    }
    
    function secondSMSMsg() {
        return get_option('secondSMSMsg');
    }
    
    function thirdSMSMsg() {
        return get_option('thirdSMSMsg');
    }
    
    function fourthSMSMsg() {
        return get_option('fourthSMSMsg');
    }

     function smsTemplate($numberOfNotification, $contactFirstName, $contactLastName,$userFirstName, $userLastName, $brandName, $websiteURL, $phoneNumber)
    {
        $message = "";
        switch ($numberOfNotification) {
             
            //MAIL
            case -1:
                $message = mailSubject();
                break;
            case 0:
                $message = mailMsg();
                break;
            case 1:
                $message = firstSMSMsg();
                break;
            case 2:
                $message = secondSMSMsg();
                break;
            case 3:
                $message = thirdSMSMsg();
                break;
            case 4:
                $message = fourthSMSMsg();
                break;
        }
        $message = str_replace('{CONTACT FIRSTNAME}', $contactFirstName, $message);
        $message = str_replace('{CONTACT LASTNAME}', $contactLastName, $message);
        $message = str_replace('{USER FIRSTNAME}', $userFirstName, $message);
        $message = str_replace('{USER LASTNAME}', $userLastName, $message);
        $message = str_replace('{INSTAGRAM}', instagram(), $message);
        $message = str_replace('{BRAND NAME}', $brandName, $message);
        $message = str_replace('{WEBSITE URL}', $websiteURL, $message);
        $message = str_replace('{PHONE NUMBER}', $phoneNumber, $message);
        return $message;
    }

