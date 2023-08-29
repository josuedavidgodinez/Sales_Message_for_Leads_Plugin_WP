<?php

/*
Plugin Name: Leads Plugin
Plugin URI:  
Description: Plugin for Leads
Version:     1.0
Author:      Josue Godinez
Author URI:  
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

require_once "services/login-service.php";
require_once "services/leads-service.php";

function leads_plugin_settings()
{
    add_menu_page(
        "leads-plugin",
        'Leads Plugin',
        'administrator',
        "leads-plugin",
        'displayAdminDashboard',
        'dashicons-testimonial',
        20
    );
    // Add more settings fields as needed
}

function displayAdminDashboard()
{


    // Check if the form has been submitted
    if (isset($_POST['leads_plugin_submit'])) {
        // Save the settings here
        // Remember to sanitize and validate user input before saving
        $clientID = sanitize_text_field($_POST['clientID']);
        update_option('clientID', $clientID);
        $clientSecrect = sanitize_text_field($_POST['clientSecrect']);
        update_option('clientSecrect', $clientSecrect);
        $userFirstName = sanitize_text_field($_POST['userFirstName']);
        update_option('userFirstName', $userFirstName);
        $userLastName = sanitize_text_field($_POST['userLastName']);
        update_option('userLastName', $userLastName);
        $brandName = sanitize_text_field($_POST['brandName']);
        update_option('brandName', $brandName);
        $Tag = sanitize_text_field($_POST['Tag']);
        update_option('Tag', $Tag);
        $websiteURL = sanitize_text_field($_POST['websiteURL']);
        update_option('websiteURL', $websiteURL);
        $instagram = sanitize_text_field($_POST['instagram']);
        update_option('instagram', $instagram);
        $phoneNumber = sanitize_text_field($_POST['phoneNumber']);
        update_option('phoneNumber', $phoneNumber);
        $timeZone = sanitize_text_field($_POST['timeZone']);
        update_option('timeZone', $timeZone);
        $minAvailableHour = sanitize_text_field($_POST['minAvailableHour']);
        update_option('minAvailableHour', $minAvailableHour);
        $maxAvailableHour = sanitize_text_field($_POST['maxAvailableHour']);
        update_option('maxAvailableHour', $maxAvailableHour);
        $mailSubject = sanitize_text_field($_POST['mailSubject']);
        update_option('mailSubject', $mailSubject);
        echo '<div class="updated"><p>Settings saved!</p></div>';
    }

    // Retrieve the saved setting value
    $savedclientID = get_option('clientID');
    $savedclientSecrect = get_option('clientSecrect');
    $saveduserFirstName = get_option('userFirstName');
    $saveduserLastName = get_option('userLastName');
    $savedbrandName = get_option('brandName');
    $savedTag = get_option('Tag');
    $savedwebsiteURL = get_option('websiteURL');
    $savedinstagram = get_option('instagram');
    $savedphoneNumber = get_option('phoneNumber');
    $savedtimeZone = get_option('timeZone');
    $savedminAvailableHour = get_option('minAvailableHour');
    $savedmaxAvailableHour = get_option('maxAvailableHour');
    $savedmailSubject = get_option('mailSubject');

    


    // Display the settings page content
?>
    <div class="wrap">
        <h1>Leads Plugin Settings</h1>

        <p>
            <b>
                For use this Plugin is important to install the following plugins:
            </b>
        </p>
        <ul>
            <li>
                WP Mail SMTP - https://wpmailsmtp.com
            </li>
        </ul>
        <p>
            <b>
               And following this tuturial of configuration GMAIL SMTP in WP using the Method 1 https://www.wpbeginner.com/plugins/how-to-send-email-in-wordpress-using-the-gmail-smtp-server/
            </b>
        </p>
        <form method="post" action="">
            <table class="table-form">
                <tr>
                    <th style="text-align: left;"><label for="clientID">Client ID:</label></th>
                    <td><input type="text" name="clientID" id="clientID" value="<?php echo esc_attr($savedclientID); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="clientSecrect">Client Secrect:</label></th>
                    <td><input type="text" name="clientSecrect" id="clientSecrect" value="<?php echo esc_attr($savedclientSecrect); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="userFirstName">User First Name:</label></th>
                    <td><input type="text" name="userFirstName" id="userFirstName" value="<?php echo esc_attr($saveduserFirstName); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="userLastName">User Last Name:</label></th>
                    <td><input type="text" name="userLastName" id="userLastName" value="<?php echo esc_attr($saveduserLastName); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="brandName">Brand Name:</label></th>
                    <td><input type="text" name="brandName" id="brandName" value="<?php echo esc_attr($savedbrandName); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="Tag">Tag:</label></th>
                    <td><input type="text" name="Tag" id="Tag" value="<?php echo esc_attr($savedTag); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="websiteURL">Website URL:</label></th>
                    <td><input type="text" name="websiteURL" id="websiteURL" value="<?php echo esc_attr($savedwebsiteURL); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="instagram">Instagram:</label></th>
                    <td><input type="text" name="instagram" id="instagram" value="<?php echo esc_attr($savedinstagram); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="phoneNumber">Phone Number:</label></th>
                    <td><input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo esc_attr($savedphoneNumber); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="timeZone">Time Zone (Example: GMT-5):</label></th>
                    <td><input type="text" name="timeZone" id="timeZone" value="<?php echo esc_attr($savedtimeZone); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="minAvailableHour">Min. Available Hour for contact client:</label></th>
                    <td><input type="text" name="minAvailableHour" id="minAvailableHour" value="<?php echo esc_attr($savedminAvailableHour); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="maxAvailableHour">Max. Available Hour for contact client:</label></th>
                    <td><input type="text" name="maxAvailableHour" id="maxAvailableHour" value="<?php echo esc_attr($savedmaxAvailableHour); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="mailSubject">Mail Subject:</label></th>
                    <td><input type="text" name="mailSubject" id="mailSubject" value="<?php echo esc_attr($savedmailSubject); ?>">
                        <br>
                        <br>
                    </td>
                </tr>
            </table>
            <input type="submit" name="leads_plugin_submit" class="button-primary" value="Save Settings">

        </form>
    </div>
<?php
}



function handleLeads($record, $ajax_handler)
{
    wp_InitialFlowForLeads($record, $ajax_handler);
}

add_action('elementor_pro/forms/new_record', 'handleLeads', 10, 2);
// Hook into the admin menu
add_action('admin_menu', 'leads_plugin_settings');

?>