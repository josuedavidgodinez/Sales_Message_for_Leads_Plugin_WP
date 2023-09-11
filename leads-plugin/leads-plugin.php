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
require_once "db/qty-data-service.php";


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


    $settings = array(
        'clientID' => 'Client ID',
        'clientSecrect' => 'Client Secret',
        'brandName' => 'Brand Name',
        'instagram' => 'Instagram',
        'userFirstName' => 'User First Name',
        'userLastName' => 'User Last Name',
        'minAvailableHour' => 'Min. Available Hour for contact client (Example: 8)',
        'maxAvailableHour' => 'Max. Available Hour for contact client (Example: 22)',
        'mailSubject' => 'Mail Subject',
        'mailMsg' => 'Mail Message',
        'firstSMSMsg' => 'First SMS Message (2min)',
        'secondSMSMsg' => 'Second SMS Message (24 hrs)',
        'thirdSMSMsg' => 'Third SMS Message (3 days)',
        'fourthSMSMsg' => 'Fourth SMS Message (7days)',
        'MainFormName' => 'Main Form Name (Example: contactusmainform)',
        'IDFieldforFirstName' => 'ID Field for first name (Example: firstname)',
        'IDFieldforLastName' => 'ID Field for last name  (Example: lastname)',
        'IDFieldforEmail' => 'ID Field for email  (Example: email)',
        'IDFieldforPhone' => 'ID Field for phone  (Example: phone)',
        'IDFieldforDropdownSofLocations' => 'ID Field for dropdown selector of location in main form (Example: locationselector)',
        'LocationParams' => array(),
    );

    if (isset($_POST['leads_plugin_submit'])) {
        $QtyLocations = (int) $_POST['QtyLocations'];
        UpdateQtyLocationsTable($QtyLocations);
    }

    $savedQtyLocation = getQtyLocationsTable();

    $LocationsArrayParams = array();
    for ($i = 1; $i <= $savedQtyLocation; $i++) {
        array_push(
            $LocationsArrayParams,
            array(
                'location' . $i => 'Location',
                'elementor_form_name' . $i => 'Elementor Form Name (Example: contactusboston)',
                'main_form_drpdown_value' . $i => 'Value for dropdown in main form',
                'Tag' . $i => 'Tag',
                'websiteURL' . $i => 'Website URL',
                'phoneNumber' . $i => 'Phone Number (Example: (512) 456-9263)',
                'timeZone' . $i => 'Time Zone (Example: GMT-5)'
            )
        );
    }
    $settings['LocationParams'] = $LocationsArrayParams;



    if (isset($_POST['leads_plugin_submit'])) {
        //foreach setting of brand
        foreach ($settings as $key => $label) {
            //if is setting of brand set
            if ($key != 'LocationParams') {
                $value;
                if (
                    $key == 'mailSubject' ||
                    $key == 'mailMsg' ||
                    $key == 'firstSMSMsg' ||
                    $key == 'secondSMSMsg' ||
                    $key == 'thirdSMSMsg' ||
                    $key == 'fourthSMSMsg'
                ) {
                    $value = stripslashes($_POST[$key]);
                }else{
                    if($key=='Instagram'){
                        $value = (sanitize_text_field($_POST[$key]));
                    }else{
                        $value = stripslashes(sanitize_text_field($_POST[$key]));
                    }
                }
                update_option($key, $value);
            } else {
                //if is setting of location 
                foreach ($settings['LocationParams'] as $i => $LocSettings) {
                    //foreach location 
                    foreach ($LocSettings as $settingkey => $settinglabel) {
                        //foreach settings of location
                        //Search value of location setting
                        $locsettingvalue = sanitize_text_field($_POST[$settingkey]);
                        update_option($settingkey, $locsettingvalue);
                    }
                }
            }
        }

        echo '<div class="updated"><p>Settings saved!</p></div>';
    }

    $savedSettings = array();
    foreach ($settings as $key => $label) {
        //if is setting of brand set o message settings
        if ($key != 'LocationParams') {
            $savedSettings[$key] = get_option($key);
        } else {
            //if is setting of location 
            $savedlocations = array();
            foreach ($settings['LocationParams'] as $i => $LocSettings) {
                //foreach location 
                $locationsavedsettings = array();
                foreach ($LocSettings as $settingkey => $settinglabel) {
                    $locationsavedsettings[$settingkey] = get_option($settingkey);
                }
                array_push($savedlocations, $locationsavedsettings);
            }
            $savedSettings[$key] = $savedlocations;
        }
    }


    // Display the settings page content

    echo '<div class="wrap">
        <h1>Leads Plugin Settings</h1>
        <!-- Intro text here -->

        <form method="post" action="">
            <h3>Number of Locations Params</h3>
            <table class="table-form" id="brandsettings">
                <tr>
                    <th style="text-align: left;"><label for="QtyLocations">Qty. Locations</label></th>
                    <td><input type="text" name="QtyLocations" id="QtyLocations"
                            value="' . esc_attr($savedQtyLocation) . '">
                        <br><br>
                    </td>
                </tr>
            </table>
            <input type="submit" name="leads_plugin_submit" class="button-primary" value="Save Settings">

            <h3>Brand Params</h3>
            <table class="table-form" id="brandsettings">';
    foreach ($settings as $key => $label):
        if (
            $key != 'LocationParams' &&
            $key != 'mailSubject' &&
            $key != 'mailMsg' &&
            $key != 'firstSMSMsg' &&
            $key != 'secondSMSMsg' &&
            $key != 'thirdSMSMsg' &&
            $key != 'fourthSMSMsg'
        ) {

            echo ' <tr>
                            <th style="text-align: left;"><label for="' . esc_attr($key) . '">' . esc_html($label) . ':</label></th>
                            <td><input type="text" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '"
                                    value="' . esc_attr($savedSettings[$key]) . '">
                                <br><br>
                            </td>
                        </tr>';
        }
    endforeach;
    echo '</table>
    <input type="submit" name="leads_plugin_submit" class="button-primary" value="Save Settings">

    <h3>Message Params</h3>';

    echo '  <table class="table-form" id=SMSsettings">';
    foreach ($settings as $key => $label):
        if (
            $key == 'mailSubject' ||
            $key == 'mailMsg' ||
            $key == 'firstSMSMsg' ||
            $key == 'secondSMSMsg' ||
            $key == 'thirdSMSMsg' ||
            $key == 'fourthSMSMsg'
        ) {
            $rows=$key=='mailSubject'?5:15;

            echo ' <tr>
                            <th style="text-align: left;"><label for="' . esc_attr($key) . '">' . esc_html($label) . ':</label></th>
                            <td style=" width: 557px">
                            <textarea
                            style=" width: 100%" 
                            name="' . esc_attr($key) . '" 
                            id="' . esc_attr($key) . '" 
                            type="text"
                            rows="'.$rows.'" 
                            >' . esc_attr($savedSettings[$key]) . '</textarea>
                            <input type="submit" name="leads_plugin_submit" class="button-primary" value="Save Settings">
                                <br><br>          
                            </td>

                        </tr>';
        }
    endforeach;
    echo '</table>
            <input type="submit" name="leads_plugin_submit" class="button-primary" value="Save Settings">

            <h3>Locations Params</h3>
            <div style=" display: flex; justify-content: space-between;  padding: 20px; flex-wrap: wrap; ">';
    foreach ($settings['LocationParams'] as $i => $LocSettings):

        echo '<table style="border: 1px solid;" class="table-form" id="locationsettings' . esc_attr($i) . '">';
        foreach ($settings['LocationParams'][$i] as $key => $label):
            echo ' <tr>
                                <th style="text-align: left;"><label for="' . esc_attr($key) . '">' . esc_html($label) . ':</label></th>
                                <td>
                                    <input type="text" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '"
                                        value="' . esc_attr($savedSettings['LocationParams'][$i][$key]) . '">
                                    <br><br>
                                </td>
                            </tr>';
        endforeach;
        echo '</table>';
    endforeach;
    echo ' </div>

            <input type="submit" name="leads_plugin_submit" class="button-primary" value="Save Settings">
        </form>
    </div>';
}



function handleLeads($record, $ajax_handler)
{
    wp_InitialFlowForLeads($record, $ajax_handler);
}




register_activation_hook(__FILE__, 'createQtyLocationsTable');
register_deactivation_hook(__FILE__, 'DeleteQtyLocationsTable');


add_action('elementor_pro/forms/new_record', 'handleLeads', 10, 2);
// Hook into the admin menu
add_action('admin_menu', 'leads_plugin_settings');