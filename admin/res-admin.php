<?php

if( !class_exists( 'CD_Res_Admin_Class' ) )
{
	class CD_Res_Admin_Class extends CD_Base_Admin_Tools
	{
		public $setting = 'cd_res_options';
		
		function __construct()
		{
			parent::__construct();  // Register some scripts in styles from the parent class
			add_action( 'admin_menu', array( &$this, 'menu_page' ) );
			add_action( 'admin_init', array( &$this, 'settings' ) );
			add_filter( 'plugin_action_links_' . CD_RES_BASENAME, array( &$this, 'plugin_row' ), 10, 1 );
				
		}	
		
		/**
		* Adds the menu page, and prints the scripts/styles for it. Also adds contextual help.
		*/
		public function menu_page()
		{
			$page = add_options_page( __( 'Really Easy Slider', 'cd-res' ), __( 'RE Slider', 'cd-res' ), 'administrator', 'really-easy-slider', array( &$this, 'menu_page_cb' ) );
			
			add_action( "admin_print_styles-{$page}", array( &$this, 'styles' ) );
			add_action( "admin_print_scripts-{$page}", array( &$this, 'scripts' ) );
			
			$help_content = '<p>' . __( 'To use Really Easy Slider, just drop this <code>[reslider posts="1,2,3" width="300" height="200"]</code> into your posts or pages', 'cd-res' ) . '</p>';
			$help_content .= '<p>' . __( 'In the <code>post="1,2,3"</code>, replace <code>1,2,3</code> with a comma separated list of the post IDs you\'d like to have in the slider. Really Easy Slider will then grab the post thumbnail (or the first attached image) and the title from each of those posts and assemble them into a slider. Width and height are the dimensions of your slide in pixels. They are optional, but the slider will default to <code>width="500"</code> and <code>height="300"</code>', 'cd-res' ) . '</p>';
			$help_content .= '<p>' . __( 'Not sure how to find your post IDs? ', 'cd-res' ) . '<a href="http://wordpress.org/extend/plugins/wp-show-ids/" target="_blank">' . __('This plugin', 'cd-res' ) . '</a>' . __( ' will add a column to your post, page, category, and tag lists that shows the id.', 'cd-res' ) . '</p>';
			$help_content .= '<p>' . __( "Please note that plugins like Really Easy Slider will never be able to replace a custom made slider that fits completely into your design. Really Easy Slider might get you started, however. If you're familiar with html and css, you should style the slider yourself using the advanced options", 'cd-res' ) . '</p>';
			
			$help_content .= '<p>' . __( 'This plugin uses the ', 'cd-res' ) . '<a href="http://cssglobe.com/post/5780/easy-slider-17-numeric-navigation-jquery-slider" target="_blank">' . __( 'Easy Slider', 'cd-res' ) . '</a>' . __( ' jQuery plugin by ', 'cd-res' ) . '<a href="http://grakalic.com/" target="_blank">Alen Grakalic</a></p>';
			add_contextual_help( $page, $help_content );
		}
		
		/**
		* Registers the setting
		*/
		public function settings()
		{
			register_setting( 'cd_res_options', 'cd_res_options', array( &$this, 'validate' ) );
		}
		
		/**
		* Validate settings submission
		*/
		public function validate( $input )
		{
			$clean = array();
			// Basic Options
			$clean['res_speed'] = ( isset( $input['res_speed'] ) && absint( $input['res_speed'] ) != 0 ) ? absint( $input['res_speed'] ) : '';
			$clean['res_pause'] = ( isset( $input['res_pause'] ) && absint( $input['res_pause'] ) != 0 ) ? absint( $input['res_pause'] ) : '';
			$clean['res_auto'] = ( isset( $input['res_auto'] ) && $input['res_auto'] == 'true' ) ? 'true' : 'false';
			$clean['res_continuous'] = ( isset( $input['res_continuous'] ) && $input['res_continuous'] == 'true' ) ? 'true' : 'false';
			$clean['res_controlshow'] = ( isset( $input['res_controlshow'] ) && $input['res_controlshow'] == 'true' ) ? 'true' : 'false';
			$clean['res_numeric'] = ( isset( $input['res_numeric'] ) && $input['res_numeric'] == 'true' ) ? 'true' : 'false';
			
			//Post thumbnail support
			$clean['res_thumb_support'] = ( isset( $input['res_thumb_support'] ) && $input['res_thumb_support'] ) ? 'on' : 'off';
			
			//Advanced Options
			$clean['res_disable_style'] = ( isset( $input['res_disable_style'] ) && $input['res_disable_style'] ) ? 'on' : 'off';
			$clean['res_disable_script'] = ( isset( $input['res_disable_script'] ) && $input['res_disable_script'] ) ? 'on' : 'off';
			$clean['res_nextid'] = isset( $input['res_nextid'] ) ? wp_filter_kses( $input['res_nextid'] ) : '';
			$clean['res_previd'] = isset( $input['res_previd'] ) ? wp_filter_kses( $input['res_previd'] ) : '';
			$clean['res_nexttext'] = isset( $input['res_nexttext'] ) ? wp_filter_kses( $input['res_nexttext'] ) : '';
			$clean['res_prevtext'] = isset( $input['res_prevtext'] ) ? wp_filter_kses( $input['res_prevtext'] ) : '';
			$clean['res_controlsid'] = isset( $input['res_controlsid'] ) ? wp_filter_kses( $input['res_controlsid'] ) : '';
			$clean['res_control_container_class'] = isset( $input['res_control_container_class'] ) ? wp_filter_kses( $input['res_control_container_class'] ) : '';
			
			// Handle Settings Errors
			if( $clean['res_speed'] != $input['res_speed'] ) add_settings_error( 'cd_res_options', 'res_speed', __( 'The animation speed was not valid', 'cd-res' ), 'error' );
			if( $clean['res_pause'] != $input['res_pause'] ) add_settings_error( 'cd_res_options', 'res_pause', __( 'The pause between slides value was not valid!', 'cd-res' ), 'error' );
			if( $clean['res_nextid'] != $input['res_nextid'] ) add_settings_error( 'cd_res_options', 'res_nextid', __( 'Next Button ID not valid!', 'cd-res' ), 'error' );
			if( $clean['res_previd'] != $input['res_previd'] ) add_settings_error( 'cd_res_options', 'res_previd', __( 'Previous Button ID not valid!', 'cd-res' ), 'error' );
			if( $clean['res_nexttext'] != $input['res_nexttext'] ) add_settings_error( 'cd_res_options', 'res_nexttext', __( 'Next button text not valid!', 'cd-res' ), 'error' );
			if( $clean['res_prevtext'] != $input['res_prevtext'] ) add_settings_error( 'cd_res_options', 'res_prevtext', __( 'Previous button text not valid!', 'cd-res' ), 'error' );
			if( $clean['res_controlsid'] != $input['res_controlsid'] ) add_settings_error( 'cd_res_options', 'res_controlsid', __( 'Numeric controls ID not valid!', 'cd-res' ), 'error' );
			if( $clean['res_control_container_class'] != $input['res_control_container_class'] ) add_settings_error( 'cd_res_options', 'res_controlsid', __( 'Control container class not valid!', 'cd-res' ), 'error' );
			
			return $clean;
		}
		
		/**
		* Scripts & Styles Enqueue functions
		*/
		public function styles()
		{
			wp_enqueue_style( 'cd-admin' );	
		}
		
		public function scripts()
		{
			wp_enqueue_script( 'cd-collapse' );
			wp_enqueue_script( 'populate', CD_RES_URL . 'js/populate.js', array('jquery'), NULL );
		}
		
		/**
		* Call back function for the menu page
		*/
		public function menu_page_cb()
		{
			global $cd_res_options;
			?>
			<div class="wrap">
			<div class="icon32" id="icon-tools"> </div>
			<h2><?php _e( 'Really Easy Slider Settings', 'cd-res' ); ?></h2>
			<div class="metabox-holder">
			
				<form action="<?php echo admin_url('options.php'); ?>" method="post" id="res-options">
				<?php settings_fields( 'cd_res_options' ); ?>
				
				<?php 
				$content = "<p>" . __('Both Animation speed and pause between slides are set in milliseconds. If you want a 4 second pause between slide changes, enter "4000" in the "Pause between..." input.', 'cd-res' ) . "</p>";
				$content .= $this->textinput( 'res_speed', __( 'Animation Speed', 'cd-res' ) );
				$content .= $this->textinput( 'res_pause', __( 'Pause between slide changes', 'cd-res' ) );
				$content .= $this->select( 'res_auto', array( 'true' => __( 'Yes', 'cd-res' ), 'false' => __( 'No', 'cd-res' ) ), __( 'Start scrolling automatically?', 'cd-res' ) );
				$content .= $this->select( 'res_continuous', array( 'true' => __( 'Yes', 'cd-res' ), 'false' => __( 'No', 'cd-res' ) ), __( 'Slides move continuously?', 'cd-res' ) );
				$content .= $this->select( 'res_controlshow', array( 'true' => 'Yes', 'false' => 'No' ), __( 'Show controls?', 'cd-res' ) );
				$content .= $this->select( 'res_numeric', array( 'false' => 'Next/Previous Buttons', 'true' => 'Numeric' ), __( 'Control Style', 'cd-res' ) );
				$content .= $this->submit( __('Save Settings', 'cd-res' ) );
				echo $this->postbox( 'cd-res-options', __( 'General Options', 'cd-res' ), $content );
					
				$thumbnail_content = '<p>' . __( "My theme doesn't support post thumbnails! What do I do?", 'cd-res' ) . '</p>';
				$thumbnail_content .= $this->checkbox( 'res_thumb_support', __('Enable Post Thumbnails', 'cd-res' ) );
				$thumbnail_content .= '<p>' . __( 'Please note, however, that you should probably enable ', 'cd-res' ) . '<a href="http://codex.wordpress.org/Post_Thumbnails" target="_blank">' . __( 'thumbnail support', 'cd-res' ) . '</a>' . __( "in your theme's functions file. As much as you may like Really Easy Slider, someday you'll uninstall it. You shouldn't rely on this plugin to enable your thumbnail support as it could cause complications on uninstall.", 'cd-res' ) . '</p>';
				
				$thumbnail_content .= $this->submit( __('Save Settings', 'cd-res' ) );
				echo $this->postbox( 'cd-res-thumbs', __( 'Post Thumbnails', 'cd-res' ), $thumbnail_content );
				
				$advanced_content = '<p>' . __('Only disable the Easy Slider javascript if you intended to add it into your theme manually. This plugin loads the script in the footer, and only on pages with an the short code <code>[reslider]</code>.', 'cd-res' ) . '</p>';
				$advanced_content .= $this->checkbox( 'res_disable_script', __('Check this box to stop the plugin from loading the Easy Slider javascript', 'cd-res' ) );
				$advanced_content .= '<p>'. __( 'The options below let you change the html attributes and text of the various slider elements. If you change anything with these settings, the default Really Easy Slider stylesheet will <em>not</em> work. If you\'re an advanced user, feel free to change these as you like or disable the default stylesheet entirely.', 'cd-res' ) . '</p>';
				$advanced_content .= $this->checkbox( 'res_disable_style', __('Check this box to stop the plugin from loading the built in stylesheet', 'cd-res' ) );
				$advanced_content .= $this->textinput( 'res_nextid', __( 'Next Button ID', 'cd-res' ) );
				$advanced_content .= $this->textinput( 'res_previd', __( 'Previous Button ID', 'cd-res' ) );				
				$advanced_content .= $this->textinput( 'res_nexttext', __( 'Next Button Text', 'cd-res' ) );
				$advanced_content .= $this->textinput( 'res_prevtext', __( 'Previous Button Text', 'cd-res' ) );
				$advanced_content .= $this->textinput( 'res_controlsid', __( 'Numeric controls ID', 'cd-res' ) );
				$advanced_content .= $this->textinput( 'res_control_container_class', __( 'Control Container Class', 'cd-res' ) );
				$advanced_content .= $this->submit( __('Save Settings', 'cd-res' ) );
				echo $this->postbox( 'cd-res-advanced', __( 'Advanced Settings', 'cd-res' ), $advanced_content );
				
				$restore_content = '<p>' . __( 'It will be okay. Just follow these steps.', 'cd-res' ) . '</p>';
				$restore_content .='<p><strong>1.</strong> ' . __( 'Click this button ', 'cd-res' ) . '<a href="#restore" class="button-secondary">' . __( 'Restore Defaults', 'cd-res' ) . '</a></p>';
				$restore_content .= '<p><strong>2.</strong> ' . __( 'Check the settings above and make sure everything is okay. Adjust as necessary.', 'cd-res' ) . '</p>';
				$restore_content .= '<p><strong>3.</strong> ' . __( 'Save your settings.', 'cd-res' ) . '</p>';
				$restore_content .= $this->submit( __('Save Settings', 'cd-res' ) );
				$restore_content .= '<p>' . __( 'Did that work? If not, ', 'cd-res' ) . '<a href="http://www.christopherguitar.net/contact-christopher/" target="_blank">' . __( 'get in touch', 'cd-res' ) . '</a>.</p>';
				
				echo $this->postbox( 'cd-res-restore', __( "It's broken! Help!", 'cd-res' ), $restore_content );
					
				?>		
				
				</form>
			</div>
			</div>
			<?php
			
		}
		
		/**
		* Add a link to the plugin page
		*/
		function plugin_row( $links )
		{ 
			$settings_link = array( 'settings' => '<a href="' . admin_url( 'options-general.php?page=really-easy-slider' ) .	'">' . __( 'Settings', 'cd-res' ) . '</a>' );
			$links = array_merge( $settings_link, $links );
			return $links;
		}
		
	} // end class
	
	$cd_res_admin_class = new CD_Res_Admin_Class();
} // end class_exists

?>