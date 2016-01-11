if(!window.receiveFromURL){
function receiveFromURL (url,data,callback,async){
	if(!async)
	{
		async =false;
	}
	if(url=="") return "";
            
            var result =null;                                                                                                                                                                                 
                   //call loading
            if(data == undefined){
                jQuery.ajax({
                  url: url,
                  async: async,
                  success:function(data){
                    result=data;
                    if (callback instanceof Function) {
                        callback.call(null,data);
                    }
                  }
                }); 
              return result;
            }else{
               jQuery.ajax({
                                    dataType: "json",
                                    type: "POST",
                                    url: url,
                                    timeout:10000,
                                    data: {ahlu:JSON.stringify(data)}, //
                                    async: async,
                                    success: function(data, textStatus, jqXHR) {
                                          result = data; 
                                        if (callback instanceof Function) {
                                            callback.call(null,data);
                                        }
                                    },
                                    error : function(xhr, ajaxOptions, thrownError) {
                                       
                                       //google chrome
                                          try
                                          { 
                                            loader.close();
                                              switch(xhr.status){
                                                case 200:
                                                   if(xhr.responseText != "")
                                                  {  
                                                      // parse json, string into object  in javascipt
                                                    var data =  eval('(' + xhr.responseText + ')');
                                                    result = data;
                                                    //console.log(test);
                                                    if (callback instanceof Function) {
                                                        callback.call(null,data);
                                                    }
                                                    
                                                  }
                                                break;
                                                case 500:
                                                  if (callback instanceof Function) {
                                                        callback.call(null,{d:{error:xhr.responseText,code:0,data:null}});
                                                   }
                                                break;
                                              }
                        
                                            }
                                            catch(e)
                                            {
                                            // We report an error, and show the erronous JSON string (we replace all " by ', to prevent another error)
                                              //alert("loi");
                                              if (callback instanceof Function) {
                                                        callback.call(null,xhr.responseText);
                                              }
                                            }

                                    }
                           });
            }
            return result; 
    }
}
	var ahluForm = function(options){

		var settings = jQuery.extend({

			 url : null, // controller to connect to server,

			 onError: function(e){} || null,

			 onSuccess : function(e){} || null,

			 mode : "inline",

			 holdForm : false, //check if form will be send data to server or not, default false

			 handler : null

		},options !=undefined && options!=null ? options : {});

		var formData = null;



		if(settings.handler==null || (settings.handler instanceof Object && settings.handler.lenght==0)){

			alert("Can not find the handler.");

			return;

		}



		var isValidate = false,formOptions=null,form=null, isEnter = false;

		//scan for form

		if(settings.handler[0] != undefined && settings.handler[0].nodeName=="FORM"){

			form = settings.handler;

		}else{

			form = settings.handler.closest("form");

		}

		

		//setting url

		if(settings.url==null && form.length!=0){

			settings.url = form.attr("action");

		}



		var action = function(e,f){



			if(e=="change" | e=="onChange"){ //only input

				settings.handler.live("change",function(e){ // whenever the mouse on focus, the event change apply, ex: click button sumit with form preventing submit

					var call = f.call(this,e,method.public);
					if(call!==false){
						for(var i in method.events["success"]){
							method.events["success"][i].call(this,form);
						}
					}
					method.public.enabled();
					jQuery(this).die("change");

				});

				settings.handler.live("keypress",function(e){ 

					var code = e.keyCode ? e.keyCode : e.which;

				    if(code== 13) {
						method.public.disable();
				        e.preventDefault();

				        isEnter = true;

				        settings.handler.trigger("change");

				    }



					jQuery(this).die("keypress");

				});

			}else if(e=="click" | e=="onClick"){ //button

				settings.handler.live("click",function(e){

					e.preventDefault();

					var call = f.call(this,e,method.public);
					if(call!==false){
						for(var i in method.events["success"]){
							method.events["success"][i].call(this,form);
						}
					}
					
					method.public.enabled();

					jQuery(this).die("click");

				});

				settings.handler.live("keypress",function(e){ 

					var code = e.keyCode ? e.keyCode : e.which;

				    if(code== 13) {
						method.public.disable();
				        e.preventDefault();

				        isEnter = true;

				        settings.handler.trigger("click");

				    }



					jQuery(this).die("keypress");

				});

			}else if(e=="success" | e=="onSuccess"){
				
			}else{

				alert("Can not support '"+e+"'");

			}

		};

		//plugin

		jQuery.ahluForm = function(e,f){

			action(e,f);

		};

		var mode ={

			inline: function(f){ //direct to form action for usage

				var obj = {method : "inline"};


				//this hanlder has form wrapper
				if(form!=null && form.length!=0){

					

					if(!settings.holdForm){

						document.location.href = settings.url+"?"+form.serialize();

					}else{

						formData = form.serializeObject();

						if(f !=undefined && f!=null && f instanceof Function){

							f.call(formData,{error:"hold",code:1,data:null});

						}
						

						
						for(var i in method.events){
							
							for(var j in method.events[i]){
							
								action(i,method.events[i][j]);
							}
						}
						
						form.submit(function(e){
							method.public.disable();
							for(var i in method.events){
								var e = i.toLowerCase().replace("on","");
								if(e=="change" || e=="click"){
									settings.handler.trigger(e);
								}
								
							}
							
							return false;
						});
					}

						

				 }else{
					
					//get information about hanlder input
			 		if(settings.handler[0].nodeName=="INPUT")

				 		obj[settings.handler.attr("name")!=undefined?settings.handler.attr("name"):"q"] = settings.handler.val();

				 	else

				 		obj.ahlu = settings.handler.html();
					
					//assign events
					
					for(var i in method.events){
						
						for(var j in method.events[i]){
						
							action(i,method.events[i][j]);
						}
					}
					
					//store
				 	formData = obj;
					//if form is callback or not
				 	if(!settings.holdForm){
					 	document.location.href = settings.url+"?"+jQuery.param(obj);
					}else{
						if(f instanceof Function){
							f.call(formData,null,{error:"hold",code:1,data:null});
						}

					}

				 	

				 }

			},

			suggest : function(f){ // like cart

				//form.attr("action",settings.url);

				var obj = null;

					

				if(form.length!=0){

					obj = form.serializeObject();
					//console.log(obj);
					formData = obj;

				}

				obj.method = "suggest";



				if(!settings.holdForm){

					receiveFromURL(settings.url,obj,function(e){

							//console.log(e);

							if(typeof(e) !="string" && parseInt(e.d.code)==1){

								//show callback from add

								if(f instanceof Function){

									f.call(obj,e.d);

								}
								method.__showMessage("success",{type:"onFlow",message:"connect to server successfully.",data:e.d.data});

							}else{
								if(f instanceof Function){
							
									f.call(obj,e);

								}
								method.__showMessage("error",{type:"add",message:e.error});

							}

					});

				}else{

					if(f instanceof Function){

						f.call(obj,{error:"hold",code:1,data:null});

					}

				}

			}

		};



		var method = {

			events : {},

			public : {

				init : function(){

				

					return this;

				},

				template : function(){

					return '';

				},

				getData : function(){

					return formData;

				},
				enabled : function(){
					form.find('button[type="submit"]').attr('disabled' , false);
					form.find('input[type="submit"]').attr('disabled' , false);
				},
				disable : function(){
					form.find('button[type="submit"]').attr('disabled' , true);
					form.find('input[type="submit"]').attr('disabled' , true);
				},
				process : function(f){

					if(mode[settings.mode]!=undefined){

					  mode[settings.mode](f);

					}
				},

				on : function(e,f){

					if(method.events[e]==undefined){

						method.events[e] = [];

					}

					method.events[e].push(f);
					
					return this;

				},

				validate : function(options){
					var pub = this;
					if(form.length!=0){

						isValidate = true;

						formOptions = options == undefined ? {} : options;

						//ser handler

						formOptions.submitHandler =  function(form) {

							//no error
							pub.disable();
						    if(mode[settings.mode]!=undefined){
								
								mode[settings.mode](formOptions.fromServer);

							}

						};



						//begin for validation

						form.validate(formOptions);

					}

					return this;

				}

			},

			__showMessage :function(type,message){



				switch(type){

					case "error":

						if(settings.onError instanceof Function){

							settings.onError.call(null,message);

						}

					return false;



					case "success":

						if(settings.onSuccess instanceof Function){

							settings.onSuccess.call(null,message);

						}

					return true;

				}

			}

		};

		//check form

		

		return method.public;

	};


