
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-pdf_session_fields\" class=\"ahlu-pdf_session\" />");
					window.ahlu_pdf_session = {
						add : function(val){
							var a = jQuery(".ahlu-pdf_session").val();
							jQuery(".ahlu-pdf_session").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			