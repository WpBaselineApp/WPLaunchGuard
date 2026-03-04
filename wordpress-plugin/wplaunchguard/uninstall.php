<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('wplg_api_base_url');
delete_option('wplg_site_token');
delete_option('wplg_default_form_mode');
