// :: Dashboard ::
// :: @cendekiapp ::

	//Global Variable
	var sumInit = 0;
	var twitInit = 0;
	var fbInit = 0;
	var webInit = 0;
	var forumInit = 0;
	var newsInit = 0;
	var ecommerceInit = 0;
	var corporateInit = 0;
	var videoInit = 0;
	var channelInit = 0;
	var checkShowList = 0;
	var loadVideoTab = 0;
	var sumDataCollection,twitDataCollection, fbDataCollection, webDataCollection,videoDataCollection, channelDataCollectionTwit, channelDataCollectionFB;
	var sumVolbySentiment, sumVolbyImpression, sumVolbyMention, sumVolbyPositif;
	var webVolbyMention, webVolbyImpression,videoVolbyMention,videoVolbyLike, videoVolbyDislike,  videoVolbyImpression, fbVolbyImpression, fbVolbyMention, twitVolbyImpression, twitVolbyMention, twitVolbyQuadrant, twitVolbyInteraction;
	var category;
	var startDate,prevStartDate, endDate, prevEndDate, typeFilter, startPage, webType;
	var twitCount, fbCount, webCount, sumCount,videoCount, blogCount, forumCount, newsCount, ecommerceCount, corporateCount;
	var actionPage;
	var test = {};
	var fbID, twitterID;
	$(document).ready(function(){
		Backbone.emulateHTTP = true;
		Backbone.emulateJSON = true;
		
		//initial main menu
		$('.dashboard a').addClass('current');
		//open popup change topic
		$('.popupListBtn').live('click', function(event){
			event.preventDefault();
			var listTag = $(this).attr('href');
			if(checkShowList==0){
				checkShowList=1;
				$(listTag).show();
			}else{
				checkShowList=0;
				$('.popupList').hide();
			}
			
		});
		
		//Date Initiation
		var tempStart = $('input[name="dt_from"]').val();
		var tempEnd = $('input[name="dt_to"]').val();
		// startDate = date_dmySlash_to_ymdDash(tempStart);
		// endDate = date_dmySlash_to_ymdDash(tempEnd);
		startDate = '';
		endDate = '';
		prevStartDate = startDate;
		prevEndDate = endDate;
		
		//Backbone Router
		var Router = Backbone.Router.extend({
			routes: {
				"workflow/:keyword/:type": "workflow",
				"workflow_gcs/:keyword/:type": "workflow_gcs",
				"reply/:id/:feed_id/:type": "reply",
				"play_video/:url/:trigger" : "play_video",
				"close_video":"close_video",
				"video_comments/:id":"video_feeds",
				":action" : "hashTagMenu",
				"*action" : "hashTagMenu"
			},
			hashTagMenu: function(action){
				if(action == 'summayPage' || action == ''){
					$('#noDashboardDataPage').hide();
					summaryTabMenu('summayPage');
					actionPage = 'summayPage';
					if (sumInit == 0){
						// sumInit = 1;						
						//Summary Data
						post_count('dashboardSummary');
					}else{
						if(prevStartDate != startDate || prevEndDate != endDate){
							post_count('dashboardSummary');
						}
					}
					
				}else if(action == 'twitterPage'){
					$('#noDashboardDataPage').hide();
					summaryTabMenu('twitterPage');
					actionPage = 'twitterPage';
					if (twitInit == 0){
						// twitInit = 1;					
						//Twitter Data
						post_count('dashboardTwitter');
						// dashboardTwitter();
					}else{
						if(prevStartDate != startDate || prevEndDate != endDate){
							post_count('dashboardTwitter');
							// dashboardTwitter();
						}
					}
				}else if(action == 'facebookPage'){
					$('#noDashboardDataPage').hide();
					summaryTabMenu('facebookPage');
					actionPage = 'facebookPage';
					if (fbInit == 0){
						// fbInit = 1;
						//Facebook Data
						post_count('dashboardFacebook');
						// dashboardFacebook();
					}else{
						if(prevStartDate != startDate || prevEndDate != endDate){
							post_count('dashboardFacebook');
							// dashboardFacebook();
						}
					}
				}else if(action == 'webPage'){
					$('#noDashboardDataPage').hide();
					summaryTabMenu('webPage');
					webType = '1';
					actionPage = 'webPage';
					if (webInit == 0){
						// webInit = 1;
						//Web Data
						post_count('dashboardWeb', 'web');
					}else{
						if(prevStartDate != startDate || prevEndDate != endDate){
							post_count('dashboardWeb', 'web');
							// dashboardWeb();
						}
					}
				}else if(action == 'forumPage'){
					$('#noDashboardDataPage').hide();
					summaryTabMenu('forumPage');
					webType = '2';
					actionPage = 'forumPage';
					if (forumInit == 0){
						// forumInit = 1;
						//Web Data
						post_count('dashboardWeb', 'forum');
					}else{
						if(prevStartDate != startDate || prevEndDate != endDate){
							post_count('dashboardWeb', 'forum');
						}
					}
				}else if(action == 'newsPage'){
					$('#noDashboardDataPage').hide();
					summaryTabMenu('newsPage');
					webType = '3';
					actionPage = 'newsPage';
					if (newsInit == 0){
						// newsInit = 1;
						//Web Data
						post_count('dashboardWeb', 'news');
					}else{
						if(prevStartDate != startDate || prevEndDate != endDate){
							post_count('dashboardWeb', 'news');
						}
					}
				}else if(action == 'ecommercePage'){
					$('#noDashboardDataPage').hide();
					summaryTabMenu('ecommercePage');
					webType = '5';
					actionPage = 'ecommercePage';
					if (ecommerceInit == 0){
						post_count('dashboardWeb', 'ecommerce');
					}else{
						if(prevStartDate != startDate || prevEndDate != endDate){
							post_count('dashboardWeb', 'ecommerce');
						}
					}
				}else if(action == 'corporatePage'){
					$('#noDashboardDataPage').hide();
					summaryTabMenu('corporatePage');
					webType = '0';
					actionPage = 'corporatePage';
					if (corporateInit == 0){
						post_count('dashboardWeb', 'corporate');
					}else{
						if(prevStartDate != startDate || prevEndDate != endDate){
							post_count('dashboardWeb', 'corporate');
						}
					}
				}else if(action == 'videoPage'){
					$('#noDashboardDataPage').hide();
					if(loadVideoTab == 0)summaryTabMenu('videoPage');
					actionPage = 'videoPage';
					$('#videoIframe').remove();
					// console.log(videoInit);
					if (videoInit == 0){
						// videoInit = 1;
						//Web Data
						post_count('dashboardVideo');
						// dashboardWeb();
					}else{
						if(prevStartDate != startDate || prevEndDate != endDate){
							post_count('dashboardVideo');
							// dashboardWeb();
						}
					}
				}else if(action == 'channelPage'){
					$('#noDashboardDataPage').hide();
					summaryTabMenu('channelPage');
					actionPage = 'channelPage';
					if (channelInit == 0){
						// channelInit = 1;
						//Channel Data
						post_count('dashboardChannel');
						// dashboardChannel();			
					}else{
						if(prevStartDate != startDate || prevEndDate != endDate){
							post_count('dashboardChannel');
							// dashboardChannel();
						}
					}
				}else if(action =="xxx"){
					//do nothing
				}
			},
			workflow:function(keyword,type){
				open_workflow_popup(keyword,type,0);
			},
			workflow_gcs:function(keyword,type){
				open_workflow_popup(keyword,3,type);
			},
			reply:function(id,feed_id,type){
				//$("html").scrollTop(0);
				mark_reply("#"+id,feed_id,type);
			},
			play_video:function(url, trigger){
				global_play_video(url, trigger);
			},
			close_video:function(){
				global_close_video();
			},
			video_feeds:function(id){
				videoFeeds(id, 0);
				$("#dashboardNav a").removeClass("active");
				$(".navVideo a").addClass("active");
				
			}
		});
		
		var app_router = new Router;
		
		Backbone.history.start();
		
		
	});
	
	//All Channel Performance (select from dropdown menu)
	function allChannelPerformance(divID){
			var dataCollectionToggle;
			switch(divID){
				case "sumVolbyNegative":
				  dataCollectionToggle = sumVolbyNegative;
				  break;
				case "sumVolbyImpression":
				  dataCollectionToggle = sumVolbyImpression;
				  break;
				case "sumVolbyMention":
				  dataCollectionToggle = sumVolbyMention;
				  break;
				case "sumVolbyPositive":
				  dataCollectionToggle = sumVolbyPositive;
				  break;
				default:
				  dataCollectionToggle = "Data not Available";
			}
			
			//FB data
			var _category = [];
			var _fbData = [];
			$.each(dataCollectionToggle.fb, function(k, v) {
				$.each(dataCollectionToggle.fb[k], function(key, value) {
					_category.push(key);
					_fbData.push(parseInt(value));
				});
			})
			var category = _category;
			
			//Twitter data
			var _twData = [];
			$.each(dataCollectionToggle.twitter, function(k, v) {
				$.each(dataCollectionToggle.twitter[k], function(key, value) {
					_twData.push(parseInt(value));
				});
			});
			
			if(divID == 'sumVolbyMention'){
				//Blog data
				var _webData = [];
				$.each(dataCollectionToggle.blog, function(k, v) {
					$.each(dataCollectionToggle.blog[k], function(key, value) {
						_webData.push(parseInt(value));
					});
				});
				
				//Forum data
				var _forumData = [];
				$.each(dataCollectionToggle.forum, function(k, v) {
					$.each(dataCollectionToggle.forum[k], function(key, value) {
						_forumData.push(parseInt(value));
					});
				});
				
				//News data
				var _newsData = [];
				$.each(dataCollectionToggle.news, function(k, v) {
					$.each(dataCollectionToggle.news[k], function(key, value) {
						_newsData.push(parseInt(value));
					});
				});
				
				//Ecommerce data
				var _ecommerceData = [];
				$.each(dataCollectionToggle.ecommerce, function(k, v) {
					$.each(dataCollectionToggle.ecommerce[k], function(key, value) {
						_ecommerceData.push(parseInt(value));
					});
				});
				
				//Corporate data
				var _corporateData = [];
				$.each(dataCollectionToggle.corporate, function(k, v) {
					$.each(dataCollectionToggle.corporate[k], function(key, value) {
						_corporateData.push(parseInt(value));
					});
				});
			}else{
				//Blog data
				var _webData = [];
				$.each(dataCollectionToggle.web, function(k, v) {
					$.each(dataCollectionToggle.web[k], function(key, value) {
						_webData.push(parseInt(value));
					});
				});
			}
			
			//Youtube data
			var _youtubeData = [];
			try{
			$.each(dataCollectionToggle.youtube, function(k, v) {
				$.each(dataCollectionToggle.youtube[k], function(key, value) {
					_youtubeData.push(parseInt(value));
				});
			});
			}catch(e){}
			if(use_video){
				if(divID == 'sumVolbyMention'){
				var data = [{
								name: 'Twitter',
								data: _twData,
								color: '#33CCFF'
							}, {
								name: 'Facebook',
								data: _fbData,
								color: '#0071BB'
							}, {
								name: 'Blog',
								data: _webData,
								color: '#F7931E'
							},{
								name: 'Forum',
								data: _forumData,
								color: '#AE8761'
							},{
								name: 'News',
								data: _newsData,
								color: '#906EB0'
							},{
								name: 'Ecommerce',
								data: _ecommerceData,
								color: '#9E0039'
							},{
								name: 'Corporate/Personal',
								data: _corporateData,
								color: '#ABA000'
							}, {
								name: 'Videos',
								data: _youtubeData,
								color: '#C70000'
							}];
				}else{
					var data = [{
								name: 'Twitter',
								data: _twData,
								color: '#33CCFF'
							}, {
								name: 'Facebook',
								data: _fbData,
								color: '#0071BB'
							}, {
								name: 'Blog',
								data: _webData,
								color: '#F7931E'
							},{
								name: 'Videos',
								data: _youtubeData,
								color: '#C70000'
							}];
				}
			}else{
				if(divID == 'sumVolbyMention'){
				var data = [{
								name: 'Twitter',
								data: _twData,
								color: '#33CCFF'
							}, {
								name: 'Facebook',
								data: _fbData,
								color: '#0071BB'
							}, {
								name: 'Blog',
								data: _webData,
								color: '#F7931E'
							},{
								name: 'Forum',
								data: _forumData,
								color: '#AE8761'
							},{
								name: 'News',
								data: _newsData,
								color: '#906EB0'
							},{
								name: 'Ecommerce',
								data: _ecommerceData,
								color: '#9E0039'
							},{
								name: 'Corporate/Personal',
								data: _corporateData,
								color: '#ABA000'
							}];
				}else{
					var data = [{
								name: 'Twitter',
								data: _twData,
								color: '#33CCFF'
							}, {
								name: 'Facebook',
								data: _fbData,
								color: '#0071BB'
							}, {
								name: 'Blog',
								data: _webData,
								color: '#F7931E'
							}];
				}
			}
			//testing
			test.category = category;
			test.data = data;
			//--> end of test
			stackAreaChart('sumVolumebySentiment', category, data, 50);			
	}
	
	//Summary Tab Menu
	function summaryTabMenu(hashTag){
		var targetID = "#"+hashTag;
		$(".pageContent").fadeOut();
		$("#dashboardNav a").removeClass("active");
		$(targetID).fadeIn();
		
		switch(targetID){
			case '#summayPage':
				var active = '.navSummary';
				break;
			case '#twitterPage':
				var active = '.navTwitter';
				break;
			case '#facebookPage':
				var active = '.navFacebook';
				break;
			case '#webPage':
				var active = '.navWeb';
				break;
			case '#forumPage':
				var active = '.navForum';
				break;
			case '#newsPage':
				var active = '.navNews';
				break;
			case '#ecommercePage':
				var active = '.navEcommerce';
				break;
			case '#corporatePage':
				var active = '.navCorporate';
				break;
			case '#videoPage':
				var active = '.navVideo';
				loadVideoTab = 1;
				break;
			case '#channelPage':
				var active = '.navChannel';
				break;
			default:
				var active = '.navSummary';
		}
		
		$(active+" a").addClass("active");
	}
	
	//Mini Box
	function miniBox(val, divID, detail){
		
		if (val != null){
			$("#"+divID+" h1").html(smac_number2(val.value));
			$("#"+divID+" h1").addClass('theTolltip');
			$("#"+divID+" h1").attr({'title': number_format(val.value),'style' : 'cursor: pointer;'});
			if (detail){
				var diff = ""+val.diff+"";
				diff = diff.substr(0,1);
				$("#"+divID+" .triangle").show();
				if (diff == '-'){
					$("#"+divID+" .triangle").addClass('arrow_down');
					$("#"+divID+" .counts").show();
				}else if(val.diff==0){
					$("#"+divID+" .triangle").hide();
					$("#"+divID+" .counts").hide();
				}
				$("#"+divID+" .counts").html(val.diff);
			}else{
				$("#"+divID+" .triangle").hide();
			}
		}else{
			$("#"+divID+" h1").css('fontSize', '18px');
			$("#"+divID+" .triangle").hide();
			$("#"+divID+" h1").html("Data not Available");
		}
	}
	
	//Pie Chart Data
	function pieChartData(divID, chartData, type){
		var temp = new Array();
		$.each(chartData, function(key, val) {
			if(type == 'sentiment'){
				switch(stripslashes(val.rule)){
					case 'positive':
						var warna = '#8ec448';
						break;
					case 'negative':
						var warna = '#ff0000';
						break;
					case 'neutral':
						var warna = '#666666';
						break;
					default:
						var warna = '#666666';
				}
				var pieChartSlice = {
					name: stripslashes(val.rule),
					y 	: parseInt(val.value),
					color: warna
				};
			}else{
				var pieChartSlice = {
					name: stripslashes(val.rule),
					y 	: parseInt(val.value)
				};
			}
			temp.push(pieChartSlice);
		});
		pieChart(divID, temp);
	}
	
	//Web Daily Data
	function channelPerformance(containerID, divID, tabName, tabWeb){
		var dataCollectionToggle;
		var volumeColor;
		switch(divID){
			case "videoVolbyImpression":
			  var typeKOLChart = 0;
			  dataCollectionToggle = videoVolbyImpression;
			  volumeColor = '#C70000';
			  break;
			case "videoVolbyLike":
			  var typeKOLChart = 0;
			  dataCollectionToggle = videoVolbyLike;
			  volumeColor = '#C70000';
			  break;
			  
			 case "videoVolbyDislike":
			  var typeKOLChart = 0;
			  dataCollectionToggle = videoVolbyDislike;
			  volumeColor = '#C70000';
			  break;
			  
			case "videoVolbyMention":
				var typeKOLChart = 0;
			  dataCollectionToggle = videoVolbyMention;
			  volumeColor = '#C70000';
			  break;
			case ""+tabWeb+"VolbyImpression":
				var typeKOLChart = 0;
			  dataCollectionToggle = webVolbyImpression;
			  volumeColor = '#F7931E';
			  break;
			case ""+tabWeb+"VolbyMention":
				var typeKOLChart = 0;
			  dataCollectionToggle = webVolbyMention;
			  volumeColor = '#F7931E';
			  break;
			case "fbVolbyImpression":
				var typeKOLChart = 0;
			  dataCollectionToggle = fbVolbyImpression;
			  volumeColor = '#0071BB';
			  break;
			case "fbVolbyMention":
				var typeKOLChart = 0;
			  dataCollectionToggle = fbVolbyMention;
			  volumeColor = '#0071BB';
			  break;
			case "twitVolbyImpression":
				var typeKOLChart = 0;
			  dataCollectionToggle = twitVolbyImpression;
			  volumeColor = '#33CCFF';
			  break;
			case "twitVolbyMention":
				var typeKOLChart = 0;
			  dataCollectionToggle = twitVolbyMention;
			  volumeColor = '#33CCFF';
			  break;
			case "twitVolbySentiment2":
				var typeKOLChart = 1; 
			  twitSentimentDash('twitDailyVolume', twitVolbySentiment2)
			  volumeColor = '#33CCFF';
			  break;
			case "twitVolbySentiment":
				var typeKOLChart = 1;
				twitterQuadrant('twitDailyVolume', twitVolbyQuadrant);
			  break;
			case "twitVolbyInteraction":
				var typeKOLChart = 1;
				twitterQuadrant('twitDailyVolume', twitVolbyInteraction, true);
			  break;
			
			default:
			  dataCollectionToggle = "Data not Available";
		}
		if (typeKOLChart != 1){
			//Web data
			var _category = new Array();
			var _webData = new Array();
			$.each(dataCollectionToggle, function(key, value) {
				// var month = (key).substr(5,2);
				// var tgl = (key).substr(8,2);
				// _category.push(tgl+"/"+month);
				_category.push(key);
				
				_webData.push(parseInt(value));
			});
			var category = _category;
			var is_web = false;
			var data = [];
			if(tabWeb){
				is_web = true;
			
			data = [{
							name: tabWeb,
							data: _webData,
							color: volumeColor
						}];
			}else{
				data = [{
							name: tabName,
							data: _webData,
							color: volumeColor
						}];
			}
		
			stackAreaChart(containerID, category, data, 50,is_web);
		}
	}
	
	//Sentiment Twitter
	function twitSentimentDash(containerID, twitVolbySentiment2){
		var _category = new Array();
		var _sentimentPlus = new Array();
		var _sentimentMin = new Array();
		$.each(twitVolbySentiment2, function(k, v) {
			// var month = (v['dtreport']).substr(5,2);
			// var tgl = (v['dtreport']).substr(8,2);
			// _category.push(tgl+"/"+month);
			_category.push(v['dtreport']);
			
			_sentimentPlus.push(parseInt(v['positive']));
			_sentimentMin.push(parseInt(v['negative']));
		});
		var category = _category;
		
		var data = [{
						name: 'Positive',
						data: _sentimentPlus,
						color:  '#8ec448'
					},{
						name: 'Negative',
						data: _sentimentMin,
						color:  '#ff0000'
					}];
		stackAreaChart(containerID, category, data, 50);
	}
	
	//Top Post - Summary
	function topPostSummary(dataCollection){
		var postLengthSUM = dataCollection.data;
		if(postLengthSUM.length > 0){
			var str="";
			$.each(dataCollection.data, function(key, val) {
				str+='<div class="list">';
				if(val.channel==1){
					str+='<div class="smallthumb"><a rel="profile" class="poplight" href="#" onclick="twitterPopup(\''+val.author_id+'\', \''+val.name+'\'); return false;"><img src="'+val.profile_image_url+'"></a> </div>';
					var headID = 'mt';
				}else if(val.channel==2){
					str+='<div class="smallthumb"><a rel="profile" class="poplight" target="_blank" href="https://www.facebook.com/'+val.author_id+'"><img src="'+val.profile_image_url+'"></a> </div>';	
					var headID = 'mr';
				}else{
					str+='<div class="smallthumb"><a id="thumb'+val.id+'" onclick="webScreenshot(\''+val.screenshot+'\');return false;" class="poplight" style="background:white;"><img src="content/pics/thumb_'+val.screenshot+'" onerror="errorImage(this,'+val.id+');"></a></div>';
					var headID = 'mw';
				}
				str+='<div class="entry">';
				if(val.channel==1 || val.channel==2){
					str+='<h3>'+val.name+'</h3>';
				}else{
					str+='<h3><a href="'+val.url+'" target="_blank">'+val.name+'</a></h3>';
				}
				str+='<span class="date">'+date('d/m/Y H:i',strtotime(val.published))+'</span> ';			
				str+='<span class="conversFont">'+val.txt+'</span>';
				str+='</div>';
				str+='<div class="entry-action">';
				if(val.device == 'blackberry') var bb='active';
				if(val.device == 'apple') var apple='active';
				if(val.device == 'android') var android='active';
				str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
				str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
				str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
				var sentiment,icon;
				if(val.sentiment > 0){
					if(val.sentiment>1){
						sentiment = 'very positive';
						icon = 'positive';
					}else{
						sentiment = 'positive';
						icon = 'positive';
					}
				}
				else if(val.sentiment < 0){
					if(val.sentiment<-1){
						sentiment = 'very negative';
						icon = 'negative';
					}else{
						sentiment = 'negative';
						icon = 'negative';
					}
				}
				else {
					sentiment = 'neutral';
					icon = 'neutral';
				}
				str+='<a id="sentiment_'+val.id+'" class="icon-'+icon+' theTolltip" title="Sentiment" onclick="sentimentChangePopup(\''+val.id+'\');return false;" style="margin: 0 0 0 16px;text-transform: capitalize;width: 100px;">'+sentiment+'</a>';
				str+=sentimentChange(val.id,val.channel,null,icon);
				if(val.flag != '1'){
					str+='<a id="'+headID+''+val.id+'" title="Move to Folder" href="#" class="reply theTolltip" onclick="openFolderList(\''+val.id+'\');return false;">&nbsp;</a>';
					str+=moveFolderList('#'+headID+''+val.id,val.id,val.channel);
				}else{
						str+='<a style="float: right;margin: 0 9px 0 0;"><img src="images/icon_centang.png" width="24px" height="27px"/></a>';
					}
				str+='</div>';
				str+='</div>';
			});
			return str;
		}else{
			return '<span style="color: #333333;">No data available</span>';
		}
	}
	
	//Top Post - Twitter
	function topPostTwitter(dataCollection){
		var postLengthTW = dataCollection.data;
		if(postLengthTW.length > 0){
			var str="";
			$.each(dataCollection.data, function(key, val) {
				str+='<div class="list">';
				str+='<div class="smallthumb"><a rel="profile" href="#" onclick="twitterPopup(\''+val.author_id+'\', \''+val.name+'\'); return false;"><img src="'+val.profile_image_url+'"></a> </div>';
				str+='<div class="entry">';
				str+='<h3>'+val.name+'  (@'+val.author_id+') - '+val.location+'</h3>';
				str+='<span class="date">'+date('d/m/Y H:i',strtotime(val.published))+'</span> ';
				str+='<a class="icon-rts theTolltip" title="Retweet Frequency  ('+number_format(val.rt)+')">'+smac_number2(val.rt)+'</a>';
				str+='<a class="icon-imp theTolltip" title="Total Impressions ('+number_format(val.impression)+')">'+smac_number2(val.impression)+'</a>';
				str+='<a class="icon-share theTolltip" title="Share">'+roundNumber(val.share, 2)+'%</a>';
				var sentiment,icon;
				if(val.sentiment > 0){
					if(val.sentiment>1){
						sentiment = 'very positive';
						icon = 'positive';
					}else{
						sentiment = 'positive';
						icon = 'positive';
					}
				}
				else if(val.sentiment < 0){
					if(val.sentiment<-1){
						sentiment = 'very negative';
						icon = 'negative';
					}else{
						sentiment = 'negative';
						icon = 'negative';
					}
				}
				else {
					sentiment = 'neutral';
					icon = 'neutral';
				}
				str+='<a id="sentiment_'+val.id+'" class="icon-'+icon+' theTolltip" title="Sentiment" onclick="sentimentChangePopup(\''+val.id+'\',1);return false;" style="line-height:27px;float:none;text-transform: capitalize;width: 100px;">'+sentiment+'</a>';
				str+='</div>';
				str+='<div class="entry-action" style="position:relative;">';
				str+=sentimentChange(val.id,1,null,icon, '-35px');
				if(val.device == 'blackberry') var bb='active';
				if(val.device == 'apple') var apple='active';
				if(val.device == 'android') var android='active';
				str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
				str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
				str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
							
				str+='<span class="conversFont" style="font-size: 14px;left: 65px;position: absolute;top: -3px;">'+val.txt+'</span>';
				if(val.flag != '1'){
				str+='<a id="mt'+val.id+'" title="Move to Folder" href="#" class="reply theTolltip" onclick="openFolderList(\''+val.id+'\');return false;">&nbsp;</a>';
				str+=moveFolderList('#mt'+val.id,val.id,'1');
				}else{
						str+='<a style="float: right;margin: 0 9px 0 0;"><img src="images/icon_centang.png" width="24px" height="26px"/></a>';
					}
				str+='</div>';
				str+='</div>';
			});
			return str;
		}else{
			return '<span style="color: #333333;">No data available</span>';
		}
	}
	
	//Top Post - Facebook
	function topPostFB(dataCollection){
		var postLengthFB = dataCollection.data;
		if(postLengthFB.length > 0){
			var str="";
			$.each(dataCollection.data, function(key, val) {
				str+='<div class="list">';
				str+='<div class="smallthumb"><a rel="profile" class="poplight" target="_blank" href="'+val.url+'"><img src="'+val.profile_image_url+'"></a> </div>';
				str+='<div class="entry">';
				str+='<h3>'+val.name+'</h3>';
				str+='<span class="date">'+val.published+'</span> ';
				str+='<span class="conversFont">'+val.txt+'</span>';
				str+='</div>';
				str+='<div class="entry-action">';
				if(val.device == 'blackberry') var bb='active';
				if(val.device == 'apple') var apple='active';
				if(val.device == 'android') var android='active';
				str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
				str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
				str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';

				str+='<a class="like theTolltip" title="Total Like">'+val.like+'</a>';
				var sentiment,icon;
				if(val.sentiment > 0){
					if(val.sentiment>1){
						sentiment = 'very positive';
						icon = 'positive';
					}else{
						sentiment = 'positive';
						icon = 'positive';
					}
				}
				else if(val.sentiment < 0){
					if(val.sentiment<-1){
						sentiment = 'very negative';
						icon = 'negative';
					}else{
						sentiment = 'negative';
						icon = 'negative';
					}
				}
				else {
					sentiment = 'neutral';
					icon = 'neutral';
				}
				str+='<a id="sentiment_'+val.id+'" class="icon-'+icon+' theTolltip" onclick="sentimentChangePopup(\''+val.id+'\');return false;" title="Sentiment" style="text-transform: capitalize;width:100px;">'+sentiment+'</a>';
				str+=sentimentChange(val.id,2,null,icon);
				if(val.flag != '1'){
				str+='<a id="mf'+val.id+'" title="Move to Folder" href="#" style="width: 24px;" class="reply theTolltip" onclick="openFolderList(\''+val.id+'\');return false;">&nbsp;</a>';
				str+=moveFolderList('#mf'+val.id,val.id,'2');
				}else{
						str+='<a style="float: right;margin: 0 9px 0 0;"><img src="images/icon_centang.png" width="24px" height="27px"/></a>';
					}
				str+='</div>';
				str+='</div>';
			});
			return str;
		}else{
			return '<span style="color: #333333;">No data available</span>';
		}
	}
	
	//Top Post - Web
	function topPostWeb(dataCollection){
		var postLengthWeb = dataCollection.data;
		if(postLengthWeb.length > 0){
			var str="";
			$.each(dataCollection.data, function(key, val) {
				str+='<div class="list">';
				str+='<div class="smallthumb">';
				str+='<a id="thumb'+val.id+'" onclick="webScreenshot(\''+val.screenshot+'\');return false;" class="poplight" style="background:white;"><img src="content/pics/thumb_'+val.screenshot+'" onerror="errorImage(this,'+val.id+');"></a>';
				str+='</div>';
				str+='<div class="entry">';
				str+='<h3><a href="'+val.url+'" target="_blank">'+val.name+'</a></h3>';			
				str+='<span>'+val.title+'</span><br>';
				str+='<span class="conversFont">'+val.txt+'</span>';
				str+='</div>';
				str+='<div class="entry-action">';
				if(val.device == 'blackberry') var bb='active';
				if(val.device == 'apple') var apple='active';
				if(val.device == 'android') var android='active';
				str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
				str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
				str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
				var sentiment,icon;
				if(val.sentiment > 0){
					if(val.sentiment>1){
						sentiment = 'very positive';
						icon = 'positive';
					}else{
						sentiment = 'positive';
						icon = 'positive';
					}
				}
				else if(val.sentiment < 0){
					if(val.sentiment<-1){
						sentiment = 'very negative';
						icon = 'negative';
					}else{
						sentiment = 'negative';
						icon = 'negative';
					}
				}
				else {
					sentiment = 'neutral';
					icon = 'neutral';
				}
				str+='<a id="sentiment_'+val.id+'" class="icon-'+icon+' theTolltip" onclick="sentimentChangePopup(\''+val.id+'\');return false;" title="Sentiment" style="margin: 0 0 0 16px;text-transform: capitalize;width:100px;">'+sentiment+'</a>';
				str+=sentimentChange(val.id,3,webType,icon);
				if(val.flag != '1'){
				str+='<a id="mw'+val.id+'" title="Move to Folder" href="#" class="reply theTolltip" onclick="openFolderList(\''+val.id+'\');return false;">&nbsp;</a>';
				str+=moveFolderList('#mw'+val.id,val.id,'3',null,webType);
				}else{
						str+='<a style="float: right;margin: 0 9px 0 0;"><img src="images/icon_centang.png" width="24px" height="27px"/></a>';
					}
				str+='</div>';
				str+='</div>';
			});
			return str;
		}else{
			return '<span style="color: #333333;">No data available</span>';
		}
	}
	//Top Post - Web
	function topPostVideo(dataCollection){
		var postLengthWeb = dataCollection.data;
		if(postLengthWeb.length > 0){
			var str="";
			$.each(dataCollection.data, function(key, val) {
				str+='<div class="list">';
				str+='<div class="smallthumb"><a rel="profile" class="poplight" style="background:white;" href="#play_video/'+urlencode(val.link)+'/0"><img src="'+val.preview_url+'"></a> </div>';
				str+='<div class="entry">';
				str+='<h3>'+date('d/m/Y',strtotime(val.published_date))+'</h3>';			
				str+='<span>'+val.title+'</span><br>';
				str+='<span class="conversFont">'+val.txt+'</span>';
				str+='<a class="icon-imp theTolltip" title="Total Views">'+val.total_views+'</a>';
				str+='<a class="icon-rts theTolltip" title="Total Likes">'+val.total_likes+'</a>';
				str+='<a class="icon-share theTolltip" title="Total Comments">'+val.total_comments+'</a>';
				str+='</div>';
				str+='<div class="entry-action">';
				if(val.total_comments>0){
					str+='<a href="#/video_comments/'+val.id+'" style="float: left;padding-left:65px;margin: 0 9px 0 0;width:85px;">View Comments</a>';
				}
				/*
				if(val.flag != '1'){
				str+='<a id="mw'+val.id+'" title="Move to Folder" href="#" class="reply theTolltip" onclick="openFolderList(\''+val.id+'\');return false;">&nbsp;</a>';
				str+=moveFolderList('#mw'+val.id,val.id,'3');
				}else{
						str+='<a style="float: right;margin: 0 9px 0 0;"><img src="images/icon_centang.png" width="24px" height="27px"/></a>';
					}
				*/
				str+='</div>';
				str+='</div>';
			});
			return str;
		}else{
			return '<span style="color: #333333;">No data available</span>';
		}
	}
	//Wordcloud
	function dashboardWordcloud(wordData,type,group_type){
		group_type = typeof group_type !== 'undefined' ? group_type : 0;
		if(wordData.length > 0){
			var str="";
			//before we do anything, we need to normalize the ratios
			var an = {};
			var ff = false;
			$.each(wordData, function(k, v) {
				if(typeof an[v.value]==='undefined'){
					an[v.value]=1;
					ff=true;
				}
			});
			var nParts=0;
			if(ff){
				$.each(an,function(k,v){
					nParts++;
				});
			}
			
			$.each(wordData, function(k, v) {
				var fontRatio = (Math.ceil(v.value*nParts)/10)/10;
				if(fontRatio<0.1){
					fontRatio=0.1;
				}
				var fontSize = Math.ceil((fontRatio*34)+6);
				
				if(fontSize>40){fontSize=40;}
				
				var zIndex = parseInt(40 - fontSize);
				var sentiment;
				if(v.sentiment>0){
					 sentiment = '#8ec448';
				}else if(v.sentiment<0){
					sentiment = '#ff0000';
				}else{
					 sentiment = '#000000';
				}
				//for web channel, we use workflow_gcs
				if(type==3){
					str+='<span><a href="#/workflow_gcs/'+v.keyword+'/'+group_type+'" style="font-size:'+fontSize+'px; color:'+sentiment+';position:relative;z-index:'+zIndex+';">'+v.keyword+'</a> </span>';
				}else{
					str+='<span><a href="#/workflow/'+v.keyword+'/'+type+'" style="font-size:'+fontSize+'px; color:'+sentiment+';position:relative;z-index:'+zIndex+';">'+v.keyword+'</a> </span>';
				}
			});
			return str;
		}else{
			return '<span style="color: #333333;">No data available</span>';
		}
	}
	
	//Twitter Quadrant
	function twitterQuadrant(divID, data, rate){
		var contentGroupsData = [data];
		
		if(contentGroupsData.length != 1){
			contentGroupsData = data
		}
		//Quadrant Chart
			var quadrantData = new Array();
			$.each(contentGroupsData, function(k,v){
				if(rate == true){
					var qData = {
					id:parseInt(v.id),
					label:v.name,
					volume:parseInt(v.volume),
					viral:parseFloat(v.rate),
					sentiment:round(v.sentiment,2)
					};
				}else{
					var qData = {
						id:parseInt(v.id),
						label:v.name,
						volume:parseInt(v.volume),
						viral:parseFloat(v.viral),
						sentiment:round(v.sentiment,2)
						};
				}
				quadrantData.push(qData);
			});
		
		//Twitter Channel Performance
		quadrant({target: divID,width:943,height:280,data:quadrantData,type: rate});
	}
	
	function dashboardChannel(){
		//FB Channel
		$('#channelPage').show();
		facebookChannel();
		//Twitter Channel
		// twitterChannel();	
	}
	
	function twitterChannel(){
		smac_api(smac_api_url+'?method=dashboard&action=twitter_channels&from='+startDate+'&to='+endDate+'',function(dataCollection){
				
				twitterID = dataCollection.data[0];
				
				try{
				//Load All Topic
				var str='';
				$.each(dataCollection.data, function(k,v){
					str+='<a href="#" onclick="twitterChannelTopic(\''+v+'\');myChannelTwitterPost(\''+v+'\', 0);return false;">'+v+'</a>';
				});
				$('#listTwitID').html(str);
				}catch(e){
				}
				
				//Twitter Channel Stats
				twitterChannelTopic(twitterID);
				
				//Twitter Post
				myChannelTwitterPost(twitterID, 0);
		});
	}
	
	function twitterChannelTopic(twitterID){
	
		$("#myChannelTwitID").html(''+twitterID+'');
	
		smac_api(smac_api_url+'?method=dashboard&action=twitter_channel_stats&twitter_id='+twitterID+'&from='+startDate+'&to='+endDate+'',function(dataCollection){
				
			//Global
			channelDataCollectionTwit = dataCollection.data;
			
			//Summary
				//Check Val
				var mention = channelDataCollectionTwit.summary.mention;
					if(mention == null)mention=0;
				var RT = channelDataCollectionTwit.summary.rt;
					if(RT == null)RT=0;
				var newFollowers = channelDataCollectionTwit.summary.new_followers;
					if(newFollowers == null)newFollowers=0;
				var unFollows = channelDataCollectionTwit.summary.unfollows;
					if(unFollows == null)unFollows=0;
			
			$("#myChannelTwitMention, #myChannelTwitRT, #myChannelTwitNewFollowers, #myChannelTwitUnfollows").attr({'class':'theTolltip','style' : 'cursor: pointer;'});

			$("#myChannelTwitMention").html(smac_number2(mention));
			$("#myChannelTwitMention").attr('title', number_format(mention));
			$("#myChannelTwitRT").html(smac_number2(RT));
			$("#myChannelTwitRT").attr('title', number_format(RT));
			$("#myChannelTwitNewFollowers").html(smac_number2(newFollowers));
			$("#myChannelTwitNewFollowers").attr('title', number_format(newFollowers));
			$("#myChannelTwitUnfollows").html(smac_number2(unFollows));
			$("#myChannelTwitUnfollows").attr('title', number_format(unFollows));
			
			//Linechart
			try{
				// $('#noDashboardDataPage').hide();
				if(mention != 0){
					$('#noDashboardDataPage').hide();
					var category = new Array();
					var impresi_data = new Array();
					var mention_data = new Array();
					var rt_data = new Array();
					$.each(channelDataCollectionTwit.daily_volume, function(k, v) {
						var month = (v.dtpost).substr(5,2);
						var tgl = (v.dtpost).substr(8,2);
						category.push(tgl+"/"+month);
						impresi_data.push(parseInt(v.impression));			
						mention_data.push(parseInt(v.mention));			
						rt_data.push(parseInt(v.rt));			
					});
					
					var data = [{
							name: 'Impression',
							data: impresi_data,
						},{
							name: 'Mention',
							data: mention_data
						},{
							name: 'Retweet',
							data: rt_data
						}];

					lineChartMini('myTwitterChart', category, data, true);
				}else{
					$('.pageContent').hide();
					$('#noDashboardDataPage').show();
				}
			}catch(e){
				$('.pageContent').hide();
				$('#noDashboardDataPage').show();
			}
		});
	}
	
	function facebookChannel(){
		smac_api(smac_api_url+'?method=dashboard&action=fb_channels&from='+startDate+'&to='+endDate+'',function(dataCollection){
				
				fbID = dataCollection.data[0].id;
				fbName = dataCollection.data[0].name;
				
				//Load All Topic
				var str='';
				$.each(dataCollection.data, function(k,v){
					str+='<a href="#" onclick="facebookChannelTopic(\''+v.id+'\', \''+v.name+'\');return false;">'+v.name+'</a>';
				});
				$('#listFBID').html(str);
				
				facebookChannelTopic(fbID, fbName);
		});
	}
	
	function facebookChannelTopic(fbID, fbName){
		$("#myChannelFBID").html(fbName);
		
		smac_api(smac_api_url+'?method=dashboard&action=fb_channel_stats&fb_id='+fbID+'&from='+startDate+'&to='+endDate+'',function(dataCollection){
			//Global
			channelDataCollectionFB = dataCollection.data;
			
			var dataLength = parseInt(dataCollection.data.summary.mention);
			if(dataLength > 0){
				
				$('#colTwitter').addClass('w480');
				$('#colTwitter').removeAttr('style');
				$('#myTwitterChart').css('width','440px');
				$('#colTwitter .list-box div:eq(2)').addClass('firstBox');
				$('#colFacebook').show();
				//Summary
					//Check Val
					var post = dataCollection.data.summary.mention;
						if(post == null)post=0;
					var likes = dataCollection.data.summary.likes;
						if(likes == null)likes=0;
					var newLikes = dataCollection.data.summary.new_like;
						if(newLikes == null)newLikes=0;
					var uLike = dataCollection.data.summary.unlike;						
						if(uLike == null)uLike=0;
				
				$("#myChannelFBPosts, #myChannelFBLikes, #myChannelFBNewlikes, #myChannelFBUnlike").attr({'class':'theTolltip','style' : 'cursor: pointer;'});

				$("#myChannelFBPosts").html(smac_number2(post));
				$("#myChannelFBPosts").attr('title', number_format(post));
				$("#myChannelFBLikes").html(smac_number2(likes));
				$("#myChannelFBLikes").attr('title', number_format(likes));
				$("#myChannelFBNewlikes").html(smac_number2(newLikes));
				$("#myChannelFBNewlikes").attr('title', number_format(newLikes));
				$("#myChannelFBUnlike").html(smac_number2(uLike));
				$("#myChannelFBUnlike").attr('title', number_format(uLike));
				
				if(post != 0){
					//Linechart
					var category = new Array();
					var mention_data = new Array();

					$.each(channelDataCollectionFB.daily_volume, function(k, v) {
						var month = (v.dtpost).substr(5,2);
						var tgl = (v.dtpost).substr(8,2);
						category.push(tgl+"/"+month);			
						mention_data.push(parseInt(v.mention));			
					});
					
					var data = [{
							name: 'Post',
							data: mention_data
						}];

					lineChartMini('myFacebookChart', category, data, true);
				}else{
					$('#myFacebookChart').html('<span style="color: #333333;">No data for this channel</span>');
				}
				twitterChannel();
			}else{
				$('#colFacebook').hide();
				$('#colTwitter').removeClass('w480');
				$('#colTwitter').css('width', '975px');
				$('#myTwitterChart').css('width','935px');
				$('#colTwitter .list-box div:eq(2)').removeClass('firstBox');
				twitterChannel();	
			}
		});
	}
	
	function myChannelTwitterPost(twID, page){
		$('#allPostnav2 h1 span').removeClass('active');
		$('#allPostnav2 .navTwitter').addClass('active');
		smacLoader('twitter-topconv', 'loader-med', 'Top Twitter Post');
		smac_api(smac_api_url+'?method=dashboard&action=twitter_channel_posts&twitter_id='+twitterID+'&start='+page+'&from='+startDate+'&to='+endDate+'',function(dataCollection){
			try{
				var str='';
				$.each(dataCollection.data.feeds, function(k, v){
					str+='<div class="list">';
					str+='<div class="smallthumb"><a href="#" onclick="twitterPopup(\''+v.author_id+'\', \''+v.author_name+'\'); return false;" rel="profile"><img src="'+v.author_avatar+'"></a> </div>';
					str+='<div class="entry">';
					str+='<h3>'+v.author_name+'  (@'+v.author_id+')</h3>';
					str+='<a title="Retweet Frequency  ('+number_format(v.rt_total)+')" class="icon-rts theTolltip">'+smac_number2(parseInt(v.rt_total))+'</a>';
					str+='<a title="Total Impressions  ('+number_format(v.impression)+')" class="icon-imp theTolltip">'+smac_number2(parseInt(v.impression))+'</a>';
					str+='<a title="Share" class="icon-share theTolltip">'+v.share+'%</a>';
					str+='<span class="date">'+date('d/m/Y H:i',strtotime(v.published_datetime))+'</span>'; 
					str+='</div> <!-- .entry -->';
					str+='<div class="entry-action" style="position:relative;">'; 
					str+='<a href="#"><span class="blackberry">&nbsp;</span></a>'; 
					str+='<a href="#"><span class="apple">&nbsp;</span></a>'; 
					str+='<a href="#"><span class="android">&nbsp;</span></a>'; 

					str+='<span style="font-size: 14px;left: 65px;position: absolute;top: -5px;">'+v.content+'</span>';
					if(v.flag != '1'){
					str+='<a id="mt'+v.feed_id+'" title="Move to Folder" href="#" class="reply theTolltip" onclick="openFolderList(\''+v.feed_id+'\');return false;">&nbsp;</a>';
					str+=moveFolderList('#mt'+v.feed_id,v.feed_id,'1');
					}else{
						str+='<a style="float: right;margin: 0 9px 0 0;"><img src="images/icon_centang.png" width="24px" height="27px"/></a>';
					}
					str+='</div><!-- .entry-action -->';
					str+='</div>';
				});
				
				$("#twitter-topconv").html(str);		
			}catch(e){
				$("#twitter-topconv").html('<span style="color: #333333;">No data for this channel</span>');
			}
		});
	}
	
	function myChannelFBPost(facebookID, page){
		$('#allPostnav2 h1 span').removeClass('active');
		$('#allPostnav2 .navFacebook').addClass('active');
		facebookID = fbID;
		smacLoader('twitter-topconv', 'loader-med', 'Top Facebook Post');
		smac_api(smac_api_url+'?method=dashboard&action=fb_channel_posts&fb_id='+facebookID+'&start='+page+'',function(dataCollection){
			try{
				var str='';
				$.each(dataCollection.data.feeds, function(k, v){
					str+='<div class="list">';
					str+='<div class="smallthumb"><a href="http://www.facebook.com/'+v.from_object_id+'" rel="profile" target="_blank"><img src="http://graph.facebook.com/'+v.from_object_id+'/picture"></a> </div>';
					str+='<div class="entry">';
					str+='<h3>'+v.from_object_name+'</h3>';
					str+='<a title="Total Like  ('+number_format(v.likes_count)+')" class="like theTolltip">'+smac_number2(parseInt(v.likes_count))+'</a>';
					str+='<span class="date">'+date('d/m/Y H:i',strtotime(v.created_time))+'</span>'; 
					str+='</div> <!-- .entry -->';
					str+='<div class="entry-action" style="position:relative;">'; 
					str+='<a href="#"><span class="blackberry">&nbsp;</span></a>'; 
					str+='<a href="#"><span class="apple">&nbsp;</span></a>'; 
					str+='<a href="#"><span class="android">&nbsp;</span></a>'; 

					str+='<span style="font-size: 14px;left: 65px;position: absolute;top: -5px;">'+v.message+'</span>';
					if(v.flag != '1'){
					str+='<a id="mf'+v.feed_id+'" title="Move to Folder" href="#" class="reply theTolltip" onclick="openFolderList(\''+v.feed_id+'\');return false;">&nbsp;</a>';
					str+=moveFolderList('#mf'+v.feed_id,v.feed_id,'1');
					}else{
						str+='<a style="float: right;margin: 0 9px 0 0;"><img src="images/icon_centang.png" width="24px" height="27px"/></a>';
					}
					str+='</div><!-- .entry-action -->';
					str+='</div>';
				});
				
				$("#twitter-topconv").html(str);
			}catch(e){
				$("#twitter-topconv").html('<span style="color: #333333;">No data for this channel</span>');
			}
		});
	}
	
	//Date Filter
	$('#smacDateFilter').live('submit', function(event) {
		event.preventDefault();
		
		var tempStart = $('input[name="dt_from"]').val();
		var tempEnd = $('input[name="dt_to"]').val();
		startDate = date_dmySlash_to_ymdDash(tempStart);
		endDate = date_dmySlash_to_ymdDash(tempEnd);
		
		if(prevStartDate != startDate || prevEndDate != endDate){
			switch(actionPage){
				case 'summayPage':
				post_count('dashboardSummary');
				break;
				case 'twitterPage':
				post_count('dashboardTwitter');
				break;
				case 'facebookPage':
				post_count('dashboardFacebook');
				break;
				case 'webPage':
				webTybe=1;
				post_count('dashboardWeb', 'web');
				break;
				case 'forumPage':
				webTybe=2;
				post_count('dashboardWeb', 'forum');
				break;
				case 'newsPage':
				webTybe=3;
				post_count('dashboardWeb', 'news');
				break;
				case 'ecommercePage':
				webTybe=5;
				post_count('dashboardWeb', 'ecommerce');
				break;
				case 'corporatePage':
				webTybe=0;
				post_count('dashboardWeb', 'corporate');
				break;
				case 'channelPage':
				post_count('dashboardChannel');
				break;
				case 'videoPage':
				post_count('dashboardVideo');
				break;
				default:
				post_count('dashboardSummary');
			}	
		}
	});
	
	//Summary Data
	function dashboardSummary(){						
		$('#summayPage').show();
		smac_api(smac_api_url+'?method=dashboard&action=summary&from='+startDate+'&to='+endDate+'',function(dataCollection){
			sumCount = dataCollection.data.summary.mentions.value;
			if(sumCount != null){
				$('#noDashboardDataPage').hide();
				if(sumCount != 0){
					//Store Data to Global variable
					sumDataCollection = dataCollection.data;
					sumVolbyImpression = dataCollection.data.daily_volume_by_impression;
					sumVolbyMention = dataCollection.data.daily_volume_by_mention;
					sumVolbyPositive = dataCollection.data.daily_volume_by_positive_sentiment;
					sumVolbyNegative = dataCollection.data.daily_volume_by_negative_sentiment;
					
					//Share of Voice (Pie chart)
					var shareOfVoice = dataCollection.data.share_of_voice;
					if (shareOfVoice.length > 1){
						pieChartData('sumShareofVoice', dataCollection.data.share_of_voice);
					}else{
						$('#pieChart1').css({
									display: 'block', 
									height: '411px'
								});
						$('#pieChart1 .titles').hide();
						$('#pieChart1 .bgGreys').hide();
						$('#sumChart').css('width', '943px');
						$('#sumList').css('width', '975px');
						$('#sumVolumebySentiment').css('width', '942px');
						$('#sumPII, #sumTM, #sumPI').css('width', '284px');
					}
					
					//update datefilter widget automagically
					update_date_filter(sumVolbyMention);
					
					//All Channel Performance (initialize - volume by sentiment)
					allChannelPerformance("sumVolbyMention");
						
					
					//Sentiment All Channels (Pie chart)
					pieChartData('sumSentimentAllChannels', dataCollection.data.sentiment_all_channels, 'sentiment');
					
					//Summary Data
						//PII
						miniBox(sumDataCollection.summary.people, 'sumPII', true);
						
						//Total Mention
						miniBox(sumDataCollection.summary.mentions, 'sumTM', true);

						//Potential Impressions
						miniBox(sumDataCollection.summary.potential_impression, 'sumPI', true);
					
					//Top Wordcloud
					$("#sumTopWords").html(dashboardWordcloud(sumDataCollection.top_keywords,0));
					
					//Top People
					try{
						var peopleSumLength = dataCollection.data.top_kol;
						if(peopleSumLength.length > 0){
							var str="";
							$.each(dataCollection.data.top_kol, function(k, v) {
								str+='<div class="list">';
								str+='<div class="smallthumb">';
								str+='<a href="#" onclick="twitterPopup(\''+v.author_id+'\', \''+v.author_name+'\'); return false;" rel="profile"><img src="'+v.author_avatar+'" /></a>';
								str+='</div>';
								str+='<div class="entry">';
								str+='<h3>'+v.author_name+'</h3>';
								str+='<a class="icon-rts theTolltip" title="Retweet Frequency ('+number_format(v.rt)+')">'+smac_number2(v.rt)+'</a>';
								str+='<a class="icon-imp theTolltip" title="Total Impressions ('+number_format(v.impression)+')">'+smac_number2(v.impression)+'</a>';
								str+='<a class="icon-share theTolltip" title="Share">'+roundNumber(v.share, 2)+'%</a>';
								str+='</div>';
								str+='</div>';
							});
							$("#sumTopPeople").html(str);
						}else{
							$("#sumTopPeople").html("No data available");
						}
					}catch(e){
						$("#sumTopPeople").html("No data available");
					}
				}
			}else{
				// $("#sumTopPeople, #sumTopWords, #sumSentimentAllChannels, #sumVolumebySentiment, #sumShareofVoice").html('<span style="color: #333333;">No data available</span>');
				// $("#sumPII h1, #sumTM h1, #sumPI h1").html("0");
				$('.pageContent').hide();
				$('#noDashboardDataPage').show();
			}
		});
		//Top Post
		smac_api(smac_api_url+'?method=dashboard&action=summary_top_posts&from='+startDate+'&to='+endDate+'',function(dataCollection){
			var cekLength = dataCollection.data;
			try{
				if(cekLength.length != 0){
					$("#sumTopPost").html(topPostSummary(dataCollection));
				}else{
					$("#sumTopPost").html("No data available");
				}
			}catch(e){
				$("#sumTopPost").html("No data available");
			}
		});
		
	}
	
	//Twitter Data
	function dashboardTwitter(){
		if(twitCount !=0){
			$('#noDashboardDataPage').hide();
			$('#twitterPage').show();
			
			smac_api(smac_api_url+'?method=dashboard&action=twitter&from='+startDate+'&to='+endDate+'',function(dataCollection){
				
				//Store data
				twitDataCollection = dataCollection.data;
				twitVolbyImpression = dataCollection.data.daily_volume_by_impression;
				twitVolbyMention = dataCollection.data.daily_volume_by_mention;
				twitVolbySentiment2 = dataCollection.data.daily_volume_by_sentiment;
				twitVolbyQuadrant = dataCollection.data.quadrant;
				twitVolbyInteraction = dataCollection.data.interaction;
				
				
				//update datefilter widget automagically
				update_date_filter(twitVolbyMention);
				//Twitter Quadrant
				twitterQuadrant('twitDailyVolume', twitVolbyQuadrant);
				// channelPerformance('twitDailyVolume', 'twitVolbyImpression', 'Twitter');
				
				//Summary Data
					//Total Post
					miniBox(twitDataCollection.summary.total_posts, 'twitTotalPost', true);
					
					//Original Post
					miniBox(twitDataCollection.summary.original_posts, 'twitOriginalPost', true);
					
					//Retweet
					miniBox(twitDataCollection.summary.rt, 'twitRetweet', true);
					
					//Potential Impression
					miniBox(twitDataCollection.summary.potential_impression, 'twitPotentialImpression', true);
					
					//Potential Impression
					miniBox(twitDataCollection.summary.people, 'twitPeople', true);
				
				//Sentiment (Pie chart)
				pieChartData('twitSentiment', twitDataCollection.sentiment, 'sentiment');
				
				//Top Wordcloud
				$("#twitTopWords").html(dashboardWordcloud(twitDataCollection.top_keywords,1));
				
				//Top People
				var str="";
				$.each(twitDataCollection.top_kol, function(key, val) {
					str+='<div class="list">';
					str+='<div class="smallthumb">';
					str+='<a rel="profile" href="#" onclick="twitterPopup(\''+val.author_id+'\', \''+val.author_name+'\'); return false;"><img src="'+val.author_avatar+'"></a>';
					str+='</div>';
					str+='<div class="entry">';
					str+='<h3>'+val.author_name+'</h3>';
					str+='<a class="icon-rts theTolltip" title="Retweet Frequency ('+number_format(val.rt)+')">'+smac_number2(val.rt)+'</a>';
					str+='<a class="icon-imp theTolltip" title="Total Impressions ('+number_format(val.impression)+')">'+smac_number2(val.impression)+'</a>';
					str+='<a class="icon-share theTolltip" title="Share">'+roundNumber(val.share, 2)+'%</a>';
					// str+='<a class="reply theTolltip"title="Mark for Reply" onclick="" href="#">&nbsp;</a>';
					str+='</div>';
					str+='</div>';
				});
				$("#twitTopPeople").html(str);
			});
			
			//Top Post
			smac_api(smac_api_url+'?method=dashboard&action=twitter_top_posts&from='+startDate+'&to='+endDate+'',function(dataCollection){
				$("#twitTopPost").html(topPostTwitter(dataCollection));
			});
		}else{
			// $('#twitDailyVolume, #twitSentiment, #twitTopWords, #twitTopPeople, #twitTopPost').html('<span style="color: #333333;">No data for this channel</span>');
			// $('#twitTotalPost h1, #twitOriginalPost h1, #twitRetweet h1, #twitPotentialImpression h1, #twitPeople h1').html(0);
			$('.pageContent').hide();
			$('#noDashboardDataPage').delay(1000).show();
		}
	}
	
	//Facebook Data
	function dashboardFacebook(){
		if(fbCount != 0){
			$('#noDashboardDataPage').hide();
			$('#facebookPage').show();

			smac_api(smac_api_url+'?method=dashboard&action=fb&from='+startDate+'&to='+endDate+'',function(dataCollection){
					
				//Store Data to Global variable
				fbDataCollection = dataCollection.data;
				fbVolbyImpression = dataCollection.data.daily_volume_by_impression;
				fbVolbyMention = dataCollection.data.daily_volume_by_mention;
				
				//update datefilter widget automagically
				update_date_filter(fbVolbyMention);
				
				//FB Channel Performance
				channelPerformance('fbDailyVolume', 'fbVolbyMention', 'Facebook');
				
				//Summary Data
					//Total Post
					miniBox(fbDataCollection.summary.total_posts, 'fbTotalPost', true);
					
					//Likes
					miniBox(fbDataCollection.summary.total_likes, 'fbLikes', true);
					
					//People
					miniBox(fbDataCollection.summary.people, 'fbPeople', true);
				
				//Top Wordcloud
				$("#fbTopWords").html(dashboardWordcloud(fbDataCollection.top_keywords,2));
				
				//Top People
				var str="";
				$.each(fbDataCollection.top_kol, function(key, val) {
					str+='<div class="list">';
					str+='<div class="smallthumb">';
					str+='<a rel="profile" class="poplight" target="_blank" href="http://www.facebook.com/'+val.author_id+'"><img src="https://graph.facebook.com/'+val.author_id+'/picture"></a>';
					str+='</div>';
					str+='<div class="entry">';
					str+='<h3>'+val.author_name+'</h3>';
					str+='<a class="icon-posts theTolltip" title="Total Post ('+number_format(val.mentions)+')">'+smac_number2(val.mentions)+'</a>';
					str+='<a class="icon-likes theTolltip" title="Total Likes ('+number_format(val.total)+')">'+smac_number2(val.total)+'</a>';
					// str+='<a class="reply theTolltip"title="Mark for Reply" onclick="" href="#">&nbsp;</a>';
					str+='</div>';
					str+='</div>';
				});
				$("#fbTopPeople").html(str);
			});
			
			//Top Post
			smac_api(smac_api_url+'?method=dashboard&action=fb_top_posts&from='+startDate+'&to='+endDate+'',function(dataCollection){
				$("#fbTopPost").html(topPostFB(dataCollection));
			});
		}else{
			// $('#fbDailyVolume, #fbTopWords, #fbTopPeople, #fbTopPost').html('<span style="color: #333333;">No data for this channel</span>');
			// $('#fbTotalPost h1, #fbLikes h1, #fbPeople h1').html(0);
			$('.pageContent').hide();
			$('#noDashboardDataPage').delay(1000).show();
		}
	}
	
	//Web Data
	function dashboardWeb(tabWeb){
		var counterWeb;
		var group_type
		switch(tabWeb){
			case 'web':
				counterWeb = blogCount;
				group_type = 1;
				break;
			case 'forum':
				counterWeb = forumCount;
				group_type = 2;
				break;
			case 'news':
				counterWeb = newsCount;
				group_type = 3;
				break;
			case 'ecommerce':
				counterWeb = ecommerceCount;
				group_type = 5;
				break;
			case 'corporate':
				counterWeb = corporateCount;
				group_type = 0;
				break;
			default:
				counterWeb = blogCount;
		}
		if(counterWeb != 0){
			$('#noDashboardDataPage').hide();
			$('#'+tabWeb+'Page').show();
			
			smac_api(smac_api_url+'?method=dashboard&action=site&from='+startDate+'&to='+endDate+'&type='+webType+'',function(dataCollection){
				
				//Store Data to Global variable
				webDataCollection = dataCollection.data;
				webVolbyImpression = dataCollection.data.daily_volume_by_impression;
				webVolbyMention = dataCollection.data.daily_volume_by_mention;
				
				//update datefilter widget automagically
				update_date_filter(webVolbyMention);
				
				//Other Channel Performance
				channelPerformance(tabWeb+'DailyVolume', tabWeb+'VolbyMention', 'Web', tabWeb);
				
				//Summary Data
					//Total Post
					miniBox(webDataCollection.summary.total_posts, tabWeb+'TotalPost', true);
					
					//Likes
					miniBox(webDataCollection.summary.websites, tabWeb+'Likes', true);
				
				//Top Wordcloud
				$("#"+tabWeb+"TopWords").html(dashboardWordcloud(webDataCollection.top_keywords,3,group_type));
				
				//Top People
				try{
					if(webDataCollection.top_websites != null){
						var str="";
						$.each(webDataCollection.top_websites, function(key, val) {
							str+='<div class="list">';
							str+='<div class="smallthumb">';
							str+='<a rel="profile" class="poplight" target="_blank" style="background:white;" href="'+val.author_uri+'"><img src="images/iconWeb2.png"></a>';
							str+='</div>';
							str+='<div class="entry">';
							str+='<h3>'+val.author_name+'</h3>';
							str+='<a class="icon-posts theTolltip" title="Total ('+number_format(val.total)+')">'+smac_number2(val.total)+'</a>';
							// str+='<a class="reply theTolltip"title="Mark for Reply" onclick="" href="#">&nbsp;</a>';
							str+='</div>';
							str+='</div>';
						});
						$("#"+tabWeb+"TopPeople").html(str);
					}else{
						$("#"+tabWeb+"TopPeople").html('<span style="color: #333333;">No data for this channel</span>');
					}
				}catch(e){$("#"+tabWeb+"TopPeople").html('<span style="color: #333333;">No data for this channel</span>');}
					
			});
			
			//Top Post
			smac_api(smac_api_url+'?method=dashboard&action=site_top_posts&from='+startDate+'&to='+endDate+'&type='+webType+'',function(dataCollection){
				$("#"+tabWeb+"TopPost").html(topPostWeb(dataCollection));
			});
		}else{
			$('.pageContent').hide();
			$('#noDashboardDataPage').delay(1000).show();
		}
	}
	//Video Data
	function dashboardVideo(){
		if(videoCount != 0){
			$('#noDashboardDataPage').hide();
			$('#videoPage').show();
			
			smac_api(smac_api_url+'?method=dashboard&action=video&from='+startDate+'&to='+endDate+'',function(dataCollection){
				
				//Store Data to Global variable
				videoDataCollection = dataCollection.data;
				try{
					videoVolbyImpression = dataCollection.data.daily_volume_by_impression;
					videoVolbyMention = dataCollection.data.daily_volume_by_mention;
					videoVolbyLike = dataCollection.data.daily_volume_by_like;
					videoVolbyDislike = dataCollection.data.daily_volume_by_dislike;
				}catch(e){}

				
				//update datefilter widget automagically
				update_date_filter(videoVolbyMention);
				
				//Other Channel Performance
				try{
					channelPerformance('videoDailyVolume', 'videoVolbyMention', 'Video');
				
				//Summary Data
					//Total Post
					miniBox(videoDataCollection.summary.total_video, 'videoTotalPost', true);
					
					//Likes
					miniBox(videoDataCollection.summary.total_people, 'videoTotalPeople', true);
				}catch(e){
					console.log(e.message);
				}
				//Top Wordcloud
				$("#videoTopWords").html(dashboardWordcloud(videoDataCollection.top_keywords,3));
				
				//Top People
				try{
					if(videoDataCollection.top_people != null){
						var str="";
						$.each(videoDataCollection.top_people, function(key, val) {
							str+='<div class="list">';
							str+='<div class="smallthumb" style="34px;">';
							str+='<a class="poplight" style="background:white;" href="#play_video/'+urlencode(val.video_url)+'/0"><img src="'+val.preview_url+'"></a>';
							str+='</div>';
							str+='<div class="entry">';
							str+='<h3>'+val.author_id+'</h3>';
							str+='<a class="icon-imp theTolltip" title="Total Views ('+number_format(val.views)+')">'+smac_number2(val.views)+'</a>';
							str+='</div>';
							str+='</div>';
						});
						$("#videoTopPeople").html(str);
					}else{
						$("#videoTopPeople").html('<span style="color: #333333;">No data for this channel</span>');
					}
				}catch(e){$("#videoTopPeople").html('<span style="color: #333333;">No data for this channel</span>');}
					
			});
			
			//Top Post
			smac_api(smac_api_url+'?method=dashboard&action=video_top_posts&from='+startDate+'&to='+endDate+'',function(dataCollection){
				$("#videoTopPost").html(topPostVideo(dataCollection));
			});
		}else{
			// $('#webDailyVolume, #webTopWords, #webTopPeople, #webTopPost').html('<span style="color: #333333;">No data for this channel</span>');
			// $('#webTotalPost h1, #webLikes h1').html(0);
			$('.pageContent').hide();
			$('#noDashboardDataPage').delay(1000).show();
		}
	}
	/**
	 * resetting start and end date for datefilter widget based on the daily data
	 */
	function update_date_filter(data){
		
		try{
			var start_date = "";
			var end_date = "";
			if(data.twitter){
				
				for(var i in data.twitter[0]){
					start_date = i;
				}
				for(var i in data.twitter[data.twitter.length-1]){
					end_date = i;
				}
				
				$('input[name="dt_from"]').val(date("d/m/Y",strtotime(start_date)));
				$('input[name="dt_to"]').val(date("d/m/Y",strtotime(end_date)));
			}else{			
				for(var i in data){
					if(start_date==""){
						start_date = i;
					}
				}
				end_date = i;
				
				$('input[name="dt_from"]').val(date("d/m/Y",strtotime(start_date)));
				$('input[name="dt_to"]').val(date("d/m/Y",strtotime(end_date)));
			}
		}catch(e){}
	}

	function post_count(tabMenu, tabWeb){
		//Loader
		switch(tabMenu){
			case 'dashboardSummary':
				smacLoader('sumVolumebySentiment', 'loader-med', 'Channel Performance');
				smacLoader('sumShareofVoice', 'loader-med', 'Share of Voice');
				smacLoader('sumSentimentAllChannels', 'loader-med', 'Sentiment All Channels');
				smacLoader('sumTopWords', 'loader-med', 'Top Wordcloud');
				smacLoader('sumTopPeople', 'loader-med', 'Top People');
				smacLoader('sumTopPost', 'loader-med', 'Top Post');
			break;
			case 'dashboardTwitter':
				smacLoader('twitDailyVolume', 'loader-med', 'Volume');
				smacLoader('twitSentiment', 'loader-med', 'Sentiment');
				smacLoader('twitTopWords', 'loader-med', 'Top Wordcloud');
				smacLoader('twitTopPeople', 'loader-med', 'Top People');
				smacLoader('twitTopPost', 'loader-med', 'Top Post');
				break;
			case 'dashboardFacebook':
				smacLoader('fbDailyVolume', 'loader-med', 'Facebook Performance');
				smacLoader('fbTopWords', 'loader-med', 'Top Keywords');
				smacLoader('fbTopPeople', 'loader-med', 'Top People');
				smacLoader('fbTopPost', 'loader-med', 'Top Post');			
			break;
			case 'dashboardWeb':
				smacLoader(tabWeb+'DailyVolume', 'loader-med', 'Web Performance');
				smacLoader(tabWeb+'TopWords', 'loader-med', 'Top Keywords');
				smacLoader(tabWeb+'TopPeople', 'loader-med', 'Top People');
				smacLoader(tabWeb+'TopPost', 'loader-med', 'Top Post');		
			break;
			case 'dashboardVideo':
				smacLoader('videoDailyVolume', 'loader-med', 'Video Performance');
				smacLoader('videoTopWords', 'loader-med', 'Top Keywords');
				smacLoader('videoTopPeople', 'loader-med', 'Top People');
				smacLoader('videoTopPost', 'loader-med', 'Top Post');		
			break;
			default:
				smacLoader('sumVolumebySentiment', 'loader-med', 'Channel Performance');
				smacLoader('sumShareofVoice', 'loader-med', 'Share of Voice');
				smacLoader('sumSentimentAllChannels', 'loader-med', 'Sentiment All Channels');
				smacLoader('sumTopWords', 'loader-med', 'Top Wordcloud');
				smacLoader('sumTopPeople', 'loader-med', 'Top People');
				smacLoader('sumTopPost', 'loader-med', 'Top Post');
		}
		
		smac_api(smac_api_url+'?method=dashboard&action=post_count&from='+startDate+'&to='+endDate+'',function(dataCollection){
			
			twitCount = number_format(parseInt(dataCollection.data.twitter));
			fbCount = number_format(parseInt(dataCollection.data.fb));
			webCount = number_format(parseInt(dataCollection.data.web));
			blogCount = number_format(parseInt(dataCollection.data.blog));
			forumCount = number_format(parseInt(dataCollection.data.forum));
			newsCount = number_format(parseInt(dataCollection.data.news));
			corporateCount = number_format(parseInt(dataCollection.data.common));
			ecommerceCount = number_format(parseInt(dataCollection.data.ecommerce));
			videoCount = number_format(parseInt(dataCollection.data.video));
			
			$('a#buttonTwitter').attr('title', 'Twitter ('+twitCount+')');
			$('a#buttonFacebook').attr('title', 'Facebook ('+fbCount+')');
			$('a#buttonWeb').attr('title', 'Blog ('+blogCount+')');
			$('a#buttonForum').attr('title', 'Forum ('+forumCount+')');
			$('a#buttonNews').attr('title', 'News ('+newsCount+')');
			$('a#buttonCorporate').attr('title', 'Corporate/Personal ('+corporateCount+')');
			$('a#buttonEcommerce').attr('title', 'Ecommerce ('+ecommerceCount+')');
			$('a#buttonVideo').attr('title', 'Video ('+videoCount+')');
			
			switch(tabMenu){
				case 'dashboardSummary':
					dashboardSummary();
				break;
				case 'dashboardTwitter':
					dashboardTwitter();
					break;
				case 'dashboardFacebook':
					dashboardFacebook();
				break;
				case 'dashboardWeb':
					dashboardWeb(tabWeb);
				break;
				case 'dashboardVideo':
					dashboardVideo();
				break;
				case 'dashboardChannel':
					dashboardChannel();
				break;
				default:
					dashboardSummary();
			}
		});
	}
	
	
	function videoFeeds(id, page){
		$("body,html").animate({scrollTop:0}, 500);
		if(page == 0){
			if(globalPageInit == 0){
				$('#master').append('<div id="fade" style="display: block;"></div>');		
				$("#popup-sentiment").prepend('<a class="close2" href="#videoPage"><img style="margin: -20px -33px 0 615px;" alt="Close" title="Close Window" class="btn_close" src="images/close.png"></a>');
				$("#popup-sentiment").fadeIn();
				$("#popup-sentiment .headpopup h1").html('Video Comments');
				globalPageInit = 1;
			}
		}
		smacLoader('sentimentPopup', 'loader-med', 'Video Comments');
		smac_api(smac_api_url+'?method=feeds&action=video_comments&video_id='+id+'&start='+page+'',function(dataCollection){
			var dataLength = dataCollection.data.feeds;
			if(dataLength.length > 0){
				var list = '';
				$.each(dataLength, function(k,v){		
					list += conversationList('images/iconVideo.png', v.name, v.txt, date('d/m/Y', strtotime(v.published)),"#");
				});
				$("#popup-sentiment .content-popup").html(list);
				if(page == 0){
					page=1;
					smacPagination(dataCollection.data.total_rows, page, 'sentimentPopupPaging', id, 'videoFeeds', 20);
				}
			}else{
				$('#sentimentPopup').html("No data available");
				$('#sentimentPopupPaging').hide();
			}
		});
	}
	
	$('a.close2, a#popupmsgbtn2').live('click', function(){
		// $("body,html").animate({scrollTop:0}, 500);
		$('#popup-video').fadeOut();
		globalPageInit = 0;
		$('#fade , .popup_block').fadeOut();
	});