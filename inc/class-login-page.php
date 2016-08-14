<?php
namespace PETA_Social_Connect\inc;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Login_Page {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_acf_options_page' ) );

		add_action( 'login_enqueue_scripts', array( __CLASS__, 'customize_login_logo' ) );
		add_action( 'admin_init', array( __CLASS__, 'verify_acf_activated' ) );
		add_action( 'init', array( __CLASS__, 'login_checked_remember_me' ) );

		add_filter( 'acf/settings/load_json', array( __CLASS__, 'add_acf_json_load_point' ) );
		add_filter( 'login_redirect', array( __CLASS__, 'ga_login_redirect' ), 10, 3 );

		add_action( 'login_enqueue_scripts', array( __CLASS__, 'wp_login_styles' ), 20 );

	}

	/**
	 * Adds Settings admin menu page via ACF
	 **/
	public static function add_acf_options_page() {

		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page( array(
				'page_title'  => 'peta2 Options',
				'menu_title'  => 'peta2 Options',
				'menu_slug'   => 'peta2-options',
				'capability'  => 'publish_posts',
				'redirect'    => false,
				)
			);
			acf_add_options_page( array(
				'page_title' 	=> 'Social Connect Options',
				'menu_title'	=> 'Social Connect',
				'menu_slug' 	=> 'social-connect-options',
				'capability'	=> 'publish_posts',
				'parent_slug'	=> 'peta2-options',
				)
			);
		}
	}



	/**
	 * Redirect user after successful login.
	 *
	 * @param string $redirect_to URL to redirect to.
	 * @param string $request URL the user is coming from.
	 * @param object $user Logged user's data.
	 * @return string
	 */
	public static function ga_login_redirect( $redirect_to, $request, $user ) {
		global $user;

		//is there a user to check?
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			//check for admins
			if ( in_array( 'administrator', $user->roles, true ) ) {
				// redirect them to the default place
				$redirect_to = admin_url();
				return $redirect_to;
			} elseif ( in_array( 'editor', $user->roles, true ) ) {
				// redirect them to the default place
				//Login_Helpers::login_from_admin();
				$redirect_to = home_url() . '/members/';
				return $redirect_to;
			} else {
				self::enqueue_ga_form_submission_script();
				$redirect_to = home_url() . '/activity/';
				return $redirect_to;
			}
		} else {
				self::enqueue_ga_page_load_script();
				//$redirect_to = login_url();
				return $redirect_to;
		}
	}


	public static function enqueue_ga_form_submission_script() {
		wp_register_script( 'form-submit', plugins_url( 'js/form-submit.js', __FILE__ ), array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'form-submit' );
		// Login_Helpers::login_form_login();
	}

	public static function enqueue_ga_page_load_script() {
		wp_register_script( 'login-ga', plugins_url( 'js/login-ga.js', __FILE__ ), array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'login-ga' );
		// Login_Helpers::login_enqueue_scripts();
	}

	public static function add_login_page_customizations() {
		$custo_mize = get_field( 'enable_login_page_customizations', 'option' );

		if ( get_field( 'enable_login_page_customizations', 'option' ) ) {
			if ( 1 === intval( $custo_mize[0] ) ) {
				return 'true';
			} else {
			 	return 'false';
			}
		}

	}

	public static function get_login_logo() {
		if ( get_field( 'login_page_logo', 'option' ) ) {
			$login_page_logo = get_field( 'login_page_logo', 'option' );
		} else {
			$login_page_logo = plugins_url( 'inc/images/logo.png', dirname( __FILE__ ) );
		}
		return $login_page_logo;
	}

	public static function customize_login_logo() {

		$login_page_logo = self::get_login_logo();
		$customize = self::add_login_page_customizations();

		if ( 'true' === $customize ) {
			wp_register_style( 'login-logo', plugins_url( 'inc/css/login-logo.css', dirname( __FILE__ ) ) );
			wp_enqueue_style( 'login-logo' );
	?>
			<style type="text/css">
			#login h1 a, .login h1 a {
				background-image: url("<?php echo esc_html( $login_page_logo ); ?>");
			}
			</style>
			<?php
		}
	}


	public static function get_login_background_image() {
		if ( get_field( 'login_page_background_image', 'option' ) ) {
			$the_background_image = get_field( 'login_page_background_image', 'option' );
		} else {
			$the_background_image = plugins_url( 'inc/images/peta2bg.png', dirname( __FILE__ ) );
		}
		return $the_background_image;
	}


	public static function add_bg_image_login_page() {

		$the_background_image = self::get_login_background_image();
		?>
		<style type="text/css">
		body.login {
			background-image: url("<?php echo esc_html( $the_background_image ); ?>");
			}
		}
		</style>
		<?php
	}

	public static function login_page_message() {

		wp_register_style( 'login-text', plugins_url( 'inc/css/login-text.css', dirname( __FILE__ ) ) );
		wp_enqueue_style( 'login-text' );

		$top_message = get_field( 'login_page_intro', 'option' );
		echo $top_message;
	}


	public static function wp_login_styles() {
		$customize = self::add_login_page_customizations();

		if ( 'true' === $customize ) {
			if ( get_field( 'formatted_text', 'option' ) ) {
				wp_register_style( 'login-locked', plugins_url( 'inc/css/login-locked.css', dirname( __FILE__ ) ) );
				wp_enqueue_style( 'login-locked' );
			}

			if ( get_field( 'background_image', 'option' ) ) {
				wp_register_style( 'login-background', plugins_url( 'inc/css/login-background.css', dirname( __FILE__ ) ) );
				wp_enqueue_style( 'login-background' );

				self::add_bg_image_login_page();
			}

			if ( get_field( 'custom_message', 'option' ) ) {
				add_filter( 'login_message', array( __CLASS__, 'login_page_message' ) );
			}
		}
	}

	public static function verify_acf_activated() {
		if ( ! class_exists( 'acf' ) ) {
			new Admin_Notification( 'Advanced Custom Fields must be installed and activated to support the Login Page customizations', 'error' );
		}
	}
	public static function dev_login_menu() {
		add_dashboard_page( 'Login Dev Page', 'Login Dev Page', 'edit_posts', 'dev-login-page.php',  array( __CLASS__, 'dev_login_menu_page' ), 'dashicons-tickets', 11 );
	}

	public static function login_checked_remember_me() {
		add_filter( 'login_footer',  array( __CLASS__, 'rememberme_checked' ) );
	}


	public static function rememberme_checked() {
		echo "<script>document.getElementById('rememberme').checked = true;</script>";
	}

	public static function add_acf_json_load_point( $paths ) {
		$paths[] = plugin_dir_path( __FILE__ ) . 'acf-json';

		return $paths;
	}

}
