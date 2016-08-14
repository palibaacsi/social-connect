<?php
/*
Twitter Connect Settings Page
*/
namespace PETA_Social_Connect\inc;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

$pta_twitter_status = 'normal';

class Help_Info {


	public static function init() {
		// add_action( 'admin_init', array( __CLASS__, 'store_settings' ) );
		add_action( 'admin_menu', array( __CLASS__, 'ptb_twitter_menu' ) );
		// add_action( 'contextual_help', array( __CLASS__, 'twitter_connect_plugin_help'), 10, 3 );

	}

	public static function ptb_twitter_menu() {

		$help_info_page = add_dashboard_page( 'Help Info', 'Help Info', 'manage_options', 'pta-help-info', array( __CLASS__, 'help_info_page' ) );

		add_action( 'load-' . $help_info_page, array( __CLASS__, 'my_complicated_help_tab' ) );

	}


	public static function twitter_connect_plugin_help( $contextual_help, $screen_id, $screen ) {

			global $help_info_page;
		// 	// if ( $screen_id == $stc_options_page) {
		 	if ( $screen_id == $help_info_page ) {

		 		$home = home_url( '/' );
		 		// $contextual_help = <<< END
		 		$contextual_help = '<p>To connect your site to Twitter, you will need a Twitter Application. If you have already created one, please insert your Consumer Key and Consumer Secret below.</p>';
		END;
		 	}
		 	return $contextual_help;
	}


	public static function help_tabs() {
		$tabs = array(
			// The assoc key represents the ID
			// It is NOT allowed to contain spaces
			'1' => array(
			 	'title'   => 'Setup Actions',
			 	'content' => '<p>
			 	<ol>
			 	<li>First thing you\'ll need to do is <a href="https://dev.twitter.com/apps/new" target="_blank">Create a twitter app!</a></li>
			 	<li>Choose an App Name, it can be anything you like. Fill out the description and your website home page: ' . site_url() . '</li>
			 	<li>Callback url must be: ' . /* new_twitter_login_url() */ site_url() . '</li>
			 	<li>Accept the rules and Click on <b>Create your twitter application</b></li>
			 	<li>The next page contains the <b>Consumer key</b> and <b>Consumer secret</b> which you have to copy and past below.</li>
			 	<li><b>Save changes!</b></li></ol>
			 	',
		),

			'2' => array(
			 	'title'   => 'Usage',
			 	'content' => '<h4>Simple link</h4>

<a href="http://localhost/social-new/wp-login.php?loginTwitter=1&redirect=http://localhost/social-new" onclick="window.location = \'http://localhost/social-new/wp-login.php?loginTwitter=1&redirect=\'+window.location.href; return false;">Click here to login or register with twitter</a>

<h4>Image button</h4>

<a href="http://localhost/social-new/wp-login.php?loginTwitter=1&redirect=http://localhost/social-new" onclick="window.location = \'http://localhost/social-new/wp-login.php?loginTwitter=1&redirect=\'+window.location.href; return false;"> <img src="HereComeTheImage" /> </a>',
			),
			'3' => array(
			 	'title'   => 'NOTE!!!',
			 	'content' => 'If the twitter user\'s email address already used by another member of your site, the twitter profile will be automatically linked to the existing profile!',
			),
		);
		return $tabs;
	}

	public static function add_tabs() {
		// Replacing all $this with __CLASS__
		// foreach ( $this->tabs as $id => $data ) {
		$tabs = self::help_tabs();
		$help_tabs = array();
		foreach ( $tabs as $id => $value ) {
			get_current_screen()->add_help_tab( array(
				'id'       => $id,
				'title'    => __( $value['title'], 'pta-twitter-connect' ),
				'content'  =>'<p>' .  __( $value['content'], 'pta-twitter-connect' ) . '</p>',
				'callback' => array( __CLASS__, 'prepare' ),
				)
			);
		}
	}

	public static function prepare( $screen, $tab ) {
		printf( '<p>%s</p>', __( $tab['callback']->tabs[ $tab['id'] ], 'pta-twitter-connect' ) );
	}


	public static function my_complicated_help_tab () {
		$screen = get_current_screen();

		$help_tabs = self::help_tabs();
		foreach ( $help_tabs as $id => $value ) {

			// Add my_help_tab if current screen is My Admin Page
			$screen->add_help_tab( array(
				'id'	  => $id,
				'title'   => __( $value['title'], 'pta-twitter-connect' ),
				'content' => '<p>' . __( $value['content'], 'pta-twitter-connect' ) . '</p>',
			) );
		}
	}

	public static function dev_menu_page() {
		global $menu, $submenu;

			echo '<h3>You are viewing this menu from a ';
			// echo Setup_Functions::detect_mobile_device();
			echo ' device</h3>';

			echo '<pre>';
			echo 'You can find this file in  <br>';
			echo plugins_url( '/', __FILE__ );
			echo '<br>';
			echo '</pre>';
	}

