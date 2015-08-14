<?php



function ES_get_options()
{	
	static $options;

	if( ! $options ) {
		$defaults = array(
			'twitter_username' => "",
			'auto_add_post_types' => array( 'post' ),
			'social_options'=>array('facebook','twitter','googleplus','linkedin','pinterest'),
			'load_esset'=>array('load_css','load_js'),
			'facebook_text'=>"",
			'twitter_text'=>"",
			'googleplus_text'=>"",
			'linkedin_text'=>"",
			'pinterest_text'=>"",
			'pinterest_image'=>"",
			'before_button_text'=>'',
		);

		$db_option = get_option( 'wpe_sharing', array());
		if(!isset($db_option['load_esset'])){
			$db_option['load_esset']=array();
		}
		if(!isset($db_option['social_options'])){
			$db_option['social_options']=array();
		}
		if(!isset($db_option['auto_add_post_types'])){
			$db_option['auto_add_post_types']=array();
		}
	
		if( ! $db_option ) {
			update_option( 'wpe_sharing', $defaults );
		}
		
		$options = wp_parse_args( $db_option, $defaults );
	}
	return $options;
}
add_action('admin_footer','include_icon_order_script');
function include_icon_order_script(){
	wp_enqueue_script( 'jquery-ui-sortable' );
?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$('.dndicon').sortable({
				stop:function(event,ui){
					var new_order='';
					$('.dndicon > div').each(function(e,i){
						new_order += $(i).attr('id')+',';
					});
					new_order = new_order.slice(0,new_order.length-1);
					var ajax_data={'action':'wes_update_icon_order','new_order':new_order};
					$.post(ajaxurl,ajax_data,function(response){});
				}	
			});
		});
	</script>
<?php 	
}

add_action('wp_ajax_wes_update_icon_order','include_icon_order_action');
function include_icon_order_action(){
	update_option('wes_wpe_sharing', rtrim($_POST['new_order'],','));
	die;
}