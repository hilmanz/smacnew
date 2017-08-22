// :: All Post ::
// :: @kia ::

	//Global Variable
	var pageInit=0;
	var divID;
	var webType;
	
	
	$(document).ready(function(){
		Backbone.emulateHTTP = true;
		Backbone.emulateJSON = true;
		
		//initial main menu
		$('.dashboard a').addClass('current');
		
		//Backbone Router
		var Router = Backbone.Router.extend({
			routes: {
				"tabs/:action" : "hashTagMenu",
				"*actions": "defaultRoute" // Backbone will try match the route above first
			},
			hashTagMenu: function(action){
				if(action == 'twitterTab'){
					summaryTabMenu('twitterTab');
					divID = 'twitterTabTD';
					pageInit=0;
					allPost('twitter_posts',0);
				}else if(action == 'facebookTab'){
					summaryTabMenu('facebookTab');
					divID = 'facebookTabTD';
					pageInit=0;
					allPost('fb_posts',0);
				}else if(action == 'webTab'){
					summaryTabMenu('webTab');
					divID = 'webTabTD';
					webType = 1;
					pageInit=0;
					allPost('site_posts',0, webType);
				}else if(action == 'forumTab'){
					summaryTabMenu('forumTab');
					divID = 'forumTabTD';
					webType = 2;
					pageInit=0;
					allPost('site_posts',0, webType);
				}else if(action == 'newsTab'){
					summaryTabMenu('newsTab');
					divID = 'newsTabTD';
					webType = 3;
					pageInit=0;
					allPost('site_posts',0, webType);
				}else if(action == 'ecommerceTab'){
					summaryTabMenu('ecommerceTab');
					divID = 'ecommerceTabTD';
					webType = 5;
					pageInit=0;
					allPost('site_posts',0, webType);
				}else if(action == 'corporateTab'){
					summaryTabMenu('corporateTab');
					divID = 'corporateTabTD';
					webType = 0;
					pageInit=0;
					allPost('site_posts',0, webType);
				}
			},
			defaultRoute: function(actions){
				summaryTabMenu('twitterTab');
				divID = 'twitterTabTD';
				pageInit=0;
				allPost('twitter_posts',0);
			}
		});
		
		var app_router = new Router;
		Backbone.history.start();
	});
	
	//Tab Menu
	function summaryTabMenu(hashTag){
		 var targetID = "#"+hashTag;
		$(".pageContent").hide();
		$("#allPostnav span").removeClass("active");
		$(targetID).fadeIn();
		
		switch(targetID){
			case '#twitterTab':
				var active = '.navTwitter';
				break;
			case '#facebookTab':
				var active = '.navFacebook';
				break;
			case '#webTab':
				var active = '.navWeb';
				break;
			case '#forumTab':
				var active = '.navForum';
				break;
			case '#newsTab':
				var active = '.navNews';
				break;
			case '#ecommerceTab':
				var active = '.navEcommerce';
				break;
			case '#corporateTab':
				var active = '.navCorporate';
				break;
			default:
				var active = '.navTwitter';
		}
		$(active).addClass("active");
	}
	
	function allPost(channel, start){
		//Loader
		smacLoader(divID, 'loader-med', 'All Post');
		
		smac_api(smac_api_url+'?method=dashboard&action='+channel+'&start='+start+'&type='+webType+'',function(dataCollection){
			try{
				var feedLength = dataCollection.data.feeds;
				if(feedLength.length > 0){
					var str='';
					$.each(dataCollection.data.feeds, function(k,v){
						str+='<div class="list">';
						str+='<div class="smallthumb">';
						if(channel == 'twitter_posts'){
							var ch = '1';
							var headID = 'mt';
							str+='<a rel="profile" href="#" onclick="twitterPopup(\''+v.author_id+'\', \''+v.name+'\'); return false;">';
						}else if(channel == 'fb_posts'){
							str+='<a rel="profile" href="'+v.url+'" target="_blank" style="background:white;">';
						}else{
							str+='<a id="thumb'+v.id+'" onclick="webScreenshot(\''+v.screenshot+'\');return false;" class="poplight" style="background:white;">';
						}
						if(channel == 'site_posts'){	
							var ch = '3';
							var headID = 'mw';
							str+='<img src="content/pics/thumb_'+v.screenshot+'" onerror="errorImage(this,'+v.id+');"></a>';
						}else{
							str+='<img src="'+v.profile_image_url+'"></a>';
						}
						
						if(channel == 'fb_posts'){
							var ch = '2';
							var headID = 'mf';
						}
						str+='</div><!-- .smallthumb -->';
						str+='<div class="entry">';
						if(channel == 'site_posts'){
							str+='<h3><a href="'+v.url+'" target="_blank">'+v.name+'</a></h3>';
						}else{
							str+='<h3>'+v.name+'</h3>';
						}
						str+='<span class="date">'+v.published+'</span>'; 
						str+='<span class="conversFont">'+v.txt+'</span>';
						str+='</div><!-- .entry -->';
						str+='<div class="entry-action">';
						if(v.device == 'blackberry') var bb='active';
						if(v.device == 'apple') var apple='active';
						if(v.device == 'android') var android='active';
						str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
						str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
						str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
						var sentiment,icon;
						if(v.sentiment > 0){
							if(v.sentiment>1){
								sentiment = 'very positive';
								icon = 'positive';
							}else{
								sentiment = 'positive';
								icon = 'positive';
							}
						}
						else if(v.sentiment < 0){
							if(v.sentiment<-1){
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
						if(channel == 'twitter_posts'){
							str+='<a class="icon-rts theTolltip" title="Retweet Frequency ('+number_format(v.rt)+')">'+smac_number2(v.rt)+'</a>';
							str+='<a class="icon-imp theTolltip" title="Total Impressions ('+number_format(v.impression)+')">'+smac_number2(v.impression)+'</a>';
							str+='<a class="icon-share theTolltip" title="Share">'+v.share+'%</a>';
							str+='<a id="sentiment_'+v.id+'" class="icon-'+icon+' theTolltip" title="Sentiment" onclick="sentimentChangePopup(\''+v.id+'\');return false;" style="text-transform: capitalize;width:100px;">'+sentiment+'</a>';
						}else if(channel == 'fb_posts'){
							str+='<a title="Total Likes ('+number_format(v.like)+')" class="icon-likes theTolltip" style="margin: 0 0 0 16px;">'+smac_number2(v.like)+'</a>';
							str+='<a id="sentiment_'+v.id+'"  class="icon-'+icon+' theTolltip" title="Sentiment" onclick="sentimentChangePopup(\''+v.id+'\');return false;" style="text-transform: capitalize;width:100px;">'+sentiment+'</a>';
						}else{
							str+='<a id="sentiment_'+v.id+'"  class="icon-'+icon+' theTolltip" title="Sentiment" onclick="sentimentChangePopup(\''+v.id+'\');return false;" style="margin: 0 0 0 16px;text-transform: capitalize;width:100px;">'+sentiment+'</a>';
						}
						str+=sentimentChange(v.id,ch,webType,icon);
						if(v.flag != '1'){
							str+='<a id="'+headID+''+v.id+'" title="Move to Folder" href="#" style="width: 24px;" class="reply theTolltip" onclick="openFolderList(\''+v.id+'\');return false;">&nbsp;</a>';
							str+=moveFolderList('#'+headID+''+v.id,v.id,ch,null,webType);
						}else{
							str+='<a style="float: right;margin: 0 9px 0 0;"><img src="images/icon_centang.png" width="24px" height="27px"/></a>';
						}
						str+='</div>';
					});
					$('#'+divID).html(str);
					
					//web paging
					var divPagination = channel;
					switch(webType){
						case 1:
							divPagination = 'web_posts';
							break;
						case 2:
							divPagination = 'forum_posts';
							break;
						case 3:
							divPagination = 'news_posts';
							break;
						case 5:
							divPagination = 'ecommerce_posts';
							break;
						case 0:
							divPagination = 'corporate_posts';
							break;
						default:
							 divPagination = channel;
					}
					
					//Init Page
					if(pageInit == 0){
						pageInit = 1;
						if(start == 0)start=1;
						smacPagination(dataCollection.data.total_rows, start, divPagination+'_page', channel, 'allPost');
					}
				}else{
					$('#'+divID).html("No data available");
				}
			}catch(e){
				$('#'+divID).html("No data available");
			}
		});
	}