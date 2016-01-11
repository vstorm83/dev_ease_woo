<?php

    /**

    *  Model Experience

    */

    class Experience_model extends Category_model
    {
         public function __construct(){
            parent::__construct();
			
			$this->post_type = "experience";
			$this->taxonomy = "experience_genre";
		 
            return $this; 

         }

       public function postPaging($limit=10){

            $this->pagation = Ahlu::Library("Ahlu_WP_Pagation",$this->db);

            //$this->pagation->setPage($page);

            $this->pagation->setLimit($limit);

            mysql_query("SET SESSION group_concat_max_len = 1000000;");

			$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

                        CASE meta_value

                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

			   ELSE CONCAT(meta_key, '=', meta_value)

                        END AS meta,post_id as ID

                    FROM {$this->db->postmeta}

                ) as mp GROUP BY ID )  as metap, {$this->db->term_taxonomy} term_tax,{$this->db->term_relationships} term_tax_re,{$this->db->posts} post where metap.ID= post.ID and post.ID=term_tax_re.object_id and  term_tax.term_taxonomy_id=term_tax_re.term_taxonomy_id and term_tax.term_taxonomy_id ={$this->category->term_taxonomy_id}";

            

			//echo $query;

			$this->pagation->excute($query); 

			if($this->pagation->hasData){

				$o = new stdClass();

				$o->link = $this->pagation->PageLinks(true);

				$o->data = $this->pagation->PageData();

				if(is_array($o->data)){

					foreach($o->data as $obj){

					

					if(isset($obj->meta)){

						$meta = explode(",",$obj->meta);

						foreach($meta as  $v){

							$k=explode("=",ltrim($v,"_"));

							$obj->{$k[0]} = StringUtil::is_serialized($k[1]) ? unserialize($k[1]) : $k[1];

						}

						unset($obj->meta);

						unset($meta);

						

						if(isset($obj->thumbnail_id)){

							$query = "SELECT post.guid from  {$this->db->posts} as post where post.ID={$obj->thumbnail_id}";

							 //echo $query;

							$a = $this->db->get_results($query);

							//print_r($a);

							if(count($a)>0){

								unset($obj->thumbnail_id);

							   $obj->thumbnail =$a[0]->guid;

							}

						}

					}

				  }

				  

				  

				}

				return $o;

			}

			return null;

        }

	}

    ?>