
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-news_fields\" class=\"ahlu-news\" />");
					window.ahlu_news = {
						add : function(val){
							var a = jQuery(".ahlu-news").val();
							jQuery(".ahlu-news").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			