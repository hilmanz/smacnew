/*
 *	MAP FOR SMAC 
 *
 *	Irvan Fanani	
 *
 */	

smap = new Object();	//SMAP -> Smac Map Start

var map_enabled = false;
var wc_busy = false;
var map_busy = false;
var wc_reset = true; //force reset the wordcloud for the first time the page is loaded.
smap.maps;	//map

smap.last_id = 0;

smap.grid_last_id = 0;

smap.urlx = '';

smap.mapurlx = '';
smap.wc_urlx = '';

smap.first_marker = false;

smap.popup = [];
var max_amount = 0;
people = new Object();
var new_update = false;

var WINDOW_HTML = '';


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
		smap.getLTWordcloud();
		s_wl = "";
		
		smap.interval = setInterval(smap.getData,30000);	//30 detik
		
		smap.interval2 = setInterval(smap.getGridData,5000);	//5 detik
		smap.interval3 = setInterval(smap.getLTWordcloud,5000); //5 detik
	}

});

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

smap.addDataGrid = function(nama,keyword,txt,tanggal){

	var htm = '<tr><td>'+nama+'</td><td>'+txt+'</td><td>'+tanggal+'</td></tr>';
	
	$('#grid').show();
	
	$('#grid tbody').prepend(htm);

}

smap.getData = function () {

		var d = new Date();
		
		var rand = d.getTime();
		
		//'index.php?page=livetrack&act=getfeed&ajax=1&last_id='+smap.last_id+'&rand='+rand
		
		$.ajax({
		  url: smap.mapurlx + '&last_id='+smap.last_id+'&rand='+rand,
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
smap.getGridData = function () {
		if(map_busy==false){
			map_busy=true;
			var d = new Date();
			
			var rand = d.getTime();
			
			$.ajax({
			  url: smap.urlx + '&last_id='+smap.grid_last_id+'&rand='+rand,
			  dataType: 'json',
			  success: function( data ) {
				map_busy = false;
			  	if(data.success == 1){
			  		new_update = true;
					var feed = data.feed;
					
					if( feed ){
					
						var num = feed.length;
						
						for(i=(num-1);i>=0;i--){
						
							//alert(feed[i].lat[0]+' - '+feed[i].lon[0]);
						
							var id = feed[i].id[0];
						
							var lat = feed[i].lat[0];
		
							var lon = feed[i].lon[0];
							
							var img = feed[i].image[0];
							
							var nama = feed[i].name[0];
							
							var txt = feed[i].txt[0];
							
							var keyword = feed[i].keyword[0];
							var words = feed[i].wordlist;
							
							
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
								smap.addDataGrid(nama,keyword,txt,tanggal)
							}
							smap.grid_last_id = feed[0].id[0];
							
							
						}
					
					}
					
					//alert(smap.grid_last_id);
					//new_update = true;  
					//update stats
					
						$("#people").html(smac_number(lt_people));
						$("#mentions").html(smac_number(lt_mentions));
						$("#reach").html(smac_number(lt_reach));
						//smap.getLTWordcloud(s_wl);
					
					//-->
				}else{
					
					//alert('Connection failed, please try again');
				
				}
			  }
			});
		}

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
			  success:  function( data ) {
				  	$("#ltwordcloud").html(data);
				  
				  	wc_busy = false;
				  }
			});
		
	}

}
