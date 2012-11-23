
(function($){
   	
   $.fn.kkstarratings = function(options) {
	   
		var kksr_settings = {
			'ajaxurl' : null,
			'nonce' : null,
			'func' : null,
			'grs' : false,
			'tooltip' : true,
			'tooltips' : {
				"0":{"tip": "Poor", "color": "red"},
				"1":{"tip": "Fair", "color": "brown"},
				"2":{"tip": "Average", "color": "orange"},
				"3":{"tip": "Good", "color": "blue"},
				"4":{"tip": "Excellent", "color": "green"}
			},
			'msg' : 'Rate this post',
			'fuelspeed' : 400,
			'thankyou' : 'Thank you for rating.',
			'error_msg' : 'An error occured.'
		};
	
		return this.each(function() {        
		
			if ( options ) { 
			  $.extend( kksr_settings, options );
			}

			var obj = $(this);
			
			function kksr_animate(obj)
			{
				if(!obj.hasClass('disabled'))
				{
					var legend = $('.kksr-legend', obj).html();
					var fuel = $('.kksr-fuel', obj).css('width');
					$('.kksr-stars a', obj).hover( function(){
						var stars = $(this).attr('href').split('#')[1];
						if(kksr_settings.tooltip!=0)
						{
							if(kksr_settings.tooltips[stars-1]!=null)
							{
								$('.kksr-legend', obj).html('<span style="color:'+kksr_settings.tooltips[stars-1].color+'">'+kksr_settings.tooltips[stars-1].tip+'</span>');
							}
							else
							{
								$('.kksr-legend', obj).html(legend);
							}
						}
						$('.kksr-fuel', obj).stop(true,true).css('width', '0%');
						$('.kksr-stars a', obj).each(function(index, element) {
							var a = $(this);
							var s = a.attr('href').split('#')[1];
							if(parseInt(s)<=parseInt(stars))
							{
								$('.kksr-stars a', obj).stop(true, true);
								a.hide().addClass('kksr-star').addClass('orange').fadeIn('fast');
							}
						});
					}, function(){
						$('.kksr-stars a', obj).removeClass('kksr-star').removeClass('orange');
						if(kksr_settings.tooltip!=0) $('.kksr-legend', obj).html(legend);
						$('.kksr-fuel', obj).stop(true,true).animate({'width':fuel}, kksr_settings.fuelspeed);
					}).unbind('click').click( function(){
						return kksr_click(obj, $(this).attr('href').split('#')[1]);
					});
				}
				else
				{
					$('.kksr-stars a', obj).unbind('click').click( function(){ return false; });
				}
			}
			
			function kksr_update(obj, per, legend, disable, is_fetch)
			{
				if(disable=='true')
				{
					$('.kksr-fuel', obj).removeClass('yellow').addClass('orange');
				}
				$('.kksr-fuel', obj).stop(true, true).animate({'width':per}, kksr_settings.fuelspeed, 'linear', function(){
					if(disable=='true')
					{
						obj.addClass('disabled');
						$('.kksr-stars a', obj).unbind('hover');
					}
					if(!kksr_settings.grs || !is_fetch)
					{
						$('.kksr-legend', obj).stop(true,true).hide().html(legend?legend:kksr_settings.msg).fadeIn('slow', function(){
							kksr_animate(obj);
						});
					}
					else
					{
						kksr_animate(obj);
					}
				});
			}
			
			function kksr_click(obj, stars)
			{
				$('.kksr-stars a', obj).unbind('hover').unbind('click').removeClass('kksr-star').removeClass('orange').click( function(){ return false; });
				
				var legend = $('.kksr-legend', obj).html();
				var fuel = $('.kksr-fuel', obj).css('width');
				
				kksr_fetch(obj, stars, fuel, legend, false);
				
				return false;
			}
			
			function kksr_fetch(obj, stars, fallback_fuel, fallback_legend, is_fetch)
			{
				$.ajax({
					url: kksr_settings.ajaxurl,
					data: 'action='+kksr_settings.func+'&id='+obj.attr('data-id')+'&stars='+stars+'&_wpnonce='+kksr_settings.nonce,
					type: "post",
					dataType: "json",
					beforeSend: function(){
						$('.kksr-fuel', obj).animate({'width':'0%'}, kksr_settings.fuelspeed);
						if(stars)
						{
							$('.kksr-legend', obj).fadeOut('fast', function(){
								$('.kksr-legend', obj).html('<span style="color: green">'+kksr_settings.thankyou+'</span>');
							}).fadeIn('slow');
						}
					},
					success: function(response){
						if(response.success=='true')
						{
							kksr_update(obj, response.fuel+'%', response.legend, response.disable, is_fetch);
						}
						else
						{
							kksr_update(obj, fallback_fuel, fallback_legend, false, is_fetch);
						}
					},
					complete: function(){
						
					},
					error: function(e){
						$('.kksr-legend', obj).fadeOut('fast', function(){
							$('.kksr-legend', obj).html('<span style="color: red">'+kksr_settings.error_msg+'</span>');
						}).fadeIn('slow', function(){
							kksr_update(obj, fallback_fuel, fallback_legend, false, is_fetch);
						});
					}
				});
			}
			
			kksr_fetch(obj, 0, '0%', kksr_settings.msg, true);

		});
		
   };
   
})( jQuery );


jQuery(document).ready( function($){
	$('.kk-star-ratings').kkstarratings({
		'ajaxurl' : bhittani_plugin_kksr_js.ajaxurl,
		'func' : bhittani_plugin_kksr_js.func,
		'nonce' : bhittani_plugin_kksr_js.nonce,
		'grs' : bhittani_plugin_kksr_js.grs,
		'tooltip' : bhittani_plugin_kksr_js.tooltip,
		'tooltips' : bhittani_plugin_kksr_js.tooltips,
		'msg' : bhittani_plugin_kksr_js.msg,
		'fuelspeed' : bhittani_plugin_kksr_js.fuelspeed,
		'thankyou' : bhittani_plugin_kksr_js.thankyou,
		'error_msg' : bhittani_plugin_kksr_js.error_msg
	});
});