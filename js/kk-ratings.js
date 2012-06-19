/* ***************************
  kk Star Ratings js - start
*************************** */

jQuery(document).ready( function($){
	$('.kk-ratings').kkratings({
		'path' : kk_ratings_settings.path,
		'nonce' : kk_ratings_settings.nonce,
		'position' : kk_ratings_settings.pos
	});
});

(function( $ ){
   	
   $.fn.kkratings = function( options ) {
	   
		settings = {
			'path' : null,
			'nonce' : null,
			'root' : null,
			'position' : 'top-left'
		};
	
		return this.each(function() {        
		
			if ( options ) { 
			  $.extend( settings, options );
			}

			var obj = this;
			
			if($.browser.msie)
			    $(obj).css({'background-color' : '#FFF' });
				
			switch(settings.position)
			{
				case 'top-left':
				case 'bottom-left': $(obj).css({'float':'left'}); break;
				case 'top-right':
				case 'bottom-right': $(obj).css({'float':'right'}); break;
			}
			
			// ANIMATION
			
			$(".hover-panel > a", obj).mouseover( function() {
				  var obj = $(this).parent().parent();
				  if(obj.hasClass('open'))
				  {
					  $('.stars-turned-on', obj).stop(true, true);
					  $('.stars-turned-on', obj).hide();
					  $(this).addClass('hovered-rating');
					  var flag = true;
					  $(".hover-panel > a", obj).each( function(index) {
						  if(flag)
						  {
							  $(".hover-panel > a", obj).stop(true, true);
							  $(this).hide();
							  $(this).addClass('hovered-star')
							  $(this).fadeIn('slow');
						  }
						  if($(this).hasClass('hovered-rating')==true)
						  {
							  flag = false;
						  }
					  });
					  $(this).removeClass('hovered-rating');
				  }
			  });

			  $(".hover-panel > a", obj).mouseout( function() {
				    var obj = $(this).parent().parent();
					var stars = $(".hovered-star", obj).get();  
					for(var i = stars.length - 1; i >= 0; i--)
					{
					    $(".hovered-star", obj).stop(true, true);
					    $(".hovered-star:eq("+i+")", obj).fadeOut('fast', function() {
						    $(this).removeClass('hovered-star') ;
						    $(this).css({'display':'block'});
						});
					}
					$('.stars-turned-on', obj).fadeIn(1000);
			  });
            
			var id = $("span:eq(0)",obj).html();
			
			$(obj).addClass('open');
			$.ajax({
				   type: "POST",
				   url: settings.path+"ajax/kk-ratings-ajax.php",
				   data: '_ajax_nonce='+settings.nonce+"&op=get&id="+id,
				   dataType: "xml",
				   success: function(msg){
					   if($(msg).find('success').text())
					   {
						   var per = $(msg).find('percentage').text();
						   var legend = $(msg).find('legend').text();
						   var open = $(msg).find('open').text();
						   
						   if($.browser.opera)
						      $('.stars-turned-on',obj).css({'width':per+'%'});
						   else
						   {
							   $('.stars-turned-on',obj).animate({
								   'width' : '0%'
							   }, 'slow', function(){
								   $('.stars-turned-on',obj).animate({
									   'width' : per+'%'
								   }, 'slow');
							   });
						   }
						   

						   $('.casting-desc',obj).text(legend);
                           
						   if(open=='no')
						   {
							   $(obj).removeClass('open');
							   $('.stars-turned-on',obj).addClass('stars-turned-strict');
						   }
						   else
						   {
							   $(obj).addClass('open');
							   $('.stars-turned-on',obj).removeClass('stars-turned-strict');
						   }
					   }
					   else
					   {

					   }
				   }
			  });

                var busy = false;
				$(".hover-panel > a", obj).click( function() {
					  var obj = $(this).parent().parent();
					  var percentage = 0;
					  var flag = true;
					  if(obj.hasClass('open'))
					  {
						  var current = $(this);
						  var starsT = current.attr('rel');
						  var starsTT = starsT.split('-');
						  var stars = parseInt(starsTT[1]);
						  var id = $("span:eq(0)",obj).text();
						  if(!busy)
						  {
					      busy = true;
						  $.ajax({
							   type: "POST",
							   url: settings.path+"ajax/kk-ratings-ajax.php",
							   data: '_ajax_nonce='+settings.nonce+"&op=put&id="+id+'&stars='+stars,
							   dataType: "xml",
							   beforeSend: function(){
									obj.fadeTo('slow',0.3);
									$('.casting-desc',obj).fadeOut('fast');  
							   },
							   success: function(msg){
								   if($(msg).find('success').text())
								   {
									   var per = $(msg).find('percentage').text();
									   var legend = $(msg).find('legend').text();
									   var open = $(msg).find('open').text();
									   
									   percentage = per;
									   
									   $('.casting-desc',obj).text(legend);
		
									   if(open=='no')
									   {
										   obj.removeClass('open');
										   $('.stars-turned-on',obj).addClass('stars-turned-strict');
									   } 
									   else
									   {
										   obj.addClass('open');
										   $('.stars-turned-on',obj).removeClass('stars-turned-strict');
									   }
								   }
								   else
								   {
										flag = false;
								   }
							   },
							   complete: function(){
							       if(flag)
								   {
									   obj.stop(true, true);
									   obj.fadeTo('slow',1, function(){
										   $(".casting-thanks",obj).fadeIn('slow').delay(1000).fadeOut('slow', function(){
											   $('.casting-desc',obj).fadeIn('slow'); 
										   });
										   
										   if($.browser.opera)
											  $('.stars-turned-on',obj).css({'width':percentage+'%'});
										   else
										   {
											   $('.stars-turned-on',obj).animate({
												   'width' : '0%'
											   }, 'slow', function(){
												   $('.stars-turned-on',obj).animate({
													   'width' : percentage+'%'
												   }, 'slow');
											   });
										   }
									   });
								   }
								   else 
								   {
									   obj.stop(true, true);
									   obj.fadeTo('slow',1, function(){
										   $(".casting-error",obj).fadeIn('slow').delay(1000).fadeOut('slow', function(){
											   $('.casting-desc',obj).fadeIn('slow'); 
										   });
									   });
								   }								   
							   }
						  });
						  }
					  }
					  return false;
				});

		});
		
   };
   
})( jQuery );


/* ***************************
  kk Star Ratings js - end
*************************** */