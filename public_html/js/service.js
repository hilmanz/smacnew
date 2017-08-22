function smac_api(s_url,callback){
	$.ajax({
		  url: s_url+'&campaign_id='+campaign_id+'&access_token='+access_token,
		  dataType: 'json',
		  success: function( data ) {
		  	callback(data);
		  }
		});
}
function smac_html(s_url,callback){
	$.ajax({
		  url: s_url+'&campaign_id='+campaign_id+'&access_token='+access_token,
		  dataType: 'text',
		  success: function( data ) {
		  	callback(data);
		  }
		});
}
function smac_post(s_url,params,callback){
	$.ajax({
		  url: s_url+'?campaign_id='+campaign_id+'&access_token='+access_token,
		  dataType: 'json',
		  data:params,
		  type:'POST',
		  success: function( data ) {
		  	callback(data);
		  }
		});
}
