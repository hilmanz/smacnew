/**
 * a javascrip resource for report page.
 */
var exclusiveReport = false;
function load_topic_report(sURL,target,txt){
	$.ajax({
	  url: sURL,
	  dataType: 'html',
	  beforeSend: function(){
		$("#reportloader").show();
		$("#reportloader").html("<img src='images/loader.gif' align='center' />");			
	  },
	  success: function( response ) {
		  if(response.length>0){
			  $("#"+target).html(response);
			  $("#reportloader").hide();
		  }
	  }});
}
function raw_progress(target,u){
	$.ajax({
	  url: u,
	  dataType: 'json',
	  beforeSend: function(){
	  },
	  success: function( response ) {
		  if(response.progress>0){
			  $("#"+target).html(response.progress);
		  }
		  if(response.progress<100){
			  setTimeout(function(){
				    raw_progress(target,u);
				},5000);
		  }
	  }});
}
function exclusive_reports(sURL){
	var target = "hasilReport3";
	var txt = "Loading Report(s)";
	$.ajax({
	  url: sURL,
	  dataType: 'html',
	  beforeSend: function(){
		$("#reportloader2").show();
		$("#reportloader2").html("<img src='images/loader.gif' align='center'/>");			
	  },
	  success: function( response ) {
		  if(response.length > 5){
			  $("#"+target).html(response);
			  $("#reportloader2").hide();
		  }else{
			$('#exclusiveReport').html(noDataReport);
		  }
		  
		  
	  }});
}