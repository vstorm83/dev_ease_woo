<section id="content">
    <div class="slider-wrapper">
        <div id="camera_wrap">
		
		<?php
												$slideshow = new Media_model(); 
												$data = $slideshow->byNamePost('homepage');
												//print_r($data);
												if(is_array($data)){
												   foreach($data as $i=> $thumb){
								?>		
		
            <div data-src="<?php echo $thumb->guid; ?>">
                
				<?php 
			$a = array_map(function($v){return '<div class="caption fadeIn"> <div class="container"> <div class="row"> <div class="grid_12"> <h6> '.$v.' </h6> </div> </div> </div> </div>';},explode("&mp&",$thumb->post_excerpt));	
			echo implode("</br>",$a); 			
				?>		
            </div>
			
			
<?php }}?>
           

           
        </div>
        <div class="clearfix"></div>
    </div>
    
	<?php
						global $lang;
							$upload_dir = wp_upload_dir();

							$page = get_page_by_title( 'homePage' );

							echo $page->post_content;
					?>

	
	 <div class="wrapper1 border-wrapper">
        <div class="container">
            <div class="row">
                <div class="grid_12">
                    <div class="header1">
                        <h2 align="center">~ Welcome to D'Artist Gallery ~</h2>
                        <br>
                        <h3 align="center">At D’Artist Gallery, our craftsmanship goes into every frame to interpret your needs powerfully and creatively.<br>
We are confident in our ability to frame your artwork in an exceptional manner.<br>
Visit our outlet for a comprehensive range of good quality, affordable frames.</h3>
                        <br><br>
                        <h2 align="center">~ Projects Gallery ~</h2>
                    </div>
                </div>
            </div>
            <div class="row">
		
			<?php


function unique_randoms($min, $max, $count) {
$arr = array();
for ($i=$min;$i<$max;$i++) {
    $arr[] = $i;
}
shuffle($arr);
return array_slice($arr, 0, $count);
}						
												$list_home=array();
												$slideshow = new Media_model(); 
												$data = $slideshow->byNamePost('gallery');
												if(is_array($data)){
													$rand = unique_randoms(0,count($data),4);
												   foreach($rand as $c){
												    
										
								?>	
							  			   
				   <div class="grid_3">
								<div class="gallery_image">
									<a href="gallery.html">
										<img src="<?php echo ($data[$c]->guid); ?>" alt="" style="height:215px"/>
										
									</a>
								</div>
							</div>			
				
				<?php }}?>
               
            		
			   
			   
            </div>

	  </div>
    </div>

	
</section>


