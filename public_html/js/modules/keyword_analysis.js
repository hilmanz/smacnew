// :: Keyword Analysis ::
// :: @kia ::

	//Global Variable
	var ruleInit = 0;
	var totalVolumeInit = 0;
	var channelData, sentimentData, ruleData, keywordData;
	var startDate,prevStartDate, endDate, prevEndDate, typeFilter, startPage;
	var viewAllBreak;
	var pageInitRule = 0;
	var pageInitSentiment = 0;
	var pageInitResponse = 0;
	var pageInitSentimentPopup = 0;
	var keywordInitRule;
	var editSentimentShow = false;
	var dataTableInit = 0;
	var conversationType = 'conversation_by_date';
	var sentimentType = 'sentiment_tweet';
	var dtArr, sentimentDtArr;
	var websiteType, sentimentWebsiteType;
	
	$(document).ready(function(){
		Backbone.emulateHTTP = true;
		Backbone.emulateJSON = true;
		
		//initial main menu
		$('.analisis a').addClass('current');
		
		$('#show-editsentiment').click(function(){
			if( editSentimentShow ){
				$('#tabel-sentiment-block').hide();
				$('#tabel-tweets-block').fadeIn();
				editSentimentShow = false;
				$(this).html('Edit Sentiment');
			}else{
				$('#tabel-tweets-block').hide();
				$('#tabel-sentiment-block').fadeIn();
				editSentimentShow = true;
				$(this).html('Done Editing');
				
				if(dataTableInit == 0){
					
						//Data Table
						$('#tbl-sentiment').dataTable({
							 "bJQueryUI": true,
							 // "aaSorting": [[2,'desc']],
							 "aoColumns": [
								null,
								null,
								null,
								null,
								{ "bSortable": false }
							],
							 "sPaginationType": "full_numbers",
							 "bProcessing": true,
							 "bServerSide": true,
							 "sAjaxSource": smac_api_url+'?method=keyword&action=keyword_sentiment&campaign_id='+campaign_id+'&access_token='+access_token
							 // "sAjaxSource": smac_api_url+'?method=kol&action=twitter_all_people&campaign_id='+campaign_id+'&access_token='+access_token
							 });

							$("#tbl-sentiment").css("width","100%");
					// });
					dataTableInit = 1;
				}
			}
		});
		
		//Backbone Router
		var Router = Backbone.Router.extend({
			routes: {
				"workflow/:keyword/:type": "workflow",
				"workflow_gcs/:keyword/:type": "workflow_gcs",
				"select_channel/:index/:keyword": "select_channel",
				"*action" : "hashTagMenu",
			},
			workflow:function(keyword,type){
				open_workflow_popup(keyword,type,0);
			},
			workflow_gcs:function(keyword,type){
				open_workflow_popup(keyword,3,type);
			},
			select_channel:function(index, keyword){
				$('#cloudChannelList-'+index).html(addChannelList(keyword));
				$('#wordcloudChannel-'+index).fadeIn();
				document.location="#/";
			},
			hashTagMenu: function(action){
				if(action == 'tabTwitter' || action == ''){
					// tabCheck = 'twitter';
					// kolTabMenu('tabTwitter');
					if (ruleInit == 0){
						ruleInit = 1;
						
						//Initiation
							//Keyword Analysis Chart
							var tempStart = $('input[name="dt_from"]').val();
							var tempEnd = $('input[name="dt_to"]').val();
							startDate = date_dmySlash_to_ymdDash(tempStart);
							endDate = date_dmySlash_to_ymdDash(tempEnd);
							keywordAnalysisChart('summary_by_rule');
							kaResponseList('gakada', 0);
												
							//Get Rule Break Down
							prevStartDate = startDate;
							prevEndDate = endDate;
							breakdownRule();
							
							//Sentiment Conversation
							sentimentConversation('gakada', 0);
					}
				}
			}
		});
	
		var app_router = new Router;
		Backbone.history.start();
	});
	
	//Save Sentiment
	function sentimentSave(id){
		var sentimentVal = $('#val-'+id).val();
		
		smac_api(smac_api_url+'?method=keyword&action=update_keyword_sentiment&id='+id+'&value='+sentimentVal+'',function(dataCollection){
			$('#btn-'+id).show();
			$('#edit-'+id).hide();
			$('#w-'+id).html(sentimentVal);
		});
	}
	
	//Keyword Analysis Detail by function
	function kaDetails(keyID, index, bool){
		if (bool == true){
			$("#kaDefaultpage").hide();
			$("#kaDetailPage").fadeIn();
			
			//Rule Title
			$("#kaRuleTitle").html(ruleData[index]);
			
			//load Chart
			smacLoader('postChartDetail', 'loader-med', 'Post');
			
			//Chart
			pieChartMiniChannel('channelChartDetail', channelData[index], 150);
			pieChartMiniSentiment('sentimentChartDetail', sentimentData[index], 150);
			analysisLineChart('postChartDetail', keyID, ruleData[index]);
			
			
			//Top Keyword Tab List
			topKeywordList(keyID);			
			
			//default
			$(".tab_content3").hide(); //Hide all content
			$("ul.tabs3 li").removeClass("active"); //Remove All active class
			$("ul.tabs3 li:first").addClass("active"); //Activate first tab
			$(".tab_content3:first").show(); //Show first tab content
		}else{
			$("#kaDetailPage").hide();
			$("#kaDefaultpage").fadeIn();
			
			$(".tab_content").hide(); //Hide all content
			$("ul.tabs li").removeClass("active"); //Remove All active class
			$("ul.tabs li:first").addClass("active").show(); //Activate first tab
			$(".tab_content:first").show(); //Show first tab content
		}
	}
	
	//Keyword Analysis Detail by option menu
	$("#kaRuleMenu").live('change', function(){
		var kaRuleIDnIndex =($(this).val()).split("n");
		
		//Rule Title
		$("#kaRuleTitle").html(ruleData[kaRuleIDnIndex[1]]);
		
		//load Chart
		smacLoader('postChartDetail', 'loader-med', 'Post');
		
		//Chart
		pieChartMiniChannel('channelChartDetail', channelData[kaRuleIDnIndex[1]], 150);
		pieChartMiniSentiment('sentimentChartDetail', sentimentData[kaRuleIDnIndex[1]], 150);
		analysisLineChart('postChartDetail', kaRuleIDnIndex[0], ruleData[kaRuleIDnIndex[1]]);
		
		//Top Keyword Tab List
		topKeywordList(kaRuleIDnIndex[0]);
		
		//default
		$(".tab_content3").hide(); //Hide all content
		$("ul.tabs3 li").removeClass("active"); //Remove All active class
		$("ul.tabs3 li:first").addClass("active"); //Activate first tab
		$(".tab_content3:first").show(); //Show first tab content
	});
	
	function keywordAnalysisChart(actionFilter, type){
		//filter
		if(actionFilter == 'top')var top = '&type='+type;
		else var top = '';
		
		if(actionFilter == 'summary_by_rule' && totalVolumeInit == 0){
			totalVolumeInit = 1;
			$('#kaTabs').prepend('<li id="liTab-volume"><a href="#tab-volume" class="tab-volume">Total Volume Over Time</a></li>');
			$('#kaContainer').prepend('<div id="tab-volume" class="tab_content"><div style="min-width:710px;height:350px;" id="kaVolume"></div></div>');
			$(".tab_content").hide(); //Hide all content
			$("ul.tabs li").removeClass("active"); //Remove All active class
			$("ul.tabs li:first").addClass("active").show(); //Activate first tab
			$(".tab_content:first").show(); //Show first tab content
		}else if(actionFilter != 'summary_by_rule'){
			totalVolumeInit = 0;
			$('#liTab-volume').remove();
			$('#tab-volume').remove();
			$(".tab_content").hide(); //Hide all content
			$("ul.tabs li").removeClass("active"); //Remove All active class
			$("ul.tabs li:first").addClass("active").show(); //Activate first tab
			$(".tab_content:first").show(); //Show first tab content
		}
		
		//Loader
		smacLoader('kaVolume', 'loader-med', 'Volume');
		smacLoader('kaMention', 'loader-med', 'Mention');
		smacLoader('kaImpression', 'loader-med', 'Impression');
		smacLoader('kaRetweets', 'loader-med', 'Retweets');
		smacLoader('kaPII', 'loader-med', 'Potential Impact Index');
		smacLoader('kaInteraction', 'loader-med', 'Interaction Index');
		smacLoader('kaSentiment', 'loader-med', 'Sentiment');
		
		//Get Keyword Analysis Data
		smac_api(smac_api_url+'?method=keyword&action='+actionFilter+'&from='+startDate+'&to='+endDate+''+top+'',function(dataCollection){
			
			//Total Volume
			if(actionFilter == 'summary_by_rule'){
				try{
				var dataVolumeCollection = new Array();
				var ruleID = new Array();
				var category = new Array();		
				var categoryInit = 0;
				$.each(dataCollection.data.daily_volume, function(key, val) {
					var _data = new Array();
					$.each(val.data, function(k, v) {
						if(categoryInit == 0){
							// var month = (v.published_date).substr(5,2);
							// var tgl = (v.published_date).substr(8,2);
							// category.push(tgl+"/"+month);
							category.push(v.published_date);
						}
						_data.push(parseInt(v.total_mention));
					});
					
					
					var data = {
						name: val.keyword_id,
						data: _data
					};
					ruleID.push({id : val.keyword_id, keys : val.keyword});
					
					dataVolumeCollection.push(data);
					categoryInit = 1;
				});
				
				lineChartByRuleId('kaVolume', category, dataVolumeCollection, false,ruleID);	
				}catch(e){
					
				}
			}
			
			
			//Mentions
			try{
			var _category = new Array();
			var _data = new Array();
			$.each(dataCollection.data.post, function(key, value){
				_data.push(parseInt(value.total_mention));
				if(value.label == null){
					_category.push(stripslashes(value.keyword));
				}else{
					_category.push(stripslashes(value.label));
				}
			});
			var data = {
				name : 'Mentions',
				data : _data
			};
			
			horizontalBarChart('kaMention', _category, data);
			}catch(e){}
			//Impression
			try{
			var _category = new Array();
			var _data = new Array();
			$.each(dataCollection.data.impression, function(key, value){
				_data.push(parseInt(value.impression));
				if(value.label == null){
					_category.push(stripslashes(value.keyword));
				}else{
					_category.push(stripslashes(value.label));
				}
			});
			var data = {
				name : 'Impressions',
				data : _data
			};
			
			horizontalBarChart('kaImpression', _category, data);
			}catch(e){}
			//Retweets
			try{
			var _category = new Array();
			var _data = new Array();
			$.each(dataCollection.data.rt, function(key, value){
				_data.push(parseInt(value.retweets));
				if(value.label == null){
					_category.push(stripslashes(value.keyword));
				}else{
					_category.push(stripslashes(value.label));
				}
			});
			var data = {
				name : 'Retweets',
				data : _data
			};
			
			horizontalBarChart('kaRetweets', _category, data);
			}catch(e){}
			
			//PII
			twitterQuadrant('kaPII', dataCollection.data.quadrant);
			twitterQuadrant('kaInteraction', dataCollection.data.interactivity, true);
			
			//Sentiment
			try{
			var _category = new Array();
			var _dataPlus = new Array();
			var _dataMin = new Array();
			var _data = new Array();
			$.each(dataCollection.data.sentiment.positive, function(k, v) {
				
				_category.push(k);
				_dataPlus.push(v);
			});
			$.each(dataCollection.data.sentiment.negative, function(k, v) {
				_dataMin.push(Math.abs(v) * -1);
			});
			var dataPositive = {
					name: 'Favourable',
					data: _dataPlus
			};
			var dataNegative = {
					name: 'Unfavourable',
					data: _dataMin
			};
			_data.push(dataPositive);
			_data.push(dataNegative);
			negativeBarChart('kaSentiment', _category, _data);
			}catch(e){}
		});	
		
		
	}
	
	//Response List
	function kaResponseList(fungsi,startPage){
		smacLoader('twitter_responseList', 'loader-med', 'Twitter Response List');		
		smac_api(smac_api_url+'?method=keyword&action=response_list&channel=1&start='+startPage,function(dataCollection){
			var str = '';
			$.each(dataCollection.posts, function(key, val) {
				str+='<div class="list">';
				str+='<div class="smallthumb"><a rel="profile" href="#" onclick="twitterPopup(\''+val.author_id+'\', \''+val.name+'\'); return false;"><img src="'+val.profile_image_url+'"></a> </div>';
				str+='<div class="entry">';
				str+='<h3>'+val.name+'  (@'+val.author_id+')</h3>';
				str+='<span class="date">'+date('d/m/Y',strtotime(val.published))+'</span> ';
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
				str+='<a id="sentiment_'+val.id+'" class="icon-'+icon+' theTolltip" title="Sentiment" onclick="sentimentChangePopup(\''+val.id+'\');return false;" style="line-height:27px;float:none;text-transform: capitalize;width: 100px;">'+sentiment+'</a>';
				str+='</div>';
				str+='<div class="entry-action" style="position:relative;">';
				str+=sentimentChange(val.id,1,null,icon, '-35px');
				if(val.device == 'blackberry') var bb='active';
				if(val.device == 'apple') var apple='active';
				if(val.device == 'android') var android='active';
				str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
				str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
				str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
							
				str+='<span class="conversFont" style="font-size: 14px;left: 65px;position: absolute;top: -3px;width:650px;">'+val.txt+'</span>';
				
				str+='</div>';
				str+='</div>';
			});
			$('#twitter_responseList').html(str);
			if(pageInitResponse == 0){
				pageInitResponse = 1;
				if(startPage == 0)startPage=1;
				smacPagination(dataCollection.total, startPage, 'responsePaging', null, 'kaResponseList', 20);
			}			
		});
	}
	
	function breakdownRule(){
		channelData = new Array();
		sentimentData = new Array();
		ruleData = new Array();
		
		//Loader
		smacLoader('kaBreakdown', 'loader', 'Breakdown');
		
		smac_api(smac_api_url+'?method=keyword&action=get_rule_breakdown&from='+startDate+'&to='+endDate+'',function(dataCollection){
			
			//Break Down Table
			try{
				var str='';
				var i = 0;
				$.each(dataCollection.data, function(k, v){
					if (v.data.total_mentions != 0){
						//Breakdown List
						str+='<tr>';
						str+='<td width="100"><h3 style="width: 80px;display: block;"><a href="#" class="smallArrow" onclick="kaDetails('+v.keyword_id+','+i+', true);return false;">'+v.keyword+'</a></h3></td>';
						str+='<td><div id="channel'+i+'" style="width:118px;height:118px;"></div></td>';
						str+='<td><span style="width: 0px;display: block;" class="theTolltip" title="'+number_format(v.data.total_mentions)+'">'+smac_number2(v.data.total_mentions)+'</span></td>';
						str+='<td><div id="sentiment'+i+'" style="width:118px;height:118px;"></div></td>';
						str+='<td><div id="wcloud'+i+'" class="wordclouds" style="width: 265px"></div>';
						str+='</td>';
						str+='</tr>';
						
						//Rule Data
						ruleData.push(stripslashes(v.keyword));
						
						//Rule Option Menu
						$('#kaRuleMenu').append('<option class="kaRuleMenu" value="'+v.keyword_id+'n'+i+'">'+stripslashes(v.keyword)+'</option>');
						i++;
					}
					if(k==0){
						viewAllBreak = v.keyword_id;
					}
				});
				
				$('#kaBreakdown').html(str);
			}catch(e){}
			
			var i = 0;
			$.each(dataCollection.data, function(k, v){
				if (v.data.total_mentions != 0){
					//Pie on table - Channel
					var channelArr = new Array();
					var facebook = {
						name : 'Facebook',
						y	 : parseInt(v.data.facebook),
						color: '#262DED'
					}
					var twitter = {
						name : 'Twitter',
						y	 : parseInt(v.data.twitter),
						color: '#31EBE8'
					}
					var web = {
						name : 'Web',
						y	 : parseInt(v.data.web),
						color: '#FABC11'
					}
					channelArr.push(facebook);
					channelArr.push(twitter);
					channelArr.push(web);
					
					pieChartMiniChannel('channel'+i+'', channelArr, 110);
					channelData.push(channelArr);
					
					//Pie on table - Sentiment
					var tempPlus = (v.data.sentiment_positive == null) ? '0' : v.data.sentiment_positive;
					var tempMin = (v.data.sentiment_negative == null) ? '0' : v.data.sentiment_negative;

					var channelArr = new Array();
					if(v.data.sentiment_positive != null || tempPlus != '0'){
						var positive = {
							name : 'Positive',
							y	 : parseInt(v.data.sentiment_positive),
							color: '#8ec448'
						}
						channelArr.push(positive);
					}
					if(v.data.sentiment_negative != null || tempMin != '0'){
						var negative = {
							name : 'Negative',
							y	 : parseInt(v.data.sentiment_negative),
							color: '#ff0000'
						}
						channelArr.push(negative);
					}
					var neutralCalculation = Math.abs((parseInt(tempPlus)+parseInt(tempMin))-parseInt(v.data.total_mentions));
					var neutral = {
						name : 'Neutral',
						y	 : neutralCalculation,
						color: '#666666'
					}
					channelArr.push(neutral);
					
					
					pieChartMiniSentiment('sentiment'+i+'', channelArr, 110);
					sentimentData.push(channelArr);
					
					//Wordcloud
					if(v.wordcloud != null){
						var arrTemp = v.wordcloud;
							//Rearrange
							arrTemp.sort(function(){ return Math.random()-0.5; });
						$("#wcloud"+i).html(wordcloud(arrTemp,1,i));
					}else{
						$("#wcloud"+i).html("Not available");
					}
					i++;
				}
			});

			
			
		});
	}
	
	$('#kaBreakdownViewAll').live('click', function(event){
		event.preventDefault();
		kaDetails(viewAllBreak, 0, true);
	});
	
	$('#smacDateFilter').live('submit', function(event) {
		event.preventDefault();
		
		var tempStart = $('input[name="dt_from"]').val();
		var tempEnd = $('input[name="dt_to"]').val();
		startDate = date_dmySlash_to_ymdDash(tempStart);
		endDate = date_dmySlash_to_ymdDash(tempEnd);
		
		var tempTypeFilter = $('#dailySelect').val();
		var tempTypeFilterNum = tempTypeFilter.substr(3,1);
		var tempTypeFilterTop = tempTypeFilter.substr(0,3);
		if(tempTypeFilterTop == 'top'){
			var top = tempTypeFilterNum;
			tempTypeFilter = tempTypeFilterTop;
		}
		keywordAnalysisChart(tempTypeFilter, top);
		if(prevStartDate != startDate || prevEndDate != endDate){
			prevStartDate = startDate;
			prevEndDate = endDate;
			breakdownRule();
		}
			
	});
	
	
	//Top Keyword List
	function topKeywordList(keyID){
		var topKeywordListInit = 0;
		keywordData = new Array();
		
		//Loader
		smacLoader('keywordDetailList', 'loader', 'Keyword List');
		smacLoader('tab-list', 'loader-med', 'Conversation List');
		
		smac_api(smac_api_url+'?method=keyword&action=top_keywords&rule_id='+keyID+'',function(dataCollection){
			
			//Keyword Detail List
			var str = '';
			$.each(dataCollection.data, function(k, v){
				if(topKeywordListInit == 0){
					var topKeywordListActive = 'active';
				}else{
					var topKeywordListActive = '';
				}
				str+='<li class="'+topKeywordListActive+'"><a href="#tab-list" onclick="topConversationListbyKeyword(\''+v.keyword+'\', 0);">'+v.keyword+'</a></li>';
				keywordData.push(stripslashes(v.keyword));
				topKeywordListInit = 1;
			});
			$("#keywordDetailList").html(str);
			
			//Top Conversation by Keyword
			topConversationListbyKeyword(keywordData[0], 0);
		});
	}
	
	//Top Conversation by Keyword
	function topConversationListbyKeyword(keyword, page){
		//Loader
		smacLoader('tab-list', 'loader-med', 'Conversation List');
		
		smac_api(smac_api_url+'?method=keyword&action=top_keyword_conversation&keyword='+keyword+'&start='+page+'',function(dataCollection){
			
			//Conversation Detail List
			var str = '';
			$.each(dataCollection.data.tweets, function(k, v){
				if(v.device == 'blackberry') var bb='active';
				if(v.device == 'apple') var apple='active';
				if(v.device == 'android') var android='active';
				str+='<div class="list">'+
					 '<div class="smallthumb"><a rel="profile" href="#" onclick="twitterPopup(\''+v.author_id+'\', \''+v.author_name+'\'); return false;"><img src="'+v.author_avatar+'"></a> </div>'+
					 '<div class="entry">'+
					 '<h3>'+v.author_name+'</h3>'+
					 '<span class="date">'+date('d/m/Y H:i',strtotime(v.published_datetime))+'</span>'+
					 '<span class="entry_status conversFont">'+v.content+'</span>'+ 
					 '</div> <!-- .entry -->'+
					 '<div class="entry-action">'+
					 '<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>'+ 
					 '<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'+ 
					 '<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>'+ 
					 '<a class="icon-imp theTolltip" title="Total Impressions ('+number_format(v.impression)+')">'+smac_number2(v.impression)+'</a>';
				if(v.sentiment>0){
					str+='<a class="icon-positive">Positive</a>';
				}else if(v.sentiment<0){
					str+='<a class="icon-negative">Negative</a>';
				}else{}
				if(v.flag != '1'){
				str+='<a id="mt'+v.feed_id+'" title="Move to Folder" href="#" class="reply theTolltip" onclick="openFolderList(\''+v.feed_id+'\');return false;">&nbsp;</a>';
				str+=moveFolderList('#mt'+v.feed_id,v.feed_id,'1','500px');
				}else{
					str+='<a style="float: right;margin: -6px 0 0;"><img src="images/icon_centang.png" width="24px" height="27px"/></a>';
				}
				str+='</div><!-- .entry-action -->';
				str+='</div><!-- .list -->';
					 
			});
			$("#tab-list").html(str);
			
			//Init Page
			if(pageInitRule == 0 || keywordInitRule != keyword){
				keywordInitRule = keyword;
				pageInitRule = 1;
				if(page == 0)page=1;
				smacPagination(dataCollection.data.total_rows, page, 'cPaging', keyword, 'topConversationListbyKeyword');
			}
			
		});
		
		
	}
	
	function analysisLineChart(divID, ruleID, ruleName){
		smac_api(smac_api_url+'?method=keyword&action=rule_daily&rule_id='+ruleID+'&from='+startDate+'&to='+endDate+'',function(dataCollection){
			
			//Post - Mini line Chart
			var category = new Array();
			var _data = new Array();
			$.each(dataCollection.data, function(k, v) {
				var month = (v.published_date).substr(5,2);
				var tgl = (v.published_date).substr(8,2);
				category.push(tgl+"/"+month);
				_data.push(parseInt(v.total_mention));				
			});
			
			var data = {
				name: ruleName,
				data: _data
			};

			lineChartMini(divID, category, [data]);	
			
		});
	}
	
