<?php
// Register style sheet.
add_action( 'admin_enqueue_scripts', 'APS_CPB_admin_plugin_styles',18 );
add_action( 'wp_enqueue_scripts', 'APS_CPB_plugin_styles' ,18 );



 function APS_CPB_admin_plugin_styles() {

    $t = time();
    wp_register_style( 'style', plugins_url(APS_EC_FOLDER.'/css/style.css' ) );
	wp_enqueue_style( 'style' );

    wp_register_script( 'apscpb_script', plugins_url(APS_EC_FOLDER.'/js/script.js' ), '1.0',  1 );
     wp_enqueue_script( 'apscpb_script' );;
        
 }
 
function APS_CPB_plugin_styles() {
    $t = time();
	wp_register_style( 'style', plugins_url(APS_EC_FOLDER.'/css/ecards.css') );
	wp_enqueue_style( 'style' );
    
    wp_register_style( 'Girds', plugins_url(APS_EC_FOLDER.'/css/Girds.css' ) );
	wp_enqueue_style( 'Girds' );

    wp_enqueue_script( 'jquery' );

    wp_register_script( 'ecardsjs', plugins_url(APS_EC_FOLDER.'/js/ecards.js'), '1.0',  1 );
    wp_enqueue_script( 'ecardsjs' );;
    
}

 