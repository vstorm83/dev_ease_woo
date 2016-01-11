
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-portal_fields\" class=\"ahlu-portal\" />");
					window.ahlu_portal = {
						add : function(val){
							var a = jQuery(".ahlu-portal").val();
							jQuery(".ahlu-portal").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			