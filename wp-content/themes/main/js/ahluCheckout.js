var ahluCheckout = function(){
		var method = {
			
			public :{
				user :{
				  completed :false,
				  getUser : function(){ //get user
				  	 return this.mode;
				  },
				  mode : null,
				  activated : function(c,f){
				  	var me = this;
				  	var choice = {
				  		guest : function(){
				  			me.completed = true;
				  			me.mode = "guest";
				  		},
				  		register: function(){
				  			if(f instanceof Function){
				  				f.call(me);
				  			}
				  		},
				  		login : function(){
				  			if(f instanceof Function){
				  				f.call(me);
				  			}
				  		}
				  	};
				  	if(choice[c] !=undefined)
				  		choice[c]();

				  	return me;
				  }
				},
				billing :{
					completed :false,
					url : null,
					setURL : function(url){
						this.url = url ;
						return this;
					},
					data :null,
				    activated : function(form,f){
				    	var me=this;
						if(!this.completed){
							this.completed =true;
							var bill = ahluForm({
								url: this.url,
								mode : "suggest",
								handler : form
							}).init().validate({
								fromServer : function(e){
									me.data = this;
									if(f instanceof Function){
										f.call(this,e);
									}
								}
							});
						}else{
							if(f instanceof Function){
								f.call(this,me.data);
							}
						}
				    	return this;
				    },
					getData : function(){
						return me.data;
					}
				},
				shipping :{
					completed :false,
					url : null,
					setURL : function(url){
						this.url = url ;
						return this;
					},
					data :null,
				    activated : function(form,f){
				    	var me=this;

				    	var bill = ahluForm({
							url: this.url,
							mode : "suggest",
							handler : form
						}).init().validate({
							fromServer : function(e){
								me.data = this;
								if(f instanceof Function){
						  			f.call(this,e);
						  		}
							}
						});

				    	return this;
				    },
					getData : function(){
						return me.data;
					}
				},
				shipping_price :{
				
				},
				payment :{
				
				},
				review : {
					
				}
			}
		};
		
		return method.public;
};