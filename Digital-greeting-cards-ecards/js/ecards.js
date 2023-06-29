 (function($){

  
  /*ecard form submit */

  $(document).on('submit', '#ecard-send-email-form', function(e){
 
    e.preventDefault();

    $("#ecard-send-loader").show();

		$('#ecard-submit').val('Sending...');

	  var cardId 	= $("#ecard-id").val();
	  var to 			= $("#ecard-to").val();
	  var from 		= $("#ecard-from").val();
	  var email 	= $("#ecard-email").val();
	  var msg 		= $("#ecard-msg").html();
	  var pageID 	= $("#page-id").val();
	  var image 	= $("#image").val();
      var ecardtype = $("input[name=ecardtype]:checked").val();
	   
	 // var ecolor 	= $("input[name=ecard-color]:checked").val();

	  msg 				= msg.replace(/'/g,"&#39;");





	  $('.has-error').remove();

	  $(this).find('input, select, textarea').css('border', 'solid');



	  var data = { 'action': 'ecardformsubmit', pageID:pageID, cardId:cardId, to:to, from:from, email:email, image:image, msg:msg, ecardtype:ecardtype};
 
 

		$.ajax({
	    type: 'post',
	    url: ajaxurl, 
	    data: data, 
		//async: false,
    	dataType: 'json',
    	success: function(response){
       console.log(response)
        if(response.status){

           $("#ecard-thanks").show();
           $("#editor").hide();
          window.scrollTo(0, 0);

        }else{
          $('#ecard-submit').val('Submit');
          $("#ecard-send-loader").hide();
         

        }

	    },
		error: function (jqXHR, exception) {
			console.log(jqXHR,exception)
		}


		});



  });





  /* ecard preview */

  $(document).on('click', '#ecard-preview', function() {

  	var toText = 'Dear ' + $("#ecard-to").val();

  	var fromText = 'From: ' + $("#ecard-from").val();

  	var msgText = $("#ecard-msg").val();



  	$(".ecard-preview-head-to").text(toText);

  	$(".ecard-preview-head-from").text(fromText);

  	$(".ecard-preview-msg").text(msgText);



  	$('html').addClass("ecard-hidden");

  	$("#ecard-preview-wr").fadeIn();

  });



  // $("#preview-close-layer, #preview-close, #ecard-preview-submit").click(function(){

  $(document).on('click', '#preview-close-layer, #preview-close, #ecard-preview-submit', function(){

  	$("#ecard-preview-wr").fadeOut();

  	$('html').removeClass("ecard-hidden");

  });



  $(document).on('change', 'input[type=radio][name=ecard-color]', function(){

    var bcolor = $(this).val();

    $("#ecard-preview-box").css('border-color', '#'+bcolor);

    $(".ecard-color-scheme-wr > label").removeClass("active");

    $(this).parent().addClass("active");

  });





})(jQuery);