	public static function store_settings() {
	    if ( current_user_can( 'manage_options' ) ) {
			if ( isset( $_POST['pta_twitter_update_options'] ) && check_admin_referer( 'pta-twitter-connect' ) ) {
			    if ( 'Y' === $_POST['pta_twitter_update_options'] ) {
					foreach ( $_POST AS $k => $v ) {
					    $_POST[ $k ] = stripslashes( $v );
					}
					unset( $_POST['Submit'] );
					$sanitize = array(
					    'pta_twitter_update_options',
					    'twitter_consumer_key',
					    'twitter_consumer_secret',
					    'twitter_redirect',
					    'twitter_redirect_reg',
					    'twitter_load_style',
					);
					foreach ( $sanitize AS $k ) {
					    $_POST[ $k ] = sanitize_text_field( $_POST[ $k ] );
					}

	                $_POST['twitter_user_prefix'] = preg_replace( "/[^A-Za-z0-9\-_ ]/", '', $_POST['twitter_user_prefix'] );

	                update_option( 'pta_twitter_connect', maybe_serialize( $_POST ) );
	                $pta_twitter_status = 'update_success';
	            }
	        }
	    }
	}

	public static function help_info_page() {
	    $domain = get_option( 'siteurl' );
	    $domain = str_replace(array(
	        'http://',
	        'https://',
		    ), array(
					'',
					'',
			),
		$domain );
	    $domain = str_replace( 'www.', '', $domain );
	    $a      = explode( '/', $domain );
	    $domain = $a[0];
	    ?>

	<?php
	global $pta_twitter_status;
	if ( 'update_success' === $pta_twitter_status ) {
		$message = __( 'Configuration updated', 'pta-twitter-connect' ) . '<br>';
	} elseif ( 'update_failed' === $pta_twitter_status ) {
		$message = __( 'Error while saving options', 'pta-twitter-connect' ) . '<br>';
	} else {
	    $message = '';
	}

	if ( '' !== $message ) {
	    ?>
	    <div class="updated"><strong><p><?php
	            echo $message;
	            ?></p></strong></div><?php
	} ?>


<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1><?php esc_attr_e(  'Help Info Settings', 'pta-twitter-connect'  ); ?></h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h2 class="hndle"><span><?php esc_attr_e( 'Enter your Twitter app information below', 'pta-twitter-connect'  ); ?></span></h2>
	<?php $pta_twitter_connect = maybe_unserialize( get_option( 'pta_twitter_connect' ) ); ?>

						<div class="inside">


<div id="accordion">
<h3>Getting Social Connect Set up</h3>

<div>
			 	<ol>
			 	<li>First thing you\'ll need to do is <a href="https://dev.twitter.com/apps/new" target="_blank">Create a twitter app!</a></li>
			 	<li>Choose an App Name, it can be anything you like. Fill out the description and your website home page: ' . site_url() . '</li>
			 	<li>Callback url must be: ' . /* new_twitter_login_url() */ site_url() . '</li>
			 	<li>Accept the rules and Click on <b>Create your twitter application</b></li>
			 	<li>The next page contains the <b>Consumer key</b> and <b>Consumer secret</b> which you have to copy and past below.</li>
			 	<li><b>Save changes!</b></li></ol>
</div>

<h3>Section 2</h3>

<div>
Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In suscipit faucibus urna.
</div>
</div>
						<?php
							echo '<pre>';
							// print_r( $options );
							echo '</pre>';

/*

*/?>
								<p><?php esc_attr_e( 'WordPress started in 2003 with a single bit of code to enhance the typography of everyday writing and with fewer users than you can count on your fingers and toes. Since then it has grown to be the largest self-hosted blogging tool in the world, used on millions of sites and seen by tens of millions of people every day.', 'wp_admin_style' ); ?></p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h2 class="hndle"><span><?php esc_attr_e(
									 'About this plugin', 'pta-twitter-connect'
								); ?></span></h2>

						<div class="inside">
							<p class="submit">
	<input class="button-primary" type="submit" name="Submit" value="<?php esc_attr_e( 'Save Changes', 'pta-twitter-connect' ); ?>"/>
	</p>
	</form>


				<p><?php esc_attr_e( 'Everything you see here, from the documentation to the code itself, was created by and for the community. WordPress is an Open Source project, which means there are hundreds of people all over the world working on it. (More than most commercial platforms.) It also means you are free to use it for anything from your cat’s home page to a Fortune 500 web site without paying anyone a license fee and a number of other important freedoms.', 'wp_admin_style' ); ?></p>
		<!--left-->

						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->

	<?php
	}
}
// }

