<?php
/*
Save Settings Page
*/
namespace PETA_Social_Connect\inc;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Save_Settings {


	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'pta_settings_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'pta_add_admin_menu' ) );
	}


	public static function pta_add_admin_menu() {

		add_options_page( 'PTA Local', 'PTA Local', 'manage_options', 'pta_local', array( __CLASS__, 'pta_options_page' ) );

	}


	public static function pta_settings_init() {

		register_setting( 'pluginPage', 'pta_settings' );

		add_settings_section(
			'pta_pluginPage_section',
			__( 'Your section description', 'pta-local' ),
			array( __CLASS__, 'pta_settings_section_callback' ),
			'pluginPage'
		);

		add_settings_field(
			'pta_text_field_0',
			__( 'Settings field description', 'pta-local' ),
			array( __CLASS__, 'pta_text_field_0_render' ),
			'pluginPage',
			'pta_pluginPage_section'
		);

		add_settings_field(
			'pta_text_field_1',
			__( 'Settings field description', 'pta-local' ),
			array( __CLASS__, 'pta_text_field_1_render' ),
			'pluginPage',
			'pta_pluginPage_section'
		);
	}


	public static function pta_text_field_0_render() {

		$options = get_option( 'pta_settings' );
		?>
		<label for='<?php echo esc_attr( $options['pta_text_field_1'] ); ?>'><strong>Input One = pta_text_field_0</strong>
		<input type='text' name='pta_settings[pta_text_field_0]' value='<?php echo esc_attr( $options['pta_text_field_0'] ); ?>'></label>
		<?php

	}


	public static function pta_text_field_1_render() {

		$options = get_option( 'pta_settings' );
		?>
		<label for='<?php echo esc_attr( $options['pta_text_field_1'] ); ?>'><strong>Input Two = pta_text_field_1</strong>
		<input type='text' name='pta_settings[pta_text_field_1]' value='<?php echo esc_attr( $options['pta_text_field_1'] ); ?>'></label>
		<?php

	}


	public static function pta_settings_section_callback() {

		echo sanitize_html_class( '<p class="description">This section description</p>', 'pta-local' );

	}


	public static function pta_options_page() {

		?>
		<form action='options.php' method='post'>

			<h2>PTA Local</h2>

			<?php
			echo '<h2>Saving Settings</h2>';
			echo '<p class="description">Can\'t seem to save setting with Namespaced system = Saving Settings</p>' . __FILE__ . '<br><br>';
			self::pta_settings_section_callback();

			self::pta_text_field_0_render();
			echo '<br>';
			self::pta_text_field_1_render();
			settings_fields( 'pta_pluginPage_section' );
			do_settings_sections( 'pta_pluginPage_section' );
			submit_button();
			?>

		</form>
		<?php

	}
}
