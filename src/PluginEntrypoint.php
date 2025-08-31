<?php

namespace Doubleedesign\ClassicPress\PluginDependencies;

class PluginEntrypoint {
    private static string $version = '0.0.1';

    public function __construct() {
        add_action('admin_init', [$this, 'handle_no_classicpress'], 1);
        new DependencyChecker();
        new AssetHandler();
    }

    public function handle_no_classicpress(): void {
        if (!function_exists('classicpress_version')) {
            deactivate_plugins('classicpress-plugin-dependencies/index.php');
            add_action('admin_notices', function() {
                echo '<div class="error"><p>The ClassicPress Plugin Dependencies plugin has been deactivated because this is not a <a href="https://www.classicpress.net/" target="_blank">ClassicPress</a> site.</p></div>';
            });
        }
    }

    public static function get_version(): string {
        return self::$version;
    }

    public static function activate() {
        // Activation logic here
    }

    public static function deactivate() {
        // Deactivation logic here
    }

    public static function uninstall() {
        // Uninstallation logic here
    }
}
