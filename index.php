<?php
/**
 * Plugin Name: Plugin Dependencies for ClassicPress
 * Description: Reads the "requires plugins" headers in plugins and shows a warning if a dependency is missing.
 * Requires PHP: 8.3
 * Author: Double-E Design
 * Plugin URI: https://github.com/doubleedesign/classicpress-plugin-dependencies
 * Author URI: https://www.doubleedesign.com.au
 * Version: 0.0.1
 * Text domain: classicpress-plugin-dependencies
 */

include __DIR__ . '/vendor/autoload.php';
use Doubleedesign\ClassicPress\PluginDependencies\PluginEntrypoint;

new PluginEntrypoint();

function activate_classicpress_plugin_dependencies(): void {
	PluginEntrypoint::activate();
}
function deactivate_classicpress_plugin_dependencies(): void {
	PluginEntrypoint::deactivate();
}
function uninstall_classicpress_plugin_dependencies(): void {
	PluginEntrypoint::uninstall();
}
register_activation_hook(__FILE__, 'activate_classicpress_plugin_dependencies');
register_deactivation_hook(__FILE__, 'deactivate_classicpress_plugin_dependencies');
register_uninstall_hook(__FILE__, 'uninstall_classicpress_plugin_dependencies');
