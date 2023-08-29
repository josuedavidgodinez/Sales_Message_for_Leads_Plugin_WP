<?php
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
function Tag()
{
    return get_option('Tag');
}


function websiteURL()
{
    return get_option('websiteURL');
}

function instagram()
{
    return get_option('instagram');
}


function PhoneNumber()
{
    return get_option('phoneNumber');
}

//VALID TIMEZONE VALUES GMT-4 GMT-5 GMT-6 GMT-7 GMT-8 GMT-9 GMT-10 
function TimeZone()
{
    return get_option('timeZone');
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

function smsTemplate($numberOfNotification, $contactName, $userFirstName, $userLastName, $brandName, $websiteURL, $phoneNumber)
{
    $message = "";
    switch ($numberOfNotification) {
            //MAIL
        case 0:
            $message = "
            <div>
            <h3>
            Hey there {CONTACT NAME}
            </h3>
            <p>Firstly, a massive CONGRATS on your engagement! 🎉 The {BRAND NAME} crew and I are thrilled at the thought of capturing your big day.
            <br>
            I'm {USER FIRSTNAME}, your go-to guy for booking, and our team of photo and video ninjas have created countless timeless memories for couples just like you. Check out our work and the magic we offer here: {WEBSITE URL}
            <br>
            To make things even more exciting, we're currently running a special promotion: Book within 48 hours and receive a FREE engagement session! It's our way of saying thank you for choosing Love in Focus. Don't miss out on this opportunity to capture beautiful moments before your wedding day.
            <br>
            Now, let's talk about your big day. Have you set a date? Booked a venue? If yes, fantastic! 🎊 If not, no worries, but we kindly request that you secure those details before we can move forward.
            <br>
            Once you've got that, let's chat! You can book a call with me using my CALENDAR or simply text me your questions at {PHONE NUMBER}. I promise, I'm all thumbs and ready to text back!
            <br>
            For a dash of wedding inspiration and to see what we've been up to, check us out on {INSTAGRAM}. You might find ideas that resonate with your vision for your special day.
            <br>
            Here's to the start of an exciting journey. We're eagerly awaiting your response!
            
            Stay awesome,
            </p>
            <b>{USER FIRSTNAME} {USER LASTNAME}
            <br>
            {BRAND NAME}
            <br>
            {PHONE NUMBER}
            </b>
            </div>

           ";
            break;
        case 1:
            $message = "Hey {CONTACT NAME}, it's {USER FIRSTNAME} from {BRAND NAME}!📸 Just shot you an email. Excited to chat about your big day! If you've nailed down a date and venue, would you mind sharing? Also, make sure to check your email for more information on how you can get a FREE engagement shoot. Can't wait to hear from you! - {USER FIRSTNAME} {USER LASTNAME}";
            break;
        case 2:
            $message = "Hey {CONTACT NAME}, it's {USER FIRSTNAME} from {BRAND NAME}!📸 Just checking in as I haven't heard from you. Got a moment to share your wedding date and venue? Really excited to discuss how we can capture your special day! - {USER FIRSTNAME} {USER LASTNAME}";
            break;
        case 3:
            $message = "Hey {CONTACT NAME}, it's {USER FIRSTNAME} from {BRAND NAME}!📸 Just wanted to pop into your messages one more time. We're super excited about the possibility of being a part of your special day. When you're ready to talk wedding magic, I'm here!";
            break;
        case 4:
            $message = "Hey {CONTACT NAME}, it's {USER FIRSTNAME} from {BRAND NAME}. Just a final check-in to see if you're still interested in discussing your wedding photo and video needs. If I don't hear back, I'll assume you're all set and won't text again. But remember, if you need anything in the future, we're here for you. Wedding planning is a marathon, not a sprint! 😊";
            break;
    }
    $message = str_replace('{CONTACT NAME}', $contactName, $message);
    $message = str_replace('{USER FIRSTNAME}', $userFirstName, $message);
    $message = str_replace('{USER LASTNAME}', $userLastName, $message);
    $message = str_replace('{INSTAGRAM}', instagram(), $message);
    $message = str_replace('{BRAND NAME}', $brandName, $message);
    $message = str_replace('{WEBSITE URL}', $websiteURL, $message);
    $message = str_replace('{PHONE NUMBER}', $phoneNumber, $message);
    return $message;
}
