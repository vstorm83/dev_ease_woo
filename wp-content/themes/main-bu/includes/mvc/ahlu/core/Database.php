<?php

    class Database{

        protected $db = null;

        

        public function __construct(){

            $this->db = &Ahlu::DB();

        }



          //////////////////////////post     

        

        public static function getPost($id){

            if(is_numeric($id)) return self::get_post($id);

			return null;
        }

        public static function get_post($id){

			$post = get_post($id);
			if(!empty($post)) return $post;
			//get meta post
			
			   mysql_query("SET SESSION group_concat_max_len = 1000000;");

             $a =   Ahlu::DB()->get_results("

               SELECT term_r.term_taxonomy_id,p.* ,group_concat(meta) as meta FROM (

                        SELECT

                            CASE

                                WHEN meta_value IS NULL THEN CONCAT(meta_key, '=', \"null\")

                                ELSE  CONCAT(meta_key, \"=\", meta_value)

                            END AS meta,post_id

                        FROM ".Ahlu::DB()->postmeta." 

                    ) as mp ,".Ahlu::DB()->posts." p, ".Ahlu::DB()->term_relationships." term_r

where mp.post_id= p.ID and term_r.object_id=p.ID and  p.ID={$id}");



              //alway

                if(is_array($a) && count($a)>0){

						$obj = $a[0];

						if(isset($obj->meta)){

						$meta = explode(",",$obj->meta);

						foreach($meta as  $v){

							$k=explode("=",ltrim($v,"_"));

							$obj->{$k[0]} = StringUtil::is_serialized($k[1]) ? unserialize($k[1]) : $k[1];

						}

						unset($obj->meta);

						unset($meta);

						

						if(isset($obj->thumbnail_id)){

							$query = "SELECT post.guid from  ".Ahlu::DB()->posts." as post where post.ID={$obj->thumbnail_id}";

							 //echo $query;

							$a = Ahlu::DB()->get_results($query);

							//print_r($a);

							if(count($a)>0){

								unset($obj->thumbnail_id);

							   $obj->thumbnail =$a[0]->guid;

							}

						}

				}
			}
			return $obj;	
		}
		
        public function getSlugPage($slug){

            //we check in post table

            $slug = mysql_escape_string($slug);

           $query = "SELECT * From {$this->db->posts} WHERE post_name='{$slug}'";

           $a = $this->db->get_results($query);

           if($a!=null && count($a)>0){

               return $a[0];

           }

           return null;

        }

        

        public function getSlug($post_type,$slug){

            //we check in post table

            $slug = strtolower(mysql_escape_string($slug));

            $post_type = strtolower(mysql_escape_string($post_type));

                

           $query = "SELECT t4.*,t2.*,t3.*,t1.* from $this->db->terms as t1 , $this->db->term_taxonomy as t2,$this->db->term_relationships as t3,$this->db->posts as t4 where t1.term_id = t2.term_id and t3.term_taxonomy_id = t2.term_taxonomy_id and t4.ID=t3.object_id and t4.post_name='{$slug}' and t4.post_type='{$post_type}' order by t4.ID DESC";

            $a = $this->db->get_results($query);

               return count(a)==1 ? $a[0] :$a;

        }

        

       

        

        

        

        //////////////////////////category or taxanomy 

        public static function PostTypeByCategory($item){

          $db = Ahlu::DB(); 

          $query =null;  

              if(is_numeric($item)) 

                {

                    $query = "SELECT * from  $db->term_taxonomy where term_id={$item} limit 1";

                }

            else if(is_string($item)){ 

                $item = mysql_escape_string($item);

                $query = "SELECT t2.* from $db->terms as t1, $db->term_taxonomy as t2 where t1.term_id = t2.term_id and t1.slug='{$item}' limit 1";  

            }else{

                trigger_error("Cannot not process the param is not string or numberic.");

            }

            //echo $query;

            $a = $db->get_results($query);

            //print_r($a);

            if(count($a)>0){

                return str_replace(array("_genre","_tax","_taxanomy","_taxo","_txnm"),"",$a[0]->taxonomy);

            }

            return  null;

        }

        public static function CategoryByPost($id){

            $db = Ahlu::DB(); 

            $query = "SELECT t1.* from $db->terms as t1 , $db->term_taxonomy as t2,$db->term_relationships as t3 where t1.term_id = t2.term_id and t3.term_taxonomy_id = t2.term_taxonomy_id and t3.object_id={$id}";

            $a = $db->get_results($query);

               return count(a)==1 ? $a[0] :$a;

        } 

         

        public static function is_category($name){

            $a = self::Category($name);

            if($a!=null){

              return $a;  

            }

            return false;

        }

        

        public static function Category($item){

            $db = Ahlu::DB();

            

            if(is_numeric($item)) 

                {

                    $query = "SELECT * from $db->terms where term_id={$item}";

                }

            else if(is_string($item)){ 

                $item = mysql_escape_string($item);

                $query = "SELECT * from $db->terms where slug='{$item}'";

            }else{

                trigger_error("Cannot not process the param is not string or numberic.");

            }

            $a = $db->get_results($query);

            //print_r($a);

               return is_array($a) && count($a)>0 ? $a : null;

        }

        /**

        * List all category from post type

        * 

        * @param string $type

        * @param mixed $is_parent

        * @return bool

        */

        public static function CategoryByType($type,$is_parent=true){

            $db = Ahlu::DB();

            $type = mysql_escape_string($type);

            $query = "SELECT t1.*,t2.* from $db->terms as t1 , $db->term_taxonomy as t2 where t1.term_id = t2.term_id and t2.taxonomy='{$type}'".($is_parent ? " and t2.parent=0 ":"")." order by t1.term_id DESC";

            //echo $query;

            $a = $db->get_results($query);

               return  is_array($a) && count($a)>0 ? $a :null;

        }

        

        public static function CategoryByPost_Type($type){

            $db = Ahlu::DB();

            $type = mysql_escape_string($type);

            $query = "SELECT * from $db->posts where post_type='{$type}' and post_status='publish' limit 1";

            

            $a = $db->get_results($query);

            $cate = $a[0];

            

            //print_r($a);

            $slug = strtolower(mysql_escape_string($cate->post_name));

            $post_type = strtolower(mysql_escape_string($cate->post_type));

                

           $query = "SELECT t1.* from $db->terms as t1 , $db->term_taxonomy as t2,$db->term_relationships as t3,$db->posts as t4 where t1.term_id = t2.term_id and t3.term_taxonomy_id = t2.term_taxonomy_id and t4.ID=t3.object_id and t4.post_name='{$slug}' and t4.post_type='{$post_type}' order by t4.ID DESC";

            $a = $db->get_results($query);

               return count(a)==1 ? $a[0] :$a;

        }

        

         public static function TaxanomyByPost($id){

            $db = Ahlu::DB();

            $query = "SELECT t2.* from $db->term_taxonomy as t2,$db->term_relationships as t3 where t3.term_taxonomy_id = t2.term_taxonomy_id and t3.object_id={$id}";

             $a = $db->get_results($query);

           $a = $db->get_results($query);

               return count(a)==1 ? $a[0] :$a;

        }

        public static function TaxanomyByPost_Type($type){

            $db = Ahlu::DB();

            $type = mysql_escape_string($type);

            $query = "SELECT * from $db->posts where post_type='{$type}' and post_status='publish' limit 1";

            

            $a = $db->get_results($query);

            $cate = $a[0];

            

            $query = "SELECT t2.* from $db->term_taxonomy as t2,$db->term_relationships as t3 where t3.term_taxonomy_id = t2.term_taxonomy_id and t3.object_id={$cate->ID}";

             $a = $db->get_results($query);

               return count(a)==1 ? $a[0] :$a;

        }

        

        

        ///////////////////////////// Slug///

        public static function category_slug($cat_id) {

            $cat_id = (int) $cat_id;

            $category = get_category($cat_id);

            return $category->slug;

        }

        public static  function the_slug($id) {

            $post_data = get_post($id, ARRAY_A);

            $slug = $post_data['post_name'];

            return $slug; 

        }

        /**

        * Get ID by slug name

        * 

        * @param string $slug

        * @param mixed $table

        */

        public static function getIdBySlug($slug,$table=null){

            $slug = mysql_escape_string($slug);

            $db = Ahlu::DB();  

            

            $return = new stdClass();

            

            $arr = array(

               "post" =>  "SELECT * from $db->posts where post_name='{$slug}' and post_status='publish'",

               "category" => "SELECT t.* , tax.* from $db->terms t,$db->term_taxonomy tax where tax.term_id = t.term_id and t.slug='{$slug}' limit 1",

               "taxanomy" =>  "SELECT * from $db->term_taxonomy where taxonomy='{$slug}' limit 1"

            ) ;

               

            if($table!=null){

                $table = strtolower($table);

                if(isset($arr[$table])){

                    $a = $db->get_results($arr[$table]);

                    if(count($a)>0){

                        $return->$table = $a[0];  

                   }else{

                       return null;

                   } 

                }

                return null; 

            }else{

                /*

                $f = false;

                foreach($arr as $k=>$v){

                     $a = $db->get_results($v);

                     if(count($a)>0){

                         $return->$k = $a[0];

                         $f  = true;

                     }else{

                        $return->$k =null; 

                     } 

                }

               

               return $f ? $return : null;

               */

              foreach($arr as $k=>$v){

                     $a = $db->get_results($v);

                     if(count($a)>0){

                         $return->$k = $a[0];

                         return $return;

                     }

                }

                

               return null;  

            }

        }

    }
	
?>