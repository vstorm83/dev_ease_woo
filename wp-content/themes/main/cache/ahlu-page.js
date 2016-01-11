
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-page_fields\" class=\"ahlu-page\" />");
					window.ahlu_page = {
						add : function(val){
							var a = jQuery(".ahlu-page").val();
							jQuery(".ahlu-page").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			