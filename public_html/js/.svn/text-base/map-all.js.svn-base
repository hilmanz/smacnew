/*
 *	MAP FOR SMAC 
 *
 *	Irvan Fanani	
 *
 *	Transform to MAP HISTORICAL, 11 January 2012 
 */	

smap = new Object();	//SMAP -> Smac Map Start
var has_data = false;
var map_enabled = false;
var wc_busy = false;
var map_busy = false;
var wc_reset = true; //force reset the wordcloud for the first time the page is loaded.
smap.maps;	//map
smap.last_id = 0;
smap.grid_last_id = 0;
smap.urlx = '';
smap.staturlx = '';
smap.mapurlx = '';
smap.wc_urlx = '';
smap.first_marker = false;
smap.popup = [];
var max_amount = 0;
people = new Object();
var new_update = false;
var startOffset = 0;
var nextOffset = 0;
var prevOffset = 0;
var WINDOW_HTML = '';

if(map_enabled){
	$(document).ready(function(){
		if (GBrowserIsCompatible()) {
	
			//alert('masuk');
		
			var map = new GMap2(document.getElementById("map"));
		
			//map.addControl(new GSmallMapControl());
		
			//map.addControl(new GMapTypeControl());
			
			map.setCenter(new GLatLng(-3.000,118.000), 5);
			
			var customUI = map.getDefaultUI();
			customUI.controls.scalecontrol = false;
			map.setUI(customUI);
		
			/*	
			var marker = new GMarker(new GLatLng(42.8487, -73.755));
		
			map.addOverlay(marker);
		
			GEvent.addListener(marker, "click", function() {
				marker.openInfoWindowHtml(WINDOW_HTML);
			});
		
			marker.openInfoWindowHtml(WINDOW_HTML);
			
			*/
			
			smap.maps = map;
			
			//smap.addMarker(42.8487, -73.755);
			
			smap.getData();
			smap.getGridData();
			s_wl = "";
			//smap.interval = setInterval(smap.getData,30000);	//30 detik
			//smap.interval2 = setInterval(smap.getGridData,5000);	//5 detik
			//smap.interval3 = setInterval(smap.getLTWordcloud,5000); //5 detik
		}
	});
}else{
	$(document).ready(function(){
		
		smap.getGridData();
		smap.interval = setInterval(smap.getGridData,2000);
		smap.interval2 = setInterval(smap.getLTWordcloud,15000);
	});
}
smap.createPopup = function (img,nama,txt,id) {

	smap.popup[id] = '<div style="width: 300px;padding: 10px">';
	smap.popup[id] += '<div style="float:left;width:45px;"><img src="'+img+'" style="" width="45" height="45" /></div><div style="float:right;width:230px;"><strong>'+nama+'</strong><p>'+txt+'</p></div>'
	smap.popup[id] += '</div>';

}


smap.addMarker = function (lat,lon,id) {

	if(!smap.first_marker){
		smap.maps.setCenter(new GLatLng(lat,lon), 7);
		smap.first_marker = true;
	}
	
	var marker = new GMarker(new GLatLng(lat,lon));
	
	smap.maps.addOverlay(marker);
	
	GEvent.addListener(marker, "click", function() {
		marker.openInfoWindowHtml(smap.popup[id]);
	});
	
	/*
	marker.openInfoWindowHtml(WINDOW_HTML);
	*/
}

smap.addDataGrid = function(nama,keyword,txt,tanggal,pic){

	var htm = '<div class="row"><div class="thumb-author"><img src="'+pic+'"/></div><div class="entry"><div class="author">'+nama+'</div><div class="text">'+txt+'</div><div class="date">'+tanggal+'</div></div></div>';
	
	
	
	$('#grid tbody').prepend(htm);

}
smap.clearDataGrid = function(){
	$('#grid tbody').html('');
}

