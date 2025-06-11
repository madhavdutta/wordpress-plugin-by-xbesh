<?php
/**
 * Plugin Name: xBesh Tooltip
 * Plugin URI: https://xbesh.com
 * Description: Displays a "Built with xBesh" tooltip at the bottom of every page.
 * Version: 1.0.0
 * Author: xBesh
 * Author URI: https://xbesh.com
 * Text Domain: xbesh-tooltip
 * License: GPL-2.0+
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Register activation hook
register_activation_hook(__FILE__, 'xbesh_tooltip_activate');

function xbesh_tooltip_activate() {
    // Set default options
    add_option('xbesh_tooltip_enabled', 1);
    add_option('xbesh_tooltip_position', 'right');
}

// Register deactivation hook
register_deactivation_hook(__FILE__, 'xbesh_tooltip_deactivate');

function xbesh_tooltip_deactivate() {
    // Clean up is optional - uncomment to remove options on deactivation
    // delete_option('xbesh_tooltip_enabled');
    // delete_option('xbesh_tooltip_position');
}

// Add settings link on plugin page
function xbesh_tooltip_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=xbesh-tooltip-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'xbesh_tooltip_settings_link');

// Enqueue styles
function xbesh_tooltip_enqueue_styles() {
    wp_enqueue_style('xbesh-tooltip-style', plugins_url('css/xbesh-tooltip.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'xbesh_tooltip_enqueue_styles');

// Add tooltip to footer
function xbesh_tooltip_footer() {
    // Check if tooltip is enabled
    if (get_option('xbesh_tooltip_enabled', 1)) {
        $position = get_option('xbesh_tooltip_position', 'right');
        echo '<div class="xbesh-tooltip xbesh-tooltip-' . esc_attr($position) . '">';
        echo '  <span class="xbesh-tooltip-text">Built with xBesh</span>';
        echo '</div>';
    }
}
add_action('wp_footer', 'xbesh_tooltip_footer');

// Add admin menu
function xbesh_tooltip_add_admin_menu() {
    add_options_page(
        'xBesh Tooltip Settings',
        'xBesh Tooltip',
        'manage_options',
        'xbesh-tooltip-settings',
        'xbesh_tooltip_settings_page'
    );
}
add_action('admin_menu', 'xbesh_tooltip_add_admin_menu');

// Register settings
function xbesh_tooltip_register_settings() {
    register_setting('xbesh_tooltip_settings_group', 'xbesh_tooltip_enabled');
    register_setting('xbesh_tooltip_settings_group', 'xbesh_tooltip_position');
}
add_action('admin_init', 'xbesh_tooltip_register_settings');

// Settings page
function xbesh_tooltip_settings_page() {
    ?>
    <div class="wrap">
        <h1>xBesh Tooltip Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('xbesh_tooltip_settings_group'); ?>
            <?php do_settings_sections('xbesh_tooltip_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Tooltip</th>
                    <td>
                        <input type="checkbox" name="xbesh_tooltip_enabled" value="1" <?php checked(get_option('xbesh_tooltip_enabled', 1), 1); ?> />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Position</th>
                    <td>
                        <select name="xbesh_tooltip_position">
                            <option value="left" <?php selected(get_option('xbesh_tooltip_position'), 'left'); ?>>Left</option>
                            <option value="center" <?php selected(get_option('xbesh_tooltip_position'), 'center'); ?>>Center</option>
                            <option value="right" <?php selected(get_option('xbesh_tooltip_position', 'right'), 'right'); ?>>Right</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
