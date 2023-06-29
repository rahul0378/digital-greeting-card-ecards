<?php
if (!defined('ABSPATH')) {
	exit; //block direct access
}
add_action('admin_menu', 'APS_EC__adm_menu');



function APS_EC__adm_menu()
{
	add_menu_page('E-Cards', __('All Cards'), 'manage_options', 'all-ecards', 'all_ecards', plugins_url('Ecards/img/icon.png'), 2);
	add_submenu_page('all-ecards', __('Add Card'), __('Add Card'),  'manage_options',__('Add Card'), 'AddCard');
	add_submenu_page('all-ecards', __('Categories'), __('Categories'),  'manage_options', __('Categories'), 'categories');
	add_submenu_page('all-ecards', __('Entries'), __('Entries'),  'manage_options', __('Entries'), 'Entries');
	add_submenu_page('all-ecards', __('Ecard Settings'), __('Ecard Settings'),  'manage_options', __('Settings'), 'Ecard_Settings');
	add_submenu_page('', 'ECPTAPS_pagecreate', 'ECPTAPS_pagecreate',  'manage_options', 'ECPTAPS_pagecreate', 'ECPTAPS_pagecreate');
}

// settings
function Ecard_Settings()
{
	$msg = "";
	if(isset($_POST['action'])){
        if($_FILES['cardimage']['tmp_name']){
			$image = $_FILES['cardimage']['tmp_name'];
			$type = pathinfo($_FILES['cardimage']['name'], PATHINFO_EXTENSION);
			$data = file_get_contents($image);
			 $dataUri = 'data:image/' . $type . ';base64,' . base64_encode($data);
			 update_option("ecard_banner_img",$dataUri);
		}
		if(isset($_POST['ecard_banner_title'])){
			update_option("ecard_banner_title",esc_html($_POST['ecard_banner_title']));
		}
		if(isset($_POST['ecard_banner_slogan'])){
			update_option("ecard_banner_slogan",$_POST['ecard_banner_slogan']);
		}
		if(isset($_POST['ecards_top_content_title'])){
			update_option("ecards_top_content_title",esc_html($_POST['ecards_top_content_title']));
		}
		if(isset($_POST['ecards_top_content'])){
			update_option("ecards_top_content",$_POST['ecards_top_content']);
		}
	}
 
?>
	<div id="col-container" style="background-color:#f0f0f1;" class="wp-clearfix">
		<?php if ($msg) : ?>
			<div id="message" class="updated notice is-dismissible">
				<p><?php echo esc_html($msg) ?></p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"><?php __('Dismiss this notice') ?>.</span>
				</button>
			</div>
		<?php endif ?>
		<div class="col-wrap">

		
			<div class="ecard-form-wrap">
				<h2><?php echo __('Ecards Plugin Settings') ?></h2>		
				   <form  method="post" action="" enctype="multipart/form-data" class="validate">		
					<input type="hidden" name="action" value="add-settins">
					<div style="float:left; padding:15px" class="form-field term-slug-wrap">
						<label for="tag-slug"><?php echo __('Banner Image') ?></label><br>
                        <input onchange="loadFile(event)" accept="image/*" type='file' id="imgInp" name="cardimage" required />
						<br>
						<br>
					  <p class="submit">						 
						<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Update') ?>"> <span class="spinner"></span>
					  </p>
					</div>
				  			
				   <div >
				   <?php __('Seleted Image') ?><br />
					<?php if (get_option("ecard_banner_img")) : ?>
						<img style="width:200px" id="priview-img" src="<?php echo esc_html(get_option("ecard_banner_img")) ?>" alt="<?php __('Seleted Image') ?>" />
					<?php else : ?>
						<img style="width:200px" id="priview-img" src="<?php echo esc_html(plugins_url(APS_EC_FOLDER . '/img/imageupload.png')) ?>" alt="<?php __('Seleted Image') ?>" />
					<?php endif ?>
					</form>	
				</div>
				<div style="clear: both;"></div>
			</div>
			<div class="ecard-form-wrap">
				<h2><?php __('Banner Text') ?></h2>	
				<form  method="post" action="" enctype="multipart/form-data" class="validate">			
					<input type="hidden" name="action" value="add-settins">
					<div class="form-field term-slug-wrap">
						<label for="tag-slug"><?php echo __('Page Title') ?></label><br>
                        <input  type='text' id="imgInp" name="ecard_banner_title" value="<?php echo get_option("ecard_banner_title") ?>"  />
				   </div>
				   <div class="form-field term-slug-wrap">
						<label for="tag-slug"><?php echo  __('Page slogan') ?></label><br>
						<?php  
						 $options['editor_height'] = 50;
						 $options['media_buttons'] = false;
						 $options['fonts'] = true;
						 $options['quicktags'] = false;
						 $options['tinymce'] = array(
							'toolbar1' => 'bold, italic, underline,|,fontsizeselect,|,forecolor,backcolor,fontselect',
							'toolbar2' => false
							 
						  );
						 wp_editor(get_option("ecard_banner_slogan"), 'ecard_banner_slogan', $options); ?>
                         
				   </div>
				   <br>
					<p class="submit">
						 
						<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo  __('Update') ?>"> <span class="spinner"></span>
					</p>			
				</form>  
				<div style="clear: both;"></div>
			</div>
		
			<div class="ecard-form-wrap">
				<h2><?php echo  __('Ecard listing Top Content') ?></h2>	
				<form  method="post" action="" enctype="multipart/form-data" class="validate">			
					<input type="hidden" name="action" value="add-settins">
					<div class="form-field term-slug-wrap">
						<label for="tag-slug"><?php echo  __('Title') ?></label><br>
                        <input  type='text' id="imgInp" name="ecards_top_content_title" value="<?php echo get_option("ecards_top_content_title") ?>"  />
				   </div>
				   <div class="form-field term-slug-wrap">
						<label for="tag-slug"><?php echo  __('Content') ?></label><br>
						<?php  
						 $options['editor_height'] = 50;
						 $options['media_buttons'] = false;
						 $options['fonts'] = true;
						 $options['quicktags'] = false;
						 $options['tinymce'] = array(
							'toolbar1' => 'bold, italic, underline,|,fontsizeselect,|,forecolor,backcolor,fontselect',
							'toolbar2' => false
							 
						  );
						 wp_editor(get_option("ecards_top_content"), 'ecards_top_content', $options); ?>
                         
				   </div>
				   <br>
					<p class="submit">						 
						<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Update') ?>"> <span class="spinner"></span>
					</p>			
				</form>  
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
<?php
}
