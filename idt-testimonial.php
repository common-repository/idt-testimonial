<?php
/*
Plugin Name:  IDT Testimonial Plugin
Plugin URI:   http://innovativedigitalteam.com/plugins
Description:  Simple plugin to Show testimonial on pages, widgets and posts.
Version:      0.1
Author:       <a href="http://innovativedigitalteam.com/">innovativedigitalteam</a>
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/


/*
Copyright (c) 2011, innovativedigitalteam.
 
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$plugin_dir = dirname( __FILE__ );
$plugin_url = plugin_dir_url( __FILE__ );

define( 'IDT_INC_DIR', $plugin_dir . '/inc/' );



/**
* Testimonial class 
* @since v0.1
* 
*/

class idt_testimonial
{

	public function __construct()
	{
		if (esc_attr($_REQUEST['page']) == 'add_testimonial' ||	esc_attr($_REQUEST['page']) == 'idt-options' || esc_attr($_REQUEST['page']) == 'idt-testimonial' ) {
			ob_start();
		}
		if(is_admin()) {
   		 add_action('admin_menu', array( $this, 'idt_testimonial_menu' ));

		}
		 add_action( 'admin_enqueue_scripts', array( $this, 'idt_testimonial_assets' ) );
		 add_action( 'wp_enqueue_scripts', array( $this, 'idt_testimonial_assets' ) );

		// include front display file
		require( IDT_INC_DIR. 'idt-front-display.php');

	}

	/**
	* Testimonials assets Load 
	* @since v0.1
	* 
	*/
	public function idt_testimonial_assets() {
		
		/**
		* Testimonials assets Load 
		* @since v0.1
		*  Load Style sheets front end
		**/
		if (!is_admin()) 
		{
			wp_enqueue_style( 'idt-flexslider', plugins_url( '/assets/css/flex-slider.css', __FILE__ ), array(), false );
		}

		// default style sheet
		wp_enqueue_style( 'idt-testimonial', plugins_url( '/assets/css/style.css', __FILE__ ), array(), '2.0.1', false );
		
		// default wordpress libraries
		wp_enqueue_media('media-upload');
	    wp_enqueue_media('thickbox');
		
		// script for front end slider
		if (!is_admin()) {
			wp_enqueue_script( 'idt-testimonial-flexslider',  plugins_url( '/assets/js/flex-slider-jquery.js', __FILE__), array(), true );
		}
		// default script
		wp_enqueue_script( 'idt-testimonial-script', plugins_url( '/assets/js/script.js', __FILE__ ) , array(), '', true );

	}

	/**
	* Testimonial Admin Pages 
	* @since v0.1
	* 
	**/

	public function idt_testimonial_menu() {
		add_menu_page( 'idt Testimonial', 'Testimonial', 'manage_options', 'idt-testimonial', array($this,'idt_display') );
		add_submenu_page( 'idt-testimonial', 'Add Testimonial', 'Add testimonial' , 'manage_options', 'add_testimonial', array($this,'idt_display') );
		add_submenu_page( 'idt-testimonial', 'Settings', 'Settings' , 'manage_options', 'idt-options', array($this,'idt_display') );
	}

	/**
	* Testimonials files Load 
	* @since v0.1
	* 
	**/

	public function dzz_testimonial_includes()
	{


		if (esc_attr($_GET['page']) == 'idt-options') {
			require( IDT_INC_DIR. 'idt-admin-settings.php');
		}	else {
			require( IDT_INC_DIR. 'idt-admin-display.php');
		}


	}

	/**
	* Testimonials Admin display function 
	* @since v0.1
	* 
	**/

	public function idt_display()
	{
		$this->dzz_testimonial_includes();
	}


}

/**
* Testimonials initialize class
* @since v0.1
* 
**/


new idt_testimonial;

