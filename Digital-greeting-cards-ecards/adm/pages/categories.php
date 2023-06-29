<?php
if (!defined('ABSPATH')) exit();
function categories()
{ $msg = "";


if(isset($_POST['action']) && $_POST['action'] == 'remove-tag'){
    c54s4e4d_del_row('#__ecards_categories',['id'=>$_POST['del_id']]);
    }
if(isset($_POST['action']) && $_POST['action'] == 'add-tag'){
  
    if($_POST['slug']){
        $slug = c54s4e4d_cardsDB_slugify(sanitize_text_field($_POST['slug']));
    }else{
        $slug = c54s4e4d_cardsDB_slugify(sanitize_text_field($_POST['tag-name']));
    }
    $label = 0;
    if($_POST['parent']!=0){
        $label = row("SELECT lavel FROM `wp_ecards_categories` Where id=".esc_html($_POST['parent']));
        $label = $label->lavel + 1;
        
    }

    $data = [
        'title'=>sanitize_text_field($_POST['tag-name']),
        'slug'=>$slug,
        'parent'=>esc_html($_POST['parent']),   
        'filtered'=>esc_html($_POST['filtered']),  
        'lavel' =>$label         
    ];

    if($_POST['edit'] == 0){
      
        c54s4e4d_insert('#__ecards_categories',$data);
         $msg = "Category Created Successfully ";
    }else{
        c54s4e4d_update('#__ecards_categories',$data,['id'=>$_POST['edit']]);
         $msg = "Category Updated Successfully ";
    }
    
    //
}
?>
    <div id="col-container" class="wp-clearfix">
    <?php if($msg): ?>
            <div id="message" class="updated notice is-dismissible"><p><?php echo $msg ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php echo __('Dismiss this notice.') ?></span>
            </button>
             </div>
         <?php endif ?>
        <div id="col-left">
            <div class="col-wrap">


                <div class="form-wrap">
                    <h2><?php echo __('Add New Category') ?></h2>
                    <form id="addtag" method="post" action="" class="validate">
                        <input type="hidden" name="action" value="add-tag">                        
                        <div class="form-field form-required term-name-wrap">
                            <label for="tag-name"><?php echo __('Name') ?></label>
                            <input required name="tag-name" id="tag-name" type="text" value="" size="40" aria-required="true" aria-describedby="name-description">
                             
                        </div>
                        <div class="form-field term-slug-wrap">
                            <label for="tag-slug"><?php echo __('Slug') ?></label>
                            <input name="slug" id="tag-slug" type="text" value="" size="40" aria-describedby="slug-description">                            
                        </div>
                        <div class="form-field term-parent-wrap">
                            <label for="parent"><?php echo __('Parent Category') ?></label>
                            <select name="parent" id="parent" class="postform">
                                <option value="0"><?php echo __('None') ?></option>
                                <?php 
                                 c54s4e4d_cardsDB_get_cat_tree_options(0);
                               ?>
                            </select>
                            
                        </div>
                        <div class="form-field term-slug-wrap">
                            <label for="tag-slug"><?php echo __('Filtered') ?></label><br>
                            <label><input type="radio" name="filtered" value="1" /><?php echo __('Yes') ?></label>
                            <label><input type="radio" name="filtered" value="0" /><?php echo __('No') ?></label>      
                                                                       
                        </div>
                         

                        <p class="submit">
                            <input type="hidden" id="edit" name="edit" value="0" />
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Add New Category') ?>"> <span class="spinner"></span>
                        </p>
                    </form>
                </div>
            </div>
        </div><!-- /col-left -->

        <div id="col-right">
            <div class="col-wrap">

                <ul class="cat_tree">
                    <?php c54s4e4d_cardsDB_get_cat_tree() ?>
                </ul>
                <form id="deletecard" method="post" action="" class="validate">
                <input type="hidden" name="action" value="remove-tag">  
                <input type="hidden" name="del_id" id="del_id">
                </form>
 
            </div>
        </div><!-- /col-right -->

    </div>
    

<?php

}
?>