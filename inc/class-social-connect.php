<?php
namespace PETA_Social_Connect\inc;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Social_Connect {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'social_connect_dash' ) );
		add_action( 'login_form', array( __CLASS__, 'login_form_add_fb_button' ) );
		add_action( 'login_form', array( __CLASS__, 'login_form_add_twitter_button' ) );
		add_action( 'admin_menu', array( __CLASS__, 'remove_social_menus' ), 999 );
		add_action( 'init', array( __CLASS__, 'social_connect_settings' ) );
		add_filter( 'acf/settings/load_json', array( __CLASS__, 'add_acf_json_load_point' ) );
	}


	public static function social_connect_dash() {
		add_dashboard_page( 'Social Connect Dashboard', 'Social Connect', 'manage_options', 'social-connect-dash', array( __CLASS__, 'social_connect_dashboard' ) );

	}

	public static function social_connect_settings() {

	}

	public static function social_connect_dashboard() {

		echo '<h2>Social Connect Issues</h2>';
		echo '<pre>';
		echo '<h3>Facebook</h3>';
		echo '<p style="margin-left:2rem;">';
		echo 'key = ' . get_field( 'facebook_api_key', 'option' ) . '<br>';
		echo 'secret = ' . get_field( 'facebook_api_secret', 'option' ) . '<br>';
		// print_r( self::parse_facebook_settings() );
		echo '</p>';
		echo '<h3>Twitter</h3>';
		echo '<p style="margin-left:2rem;">';
		echo 'key = ' . get_field( 'twitter_consumer_key', 'option' ) . '<br>';
		echo 'secret = ' . get_field( 'twitter_consumer_secret', 'option' ) . '<br>';
		// print_r( self::parse_twitter_settings() );
		echo '</p>';
		echo '</pre>';
		echo '<ol>';
		echo '<li>Removed</li>';
		echo '</ol>';
	}
	public static function nextend_fb_connect() {

		return 'a:12:{
			s:8:"_wpnonce";s:10:"9b6a6691da";s:16:"_wp_http_referer";s:71:"/PETA2revamp/wp-admin/options-general.php?page=nextend-facebook-connect";s:20:"newfb_update_options";s:1:"Y";s:8:"fb_appid";s:15:"298767870207064";s:9:"fb_secret";s:32:"be130cc1ce345a30fa611ef66cbe457a";s:14:"fb_user_prefix";s:11:"Facebook - ";s:11:"fb_redirect";s:4:"auto";s:15:"fb_redirect_reg";s:4:"auto";s:13:"fb_load_style";s:1:"1";s:15:"fb_login_button";s:133:"<div class="new-fb-btn new-fb-1 new-fb-default-anim"><div class="new-fb-1-1"><div class="new-fb-1-1-1">CONNECT WITH</div></div></div>";s:14:"fb_link_button";s:136:"<div class="new-fb-btn new-fb-1 new-fb-default-anim"><div class="new-fb-1-1"><div class="new-fb-1-1-1">LINK ACCOUNT TO</div></div></div>";s:16:"fb_unlink_button";s:135:"<div class="new-fb-btn new-fb-1 new-fb-default-anim"><div class="new-fb-1-1"><div class="new-fb-1-1-1">UNLINK ACCOUNT</div></div></div>";
		}';
	}

	public static function nextend_twitter_connect() {

		return 'a:12:{
			s:8:"_wpnonce";s:10:"c09adf2b5d";s:16:"_wp_http_referer";s:70:"/PETA2revamp/wp-admin/options-general.php?page=nextend-twitter-connect";s:25:"newtwitter_update_options";s:1:"Y";s:20:"twitter_consumer_key";s:25:"YqOTfHeExOQEjNvFp1zNX5hZs";s:23:"twitter_consumer_secret";s:50:"3fhrCRsnHu4iypVMvvxWqB2ThcdJCbY8De6PMJ6jHhakWUq6o5";s:19:"twitter_user_prefix";s:10:"twitter - ";s:16:"twitter_redirect";s:4:"auto";s:20:"twitter_redirect_reg";s:4:"auto";s:18:"twitter_load_style";s:1:"1";s:20:"twitter_login_button";s:158:"<div class="new-twitter-btn new-twitter-1 new-twitter-default-anim"><div class="new-twitter-1-1"><div class="new-twitter-1-1-1">CONNECT WITH</div></div></div>";s:19:"twitter_link_button";s:161:"<div class="new-twitter-btn new-twitter-1 new-twitter-default-anim"><div class="new-twitter-1-1"><div class="new-twitter-1-1-1">LINK ACCOUNT TO</div></div></div>";s:21:"twitter_unlink_button";s:160:"<div class="new-twitter-btn new-twitter-1 new-twitter-default-anim"><div class="new-twitter-1-1"><div class="new-twitter-1-1-1">UNLINK ACCOUNT</div></div></div>";
		}';
	}

	public static function remove_social_menus() {

		remove_submenu_page( 'options-general.php', 'nextend-facebook-connect' );
		remove_submenu_page( 'options-general.php', 'nextend-twitter-connect' );

	}


	public static function parse_facebook_settings() {

		// $nextend_fb = maybe_unserialize( get_option( 'nextend_fb_connect' ) );
		$nextend_fb = self::nextend_fb_connect();
		$nextend_fb = unserialize( $nextend_fb );
		return $nextend_fb;

	}

	public static function parse_twitter_settings() {

		// $nextend_twit = maybe_unserialize( get_option( 'nextend_twitter_connect' ) );

		$nextend_twit = self::nextend_twitter_connect();

		return $nextend_twit;

	}

	public static function get_next_fb_settings() {

		$nextend_fb = maybe_unserialize( get_option( 'nextend_fb_connect' ) );
		return $nextend_fb;
	}

	public static function get_next_twitter_settings() {

		$nextend_twit = maybe_unserialize( get_option( 'nextend_twitter_connect' ) );
		return $nextend_twit;
	}

	public static function login_form_add_fb_button() {
?>
	<script type="text/javascript">
		if(jQuery.type(has_social_form) === "undefined"){
		var has_social_form = false;
		var socialLogins = null;
		}
		jQuery(document).ready(function(){
		(function($) {
		  if(!has_social_form){
		    has_social_form = true;
		    var loginForm = $('.forgetmenot,#registerform,#front-login-form,#setupform');
		    socialLogins = $('<div class="newsociallogins" style="text-align: center;"><div style="clear:both;"></div></div>');
		    if(loginForm.find('input').length > 0)
		      loginForm.prepend("<h3 style='text-align:center;'><?php _e('<br>'); ?></h3>");
		    loginForm.prepend(socialLogins);
		    socialLogins = loginForm.find('.newsociallogins');
		  }
		  if(!window.fb_added){
		    socialLogins.prepend('<?php echo addslashes(preg_replace('/^\s+|\n|\r|\s+$/m', '', new_fb_sign_button())); ?>');
		    window.fb_added = true;
		  }
		}(jQuery));
		});
		</script>
	<?php
	}

	public static function login_form_add_twitter_button() {
?>
	<script>
		if(jQuery.type(has_social_form) === "undefined"){
		var has_social_form = false;
		var socialLogins = null;
		}
		jQuery(document).ready(function(){
		(function($) {
			if(!has_social_form){
				has_social_form = true;
				var loginForm = $('.forgetmenot,#registerform,#front-login-form,#setupform');
				socialLogins = $('<div class="newsociallogins" style="text-align: center;"><div style="clear:both;"></div></div>');
			if(loginForm.find('input').length > 0)
			  loginForm.prepend("<h3 style='text-align:center;'><?php _e('OR'); ?></h3>");
			loginForm.prepend(socialLogins);
		  }
		  if(!window.twitter_added){
		    socialLogins.prepend('<?php echo addslashes(preg_replace('/^\s+|\n|\r|\s+$/m', '', new_twitter_sign_button())); ?>');
		    window.twitter_added = true;
		  }
		}(jQuery));
		});
	</script>
<?php
	}

	public static function add_acf_json_load_point( $paths ) {
		$paths[] = plugin_dir_path( __FILE__ ) . 'acf-json';

		return $paths;
	}
}
