<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once WPLG_PLUGIN_DIR . 'includes/class-wplg-admin.php';

class WPLG_Plugin
{
    private static ?WPLG_Plugin $instance = null;

    public static function instance(): WPLG_Plugin
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init(): void
    {
        new WPLG_Admin();
    }
}
