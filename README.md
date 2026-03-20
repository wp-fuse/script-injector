# Script Injector

Script Injector is a lightweight WordPress plugin that allows you to easily insert custom scripts (like Google Analytics, Facebook Pixel, Google Tag Manager, etc.) into your website's header, body, or footer.

## Features

- **Header Scripts**: Insert scripts into the `<head>` section of your site.
- **Body Scripts**: Insert scripts immediately after the opening `<body>` tag.
- **Footer Scripts**: Insert scripts into the `<footer>` section of your site.
- **Permission Check**: Only users with `manage_options` capability can access the settings page.
- **Clean Execution**: Scripts are injected using standard WordPress hooks (`wp_head`, `wp_body_open`, `wp_footer`).

## Performance

This plugin is engineered for minimal overhead:

- **Admin/front-end code isolation**: Admin code (settings, menus, rendering) is never loaded on public pages.
- **Single DB query**: Only one `get_option` call per request on the front-end.
- **Early bail-out**: If no scripts are configured, the plugin exits immediately — zero hooks registered.
- **Conditional hooks**: Only hooks for non-empty script fields are registered.
- **Zero global functions**: All logic uses closures, avoiding namespace pollution.

## Installation

1. Upload the `script-injector` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Access the settings via **Settings → Scripts**.

## How to use

1. Go to the Scripts settings page.
2. Paste your script code (e.g., `<script>...</script>`) into the corresponding field (Header, Body, or Footer).
3. Click "Save Changes".

## Requirements

- WordPress 5.2+ (for `wp_body_open` support)
- PHP 7.0+
