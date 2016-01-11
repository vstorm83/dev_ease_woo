
				function createUpload_ahlu(){
	    function parseURL(url) {

            var a =  document.createElement("a");

            a.href = url.toLowerCase();

            var obj = {

                source: url,

                protocol: a.protocol.replace(":",""),

                host: a.hostname,

                port: a.port,

                query: a.search,
				queryObject: function(){
					var result = {};
					var str = a.search ;
					if( !str || str.indexOf("?") == -1 ) 
						return result;
						
					var pairs = str.split("?")[1].split("&");
					pairs.forEach(function(pair) {
						pair = pair.split("=");
						var name = decodeURI(pair[0])
						var value = decodeURI(pair[1])
						if( name.length )
							if (result[name] !== undefined) {
								if (!result[name].push) {
									result[name] = [result[name]];
								}
								result[name].push(value || "");
							} else {
								result[name] = value || "";
							}
					});
					return result ;
				},

                params: (function(){

                    var ret = {},

                        seg = a.search.replace(/^\?/,'').split('&'),

                        len = seg.length, i = 0, s;

                    for (;i<len;i++) {

                        if (!seg[i]) { continue; }

                        s = seg[i].split('=');

                        ret[s[0]] = s[1];

                    }

                    return ret;

                })(),

                file: (a.pathname.match(/\/([^\/?#]+)$/i) || [,''])[1],

                hash: a.hash.replace('#',''),

                path: a.pathname.replace(/^([^\/])/,'/$1'),

                relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [,''])[1],

                segments: a.pathname.replace(/^\//,'').split('/')

            };

            

                obj.name=obj.file.split('.')[0],

                obj.ext = obj.file.split('.').pop(),

                obj.extension = obj.file.split('.').pop(),

                obj.isImage = /(\.jpg|\.jpeg|\.gif|\.png|\.tif)$/i.test(obj.file),

                obj.type = function($bool){
                   //group name images
                   //group name document
                   //group name media 
                };

            return obj;

        }

         function capitalize (str){

            return str.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );

         };

	
			(function(){ 

                var tb_show_temp = window.tb_show; 

                window.tb_show = function() { 

                  tb_show_temp.apply(null, arguments); 

                  var iframe = jQuery('#TB_iframeContent');

                  iframe.load(function() {

                    var iframeDoc = iframe[0].contentWindow.document;

                    var iframeJQuery = iframe[0].contentWindow.jQuery;

                    var buttonContainer = iframeJQuery == undefined ? null : iframeJQuery('td.savesend');

                    if (buttonContainer) {

                      var btnSubmit = jQuery('input:submit', buttonContainer);
					  //find some information
					  var infoFrame = parseURL(iframeDoc.location.href);

                      iframeJQuery(btnSubmit).click(function(){

                        var fldID = jQuery(this).attr("id").replace("send", "").replace('[', '').replace(']', '');

                        var imgurl = iframeJQuery('input[name="attachments\['+fldID+'\]\[url\]"]').val();

                        var title = iframeJQuery('input[name="attachments\['+fldID+'\]\[post_title\]"]').val(); 
						
						
						var who = infoFrame.queryObject();
						
                        window.uploadCallback({id:fldID,url:imgurl,title:title},(who && who.who?who.who:null));

                        tb_remove();

                      });

                    }

                  });

                   }

		})();

		 
		//adding my custom function with Thick box close function tb_close() .
		window.old_tb_remove = window.tb_remove;
		window.tb_remove = function() {
			window.old_tb_remove(); // calls the tb_remove() of the Thickbox plugin
		};
	 
		/*
		window.original_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html){

			console.log(html);
			tb_remove();
		};
		*/
	}

	createUpload_ahlu();
			