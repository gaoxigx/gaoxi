// JavaScript Document
$(function(){
	 loadpic();

	webcam.set_swf_url('/public/photo/js/webcam.swf');
	webcam.set_api_url('/index.php/Console/Shipments/expressphoto.html');	// The upload script
	webcam.set_quality(80);				// JPEG Photo Quality
	webcam.set_shutter_sound(true, '/public/photo/js/shutter.mp3');

	// Generating the embed code and adding it to the page:	
	var cam = $("#webcam");
	cam.html(
		webcam.get_html(cam.width(), cam.height())
	);
	
	var camera = $("#camera");
	var shown = false;
	$('#cam').click(function(){
		//$('#camera').target();
		// if(shown){
		// 	camera.animate({
		// 		bottom:-466
		// 	});
		// }else {
		// 	camera.animate({
		// 		bottom:-5
		// 	},{easing:'easeOutExpo',duration:'slow'});
		// }
		$('#camera').hide();
		//shown = !shown;
	});
	$('.photobtn').click(function(){
		$('#expressid').val($(this).attr('name'));
		$('#camera').show();
	});
	
	$('.imgcl').click(function(){
		$('#expressid').val($(this).attr('name'));
		$('#camera').show();
	});
	
	$("#btn_shoot").click(function(){
		webcam.freeze();
		$("#shoot").hide();
		$("#upload").show();
		return false;
	});
	
	$('#btn_cancel').click(function(){
		webcam.reset();
		$("#shoot").show();
		$("#upload").hide();
		return false;
	});
	
	$('#btn_upload').click(function(){	
	    var vl=$("#expressid").val();
	    var url="/index.php/Console/Shipments/expressphoto/id/"+vl+".html";
		webcam.upload(url);
		webcam.reset();
		$("#shoot").show();
		$("#upload").hide();
		return false;
	});
	
	
	webcam.set_hook('onComplete', function(msg){		
		msg = $.parseJSON(msg);
		
		if(msg.error){
			alert(msg.message);
		}
		else {
			$('#camera').hide();
			// Adding it to the page;


			var pic = '<div onclick="javascritp:showimg(\'/'+msg.sfilename+'\')"  data="/'+msg.sfilename+'"><span> <img style="width:78px" src="/'+msg.sfilename+'"></span></div>';
			$("#img"+msg.id).html(pic);
			//initFancyBox();
		}
	});
	
	webcam.set_hook('onError',function(e){
		cam.html(e);
	});
	
	
	// function initFancyBox(){
	// 	$("a[rel=group]").fancybox({
	// 	    'transitionIn'	: 'elastic',
	// 	    'transitionOut'	: 'elastic',
	// 	    'cyclic'        : true
	//     });
	// }
	
	 function loadpic(){
	 	$('#camera').hide();
	// 	$.getJSON("getpic.php",function(json){
	// 		if(json){
	// 			$.each(json,function(index,array){ 
	// 			   var pic = '<a rel="group" href="uploads/'+array['pic']+'"><img src="uploads/small_'+array['pic']+'"></a>';
 //                   $("#photos").prepend(pic); 
 //                }); 
	// 		}
	// 		initFancyBox();
	// 	});
 }
});