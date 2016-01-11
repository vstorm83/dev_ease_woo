<?php
function getParent($id,$taxanomy){
   global $wpdb;
   
   if($post->post_parent==0){
       $query = "SELECT t.*,term_t.* FROM {$wpdb->posts} post,{$wpdb->term_relationships} term_re, {$wpdb->term_taxonomy} term_t, {$wpdb->terms} t 
       WHERE post.ID=term_re.object_id and term_re.term_taxonomy_id=term_t.term_taxonomy_id and term_t.term_id=t.term_id and post.ID ={$id} and term_t.taxonomy ='{$taxanomy}'";
       //echo $query;
       $r = $wpdb->get_results($query);
       return $r[0]->name;
   } 
}

function listAllCategory($taxanomy,$is_parent=true) {
   global $wpdb; 
    
   $query = "SELECT t.*,term_t.* FROM   {$wpdb->term_taxonomy} term_t, {$wpdb->terms} t 
       WHERE  term_t.term_id=t.term_id and term_t.taxonomy ='{$taxanomy}'".($is_parent ? " and term_t.parent=0":"");
       //echo $query;
       $r = $wpdb->get_results($query);
     return is_array($r) && count($r)>0 ? $r :null; 
}//custom site
?>