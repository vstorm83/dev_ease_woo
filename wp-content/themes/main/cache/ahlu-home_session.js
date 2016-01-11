
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-home_session_fields\" class=\"ahlu-home_session\" />");
					window.ahlu_home_session = {
						add : function(val){
							var a = jQuery(".ahlu-home_session").val();
							jQuery(".ahlu-home_session").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			