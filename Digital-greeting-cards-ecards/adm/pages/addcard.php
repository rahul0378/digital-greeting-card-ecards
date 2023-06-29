<?php
if (!defined('ABSPATH')) exit();

if ( ! function_exists( 'wp_handle_upload' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

function AddCard()
{


    $msg = "";
if(isset($_POST['action']) && $_POST['action'] == 'remove-tag'){
    c54s4e4d_del_row('#__ecards',['id'=>$_POST['del_id']]);
    }
if(isset($_POST['action']) && $_POST['action'] == 'add-ecard'){
    
    if($_POST['slug']){
        $slug = c54s4e4d_cardsDB_slugify(sanitize_text_field($_POST['slug']));
    }else{
        $slug = c54s4e4d_cardsDB_slugify(sanitize_text_field($_POST['tag-name']));
    }
    
   if($_FILES['cardimage']['tmp_name']){
 
    $image = $_FILES['cardimage']['tmp_name'];
    $type = pathinfo($_FILES['cardimage']['name'], PATHINFO_EXTENSION);
    $data = file_get_contents($image);
    $dataUri = 'data:image/' . $type . ';base64,' . base64_encode($data);
 
        $data = [
            'title'=>sanitize_text_field($_POST['tag-name']),
            'slug'=>$slug,
            'image'=>$dataUri,   
            'category'=>$_POST['category'],
            'filtered'=>$_POST['filtered'],  
        ];
   

   }else{
    $data = [
        'title'=>sanitize_text_field($_POST['tag-name']),
        'slug'=>$slug,
        'category'=>$_POST['category'],
        'filtered'=>$_POST['filtered'],  
    ];
   }
 
   global $wpdb;

    if($_POST['edit'] == 0){    
         $wpdb->insert($wpdb->prefix.'ecards',$data);
         $msg = "Ecard Created Successfully - <a target='_blank' href='".esc_url(home_url()."/ecards-home/?eCard=".$wpdb->insert_id)."'>View</a> ";
    }else{
        c54s4e4d_update('#__ecards',$data,['id'=>$_POST['edit']]);
         $msg = "Ecard Updated Successfully - <a target='_blank' href='".esc_url(home_url()."/ecards-home/?eCard=".$_POST['edit'])."'>View</a> ";
    }
    
 
}

if(isset($_GET['ID'])){
    $data = c54s4e4d_row("select * FROM #__ecards where id=".   esc_html($_GET['ID']));
   
    $name = $data->title;
    $slug = $data->slug;
    $image = $data->image;
    $category = $data->category;
    $filtered = $data->filtered;
 }else{
    $name = '';
    $slug = '';
    $image = '';
    $category = 0;
    $filtered = 0;
 }

?>
    <div id="col-container" style="background-color:#f0f0f1;" class="wp-clearfix">

         <?php if($msg): ?>
            <div id="message" class="updated notice is-dismissible"><p><?php echo ($msg) ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php echo __('Dismiss this notice.') ?></span>
            </button>
             </div>
         <?php endif ?>
            <div class="col-wrap">


                <div class="ecard-form-wrap">
                    <h2><?php echo __('Add New Ecard') ?></h2>
                    <form id="add-ecard" method="post" action="" enctype="multipart/form-data" class="validate">
                        <input type="hidden" name="action" value="add-ecard">                        
                        <div class="form-field form-required term-name-wrap">
                            <label for="tag-name"><?php echo __('E-card Title') ?></label><br>
                            <input name="tag-name" id="tag-name" type="text" value="<?php echo esc_html($name) ?>"    required >
                             
                        </div>
                        <div class="form-field term-slug-wrap">
                            <label for="tag-slug"><?php echo __('Slug') ?></label><br>
                            <input name="slug" id="tag-slug" type="text" value="<?php echo esc_html($slug) ?>">                            
                        </div>
                        <div class="form-field term-slug-wrap">
                            <label for="tag-slug"><?php echo __('Card Image') ?></label><br>
                           <p style="color:blue"><?php echo __('Use 850X680 Image for Best Resutls') ?></p>
                            <?php if(isset($_GET['ID'])): ?>
                                <input onchange="loadFile(event)" accept="image/*" type='file' id="imgInp" name="cardimage" />
                            <?php else: ?>
                                <input onchange="loadFile(event)" accept="image/*" type='file' id="imgInp" name="cardimage" required />
                            <?php endif ?> 
                                                                         
                        </div>
                        
                        <div class="form-field term-slug-wrap">
                            <label for="tag-slug"><?php echo __('Filtered') ?></label><br>
                            <label><input type="radio" name="filtered" <?php if($filtered): ?> checked <?php endif ?> value="1" /><?php echo __('Yes') ?></label>
                            <label><input type="radio" name="filtered" <?php if(!$filtered): ?> checked <?php endif ?> value="0" /><?php echo __('No') ?></label>      
                                                                         
                        </div>
                        

                        <div class="form-field term-parent-wrap">
                            <label for="category"><?php echo __('Category') ?></label><br>
                             
                            <select required name="category" id="category" class="postform">
                                <option value=""><?php echo __('None') ?></option>
                                <?php 
                                
                                c54s4e4d_cardsDB_get_cat_tree_options($category);
                               ?>
                            </select>
                            
                        </div>
                         
                        <br>
                        <p class="submit">
                            <?php if(isset($_GET['ID'])): ?>
                            <input type="hidden" id="edit" name="edit" value="<?php echo esc_html($_GET['ID']) ?>" />
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Update Ecard') ?>"> <span class="spinner"></span>
                            <?php else: ?>
                            <input type="hidden" id="edit" name="edit" value="0" />
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Add New Ecard') ?>"> <span class="spinner"></span>
                            <?php endif ?>
                            
                        </p>
                    </form>
                    <div>
                    <?php if(isset($_GET['ID'])): ?>
                        <img style="width:200px" id="priview-img" src="<?php echo  esc_html($image) ?>" alt="Seleted image" /> 
                            <?php else: ?>
                                <img style="width:200px" id="priview-img" src="<?php echo  plugins_url(APS_EC_FOLDER.'/img/imageupload.png' ) ?>" alt="<?php echo __('Seleted image') ?>" /> 
                            <?php endif ?>
                    
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        
 

    </div>
    

<?php

}
?>