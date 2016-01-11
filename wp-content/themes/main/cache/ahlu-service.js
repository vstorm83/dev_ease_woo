
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-service_fields\" class=\"ahlu-service\" />");
					window.ahlu_service = {
						add : function(val){
							var a = jQuery(".ahlu-service").val();
							jQuery(".ahlu-service").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			