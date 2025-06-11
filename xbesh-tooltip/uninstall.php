<?php
// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options
delete_option('xbesh_tooltip_enabled');
delete_option('xbesh_tooltip_position');
