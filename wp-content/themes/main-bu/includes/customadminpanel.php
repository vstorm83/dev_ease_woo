<?php //remove comment on screen
function edit_admin_menus() {  
    global $menu;  
    global $submenu;  
    $menu[5][0] = 'Recipes'; // Change Posts to Recipes  
    $submenu['edit.php'][5][0] = 'All Recipes';  
    $submenu['edit.php'][10][0] = 'Add a Recipe';  
    $submenu['edit.php'][15][0] = 'Meal Types'; // Rename categories to meal types  
    $submenu['edit.php'][16][0] = 'Ingredients'; // Rename tags to ingredients  
    remove_menu_page('tools.php');
    remove_menu_page('edit.php'); 
    remove_menu_page('edit-comments.php'); // Remove the Tools Menu  
}
add_action( 'admin_menu', 'edit_admin_menus' ); 
?>