var validateForm = function(form,options){

			var settings = jQuery.extend({

				fromServer :null,

				submitHandler :null,

				url : null,

				rules : null,

				messages : null,

				buttonSubmit : null, //set handler pointer ,default submit button

				displayError : "text",

				errorClass: "error", //set class "error" style

				ignore : "hidden", //ignore hidden tag input

				titleTag : "error" //set error in attribute inline tag



			},options == undefined ? {} : options);

									

			var private = {

				isDirect : function(data){

					//if fromServer is set

					if(settings.fromServer instanceof Function){

						fromServer.call(form.serializeObject(),data);

						return false;

					}else if(settings.submitHandler instanceof Function){ //form is ready

						settings.submitHandler.call(null,form);

						return false;

					}

					return true;

				},

				fields : {},

				init : function(){

					var $this =this;

					if(form.length!=0){

						form.live("submit",function(e) {

							var submitted = e.originalEvent.explicitOriginalTarget ||e.originalEvent.relatedTarget || document.activeElement;



							// Look if it was a text node (IE bug)

							submitted = submitted.nodeType == 1 ? submitted : submitted.parentNode;

						    

						    var go = $this.processForm();

						      

						      if(!go){

						        e.preventDefault();

						        return false;

						      }



						      return true;

						});

						form.bind("keypress", function(e) {

					            if (e.keyCode == 13) return false;

					    });

					    //set button

					    if(settings.buttonSubmit instanceof Object){

							if(settings.buttonSubmit.length!=0){

								settings.buttonSubmit.live("click",function(e){

									e.preventDefault();

									form.trigger("submit");

								});

							}

						}

					}else{

						alert("Form not found.");

					}

					return this;

				},

				processForm : function(){

					console.log("form submitted");

					//check error

					var hasError = this.checkError();

					if(!hasError){

						return this.isDirect();

					}

				},

				processField :function(){

					var $this = this;

					//from form

					jQuery.each(form[0],function(i,v){

						if(!isNaN(i)){

							var input = form[0][i];

							if(input.name !="" && input.id!=undefined && input.id!=""){

								var id = input.id;

								input = jQuery("#"+id);

								if(input.length!=0){

									

									if(private.fields[id] ==undefined)

										private.fields[id] = {};



									//set jquery

									private.fields[id].$ = input;

									private.fields[id].error = true; //status error on



									//in rules

									var type = (input.attr("type") !="" && input.attr("type")!=undefined)  ? input.attr("type").toLowerCase(): null;

									if(type!=null && type!=settings.ignore && type!="reset" && type!="button" && type!="submit"){

										

										if(settings.rules instanceof Object && settings.rules[id]!=undefined){

											private.fields[id].rule = {};

											jQuery.each(settings.rules[id], function(k,v) {

													private.fields[id].rule[k] = v instanceof Function ? v.call(input) : v==true;

											});

											private.fields[id].error = true;

										}else{

											var cls = input.attr("class");

											if(private.fields[id].rule == undefined)

												private.fields[id].rule = {};



											if (cls!="" || cls!=undefined) {

												jQuery.each(cls.split(/\s/), function() {

													if($this.inlineRules[this] !=undefined){

														

														private.fields[id].rule[this] = true;

													}

												});

											}

										}



										//in messages

										if(settings.messages instanceof Object && settings.messages[id]!=undefined){

											private.fields[id].message = settings.messages[id];

										}else{

											if(private.fields[id].message==undefined)

												private.fields[id].message = {};



											var error = input.attr(settings.titleTag);

											if (error!="" && error!=undefined) {

												jQuery.each(error.split(/\s/), function() {

													if($this.inlineRules[this] !=undefined){

		

														//formt email:message

														var format = this.split(":");

														if(form.lenght==2){

															private.fields[id].message[format[0]] = format[1];

														}

														

													}

												});

											}else{ //set as default

												if(private.fields[id].message==undefined)

															private.fields[id].message = {};



												for(var i in private.fields[id].rule){

													console.log(private.messages[i]);

													if(private.messages[i]!=undefined){

														private.fields[id].message[i] = private.messages[i];

													}

												}

											}

										}

									}

								}

							}

						}



						

					});

					console.log(private.fields);

					return this;

				},

				checkError : function(){



				},

				inlineRules : {

					required: {required: true},

					email: {email: true},

					url: {url: true},

					date: {date: true},

					dateISO: {dateISO: true},

					dateDE: {dateDE: true},

					number: {number: true},

					numberDE: {numberDE: true},

					digits: {digits: true},

					creditcard: {creditcard: true}

				},

				messages: {

					required: "This field is required.",

					remote: "Please fix this field.",

					email: "Please enter a valid email address.",

					url: "Please enter a valid URL.",

					date: "Please enter a valid date.",

					dateISO: "Please enter a valid date (ISO).",

					number: "Please enter a valid number.",

					digits: "Please enter only digits.",

					creditcard: "Please enter a valid credit card number.",

					equalTo: "Please enter the same value again.",

					accept: "Please enter a value with a valid extension.",

					maxlength: "Please enter no more than {0} characters.",

					minlength: "Please enter at least {0} characters.",

					rangelength: "Please enter a value between {0} and {1} characters long.",

					range: "Please enter a value between {0} and {1}.",

					max: "Please enter a value less than or equal to {0}.",

					min: "Please enter a value greater than or equal to {0}."

				},

				methods: {

					// http://docs.jquery.com/Plugins/Validation/Methods/required

					required: function(value, element, param) {

						// check if dependency is met

						if ( !this.depend(param, element) )

							return "dependency-mismatch";

						switch( element.nodeName.toLowerCase() ) {

						case 'select':

							// could be an array for select-multiple or a string, both are fine this way

							var val = $(element).val();

							return val && val.length > 0;

						case 'input':

							if ( this.checkable(element) )

								return this.getLength(value, element) > 0;

						default:

							return $.trim(value).length > 0;

						}

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/remote

					remote: function(value, element, param) {

						if ( this.optional(element) )

							return "dependency-mismatch";



						var previous = this.previousValue(element);

						if (!this.settings.messages[element.name] )

							this.settings.messages[element.name] = {};

						previous.originalMessage = this.settings.messages[element.name].remote;

						this.settings.messages[element.name].remote = previous.message;



						param = typeof param == "string" && {url:param} || param;



						if ( this.pending[element.name] ) {

							return "pending";

						}

						if ( previous.old === value ) {

							return previous.valid;

						}



						previous.old = value;

						var validator = this;

						this.startRequest(element);

						var data = {};

						data[element.name] = value;

						$.ajax($.extend(true, {

							url: param,

							mode: "abort",

							port: "validate" + element.name,

							dataType: "json",

							data: data,

							success: function(response) {

								validator.settings.messages[element.name].remote = previous.originalMessage;

								var valid = response === true;

								if ( valid ) {

									var submitted = validator.formSubmitted;

									validator.prepareElement(element);

									validator.formSubmitted = submitted;

									validator.successList.push(element);

									validator.showErrors();

								} else {

									var errors = {};

									var message = response || validator.defaultMessage( element, "remote" );

									errors[element.name] = previous.message = $.isFunction(message) ? message(value) : message;

									validator.showErrors(errors);

								}

								previous.valid = valid;

								validator.stopRequest(element, valid);

							}

						}, param));

						return "pending";

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/minlength

					minlength: function(value, element, param) {

						return this.optional(element) || this.getLength($.trim(value), element) >= param;

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/maxlength

					maxlength: function(value, element, param) {

						return this.optional(element) || this.getLength($.trim(value), element) <= param;

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/rangelength

					rangelength: function(value, element, param) {

						var length = this.getLength($.trim(value), element);

						return this.optional(element) || ( length >= param[0] && length <= param[1] );

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/min

					min: function( value, element, param ) {

						return this.optional(element) || value >= param;

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/max

					max: function( value, element, param ) {

						return this.optional(element) || value <= param;

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/range

					range: function( value, element, param ) {

						return this.optional(element) || ( value >= param[0] && value <= param[1] );

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/email

					email: function(value, element) {

						// contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/

						return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(value);

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/url

					url: function(value, element) {

						// contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/

						return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/date

					date: function(value, element) {

						return this.optional(element) || !/Invalid|NaN/.test(new Date(value));

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/dateISO

					dateISO: function(value, element) {

						return this.optional(element) || /^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(value);

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/number

					number: function(value, element) {

						return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/digits

					digits: function(value, element) {

						return this.optional(element) || /^\d+$/.test(value);

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/creditcard

					// based on http://en.wikipedia.org/wiki/Luhn

					creditcard: function(value, element) {

						if ( this.optional(element) )

							return "dependency-mismatch";

						// accept only spaces, digits and dashes

						if (/[^0-9 -]+/.test(value))

							return false;

						var nCheck = 0,

							nDigit = 0,

							bEven = false;



						value = value.replace(/\D/g, "");



						for (var n = value.length - 1; n >= 0; n--) {

							var cDigit = value.charAt(n);

							var nDigit = parseInt(cDigit, 10);

							if (bEven) {

								if ((nDigit *= 2) > 9)

									nDigit -= 9;

							}

							nCheck += nDigit;

							bEven = !bEven;

						}



						return (nCheck % 10) == 0;

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/accept

					accept: function(value, element, param) {

						param = typeof param == "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";

						return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));

					},



					// http://docs.jquery.com/Plugins/Validation/Methods/equalTo

					equalTo: function(value, element, param) {

						// bind to the blur event of the target in order to revalidate whenever the target field is updated

						// TODO find a way to bind the event just once, avoiding the unbind-rebind overhead

						var target = $(param).unbind(".validate-equalTo").bind("blur.validate-equalTo", function() {

							$(element).valid();

						});

						return value == target.val();

					}



				}

	};	

	//begin

	private.init();

	private.processField();



	var public = {

		AddMethod : function(name,f,message){

			if(private.inlineRules[name]==undefined){

				private.inlineRules[name] = f(); 

			}

			if(private.messages[name]==undefined){

				private.inlineRules[name] = message; 

			}

		},

		setFormatError : function(){

			return "";

		}

	};

	return public;

};

/*

var test = validateForm( jQuery("#co-billing-form"),{

			fromServer : function(data){

				console.log(data);

			},

			rules : {

				firstname : {

					required: function(){

						return true;

					}

				}

			}

		});

  use mvc



*/