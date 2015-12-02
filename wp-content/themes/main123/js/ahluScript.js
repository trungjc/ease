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
				e.preventDefault();
				
				var $this = $(this);
				var tr = $this.closest("tr");
				var id = tr.attr("data-id");

				if(confirm("Are you sure to delete item?")){
					
					//send ajax to remove item
						//delete id
						receiveFromURL(WC_url,{id_product:id},function(data){
							if(data!="error"){
								jQuery(".form_enquires").prepend("<p class='well'>"+data+"</p>");
								tr.remove();
								setTimeout(function(){
									jQuery(".form_message .well").remove();
									
									//update mini cart
								$(document).trigger("mini-cart",{callback:function(/**/){
									var args = arguments[0];
									$(document).trigger("mini-cart-update",{html:args.fragments["div.widget_shopping_cart_content"]});
									
								}});

								},1000);
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
						jQuery(".form_enquires").prepend("<p class='well'><strong>Thank you, your request have been sent.</strong></p>");
						
						//clear cart
						receiveFromURL(WC_url,{clear_cart:true},function(data){
							if(data!="error"){
								setTimeout(function(){
									document.location.href = data;
								},3000);
							}else{
								jQuery(".form_enquires").prepend("<p class='well'><strong>"+data+"</strong></p>");
							}
						},true);
					}else{
						jQuery(".form_enquires").prepend("<p class='well'><strong>Sorry, your request can not send.</strong></p>");
						jQuery(".form_enquires .text").val('');
					}
					setTimeout(function(){
							jQuery(".form_message .well").remove();
					},1000*60);
					
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