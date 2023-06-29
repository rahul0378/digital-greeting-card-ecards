<?php
if (!function_exists('c54s4e4d_rows')) {
function c54s4e4d_rows($q) {
     global $wpdb;
     $q =str_replace("#__",$wpdb->prefix,$q);
    return $wpdb->get_results($q);
    }
}
if (!function_exists('c54s4e4d_row')) {
function c54s4e4d_row($q)
    {
        global $wpdb;
         $q =str_replace("#__",$wpdb->prefix,$q);
    return $wpdb->get_row($q);
    }
}
  
if (!function_exists('c54s4e4d_insert')) {
function c54s4e4d_insert($table,$data){
    global $wpdb;
    $table =str_replace("#__",$wpdb->prefix,$table);       
    $wpdb->insert($table,$data);
    return  $wpdb->insert_id;
}
}
if (!function_exists('c54s4e4d_update')) {
function c54s4e4d_update($table, $data, $where){
    global $wpdb;
    $table =str_replace("#__",$wpdb->prefix,$table);       
    $wpdb->update( $table, $data, $where);
}
}
if (!function_exists('c54s4e4d_del_row')) {
function c54s4e4d_del_row($table, $where){
    global $wpdb;
    $table =str_replace("#__",$wpdb->prefix,$table);   
    if($wpdb->delete( $table,$where ))
    {
        return true;
    }else{
        return false;
    }
    
}
}
if (!function_exists('c54s4e4d_cardsDB_slugify')) {
function c54s4e4d_cardsDB_slugify($text, string $divider = '-')
{
  // replace non letter or digits by divider
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, $divider);

  // remove duplicate divider
  $text = preg_replace('~-+~', $divider, $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}
}
if (!function_exists('c54s4e4d_cardsDB_get_cat_tree')) {
function c54s4e4d_cardsDB_get_cat_tree(){
    $cats =  c54s4e4d_rows("SELECT * FROM `#__ecards_categories`"); 
    $items = c54s4e4d_cardsDB_buildTree($cats);
    c54s4e4d_cardsDB_print_li( $items);
}
}
if (!function_exists('c54s4e4d_cardsDB_print_li')) {
function c54s4e4d_cardsDB_print_li($items, $html=''){
    if($items)
    foreach($items as $item){
        $labels="";
        for($a=1;$a<=$item->lavel;$a++){ $labels .= "-";}
        $ml = $item->lavel * 15;
        echo '<li style="margin-left:'.$ml.'px" value="'.$item->id.'">'.$labels.__($item->title);
        if($item->id>1):
        echo '<a onclick="delele_card(\''.$item->id.'\')" class="del-icon"><span class="dashicons dashicons-trash"></span></a><a onclick="setedit(\''.$item->id.'\',\''.$item->title.'\',\''.$item->slug.'\',\''.$item->parent.'\',\''.$item->filtered.'\')" class="edit-icon"><span class="dashicons dashicons-edit"></span></a>';
        endif;
        echo '</li>';
        if(isset($item->childs)){             
            echo '<ul class="childs" >';          
            c54s4e4d_cardsDB_print_li($item->childs,$html);
            echo '</ul>';
        }
       
    }
  
}
}
if (!function_exists('c54s4e4d_cardsDB_get_cat_tree_options')) {
function c54s4e4d_cardsDB_get_cat_tree_options($selected){
    $cats =  c54s4e4d_rows("SELECT * FROM `#__ecards_categories`"); 
    $items = c54s4e4d_cardsDB_buildTree($cats);
    c54s4e4d_cardsDB_print_options( $items , $selected);
}
}

if (!function_exists('c54s4e4d_cardsDB_print_options')) {
function c54s4e4d_cardsDB_print_options($items, $selected, $html=''){
 
    foreach($items as $item){
        $labels="";
        for($a=1;$a<=$item->lavel;$a++){ $labels .= "-";}

        if($selected==$item->id){
            echo '<option selected value="'.$item->id.'">'.$labels.__($item->title).'</option>';
        }else{
            if($item->parent==0){
                echo '<option  value="'.$item->id.'">'.$labels.__($item->title).'</option>';
            }else{
                echo '<option value="'.$item->id.'">'.$labels.__($item->title).'</option>';
            }
            
        }
        
        if(isset($item->childs)){            
            c54s4e4d_cardsDB_print_options($item->childs,$selected,$html);
        }
    }
  
}
}

if (!function_exists('c54s4e4d_cardsDB_buildTree')) {
function c54s4e4d_cardsDB_buildTree($items) {

    $childs = array();

    foreach($items as $item)
        $childs[$item->parent][] = $item;

    foreach($items as $item) if (isset($childs[$item->id]))
        $item->childs = $childs[$item->id];

    if(isset($childs[0]))
    return $childs[0];
}
}
if (!function_exists('c54s4e4d_cardsDB_get_child_count')) {
function c54s4e4d_cardsDB_get_child_count($id){
    global $wpdb;
    $cats =  $wpdb->get_results("
    SELECT count(*) as total 
    FROM `".$wpdb->prefix."ecards` where category=".$id);
    return  $cats[0]->total;
}
}