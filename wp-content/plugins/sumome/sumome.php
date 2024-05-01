<?php
/*
Plugin Name: SumoMe
Plugin URI: https://sumome.com
Description: Free Tools to automate your site growth from sumome.com
Version: 1.33.1
Requires at least: 4.7
Requires PHP: 7.0
Tested up to: 6.4.1
Author: SumoMe
Author URI: https://www.sumome.com
*/

define('SUMOME__PLUGIN_DIR', plugin_dir_path(__FILE__));
const SUMOME__PLUGIN_FILE = __FILE__;

include 'classes/class_sumome.php';
$wp_plugin_sumome = new WP_Plugin_SumoMe();

register_activation_hook(__FILE__, ['WP_Plugin_SumoMe', 'activate_SumoMe_plugin']);
register_deactivation_hook(__FILE__, ['WP_Plugin_SumoMe', 'deactivate_SumoMe_plugin']);

function sumome_plugin_settings_link($links){
    $settings_link = '<a href="options-general.php?page=sumo">Settings</a>';
    array_unshift($links, $settings_link);

    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter('plugin_action_links_' . $plugin, 'sumome_plugin_settings_link');
