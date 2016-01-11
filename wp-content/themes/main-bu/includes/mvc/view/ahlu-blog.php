<?php
  $view = $category->viewOnPost();
  $view = $category->viewOnPost();
  $post = new Post_model("Blog page");
  $post = $post->getMe();
?>
<div class="wrapper ahlu-box panel-1 ">
		
		<div class="wrapper ahlu-box panel-6" style="background:url(<?php echo  $post->thumbnail;?>) no-repeat top center;background-attachment: fixed; background-size: cover;">
				<div class="ahlu-body">
					<?php echo  $post->post_content;?>
				</div>
			</div>
		
		
		
		<div class="wrapper ahlu-box panel-2">
				<div class="ahlu-body">
					<div class="ahlu-body-layer" style="background-color:white"></div>
					<div class="panel-box col-md-10">
							<div class="panel-box col-md-12 head-title" style="text-align: left;">
									<span>LATEST</span> News

							</div>
							
							<div class="col-md-12 col-sm-12">
								<?php 
									$data = $category->listDynamic(3,URI::getinstance()->paged,array("featured"=>1))->data;
									
								?>
								<div class="col-md-8 mobile col-sm-8 no-space" style="padding-bottom: 6px;background:#005596;border-radius: 10px;">
										<div class="col-md-12 col-sm-12 no-space" style="height:350px;    border-radius: 10px;background:url(<?php echo $data[0]->thumbnail; ?>) no-repeat top center;background-attachment: fixed; background-size: cover;">
											<div class="ahlu-body-layer" style="border-radius: 10px;"></div>
											<div class="article">
												<h3 class="title"><a href="<?php echo site_url($data[0]->post_name); ?>"><?php echo $data[0]->post_title; ?></a></h3>
												<div class="desc"><?php echo $data[0]->post_excerpt; ?></div>
												<div class="info">
													<?php $t = strtotime($data[0]->post_date); 
														$username = get_the_author_meta( 'user_nicename' , $data[0]->post_author );

														$a = explode(" ",$username);
														if(count($a)>1){
															$f = array_shift($a);
															$username = '<span class="bold">'.$f.'</span> '.implode(" ",$a).'</span>';
														}else{
															$username = '<span class="bold">'.$username.'</span>';
														}
													?>
													<span class="left"><i class="fa fa-calendar"></i> <span><?php echo date("l",$t)?> <span class="bold"><?php echo date("d",$t)?> <?php echo date("F",$t)?></span> <?php echo date("Y",$t)?></span></span>
													<span class="right"><i class="fa fa-user"></i> <span><?php echo $username; ?></span>
												</div>
											</div>
										</div>

								</div>
								<div class="col-md-4 mobile col-sm-4">
									<div class="col-md-12 mobile col-sm-12 no-space" style="padding-bottom: 6px;background:#005596;border-radius: 10px;">
										<div style="background:url(<?php bloginfo('template_directory');?>/images/bg_pic3.jpg) no-repeat top center #005596;background-attachment: fixed; background-size: cover; height:165px;float:left;border-radius: 10px;">
											<div class="ahlu-body-layer" style="border-radius: 10px;"></div>
											<div class="article">
												<h4 class="title"><a href="<?php echo site_url($data[1]->post_title); ?>"><?php echo $data[1]->post_title; ?> </a></h4>
											</div>
										</div>
									</div>
									<div class="col-md-12 mobile col-sm-12 no-space" style="margin-top: 13px;padding-bottom: 6px;background:#005596;border-radius: 10px;">
										<div style="background:url(<?php bloginfo('template_directory');?>/images/bg_pic3.jpg) no-repeat top center #005596;background-attachment: fixed; background-size: cover; height:165px;float:left;border-radius: 10px;">
											<div class="ahlu-body-layer" style="border-radius: 10px;"></div>
											<div class="article">
												<h4 class="title"><a href="<?php echo site_url($data[2]->post_title); ?>"><?php echo $data[2]->post_title; ?> </a></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12" style="padding:50px 0px"></div>
							<div class="col-md-12">
								<div class="col-md-8 list-items">
									<?php 
										$data = $category->listDynamic(5,URI::getinstance()->paged);
										if(is_array($data->data) && count($data->data)){
											foreach($data->data as $i=> $item){
												$first = $i==0?"first":"";
												$url = site_url($item->post_name);
												$t = strtotime($item->post_date);
												
												$l = date("l",$t);
												$d = date("d",$t);
												$F = date("F",$t);
												$Y = date("Y",$t);
												$username = get_the_author_meta( 'user_nicename' , $item->post_author );

												$a = explode(" ",$username);
												if(count($a)>1){
													$f = array_shift($a);
													$username = '<span class="bold">'.$f.'</span> '.implode(" ",$a).'</span>';
												}else{
													$username = '<span class="bold">'.$username.'</span>';
												}
											echo <<<AHLU
												<div class="col-md-12 no-space article {$first}">
													<div class="">
														<h3 class="title"><a href="{$url}">{$item->post_title}</a></h3>
														<div class="desc">{$item->post_excerpt}</div>
														<div class="info">
									
															<span class="left"><i class="fa fa-calendar"></i> <span>{$l} <span class="bold">{$d} {$F}</span> {$Y}</span></span>
															<span class="right"><i class="fa fa-user"></i> {$username}</span>
														</div>
														<div class="line-left"></div>
														<div class="pointer"><a href="{$url}">&nbsp;</a></div>
													</div>
												</div>
								
AHLU;
											}
										}
									?>
									
								</div>
								
								
								
								
								
								<div class="col-md-4">
									
									<div class="col-md-12 no-space news-list">
										<h2 class="title">ALL TIME <span>HITS</span></h2>
										<ul>	
											<?php
												if(is_array($view->data) && count($view->data)>0){
													foreach($view->data as $item){
												
											?>
											 <li><a href="<?php echo site_url($item->post_name); ?>"><?php echo $item->post_title; ?></a></li>
											<?php
												}
											}?>
										</ul>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
		
	
		
		</div>