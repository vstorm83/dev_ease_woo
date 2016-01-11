function bindGrid(defaultView)
{
	var view = $.totalStorage('display');

	if (!view && (typeof defaultView != 'undefined') && defaultView)
		view = defaultView;

	if (view && view != 'grid') {
		display(view),
		$('#view').find('li#list').addClass('selected')
	} else {
		display(view),
		$('#view').find('li#grid').addClass('selected')
	}
	$(document).on('click', '#grid', function(e){
		e.preventDefault();
		display('grid');
	});
	
	$(document).on('click', '#list', function(e){
		e.preventDefault();
		display('list');
	});
}

function display(view) {
	if (view == 'list') {
		$('ul.product-listing').removeClass('grid').addClass('list');
		$('ul.product-listing > li').removeClass('col-xs-'+productWidth).addClass('col-xs-12');
		
		$('ul.product-listing > li').each(function(index, element) {
			
			html ='';
			html += '<div class="product-container"><div class="row">';
			
			var img = $(element).find('.product-image-box').html();
			if (img != null)
				html += '<div class="product-image-box col-xs-4">'+img+'</div>';
				
			html +='<div class="product-content col-xs-8">'+$(element).find('.product-content').html()+'</div>';
			
			var buttons = $(element).find('.button-container').html();
			if (buttons != null)
				html += '<div class="button-container col-xs-8">'+buttons+'</div>';
			
			html += '</div></div>';
			
			$(element).html(html);
		});
		
		$('#view').find('li#list').addClass('selected');
		$('#view').find('li#grid').removeAttr('class');
		$.totalStorage('display', 'list');
	}
	else {
		$('ul.product-listing').removeClass('list').addClass('grid');
		$('ul.product-listing > li').removeClass('col-xs-12').addClass('col-xs-'+productWidth);
		
		$('ul.product-listing > li').each(function(index, element) {
			
			html ='';
			html += '<div class="product-container">';
			
			var img = $(element).find('.product-image-box').html();
			if (img != null)
				html += '<div class="product-image-box">'+img+'</div>';
				
			html +='<div class="product-content">'+$(element).find('.product-content').html()+'</div>';
			
			var buttons = $(element).find('.button-container').html();
			if (buttons != null)
				html += '<div class="button-container">'+buttons+'</div>';
			
			html += '</div';
			
			$(element).html(html);
		});
		
		$('#view').find('li#grid').addClass('selected');
		$('#view').find('li#list').removeAttr('class');
		$.totalStorage('display', 'grid');	
	}
}