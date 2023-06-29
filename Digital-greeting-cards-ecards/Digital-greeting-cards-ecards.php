<?php
/*
 * plugin for WordPress.
 *
 * Plugin Name: Digital Greeting Cards (Ecards)
 * Description: Digital Greeting Cards (Ecards) is for Create Ecard site with this plugin..
 * Version:     1.0.0
 * Author:      APSwebtech Team
 * Author URI:  https://apswebtech.com/
 * License:     GPL-2.0+
 * Copyright:   2022 Sep apswebtech.com
 * 
 * Text Domain: digital-greeting-cards-ecards
 * 
 */
if (!defined('ABSPATH')) {
    exit; //block direct access
}
define('APS_EC_FOLDER', plugin_basename(dirname(__FILE__)));
define('EC_DIR', WP_PLUGIN_DIR . '/' . APS_EC_FOLDER);
define('DS', DIRECTORY_SEPARATOR); 

require EC_DIR . DS.'vendor' . DS. 'autoload.php';
use Dompdf\Dompdf;

add_action('wp_head', 'ECPT_ajaxurl');

if (!function_exists('ECPT_ajaxurl')) {
function ECPT_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}
}

/*
REGISTER STYLES AND SCRIPTS
*/

include_once( EC_DIR . DS. 'include' . DS. "scripts_styles.php" );

/*
REGISTER ADMIN MENU AND PAGES
*/
include_once( EC_DIR . DS. 'adm' . DS. "menu.php" );
include_once( EC_DIR . DS. 'adm' . DS. 'pages' . DS. "ecards-list.php" );
include_once( EC_DIR . DS. 'adm' . DS. 'pages' . DS. "addcard.php" );
include_once( EC_DIR . DS. 'adm' . DS. 'pages' . DS. "categories.php" );
include_once( EC_DIR . DS. 'adm' . DS. 'pages' . DS. "entries.php" );

/*
INCLUDE PAGES
*/
include_once( EC_DIR . DS. 'include' . DS. "ecard-submit.php" );
include_once( EC_DIR . DS. 'include' . DS.  "functions.php" );

 

if (!function_exists('ECPTAPS_EC_activate')) {
function ECPTAPS_EC_activate() { 
  
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE `{$wpdb->base_prefix}ecards_entries` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `from_email` varchar(255) NOT NULL,
        `to_email` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `image` longtext NOT NULL,
        `message` text NOT NULL,
        `ecolor` varchar(50)  DEFAULT NULL,
        `ecard_type` varchar(50)  DEFAULT NULL,
        `created_at` datetime NOT NULL DEFAULT current_timestamp(),
        `modify_at` datetime NOT NULL DEFAULT current_timestamp(),
         PRIMARY KEY  (`id`)
      ) $charset_collate;";



    $sql .= "CREATE TABLE `{$wpdb->base_prefix}ecards` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL,
        `filtered` tinyint(4) NOT NULL,
        `image` longtext NOT NULL,
        `category` int(11) NOT NULL,
        `message` text DEFAULT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    
    $sql .= "CREATE TABLE `{$wpdb->base_prefix}ecards_categories` (
        id int NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        slug varchar(255) NOT NULL,
        filtered tinyint(4) NOT NULL,
        parent varchar(255) NOT NULL,  
        lavel int NOT NULL,
        description  text NOT NULL,
        PRIMARY KEY  (`id`)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    dbDelta($sql);
   
    set_transient( 'fx-admin-notice-ecards', true, 5 );  


}
}
register_activation_hook( __FILE__, 'ECPTAPS_EC_activate' );
add_action( 'admin_notices', 'ECPTAPS_ecards_admin_notice' );


