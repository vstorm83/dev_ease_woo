
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-blog_fields\" class=\"ahlu-blog\" />");
					window.ahlu_blog = {
						add : function(val){
							var a = jQuery(".ahlu-blog").val();
							jQuery(".ahlu-blog").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			