# ClassicPress plugin dependency checker

WordPress provides an option for plugin authors to specify dependent plugins using the "Required plugins" header, but ClassicPress has opted not to implement this feature due to concerns such as interdependencies, version conflicts, etc.

This plugin aims to go some way towards addressing this gap by checking for dependencies and showing warnings on the plugins page.

## What it does
- Checks for required plugins for all plugins, using the "Required plugins" header
- Shows an admin notice if a dependency is missing
- For each plugin that has dependencies:
	- Shows a "has requirements" note in the plugin meta in the admin table row with a tooltip providing details
	- Shows a red "unmet requirements" note in the plugin meta in the admin table row with a tooltip providing details.

![Admin notice example](docs/screenshot-admin-notice.png)

![Plugin meta example - has dependencies notice](docs/screenshot-has-dependencies-tooltip.png)

![Plugin meta example - unmet dependencies notice](docs/screenshot-unmet-requirements-tooltip.png)

## What it doesn't do
- Prevent activation if a plugin has unmet dependencies
- Deactivate plugins if their dependencies are deactivated.
