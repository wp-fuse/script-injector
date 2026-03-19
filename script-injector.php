<?php
/**
 * Plugin Name:       Script Injector
 * Description:       Insert custom scripts into the site header, body, and footer (Google Analytics, Tag Manager, etc.).
 * Version:           1.0.1
 * Author:            wpfuse
 * Author URI:        https://wpfuse.net
 * Text Domain:       script-injector
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register settings and fields
add_action( 'admin_init', function() {
	register_setting( 'si_settings_group', 'si_settings', 'si_sanitize_settings' );

	add_settings_section(
		'si_main_section',
		__( 'Scripts Settings', 'script-injector' ),
		'', // no callback
		'si_scripts'
	);

	add_settings_field(
		'si_header_scripts',
		sprintf(
			'%s<br><small style="color:#999;font-weight:normal;">%s</small>',
			esc_html__( 'Scripts in <head>', 'script-injector' ),
			esc_html__( 'Ex. Google Tag (gtag.js)', 'script-injector' )
		),
		'si_header_field_render',
		'si_scripts',
		'si_main_section'
	);
	add_settings_field(
		'si_body_scripts',
		sprintf(
			'%s<br><small style="color:#999;font-weight:normal;">%s</small>',
			esc_html__( 'Scripts after <body>', 'script-injector' ),
			esc_html__( 'Ex. GTM noscript iframe', 'script-injector' )
		),
		'si_body_field_render',
		'si_scripts',
		'si_main_section'
	);
	add_settings_field(
		'si_footer_scripts',
		sprintf(
			'%s<br><small style="color:#999;font-weight:normal;">%s</small>',
			esc_html__( 'Scripts in <footer>', 'script-injector' ),
			esc_html__( 'Ex. Facebook Pixel or chat widget', 'script-injector' )
		),
		'si_footer_field_render',
		'si_scripts',
		'si_main_section'
	);
});


// Add configuration page to the menu
function si_admin_menu() {
	$capability = is_multisite() && is_network_admin() ? 'manage_network_options' : 'manage_options';
	$parent     = is_multisite() && is_network_admin() ? 'settings.php' : 'options-general.php';

	add_submenu_page(
		$parent,
		__( 'Scripts', 'script-injector' ),
		__( 'Scripts', 'script-injector' ),
		$capability,
		'si_scripts',
		'si_settings_page'
	);
}
add_action( 'admin_menu', 'si_admin_menu' );
add_action( 'network_admin_menu', 'si_admin_menu' );


// Render the settings page
function si_settings_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Scripts', 'script-injector' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'si_settings_group' );
			do_settings_sections( 'si_scripts' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}


// Render textarea fields
function si_header_field_render() {
	$opts  = get_option( 'si_settings', [] );
	$value = isset( $opts['header_scripts'] ) ? esc_textarea( $opts['header_scripts'] ) : '';
	printf(
		'<textarea name="si_settings[header_scripts]" rows="12" class="large-text code" style="font-size:12px;">%s</textarea>',
		$value
	);
}
function si_body_field_render() {
	$opts  = get_option( 'si_settings', [] );
	$value = isset( $opts['body_scripts'] ) ? esc_textarea( $opts['body_scripts'] ) : '';
	printf(
		'<textarea name="si_settings[body_scripts]" rows="12" class="large-text code" style="font-size:12px;">%s</textarea>',
		$value
	);
}
function si_footer_field_render() {
	$opts  = get_option( 'si_settings', [] );
	$value = isset( $opts['footer_scripts'] ) ? esc_textarea( $opts['footer_scripts'] ) : '';
	printf(
		'<textarea name="si_settings[footer_scripts]" rows="12" class="large-text code" style="font-size:12px;">%s</textarea>',
		$value
	);
}


// Sanitize saved values
function si_sanitize_settings( $input ) {
	if ( ! is_array( $input ) ) {
		return [];
	}
	return [
		'header_scripts' => $input['header_scripts'] ?? '',
		'body_scripts'   => $input['body_scripts'] ?? '',
		'footer_scripts' => $input['footer_scripts'] ?? '',
	];
}


// Output scripts on the front-end
add_action( 'wp_head', function() {
	$opts = get_option( 'si_settings', [] );
	if ( ! empty( $opts['header_scripts'] ) ) {
		echo $opts['header_scripts'];
	}
}, 999 );

add_action( 'wp_body_open', function() {
	$opts = get_option( 'si_settings', [] );
	if ( ! empty( $opts['body_scripts'] ) ) {
		echo $opts['body_scripts'];
	}
}, 999 );

add_action( 'wp_footer', function() {
	$opts = get_option( 'si_settings', [] );
	if ( ! empty( $opts['footer_scripts'] ) ) {
		echo $opts['footer_scripts'];
	}
}, 999 );
