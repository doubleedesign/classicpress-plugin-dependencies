<?php

namespace Doubleedesign\ClassicPress\PluginDependencies;

class DependencyChecker {
    private array $all_requirements = [];
    private array $unmet_requirements = [];

    public function __construct() {
        add_filter('extra_plugin_headers', [$this, 'register_header']);
        add_action('admin_init', [$this, 'check_plugin_dependencies'], 10);
        add_action('admin_notices', [$this, 'display_warnings']);
        add_filter('plugin_row_meta', [$this, 'add_plugin_row_meta'], 10, 4);
    }

    public function register_header($headers) {
        $headers[] = 'Requires plugins';

        return $headers;
    }

    public function check_plugin_dependencies(): void {
        $plugins = get_plugins();

        foreach ($plugins as $plugin_file => $plugin_data) {
            if (!empty($plugin_data['Requires plugins'])) {
                $required_plugins = array_map('trim', explode(',', $plugin_data['Requires plugins']));
                foreach ($required_plugins as $required_plugin) {
                    $this->all_requirements[$plugin_file][] = $required_plugin;
                    if (!$this->is_plugin_active($required_plugin)) {
                        $this->unmet_requirements[$plugin_file][] = $required_plugin;
                    }
                }
            }
        }
    }

    public function display_warnings(): void {
        if (!empty($this->unmet_requirements)) {
            foreach (array_keys($this->unmet_requirements) as $plugin_file) {
                $message = $this->get_warning_text($plugin_file);
                if ($message) {
                    wp_admin_notice($message, ['type' => 'warning']);
                }
            }
        }
    }

    public function add_plugin_row_meta($plugin_meta, $plugin_file, $plugin_data, $status) {
        if (isset($this->all_requirements[$plugin_file])) {
            $tooltip = $this->get_info_text($plugin_file);
            $plugin_meta[] = <<<HTML
				<span class="cp-plugin-requirements" data-tippy-content="$tooltip" aria-label="This plugin has dependencies" tabindex="0">	
					<span class="dashicons dashicons-info"></span>
					Has dependencies
				</span>
			HTML;
        }

        if (isset($this->unmet_requirements[$plugin_file])) {
            $tooltip = $this->get_warning_text($plugin_file);
            $plugin_meta[] = <<<HTML
				<span class="cp-plugin-requirements cp-plugin-requirements--unmet" data-tippy-content="$tooltip" aria-label="This plugin has unmet requirements" tabindex="0">
					<span class="dashicons dashicons-warning"></span>
					Has unmet requirements
				</span>
			HTML;
        }

        return $plugin_meta;
    }

    protected function is_plugin_active($plugin_slug): bool {
        $active_plugins = get_option('active_plugins', []);
        $slugs = array_map(function($plugin_file) {
            return dirname($plugin_file);
        }, $active_plugins);

        return in_array($plugin_slug, $slugs);
    }

    protected function get_warning_text($plugin_file): ?string {
        $requirements = $this->unmet_requirements[$plugin_file] ?? [];
        if (empty($requirements)) {
            return null;
        }

        $plugin_name = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_file, false, false)['Name'] ?? $plugin_file;

        return sprintf(
            'The plugin <strong>%s</strong> requires the following plugins to be installed and activated: <strong>%s</strong>. Without %s, this plugin may not function correctly and could trigger fatal errors.',
            $plugin_name,
            implode(', ', $requirements),
            count($requirements) === 1 ? 'it' : 'them'
        );
    }

    protected function get_info_text($plugin_file): ?string {
        $requirements = $this->all_requirements[$plugin_file] ?? [];
        if (empty($requirements)) {
            return null;
        }

        $plugin_name = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_file, false, false)['Name'] ?? $plugin_file;

        return sprintf(
            'The plugin <strong>%s</strong> requires the following plugins to be installed and activated: <strong>%s</strong>.',
            $plugin_name,
            implode(', ', $requirements)
        );
    }
}