function sentimentConversation(gkada, startPage){
	//Sentiment Conversation
	smacLoader('kaSentimentConversation', 'loader-med', 'Sentiment Conversation');
	smac_api(smac_api_url+'?method=keyword&action=conversation&start='+startPage+'',function(dataCollection){
		
		var str='';
		$.each(dataCollection.data.tweets, function(key, val){
			str+='<div class="list">';
			str+='<div class="smallthumb"><a rel="profile" href="#" onclick="twitterPopup(\''+val.author_id+'\', \''+val.author_name+'\'); return false;">';
			str+='<img src="'+val.author_avatar+'"></a></div>';
			str+='<div class="entry"><h3>'+val.author_name+'</h3>';
			
			var tempSentiment = val.sentiment;
			var checkSentiment = tempSentiment.substr(0,1);
			var sent = 'plus'
			if(checkSentiment == '-'){
				sent = 'min';
			}
			str+='<span class="conversFont">'+val.content+'</span><span class="icon-'+sent+'">&nbsp;</span>';
			str+='</div></div>';
		});
		
		$('#kaSentimentConversation').html(str);
		if(pageInitSentiment == 0){
			pageInitSentiment = 1;
			if(startPage == 0)startPage=1;
			smacPagination(dataCollection.data.total, startPage, 'sentimentPaging', null, 'sentimentConversation', 5);
		}
	});
}

