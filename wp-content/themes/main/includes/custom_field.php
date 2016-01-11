<?php
/**
 * Registering meta boxes
 *
 * In this file, I'll show you how to extend the class to add more field type (in this case, the 'taxonomy' type)
 * All the definitions of meta boxes are listed below with comments, please read them carefully.
 * Note that each validation method of the Validation Class MUST return value instead of boolean as before
 *
 * You also should read the changelog to know what has been changed
 *
 * For more information, please visit: http://www.deluxeblogtips.com/2010/04/how-to-create-meta-box-wordpress-post.html
 *
 */

/********************* BEGIN EXTENDING CLASS ***********************/

/**
 * Extend RW_Meta_Box class
 * Add field type: 'taxonomy'
 */


class RW_Meta_Box_Taxonomy extends RW_Meta_Box {
	
	function add_missed_values() {
		parent::add_missed_values();
		
		// add 'multiple' option to taxonomy field with checkbox_list type
		foreach ($this->_meta_box['fields'] as $key => $field) {
			if ('taxonomy' == $field['type'] && 'checkbox_list' == $field['options']['type']) {
				$this->_meta_box['fields'][$key]['multiple'] = true;
			}
		}
	}
	
	// show taxonomy list
	function show_field_taxonomy($field, $meta) {
		global $post;
		
		if (!is_array($meta)) $meta = (array) $meta;
		
		$this->show_field_begin($field, $meta);
		
		$options = $field['options'];
		$terms = get_terms($options['taxonomy'], $options['args']);
		
		// checkbox_list
		if ('checkbox_list' == $options['type']) {
			foreach ($terms as $term) {
				echo "<input type='checkbox' name='{$field['id']}[]' value='$term->slug'" . checked(in_array($term->slug, $meta), true, false) . " /> $term->name<br/>";
			}
		}
		// select
		else {
			echo "<select name='{$field['id']}" . ($field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'") . ">";
		
			foreach ($terms as $term) {
				echo "<option value='$term->slug'" . selected(in_array($term->slug, $meta), true, false) . ">$term->name</option>";
			}
			echo "</select>";
		}
		
		$this->show_field_end($field, $meta);
	}
}

/********************* END EXTENDING CLASS ***********************/

/********************* BEGIN DEFINITION OF META BOXES ***********************/

// prefix of meta keys, optional
// use underscore (_) at the beginning to make keys hidden, for example $prefix = '_rw_';
// you also can make prefix empty to disable it
$prefix = 'news_';

$meta_boxes = array();
/*
// second meta box
$meta_boxes[] = array(
	'id' => 'additional',
	'title' => 'Information',
	'pages' => array('post','sanpham','news','page','solve-puzzles'),
    'context' => 'normal',
    'priority' => 'high',
	'fields' => array(
		/*array(
			'name' => 'mau sac sản phẩm',					// field name
			'desc' => 'Nhập mục này sẽ hiển thị trong trang chi tiết sản phẩm',	// field description, optional
			'id' => 'color',				// field id, i.e. the meta key
			'type' => 'color'						// text box
		),
		/*array(
			'name' => 'Sản phẩm Big Sale',					// field name
			'desc' => 'Chọn mục này sẽ hiểu thị khu vực sản phẩm bán chạy',	// field description, optional
			'id' => 'bigsale',				// field id, i.e. the meta key
			'type' => 'checkbox'						// text box
		),
		array(
			'name' => 'Sản phẩm Safe Off',					// field name
			'desc' => 'Sản phẩm hiển thị trong mục Sale off',	// field description, optional
			'id' => 'saleoff',				// field id, i.e. the meta key
			'type' => 'checkbox'						// text box
		),*/
	
      /*  array(
			'name' => 'URL link',					// field name
			'desc' => '',	// field description, optional
			'id' => 'image',				// field id, i.e. the meta key
			'type' => 'text'						// text box

		),
        array(
			'name' => 'URL link',
			'desc' => 'Upload File',
			'id' => 'image',
			'type' => 'image'						// image upload
		)

	)
);

//meta for post

// second meta box
$meta_boxes[] = array(
	'id' => 'seo_meta',
	'title' => 'Thông tin SEO',
	'priority' => 'low',
	'pages' => array('post','sanpham','news','page'),

	'fields' => array( 
        array(
			'name' => 'Description',					// field name
			'desc' => 'Phần này sẽ nằm trong thẻ meta description',	// field description, optional
			'id' => 'description',				// field id, i.e. the meta key
			'type' => 'textarea'						// text box

		),
        array(
			'name' => 'Keywords',					// field name
			'desc' => 'Phần này sẽ nằm trong thẻ keywords',	// field description, optional
			'id' =>  'keywords',				// field id, i.e. the meta key
			'type' => 'textarea'						// text box

		)
	)
);
*/

foreach ($meta_boxes as $meta_box) {
	$my_box = new RW_Meta_Box_Taxonomy($meta_box);
}

/********************* END DEFINITION OF META BOXES ***********************/

/********************* BEGIN VALIDATION ***********************/

/**
 * Validation class
 * Define ALL validation methods inside this class
 * Use the names of these methods in the definition of meta boxes (key 'validate_func' of each field)
 */


/********************* END VALIDATION ***********************/



?>
