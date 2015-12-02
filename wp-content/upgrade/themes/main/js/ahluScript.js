///////////////
	jQuery(document).ready(function(){
		if(window.ahluForm){
			
				var search = ahluForm({
					url:"search",
					mode : "inline",
					holdForm: true,
					handler : jQuery(".search-form_it")
				}).init().on("change",function(e,me){

					if(jQuery(this).val()==""){
						alert("Empty field.");
						return false;
					}

				}).on("success",function(form){
					document.location.href = form.attr("action")+"?"+form.serialize();
				}).process(function(form,settings){
				
				});
				

			
			var contact = ahluForm({
				url:document.location.href,
				mode : "suggest",
				handler : jQuery(".wpcf7-form")
			}).init().validate({
				fromServer : function(msg){
					contact.enabled();

					if(msg.code==1){
						jQuery(".wpcf7-form").prepend("<strong class='form_message'>Message succesfully sent. We'll reply as soon as we can.</strong>");
						jQuery(".wpcf7-form .text").val('');					
					}else{
						jQuery(".wpcf7-form").prepend("<strong>Sorry, your request can not send.</strong>");
						 jQuery(".wpcf7-form .text").val('');
					}
					setTimeout(function(){
							jQuery(".form_message").remove();
						},500*60);
					
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
			
			var comment = ahluForm({
				url:document.location.href,
				mode : "suggest",
				handler : jQuery(".commentformblog")
			}).init().validate({
				fromServer : function(msg){
					comment.enabled();

					if(msg.code==1){
						document.location.href=document.location.href;

						
						
					}else{
						jQuery(".commentformblog").prepend("<strong>Sorry, your request can not send.</strong>");
						 jQuery(".commentformblog .text").val('');
					}
					setTimeout(function(){
							jQuery(".form_message").remove();
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
			
			var newsletter = ahluForm({
				url:"http://localhost/asm/home/newsletter",
				mode : "suggest",
				handler : jQuery("#nsu-form-0")
			}).init().validate({
				fromServer : function(msg){
					newsletter.enabled();

					if(msg.code==1){
						jQuery("#nsu-form-0").prepend("<strong style='margin-bottom: 10px;display: block;' class='form_message'>Message succesfully sent.</strong>");
						jQuery("#nsu-form-0 #email").val('');
						
					}else{
						jQuery("#nsu-form-0").prepend("<strong>Sorry, your request can not send.</strong>");
						jQuery("#nsu-form-0 #email").val('');
					}
					setTimeout(function(){
						jQuery(".form_message").remove();
					},400*60);
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
	
	
	jQuery(document).ready(function(){
		
		$(".add_to_cart_button").addClass('btn').addClass('btn-primary');
		
		//look up content
		
		var setInterval = function(time,f){
			var c = {
				count :0
			};
			var id = window.setInterval(function(){
				if(f.call(c)==true){
					window.clearInterval(id);
				}
			},time);
		};
		
		function refreshCart(){
			setInterval(3000,function(){
				
				//check has content
				var list = $(".widget_shopping_cart_content ul");
				if(list.length==0){
					this.count++;
					return false;
				}else{
					this.count = 0;
					//action here
					var buttons = $(".widget_shopping_cart_content .buttons");
					//add class
					if(!buttons.find(".button").hasClass("btn")){
						buttons.find(".button").addClass("btn").addClass("btn-primary");
						$(".widget_shopping_cart_content .total .amount").addClass("price");
						
							////////Cart //////////
							jQuery('.widget_shopping_cart_content .remove').attr("onclick","return add_to_cart(this);");
							
					}
					
					//
					
					//get all product
					var quantity = 0;
					list.find("li .quantity").each(function(i,v){
						var html = v.innerHTML;
						var find = html.indexOf("<span");
						var text = find!=-1 ? html.substring(0,find): text;
						quantity += parseInt(text.trim());
					});
					$(".productCount").html("<strong> </strong> "+quantity);
					//hide empty cart
					$(".hide_cart_widget_if_empty").hide();
					
					return true;
				}
			});
		}
		refreshCart();
		//listen on add to cart click
		$("a.add_to_cart_button").live("click",function(e){
			var $me =$(this);
			var parent = $me.closest("li");
			//wait cart refresh
			refreshCart();
			
			setInterval(1000,function(){
				//waiting to see the message show
				var btn = parent.find("a.wc-forward");
				if(btn.length==1){
					btn.hide().html('<i class="fa fa-check-square-o"></i>').css({color: "orange","font-size": "38px"}).show("slow");
					btn.click(function(evt){
						evt.preventDefault();
						var url = $(this).attr("href");
						url = url.substring(0,url.length-1);
						document.location.href = url+".html";
					});
					return true;
				}
			});
			
			$(this).die(e.type);
		});
		
		//listen on add to cart click
		$(".buttonAction a.add_to_cart_button").live("click",function(e){
			e.preventDefault();
			var $me =$(this);
			var parent = $me.closest(".buttonAction");
			//wait cart refresh
			refreshCart();
			
			setInterval(1000,function(){
				//waiting to see the message show
				var btn = parent.find("a.wc-forward");
				if(btn.length==1){
					btn.hide();
					var url = btn.attr("href");
					url = url.substring(0,url.length-1);
					document.location.href = url+".html";
					return true;
				}
			});
			
			$(this).die(e.type);
		});
		
	});
	//////// action on Cart //////////
	function add_to_cart(me){
		var obj = parseQueryString(jQuery(me).attr("href"));
		var product_id = obj.remove_item;
		/*
		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			url: "/wp/shophoa/wp-admin/admin-ajax.php",
			data: { action: "product_remove", 
					product_id: product_id
			},success: function(data){
				console.log(data);
			}
		});
								
		return false;
		*/
		return true;
	}
	function parseQueryString ( queryString ) {
		var a = queryString.indexOf('?');
		var queryString = queryString;
		if(a!=-1){
			queryString = queryString.substring( queryString.indexOf('?') + 1 );
		}
		
		var params = {}, queries, temp, i, l;
	 
		// Split into key/value pairs
		queries = queryString.split("&");
	 
		// Convert the array of strings into an object
		for ( i = 0, l = queries.length; i < l; i++ ) {
			temp = queries[i].split('=');
			params[temp[0]] = temp[1];
		}
	 
		return params;
	}