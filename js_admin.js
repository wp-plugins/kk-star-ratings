
(function($){
   	
   $.fn.kkstarratings_admin = function(options) {
	   
		kksr_admin_settings = {
			'ajaxurl' : null,
			'func' : null,
			'nonce' : null
		};
	
		return this.each(function() {        
		
			if ( options ) { 
			  $.extend( kksr_admin_settings, options );
			}

			var obj = $(this);

			function ajax_post(params, waiting_msg)
			{
				$.ajax({
					url: kksr_admin_settings.ajaxurl,
					data: params,
					type: "post",
					dataType: "json",
					beforeSend: function(){
						bhittani_lightbox_js.lightbox(waiting_msg, 'busy', false);
					},
					success: function(response){
						
					},
					complete: function(){
						bhittani_lightbox_js.lightbox('Done', 'unclose', false);
						setTimeout(function(){bhittani_lightbox_js.lightbox_close()}, 1000);
					},
					error: function(){
						
					}
				});
			}
			
			$('a[rel="save-options"]', obj).click( function(){
				var form = jQuery('form[name="bf_form"]', obj);
				var values = form.serialize();
				var params = values+'&action='+kksr_admin_settings.func+'&_wpnonce='+kksr_admin_settings.nonce;
				ajax_post(params, 'Saving');
				return false;
			});

			$('a[rel="kksr-reset"]', obj).click( function(){
				var form = jQuery('form[name="bf_form"]', obj);
				var values = form.serialize();
				var params = values+'&action='+kksr_admin_settings.func_reset+'&_wpnonce='+kksr_admin_settings.nonce;
				ajax_post(params, 'Flushing');
				$('._kksr_reset._on', obj).parent().fadeOut('slow');
				return false;
			});

			$('a[rel="kksr-reset-all"]', obj).click( function(){
				$('._kksr_reset', obj).removeClass('_off').addClass('_on').val('1');
				return false;
			});

			$('a[rel="kksr-reset-none"]', obj).click( function(){
				$('._kksr_reset', obj).removeClass('_on').addClass('_off').val('0');
				return false;
			});

		});
		
   };
   
})( jQuery );


jQuery(document).ready( function($){
	$('.bhittani-framework').kkstarratings_admin({
		'ajaxurl' : bhittani_plugin_kksr_js_admin.ajaxurl,
		'func' : bhittani_plugin_kksr_js_admin.func,
		'func_reset' : bhittani_plugin_kksr_js_admin.func_reset,
		'nonce' : bhittani_plugin_kksr_js_admin.nonce,
	});
});
