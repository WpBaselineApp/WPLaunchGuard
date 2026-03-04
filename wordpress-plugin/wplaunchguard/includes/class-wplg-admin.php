<?php

if (!defined('ABSPATH')) {
    exit;
}

class WPLG_Admin
{
    private const OPTION_API_BASE = 'wplg_api_base_url';
    private const OPTION_SITE_TOKEN = 'wplg_site_token';
    private const OPTION_DEFAULT_FORM_MODE = 'wplg_default_form_mode';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function register_menu(): void
    {
        add_menu_page(
            __('WP LaunchGuard', 'wplaunchguard'),
            __('LaunchGuard', 'wplaunchguard'),
            'manage_options',
            'wplaunchguard-dashboard',
            [$this, 'render_dashboard'],
            'dashicons-shield-alt',
            65
        );

        add_submenu_page(
            'wplaunchguard-dashboard',
            __('Branding', 'wplaunchguard'),
            __('Branding', 'wplaunchguard'),
            'manage_options',
            'wplaunchguard-branding',
            [$this, 'render_branding']
        );

        add_submenu_page(
            'wplaunchguard-dashboard',
            __('Settings', 'wplaunchguard'),
            __('Settings', 'wplaunchguard'),
            'manage_options',
            'wplaunchguard-settings',
            [$this, 'render_settings']
        );
    }

    public function register_settings(): void
    {
        register_setting('wplg_settings_group', self::OPTION_API_BASE, [
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => ''
        ]);

        register_setting('wplg_settings_group', self::OPTION_SITE_TOKEN, [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => ''
        ]);

        register_setting('wplg_settings_group', self::OPTION_DEFAULT_FORM_MODE, [
            'type' => 'string',
            'sanitize_callback' => [$this, 'sanitize_form_mode'],
            'default' => 'dry-run'
        ]);
    }

    public function sanitize_form_mode(string $value): string
    {
        return in_array($value, ['dry-run', 'live'], true) ? $value : 'dry-run';
    }

    public function enqueue_assets(string $hook): void
    {
        if (strpos($hook, 'wplaunchguard') === false) {
            return;
        }
        wp_enqueue_style('wplg-admin', WPLG_PLUGIN_URL . 'assets/css/admin.css', [], WPLG_VERSION);
    }

    public function render_dashboard(): void
    {
        echo '<div class="wrap wplg-wrap">';
        echo '<h1>WP LaunchGuard</h1>';
        echo '<p>Week 1 scaffold ready. Week 2 will connect this dashboard to live scan status and controls.</p>';
        echo '<p><strong>Current mode:</strong> Bootstrap</p>';
        echo '</div>';
    }

    public function render_branding(): void
    {
        echo '<div class="wrap wplg-wrap">';
        echo '<h1>Branding</h1>';
        echo '<p>White-label fields will be connected in Week 2 to cloud branding endpoints.</p>';
        echo '<ul>';
        echo '<li>Brand name</li>';
        echo '<li>Logo URL</li>';
        echo '<li>Primary and accent colors</li>';
        echo '<li>Footer text</li>';
        echo '</ul>';
        echo '</div>';
    }

    public function render_settings(): void
    {
        ?>
        <div class="wrap wplg-wrap">
            <h1>Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('wplg_settings_group'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="wplg_api_base_url">API Base URL</label></th>
                        <td><input class="regular-text" type="url" id="wplg_api_base_url" name="wplg_api_base_url" value="<?php echo esc_attr(get_option(self::OPTION_API_BASE, '')); ?>" placeholder="https://launchguard.example.com" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wplg_site_token">Site Token</label></th>
                        <td><input class="regular-text" type="text" id="wplg_site_token" name="wplg_site_token" value="<?php echo esc_attr(get_option(self::OPTION_SITE_TOKEN, '')); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wplg_default_form_mode">Default Form Mode</label></th>
                        <td>
                            <select id="wplg_default_form_mode" name="wplg_default_form_mode">
                                <?php $mode = get_option(self::OPTION_DEFAULT_FORM_MODE, 'dry-run'); ?>
                                <option value="dry-run" <?php selected($mode, 'dry-run'); ?>>dry-run</option>
                                <option value="live" <?php selected($mode, 'live'); ?>>live</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save Settings'); ?>
            </form>
        </div>
        <?php
    }
}
