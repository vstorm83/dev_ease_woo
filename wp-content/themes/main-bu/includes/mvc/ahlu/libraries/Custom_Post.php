<?php
/*
* Class Custom Post Type
*
*/
    class Custom_Post{

        public $post_type_name;
        private $post_type_nameby;
        public $post_type_args;
        public $post_type_labels;

        protected static $registered_post_type = array();

        protected $taxanomy = array();
        protected static $is_load = false;
		
		//info custom
		protected $info = array("post_type"=>null,"action"=>null,"id"=>-1);
        protected $load = null;
		
        public $showColumn = true;
		/*
		*	create Column info
		*/
        protected $columnIns = null;

        //name the post_type
		//protected $name_type="ahlu";
		protected $name_type="";

        public function __construct($name="hello",$replaceBy=null,$isNew =true)
        {    
			if(!$isNew) return $this;
			
             $this->post_type_name = strtolower($name); // must be in db
             $this->post_type_nameby =  $replaceBy;

            // Add action to register the post type, if the post type doesnt exist

            if( ! post_type_exists( $name ) )
            {
                //print_r(Ahlu::Call("WP_Event")->Action());
                Ahlu::Call("WP_Event")->Action()->Add_action( 'init', array( &$this, 'register_post_type' ) );   
				add_action('admin_init', array( &$this, '_admin_init' ));
            }

          // Listen for the save post hook
           $this->save();

           //show default  
          //this->columns(); 

          Ahlu::Call("WP_Event")->Action()->Add_action( 'restrict_manage_posts',array(&$this,'my_filter_list') );  

          //add taxanomy default  after init
          $this->add_taxonomy($this->post_type_name);        

        }
		public function load($f=null){
			$this->load = $f;
		}
		/***
		  * Conditional enqueue of scripts according to Admin page
		 * Based on http://wordpress.stackexchange.com/a/9095/12615
		*/
		public function _admin_init()
		{
			global $pagenow;
			global $firephp; // Using FirePHP for debugging - Remove if library not included
			if ( 'edit.php' == $pagenow) 
			{
				if ( !isset($_GET['post_type']) )
				{
					$firephp->log('I am the Posts listings page');  
				}
				else if ( isset($_GET['post_type']) )
				{
					$this->info['post_type'] = $_GET['post_type'];
				}
			}
			if ('post.php' == $pagenow && isset($_GET['post']) )
			{
				// Will occur only in this kind of screen: /wp-admin/post.php?post=285&action=edit
				// and it can be a Post, a Page or a CPT
				$post_type = get_post_type($_GET['post']);
				
				$this->info['post_type'] = $post_type;
				
				if ( isset($_GET['action']) && 'edit' == $_GET['action'])
				{
					$this->info['action'] = $_GET['action'];
				}
				
				$this->info['id'] = $_GET['post'];
			}

			if ('post-new.php' == $pagenow )
			{
				// Will occur only in this kind of screen: /wp-admin/post-new.php
				// or: /wp-admin/post-new.php?post_type=page
				if ( !isset($_GET['post_type']) ) 
				{
					//$firephp->log('I am creating a new post');  
				}
				else{
					$this->info['post_type'] = $_GET['post_type'];
					$this->info['action'] = "add";
				}
			}   
			
			//ask callback
			if($this->load instanceof Closure){
				call_user_func_array($this->load,$this->info);
			}
			
			
		}
        public static function registeredPost(){
          return self::$registered_post_type;
        }
		
		public static function get($post_type,$filters = array()){
			$post_type = strtolower($post_type);
            $post_types = get_post_types();
			
			if(isset($post_types[$post_type])){
				$a = new self(null,null,false);
				$a->post_type_name = $post_type;
				//setting save event
				$a->save();
				return $a;
			}
			return null;
        }
        /*
        * Create Menu
        */
        public function register_post_type()
        {   
            $me = $this;  

            //Capitilize the words and make it plural
            $name         = ucwords( str_replace( '_', ' ', $this->post_type_name ) );
            $plural     = ucwords($this->post_type_nameby!=null ? $this->post_type_nameby: $name. 's') ;

            // We set the default labels based on the post type name and plural. We overwrite them with the given labels.

            $labels = array(
            'name' => _x($plural , 'post type general name'),
            'singular_name' => _x($name .' Item', 'post type singular name'),
            'add_new' => _x('Add New', $name.' item'),
            'add_new_item' => __('Add New '.$name.' Item'),
            'edit_item' => __('Edit '.$name.' Item'),
            'new_item' => __('New '.$name.' Item'),
            'view_item' => __('View '.$name.' Item'),
            'search_items' => __('Search '.$name),
            'not_found' =>  __('Nothing found'),
            'not_found_in_trash' => __('Nothing found in Trash'),
            'parent_item_colon' => ''
          );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            //'menu_icon' => get_stylesheet_directory_uri() . '/article16.png',
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title','editor','thumbnail','excerpt','custom-fields','page-attributes')
          ); 

             //create file system
             $this->callFile();
             //create post
             register_post_type( $name  , $args );
             self::$registered_post_type[] = $name;

           //remove taxonomy box

            /*

              Ahlu::Call("WP_Event")->Action()->Add_action( 'admin_menu', function() use($me,$name)

            {                

                    $name = strtolower($name);

                // remove default category type metabox

                remove_meta_box( $name."_genre" .'div', $name, 'side' );

                // remove default tag type metabox

                remove_meta_box( 'tagsdiv-'.$name."_genre", $name, 'side' );

               $me->add_meta_box_taxomamy($name); 

            });

             Ahlu::Call("WP_Event")->Action()->Add_action( 'admin_enqueue_scripts', function() use($me){

                 

             });

             */

            }


		/*
		* add root term
		*/
        public function add_taxonomy( $name, $args = array(), $labels = array() )

        {

            $me =$this;

            if( ! empty( $name ) )

            {  

                // We need to know the post type name, so the new taxonomy can be attached to it.

                $post_type_name = $this->post_type_name;



                // Taxonomy properties

                $taxonomy_name        = strtolower( str_replace( ' ', '_', $name) )."_".$this->name_type;

                $taxonomy_labels    = $labels;

                $taxonomy_args        = $args;

                if(!isset($this->taxanomy[$name]))

                    $this->taxanomy[$name] = $taxonomy_name;

                //Capitilize the words and make it plural

                  //  $name         = ucwords( str_replace( '_', ' ', $name ) );

                    //$plural     = $name . 's';

                    

                     Ahlu::Call("WP_Event")->Action()->Add_action( 'init', function () use($taxonomy_name,$post_type_name){

                         $name         = ucwords( str_replace( '_', ' ', $taxonomy_name ) );   

                         register_taxonomy(

                              $taxonomy_name,      // name in db, must be tolower 

                              $post_type_name,            // the same as post type

                               array(

                                                            'labels' => array(

                                                                                    'name' => $name,

                                                                                    'add_new_item' => 'Add New '.$name,

                                                                                    'new_item_name' => "New $name Type"

                                                                                      ),

                                                           'show_ui' => true,

                                                           'show_tagcloud' => true,

                                                           'hierarchical' => true

                                                                   )

                                );

                         

                     } ); 



            }

            

        }

        /**                                                                                    

        * Add box Taxanomy default in this plugin

        *  

        * @param mixed $taxonomy_name

        * @param mixed $name

        */

        public function add_meta_box_taxomamy($name) {

              $custom_tax_mb = new Add_Meta_Box($name);  

               // Update optional properties     

         

               // $custom_tax_mb->priority = 'low';

               // $custom_tax_mb->context = 'normal';

               // $custom_tax_mb->metabox_title = __( 'Custom Metabox Title', 'yourtheme' );

               // $custom_tax_mb->force_selection = true; 

              // print_r($custom_tax_mb);  

        }

        

        /* 
		* Attache meta boxe to the post type with the field
		*/

        public function add_meta_box( $title, $fields = array(), $context = 'normal', $priority = 'default' )

        {

            if( ! empty( $title ) )

            {        

                // We need to know the Post Type name again

                $post_type_name = $this->post_type_name;



                // Meta variables    

                $box_id         = strtolower( str_replace( ' ', '_', $title ) );

                $box_title        = ucwords( str_replace( '_', ' ', $title ) );

                $box_context    = $context;

                $box_priority    = $priority;



                // Make the fields global

                global $custom_fields;


                $custom_fields[$title] = $fields;

		
                Ahlu::Call("WP_Event")->Action()->Add_action( 'admin_init',

                        function() use( $box_id, $box_title, $post_type_name, $box_context, $box_priority, $fields )

                        {                

                            add_meta_box(

                                $box_id,

                                $box_title,

                                function( $post, $data ) use( $post_type_name, $fields )

                                {

                                    global $post;



                                    // Nonce field for some validation

                                    wp_nonce_field( plugin_basename( __FILE__ ), 'custom_post_type' );



                                    // Get all inputs from $data

                                    $custom_fields = $data['args'][0];



                                    // Get the saved values

                                    $meta = get_post_custom( $post->ID );



                                    // Check the array and loop through it

                                    if( ! empty( $custom_fields ) )

                                    {

                                        /* Loop through $custom_fields */

                                        foreach( $custom_fields as $label => $type )

                                        {

                                            $field_id_name     = strtolower( str_replace( ' ', '_', $data['id'] ) ) . '_' . strtolower( str_replace( ' ', '_', $label ) );

                                            

                                            $type = strtolower($type);
											//echo script
											$time = time().$field_id_name; 
											echo '<script>
												
												var id_'.$time.' = window.setInterval(function(){
												if(window.ahlu_'.$post_type_name.'){clearInterval(id_'.$time.'); }
												window.ahlu_'.$post_type_name.'.add("'.$field_id_name.'");
											},3000)</script>';

                                            if($type==="editor"){

                                               echo '<p><label style="width: 20%;display: block;float: left;" for="' . $field_id_name . '">' . ucwords($label) . '</label><div style="clear:both;"></div></p>';

                                               the_editor(isset($meta[$field_id_name])?$meta[$field_id_name][0]:"",$field_id_name); 
												
												echo '<script>
												
												setTimeout(function(){
													jQuery(".switch-tmce").trigger("click");
												},1000)</script>';
                                            }else if($type=="upload" || $type=="file"){
												$value = isset($meta[$field_id_name])?$meta[$field_id_name][0]:"";
												$display = isset($meta[$field_id_name]) && !empty($meta[$field_id_name][0])?"display:block":"display:none";
												
												echo <<<AHLU
													<p>Upload Images <a href="#" onclick="return upload({$post->ID},function(data){jQuery('.{$field_id_name}-img').attr('src',data.url).show('slow');jQuery('.{$field_id_name}').val(data.url);});">+Add</a></p>
													<img alt="Double here to edit image" class="{$field_id_name}-img" onclick="return upload({$post->ID},function(data){jQuery('.{$field_id_name}-img').attr('src',data.url).show('slow');jQuery('.{$field_id_name}').val(data.url);});" src="{$value}" style="width:50%;{$display}" />
													<input type="hidden" name="{$field_id_name}" class="{$field_id_name}" value="{$value}" />
AHLU;
												//include js upload
												include "js/upload.php";
                                            }else if($type=="gallery"){
											
											}else{
												$value = isset($meta[$field_id_name]) ? "0" : "1";
                                                echo '<p><label style="width: 20%;display: block;float: left;" for="' . $field_id_name . '">' . ucwords($label) . '</label><input type="'.$type.'" name="'. $field_id_name . '" id="' . $field_id_name . '" value="' . $value . '" /></p>'; 

                                            }

                                            
											
                                        }

                                    }



                                },

                                $post_type_name,

                                $box_context,

                                $box_priority,

                                array( $fields )

                            );

                        }

                    );

            }



        }

         

            /* Listens for when the post type being saved */

        public function save()

        {

			//register JS
			$dir = get_template_directory()."/cache";
			$dir_uri = get_template_directory_uri()."/cache";
			if(!is_dir($dir)){
				mkdir($dir,"0775",true);
			}
			if(!file_exists($dir."/ahlu-{$this->post_type_name}.js")){
				touch($dir."/ahlu-{$this->post_type_name}.js");
			}
			//add
			file_put_contents($dir."/ahlu-{$this->post_type_name}.js",'
				jQuery(document).ready(function(){
					jQuery("form#post").prepend("<input type=\"hidden\" name=\"ahlu-'.$this->post_type_name.'_fields\" class=\"ahlu-'.$this->post_type_name.'\" />");
					window.ahlu_'.$this->post_type_name.' = {
						add : function(val){
							var a = jQuery(".ahlu-'.$this->post_type_name.'").val();
							jQuery(".ahlu-'.$this->post_type_name.'").val((a==""?val:a+"+"+val));
						}
					};
			
				});
			');
			wp_enqueue_script(
				"ahlu-{$this->post_type_name}",$dir_uri."/ahlu-{$this->post_type_name}.js",
				array( 'jquery' )
			);
			
			///
			//checking is admin page
			if(!is_admin()) return $this;
			
            // Need the post type name again

            $post_type_name = $this->post_type_name;

            $me = $this;

            Ahlu::Call("WP_Event")->Action()->Add_action( 'save_post',

                function() use( $post_type_name,$me)

                {    

                    // Deny the wordpress autosave function

                    if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;



                   // if ( ! wp_verify_nonce( $_POST['custom_post_type'], plugin_basename(__FILE__) ) ) return;


                    global $post;
					
				   //print_r($_POST);
                   //die();

                    if( isset( $_POST ) && isset( $post->ID ) && get_post_type( $post->ID ) == $post_type_name )

                    {

                        global $custom_fields;


                        if(is_array($custom_fields)){

                            // Loop through each meta box

                            foreach( $custom_fields as $title => $fields )

                            {

                                // Loop through all fields

                                foreach( $fields as $label => $type )

                                {

                                    $field_id_name     = strtolower( str_replace( ' ', '_', $title ) ) . '_' . strtolower( str_replace( ' ', '_', $label ) );



                                    update_post_meta( $post->ID, $field_id_name, $_POST[$field_id_name] );

                                }



                            }

                        }else{
							//this happend when this metapost, it not create before in the post type with action add new
							if(isset($_POST["ahlu-{$post_type_name}_fields"])){
								$a = explode("+",$_POST["ahlu-{$post_type_name}_fields"]);
							//	print_r($a);
							//	die();
								// Loop through all fields

                                foreach($a as $field_id_name )

                                {
									if(isset($_POST[$field_id_name]))
                                    update_post_meta( $post->ID, $field_id_name, $_POST[$field_id_name] );

                                }

							}
							
						}

                        //insert mutil image if exist

                        $me->process_book_meta( $post->ID, $post );

                         

                    } 

                }

            );

        }

		/**
        * Get Object for columns for Table
        * 
        */
        public function getColumns($cols=null){ 
			if($this->columnIns==null){
				$this->columnIns = new Column_Post_Info($this->post_type_name);
				$this->columnIns->name_type = $this->post_type_name;
				$this->columnIns->setDefaultCols($cols);
			}
			return $this->columnIns;
        }

        

        /**

        * Add submenu in this menu

        * 

        * @param mixed $arr

        * @param mixed $view

        */

        public function add_submenu($arr=array(),$view = "hello"){

            $def =array(

             "label"=>"Settings"      ,

             "manage"=>"manage_options",

             "file"=>  basename(__FILE__)

            );

            

            $post_type = $this->post_type_name;

            $args = array_merge($def,$arr);

            

            Ahlu::Call("WP_Event")->Action()->Add_action('admin_menu' ,function() use($post_type,$view,$args) {

                

                add_submenu_page('edit.php?post_type='.$post_type, 'Custom Post Type Admin', $args["label"], $args["manage"], $args["file"],function() use ($view){

                    echo $view;

                });

            });



        }

        

        public function my_filter_list() {

               $screen = get_current_screen();

                global $wp_query;

               //print_r($screen);

                if ( $screen->post_type == $this->post_type_name ) {

                          wp_dropdown_categories(array(

                        'show_option_all' => 'Show All '.$this->post_type_name,

                        'taxonomy' => $this->post_type_name.'_genre',

                        'name' => $this->post_type_name.'_genre',

                        'orderby' => 'name',

                        'selected' =>( isset( $wp_query->query[$this->post_type_name.'_genre'] ) ?

                        $wp_query->query[$this->post_type_name.'_genre'] : '' ),

                      'hierarchical' => false,

                      'depth' => 3,

                      'show_count' => false,

                     'hide_empty' => true,

                                                                                                )

                    );

            }

        }

        

        

   /////////////////////////// Mutil upload    ////////////////////////////

        

    /**

     * Function for processing and storing all hello data.

     */

    public function process_book_meta( $post_id, $post ) {


        //now we get post_meta

        //$image_id = get_post_meta( $post->ID, '_image_id', true );   
		
		if(isset($_POST['upload_image_id_'.$this->post_type_name]))
        update_post_meta( $post_id, '_image_id_'.$this->post_type_name, $_POST['upload_image_id_'.$this->post_type_name]);

    }

        

        

     /**
     * Add and remove meta boxes from the edit page
	 * Main point
     */
    public function add_meta_mutil_upload() {

       if ( is_admin()) {

          $me = $this;

          $post_type_name =$this->post_type_name;

          //first load 

              add_action( 'admin_init', function() use($me){

                global $pagenow;

                
				 //add this box tp post type page- menu Pages
                 if ($pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) {
                        add_action( 'add_meta_boxes',array(&$me,"image_meta_box"));
                 }

            });



       }

    }

    public function  image_meta_box(){

        add_meta_box($this->post_type_name."-images", __( ucwords($this->post_type_name." gallery files") ), array( &$this, 'book_image_meta_box' ),$this->post_type_name, 'normal', 'high' );

    }

    

    

    /**

     * Display the image meta box upload multi images

     */

    public function book_image_meta_box() {

        global $post;

    

        $image_id = get_post_meta( $post->ID, '_image_id_'.$this->post_type_name, true );

        $image_ids = explode("+",$image_id); 

        

    ?>

    <style type="text/css">

       #gallery_pics{width: 100%; float: left;}

       #gallery_pics p{float: left;margin-right:5px;position:relative;padding-left: 4px;}

       #gallery_pics p.other{

           width:100px;

           height:100px;

           background: #ccc; 

           float: left;

           position: relative;

           margin: 1em 5px 1em 0px;

       }

       #gallery_pics p.other span.attachlink{

           display:block; 

           border: none;

           background: none;

           border-radius:0px;

           position: inherit;

           text-transform: capitalize;

           color: #000;

           height: 75%;

           text-align: center;

           line-height: 75px;

           width: 95%;

           font-size: 20px;

           text-shadow: 1px 1px 1px blue, 3px 3px 5px #fff;

       }

       #gallery_pics p.other span.attachname{

           font-size: 12px;

           display:block; border: none;

           background: none;

           border-radius:0px; 

           position : inherit;

           color: #000;

           text-align: center;

           width: 95%;

       }

       

        #gallery_pics p span{

            top: 0em;

            display: none;

            color: red;

            position: absolute;

            right: 5px;

            font-size: 17px;

            cursor: pointer;

            font-weight: bold;

            background: yellow;

            border-radius: 5px;

            padding: 2px 5px;

            cursor: pointer;

        }

       #gallery_pics p img {width:100px;height:100px;} 

       #gallery_pics p img:hover {cursor: pointer;} 

       #gallery_pics p:hover span{display:block;cursor:pointer;}

       

       .show_pic,.show_pic_url{

           display: none;

           font-size: 14px;

       }   

       .show_pic span{

           font-size: 14px;

           color: green;  

       }  

    </style>
	
		<?php /*html format */ ?>
        <div class="show_pic">Path: <span>abc</span></div>
        <div class="show_pic_url">URL: <span>#</span></div>

        <div id="gallery_pics">

    <?php
