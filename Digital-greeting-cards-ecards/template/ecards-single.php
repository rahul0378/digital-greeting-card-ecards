<?php if (!defined("ABSPATH")) exit(); ?>

<script>
  var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
</script>
<?php
global $wpdb;
$data = c54s4e4d_row("SELECT * FROM  `" . $wpdb->prefix . "ecards` Where id=" .esc_html($_GET['eCard']) );

if (!$data->title) :
?>
  <span>
    <center>Sorry No post found.</center>
  </span>
<?php
else :
?>
  <?php if (!isset($_GET['Personalize'])) : ?>
    <div style="width:70%; margin:0 auto;" class="section group ecards-panel">
      <div style="text-align:center" class="col span_5_of_10">
        <img style="width:50%; -webkit-box-shadow: 0 -10px 5px 5px #BABABA;box-shadow: 0 -10px 5px 5px #BABABA;" src='<?php echo $data->image ?>' />
      </div>
      <div style="text-align:center" class="col span_5_of_10">
        <form action="">
          <h2><?php echo __($data->title) ?></h2>
          <button style="width: 50%; margin:0 auto;" class="ecard-button"><?php echo __('Personalize and Send') ?></button>
          <input type="hidden" name="eCard" value="<?php echo esc_html($_GET['eCard']) ?>" />
          <input type="hidden" name="Personalize" value="1" />

        </form>
        <div class="clear"></div>
      </div>
    </div>

  <?php elseif (!isset($_POST['action'])) : ?>


    <div id="editor" style="display:block; max-width: 85% !important; ">

      <div style="position: relative;" class="section group ecards-panel">

     

        <div style="text-align:right" class="col span_4_of_10">
          <h2 style="text-align:left; width:100%; font-size:0.738rem"><?php echo __('PERSONALIZE YOUR MESSAGE (OPTIONAL)') ?></h2>
          <img style="width:100%; margin-top:15px; padding:20px;" src='<?php echo $data->image ?>' />
          <h2 style="text-align:center;"><?php echo $data->title ?></h2>
        </div>
        <div style="text-align:center" class="col span_6_of_10">
          <h2 style="float:right; text-align:right; font-size:0.738rem"><?php echo __('Step') ?> <span>2/3</span></h2>
          <form id="csend" method="post">
            <div style="display:inline-block;  width: 100%;" class="ecard-frm">
              <div class="step2">
                <span id="ecard-send-loader" style="display:none;"></span>
                <div class="ecard-form-field" style="text-align:center">
                  <input type="hidden" id="ecard-id" name="ecard-id" value="<?php echo $data->id ?>">
                  <input type="hidden" id="page-id" name="page-id" value="<?php echo $data->id ?>">
                  <input type="hidden" id="image" name="image" value="<?php echo $data->image ?>">
                  <input type="hidden" name="action" value="shareandsend">
                </div>

                <!--<div class="ecard-form-userinfo-wr clearfix">
                <div class="ecard-form step2" style="display: block; width:100%">
                  <div style="display: block; width:100%">Design Type:</div>
                  <label><input class="ecard-input-radio" checked type="radio" name="ecardtype" value="landscape">Landscape</label>
                  <label><input class="ecard-input-radio" type="radio" name="ecardtype" value="potrait">Portrait </label>
                </div>         
                </div>-->
                <div style="width: 100%; margin-top: 18px; display:inline-block">

                  <?php
                  $options['editor_height'] = 200;
                  $options['width'] = 800;
                  $options['media_buttons'] = false;
                  $options['fonts'] = true;
                  $options['quicktags'] = false;
                  $options['tinymce'] = array(
                    'toolbar1' => 'bold, italic, underline,|,fontsizeselect,|,forecolor,fontselect',
                    'toolbar2' => false,
                    'font_formats' => 'Arial=arial;Courier=courier;Georgia=georgia;Impact=impact;',
                    'keyup' => 'thiskeyup',
                    'statusbar' => false
                  );
                  wp_editor('', 'ecard-msg', $options);
                  ?>
                </div>


                


                <div style="clear:both"></div>
                <div class="ecard-form-submit" style="margin-top: 18px;">
                  <div class="col span_5_of_10">
                    <button onclick="ecard_priview()" class="ecard-button" type="button"><?php echo __('Preview') ?></button>
                  </div>
                  <div class="col span_5_of_10">
                    <button onclick="share_send()" class="ecard-button" type="button">
                    <?php echo __('Share or Send') ?></button>
                  </div>
                </div>
              </div>
            </div>


            <div class="bg-layer"></div>


          </form>

          <?php else :

          ?>
          <input type="hidden" id="ecard-id" value="<?php echo $_POST['ecard-id'] ?>" />
          <div style="display:none;" id="ecard-msg"><?php echo stripslashes($_POST['ecard-msg'])  ?></div>
          <input type="hidden" id="page-id" value="<?php echo $_POST['page-id'] ?>" />
          <input type="hidden" id="image" value="<?php echo $_POST['image'] ?>" />


          <div id="editor" class="section group ecards-panel">
            <div style="text-align:right" class="col span_4_of_10">
              <h2 style="text-align:left; width:100%; font-size:0.738rem"><?php echo __('ADDRESS AND SEND') ?></h2>
              <img style="width:100%; margin-top:15px; padding:20px;" src='<?php echo $data->image ?>' />
              <h2 style="text-align:center;"><?php echo __($data->title) ?></h2>
            </div>
            <div style="text-align:left" class="col span_6_of_10">
              <h2 style="float:right; text-align:right; font-size:0.738rem"><?php echo __('Step') ?> <span>3/3</span></h2>
              <form id="ecard-send-email-form">
                <h2><?php echo __('TO') ?>:</h2>
                <p><?php echo __('Required') ?> *</p>
                <div class="section group">
                  <div class="col span_5_of_10">
                    <label class="ecard-label"><?php echo __('Recipient name') ?> *</label>
                    <input class="ecard-input" type="text" name="ecard-to" id="ecard-to" required="">
                  </div>
                  <div class="col span_5_of_10">
                    <label class="ecard-label"><?php echo __('Recipient email') ?> *</label>
                    <input class="ecard-input" type="email" name="ecard-email" id="ecard-email" required="">
                  </div>
                </div>
                <br />
                <h2>FROM:</h2>

                <div class="section group">
                  <div class="col span_10_of_10">
                    <label class="ecard-label"><?php echo __('Your name') ?> *</label>
                    <input class="ecard-input" type="text" name="ecard-from" id="ecard-from" required="">
                  </div>
                  <div style="text-align:center;" class="col span_10_of_10">
                    <input type="submit" style="width: 50%;" class="ecard-button" value="<?php echo __('Submit') ?>" name="ecard-submit" id="ecard-submit">
                  </div>
                </div>

              </form>
            </div>
          </div>

         <?php endif ?>


 <!-- Model preview-->
 <div id="preview-model" class="model fade">
                  <div class="modal-content">
                    <div class="model-head">
                      <h3 class="model-title"><?php echo __('PREVIEW YOUR ECARD AND MESSAGE') ?></h3>
                      <span class="close"><?php echo __('Close') ?> &times;</span>
                    </div>
                    <img style="width:100%; margin-top:15px; padding:20px;" src='<?php echo $data->image ?>' />
                    <div class="msg"></div>
                  </div>
                </div>
                <!-- Model sharesend-->
                <div id="Share-model" style="width:37%" class="model fade">
                  <div class="modal-content">
                    <div class="model-head">
                      <h3 class="model-title"></h3>
                      <span class="close"><?php echo __('Close') ?> &times;</span>
                    </div>
                    <h2 style="padding-top: 25px;"><?php echo __('HOW WOULD YOU LIKE TO SEND YOUR ECARD') ?>?</h2>
                    <button onclick="jQuery('#csend').submit()" type="submit" style="width: 50%; margin-bottom:30px" class="ecard-button">
                    <?php echo __('Send via Email') ?>
                    </button>
                  </div>
                </div>
                <!-- Model sharesend-->
       
       
       
       
       <script>
          jQuery(".close").click(function() {
            jQuery(".bg-layer").hide();
            jQuery(".model.fade").hide().css('opacity', 0)
          })

          function share_send() {
            jQuery(".bg-layer").show();
            jQuery("#Share-model.fade").show().css('opacity', 1)
          }

          function ecard_priview() {
            for (var i = 0; i < tinymce.editors.length; i++) {
              tinymce.editors[i].onChange.add(function(ed, e) {
                jQuery(".msg").html(e.level.content)
              });
            }
            jQuery(".bg-layer").show();
            jQuery("#preview-model.fade").show().css('opacity', 1)
          }
        </script>




        <section id="ecard-thanks" style="display:none; text-align:center">
          <div class="ecard-ty-top ecards-panel">




            <div id="priview-landscape" style="padding:40px; text-align:center; float:left;" class="step2 ecard-preview alignleft">
              <h2><?php echo __('Your E-card is on its way now!') ?></h2>
              <h2><?php echo __('Preview') ?></h2>
              <div style="display:flex;">
                <div id="image-part" style="-webkit-box-shadow: -15px 15px 20px 1px #8A8A8A;
      box-shadow: -15px 15px 20px 1px #8A8A8A;       
      float:left; 
      
      padding:10px;      
      " class="card-page-1">
                  <!-- image will be 318X480 -->
                  <div><img src='<?php echo $data->image ?>' /></div>
                  <div style="padding:10px"><?php echo stripslashes($_POST['ecard-msg']) ?></div>

                </div>

              </div>
            </div>

          </div>
        </section>

      <?php

    endif;
    wp_reset_postdata();
      ?>
      <div style="clear:both; margin-top:50px;"></div>
      <script>
        function ecard_next(frm, to) {
          jQuery("." + frm).hide();
          jQuery("." + to).show();

          jQuery(".Step span").html(" " + to.charAt(to.length - 1) + "/3");
          jQuery("#priview-" + jQuery('input[name=ecardtype]:checked').val()).show()
        }
        jQuery(function($) {
          jQuery('input[name=ecardtype]').change(function() {
            if (jQuery(this).val() == "potrait") {
              $("#Potrait").show();
              $("#Landscape").hide()

            } else {
              $("#Landscape").show();
              $("#Potrait").hide();
            }
          })
          // Was needed a timeout since RTE is not initialized when this code run.
          setTimeout(function() {
            for (var i = 0; i < tinymce.editors.length; i++) {
              tinymce.editors[i].onChange.add(function(ed, e) {
                $(".msg").html(e.level.content)
              });
            }
          }, 1000);
        });



        jQuery("#ecard-to").keyup(function() {
          jQuery("#to span").text(jQuery(this).val())
        })
        jQuery("#ecard-from").keyup(function() {
          jQuery("#From span").text(jQuery(this).val())
        })

        jQuery(".ecard-color-scheme-wr label").click(function() {
          jQuery(".ecard-color-scheme-wr label").removeClass("active");
          jQuery(this).addClass("active")

        })
      </script>