if (!function_exists('ECPTAPS_ecards_admin_notice')) {
function ECPTAPS_ecards_admin_notice(){

    /* Check transient, if available display notice */
    if( get_transient( 'fx-admin-notice-ecards' ) ){
       ?>
        <div class="updated notice is-dismissible">
            <img style="padding: 25px; float:left;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIEAAACLCAMAAABx5QoEAAAA1VBMVEX/////TTkREiSmpqaXyB8AAADV1dX9kIb/RC1ra2v+Uj7+4d//STTgb2ajqKmenp7o6Ojf39/8tK7/PSP29vb9eWtjY2Ovr698fHyhzT/3+/AAABrt5uKSxgCdyzeRXTTV39/j78yOWCzDw8Pvi4H+8vFpaXL+WEb+ZlXF35Le68Lu9eCp0Va72X7o8talz0mWvCHm5NWTZze6n4z7b2Hrp6AAABI5OkaLi49LTFb9zMf9rabqxcH9o5v/NRXqlo7mrajazMqBQAArKzMiIjBXV112d3/UYgCwAAADpklEQVR4nO2bC1eiQBSAEYW03EXFFZUwzaJSyh4GTNvWtgL//yftgC8GRIYDzuxZ54OOwBm531yGO5wijmMwGAwGg8Fg/N+0222q4RtNUaRm0A7Ci8UaXL+1sPjZejtfRvd5T274dp1N4NeNnM7Hh3z7+f61iS8Orz6SG9/8yiIwl0sp1Kty6er313AoimGD6p5vyHN8gasUgbpcnX++D4dNEWWvQakkn+AKnNX3nadaPZ2/fomx8OkGpfoZpkErMQX16occyz1iUPdJTEIL0+AksSfL3Ccy/Ly59UlMH+5lSDQ4TcvidcDZKT2DJcyAGVAwGNE2GGlxBaIG00qlMqVqYGgVzaBpcKfBHGh39AwmvgBUmNAymA4qSwboUCBnYGgrg8hQIGZwvxaACvc0DCZbgchQIGQwraCEhgIhA0NDBLQZaYN7DbIJ77MdCmQMJqPR6GGyMpg8wL3tSCB3N/Z7K4NZHzlOzKDf29SDHqJAyqA/CNWDQViBkME2A7EskDEIZyCaBSIGUQFEgYQBegmiF4KAQTwDSBYOb9CfDXx6oei94MiqLhB8Ptj0ns7zQXgsoCWJVD1AJkfNIF8PorOzcXT1wNhRDwyS9WC2sx7MiNUDzhj01jw+Pm62Bwa5HGx4er58ftruEjPYcn5xeXEePcgMmMERGnAvf15ix47u94nHZlCa/8BhnvT1AgzqeBzQICfMIIuBjHm9s4L9J8/Xb4fiFdNg/P1QjHENyoeiYAMpDAUDqYaC5YBrIOKcrdtBBDpdHGsR06CN0x2p1gmDlwPs91SwkpAd7BRwB7obJNxRENBUpBTiAVJQmlkEOP8Np/0oXRQlpX3xL0spkXtBKTwCM2AGzOBfNGjH4ZTI7KzsbFUQ47ISp4zWZWlnm0wzQTKNHLNRoxADJYdBIaOjkedhQSoiCeMcAvhPhntops37KWR9KojTSH4hEotixiKDwWAwGAzGsbH7f2IIwgm04XjaxAxUuKw2yBlYwASCai4s0zSFrq13BEEVzK6gW0VFifYmvO8bqJ7jea6n+4te7gLFVvSyN7YdJ29k2CkYTFWBo6q8AzcgC2BZy9WynLWB6wtYC1BzbGdRUzzJGdeEhm7mFVCBZ+t+t4K+2Tz88Dygw3DwuOfa+mJlwFv6Ai6uC3TgeDbo6jaALey8AjAHwAWW61oAfgZbcNPSLeCvLgDAWhsEwy9IkcqbprpacqcgOLO6XINzBz98aJPnd9wLxKFv8BcaW9S/g4RJNQAAAABJRU5ErkJggg==" />
            <h2>Thank you for using <span style="color:blue">Free Ecard plugin</span>!</h2>
            <h2>What is Next to do?</h2>
            <ul>
              <li>1. Setup Cards Display Page by <a href="<?php echo admin_url("admin.php?page=ECPTAPS_pagecreate") ?>">Clicking here</a>. or Create Page Manualy using Plugin Shortcodes</li>
              <li>2. Setup Cards <a href="<?php echo admin_url("admin.php?page=Categories") ?>">Categories</a></li>
              <li>3. Create <a href="<?php echo admin_url("admin.php?page=Add+Card") ?>">E-Card</a></li>
              <li>4. Thats it Enjoy </li>
            </ul>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'fx-admin-notice-ecards' );
         
    }
}
}

