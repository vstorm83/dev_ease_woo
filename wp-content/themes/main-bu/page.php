<?php 

	

global $post;



echo do_shortcode($post->content);

$postData = new Post_model();

$postData->load($post->ID);


$template = Ahlu::Library("Template");

$template->assign("meta",$postData->SEO());

$template->assign("cls","{$post->post_name} page-{$post->ID}");

$template->assign("header",$template->load->view( $template->getPath()."/compoment/header.php",null,true));

	

	//check from front_end

if(IS_WP){	

	ob_start();

		while ( have_posts() ) : the_post();



			// Include the page content template.

			the_content();



			



		// End the loop.

		endwhile;

	$postData->getMe()->post_content = ob_get_clean();



}



//$template->assign("content",$content);

$template->assign("content",$template->load->view("ahlu-page-post",array("post"=>$postData),true));

	

$template->assign("footer",$template->load->view( $template->getPath()."/compoment/footer.php",null,true));



//output html

$template->render(FALSE);







?>

