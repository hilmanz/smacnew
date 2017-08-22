// :: Live Track ::
// :: @kia ::

	//Global Variable
	var liveTrackTimeOut, liveTrackPostTimeOut;
	var pageInit = 0;
	var mapPageInit = 0;
	var loadInit = 0;
	var mapOptions = null;
	var map = null;
	var is_map = false;
	var mc = null;
	var map_since_id = 0;
	
	//markers
    var markers = [];
	$(document).ready(function(){
		Backbone.emulateHTTP = true;
		Backbone.emulateJSON = true;
		
		//initial main menu
		$('.livetrack a').addClass('current');
		
		//Backbone Router
		var Router = Backbone.Router.extend({
			routes: {
				"*action" : "hashTagMenu"
			},
			hashTagMenu: function(action){
				if(action == ''){
					liveTrack();
					liveTrackPost('NA',0);
					$('#livetrackChart').hide();
					is_map = true;
					liveTrackMap();
					$('#livetrackMap').show();
				}
			}
		});
		
		var app_router = new Router;
		Backbone.history.start();
	});
	
	//Live Track Dropdown Menu
	$("#liveTrackFilterSelect").live('change', function() {
		var dropID=this.value;
		
		switch(dropID){
			case '0':
				var kolID = 'livetrackChart';
				is_map = false;
				break;
			case '1':
				var kolID = 'livetrackMap';
				is_map = true;
				liveTrackMap();
				break;
			default:
				var kolID = 'livetrackChart';
				is_map = false;
		}
		$('.bgWhite').hide();
		$('#'+kolID+'').fadeIn();
	});
	
	//Map of Livetrack Function
	function liveTrackMap(){
		if(map==null){	
			$("#livetrackMap").html("Loading Map..");
			mapOptions = {
		          center: new google.maps.LatLng(-6.99035, 106.535),
		          zoom: 8,
		          mapTypeId: google.maps.MapTypeId.ROADMAP
		     };        
		    map = new google.maps.Map(document.getElementById("livetrackMap"),mapOptions);
		}
		smac_api(smac_api_url+'?method=livetrack&action=map_data&since_id='+map_since_id,function(dataCollection){
			try{
				$("#mapLoader").show();
				if(dataCollection.data.nodes!=null){
					$("#livetrackMap").show();
					$("#mapNoData").hide();
					$("#mapLoader").html("Loading Map Data "+number_format(markers.length)+" of "+number_format(dataCollection.data.total_rows));
	  				map_data_load(dataCollection.data.nodes,map,mapOptions);
	  			}else{
	  				$("#mapLoader").hide();
	  				if(map_since_id==0){
	  					$("#livetrackMap").hide();
	  					$("#mapNoData").show();
	  				}else{
	  					$("#mapNoData").hide();
	  					$("#livetrackMap").show();
	  					render_map_cluster(map,mapOptions);
	  				}
	  			}
	  		}catch(e){}
		});
		
	}
	function refresh_map(since_id){
		map_since_id = since_id
		smac_api(smac_api_url+'?method=livetrack&action=map_data&since_id='+since_id,function(dataCollection){
			try{
				$("#mapLoader").show();
				if(dataCollection.data.nodes!=null){
					$("#livetrackMap").show();
					$("#mapNoData").hide();
					$("#mapLoader").html("Loading Map Data "+number_format(markers.length)+" of "+number_format(dataCollection.data.total_rows));
	  				map_data_load(dataCollection.data.nodes,map,mapOptions);
	  			}else{
	  				$("#mapLoader").hide();
	  				if(map_since_id==0){
	  					$("#livetrackMap").hide();
	  					$("#mapNoData").show();
	  				}else{
	  					$("#livetrackMap").show();
	  					$("#mapNoData").hide();
	  					render_map_cluster(map,mapOptions);
	  				}
	  				
	  			}
	  		}catch(e){}
		});
	}
	function map_data_load(data,map,mapOptions){
		var info = null;
        var since_id = 0;
  		$.each(data,function(k,v){
  			markers.push(plot_marker({id:v.id,lat:v.lat,lon:v.lon},map,mapOptions));
  			
  			since_id = v.id;
  		});
  		if(is_map){
	  		if(data.length>0){
		  		refresh_map(since_id);
	  		}else{
	  			setTimeout(function(){
		  			refresh_map(since_id);		
		  		},30000);
	  		}
  		}
        
	}
	function render_map_cluster(map,mapOptions){
		var info = null;
        //cluster marker.
        if(mc==null){
        	mc = new MarkerClusterer(map, markers,{zoomOnClick:false});
        }
        mc.clearMarkers();
        mc.addMarkers(markers);
        google.maps.event.addListener(mc, "click", function (c) {
			var m = c.getMarkers();
		  	var rs = {};
		  	var feeds = "";
		  	mapPageInit = 0;
		  	$.each(m,function(i,v){
		  		if(v.title.length>0){
			  		if(i>0){
			  			feeds+=',';
			  		}
			  		//var o = JSON.parse(v.title);
			  		feeds+=v.title;
		  		}
		  		
		  	});
		  	
		  	if(feeds.length>0){
			  	$("#popupmap").show();
			  	map_load_feeds(feeds,0);
		  	}
	  	});
	}
	function map_load_feeds(sFeeds,start){
		smacLoader('popupmap .content-popup', 'loader-med', 'Twitter Feeds');
		if(mapPageInit == 0){
		$('#master').append('<div id="fade" style="display: block;"></div>');
		$('#popupmap').prepend('<a class="close" href="#"><img alt="Close" style="margin: -20px -33px 0 610px;" title="Close Window" class="btn_close" src="images/close.png"></a>');
		}
		smac_post(smac_api_url,
				  {method:'livetrack',action:'map_feeds',feeds:sFeeds,start:start},
				  function(dataCollection){
			try{
				var str='';
				$.each(dataCollection.data.feeds, function(k,v){
					str+='<div class="list">';
					str+='<div class="smallthumb"><a href="#" onclick="twitterPopup(\''+v.author_id+'\', \''+v.author_name+'\'); return false;" rel="profile"><img src="'+v.author_avatar+'"></a> </div>';
					str+='<div class="entry">';
					str+='<h3>'+v.author_name+'</h3>';
					str+='<span class="date">'+date('d/m/Y H:i',strtotime(v.published_datetime))+'</span> ';
					str+='<span>'+v.content+'</span>'; 
					str+='</div> <!-- .entry -->';
					str+='</div>';			
				});
				$('#popupmap .content-popup').html(str);
				
				//Init Page
				if(mapPageInit == 0){
					mapPageInit = 1;
					if(start == 0)start=1;
					smacPagination(dataCollection.data.total_rows, start, 'mapPage', sFeeds, 'map_load_feeds');
				}
			}catch(e){
				$('#popupmap .content-popup').html("No data available");
			}
		});
		
	}
	/**
	 * plot a point into map
	 * @param {Object} data
	 * @param {Object} map
	 * @param {Object} mapOptions
	 */
	function plot_marker(data,map,mapOptions){
		var _color = '#F00';
		
		var symbolOne = {
			  path: 'M -5,0 0,-5 5,0 0,5 z',
			  strokeColor: _color,
			  fillColor: _color,
			  fillOpacity: 0.5
			};
		
		var infowindow = new google.maps.InfoWindow({content:"Not Available Yet"});
		var marker = new google.maps.Marker({
		  title: data.id,
		  icon : symbolOne,
		  position: new google.maps.LatLng(
				data.lat, data.lon),
		  clickable: true,
		  draggable: false
		});
		
		//marker.setMap(map);
		
		google.maps.event.addListener(marker, 'click', function() {
		  infowindow.open(map,marker);
		});
		
		return marker;
		
	}
	function liveTrack(){
		clearTimeout(liveTrackTimeOut);
		//Loader
		$('#livetrackLoader').fadeIn();
		if (loadInit == 0){
			loadInit = 1;
			smacLoader('trendingIssues', 'loader-med', 'Trending Issues');
			smacLoader('trendingPeople', 'loader-med', 'Trending People');
			smacLoader('topLocations', 'loader-med', 'Trending Locations');
		}
		
		smac_api(smac_api_url+'?method=livetrack&action=stats',function(dataCollection){
			
			liveTrackTimeOut = setTimeout('liveTrack()', 15000);
			$('#livetrackLoader').fadeOut();
			//Daily Volume
			var category = new Array();
			var fbData = new Array();
			var twitData = new Array();
			var webData = new Array();
			
			$.each(dataCollection.data.daily_volume, function(k, v) {
				var month = (v.the_date).substr(5,2);
				var tgl = (v.the_date).substr(8,2);
				category.push(tgl+"/"+month+" "+v.the_hour+":00");
				fbData.push(parseInt(v.facebook));
				twitData.push(parseInt(v.twitter));
				webData.push(parseInt(v.web));
			});
			
			var data = [{
							name: 'Twitter',
							data: twitData,
							color: '#33CCFF'
						}, {
							name: 'Facebook',
							data: fbData,
							color: '#0071BB'
						}, {
							name: 'Web',
							data: webData,
							color: '#F7931E'
						}];

			stackAreaChart('livetrackChart', category, data, 80);	
			
			//Trending Issues
			var str='';
			try{
				$.each(dataCollection.data.issues, function(k, v){
					str+='<div class="list">';
					str+='<h3 class="arrow">'+v.keyword+'</h3>';
					str+='</div>';
				});
				$('#trendingIssues').html(str);
			}catch(e){
				$('#trendingIssues').html("No data available");
			}
			//Trending People
			var str='';
			try{
				$.each(dataCollection.data.people, function(k, v){
					str+='<div class="list">';
					str+='<div class="smallthumb">';
					str+='<a href="#" onclick="twitterPopup(\''+v.author_id+'\', \''+v.author_name+'\'); return false;" rel="profile"><img src="'+v.author_avatar+'"></a>';
					str+='</div>';
					str+='<div class="entry">';
					str+='<h3>'+v.author_name+'</h3>';
					str+='<a title="Mentions ('+number_format(v.total_mention)+')" class="icon-mention theTolltip">'+smac_number2(v.total_mention)+'</a>';
					str+='<a title="Total Impressions ('+number_format(v.total_impression)+')" class="icon-imp theTolltip">'+smac_number2(v.total_impression)+'</a>';
					// str+='<a href="#" onclick="" title="Mark for Reply" class="reply theTolltip">&nbsp;</a>';
					str+='</div>';
					str+='</div>';
				});
				$('#trendingPeople').html(str);
			}catch(e){
				$('#trendingPeople').html("No data available");
			}
			//Trending Locations
			var str='';
			try{
				$.each(dataCollection.data.country, function(k, v){
					var countryID =  v.country_id;
					countryID = countryID.toLowerCase();
					str+='<div class="list">';
					str+='<h1 class="flag-'+countryID+' flag">'+v.country+'</h1>';
					str+='</div>';
				});
				$('#topLocations').html(str);
			}catch(e){
				$('#topLocations').html("No data available");
			}
		});
		
		
	}
	
	function liveTrackPost(NA ,pageStart){
		
		//Loader
		// if (loadInit == 0){
			// loadInit = 1;
			smacLoader('twitter-topconv', 'loader-med', 'Actual Post');
		// }	
		
		
		smac_api(smac_api_url+'?method=livetrack&action=recent_posts&start='+pageStart+'',function(dataCollection){
			
			clearTimeout(liveTrackPostTimeOut);
			liveTrackPostTimeOut = setTimeout('liveTrackPost()', 3600000);
			try{
				var str='';
				$.each(dataCollection.data.feeds, function(k, v){
					var img = v.author_avatar;
					str+='<div class="list">';
					if(v.channel == '3'){
					var headID = 'mw';
					var twitID = '';
					str+='<div class="smallthumb"><a id="thumb'+v.id+'" onclick="webScreenshot(\''+v.screenshot+'\');return false;" class="poplight" style="background:white;"><img src="content/pics/thumb_'+v.screenshot+'" onerror="errorImage(this,'+v.id+');"></a></div>';
					}else if(v.channel == '1'){
					var headID = 'mt';
					var twitID = ' - @'+v.author_id;
					str+='<div class="smallthumb"><a href="#" class="poplight" rel="profile" onclick="twitterPopup(\''+v.author_id+'\', \''+v.author+'\'); return false;"><img src="'+img+'"></a> </div>';
					}else if(v.channel == '2'){
					var headID = 'mf';
					var twitID = '';
					str+='<div class="smallthumb"><a href="https://www.facebook.com/'+v.author_id+'" target="_blank" class="poplight" rel="profile"><img src="'+img+'"></a> </div>';
					}
					str+='<div class="entry">';
					if(v.channel == '3'){
						str+='<h3><a href="'+v.author+'" target="_blank">'+v.author+'</a></h3>';
					}else{
						str+='<h3>'+v.author+''+twitID+'</h3>';
					}
					str+='<span class="date">'+date('d/m/Y H:i',strtotime(v.dtpost))+'</span> ';
					str+='<span class="conversFont" style="width: 870px;display:block;word-wrap: break-word;">'+v.content+'</span>'; 
					str+='</div> <!-- .entry -->';
					str+='<div class="entry-action"> ';
					// if(v.channel==1){
					if(v.flag != '1'){
						str+='<a id="'+headID+''+v.feed_id+'" title="Move to Folder" href="#" class="reply theTolltip" onclick="openFolderList(\''+v.feed_id+'\');return false;">&nbsp;</a>';
						str+=moveFolderList('#'+headID+''+v.feed_id,v.feed_id,v.channel);
					}else{
						str+='<a style="float: right;margin: 0 9px 0 0;"><img src="images/icon_centang.png" width="24px" height="27px"/></a>';
					}
					// } 
					str+='</div><!-- .entry-action -->';
					str+='</div>';
				});
				$('#twitter-topconv').html(str);
				
				if(pageInit == 0){
					pageInit = 1;
					if(pageStart == 0)pageStart=1;
					smacPagination(dataCollection.data.total_rows, pageStart, 'liveTrackFeedPage', 'NA', 'liveTrackPost');
				}
			}catch(e){
				$('#twitter-topconv').html("No data available");
			}
		});
		
		
	}