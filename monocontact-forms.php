<?php
/**
* Plugin Name: Monocontact Forms
* Plugin URI: http://www.monocontact.com/
* Description: Shortcodes for Monocontact forms
* Version: 1.3.0
* Author: Taller Digital
* Author URI: http://tallerdigital.cl/
**/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// admin
if ( is_admin() ){ // admin actions
	add_action('admin_menu', 'monocontact_form_setup_menu');
}
function monocontact_form_setup_menu(){
	//create new top-level menu
	add_submenu_page( 'options-general.php', 'Monocontact Forms Settings', 'Monocontact Forms', 'manage_options', 'monocontact-forms', 'monocontact_form_settings_page' );

	//call register settings function
	add_action( 'admin_init', 'register_monocontact_forms_settings' );
}
function register_monocontact_forms_settings(){
	register_setting( 'monocontact-forms-settings-group', 'domain_name' );
}
function monocontact_form_settings_page(){
?>
<div class="wrap">
<h1>Monocontact Forms Settings</h1>

<form method="post" action="options.php">
	<?php settings_fields( 'monocontact-forms-settings-group' ); ?>
	<?php do_settings_sections( 'monocontact-forms-settings-group' ); ?>
	<table class="form-table">
		<tr valign="top">
		<th scope="row">Domain Name</th>
		<td>
			<input type="text" name="domain_name" value="<?php echo esc_attr( get_option('domain_name') ); ?>" />
			<br>
			Must be like "yourdomain.monocontact.net" or if you use your own domain, like "subdomain.yourdomain.com" (the one you use to log in the Monocontact app).
		</td>
		</tr>
	</table>    
	<?php submit_button(); ?>
</form>
</div>
<?php
}

// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');

if ( !function_exists( 'monocontact_form_enqueue_script' ) ) {
	function monocontact_form_enqueue_script() {   
		wp_enqueue_script( 'monocontact-form', '//'.esc_attr( get_option('domain_name') ).'/action/getscript' );
		wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js' );
	}
	add_action('wp_enqueue_scripts', 'monocontact_form_enqueue_script');
}

if ( !function_exists( 'monocontact_form_creation' ) ) {
	function monocontact_form_creation($atts){
		extract( shortcode_atts( array(
			'code' => '',
			'pub' => '',
			'div' => '',
		), $atts, 'multilink' ) );

		return '<div class="monocontact-form" data-pub="'.$pub.'" data-code="'.$code.'"></div>';
	}

	add_shortcode('monoform', 'monocontact_form_creation');	
}

