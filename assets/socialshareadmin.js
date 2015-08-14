jQuery(document).ready(function($){
	if ($('#sc_img').length > 0) {
		if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
	        jQuery('.wrap').on('click', '#sc_img', function(e) {
	            e.preventDefault();
	            var button = jQuery(this);
	            var id = button.prev();
	            wp.media.editor.send.attachment = function(props, attachment) {
	               id.val(attachment.url);
	               $('#sc_img_src').html('<img src="'+attachment.url+'" width="100px" />');
	            };
	            wp.media.editor.open(button);
	            return false;
	        });
		}
	}
	$("#rc_img").on('click',function(){
		$('#pinterest_image').val('');
		$('#sc_img_src').html('');
	});
});