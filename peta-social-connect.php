<?php
/*
* Plugin Name: PTB Social Connect
* Description: Customized Login Page, Register/Login with Facebook or Twitter
* Version: 1.3.1
* Author: PETA WP Dev Team
*/
namespace PETA_Social_Connect;

	defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

	// Autoloader will let us call classes directly rather than requiring the files first
	require_once( 'autoload.php' );

	include_once( 'inc/lib/nextend-facebook-connect/nextend-facebook-connect.php' );
	include_once( 'inc/lib/nextend-twitter-connect/nextend-twitter-connect.php' );

inc\Login_Page::init();
inc\Dev_Dashboard::init();
inc\Help_Info::init();
inc\Social_Connect::init();