function popupSentiment(tipe, page, channel, webType){
	if(channel != null){
		if(webType != null){
			sentimentWebsiteType = webType;
		}
		switch(channel){
			case 'sentiment_tweet':
				$(".conversPop").removeClass('active');
				$("#btnTwitter").addClass('active');
				break;
			case 'fb_sentiment_post':
				$(".conversPop").removeClass('active');
				$("#btnFacebook").addClass('active');
				break;
			case 'web_sentiment_post':
				$(".conversPop").removeClass('active');
				if(webType == 1){$("#btnWeb").addClass('active');}
				else if(webType == 2){$("#btnForum").addClass('active');}
				else if(webType == 3){$("#btnNews").addClass('active');}
				else if(webType == 5){$("#btnEcommerce").addClass('active');}			
				else if(webType == 0){$("#btnCorporate").addClass('active');}			
				break;
			default:
				$(".conversPop").removeClass('active');
				$("#btnTwitter").addClass('active');
		}
		
		sentimentType = channel;
		page = 0;
		$('#conversationPopupPaging').show();
	}else{
		sentimentDtArr = tipe;
	}

	var arrTpDt = sentimentDtArr.split("_");
	$("#popup-sentiment .headpopup .postFor").remove();
	if(page == 0){
		if(globalPageInit == 0){
			$('#master').append('<div id="fade" style="display: block;"></div>');
			$("#popup-sentiment").prepend('<a class="close" href="#"><img style="margin: -20px -33px 0 615px;" alt="Close" title="Close Window" class="btn_close" src="images/close.png"></a>');
			$("#popup-sentiment").fadeIn();
			globalPageInit = 1;
		}
		if(arrTpDt[0] == '1'){
			var nama = 'Favourable';
		}else if(arrTpDt[0] == '2'){
			var nama = 'Unfavourable';
		}
		$("#popup-sentiment .headpopup h1").html(nama);
	}
		
	smacLoader('sentimentPopup', 'loader-med', nama);
	smac_api(smac_api_url+'?method=keyword&action='+sentimentType+'&type='+arrTpDt[0]+'&dt='+arrTpDt[1]+'&start='+page+'&group='+sentimentWebsiteType,function(dataCollection){
		var dataLength = dataCollection.posts;
		if(dataLength.length > 0){
			var str='';
			$.each(dataCollection.posts, function(k,v){
				str+='<div class="list">';
				str+='<div class="smallthumb">';
					if(sentimentType == 'sentiment_tweet'){
						str+='<a href="#"><img src="'+v.author_avatar+'" /></a>';					
					}else if(sentimentType == 'fb_sentiment_post'){
						str+='<a href="#"><img src="http://graph.facebook.com/'+v.author_id+'/picture" /></a>';
					}else{
						str+='<a href="#"><img src="images/iconWeb2.png" /></a>';
					}
				str+='</div>';
				str+='<div class="entry" style="border-bottom: none;">';
					str+='<h3>'+v.author_name+'</h3>';
					str+='<span>'+v.content+'</span>';
					str+='<span class="date">'+date("d-m-Y",strtotime(v.published_date))+'</span>';
				str+='</div><!-- .entry -->';
				str+='</div><!-- .list -->';
			});
			$("#popup-sentiment .content-popup").html(str);
			if(page == 0){
				page=1;
				smacPagination(dataCollection.total, page, 'sentimentPopupPaging', sentimentDtArr, 'popupSentiment', 10);
				$('#sentimentPopupPaging').show();
			}
			var tgl = date("d-m-Y",strtotime(arrTpDt[1]));
			$("#popup-sentiment .headpopup").append('<span class="postFor" style="margin: 0px 0px 0px 15px; position: absolute; top: 18px;">Post for '+tgl+'</span>');
		}else{
			$('#sentimentPopup').html('No data available.');
			$('#sentimentPopupPaging').hide();
		}
	});
}

