<?php
/**
 * Plugin Name:       Script Injector
 * Description:       Insert custom scripts into the site header, body, and footer (Google Analytics, Tag Manager, etc.)
 * Version:           1.2.0
 * Author:            wpfuse
 * Author URI:        https://wpfuse.net
 * Text Domain:       script-injector
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'script-injector', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
} );

if ( is_admin() ) {

	add_action( 'admin_init', function() {
		register_setting( 'si_settings_group', 'si_settings', [
			'sanitize_callback' => function( $input ) {
				return is_array( $input ) ? [
					'header_scripts' => $input['header_scripts'] ?? '',
					'body_scripts'   => $input['body_scripts'] ?? '',
					'footer_scripts' => $input['footer_scripts'] ?? '',
				] : [];
			},
		] );

		add_settings_section( 'si_main_section', __( 'Scripts Settings', 'script-injector' ), '', 'si_scripts' );

		$fields = [
			'header_scripts' => [ __( 'Scripts in <head>', 'script-injector' ),   __( 'Injected before </head> via wp_head — e.g. Google Tag (gtag.js)', 'script-injector' ) ],
			'body_scripts'   => [ __( 'Scripts after <body>', 'script-injector' ), __( 'Injected right after <body> via wp_body_open — e.g. GTM noscript iframe', 'script-injector' ) ],
			'footer_scripts' => [ __( 'Scripts in <footer>', 'script-injector' ), __( 'Injected before </body> via wp_footer — e.g. Facebook Pixel, chat widget', 'script-injector' ) ],
		];

		$opts = get_option( 'si_settings', [] );

		foreach ( $fields as $key => $labels ) {
			add_settings_field( "si_{$key}",
				sprintf( '%s<br><small style="color:#999;font-weight:normal;">%s</small>', esc_html( $labels[0] ), esc_html( $labels[1] ) ),
				function() use ( $key, $opts ) {
					printf(
						'<textarea name="si_settings[%s]" rows="12" class="large-text code" style="font-size:12px;">%s</textarea>',
						esc_attr( $key ),
						esc_textarea( $opts[ $key ] ?? '' )
					);
				},
				'si_scripts',
				'si_main_section'
			);
		}
	} );

	add_action( 'admin_menu', function() {
		add_submenu_page(
			'options-general.php',
			__( 'Scripts', 'script-injector' ),
			__( 'Scripts', 'script-injector' ),
			'manage_options',
			'si_scripts',
			function() {
				if ( ! current_user_can( 'manage_options' ) ) {
					wp_die( __( 'You do not have permission to access this page.', 'script-injector' ) );
				}
				echo '<div class="wrap"><h1>' . esc_html__( 'Scripts', 'script-injector' ) . '</h1>';
				echo '<form method="post" action="options.php">';
				settings_fields( 'si_settings_group' );
				do_settings_sections( 'si_scripts' );
				submit_button();
				echo '</form></div>';
			}
		);
	} );

	return;
}

// Front-end only
$si_opts = get_option( 'si_settings', [] );

if ( empty( $si_opts['header_scripts'] ) && empty( $si_opts['body_scripts'] ) && empty( $si_opts['footer_scripts'] ) ) {
	return;
}

$si_hooks = [
	'wp_head'      => 'header_scripts',
	'wp_body_open' => 'body_scripts',
	'wp_footer'    => 'footer_scripts'
];

foreach ( $si_hooks as $hook => $key ) {
	if ( ! empty( $si_opts[ $key ] ) ) {
		add_action( $hook, function() use ( $si_opts, $key ) {
			echo $si_opts[ $key ];
		}, 999 );
	}
}