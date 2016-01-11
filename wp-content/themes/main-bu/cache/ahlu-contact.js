
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-contact_fields\" class=\"ahlu-contact\" />");
					window.ahlu_contact = {
						add : function(val){
							var a = jQuery(".ahlu-contact").val();
							jQuery(".ahlu-contact").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			