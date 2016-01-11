 <?php

    /**

    *  Model Ecommercial in plugin

    */

    class Ecommercial_wp_model extends Category_model

    {
		protected $cart = array(
			"name"=>"democart"
		);
		
		protected $search = array(

			"title"=>null,

			"date"=>null,

			"auto"=>null

		);

         public function __construct(){

            parent::__construct();

			$this->post_type = 'product';

			$this->taxonomy = 'product_cat';

            return $this; 

         }
		//override
		/**
        * Get sub categories
        * 
        * @param mixed $array
        */
        public function hasChild($array=null){
            $db = $this->db;
             $query = "SELECT * from $db->terms as t1 , $db->term_taxonomy as t2, $db->wpsc_meta as t3 where t1.term_id = t2.term_id and t1.term_id=t3.object_id and t2.term_id=t3.object_id and t3.meta_key='image' and t2.parent={$this->category->term_id}";
            echo $query;
			die();
			
            $a = $db->get_results($query);
			
            if(is_array($a) && count($a)>0){
                $this->children = $a;
                return true;
            }
            return false;
        }
       
		//override
        public function categories($array=null){
           $db = $this->db;
             $query = "SELECT t1.*,t2.* from $db->terms as t1 , $db->term_taxonomy as t2 where t1.term_id = t2.term_id and t2.taxonomy='{$this->taxonomy}'";
            
            $a = $db->get_results($query);
               return is_array($a) && count($a)>0 ? $a : null; 
       }

        //////////////// other method from plugin

		public function post($id){

			mysql_query("SET SESSION group_concat_max_len = 1000000;");

			$a =   $this->db->get_results("

               SELECT term_r.term_taxonomy_id,p.* ,group_concat(meta) as meta FROM (

                        SELECT

                            CASE

                                WHEN meta_value IS NULL THEN CONCAT(meta_key, '=', \"null\")

                                ELSE  CONCAT(meta_key, \"=\", meta_value)

                            END AS meta,post_id

                        FROM ".$this->tableName('postmeta')." 

                    ) as mp ,".$this->tableName('posts')." p, ".$this->tableName('term_relationships')." term_r

where mp.post_id= p.ID and term_r.object_id=p.ID and  p.ID={$id} && p.post_type='{$this->post_type}'");



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

					 $a = $obj;

				}

			return $a;

		}

		//override

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

		/**

        * List product from sub parent with specfic level

        * 

        * @param mixed $limit

        */

		public function productFromChild($limit=10){

            $this->pagation = Ahlu::Library("Ahlu_WP_Pagation",$this->db);

            //$this->pagation->setPage($page);

            $this->pagation->setLimit($limit);

            

			$query = "Select post.*,metap.meta from  ( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

                        CASE meta_value

                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

			   ELSE CONCAT(meta_key, '=', meta_value)

                        END AS meta,post_id as ID

                    FROM {$this->db->postmeta}

                ) as mp GROUP BY ID )  as metap, {$this->db->term_taxonomy} term_tax,{$this->db->term_relationships} term_tax_re,{$this->db->posts} post where metap.ID= post.ID and post.ID=term_tax_re.object_id and  term_tax.term_taxonomy_id=term_tax_re.term_taxonomy_id and term_tax.parent ={$this->category->term_taxonomy_id}";

            

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

		/**

        * List related products

        * 

        * @param mixed $page

        * @param mixed $limit

        */

		public function relatedByName($name,$limit=10){

			if($name==null) return null;

			

            $this->pagation = Ahlu::Library("Ahlu_WP_Pagation",$this->db);

            //$this->pagation->setPage($page);

            $this->pagation->setLimit($limit);

            

			$query = $this->_relatedProducts("post_title like %'{$name}'%");

            $this->pagation->excute($query); 

            return $this->pagation;

        }

		

        /**

        * Search products by date

        * 

        * @param mixed $date

        * @param mixed $page

        * @param mixed $limit

        */

		public function relatedByDate($date,$limit=10){

            $this->pagation = Ahlu::Library("Ahlu_WP_Pagation",$this->db);

            //$this->pagation->setPage($page);

            $this->pagation->setLimit($limit);

            

			$date = date("Y-m-d G-h-i",strtotime($date));

			$query = $this->_relatedProducts("post_date like %'{$date}'%");

            $this->pagation->excute($query); 

            return $this->pagation;

        }

		/**

        * Search products from date

        * 

        * @param mixed $date

        * @param mixed $page

        * @param mixed $limit

        */

		public function relatedFromDate($date,$limit=10){

            $this->pagation = Ahlu::Library("Ahlu_WP_Pagation",$this->db);

            //$this->pagation->setPage($page);

            $this->pagation->setLimit($limit);

			

            $date = date("Y-m-d G-h-i",strtotime($date));

			$query = $this->_relatedProducts("post_date >= '{$date}'");

            $this->pagation->excute($query); 

            return $this->pagation;

        }

		/**

        * Search products from date

        * 

        * @param mixed $date

        * @param mixed $page

        * @param mixed $limit

        */

		public function relatedToDate($date,$limit=10){

            $this->pagation = Ahlu::Library("Ahlu_WP_Pagation",$this->db);

            //$this->pagation->setPage($page);

            $this->pagation->setLimit($limit);

            

			$date = date("Y-m-d G-h-i",strtotime($date));

			$query = $this->_relatedProducts("post_date <= '{$date}'");

            $this->pagation->excute($query); 

            return $this->pagation;

        }

        /**

        * Search products by  a range of date

        * 

        * @param mixed $from

        * @param mixed $to

        * @param mixed $page

        * @param mixed $limit

        */

		public function rangeDate($from,$to,$limit=10){

            $this->pagation = Ahlu::Library("Ahlu_WP_Pagation",$this->db);

            //$this->pagation->setPage($page);

            $this->pagation->setLimit($limit);

            

			$from = date("Y-m-d G-h-i",strtotime($from));

			$to = date("Y-m-d G-h-i",strtotime($to));

			$query = $this->_relatedProducts("post_date BETWEEN '{$from}' and '{$to}'");

            $this->pagation->excute($query); 

            return $this->pagation;

        }

        /**

        * Search by meta field

        * 

        * @param array $str

        * @param mixed $page

        * @param mixed $limit

        */

		public function relatedBy($str,$limit=10){

            $this->pagation = Ahlu::Library("Ahlu_WP_Pagation",$this->db);

            //$this->pagation->setPage($page);

            $this->pagation->setLimit($limit);

            

			if(is_string($str)){

				$str = explode("=",$str);

				$query = $this->_relatedProducts("{$str[0]} like %'{$str[1]}'%");

				$this->pagation->excute($query); 

				return $this->pagation;

			}

			return null;

        }

		protected function _relatedProducts($search=""){

			$query = "SELECT metap.meta,p.* from( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

                        CASE meta_value

                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

			   ELSE CONCAT(meta_key, \"=\", meta_value)

                        END AS meta,post_id as ID

                    FROM {$this->db->postmeta}

                ) as mp GROUP BY ID )  as metap,{$this->db->posts} p

				WHERE p.ID = metap.ID  and p.post_status='{$this->post_status}' and p.post_type='{$this->post_type}

				 ".(empty($search)?" and p.".ltrim($search)." ":"");

            return $query; 

        }

		public function discountProduct($limit=6,$isRandom=false){

			

		$query = "SELECT metap.meta,post.* from( SELECT group_concat(mp.meta) as meta,mp.ID from ( SELECT  meta1.post_id as ID from {$this->db->postmeta} meta1

                     WHERE meta1.meta_key='_wpsc_special_price' and  meta1.meta_value <>0) as special left join (

                    SELECT

                        CASE meta_value

                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

                            ELSE  CONCAT(meta_key, \"=\", meta_value)

                        END  AS meta,post_id as ID

                    FROM {$this->db->postmeta}

                ) as mp on mp.ID=special.ID

				 group by  ID) as metap,{$this->db->posts} post

				WHERE post.ID = metap.ID  and post.post_status='{$this->post_status}' and post.post_type='{$this->post_type}'

				limit 0,{$limit}

				";

			$rs = $this->db->get_results($query);

			//echo $query;

			if(count($rs)>0){

			

				foreach($rs as $obj){

					$temps = explode(",",$obj->meta);

					unset($obj->meta);

					foreach($temps as  $v){

						$k=explode("=",ltrim($v,"_"));

						

						$obj->{$k[0]} = StringUtil::is_serialized($k[1]) ? unserialize($k[1]) : $k[1];

					}

					//get id thumbail use feature

					if(isset($obj->thumbnail_id)){

						$query = "SELECT guid from {$this->db->posts} where ID=$obj->thumbnail_id";

						$a = $this->db->get_results($query);

						if(count($a)>0){

							$obj->thumbnail= $a[0]->guid;

							unset($obj->thumbnail_id);

						}

					}

				}

				return $rs;

			}

			return null;

		}

		

		public function newProduct($limit=6){

			$query = "SELECT metap.meta,p.* from( SELECT group_concat(meta) as meta,mp.ID  from (SELECT

                        CASE meta_value

                            WHEN  NULL THEN CONCAT(meta_key, '=', \"null\")

			   ELSE CONCAT(meta_key, \"=\", meta_value)

                        END AS meta,post_id as ID

                    FROM {$this->db->postmeta}

                ) as mp GROUP BY ID )  as metap,{$this->db->posts} p

				WHERE p.ID = metap.ID  and p.post_status='{$this->post_status}' and p.post_type='{$this->post_type}'

				 order by p.post_date DESC

				 limit 0,{$limit}

				";

			$rs = $this->db->get_results($query);



			if(count($rs)>0){

				foreach($rs as $obj){

					$temps = explode(",",$obj->meta);

					unset($obj->meta);

					foreach($temps as  $v){

						$k=explode("=",ltrim($v,"_"));

						

						$obj->{$k[0]} = StringUtil::is_serialized($k[1]) ? unserialize($k[1]) : $k[1];

					}

					//get id thumbail use feature

					if(isset($obj->thumbnail_id)){

						$query = "SELECT guid from {$this->db->posts} where ID=$obj->thumbnail_id";

						$a = $this->db->get_results($query);

						if(count($a)>0){

							$obj->thumbnail= $a[0]->guid;

							unset($obj->thumbnail_id);

						}

					}

				}

				return $rs;

			}

			return null;

		}
		
		/**
		* Get cart review order
 		* @return 
		*/
		public function orderReview($cart){
			if(empty($cart))return null;
			if(!is_string($cart))return null;
			
			$query="select cart.prodid, cart.purchaseid,cart.name,cart.price as unique_price,sum(cart.quantity)as quantity,(quantity* cart.price) as price from {$this->tableName("wpsc_cart_contents")} cart where cart.purchaseid ='{$cart}'
group by cart.prodid
order by cart.name ASC";

			$rs = $this->db->get_results($query);
			if(is_array($rs) && count($rs)>0){
				$subTotal = 0;
				$shipping =0;
				foreach($rs as $item){
					$subTotal+=(int)$item->price;
				}
				$total= $subTotal+$shipping;
				return (object)array("items"=>$rs,"total"=>$total,"subTotal"=>$subTotal,"shipping"=>0);
			}
			return null;
		}
		
		/////////////Cart
		public function cartExist(){
			$cart = $this->getCart();
			return !empty($cart);
		}
		public function getCart(){
			return isset($_COOKIE[$this->cart["name"]]) ? $_COOKIE[$this->cart["name"]]: null;
		}
    }
	
    ?>