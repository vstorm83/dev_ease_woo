<?php
   /**
    * Removes and replaces the built-in taxonomy metabox with our radio-select metabox.
    * @link  http://codex.wordpress.org/Function_Reference/add_meta_box#Parameters
    */
   class Add_Meta_Box {
 
      // Post types where metabox should be replaced (defaults to all post_types associated with taxonomy)
      public $post_types = array();
      // Taxonomy slug
      public $slug = '';
      // Taxonomy object
      public $taxonomy = false;
      // New metabox title. Defaults to Taxonomy name
      public $metabox_title = '';
      // Metabox priority. (vertical placement)
      // 'high', 'core', 'default' or 'low'
      public $priority = 'high';
      // Metabox position. (column placement)
      // 'normal', 'advanced', or 'side'
      public $context = 'side';
      // Set to true to hide "None" option & force a term selection
      public $force_selection = false;
	  
	  public $name_type="ahlu";
 
      /**
       * Initiates our metabox action
       * @param string $tax_slug      Taxonomy slug
       * @param array  $post_types    post-types to display custom metabox
       */
      public function __construct( $tax_slug, $post_types = array() ) {
 
         $this->slug = $tax_slug;
         $this->post_types = is_array( $post_types ) ? $post_types : array( $post_types );
 
         add_action( 'add_meta_boxes', array( $this, 'add_radio_box' ) );
      }
 
      /**
       * Removes and replaces the built-in taxonomy metabox with our own.
       */
      public function add_radio_box() {
          /*
         foreach ( $this->post_types() as $key => $cpt ) {
            // remove default category type metabox
            remove_meta_box( $this->slug .'div', $cpt, 'side' );
            // remove default tag type metabox
            remove_meta_box( 'tagsdiv-'.$this->slug, $cpt, 'side' );
            // add our custom radio box
            add_meta_box( $this->slug .'_radio', $this->metabox_title(), array( $this, 'radio_box' ), $cpt, $this->context, $this->priority );
         }
         */
         add_meta_box( $this->slug.$this->name_type, ucwords(str_replace("_"," ",$this->slug))." Category", array( $this, 'radio_box' ), $this->slug, $this->context, $this->priority );  
      }
 
      /**
       * Displays our taxonomy radio box metabox
       */
      public function radio_box() {
         // uses same noncename as default box so no save_post hook needed
         wp_nonce_field( 'taxonomy_'. $this->slug, 'taxonomy_noncename' );
         
                                            //Get taxonomy and terms  
		$taxonomy = $this->slug.$this->name_type;  
	  
		//Set up the taxonomy object and get terms  
		$tax = get_taxonomy($taxonomy);  
		$terms = get_terms($taxonomy,array('hide_empty' => 0));  
	  
		//Name of the form  
		$name = 'tax_input[' . $taxonomy . ']';  
	  
		//Get current and popular terms  
		$popular = get_terms( $taxonomy, array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hierarchical' => false ) );  
		$postterms = get_the_terms( $post->ID,$taxonomy );  
		$current = ($postterms ? array_pop($postterms) : false);  
		$current = ($current ? $current->term_id : 0);  
		?>  
	  
		<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">  
	  
			<!-- Display tabs-->  
			<ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">  
				<li class="tabs"><a href="#<?php echo $taxonomy; ?>-all" tabindex="3"><?php echo $tax->labels->all_items; ?></a></li>  
				<li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop" tabindex="3"><?php _e( 'Most Used' ); ?></a></li>  
			</ul>  
	  
			<!-- Display taxonomy terms -->  
			<div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">  
				<ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy?> categorychecklist form-no-clear">  
					<?php   foreach($terms as $term){  
						$id = $taxonomy.'-'.$term->term_id;  
						echo "<li id='$id'><label class='selectit'>";  
						echo "<input type='radio' id='in-$id' name='{$name}'".checked($current,$term->term_id,false)."value='$term->term_id' />$term->name<br />";  
					   echo "</label></li>";  
					}?>  
			   </ul>  
			</div>  
	  
			<!-- Display popular taxonomy terms -->  
			<div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">  
				<ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >  
					<?php   foreach($popular as $term){  
						$id = 'popular-'.$taxonomy.'-'.$term->term_id;  
						echo "<li id='$id'><label class='selectit'>";  
						echo "<input type='radio' id='in-$id'".checked($current,$term->term_id,false)."value='$term->term_id' />$term->name<br />";  
						echo "</label></li>";  
					}?>  
			   </ul>  
		   </div>  
		   <div id="category-adder" class="wp-hidden-children">
					<h4>
						<a id="category-add-toggle" href="#category-add" class="hide-if-no-js">
							+ Add New Category                    </a>
					</h4>
					<p id="category-add" class="category-add wp-hidden-child">
						<label class="screen-reader-text" for="newcategory">Add New Category</label>
						<input type="text" name="newcategory" id="newcategory" class="form-required form-input-tip" value="New Category Name" aria-required="true">
						<label class="screen-reader-text" for="newcategory_parent">
							Parent Category:                    </label>
						<select name="newcategory_parent" id="newcategory_parent" class="postform">
		<option value="-1">— Parent Category —</option>
		<option class="level-0" value="6">CROSSWORDS</option>
	</select>
						<input type="button" id="category-add-submit" data-wp-lists="add:categorychecklist:category-add" class="button category-add-submit" value="Add New Category">
						<input type="hidden" id="_ajax_nonce-add-category" name="_ajax_nonce-add-category" value="f4d2c2d1c9">                    <span id="category-ajax-response"></span>
					</p>
				</div>
		</div>  
		<?php  
	}
 
      /**
       * Gets the taxonomy object from the slug
       * @return object Taxonomy object
       */
      public function taxonomy() {
         $this->taxonomy = $this->taxonomy ? $this->taxonomy : get_taxonomy( $this->slug );
         return $this->taxonomy;
      }
 
      /**
       * Gets the taxonomy's associated post_types
       * @return array Taxonomy's associated post_types
       */
      public function post_types() {
         $this->post_types = !empty( $this->post_types ) ? $this->post_types : $this->taxonomy()->object_type;
         return $this->post_types;
      }
 
      /**
       * Gets the metabox title from the taxonomy object's labels (or uses the passed in title)
       * @return string Metabox title
       */
      public function metabox_title() {
         $this->metabox_title = !empty( $this->metabox_title ) ? $this->metabox_title : $this->taxonomy()->labels->name;
         return $this->metabox_title;
      }
 
 
   }
 


?>