/**
 * Deactivation hook.
 */
if (!function_exists('ECPTAPS_APSCL_deactivate')) {
function ECPTAPS_APSCL_deactivate() {
    
    global $wpdb;
    
 
    $sql = "DROP TABLE `{$wpdb->base_prefix}ecards`;";
    $wpdb->query($sql);
    $sql = " DROP TABLE `{$wpdb->base_prefix}ecards_entries`;";
    $wpdb->query($sql);
    $sql = " DROP TABLE `{$wpdb->base_prefix}ecards_categories`;";
    $wpdb->query($sql);
    

}
}
register_deactivation_hook(__FILE__, 'ECPTAPS_APSCL_deactivate');



/**
 * Short Codes.
 */
add_shortcode('ECARDS_VIEW', 'ECPTAPS_ecard_view_shortcode');   
 
if (!function_exists('ECPTAPS_ecard_view_shortcode')) {
function ECPTAPS_ecard_view_shortcode( $param = "") {
       include_once( EC_DIR . DS. 'template' . DS. "sidebar.php" );
        ob_start();
        if(isset($_GET['eCard'])){
             include_once( EC_DIR . DS. 'template' . DS. "ecards-single.php" );
         }
        else if(isset($_GET['cat'])){
            
            include_once( EC_DIR . DS. 'template' . DS. "ecards-list.php" );
        }
        else{
            
             include_once( EC_DIR . DS. 'template' . DS. "ecards-list.php" );
            }
            return ob_get_clean();
      }
}

