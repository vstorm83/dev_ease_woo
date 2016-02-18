<?php 
  if(!isset($_SESSION["_upload"])){
	$_SESSION["_upload"] = true;
	wp_enqueue_script( 'serialize', get_template_directory_uri() . '/js/jquery.serialize-object.min.js', array('jquery'));
?>
<script type="text/javascript">
function upload(ID,callback){
	if(!callback instanceof Function) return false;
	
jQuery(document).ready(function($) {
    function removeItem(array, item){
		for(var i in array){

			if(array[i]==item){

				console.log(i);

				array.splice(i,1);

				break;

				}

		}
		return array;
	}

	  function parseURL(url) {
		var a =  document.createElement('a');

		a.href = url.toLowerCase();

		var obj = {

			source: url,

			protocol: a.protocol.replace(':',''),

			host: a.hostname,

			port: a.port,

			query: a.search,

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

				  iframeJQuery(btnSubmit).click(function(){
					var $this = jQuery(this);
					var form = $this.closest("form");
					var obj = form.serializeObject();
					
					var fldID = jQuery(this).attr('id').replace('send', '').replace('[', '').replace(']', '');
					
					//console.log(obj);
					/*
					var fldID = jQuery(this).attr('id').replace('send', '').replace('[', '').replace(']', '');

					var imgurl = iframeJQuery('input[name="attachments\\['+fldID+'\\]\\[url\\]"]').val();

					var title = iframeJQuery('#attachments['+fldID+'][post_title]').val(); 
					var post_content = iframeJQuery('#attachments['+fldID+'][post_content]').val();
					var excerpt = iframeJQuery('#attachments['+fldID+'][post_excerpt]').val();
					*/
					
					//show callback
					callback(obj.attachments[fldID]);
					
					tb_remove();

				  });

				}

			  });

			   }

			  })();

					 

		// save the send_to_editor handler function
		window.send_to_editor_default = window.send_to_editor;

		window.attach_image = function(html) {

			 // console.log(html);
			// turn the returned image html into a hidden image element so we can easily pull the relevant attributes we need

			jQuery('body').append('<div id="temp_image">' + html + '</div>'); 

			var item = jQuery('#temp_image').find('img');

			imgclass = null;  

			imgurl = null;  

			imgid= null;

			title = null;

			 

			if(item.attr("class")==null){

			   item = jQuery('#temp_image').find('a'); 

			   title = item.html();  

			   imgurl   = item.attr('href');

			   

			   if(item.attr('rel')!=null){

					imgid = parseInt(item.attr('rel').replace("attachment wp-att-","")); 

			   } 

				//show callback
				callback({title:title,url:imgurl,id:imgid});

			}else{

			  imgclass = item.attr('class');

			  imgid    = parseInt(imgclass.replace(/\D/g, ''), 10); 

			  imgurl   = item.attr('src');   
				
				//show callback
				callback({title:title,url:imgurl,id:imgid});
			}

			//
			
			
			try{

				tb_remove();

			}catch(e){};

			

			$('#temp_image').remove();

			// restore the send_to_editor handler function

			window.send_to_editor = window.send_to_editor_default;

		}
		//open window
		
		//open new window to add image
		window.send_to_editor = window.attach_image;
		tb_show('', 'media-upload.php?post_id='+ID+'&amp;type=image&amp;TB_iframe=true');
		
	});
	
	return false;
}
</script>
<?php
  }

?>