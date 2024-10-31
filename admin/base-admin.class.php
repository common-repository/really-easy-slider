<?php

if( !class_exists( 'CD_Base_Admin_Tools' ) )
{
	class CD_Base_Admin_Tools
	{
		var $setting = '';
		function __construct() 
		{ 
			add_action( 'admin_init', array( &$this, 'register_styles' ) );	
			add_action( 'admin_init', array( &$this, 'register_scripts' ) ); 
		}
		
		function register_styles()
		{
			wp_register_style( 'cd-admin', CD_RES_URL . 'css/cd-general-admin.css', array(), NULL, 'all' );	
		}
		
		function register_scripts()
		{
			wp_register_script( 'cd-collapse', CD_RES_URL . 'js/collapse.js', array( 'jquery' ), NULL, true );
		}
		
		
		
		/**
		* Functions for form fields
		*
		*/
		function checkbox( $id, $label, $setting = 'default' )
		{
			if( $setting == 'default' ) $setting = $this->setting;
			
			$options = is_multisite() ? get_site_option( $setting ) : get_option( $setting );
			
			$out_label = "<label for='{$id}'>{$label}</label>";
			$out_input = "<input class='checkbox' type='checkbox' id='{$id}' name='{$setting}[{$id}]'" . checked( $options[$id], 'on' , false ) . "/>";
			$out = "<p>{$out_input} {$out_label}</p>";
			return $out;
		}
		
		function radio( $id, $values = array( 'value' => 'label' ), $setting = 'default' )
		{
			if( $setting == 'default' ) $setting = $this->setting;
			
			$options = is_multisite() ? get_site_option( $setting ) : get_option( $setting );
			
			$out = "<div class='radiobutton-set'>";
			foreach( $values as $value => $label )
			{
				$out .= "<p>";
				$out .= "<input type='radio' name='{$setting}[{$id}]' value='{$value}' id='{$value}' class='radiobutton'" . checked( $options[$id], $value, false ) . " />";
				$out .= "<label for='{$value}'>{$label}</lable>";
				$out .= "</p>";
			}
			$out .= "</div>";
			
			return $out;	
		}
		
		function textinput( $id, $label, $setting = 'default' )
		{
			if( $setting == 'default' ) $setting = $this->setting;
			
			$options = is_multisite() ? get_site_option( $setting ) : get_option( $setting );
			
			$out_input = "<input type='text' class='textinput' id='{$id}' name='{$setting}[{$id}]' value='{$options[$id]}' />";
			$out_label = "<label for='{$id}'>$label</label>";
			
			return "<p class='textinput'>{$out_label} {$out_input}</p>";
		}
		
		function textarea( $id, $label, $setting = 'default' )
		{
			if( $setting == 'default' ) $setting = $this->setting;
			
			$options = is_multisite() ? get_site_option( $setting ) : get_option( $setting );
			
			$input_out = "<textarea class='textarea' name='{$setting}[{$id}]' id='{$id}'>";
			$input_out .= $options[$id];
			$input_out .= "</textarea>";
			
			$label_out = "<label for='{$id}'>$label</label>";
			
			return "<div class='textarea'>{$label_out} {$input_out}</div>";
		}
		
		function select( $id, $values = array( 'value' => 'label' ), $label, $setting = 'default' )
		{
			if( $setting == 'default' ) $setting = $this->setting;
			$options = is_multisite() ? get_site_option( $setting ) : get_option( $setting );
			
			$out = "<label for='{$id}'>{$label}</label>";
			$out .= "<select id='{$id}' name='{$setting}[{$id}]' class='select'>";
			foreach( $values as $value => $label )
			{
				$out .= "<option value='{$value}'" . selected( $options[$id], $value, false ) . ">{$label}</option>";
			}
			$out .= "</select>";
			
			return "<p class='select'>{$out}</p>";
			
		}
		
		function submit( $text, $align = 'left' )
		{
			$align_class = ( $align == 'left' ) ? 'cd-text-left' : 'cd-text-right';
			return "<p class='submit {$align_class}'><input name='submit' type='submit' class='button-primary' value='{$text}' /></p>";
		}
		
		
		/*
		* Functions for creating higher level elements.
		*
		*/
		function postbox( $id, $title, $content )
		{
			$out = "<div id='{$id}' class='postbox cd-postbox'>";
			$out .= "<h3 class='hndle'>{$title}</h3>";
			$out .= "<div class='inside'>{$content}</div>";
			$out .= "</div>";
			
			return $out;	
		}	
		
	}
	
	
	
}