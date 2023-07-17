<?php
/*
Plugin Name:     Front-end Registration Form
Plugin URI:     http://wpschoolpress.com
Description:    Add-on that allows students and teachers to register themselves from website front-end.
Version:         1.0
Author:         WPSchoolPress Team
Author URI:     wpschoolpress.com
Text Domain:    WPSchoolPress
Domain Path:    languages
License: GNU General Public License v2.0 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
@package WPSchoolPressPro
 */

function edd_sample_check_license3()
{

    global $wp_version;
    global $wpdb;
    $wpsp_settings_table = $wpdb->prefix . "wpsp_settings";
    $wpsp_settings_edit  = $wpdb->get_results("SELECT * FROM $wpsp_settings_table");
    foreach ($wpsp_settings_edit as $sdat) {

        $settings_data[$sdat->option_name] = $sdat->option_value;

    }

    if (!defined('WPSPaddon_PLUGIN_URL')) {
        define('WPSPaddon_PLUGIN_URL', plugin_dir_url(__FILE__));
    }
    if (!defined('WPSPaddon_PLUGIN_PATH')) {
        define('WPSPaddon_PLUGIN_PATH', plugin_dir_path(__FILE__));
    }
    if (!defined('WPSPaddon_PLUGIN_VERSION')) {
        define('WPSPaddon_PLUGIN_VERSION', '1.0'); //Plugin version number
    }

    add_action('plugins_loaded', 'WPSPaddon_plugins_loaded', 999);
    function WPSPaddon_plugins_loaded()
    {
        $wpsp_lang_dir = dirname(plugin_basename(__FILE__)) . '/languages/';
        load_plugin_textdomain('WPSchoolPressaddons', false, $wpsp_lang_dir);

        require_once WPSPaddon_PLUGIN_PATH . 'includes/login-register.php';
        require_once WPSPaddon_PLUGIN_PATH . 'includes/wpsp-misc.php';

        global $wpsp_settings_data, $wpspaddon, $wpspsetting, $wpsp_addon_version, $wpsp_addon_version;
        // $wpsp_addon_version = new wpsp_addon_version();
        // if (!class_exists('Wpsp_Admin')) {
        //     add_action('admin_notices', array($wpsp_addon_version, 'wpspaddon_plugin_admin_notice'));
        // }

    }
    // class wpsp_addon_version
    // {
    //     public function __construct()
    //     {
    //         add_filter('pre_set_site_transient_update_plugins', array($this, 'wpspaddon_update_check'));

    //     }
    //     function wpspaddon_plugin_admin_notice()
    //     {
    //         echo '<div class="updated"><p> <strong> To Use  Front-end register, Please Activate WPSchoolPress Plugin</strong></p></div>';
    //         if (isset($_GET['activate'])) {
    //             unset($_GET['activate']);
    //         }

    //     }

    //     function wpspaddon_update_check($transient)
    //     {
    //         // Check if the transient contains the 'checked' information
    //         // If no, just return its value without hacking it
    //         if (empty($transient->checked)) {
    //             return $transient;
    //         }

    //         $plugin_path = plugin_basename(__FILE__);
    //         // POST data to send to your API
    //         $args = array(
    //             'referrer' => get_site_url(),
    //             'code'     => get_option('wpsp-lcode'),
    //         );
    //         $response         = $this->wpspaddon_updateInfo($args);
    //         $response         = json_decode($response, true);
    //         $obj              = (object) array();
    //         $obj->slug        = 'wpschoolpress-addon';
    //         $obj->new_version = $response['new_version'];
    //         $obj->tested      = $response['tested'];
    //         $obj->url         = $response['url'];
    //         $obj->package     = $response['package'];
    //         // If there is a new version, modify the transient
    //         if (version_compare($response['new_version'], $transient->checked[$plugin_path], '>')) {
    //             $transient->response[$plugin_path] = $obj;
    //         }

    //         return $transient;
    //     }
    //     function wpspaddon_updateInfo($args)
    //     {
    //         // Send request
    //         $request = wp_remote_post('http://wpschoolpress.com/update/sms', array('method' => 'POST', 'body' => $args));
    //         if (is_wp_error($request) || 200 != wp_remote_retrieve_response_code($request)) {
    //             return false;
    //         }

    //         return wp_remote_retrieve_body($request);
    //     }
    // }
}
add_action('plugins_loaded', 'edd_sample_check_license3', 0);
add_action('wp_ajax_AddStudent', 'wpsp_AddStudent');