if (!function_exists('ECPTAPS_install_demo')) {
function ECPTAPS_install_demo(){
    global $wpdb;
   
    include_once( EC_DIR . DS. 'include' . DS. "sampledata.php" );
    foreach($wp_ecards as $ecard){
        $wpdb->insert($wpdb->prefix.'ecards',$ecard);
    }
    foreach($wp_ecards_categories as $categories){
        $wpdb->insert($wpdb->prefix.'ecards_categories',$categories);
    }
   echo "<script>window.location.href= 'admin.php?page=all-ecards'</script>";
}
}
if (!function_exists('ECPTAPS_pagecreate')) {
function ECPTAPS_pagecreate(){
        $args = array(
        'post_type' => 'page',
        'post_name' => 'ecards-home',
        'post_status' => 'publish',
        'numberposts' => 1
        );
        $my_posts = get_posts($args);
        
        if( ! $my_posts ) :

            $args = array(
                'post_type' => 'page',
                'post_title' => 'Ecards Home',
                'post_name' => 'ecards-home',
                'post_content' => '[ECARDS_VIEW limit=6]',
                'post_status'   => 'publish'
            );
            
            wp_insert_post( $args );
        
        endif;

        if(isset($_GET['install']) && $_GET['install']=='demo'){
            ECPTAPS_install_demo(); 
        } 
        ?>
        <div class="col-wrap">


        <div class="ecard-form-wrap" style="">
        <img style="padding: 25px; float:left;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIEAAACLCAMAAABx5QoEAAAA1VBMVEX/////TTkREiSmpqaXyB8AAADV1dX9kIb/RC1ra2v+Uj7+4d//STTgb2ajqKmenp7o6Ojf39/8tK7/PSP29vb9eWtjY2Ovr698fHyhzT/3+/AAABrt5uKSxgCdyzeRXTTV39/j78yOWCzDw8Pvi4H+8vFpaXL+WEb+ZlXF35Le68Lu9eCp0Va72X7o8talz0mWvCHm5NWTZze6n4z7b2Hrp6AAABI5OkaLi49LTFb9zMf9rabqxcH9o5v/NRXqlo7mrajazMqBQAArKzMiIjBXV112d3/UYgCwAAADpklEQVR4nO2bC1eiQBSAEYW03EXFFZUwzaJSyh4GTNvWtgL//yftgC8GRIYDzuxZ54OOwBm531yGO5wijmMwGAwGg8Fg/N+0222q4RtNUaRm0A7Ci8UaXL+1sPjZejtfRvd5T274dp1N4NeNnM7Hh3z7+f61iS8Orz6SG9/8yiIwl0sp1Kty6er313AoimGD6p5vyHN8gasUgbpcnX++D4dNEWWvQakkn+AKnNX3nadaPZ2/fomx8OkGpfoZpkErMQX16occyz1iUPdJTEIL0+AksSfL3Ccy/Ly59UlMH+5lSDQ4TcvidcDZKT2DJcyAGVAwGNE2GGlxBaIG00qlMqVqYGgVzaBpcKfBHGh39AwmvgBUmNAymA4qSwboUCBnYGgrg8hQIGZwvxaACvc0DCZbgchQIGQwraCEhgIhA0NDBLQZaYN7DbIJ77MdCmQMJqPR6GGyMpg8wL3tSCB3N/Z7K4NZHzlOzKDf29SDHqJAyqA/CNWDQViBkME2A7EskDEIZyCaBSIGUQFEgYQBegmiF4KAQTwDSBYOb9CfDXx6oei94MiqLhB8Ptj0ns7zQXgsoCWJVD1AJkfNIF8PorOzcXT1wNhRDwyS9WC2sx7MiNUDzhj01jw+Pm62Bwa5HGx4er58ftruEjPYcn5xeXEePcgMmMERGnAvf15ix47u94nHZlCa/8BhnvT1AgzqeBzQICfMIIuBjHm9s4L9J8/Xb4fiFdNg/P1QjHENyoeiYAMpDAUDqYaC5YBrIOKcrdtBBDpdHGsR06CN0x2p1gmDlwPs91SwkpAd7BRwB7obJNxRENBUpBTiAVJQmlkEOP8Np/0oXRQlpX3xL0spkXtBKTwCM2AGzOBfNGjH4ZTI7KzsbFUQ47ISp4zWZWlnm0wzQTKNHLNRoxADJYdBIaOjkedhQSoiCeMcAvhPhntops37KWR9KojTSH4hEotixiKDwWAwGAzGsbH7f2IIwgm04XjaxAxUuKw2yBlYwASCai4s0zSFrq13BEEVzK6gW0VFifYmvO8bqJ7jea6n+4te7gLFVvSyN7YdJ29k2CkYTFWBo6q8AzcgC2BZy9WynLWB6wtYC1BzbGdRUzzJGdeEhm7mFVCBZ+t+t4K+2Tz88Dygw3DwuOfa+mJlwFv6Ai6uC3TgeDbo6jaALey8AjAHwAWW61oAfgZbcNPSLeCvLgDAWhsEwy9IkcqbprpacqcgOLO6XINzBz98aJPnd9wLxKFv8BcaW9S/g4RJNQAAAABJRU5ErkJggg==" />
        <div style="padding: 25px;">
        <h1>Page Created !</h1>
        <h2> You Want to Setup Demo Data Ecards?</h2>
        <a class="button button-primary" href="admin.php?page=ECPTAPS_pagecreate&install=demo">Yes Please</a>
        <a class="button button-info" href="admin.php?page=all-ecards">No I can Do it Myself</a>
        </div>
        </div>
        </div>
        <?php
        
        exit;
}
}
    
 



  
 
 
   
 

 