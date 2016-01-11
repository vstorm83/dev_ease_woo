(function(jQuery,$,undefined){
  //'$:nomunge'; // Used by YUI compressor.
  
  jQuery.fn.serializeObject = function(){
    var obj = {};
    
    jQuery.each(this.serializeArray(), function(i,o){
      var n = o.name,
        v = o.value;
        
        obj[n] = obj[n] === undefined ? v
          : jQuery.isArray( obj[n] ) ? obj[n].concat( v )
          : [ obj[n], v ];
    });
    
    return obj;
  };
  
})(jQuery);
(function(jQuery,$,undefined){
  //'$:nomunge'; // Used by YUI compressor.
  
  jQuery.fn.catchForm = function(e,f){
    var form = this;
	var formTag = form[0];
	var node = formTag.nodeName;
    if(node!="FORM"){
		alert("Can not process with "+node);
		return this;
	}

    switch(e){
		case "unSumbit":
		case "unsubmit":
			 formTag.onsubmit = function(e)
			{
				f.call(this,e);
				return false;
			};		
		break;
		
		case "Sumbit":
		case "submit":
			formTag.onsubmit = function(e)
			{
				f.call(this,e);
				return true;
			};
		break;
	}
    
    return form;
  };
  
})(jQuery);
(function() {
     jQuery.fn.replaceInput = function(type,val){
      if(type==undefined){
         return this;
      }
      this.each(function() {
             $('<input type="'+type+'" />').attr({id:this.id, name: this.name, value: val == undefined ?this.value :val }).insertBefore(this);
      }).remove();
    };
    var core = {
		
        require : function(source) {
            if ( typeof (source) != "object" || !source)
                throw new TypeError("Object needed as source.");
            for (var property in source)
                if (source.hasOwnProperty(property) && !this.prototype.hasOwnProperty(property))
                    this.prototype[property] = source[property];
        },
        override : function(source) {
            if ( typeof (source) != "object" || !source)
                throw new TypeError("Object needed as source.");
            for (var property in source)
                if (source.hasOwnProperty(property))
                    this.prototype[property] = source[property];
        },
		Class : function(/*params*/){
			 var init = this,
			  myClass = function () {
				init.call(this); 
			};
		   
		  
		  if(init.constructor==undefined){
			init.constructor = function(){};
		  }
		  myClass.prototype = init.prototype;

		  var  a= new myClass();
		  if(a.constructor !=undefined)	
				a.constructor.apply(a,arguments);
		  return a;
		},
        abstract :function(source) {
            var superClass = this;
            var newClass = null;
            
            //check is abstract
            if (source.hasOwnProperty("constructor")) {
                   alert("Class abstract can not declare constructor method");
                   return;
            }

            newClass = function() {
                superClass.apply(this, arguments);
            };


            var superClone = function() {
            };

            superClone.prototype = superClass.prototype;
            newClass.prototype = new superClone();
            newClass.prototype.constructor = newClass;

            if (source)
                newClass.override(source);

            newClass.prototype.super = superClass.prototype;  
          return newClass;
        }, 
        extend : function(source) {
            var superClass = this;
            var newClass = null;
            var  o = null;

            //check is abstract
            if (superClass["info"] != undefined) {
                newClass = function() {
                   superClass.apply(this, arguments);
                };
            }else{
              if (source.hasOwnProperty("constructor")) {
                newClass = source.constructor;
              }else{
                 newClass =  function() { //create function main
                  superClass.apply(this, arguments);
                };
              }
            }

            newClass.superClass = superClass;

            var superClone = function() {
            };
            superClone.prototype = superClass.prototype;
            newClass.prototype = new superClone();
            newClass.prototype.constructor = newClass;

            if (source)
                newClass.override(source);
 
            newClass.prototype.super = superClass.prototype;  

            return newClass;
        }
        ,
        revive : function(source) {
            var superClass = source;
            var newClass = this;
            var n={}, o = null;

            //check is abstract
            if (superClass["info"] != undefined) {
                newClass = function() {
                   superClass.apply(this, arguments);
                };
            }else{
              o = source.apply(n,[]);
            }

            newClass.superClass = superClass;

            var superClone = function() {
            };
            superClone.prototype = superClass.prototype;
            newClass.prototype = new superClone();
            newClass.prototype.constructor = newClass;
 
            newClass.prototype.super = superClass.prototype;  
            //if the parent return obj , we must handle this
            if (o!=null) {
              for (var i in o) {
                newClass.prototype.super[i] = o[i];
              }

              for (var i in n) {
                if (n[i] instanceof Function) {
                   newClass.prototype.super[i] = n[i];
                }
                
                newClass[i] = n[i];
              }
            } 

            return newClass;
        }
        ,
        implements :function(source) {
          var me = this;
          if (source instanceof Object) {
            //console.log(source);

            for (var property in source){
                if ( source[property] instanceof Function && source.hasOwnProperty(property) && !this.prototype.hasOwnProperty(property))
                    {
                      //warning
                      alert("the class {"+this.name+"} is not implemented the method,{"+property+"}");
                      return ;
                    }else{ //exist
                      var im = source[property].getArgs().length;
                      var me = this.prototype[property].getArgs().length;
                       if(im !=me){
                         alert("The class with {"+property+"} omits some arguments : {"+source[property].getArgs()+"}");
                         return;
                       }
                    }
            }
          }
            return this; //return function
        }
    };

    core.require.call(Function, core);

    Function.create = function (source){
        var newClass = source.hasOwnProperty("constructor") ? source.constructor : function() {};
        newClass.override(source);
        return newClass;
    };

    Function.abstract = function (source){
          if (source.hasOwnProperty("constructor")) {
             alert("Class abstract can not declare constructor method");
             return;
          }

        var newClass = function() {
          
        };
        newClass.prototype.info = function(){
            return {isAbstract : true};
        };
        newClass.override(source);
        return newClass;
    };
	
	
	jQuery.fn.getAttributes = function() {
        var attributes = {}; 

        if( this.length ) {
            jQuery.each( this[0].attributes, function( index, attr ) {
                attributes[ attr.name ] = attr.value;
            } ); 
        }

        return attributes;
    };
})(jQuery);
function receiveFromURL(url,data,callback){
		var obj = data;
		if(url=="") return "";
		
		var result =null;                                                                                                                                                                                 
		//call loading
		if(data == undefined){
			jQuery.ajax({
			  url: url,
			  async: false,
			  success:function(data){
				result=data;
				if (callback instanceof Function) {
					callback.call(obj,data);
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
					data: {ahlu :  encodeURI(JSON.stringify(data))}, //
					async: false,
					success: function(data, textStatus, jqXHR) {
						  result = data; 
						if (callback instanceof Function) {
							callback.call(obj,data);
							
						}
					},
					error : function(xhr, ajaxOptions, thrownError) {
					   
					   //google chrome
						  try
						  {
							  if(xhr.responseText != "")
							  {  
								  // parse json, string into object  in javascipt
								var data =  eval('(' + xhr.responseText + ')');
								result = data;
								//console.log(test);
								if (callback instanceof Function) {
									callback.call(obj,data);
								}
							  }
							}
							catch(e)
							{
							// We report an error, and show the erronous JSON string (we replace all " by ', to prevent another error)
							  //alert("loi");
							    result = xhr.responseText;
								if (callback instanceof Function) {
									callback.call(obj,result);
								}
							}
					}
		   });
		}
		return result; 
}
