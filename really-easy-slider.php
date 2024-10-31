<?php

/*
Plugin Name: Really Easy Slider
Plugin URI: http://www.christopherguitar.net/wordpress/really-easy-slider/
Description: Quickly add sliders to pages and posts using a shortcode
Version: 0.1
Author: Christopher Davis
Author URI: http://www.christopherguitar.net
License: GPL2
*/

/*  Copyright 2011  Christopher Davis  (email: cd@christopherguitar.net )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
$cd_res_options = get_option( 'cd_res_options' );
define( 'CD_RES_URL', plugin_dir_url( __FILE__ ) );
define( 'CD_RES_PATH', plugin_dir_path( __FILE__ ) );
define( 'CD_RES_BASENAME', plugin_basename( __FILE__ ) );

if( is_admin() )
{
	require CD_RES_PATH . 'admin/base-admin.class.php';
	require CD_RES_PATH . 'admin/res-admin.php';
}
else
{
	require CD_RES_PATH . 'inc/res-front.php';
}


// Activation hook
register_activation_hook( __FILE__, 'cd_res_activation_hook' );
function cd_res_activation_hook()
{
	$defaults = array(
		'res_speed' 			=> 800,
		'res_pause' 			=> 6000,
		'res_auto' 				=> 'true',
		'res_continuous' 		=> 'true',
		'res_direction' 		=> 'false',
		'res_controlshow'		=> 'true',
		'res_numeric' 			=> 'true',
		'res_thumb_support' 	=> 'off',
		'res_disable_style' 	=> 'off',
		'res_disable_script'	=> 'off',
		'res_nextid' 			=> 'cd-res-next',
		'res_previd' 			=> 'cd-res-prev',
		'res_nexttext' 			=> 'Next &raquo;',
		'res_prevtext' 			=> '&laquo; Previous',
		'res_controlsid' 		=> 'cd-res-controls',
		'res_control_container_class' => 'cd-res-control-container'
	);
	
	update_option( 'cd_res_options', $defaults );	
	
	
}

// Plugin row options




// Enable thumbnail support
if( $cd_res_options['res_thumb_support'] == 'on' && current_theme_supports( 'post-thumbnails' ) == false )
{
	add_theme_support( 'post-thumbnails' );	
}

?>