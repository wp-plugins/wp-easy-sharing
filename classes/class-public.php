<?php
class ES_Public {
	
	public function __construct() {
		add_filter( 'the_content', array( $this, 'add_links_after_content' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ), 99 );
		add_shortcode( 'wpe_sharing',array($this,'social_sharing'));
	}
	
	public function add_links_after_content( $content )
	{
		$opts = ES_get_options();
		$show_buttons = false;
		
		if( ! empty( $opts['auto_add_post_types'] ) && in_array( get_post_type(), $opts['auto_add_post_types'] ) && is_singular( $opts['auto_add_post_types'] ) ) {
			$show_buttons = true;
		}
			
		$show_buttons = apply_filters( 'ES_display', $show_buttons );
	
		if( ! $show_buttons ) {
			return $content;
		}
		$opts['icon_order']=get_option('wes_wpe_sharing');
		return $content . $this->social_sharing($opts);
	}
	
	public function load_assets() 
	{
		$opts = ES_get_options();
		foreach ($opts['load_esset'] as $static){
			if($static == 'load_css'){
				wp_enqueue_style( 'wp-easy-sharing', ES_PLUGIN_URL . 'assets/socialshare.css', array(), ES_VERSION );
			}	
			if($static == 'load_js'){
				wp_enqueue_script( 'wp-easy-sharing', ES_PLUGIN_URL . 'assets/socialshare.js', array(), ES_VERSION, true );				
			}		
		}
	}

	public function social_sharing( $atts=array() ) {
		extract(shortcode_atts(array(
				'social_options' => 'twitter, facebook, linkedin',
				'twitter_username' => '',
				'twitter_text' => __( '', 'easy-sharing' ),
				'facebook_text' => __( '', 'easy-sharing' ),
				'googleplus_text' => __( '', 'easy-sharing' ),
				'linkedin_text' => __('', 'easy-sharing' ),
				'pinterest_text'=>__('','easy-sharing'),
				'social_image'=> '', 
				'icon_order'=>'f,t,g,l,p',
				'show_icons'=>'0',
				'before_button_text'=>''
		),$atts));

		if(!is_array($social_options))
			$social_options = array_filter( array_map( 'trim', explode( ',',$social_options ) ) );
		
		remove_filter('the_title','wptexturize');
		$title = urlencode(html_entity_decode(get_the_title()));
		add_filter('the_title','wptexturize');
		
		$url = urlencode( get_permalink() );
	
		$loadjs='';
		
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'medium' );
		$thumb_url = $thumb['0'];
		if($thumb_url == ''){
			if(isset($atts['pinterest_image']) && $atts['pinterest_image'] == ''){
				$thumb_url = ES_PLUGIN_URL.'assets/blank.jpg';								
			}
			else{
				$thumb_url = isset($atts['pinterest_image'])?$atts['pinterest_image']:'';	
			}
		}
		if($social_image == ''){
			$social_image = $thumb_url;
		}
		$social_image = urlencode($social_image);
		
		$opts=ES_get_options();
		foreach ($opts['load_esset'] as $static){
		    if($static == 'load_js'){
		       $loadjs='onclick="return ES_plugin_loadpopup_js(this);"';
		    }
		}
		
		$ssbutton_facebook='button-facebook';
		$ssbutton_twitter='button-twitter';
		$ssbutton_googleplus='button-googleplus';
		$ssbutton_linkedin='button-linkedin';
		$ssbutton_pinterest='button-pinterest';
		$wpes_sharing='';
		if($show_icons){
			$wpes_sharing='wpes-easy-sharing';
			$ssbutton_facebook='wpes-button-facebook';
			$ssbutton_twitter='wpes-button-twitter';
			$ssbutton_googleplus='wpes-button-googleplus';
			$ssbutton_linkedin='wpes-button-linkedin';	
			$ssbutton_pinterest='wpes-button-pinterest';
		}
		$icon_order=explode(',',$icon_order);
		ob_start();
		?>
		<div class="easy-sharing <?php echo $wpes_sharing;?>">
			<?php if(!empty($before_button_text)):?>
			<span><?php echo $before_button_text; ?></span>
	        <?php endif;?>
	        <?php 
	        foreach($icon_order as $o) {
	        	switch($o) {
	        		case 'f':
	        			if(in_array('facebook', $social_options)){
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="<?php echo $ssbutton_facebook;?>" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" ><?php echo $facebook_text; ?></a><?php
	        			}
	        		break;
	        		case 't':
	        			if(in_array('twitter', $social_options)){
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="<?php echo $ssbutton_twitter;?>" href="http://twitter.com/intent/tweet/?text=<?php echo $title; ?>&url=<?php echo $url; ?><?php if(!empty($twitter_username)) {  echo '&via=' . $twitter_username; } ?>" target="_blank"><?php echo $twitter_text; ?></a><?php
	        			}
	        		break;
	        		case 'g':
	        			if(in_array('googleplus', $social_options)){
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="<?php echo $ssbutton_googleplus;?>" href="https://plus.google.com/share?url=<?php echo $url; ?>" target="_blank" ><?php echo $googleplus_text; ?></a><?php
	        			}
	        		break;
					case 'l':
						if(in_array('linkedin', $social_options)){
							?><a <?php echo $loadjs;?> rel="external nofollow" class="<?php echo $ssbutton_linkedin;?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo substr($url,0,1024);?>&title=<?php echo substr($title,0,200);?>" target="_blank" ><?php echo $linkedin_text; ?></a><?php
						}
	        		break;
	        		case 'p':
	        			if(in_array('pinterest', $social_options)){
	        				?><a <?php echo $loadjs;?> rel="external nofollow" class="<?php echo $ssbutton_pinterest;?>" href="http://pinterest.com/pin/create/button/?url=<?php echo $url;?>&media=<?php echo $social_image;?>&description=<?php echo $title;?>" target="_blank" ><?php echo $pinterest_text; ?></a><?php
	        			}
	        		break;
	        	}
	        } ?>
	    </div>
	    <?php
	  	$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
}