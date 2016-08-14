<?php

// remove_action( 'login_form', 'new_add_fb_login_form' );
// remove_action( 'login_form', 'new_add_twitter_login_form' );
remove_action( 'admin_menu', array( &$nextendtwittersettings, 'NextendTwitter_Menu' ) , 1 );
