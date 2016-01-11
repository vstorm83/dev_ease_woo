<?php 

	$cate = $category->menu();
?> 
<div class="wrapper product-bg">
	<div class="ahlu-box panel-1 col-md-10 center-no-center">
		<div class="ahlu-body">
			<h1 class="head-title">Mining Tools & Equipment</h1>
			<div class="col-md-12 no-space product-items">
				<?php
					if(is_array($cate)){
						foreach($cate as $item){
echo <<<AHLU
						<div class="col-md-3 col-sm-4 no-space item">
							<div class="wrapper">
								<a href="{$item->link}">
									<p class="title">{$item->title}</p>
									<img src="{$item->thumbnail}" />
									<p class="title">{$item->description}</p>
								</a>
							</div>
						</div>
AHLU;
						}	
					}else{
						echo "No Subcate";
					}
				?>
			
			</div>
		</div>
	</div>
</div>