// :: Live Track ::
// :: @kia ::

	//Global Variable
	var marketSummaryArr = new Array();
	var marketFeedArr = new Array();
	var marketTabFeedArr = new Array();
	var otherMarketTabFeedArr = new Array();
	var elementArr = new Array();
	var liveTrackDataCollection;
	var pageInit = 0;
	var market_data;
	var geomap = null;
	var geo_options = null;
	$(document).ready(function(){
		Backbone.emulateHTTP = true;
		Backbone.emulateJSON = true;
		
		//initial main menu
		$('.icon_marketmenu a').addClass('current');
		
		//Backbone Router
		var Router = Backbone.Router.extend({
			routes: {
				"*action" : "hashTagMenu"
			},
			hashTagMenu: function(action){
				if(action == ''){
					marketSummary();
					
				}
			}
		});
		
		var app_router = new Router;
		Backbone.history.start();
		
		//Load Pie Chart Trigger
		$('.paginate_enabled_next').live('click', function(){
			miniMarketPieChart();
		});
		$('.paginate_enabled_previous').live('click', function(){
			miniMarketPieChart();
		});
		$('#marketTopCountryTable thead th').live('click', function(){
			miniMarketPieChart();
		});
		$('#marketTopCountryTable_filter input').live('blur', function(){
			miniMarketPieChart();
		});
	});
	
	function marketSummary(){
		//Loader
		smacLoader('marketTopCountry', 'loader', 'Top Country');
		smacLoader('country-tab', 'loader-med', 'Market Feed');
		
		smac_api(smac_api_url+'?method=market&action=summary',function(dataCollection){
			
			//assign data to global var
			liveTrackDataCollection = dataCollection.data.top_country;
			
			//Map
			marketMap(liveTrackDataCollection);
			
			var str='';
			var i=0;
			$.each(dataCollection.data.top_country, function(k,v){
				if (v.country != null){
					str+='<tr>';
					str+='<td>'+v.country+'</td>';
					str+='<td>'+intval(v.mentions)+'</td>';
					str+='<td>'+intval(v.people)+'</td>';
					str+='<td>'+intval(v.impression)+'</td>';
					str+='<td>'+v.share+'%</td>';
					elementArr.push(k);
					str+='<td><div id="sentiment'+k+'" style="width:118px;height:118px;"></div></td>';
					str+='<td><div id="channel'+k+'" style="width:118px;height:118px;"></div></td>';
					str+='</tr>';
					i++;
				}
				if (v.country != null){
					marketSummaryArr.push(v);
				}
			});
			$('#marketTopCountry').html(str);
			
			//Generate datatable
			$('#marketTopCountryTable').dataTable({
				"aoColumns": [
					null,
					null,
					null,
					null,
					null,
					{ "bSortable": false },
					{ "bSortable": false }
				],
				"aaSorting": [[ 1, "desc" ]],
				"iDisplayLength": 5,
				"aoColumnDefs": [ {
				  "sType" : "numeric",
				  "aTargets": [ 1,2,3 ],				  
				  "mRender": function (data) {
					return number_format(data);
				  }
				} ]
			});
			$('#marketTopCountryTable_length').hide();
			miniMarketPieChart();		
			marketFeedTab();
			
			//hack of datatable v1.9
			$('#marketTopCountryTable_next, #marketTopCountryTable_previous').html('');
		});
		
	}
	
	function miniMarketPieChart(){
		//pie chart
		$.each(elementArr, function(k,v){
			if($('#channel'+v).length){
				//Pie on table - Channel
				var channelArr = new Array();
				var facebook = {
					name : 'Facebook',
					y	 : parseInt(liveTrackDataCollection[v].facebook),
					color: '#262DED'
				}
				var twitter = {
					name : 'Twitter',
					y	 : parseInt(liveTrackDataCollection[v].twitter),
					color: '#31EBE8'
				}
				var web = {
					name : 'Web',
					y	 : parseInt(liveTrackDataCollection[v].web),
					color: '#FABC11'
				}
				channelArr.push(facebook);
				channelArr.push(twitter);
				channelArr.push(web);
				
				pieChartMiniChannel('channel'+v+'', channelArr, 110);
			}
			if($('#sentiment'+v).length){
				//Pie on table - Sentiment
				var tempPlus = (liveTrackDataCollection[v].sentiment_positive == null) ? '0' : liveTrackDataCollection[v].sentiment_positive;
				var tempMin = (liveTrackDataCollection[v].sentiment_negative == null) ? '0' : liveTrackDataCollection[v].sentiment_negative;

				var channelArr = new Array();
				if(liveTrackDataCollection[v].sentiment_positive != null || tempPlus != '0'){
					var positive = {
						name : 'Positive',
						y	 : parseInt(liveTrackDataCollection[v].sentiment_positive),
						color: '#8ec448'
					}
					channelArr.push(positive);
				}
				if(liveTrackDataCollection[v].sentiment_negative != null || tempMin != '0'){
					var negative = {
						name : 'Negative',
						y	 : parseInt(liveTrackDataCollection[v].sentiment_negative),
						color: '#ff0000'
					}
					channelArr.push(negative);
				}
				var neutralCalculation = Math.abs((parseInt(tempPlus)+parseInt(tempMin))-parseInt(liveTrackDataCollection[v].mentions));
				var neutral = {
					name : 'Neutral',
					y	 : neutralCalculation,
					color: '#666666'
				}
				channelArr.push(neutral);
				
				pieChartMiniSentiment('sentiment'+v+'', channelArr, 110);
			}
		});
	}
	
	function marketFeedTab(){
		//Tab Feed
		$.each(marketSummaryArr, function(k, v){
			if (k < 5 && v.country != null){
				marketTabFeedArr.push({id: v.country_id, country_name: v.country});
			}else{
				otherMarketTabFeedArr.push({id: v.country_id, country_name: v.country});
			}
		});
		
		var str='';
		var i = 0;
		$.each(marketTabFeedArr, function(k,v){
			if(k == 0){var active = 'active';}
			else{var active = '';}
			str+='<li class="'+active+'"><a href="#country-tab" onclick="marketFeed(\''+v.id+'\',0);pageInit = 0;" title="'+v.country_name+'">'+v.country_name+'</a></li>';
			i++;
		});
		
		if (otherMarketTabFeedArr != "" || otherMarketTabFeedArr != null){
			str+='<li><a id="other_country" href="#other-tab" onclick="otherButton(false);">Others<span id="otherCountryTab"></span></a></li>';
		}
		$('#tabMarketCountry').html(str);
		marketFeed(marketTabFeedArr[0].id, 0);
		
		var str='';
		$.each(otherMarketTabFeedArr, function(k,v){
			str+='<div class="list"><h3 class="arrow"><a style="font-size:16px;" href="#" onclick="marketFeed(\''+v.id+'\',0);otherButton(true, \''+v.country_name+'\');pageInit = 0;return false;" title="'+v.country_name+'">'+v.country_name+'</a></h3></div>';
		});
		$('#other-tab').html(str);
		
	}
	
	function marketFeed(id, start){
		//Loader
		$('.tab_content').hide();
		$('#country-tab').show();
		smacLoader('country-tab', 'loader-med', 'Market Feed');
		
		smac_api(smac_api_url+'?method=market&action=feeds&country_code='+id+'&start='+start+'',function(dataCollection){
			
			var str='';
			$.each(dataCollection.data.feeds, function(k,v){
				str+='<div class="list">';
				str+='<div class="smallthumb"><a href="#" onclick="twitterPopup(\''+v.author_id+'\', \''+v.author_name+'\'); return false;" rel="profile"><img src="'+v.author_avatar+'"></a> </div>';
				str+='<div class="entry">';
				str+='<h3>'+v.author_name+'</h3>';
				str+='<span class="date">'+date('d/m/Y H:i',strtotime(v.published_datetime))+'</span> ';
				str+='<span>'+v.content+'</span>'; 
				str+='</div> <!-- .entry -->';
				str+='<div class="entry-action"> ';
				if(v.flag != '1'){
					str+='<a id="mt'+v.feed_id+'" title="Move to Folder" href="#" style="width: 24px;" class="reply theTolltip" onclick="openFolderList(\''+v.feed_id+'\');return false;">&nbsp;</a>';
					str+=moveFolderList('#mt'+v.feed_id,v.feed_id,'1');
				}else{
					str+='<a style="float: right;margin: 0 9px 0 0;"><img src="images/icon_centang.png" width="24px" height="27px"/></a>';
				}
				str+='</div><!-- .entry-action -->';
				str+='</div>';			
			});
			$('#country-tab').html(str);

			//Init Page
			if(pageInit == 0){
				pageInit = 1;
				if(start == 0)start=1;
				smacPagination(dataCollection.data.total_rows, start, 'countryPage', id, 'marketFeed');
			}
		});
	}
	
	function otherButton(bool, country){
		if(bool == true){
			$('#otherCountryTab').html(": "+country);
		}else{
			$('#otherCountryTab, #countryPage').html("");
		}
	}
	
	function marketMap(data){
		market_data = eval(data);
		
		if(market_data!=null){
			var arrPlots = [];
			 var data = new google.visualization.DataTable();
	         data.addColumn('string', 'CountryID');
	         data.addColumn('number', 'Volume');
			 $.each(market_data, function(k,v){
				arrPlots.push([v.country_id,intval(v.mentions)]);
			 });
	         data.addRows(arrPlots);
		       geo_options['width'] = '100%';
		       geomap.draw(data, geo_options);
		 //  google.load('visualization', '1', {'packages': ['geomap']});
		  // google.setOnLoadCallback(drawMap);  
		}
		
    }
   
    function drawMap() {
        var arrPlots = new Array();
    	 var data = new google.visualization.DataTable();
         data.addColumn('string', 'CountryID');
         data.addColumn('number', 'Volume');
		 $.each(market_data, function(k,v){
			arrPlots.push([v.country_id,intval(v.mentions)]);
		 });
         
         data.addRows(arrPlots);
		  geo_options = {};
		  geo_options['width'] = '100%';
		  //options['colors'] = [0x0000cc, 0x00cc00, 0xcc000]; //orange colors
		  //options['dataMode'] = 'markers';

		  var container = document.getElementById('map_canvas');
		  var geomap = new google.visualization.GeoMap(container);
		  geomap.draw(data, geo_options);
		  google.visualization.events.addListener(geomap, 'select', function(){
				var selection = geomap.getSelection();
				var message = "";
				for (var i = 0; i < selection.length; i++) {
					var item = selection[i];
					var row = item.row;
					var country_code = arrPlots[row][0];
					document.location="index.php?req=Hsf0T9yfirqWQdc0dqK8reueMzD1f7fA&geo="+country_code;
				}
		  });
    }