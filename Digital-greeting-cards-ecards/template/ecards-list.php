<?php
global $wpdb;
if(isset($_GET['cat'])){
  $cats =  $wpdb->get_results("SELECT *  FROM `" . $wpdb->prefix . "ecards_categories` Where id = ".esc_html($_GET['cat']));
}
else{
  $cats =  $wpdb->get_results("SELECT *  FROM `" . $wpdb->prefix . "ecards_categories` where parent!=0");
}
?>
<div class="section group ecards-panel">	 
       <?php 
       echo "<div class='banner' style='background-image: url(".get_option("ecard_banner_img").");'>
        <h1 class='banner-head'>".__(get_option("ecard_banner_title")) ."</h1>
        <p>".nl2br(stripslashes(__(get_option("ecard_banner_slogan")))) ."</p>
        </div>";
        ?>    
</div>  
<div class="section group ecards-panel">  
  <div class="col span_2_of_10">
    <div class="Ecard-Sidebar" >
      <?php Ecard_sidebar(); ?>
    </div>
  </div>
  <div class="col span_8_of_10">
      <h2><?php echo __(get_option("ecards_top_content_title")) ?></h2>
      <p><?php echo __(get_option("ecards_top_content")) ?></p>
      <?php
       $fcats =  $wpdb->get_results("SELECT *  FROM `" . $wpdb->prefix . "ecards_categories` where filtered=1");
       foreach($fcats as $F):
       ?>
       <div class="col span_4_of_12">
        <a href="?cat=<?php echo $F->id ?>" class="ecard-button"><?php echo __($F->title) ?></a>
      </div> 
      <?php endforeach ?> 
      
      <h2 style="text-align:center; margin-top:15px;"><?php echo __('ECARDS PICKED JUST FOR YOU') ?></h2>
      <div class='ecard-main-countner'>
      <?php    
        if(!isset($_GET['q'])):
        foreach ($cats as $cat):
            if(c54s4e4d_cardsDB_get_child_count($cat->id)):
              ?>
               <div class='sections sid_<?php echo $cat->id  ?>'>
                <h2 class='list-title'><?php echo __($cat->title) ?></h2>
                 <?php if(isset($_GET['cat'])): 
                   $cards =  $wpdb->get_results("SELECT *  FROM `" . $wpdb->prefix . "ecards` Where category = " . $cat->id." ORDER BY `id`");
                  ?>
                  <a href='<?php echo $_SERVER['HTTP_REFERER'] ?>' style='float:right' class='view-all-link'><?php echo __('Back') ?></a>
                <?php else:
                  $cards =  $wpdb->get_results("SELECT *  FROM `" . $wpdb->prefix . "ecards` Where category = " . $cat->id." ORDER BY `id` DESC limit 6");
                   ?>
                  <a href='?cat=<?php echo $cat->id ?>' style='float:right' class='view-all-link'><?php echo __('View All') ?></a>
                <?php endif; ?>  
            <?php  foreach ($cards as $card): ?>
              <div class="col span_4_of_12 eCard">
              <a class="" href="?eCard=<?php echo $card->id ?>" >
              <img  style="" src="<?php echo $card->image ?>">               
              </a>
              <div class="ecard-card-title"><?php echo __($card->title) ?></div>
              </div> 
               
            <?php endforeach ?>
             <div class="clear"></div>
            <?php endif; 
        endforeach;
       else:
        $cards =  $wpdb->get_results("SELECT *  FROM `" . $wpdb->prefix . "ecards` Where `title` LIKE '%" .esc_html($_GET['q'])."%' ORDER BY `id` DESC");
        ?>
         <?php  foreach ($cards as $card): ?>
              <div class="col span_3_of_10 eCard">
              <a class="" href="?eCard='<?php echo $card->id ?>" >
              <img  style="min-height: 120px;  height:auto;     margin-top: 25px;" src="<?php echo $card->image ?>">

                  <div class="ecard-card-title"><?php echo __($card->title) ?></div>
              </a>
              </div> 

            <?php endforeach ?>
        <?php
        endif;    
            ?>

      </div>  
  </div>    
</div>  

 
<script>   
   jQuery(".sidebar-link").click(function(){
    var id = jQuery(this).attr('pid');
    if(id!=0){
      jQuery(".sections").hide()
    jQuery(".sid_"+id).show()
    }else{
      jQuery(".sections").show()
    }
    
   })
</script> 