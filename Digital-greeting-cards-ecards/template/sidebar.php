<?php
function Ecard_sidebar() {
    
    
     $html ="";
    if(!isset($_GET['q'])) :
    $html .="<ul class=''><li><a pid='0' href='#' class='sidebar-link' >".__('All')."</a></li>";
    $cats = get_trums();
    if($cats)
    foreach($cats as $cat){
    if($cat->id>1):
        $html .= "<li class='parent'>".__($cat->title);
      
        if(isset($cat->childs)):   
            $html .="<ul class='childs'>";         
            foreach($cat->childs as $child){
                $html .= "<li class='child'><a pid='".$child->id."' href='#' class='sidebar-link' >".__($child->title)." (".c54s4e4d_cardsDB_get_child_count($child->id).")</a></li>";
            }  
            $html .="</ul>";        
        endif;
        $html .= "</li>";
    endif;
    }
   echo  $html .="</ul>";
endif;
}
 
function get_trums(){
    global $wpdb;
    $cats =  $wpdb->get_results("
    SELECT * 
    FROM `".$wpdb->prefix."ecards_categories` 
    ");
    
    return c54s4e4d_cardsDB_buildTree($cats);
}

 