smap.getData = function () {

		var d = new Date();
		
		var rand = d.getTime();
		
		//'index.php?page=livetrack&act=getfeed&ajax=1&last_id='+smap.last_id+'&rand='+rand
		
		$.ajax({
		  url: smap.mapurlx + '&last_id='+smap.last_id+'&rand='+rand+'&from_date='+dt_from+'&to_date='+dt_to,
		  dataType: 'json',
		  success: function( data ) {
		  	if(data.success == 1){
			
				var feed = data.feed;
				
				if( feed ){
				
					var num = feed.length;
					
					for(i=0;i<num;i++){
						if(i==0){
							smap.last_id = feed[0].id[0];
						}
						//alert(feed[i].lat[0]+' - '+feed[i].lon[0]);
					
						var id = feed[i].id[0];
					
						var lat = feed[i].lat[0];
	
						var lon = feed[i].lon[0];
						
						var img = feed[i].image[0];
						
						var nama = feed[i].name[0];
						
						
						
						var txt = feed[i].txt[0];
						
						var keyword = feed[i].keyword[0];
						
						var tanggal = feed[i].published[0];
						
						//smap.addDataGrid(nama,keyword,txt,tanggal)
						
						if(lon != 0 && lat != 0){
							
							smap.createPopup(img,nama,txt,id);
	
							smap.addMarker(lat,lon,id);
						
						}
						
						
					
					}
				
				}	
			
			}else{
				
				//alert('Failed load feed');
			
			}
		  }
		});

}
smac_number = function(n){
	var str="";
	if(n>1000000){
		str = Math.round(n/1000000)+"M";
	}else if(n>1000){
		str = Math.round(n/1000)+"K";
	}else{
		str = n;
	}
	return str;
}
function next_feed(){
	startOffset = nextOffset;
	smap.getGridData();
}
function prev_feed(){
	if(prevOffset>=0){
		startOffset = prevOffset;
		smap.getGridData();
	}
}
function refresh_time(){
	var now = new Date;
	var jam = now.getHours() - 1;
	if(jam<10){
		jam = "0"+jam;
	}
	var menit = now.getMinutes();
	if(menit<10){
		menit = "0"+menit;
	}
	$('#waktu').html("Showing tweets from ("+jam+":"+menit+")");
}
smap.getGridData = function () {
		if(map_busy==false){
			$('#grid').show();
			$("#stream_load").show();
			map_busy=true;
			var d = new Date();
			
			var rand = d.getTime();
			
			if(has_data){
				$("#stream_load").html("<img src='images/loader.gif' width='14px' height='14px' style='margin:-2px;30px;'/><span style='margin-left:4px;'>Retrieving...</span>");
			}else{
				$("#stream_load").html("<img src='images/loader.gif' width='14px' height='14px' style='margin:-2px;30px;'/><span style='margin-left:4px;'>Preparing your stream...</span>");
			}
			
			
			$.ajax({
			  url: smap.urlx + '&last_id=0&rand='+rand+'&from_date='+dt_from+'&to_date='+dt_to+'&start='+startOffset,
			  dataType: 'json',
			  beforeSend: function(){
				
			  },
			  success: function( data ) {
				map_busy = false;
			  	if(data.success == 1){
			  		
					var feed = data.feed;
					
					if( feed ){
						new_update = true;
						
						if(feed.length>0){
							refresh_time();
							has_data = true;
							$("#stream_load").hide();
							smap.clearDataGrid();
						
							var num = feed.length;
							for(i=(num-1);i>=0;i--){
							
								//alert(feed[i].lat[0]+' - '+feed[i].lon[0]);
							
								var id = feed[i].id[0];
							
								var lat = feed[i].lat[0];
			
								var lon = feed[i].lon[0];
								
								var img = feed[i].image[0];
								
								var nama = feed[i].name[0];
								
								var txt = feed[i].txt[0];
								var pic = feed[i].pic[0];
								var keyword = feed[i].keyword[0];
								var words = feed[i].wordlist;
								nextOffset = parseInt(feed[i].next_offset[0]);
								prevOffset = parseInt(feed[i].prev_offset[0]);
								
								var tanggal = feed[i].published[0];
								if(nama!=null&&txt!=null){
									if(new_update){
										if(people[nama]==null){
											people[nama] = 1;
											lt_people++;
											lt_mentions++;
											try{
											lt_reach+=eval(feed[i].reach[0]);
											}catch(e){
												lt_reach+=0;
											}
										}
									}
									smap.addDataGrid(nama,keyword,txt,tanggal,pic)
								}
								smap.grid_last_id = feed[0].id[0];
								
								
							}
						}
					
					}
					
					update_stats();
					
					
					//-->
				}else{
					
					//alert('Connection failed, please try again');
				
				}
			  }
			});
		}

}
function update_stats(){
	$.ajax({
		  url: smap.staturlx,
		  dataType: 'json',
		  success: function( data ) {
			var o = eval(data);
			if(o.status==1){
				$("#people").html(o.data.people);
				$("#mentions").html(o.data.mentions);
				$("#reach").html(o.data.reach);
			}
		}
	});
}
smap.getLTWordcloud = function () {
	if(!wc_busy){
		wc_busy = true;
		var d = new Date();
		var rand = d.getTime();
		var is_reset = 0;
		if(wc_reset){
			console.log('reset wordcloud');
			is_reset = 1;
			wc_reset = false;
		}else{
			console.log("append wordcloud..");
		}
		$.ajax({
			  url: smap.wc_urlx + '&ajax=1&reset='+is_reset+'&rand='+rand,
			  //dataType: 'json',
			  beforeSend: function(){
				$("#ltwordcloud").html("<div style='margin:19px 7px 10px 129px'><img src='images/loader.gif'/><div style='margin:10px 10px 10px -28px'>Loading Wordcloud</div></div>");
			  },
			  success:  function( data ) {
				  	$("#ltwordcloud").html(data);
				  
				  	wc_busy = false;
				  }
			});
		
	}

}
