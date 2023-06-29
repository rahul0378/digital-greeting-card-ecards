<?php
add_action("wp_ajax_ecardformsubmit", "ecardformsubmit");
//include_once(EC_DIR.'/TCPDF-main/tcpdf_include.php');
require EC_DIR . DS.'vendor' . DS. 'autoload.php';
use Dompdf\Dompdf;

 
if(isset($_POST['action']) && $_POST['action']=='ecardformsubmit'){
 
    $post =$_POST;
   

    $error['status'] 		= true;
    $error['string']		= [];
    $error['field']			= [];

    if(empty($post['to'])):
        $error['string'][] 	= 'Field is required';
        $error['field'][] 	= 'to';
        $error['status'] 	= false;
    endif;

    if(empty($post['from'])):
        $error['string'][] 	= 'Field is required';
        $error['field'][] 	= 'from';
        $error['status'] 	= false;
    endif;



    if(empty($post['email'])):
        $error['string'][] 	= 'Field is required';
        $error['field'][] 	= 'email';
        $error['status'] 	= false;
    elseif (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)):
        $error['string'][] 	= 'Invalid email format';
        $error['field'][] 	= 'email';
        $error['status'] 	= false;
    endif;



    if(empty($post['msg'])):
        $error['string'][] 	= 'Field is required';
        $error['field'][]	= 'msg';
        $error['status'] 	= false;
    endif;

    if($error['status'] == true){        
		$data = [];
		$data['from_email'] = esc_html($post['from']);
		$data['to_email'] 	= esc_html($post['to']);
		$data['email'] 		= esc_html($post['email']);
		$data['image'] 		= $post['image'];
		$data['message'] 	= htmlentities($post['msg']); 
    $data['ecard_type'] = $post['ecardtype']; 
         

        global $wpdb;

		$response = $wpdb->insert( $wpdb->prefix."ecards_entries", $data);
      

    
        if(!$response){
            wp_send_json( ['status'=>false, $data  ]);
        }
        else{
            $pdfFileName = createPDF($data);         
		       //  sendPDFtoMail($data, $pdfFileName);
            wp_send_json (['status'=>true]);
           
        }

    }
    else{
        wp_send_json(['status'=>false,$error]);
    }
   
    die(); 
 }

function createPDF($data) {


   if($data['ecard_type']== 'potrait'){
    $pdfHTML = '
    
    <div id="image-part" style="width:100%;display:block; text-align:center" class="card-page-1">
    
    <img style="width:100%; -webkit-box-shadow: 0 -10px 5px 5px #BABABA;
box-shadow: 0 -10px 5px 5px #BABABA;" src="'.$data['image'].'" />
    <div style="width:100%; margin: 0 auto;  overflow:hidden; position: relative;  -webkit-box-shadow: 0 10px 5px 5px #BABABA;
box-shadow: 0 10px 5px 5px #BABABA; margin-top: -9px;" class="card-page-2">
      <div style="text-align: left; padding: 12px;"">
        To
        <span style="display:block;font-weight:bold;">'.$data['to_email'].'</span>
      </div>
      <div style="text-align:center; height:360px; overflow:hidden; display:block;">'.nl2br(stripslashes($data['message'])).'</div>
      <div style="color:#000; text-align: right; padding: 12px; ">
        From
        <span style="display:block;font-weight:bold;">'.$data['from_email'].'</span>
      </div>
    </div>
  </div>  

     ';
   }else{

    $pdfHTML = '
    
    <div style="display:flex;">
    <div id="image-part" style="-webkit-box-shadow: -15px 15px 20px 1px #8A8A8A;
  box-shadow: -15px 15px 20px 1px #8A8A8A;       
  float:left; 
  
  padding:10px;      
  " class="card-page-1">
       
      <div><img style="width:480px" src='.$data['image'].' /></div>
      <div style="padding:10px">'.nl2br(stripslashes(html_entity_decode($data['message']))).'</div>
      
    </div>
     
  </div>
     ';
   
   }
    


     

    
    $dompdf = new Dompdf();
    $dompdf->loadHtml($pdfHTML);
    if($data['ecard_type'] == 'potrait'){
        $dompdf->setPaper([0.0, 0.0, 318, 960], $data['ecard_type']);
    }else{
        $dompdf->setPaper([0.0, 0.0, 440, 600], $data['ecard_type']);
    }
    
    $dompdf->render();
    $output = $dompdf->output();
    $file_name = EC_DIR.'/pdfs/'."ecard_".$data['from_email'].'_'.date('His').".pdf";
    file_put_contents($file_name, $output);

    return $file_name;

}
function sendPDFtoMail($data, $pdfFileName) {
    $mail_html = '<div class="border-wr" style="width:"400px"; padding: 15px;">

            <span class="preview_clr"></span><img src="'.$data['image'].'" style="width: auto; height: 200mm;">

                <div class="row preview_div">
                    <div class="form-group col-md-12">
                      <span id="" class="title-pdf" style="color: #000;font-size: 22px;padding: 0;line-height: 1.3;font-family: Roboto;font-weight: 600;margin: 10px 0;display: block;">Dear '.$data['to_email'].'</span>
                    </div>
                    <div class="form-group col-md-12">
                      <span id="labelMessage" class="decription-pdf" style="color: #333;font-size: 24px;line-height: 1.4;font-family: Roboto;font-weight: 400;padding: 15px 0;border-bottom: solid 1px #ececec;border-top: solid 1px #ececec;display: block;">'.$data['message'].'</span>
                    </div>
                    <div class="form-group col-md-12">
                      <span id="labelFrom" class="email" style="color: #000;font-size: 22px;padding: 0;line-height: 1.4;font-family: Roboto;font-weight: 600;margin: 15px 0;display: block;">from: '.$data['from_email'].'</span>
                    </div>
                </div>
              </div>';
                   
              $to 				= (string)@$data['email'];

              $subject 		= 'WP Ecard Message';
      
      
      
              $headers 		= array('Content-Type: application/pdf; charset=UTF-8');
      
              $headers 		.= "Content-Transfer-Encoding: 7bit";
      
              $attachment = array($pdfFileName);
      
      
      
              $content_type = function() { return 'text/html'; };
      
            add_filter( 'wp_mail_content_type', $content_type );
      
      
      
              $email_status = wp_mail( $to, $subject, $mail_html, $headers ,$attachment);
      
      
      
              remove_filter( 'wp_mail_content_type', $content_type );
      
      
      
              unlink($pdfFileName);
      
      
      
              return $email_status;

}