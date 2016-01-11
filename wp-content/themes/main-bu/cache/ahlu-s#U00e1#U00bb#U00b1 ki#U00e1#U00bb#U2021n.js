
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-sự kiện_fields\" class=\"ahlu-sự kiện\" />");
					window.ahlu_sự kiện = {
						add : function(val){
							var a = jQuery(".ahlu-sự kiện").val();
							jQuery(".ahlu-sự kiện").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			