/*
        $args = array(

   'post_type' => 'attachment',

   'numberposts' => -1,

   'post_status' => null,

   'post__in' => $post->ID

  );

    $img_uploaded = get_post_meta($post->ID, "_image_id_{$this->post_type_name}", true);

    //print_r($img_uploaded);



  $args = array(

   'post_type' => 'attachment',

   'numberposts' => -1,

   'post_status' => null,

   'post__in' => explode("+",$img_uploaded)

  );

  

   list all category present

  $taxonomy     = $this->taxanomy["game"];



$catArgs = array(

            'taxonomy'=>$taxonomy

            // post_type isn't a valid argument, no matter how you use it.

            );

$categories = get_categories("taxonomy=$taxonomy&post_type=game"); ?>

 <?php foreach ($categories as $category) : ?>

      <div class="job-cat"><?php echo $category->name; ?></div>

        <?php

        $postArgs = array(

            'orderby' => 'title',

            'order' => 'ASC',

            'post_type'=>'game',

            'cat'=>$category->cat_ID,

            'tax_query' => array(

                    array(

                        'taxonomy' => $taxonomy

                    )

                )

            );

         query_posts($postArgs) ?>

        <ul>

             <?php while(have_posts()): the_post(); ?>

            <li><a><?php the_title() ?></a></li>

             <?php endwhile; ?>

        </ul>

 <?php endforeach; ?>

 <?php wp_reset_query();

 */

  /*  

  global $wpdb;

  $sl = new Ahlu_WP_PostByType($wpdb);



  $pagation = new Ahlu_WP_Pagation($wpdb);

               $pagation->setPage(isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1);

               $pagation->setLimit(12);

                $sl->setPostType("game");

                   

                $pagation->excute($sl->getQueryString()); 

                $data = $pagation->PageData(); 

                $pagation->PageLinks();             

  */

 //print_r($sl->getQueryString());

  $attachments = gallery_post($post->ID,$this->post_type_name);

          //print_r($attachments);

            if ( $attachments ) {

        foreach ( $attachments as $attachment ) {

            $ext = pathinfo($attachment->guid, PATHINFO_EXTENSION);

             if(in_array($ext,array("png","gif","jpeg","jpg","tif",".java",".class",".jar"))){

                echo '<p><img title="'.$attachment->post_title.'" alt="'.$attachment->post_title.'" data-url="'.$attachment->post_content.'" id='.$this->post_type_name.'_image" src="'.$attachment->guid.'" /> <span class="close" id="'.$attachment->ID.'">x</span></p> ';

             }

            else{

                echo '

                  <p class="other" id="'.$attachment->ID.'" data-url="'.$attachment->post_content.'" title="'.$attachment->post_title.'">

                    <span class="attachlink">'.$ext.'</span>

                    <span class="attachname"><i>'.substr(pathinfo($attachment->guid, PATHINFO_BASENAME),0,15).'</i></span>

                    <span class="url" style="display:none;">'.$attachment->guid.'</span>

                    <span class="close">x</span>

                 </p>

                ';

            }

          }

     }

        

        ?>

        

         

        

        </div>

        

        <input type="hidden" name="upload_image_id_<?php echo $this->post_type_name;?>" id="upload_image_id_<?php echo $this->post_type_name;?>" value="<?php echo $image_id; ?>" />

        <p>

            <a title="<?php esc_attr_e( 'Set '.$this->post_type_name.' image' ) ?>" href="#" id="set-book-image"><?php echo 'Set '.$this->post_type_name." gallery files"; ?></a>

        </p>

        

        <script type="text/javascript">

        function removeItem(array, item){

            for(var i in array){

                if(array[i]==item){

                    console.log(i);

                    array.splice(i,1);

                    break;

                    }

            }

            

            return array;

        }

          function parseURL(url) {

            var a =  document.createElement('a');

            a.href = url.toLowerCase();

            var obj = {

                source: url,

                protocol: a.protocol.replace(':',''),

                host: a.hostname,

                port: a.port,

                query: a.search,

                params: (function(){

                    var ret = {},

                        seg = a.search.replace(/^\?/,'').split('&'),

                        len = seg.length, i = 0, s;

                    for (;i<len;i++) {

                        if (!seg[i]) { continue; }

                        s = seg[i].split('=');

                        ret[s[0]] = s[1];

                    }

                    return ret;

                })(),

                file: (a.pathname.match(/\/([^\/?#]+)$/i) || [,''])[1],

                hash: a.hash.replace('#',''),

                path: a.pathname.replace(/^([^\/])/,'/$1'),

                relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [,''])[1],

                segments: a.pathname.replace(/^\//,'').split('/')

            };

            

                obj.name=obj.file.split('.')[0],

                obj.ext = obj.file.split('.').pop(),

                obj.extension = obj.file.split('.').pop(),

                obj.isImage = /(\.jpg|\.jpeg|\.gif|\.png|\.tif)$/i.test(obj.file),

                obj.type = function($bool){

                   //group name images

                   

                   //group name document

                   

                   //group name media 

                };

            return obj;

        }

         function capitalize (str){

            return str.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );

         };

        jQuery(document).ready(function($) {

           var gallery = $("#gallery_pics");

           var upload_image_id = $('#upload_image_id_<?php echo $this->post_type_name;?>');

           

            (function(){ 

                var tb_show_temp = window.tb_show; 

                window.tb_show = function() { 

                  tb_show_temp.apply(null, arguments); 

                  var iframe = jQuery('#TB_iframeContent');

                  iframe.load(function() {

                    var iframeDoc = iframe[0].contentWindow.document;

                    var iframeJQuery = iframe[0].contentWindow.jQuery;

                    var buttonContainer = iframeJQuery == undefined ? null : iframeJQuery('td.savesend');

                    if (buttonContainer) {

                      var btnSubmit = jQuery('input:submit', buttonContainer);

                      iframeJQuery(btnSubmit).click(function(){

                        var fldID = jQuery(this).attr('id').replace('send', '').replace('[', '').replace(']', '');

                        var imgurl = iframeJQuery('input[name="attachments\\['+fldID+'\\]\\[url\\]"]').val();

                        var title = iframeJQuery('input[name="attachments\\['+fldID+'\\]\\[post_title\\]"]').val(); 
                        var tr_post_content = jQuery(this).closest("tr.submit").siblings("tr.post_content");
						var post_content = tr_post_content.find('textarea').val(); 

                        //add image

                        var values = upload_image_id.val().split("+");

                        

                        console.log(parseURL(imgurl).isImage);

                        

                        if(upload_image_id.val()==""){

                          upload_image_id.val(fldID);  

                        }else{

                            if($.inArray(fldID, values)==-1){

                               values.push(fldID);

                               upload_image_id.val(values.join("+")); 

                            }

                            

                        }

                         var what = parseURL(imgurl);

                        if(what.isImage){

                          gallery.append('<p><img id="<?php echo $this->post_type_name;?>_image" src="'+imgurl+'" data-url="'+(post_content==""?"#":post_content)+'" title="'+imgurl+'"  /> <span id="'+fldID+'">x</span></p>'); 

                          

                        }else{

                          var s =' <p class="other" id="'+fldID+'" data-url="'+post_content+'" title="'+capitalize(title)+'">';                                     

                                    s+='<span class="attachlink">'+what.ext+'</span>';

                                    s+='<span class="attachname"><i>'+what.name.substring(0,15)+'</i></span>';

                                    s+='<span class="url" style="display:none;">'+imgurl+'</span> '; 

                                    s+='<span class="close">x</span>';

                               s+='</p>';

                          gallery.append(s);  

                        }

                         

                        tb_remove();

                      });

                    }

                  });

                   }

                  })();

                         

            // save the send_to_editor handler function

            window.send_to_editor_default = window.send_to_editor;

    
			//open new window to add image
            $('#set-book-image').click(function(){

                // replace the default send_to_editor handler function with our own

                window.send_to_editor = window.attach_image;

                tb_show('', 'media-upload.php?post_id=<?php echo $post->ID ?>&amp;type=image&amp;TB_iframe=true');

                return false;

            });
			
			//when img click and show info this image
            gallery.find("p img").live("click",function(e){

               jQuery(".show_pic").show("slow").find("span").html(jQuery(this).attr("src")); 
               jQuery(".show_pic_url").show("slow").find("span").html(jQuery(this).attr("data-url")); 

            });



            //delete img
            gallery.find("p span").live("click",function() {

                 var me = jQuery(this);

                     var retVal = confirm("Do you want to delete this thumnail?");

                       if(retVal){

                          var values = upload_image_id.val().split("+");

                           //remove value from array

                           var newVal = removeItem(values,parseInt(me.parent('p').attr("id")));

                           //console.log(newVal);

                           //alert($(this).parents("p").html());   

                            var parent = $(this).parents("p").remove();

                            //now set new value

                            upload_image_id.val(newVal.join("+")); 

                            //hide this url

                            jQuery(".show_pic").hide().find("span").html("");
                            jQuery(".show_pic_url").hide().find("span").html("");

                       }
                 return false;   

            });

            

           

            



            // handler function which is invoked after the user selects an image from the gallery popup.

            // this function displays the image and sets the id so it can be persisted to the post meta

            window.attach_image = function(html) {

                // console.log(html);
 		// turn the returned image html into a hidden image element so we can easily pull the relevant attributes we need

                jQuery('body').append('<div id="temp_image">' + html + '</div>'); 

                var item = jQuery('#temp_image').find('img');

                imgclass = null;  

                imgurl = null;  

                imgid= null;

                title = null;

                 

                if(item.attr("class")==null){

                   item = jQuery('#temp_image').find('a'); 

                   title = item.html();  

                   imgurl   = item.attr('href');

                   

                   if(item.attr('rel')!=null){

                        imgid = parseInt(item.attr('rel').replace("attachment wp-att-","")); 

                   } 

                   

                }else{

                  imgclass = item.attr('class');

                  imgid    = parseInt(imgclass.replace(/\D/g, ''), 10); 

                  imgurl   = item.attr('src');   

                }

                

                 if(imgid!=null){

                      if(upload_image_id.val()==""){

                      upload_image_id.val(imgid);  

                    }else{

                       upload_image_id.val(upload_image_id.val()+"+"+imgid); 

                    }

                 }

                //set icon   

                        var what = parseURL(imgurl);

                        if(what.isImage){

                          gallery.append('<p><img id="<?php echo $this->post_type_name;?>_image" data-url="#" src="'+imgurl+'" title="'+imgurl+'"  /> <span class="close" id="'+fldID+'">x</span></p>'); 

                          

                        }else{

                          var s =' <p class="other" title="'+capitalize(title)+'">';                                     

                                    s+='<span class="attachlink">'+what.ext+'</span>';

                                    s+='<span class="attachname"><i>'+what.name.substring(0,15)+'</i></span>';

                                    s+='<span class="url" style="display:none;">'+imgurl+'</span> ';

                                    s+='<span class="close">x</span>';

                               s+='</p>';

                          gallery.append(s);  

                        }

                        

                $('#remove-book-image').show();

                

                try{

                    tb_remove();

                }catch(e){};

                

                $('#temp_image').remove();

                // restore the send_to_editor handler function

                window.send_to_editor = window.send_to_editor_default;

                

            }

            

        });



        </script>

        <?php

    }
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

     

     

     ////////////////////////
	 /*
	 *	create file system for mvc
	 */
     private function callFile(){

         $make = Ahlu::Library("DAOMVC");

         $make->controller = ucfirst($this->post_type_name);

         $make->makeView();

         $make->makeController();

         $make->makeModel();

     }

    }

?>