function conversationByDate(dt, page, type, webType){
	
	if(type != null){
		if(webType != null){
			websiteType = webType;
		}
		switch(type){
			case 'conversation_by_date':
				$(".conversPop").removeClass('active');
				$("#buttonTwitter").addClass('active');
				break;
			case 'fb_conversation_by_date':
				$(".conversPop").removeClass('active');
				$("#buttonFacebook").addClass('active');
				break;
			case 'web_conversation_by_date':
				$(".conversPop").removeClass('active');
				console.log(webType);
				if(webType == 1){$("#buttonWeb").addClass('active');}
				else if(webType == 2){$("#buttonForum").addClass('active');}
				else if(webType == 3){$("#buttonNews").addClass('active');}
				else if(webType == 5){$("#buttonEcommerce").addClass('active');}			
				else if(webType == 0){$("#buttonCorporate").addClass('active');}			
				break;
			default:
				$(".conversPop").removeClass('active');
				$("#buttonTwitter").addClass('active');
		}
		
		conversationType = type;
		page = 0;
		$('#conversationPopupPaging').show();
	}else{
		dtArr = dt;
	}
	
	var arrDtKey = dtArr.split("_");
	
	if(page == 0){
		if(globalPageInit == 0){
		$('#master').append('<div id="fade" style="display: block;"></div>');
		$("#popup-ka-conversation").prepend('<a class="close" href="#"><img style="margin: -20px -33px 0 615px;" alt="Close" title="Close Window" class="btn_close" src="images/close.png"></a>');
		$("#popup-ka-conversation").fadeIn();
		$("#popup-ka-conversation .headpopup h1").css('margin-right','30px');
		globalPageInit = 1;
		}
	}
	smacLoader('conversationPopup', 'loader-med', 'Conversation');
	smac_api(smac_api_url+'?method=keyword&action='+conversationType+'&dt='+arrDtKey[0]+'&rule_id='+arrDtKey[1]+'&start='+page+'&type='+websiteType,function(dataCollection){
		var dataLength = dataCollection.data.posts;
		try{
		if(dataLength.length > 0){
			var str='';
			$.each(dataCollection.data.posts, function(k,v){
					str+='<div class="list">';
					str+='<div class="smallthumb">';
						if(conversationType == 'web_conversation_by_date'){
							str+='<a href="#"><img src="images/iconWeb2.png" /></a>';
						}else if(conversationType == 'fb_conversation_by_date'){
							str+='<a href="#"><img src="http://graph.facebook.com/'+v.author_id+'/picture"" /></a>';
						}else{
							str+='<a href="#"><img src="'+v.author_avatar+'" /></a>';
						}
					str+='</div>';
					str+='<div class="entry" style="border-bottom: none;">';
						str+='<h3>'+v.author_name+'</h3>';
							str+='<span>'+v.content+'</span>';
						str+='<span class="date">'+v.published_date+'</span>';
					str+='</div><!-- .entry -->';
					str+='</div><!-- .list -->';
			});
			$("#popup-ka-conversation .content-popup").html(str);
			if(page == 0){
				page=1;
				smacPagination(dataCollection.data.total, page, 'conversationPopupPaging', dtArr, 'conversationByDate', 10);
			}
		}else{
			$('#conversationPopup').html("No data available");
			$('#conversationPopupPaging').hide();
		}
		}catch(e){
			$('#conversationPopup').html("No data available");
			$('#conversationPopupPaging').hide();
		}
	});
}


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
		quadrant({target: divID,width:710,height:350,data:quadrantData, type: rate});
}

//Add Channel list of wordcloud click
function addChannelList(keyword){
	var str='';
		str+='<a class="listfolder" href="#/workflow/'+keyword+'/1" onclick="close_folder_global();" style="float:none;width:auto;">Twitter</a>';
		str+='<a class="listfolder" href="#/workflow/'+keyword+'/2" onclick="close_folder_global();" style="float:none;width:auto;">Facebook</a>';
		str+='<a class="listfolder" href="#/workflow_gcs/'+keyword+'/1" onclick="close_folder_global();" style="float:none;width:auto;">Blog</a>';
		str+='<a class="listfolder" href="#/workflow_gcs/'+keyword+'/2" onclick="close_folder_global();" style="float:none;width:auto;">Forum</a>';
		str+='<a class="listfolder" href="#/workflow_gcs/'+keyword+'/3" onclick="close_folder_global();" style="float:none;width:auto;">News</a>';
		str+='<a class="listfolder" href="#/workflow_gcs/'+keyword+'/5" onclick="close_folder_global();" style="float:none;width:auto;">Ecommerce</a>';
		str+='<a class="listfolder" href="#/workflow_gcs/'+keyword+'/0" onclick="close_folder_global();" style="float:none;width:auto;">Corporate/Personal</a>';
	return str;
}