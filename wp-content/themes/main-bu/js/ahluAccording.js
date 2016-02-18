var AccordingForm = function($who,options){
		//set container
		var container = null;
		//override settings
		var settings = jQuery.extend({
				checkAllow : false, // allow for next if true
				disallowAccessToNextSections : false, 
			},options);
		//private
		var according ={
			sections : {index:[],items:{}},
        	currentSection : false,
        	currentIndex : 0,
        	events :{onBack:[],onNext:[]},
			init : function(){
				var $this = this;
				if(!jQuery.fn.AccordingForm){
					jQuery.fn.AccordingForm = function(step,f){
				    	var handler = this;
				    	if($this.events[step]){
				    		$this.events[step].push({selector:this.selector,handler:this,callback:f});
				    		this.live("click",function(e){
				    			e.preventDefault();
			    				if(f instanceof Function){
			    					f.call(method,e);
			    				}
			    				jQuery(this).die("click");
				    		});
				    	}
		    				
				    	return this;
				    };
				}
				jQuery(document).ready(function(){
					container = ($who instanceof Object) ? $who : jQuery($who);
					if(container.length==0){
						//alert("Can not find "+container.selector);	
						return;
					}					//hide all section					container.find(".a-item").hide();
					//get all section
					$this.getItems();
					//choice first
					if(!jQuery($this.sections.index[according.currentIndex]).hasClass("allow")){
						jQuery($this.sections.index[according.currentIndex]).addClass("allow");
					}
					//open first
					method.openSection($this.sections.index[according.currentIndex][0]);

					for(var i in $this.sections.items){
						$this.sections.items[i].find("div.step-title").live("click",$this.sectionClicked);
					}
					
					//$this.setEvent();
				});
			},
			setEvent :function(){
				for(var i in this.events){
					var array = this.events[i];
					if(array.length>0){
						for(var j in array){
							var obj = array[j];
							if(obj.handler!=undefined && obj.handler instanceof Object){
								var f = obj["callback"];
								var handler = obj.handler;
								handler.live("click",function(e){ // we dont use bind, if tag is remove, it is removed too
				    				e.preventDefault();
				    				
				    				if(f instanceof Function){
				    					f.call(method,e);
				    				}
				    				handler.die("click");
						    	});
							}
						}
					}
					
				}
			},
			sectionCompleted : function(section){
				if(according.sections.items[section]){
					if(!jQuery(according.sections.items[section]).hasClass("complete")){
						jQuery(according.sections.items[section]).addClass("complete")
					}
				}
				return this;
			},
			sectionClicked: function(event) {
				var title = jQuery(event.target);
        		var section = title.closest("li");

		        method.openSection(section[0]);
		        //unbind event
		        title.die("click");
		    },			isCompleted : function(){				return (container.find("li.section").length -1)!=container.find("li.complete").length;			},
		    getItems : function(index){
		    	container.find("li.section").each(function(i,item){
		    	   var me = jQuery(this);
		    	   var id = me.attr("id");

				   according.sections.index.push(me);
				   according.sections.items[id] = me;
				});
		    }
		};

		//public
		var method = {
			isAllowed : function(){
				settings.checkAllow = true;
			},
			completedSection : function(){
				according.sections.items[according.currentSection].addClass("complete");
				return this;
			},
        	getIndex :function(){
        		return according.currentIndex;
        	},
        	openSection : function(item){
        		var section = jQuery(item);
    			if(section.length==0){
    				alert("Can not open this tag '"+item+"'.");
    			}else{
    				var id_section = section.attr("id");
	        		if(!section.hasClass("complete")){ //if this section is complete, so we dont need hide
		        		// Check allow
				        if (!settings.checkAllow && !section.hasClass('allow')){
				            return;
				        }else{
				        	//prevent again
				        	settings.checkAllow = false;
				        }
				    }

			        if(id_section != according.currentSection) {
			        	//exist current section
			            this.closeExistingSection();
			            //set new current section
			            according.currentSection = id_section;
			            //add class
			            section.addClass('active');
			            //content
			            var content = section.find('div.a-item');
			            content.show();
			            //Effect.SlideDown(contents[0], {duration:.2});

			            // if dont allowed accesing the rest of sections
			            if (according.disallowAccessToNextSections) {
			                for (var id in according.sections.items) {
			                    if (id!=id_section) {
			                        according.sections.items[id].removeClass('allow');
			                    }
			                }
			            }
			        }
    			}
        		
        	},
        	closeSection : function(section){
        		section = jQuery(section);
        		//remove class
        		section.removeClass('active');
        		//hide the content of this section
		        var content = section.find('div.a-item');
		        content.hide("slow");
        	},
        	nextSection : function(){
        		according.currentIndex = according.currentIndex+1==according.sections.index.length ? 0 : according.currentIndex+1;
        		method.openSection(according.sections.index[according.currentIndex][0]);
        	},
        	prevousSection : function(){
        		according.currentIndex = according.currentIndex-1<=0 ? 0 : according.currentIndex-1;
        		method.openSection(according.sections.index[according.currentIndex][0]);
        	},
        	closeExistingSection :function(){
		        if(according.currentSection) {
		            this.closeSection(according.sections.items[according.currentSection][0]);
		        }
        	}
		};

		according.init();
		return method;
	};