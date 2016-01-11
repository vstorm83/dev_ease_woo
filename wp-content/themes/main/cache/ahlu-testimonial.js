
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-testimonial_fields\" class=\"ahlu-testimonial\" />");
					window.ahlu_testimonial = {
						add : function(val){
							var a = jQuery(".ahlu-testimonial").val();
							jQuery(".ahlu-testimonial").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			