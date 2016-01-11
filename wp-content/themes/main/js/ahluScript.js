///////////////
	$(document).ready(function(){
			$("#back-to-top").eq(0).remove();

			if(window.ahluForm){
				$(".note input").on("change focus blur keyup",function(){
				var $this =$(this);
				$this.attr("placeholder",$this.attr("placeholder"));
				
			});
			//remove item
			$(".status a").on("click",function(e){
				//$("#myModal").modal('show');

				jQuery(".form_enquires .well").remove();
				e.preventDefault();
				
				var $this = $(this);
				var tr = $this.closest("tr");
				var id = tr.attr("data-id");

				if(confirm("Sure you want to delete item?")){
					
					//send ajax to remove item
						//delete id
						jQuery(".form_enquires").prepend("<p class='well masklayer'><img style='width:25px;' src='https://media.giphy.com/media/12MFb8mvjldCSc/giphy.gif' /> deleting...</p>");
						receiveFromURL(WC_url,{id_product:id,cart_hash:WC_cart_hash},function(data){
							if(data!="error"){
								setTimeout(function(){
									jQuery(".form_enquires .well").remove();
									
									//update mini cart
									$(document).trigger("mini-cart",{callback:function(/**/){
										jQuery(".form_enquires").prepend("<p class='well '>"+data+"</p>");
										var args = arguments[0];
										$(document).trigger("mini-cart-update",{html:args.fragments["div.widget_shopping_cart_content"],callback:function(count){
												if(count==0){
													//check if empty

													jQuery(".check-none").show("slow");
													jQuery(".form_enquires").hide("slow");
												}
												tr.remove();
												jQuery(".form_enquires .well").remove();
											}
										});
										
									}});

								},10000);

								$(window).scrollTop( 0 );
							}
						},true);
				}
			});
			
			var checkout = ahluForm({
				url:document.location.href,
				mode : "suggest",
				handler : jQuery(".form_enquires .submit")
			}).init().validate({
				fromServer : function(msg){
					checkout.enabled();

					if(msg.code==1){
						jQuery(".form_enquires").prepend("<p class='well message-response'><strong>Enquiry	has been sent - a member of the	EASE team will reply shortly</strong></p>");
						
						//clear cart
						receiveFromURL(WC_url,{clear_cart:true,cart_hash:WC_cart_hash},function(data){
							if(data!="error"){
								setTimeout(function(){	
									$( ".cart-holder .total_pro" ).find("span").html("0");
									jQuery(".form_enquires").remove();
									jQuery(".check-none").show("slow");
								},4000);
								$(window).scrollTop( 0 );
							}else{
								jQuery(".form_enquires").prepend("<p class='well'><strong>"+data+"</strong></p>");
							}
						},true);
					}else{
						var msg = 'Sorry, your request can not send.';
						if(data.error!=""){
							msg = msg.error;
							jQuery(".form_enquires").prepend("<p class='well'><strong>"+msg+"<strong></p>");
							jQuery(".form_enquires").remove();
						}else{
							jQuery(".form_enquires").prepend("<p class='well'><strong>"+msg+"<strong></p>");
							jQuery(".form_enquires .text").val('');
								setTimeout(function(){
								jQuery(".form_enquires .well").remove();
							},3000*60);
						}
						
					}
					
					$( "body" ).scrollTop( 0 );
					
				},
				rules: {
					email: {
						required: true,
						email: true
					}
				},
				messages: {
					email: "Please enter a valid email address"
				}
			});
		}
		
	});