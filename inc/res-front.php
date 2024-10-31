<?php
if( !class_exists( 'CD_Res_Front_Class' ) )
{
	class CD_Res_Front_Class
	{
		function __construct()
		{
			add_shortcode( 'reslider', array( &$this, 'shortcode' ) );
			add_action( 'wp_print_scripts', array( &$this, 'scripts' ) );
			add_action( 'wp_print_styles', array( &$this, 'styles' ) );
			add_action( 'wp_head', array( &$this, 'slider_setup' ) ); 	
			
		}
		
		function CD_Res_Front_Class()
		{
			$this->__construct();
		}
		
		function register_shortcode()
		{
			add_shortcode( 'reslider', array( &$this, 'shorcode' ) );	
		}
		
		function scripts()
		{
			global $cd_res_options, $post;
			if( $cd_res_options['res_disable_script'] != 'on'  && strpos( $post->post_content, '[reslider' ) !== false )
			{
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'easyslider', CD_RES_URL . 'js/easy-slider.js', array( 'jquery' ), NULL, true );
			}	
		}
		
		function styles()
		{
			global $cd_res_options, $post;
			if( $cd_res_options['res_disable_style'] != 'on' && strpos( $post->post_content, '[reslider' ) !== false )
			{
				wp_enqueue_style( 'res-style', CD_RES_URL . 'css/res-front.css', array(), NULL, 'screen' );	
			}
		}
		
		function slider_setup()
		{
			global $cd_res_options, $post;
			if( strpos( $post->post_content, '[reslider' ) !== false )
			{
			?>
			<!-- Brought to you by the  Really Easy Slider Wordpress Plugin -->
			<script type="text/javascript">
				jQuery(document).ready(function()
				{
					jQuery('#cd-res-slider').easySlider({
						auto:			<?php echo $cd_res_options['res_auto']; ?>,
						continuous:		<?php echo $cd_res_options['res_continuous']; ?>,
						prevId:			'<?php echo $cd_res_options['res_previd']; ?>',
						prevText:		'<?php echo $cd_res_options['res_prevtext']; ?>',
						nextId:			'<?php echo $cd_res_options['res_nextid']; ?>',
						nextText:		'<?php echo $cd_res_options['res_nexttext']; ?>',
						constrolsShow:	<?php echo $cd_res_options['res_controlshow']; ?>,
						speed:			<?php echo $cd_res_options['res_speed']; ?>,
						pause:			<?php echo $cd_res_options['res_pause']; ?>,
						numeric:		<?php echo $cd_res_options['res_numeric']; ?>,
						numericId:		'<?php echo $cd_res_options['res_controlsid']; ?>',
						controlsBefore:	'<div class="<?php echo $cd_res_options['res_control_container_class']; ?>">',
						controlsAfter:  '</div>'
						
					});
				});
			
			</script>
			
			<?php
			}	
			
		}
		
		/**
		* Function for the Shortcode
		*/
		function shortcode( $atts, $content=null )
		{
			$defaults = array(
				'posts' 	=> 'null',
				'width' 	=> 500,
				'height' 	=> 300
			);
			
			extract( shortcode_atts( $defaults, $atts ) );
			
			//bit of house keeping...
			$width = CD_Res_Front_Class::clean_number( $width );
			$height = CD_Res_Front_Class::clean_number( $height );
			
			if( $posts == 'null' )
			{  	
				return 'no posts selected!'; // get out of here if the post attribute is empty
			}
			else
			{
				$posts_array = CD_Res_Front_Class::clean_array( $posts );  // clean an return an array of post ids
				if( !empty( $posts_array ) )
				{
					foreach( $posts_array as $g_post )
					{
						$post = get_post( $g_post, 'OBJECT' );
				    	if( $post ) $slider_posts[] = $post;
					}
				}
				if( !empty( $slider_posts ) )
				{
					$content = '<div id="cd-res-slider">';
					$content .= '<ul>';
					foreach( $slider_posts as $s_post )
					{
						// Some variables for later use
						$image = CD_Res_Front_Class::find_thumb( $s_post->ID );
						$title = get_the_title( $s_post->ID );
						$link = get_permalink( $s_post->ID );
						
						// Start assembling the content to return
						$content .= '<li style="width:' . $width .'px;height:' . $height . 'px">';
						$content .= '<a href="' . $link . '"><img src=' . CD_RES_URL . 'inc/thumb.php?src=' . $image . '&w=' . $width . '&h=' . $height . ' &zc=1" alt="' . $title .'" /></a>';
						$content .= '<div class="cd-res-content-container"><p><a href="' . $link . '">' . $title . '</a></p></div>';
						$content .= '</li>';
					}
					$content .= '</ul>';
					$content .= '</div>';
					return $content;
				}
				else
				{
					return 'No posts found!';	
				}
			}
			
			
		}
		
		/**
		* Helper function to find and return the post thumbnail url
		*/
		function find_thumb( $post_id )
		{
			if( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post_id ) ) 
			{
				$thumb_id = get_post_thumbnail_id( $post_id ); 
				$attachment = wp_get_attachment_image_src( $thumb_id, 'full' );
				$src = $attachment[0];
				return $src;
			}
			else
			{   
				$args = array( 
					'post_parent' => $post_id, 
					'post_type' => 'attachment', 
					'post_mime_type' => 'image', 
					'numberposts' => 1 
				);
				$attachments = get_children( $args );
				if( !empty( $attachments ) )
				{
					foreach( $attachments as $attachment )
					{
						$image = wp_get_attachment_image_src( $attachment->ID, 'full' );
						$src = $image[0];
					}
					return $src;
				}
				else
				{
					return;
				}
			}
		}
		
		/**
		*  Helper function to clean post inputs
		*/
		function clean_array( $in )
		{
			$array = explode( ',', $in );
			$clean = array();
			foreach( $array as $item )
			{
				$item = trim( $item );
				if( absint( $item ) == 0 ) continue;
				$clean[] = absint( $item );
			}
			
			return $clean;		
		}
		
		function clean_number( $in )
		{
			$in = trim( $in );
			$clean = absint( $in );
			if( $clean != 0 )
			{
				return 	$clean;
			}
			else
			{
				return 250;	
			}
		}


	}
	
	$cd_res_front_class = new CD_Res_Front_Class();
}


?>