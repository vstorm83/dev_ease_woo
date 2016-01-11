<?php 

//add images to slide , use plugin 'Categories Images'
 function add_imgslider($id){
	 $count = 0;  
$banners = ("
SELECT p.*, a.term_order 
FROM 39226_posts p inner join 39226_term_relationships a on a.object_id = p.ID 
inner join 39226_term_taxonomy ttt on ttt.term_taxonomy_id = a.term_taxonomy_id 
inner join 39226_terms tt on ttt.term_id = tt.term_id where ttt.taxonomy='media-category' && tt.term_id='$id' order by ID Desc

");
$rows= mysql_query($banners);
if($rows){
while($rs= mysql_fetch_array($rows))
{?>
		
	<?php echo "
	<li>
<img src='".$rs['guid']."' alt='' rel='<h3>".$rs['post_title']."</h3>".$rs['post_excerpt']."'/>'
<div class='banner'>
<div class='banner_indent'>
<div class='bann_title'>".$rs['post_title']."</div>
".$rs['post_content']."
</div>
</div>
</li>


	"; ?>
	
  <?php }
} 

} 
function add_imgbanner($id_cate){
	 $count = 0;  
$banners = ("
SELECT p.*, a.term_order 
FROM 39226_posts p inner join 39226_term_relationships a on a.object_id = p.ID 
inner join 39226_term_taxonomy ttt on ttt.term_taxonomy_id = a.term_taxonomy_id 
inner join 39226_terms tt on ttt.term_id = tt.term_id where ttt.taxonomy='media-category' && tt.term_id='$id_cate' order by ID Desc LIMIT 0,1

");
$rows= mysql_query($banners);

while($rs= mysql_fetch_array($rows))
{
	echo "<img src='".$rs['guid']."' alt='' rel='<h3>".$rs['post_title']."</h3>".$rs['post_excerpt']."'/>";
   }
} 

?>