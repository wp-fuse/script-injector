# Script Injector

Script Injector is a ultra lightweight WordPress plugin that allows you to easily insert custom scripts (like Google Analytics, Facebook Pixel, Google Tag Manager, etc.) into your website's header, body, or footer.

## Features

- **Header Scripts**: Insert scripts into the `<head>` section of your site.
- **Body Scripts**: Insert scripts immediately after the opening `<body>` tag.
- **Footer Scripts**: Insert scripts into the `<footer>` section of your site.
- **Multisite Support**: Compatible with WordPress Multisite, allowing network-wide script management.
- **Clean Execution**: Scripts are injected using standard WordPress hooks (`wp_head`, `wp_body_open`, `wp_footer`).

## Installation

1. Upload the `script-injector` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Access the settings via **Settings -> Scripts** (or **Network Settings -> Scripts** for multisite installations).

## How to use

1. Go to the Scripts settings page.
2. Paste your script code (e.g., `<script>...</script>`) into the corresponding field (Header, Body, or Footer).
3. Click "Save Changes".

## Requirements

- WordPress 5.2+ (for `wp_body_open` support)
- PHP 7.0+
