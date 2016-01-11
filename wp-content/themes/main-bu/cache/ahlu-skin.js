
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-skin_fields\" class=\"ahlu-skin\" />");
					window.ahlu_skin = {
						add : function(val){
							var a = jQuery(".ahlu-skin").val();
							jQuery(".ahlu-skin").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			