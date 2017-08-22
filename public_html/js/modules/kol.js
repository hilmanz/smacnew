// :: Key Opinion Leader ::
// :: @kia ::

	//Global Variable
	var pageInit = 0;
	var pageLog = 1;
	var twitInit = 0;
	var fbInit = 0;
	var webInit = 0;
	var forumInit = 0;
	var newsInit = 0;
	var ecommerceInit = 0;
	var corporateInit = 0;
	var videoInit = 0;
	var allTwitInit = 0;
	var allTwitTotalPeoplePlus = 0;
	var allTwitTotalPeopleMin = 0;
	var tabCheck, tabCheck2;
	var paramCheck = 0;
	var titleCheck;
	var pageInitPlus = 0;
	var pageInitMin= 0;
	var peopleCollection, webType, webHead, webHead2;
	// var sumDataCollection,twitDataCollection, fbDataCollection, webDataCollection;
	// var sumVolbySentiment, sumVolbyImpression, sumVolbyMention, sumVolbyPositif;
	// var webVolbyMention, webVolbyImpression, fbVolbyImpression, fbVolbyMention, twitVolbyImpression, twitVolbyMention;
	// var category;
	
	$(document).ready(function(){
		Backbone.emulateHTTP = true;
		Backbone.emulateJSON = true;
		
		//initial main menu
		$('.opinion a').addClass('current');
		
		//Backbone Router
		var Router = Backbone.Router.extend({
			routes: {
				"*action" : "hashTagMenu"
			},
			hashTagMenu: function(action){
				if(action == 'tabTwitter' || action == ''){
					tabCheck = 'twitter';
					kolTabMenu('tabTwitter');
					// if (twitInit == 0){
						// twitInit = 1;
						
						//Initiation
						$('.tab_content').hide();
						$("#idTwitExcludeKOL").hide();
						$('a#top10KOLFilter').show();
						$('#tab-Post').show();
						KOLDataCollection(paramCheck, tabCheck);
						
					if (twitInit == 0){
						twitInit = 1;
							
						//All People
						smac_api(smac_api_url+'?method=kol&action=twitter_all_people',function(dataCollection){
							dataTable('twit', 'twitter_all_people', 'twitter');
						});
					}
					
					$("#detailKOL").hide();
					$("#mainKOL").show();
					$("a#buttonTwitter").attr('href','#tabTwitter');
					$("a#buttonFacebook").attr('href','#tabFacebook');
					$("a#buttonWeb").attr('href','#tabWeb');
					$("a#buttonForum").attr('href','#tabForum');
					$("a#buttonNews").attr('href','#tabNews');
					$("a#buttonEcommerce").attr('href','#tabEcommerce');
					$("a#buttonCorporate").attr('href','#tabCorporate');
					$("a#buttonVideo").attr('href','#tabVideo');
				}else if(action == 'tabFacebook'){
					tabCheck = 'fb';
					kolTabMenu('tabFacebook');
					// if (fbInit == 0){
						// fbInit = 1;
						//initiation
						$('.tab_content').hide();
						$("#idFbExcludeKOL").hide();
						$("#tabfb-Post").show();
						KOLDataCollection(paramCheck, tabCheck);
					if (fbInit == 0){
						fbInit = 1;	
						//All People
						smac_api(smac_api_url+'?method=kol&action=fb_all_people',function(dataCollection){
							dataTable('fb', 'fb_all_people', 'facebook');
						});
					}
					
					$("#detailKOL").hide();
					$("#mainKOL").show();
					$("a#buttonTwitter").attr('href','#tabTwitter');
					$("a#buttonFacebook").attr('href','#tabFacebook');
					$("a#buttonWeb").attr('href','#tabWeb');
					$("a#buttonForum").attr('href','#tabForum');
					$("a#buttonNews").attr('href','#tabNews');
					$("a#buttonEcommerce").attr('href','#tabEcommerce');
					$("a#buttonCorporate").attr('href','#tabCorporate');
					$("a#buttonVideo").attr('href','#tabVideo');
				}else if(action == 'tabWeb'){
					tabCheck = 'site';
					webType = 1;
					kolTabMenu('tabWeb');
						$('tab_content').hide();
						$("#tabweb-Post").fadeIn();
						KOLDataCollection(paramCheck, tabCheck, webType);
					if (webInit == 0){
						webInit = 1;	
						//All People
						smac_api(smac_api_url+'?method=kol&action=site_all_sites&type='+webType,function(dataCollection){
							dataTable('web', 'site_all_sites', 'website');
						});
					}
					
					$("#detailKOL").hide();
					$("#mainKOL").show();
					$("a#buttonTwitter").attr('href','#tabTwitter');
					$("a#buttonFacebook").attr('href','#tabFacebook');
					$("a#buttonWeb").attr('href','#tabWeb');
					$("a#buttonForum").attr('href','#tabForum');
					$("a#buttonNews").attr('href','#tabNews');
					$("a#buttonEcommerce").attr('href','#tabEcommerce');
					$("a#buttonCorporate").attr('href','#tabCorporate');
					$("a#buttonVideo").attr('href','#tabVideo');
				}else if(action == 'tabForum'){
					tabCheck = 'site';
					webType = 2;
					kolTabMenu('tabForum');
						$('tab_content').hide();
						$("#tabforum-Post").fadeIn();
						KOLDataCollection(paramCheck, tabCheck, webType);
					if (forumInit == 0){
						forumInit = 1;	
						//All People
						smac_api(smac_api_url+'?method=kol&action=site_all_sites&type='+webType,function(dataCollection){
							dataTable('forum', 'site_all_sites', 'website');
						});
					}
					
					$("#detailKOL").hide();
					$("#mainKOL").show();
					$("a#buttonTwitter").attr('href','#tabTwitter');
					$("a#buttonFacebook").attr('href','#tabFacebook');
					$("a#buttonWeb").attr('href','#tabWeb');
					$("a#buttonForum").attr('href','#tabForum');
					$("a#buttonNews").attr('href','#tabNews');
					$("a#buttonEcommerce").attr('href','#tabEcommerce');
					$("a#buttonCorporate").attr('href','#tabCorporate');
					$("a#buttonVideo").attr('href','#tabVideo');
				}else if(action == 'tabNews'){
					tabCheck = 'site';
					webType = 3;
					kolTabMenu('tabNews');
						$('tab_content').hide();
						$("#tabnews-Post").fadeIn();
						KOLDataCollection(paramCheck, tabCheck, webType);
					if (newsInit == 0){
						newsInit = 1;	
						//All People
						smac_api(smac_api_url+'?method=kol&action=site_all_sites&type='+webType,function(dataCollection){
							dataTable('news', 'site_all_sites', 'website');
						});
					}
					
					$("#detailKOL").hide();
					$("#mainKOL").show();
					$("a#buttonTwitter").attr('href','#tabTwitter');
					$("a#buttonFacebook").attr('href','#tabFacebook');
					$("a#buttonWeb").attr('href','#tabWeb');
					$("a#buttonForum").attr('href','#tabForum');
					$("a#buttonNews").attr('href','#tabNews');
					$("a#buttonEcommerce").attr('href','#tabEcommerce');
					$("a#buttonCorporate").attr('href','#tabCorporate');
					$("a#buttonVideo").attr('href','#tabVideo');
				}else if(action == 'tabEcommerce'){
					tabCheck = 'site';
					webType = 5;
					kolTabMenu('tabEcommerce');
						$('tab_content').hide();
						$("#tabecommerce-Post").fadeIn();
						KOLDataCollection(paramCheck, tabCheck, webType);
					if (ecommerceInit == 0){
						ecommerceInit = 1;	
						//All People
						smac_api(smac_api_url+'?method=kol&action=site_all_sites&type='+webType,function(dataCollection){
							dataTable('ecommerce', 'site_all_sites', 'website');
						});
					}
					
					$("#detailKOL").hide();
					$("#mainKOL").show();
					$("a#buttonTwitter").attr('href','#tabTwitter');
					$("a#buttonFacebook").attr('href','#tabFacebook');
					$("a#buttonWeb").attr('href','#tabWeb');
					$("a#buttonForum").attr('href','#tabForum');
					$("a#buttonNews").attr('href','#tabNews');
					$("a#buttonEcommerce").attr('href','#tabEcommerce');
					$("a#buttonCorporate").attr('href','#tabCorporate');
					$("a#buttonVideo").attr('href','#tabVideo');
				}else if(action == 'tabCorporate'){
					tabCheck = 'site';
					webType = 0;
					kolTabMenu('tabCorporate');
						$('tab_content').hide();
						$("#tabcorporate-Post").fadeIn();
						KOLDataCollection(paramCheck, tabCheck, webType);
					if (corporateInit == 0){
						corporateInit = 1;	
						//All People
						smac_api(smac_api_url+'?method=kol&action=site_all_sites&type='+webType,function(dataCollection){
							dataTable('corporate', 'site_all_sites', 'website');
						});
					}
					
					$("#detailKOL").hide();
					$("#mainKOL").show();
					$("a#buttonTwitter").attr('href','#tabTwitter');
					$("a#buttonFacebook").attr('href','#tabFacebook');
					$("a#buttonWeb").attr('href','#tabWeb');
					$("a#buttonForum").attr('href','#tabForum');
					$("a#buttonNews").attr('href','#tabNews');
					$("a#buttonEcommerce").attr('href','#tabEcommerce');
					$("a#buttonCorporate").attr('href','#tabCorporate');
					$("a#buttonVideo").attr('href','#tabVideo');
				}else if(action == 'tabVideo'){
					tabCheck = 'video';
					kolTabMenu('tabVideo');
					// if (fbInit == 0){
						// fbInit = 1;
						//initiation
						// $("#idFbExcludeKOL").hide();
						$('tab_content').hide();
						$("#tabvideo-Post").fadeIn();
						KOLDataCollection(paramCheck, tabCheck);
					if (videoInit == 0){
						videoInit = 1;	
						
						//All People
						smac_api(smac_api_url+'?method=kol&action=video_all_people',function(dataCollection){
							dataTable('video', 'video_all_people', 'video');
						});
					}
					
					$("#detailKOL").hide();
					$("#mainKOL").show();
					$("a#buttonTwitter").attr('href','#tabTwitter');
					$("a#buttonFacebook").attr('href','#tabFacebook');
					$("a#buttonWeb").attr('href','#tabWeb');
					$("a#buttonVideo").attr('href','#tabVideo');
					
				}else if(action == 'allPeopleTwitter'){
					$('#noDashboardDataPage').hide();
					tabCheck = 'twitter_ambas';
					tabCheck2 = 'twitter_troll';
					kolTabMenu('allPeopleTwitter');
					titleCheck = 'Twitter';
						//initiation
						kolAllInfluencerPositive(tabCheck, 0);
						//kolAllInfluencerNegative(tabCheck2, 0);
						
					$("#mainKOL").hide();
					$("#detailKOL").show();
					$("#detailKOL a").attr('href','#tabTwitter');
					$("a#buttonTwitter").attr('href','#allPeopleTwitter');
					$("a#buttonFacebook").attr('href','#allPeopleFB');
					$("a#buttonWeb").attr('href','#allPeopleWeb');
					$("a#buttonForum").attr('href','#allPeopleForum');
					$("a#buttonNews").attr('href','#allPeopleNews');
					$("a#buttonEcommerce").attr('href','#allPeopleEcommerce');
					$("a#buttonCorporate").attr('href','#allPeopleCorporate');
					$("a#buttonVideo").attr('href','#allPeopleVideo');	
				}else if(action == 'allPeopleFB'){
					$('#noDashboardDataPage').hide();
					tabCheck = 'fb_ambas';
					tabCheck2 = 'fb_trolls';
					kolTabMenu('allPeopleTwitter');
					titleCheck = 'Facebook';
						//initiation
						kolAllInfluencerPositive(tabCheck, 0);
						// kolAllInfluencerNegative(tabCheck2, 0);
					
					$("#mainKOL").hide();
					$("#detailKOL").show();
					$("#detailKOL a").attr('href','#tabFacebook');
					$("a#buttonTwitter").attr('href','#allPeopleTwitter');
					$("a#buttonFacebook").attr('href','#allPeopleFB');
					$("a#buttonWeb").attr('href','#allPeopleWeb');
					$("a#buttonForum").attr('href','#allPeopleForum');
					$("a#buttonNews").attr('href','#allPeopleNews');
					$("a#buttonEcommerce").attr('href','#allPeopleEcommerce');
					$("a#buttonCorporate").attr('href','#allPeopleCorporate');
					$("a#buttonVideo").attr('href','#allPeopleVideo');	
				}else if(action == 'allPeopleWeb'){
					$('#noDashboardDataPage').hide();
					tabCheck = 'site_ambas';
					tabCheck2 = 'site_trolls';
					webType = 1;
					kolTabMenu('allPeopleTwitter');
					titleCheck = 'Blog';
						//initiation
						kolAllInfluencerPositive(tabCheck, 0, webType);
						// kolAllInfluencerNegative(tabCheck2, 0, webType);
						
					$("#mainKOL").hide();
					$("#detailKOL").show();
					$("#detailKOL a").attr('href','#tabWeb');
					$("a#buttonTwitter").attr('href','#allPeopleTwitter');
					$("a#buttonFacebook").attr('href','#allPeopleFB');
					$("a#buttonWeb").attr('href','#allPeopleWeb');
					$("a#buttonForum").attr('href','#allPeopleForum');
					$("a#buttonNews").attr('href','#allPeopleNews');
					$("a#buttonEcommerce").attr('href','#allPeopleEcommerce');
					$("a#buttonCorporate").attr('href','#allPeopleCorporate');
					$("a#buttonVideo").attr('href','#allPeopleVideo');	
				}else if(action == 'allPeopleForum'){
					$('#noDashboardDataPage').hide();
					tabCheck = 'site_ambas';
					tabCheck2 = 'site_trolls';
					webType = 2;
					kolTabMenu('allPeopleTwitter');
					titleCheck = 'Forum';
						//initiation
						kolAllInfluencerPositive(tabCheck, 0, webType);
						// kolAllInfluencerNegative(tabCheck2, 0, webType);
						
					$("#mainKOL").hide();
					$("#detailKOL").show();
					$("#detailKOL a").attr('href','#tabWeb');
					$("a#buttonTwitter").attr('href','#allPeopleTwitter');
					$("a#buttonFacebook").attr('href','#allPeopleFB');
					$("a#buttonWeb").attr('href','#allPeopleWeb');
					$("a#buttonForum").attr('href','#allPeopleForum');
					$("a#buttonNews").attr('href','#allPeopleNews');
					$("a#buttonEcommerce").attr('href','#allPeopleEcommerce');
					$("a#buttonCorporate").attr('href','#allPeopleCorporate');
					$("a#buttonVideo").attr('href','#allPeopleVideo');	
				}else if(action == 'allPeopleNews'){
					$('#noDashboardDataPage').hide();
					tabCheck = 'site_ambas';
					tabCheck2 = 'site_trolls';
					webType = 3;
					kolTabMenu('allPeopleTwitter');
					titleCheck = 'News';
						//initiation
						kolAllInfluencerPositive(tabCheck, 0, webType);
						// kolAllInfluencerNegative(tabCheck2, 0, webType);
						
					$("#mainKOL").hide();
					$("#detailKOL").show();
					$("#detailKOL a").attr('href','#tabWeb');
					$("a#buttonTwitter").attr('href','#allPeopleTwitter');
					$("a#buttonFacebook").attr('href','#allPeopleFB');
					$("a#buttonWeb").attr('href','#allPeopleWeb');
					$("a#buttonForum").attr('href','#allPeopleForum');
					$("a#buttonNews").attr('href','#allPeopleNews');
					$("a#buttonEcommerce").attr('href','#allPeopleEcommerce');
					$("a#buttonCorporate").attr('href','#allPeopleCorporate');
					$("a#buttonVideo").attr('href','#allPeopleVideo');	
				}else if(action == 'allPeopleEcommerce'){
					$('#noDashboardDataPage').hide();
					tabCheck = 'site_ambas';
					tabCheck2 = 'site_trolls';
					webType = 5;
					kolTabMenu('allPeopleTwitter');
					titleCheck = 'Ecommerce';
						//initiation
						kolAllInfluencerPositive(tabCheck, 0, webType);
						// kolAllInfluencerNegative(tabCheck2, 0, webType);
						
					$("#mainKOL").hide();
					$("#detailKOL").show();
					$("#detailKOL a").attr('href','#tabWeb');
					$("a#buttonTwitter").attr('href','#allPeopleTwitter');
					$("a#buttonFacebook").attr('href','#allPeopleFB');
					$("a#buttonWeb").attr('href','#allPeopleWeb');
					$("a#buttonForum").attr('href','#allPeopleForum');
					$("a#buttonNews").attr('href','#allPeopleNews');
					$("a#buttonEcommerce").attr('href','#allPeopleEcommerce');
					$("a#buttonCorporate").attr('href','#allPeopleCorporate');
					$("a#buttonVideo").attr('href','#allPeopleVideo');	
				}else if(action == 'allPeopleCorporate'){
					$('#noDashboardDataPage').hide();
					tabCheck = 'site_ambas';
					tabCheck2 = 'site_trolls';
					webType = 0;
					kolTabMenu('allPeopleTwitter');
					titleCheck = 'Corporate';
						//initiation
						kolAllInfluencerPositive(tabCheck, 0, webType);
						// kolAllInfluencerNegative(tabCheck2, 0, webType);
						
					$("#mainKOL").hide();
					$("#detailKOL").show();
					$("#detailKOL a").attr('href','#tabWeb');
					$("a#buttonTwitter").attr('href','#allPeopleTwitter');
					$("a#buttonFacebook").attr('href','#allPeopleFB');
					$("a#buttonWeb").attr('href','#allPeopleWeb');
					$("a#buttonForum").attr('href','#allPeopleForum');
					$("a#buttonNews").attr('href','#allPeopleNews');
					$("a#buttonEcommerce").attr('href','#allPeopleEcommerce');
					$("a#buttonCorporate").attr('href','#allPeopleCorporate');
					$("a#buttonVideo").attr('href','#allPeopleVideo');	
				}else if(action == 'allPeopleVideo'){
					$('#noDashboardDataPage').hide();
					tabCheck = 'video_ambas';
					tabCheck2 = 'video_trolls';
					kolTabMenu('allPeopleTwitter');
					titleCheck = 'Video';
						//initiation
						kolAllInfluencerPositive(tabCheck, 0);
						kolAllInfluencerNegative(tabCheck2, 0);
						
					$("#mainKOL").hide();
					$("#detailKOL").show();
					$("#detailKOL a").attr('href','#tabWeb');
					$("a#buttonTwitter").attr('href','#allPeopleTwitter');
					$("a#buttonFacebook").attr('href','#allPeopleFB');
					$("a#buttonWeb").attr('href','#allPeopleWeb');
					$("a#buttonVideo").attr('href','#allPeopleVideo');	
				}
			}
		});
	
		var app_router = new Router;
		Backbone.history.start();

		$('a#twitAllDT').live('click', function(){
			$('#top10KOLFilter').hide();
		});
		$('a#twitKOL').live('click', function(){
			$('#top10KOLFilter').show();
		});
		
	});
	
	//KOL Tab Menu
	function kolTabMenu(hashTag){
		var targetID = "#"+hashTag;
		$(".pageContent").fadeOut();
		$(".pageContent .tab_content").fadeOut();
		$(".pageContent .tab_content:first-child").fadeIn();
		$("#tabKol a").removeClass("active");
		$(".pageContent .tabs li").removeClass("active");
		$(".pageContent .tabs li:first-child").addClass("active");
		if (targetID != '#allPeopleTwitter'){
			$(targetID).fadeIn();
		}
		
		switch(targetID){
			case '#tabTwitter':
				var active = '#buttonTwitter';
				break;
			case '#tabFacebook':
				var active = '#buttonFacebook';
				break;
			case '#tabWeb':
				var active = '#buttonWeb';
				break;
			case '#tabForum':
				var active = '#buttonForum';
				break;
			case '#tabNews':
				var active = '#buttonNews';
				break;
			case '#tabEcommerce':
				var active = '#buttonEcommerce';
				break;
			case '#tabCorporate':
				var active = '#buttonCorporate';
				break;
			case '#tabVideo':
				var active = '#buttonVideo';
			break;
			case '#allPeopleTwitter':
				if(tabCheck == 'fb_ambas'){
					var active = '#buttonFacebook';
					pageInitPlus = 0;pageInitMin = 0;
				}else if(tabCheck == 'site_ambas'){
					var active;
					switch(webType){
						case 1:
							active = '#buttonWeb';
							break;
						case 2:
							active = '#buttonForum';
							break;
						case 3:
							active = '#buttonNews';
							break;
						case 5:
							active = '#buttonEcommerce';
							break;
						case 0:
							active = '#buttonCorporate';
							break;
						default:
							active = '#buttonWeb';
					}
					pageInitPlus = 0;pageInitMin = 0;
				}else{
					var active = '#buttonTwitter';
					pageInitPlus = 0;pageInitMin = 0;
				}
				break;
			
			default:
				var active = '#buttonTwitter';
		}
		
		$("a#"+active).addClass("active");
	}
	
	//KOL Data Collection
	function KOLDataCollection(param, channel, webType){
		$('#noDashboardDataPage').hide();

		//param => 0 = none; 1 = exclude news; 2 = exclude corporate accounts
		// webType = 0;
		//Loader
		if(channel == 'twitter'){
			$('#kolTopPeopleFoto').html('');
			smacLoader('kolTopPeopleBar', 'loader-med', 'Top 10 KOL');
			smacLoader('kolTwitPotentialImpression', 'loader-med', 'Potential Impression');
			smacLoader('kolTwitMention', 'loader-med', 'Mention');
			smacLoader('twitPositiveKOL', 'loader-med', 'Positive Influencers');
			smacLoader('twitNegativeKOL', 'loader-med', 'Negative Influencers');
		}else if(channel == 'fb'){
			$('#kolTopPeopleFotoFB').html('');
			smacLoader('kolTopPeopleBarFB', 'loader-med', 'Top 10 KOL');
			smacLoader('kolFBLikes', 'loader-med', 'Likes');
			smacLoader('fbPositiveKOL', 'loader-med', 'Positive Influencers');
			smacLoader('fbNegativeKOL', 'loader-med', 'Negative Influencers');
		}else if(channel == 'site'){
			switch(webType){
				case 1:
					webHead = 'web'; 
					webHead2 = 'Web';
					break;
				case 2:
					webHead = 'forum'; 
					webHead2 = 'Forum';
					break;
				case 3:
					webHead = 'news'; 
					webHead2 = 'News';
					break;
				case 5:
					webHead = 'ecommerce'; 
					webHead2 = 'Ecommerce';
					break;
				case 0:
					webHead = 'corporate'; 
					webHead2 = 'Corporate';
					break;
				default:
					webHead = 'web'; 
					webHead2 = 'Web';
			}
			$('#kolTopPeopleFoto'+webHead2).html('');
			smacLoader('kolTopPeopleBar'+webHead2, 'loader-med', 'Top 10 KOL');
			smacLoader('kol'+webHead2+'Comment', 'loader-med', 'Comments');
			smacLoader('kol'+webHead2+'Influence', 'loader-med', 'Influence');
			smacLoader(webHead+'PositiveKOL', 'loader-med', 'Positive Influencers');
			smacLoader(webHead+'NegativeKOL', 'loader-med', 'Negative Influencers');
		}else if(channel == 'video'){
			$('#kolTopPeopleFotoVideo').html('');
			smacLoader('kolTopPeopleBarVideo', 'loader-med', 'Top 10 KOL');
			smacLoader('kolVideoComment', 'loader-med', 'Comments');
			smacLoader('kolVideoInfluence', 'loader-med', 'Influence');
			smacLoader('videoPositiveKOL', 'loader-med', 'Positive Influencers');
			smacLoader('videoNegativeKOL', 'loader-med', 'Negative Influencers');
		}
		
			smac_api(smac_api_url+'?method=kol&action='+channel+'&exclude='+param+'&type='+webType+'',function(dataCollection){
				try{
					if(channel == 'twitter'){
						twitDataCollection(dataCollection.data);
					}else if(channel == 'fb'){
						fbDataCollection(dataCollection.data);
						
						//Sentiment
						smac_api(smac_api_url+'?method=kol&action=fb_ambas&start=0&exclude='+param+'',function(dataCollection){
							//Positive
							var str='';
							$.each(dataCollection.data.rows, function(key, value){
								str+='<div class="list">';
								str+='<div class="smallthumb"><a rel="profile" class="poplight" href="http://www.facebook.com/" target="_blank"><img src="'+value.img+'"></a> </div>';
								str+='<div class="entry">';
								str+='<h3>'+value.name+'</h3>';
								str+='</div> <!-- .entry -->';
								str+='<div class="entry-action"> ';
								str+='<a class="icon-positive theTolltip" title="Influencers ('+number_format(value.total)+')">'+smac_number2(value.total)+'</a>';
								// str+='<a  title="Mark for Reply" onclick="#" href="#" class="reply theTolltip">&nbsp;</a> ';
								str+='</div><!-- .entry-action -->';
								str+='</div><!-- .list -->';
							});
							var dataTwitPlus = dataCollection.data.rows;
							if(dataTwitPlus.length < 1){str = "<h4 style='text-align:center;'>No data available.<h4>";}
							$("#fbPositiveKOL").html(str);
						});
						
						smac_api(smac_api_url+'?method=kol&action=fb_trolls&start=0&exclude='+param+'',function(dataCollection){
							
							var strMin = "";
							//Negative
								$.each(dataCollection.data.rows, function(key, value){
									strMin+='<div class="list">';
									strMin+='<div class="smallthumb"><a rel="profile" class="poplight" href="http://www.facebook.com/" target="_blank"><img src="'+value.img+'"></a> </div>';
									strMin+='<div class="entry">';
									strMin+='<h3>'+value.name+'</h3>';
									strMin+='</div> <!-- .entry -->';
									strMin+='<div class="entry-action"> ';
									strMin+='<a class="icon-negative theTolltip" title="Influencers ('+number_format(value.total)+')">'+smac_number2(value.total)+'</a>';
									// strMin+='<a  title="Mark for Reply" onclick="#" href="#" class="reply theTolltip">&nbsp;</a> ';
									strMin+='</div><!-- .entry-action -->';
									strMin+='</div><!-- .list -->';
								});
							var dataTwitMin = dataCollection.data.rows;					
							if(dataTwitMin.length < 1){strMin = "<h4 style='text-align:center;'>No data available.<h4>";}
							$("#fbNegativeKOL").html(strMin);
						});
						
					}else if(channel == 'site'){
						//Sentiment
						smac_api(smac_api_url+'?method=kol&action=site_ambas&start=0&exclude='+param+'&type='+webType+'',function(dataCollection){
							
							//Positive
							var str='';
							try{
							$.each(dataCollection.data.rows, function(key, value){
								str+='<div class="list">';
								str+='<div class="smallthumb"><a id="thumb'+value.id+'" onclick="webScreenshot(\''+value.screenshot+'\');return false;" class="poplight" style="background:white;"><img src="content/pics/thumb_'+value.screenshot+'" onerror="errorImage(this,'+value.id+');"></a></div>';
								str+='<div class="entry">';
								str+='<h3><a href="http://'+value.name+'">'+value.name+'</a></h3>';
								str+='</div> <!-- .entry -->';
								str+='<div class="entry-action"> ';
								str+='<a class="icon-positive theTolltip" title="Influencers ('+number_format(value.total)+')">'+smac_number2(value.total)+'</a>';
								// str+='<a  title="Mark for Reply" onclick="#" href="#" class="reply theTolltip">&nbsp;</a> ';
								str+='</div><!-- .entry-action -->';
								str+='</div><!-- .list -->';
							});
							var dataTwitPlus = dataCollection.data.rows;					
							if(dataTwitPlus.length < 1){str = "<h4 style='text-align:center;'>No data available.<h4>";}
							}catch(e){}
							$("#webPositiveKOL, #forumPositiveKOL, #newsPositiveKOL, #ecommercePositiveKOL, #corporatePositiveKOL").html(str);
						});
						
						smac_api(smac_api_url+'?method=kol&action=site_trolls&start=0&exclude='+param+'&type='+webType+'',function(dataCollection){
							
							var strMin = "";
							//Negative
							try{
								$.each(dataCollection.data.rows, function(key, value){
									strMin+='<div class="list">';
									strMin+='<div class="smallthumb"><a id="thumb'+value.id+'" onclick="webScreenshot(\''+value.screenshot+'\');return false;" class="poplight" style="background:white;"><img src="content/pics/thumb_'+value.screenshot+'" onerror="errorImage(this,'+value.id+');"></a></div>';
									strMin+='<div class="entry">';
									strMin+='<h3><a href="http://'+value.name+'">'+value.name+'</a></h3>';
									strMin+='</div> <!-- .entry -->';
									strMin+='<div class="entry-action"> ';
									strMin+='<a class="icon-negative theTolltip" title="Influencers ('+number_format(value.total)+')">'+smac_number2(value.total)+'</a>';
									// strMin+='<a  title="Mark for Reply" onclick="#" href="#" class="reply theTolltip">&nbsp;</a> ';
									strMin+='</div><!-- .entry-action -->';
									strMin+='</div><!-- .list -->';
								});
							var dataTwitMin = dataCollection.data.rows;					
							if(dataTwitMin.length < 1){strMin = "<h4 style='text-align:center;'>No data available.<h4>";}
							}catch(e){}
							$("#webNegativeKOL, #forumNegativeKOL, #newsNegativeKOL, #ecommerceNegativeKOL, #corporateNegativeKOL").html(strMin);
						});
						
						webDataCollection(dataCollection.data, webHead2);
					}
					else if(channel == 'video'){
						
						//Sentiment
						smac_api(smac_api_url+'?method=kol&action=video_ambas&start=0&exclude='+param+'',function(dataCollection){
							
							//Positive
							var str='';
							try{
								$.each(dataCollection.data.rows, function(key, value){
									str+='<div class="list">';
									if(value.img == null){var img = 'images/iconWeb2.png';}else{var img = value.img;}
									str+='<div class="smallthumb"><a rel="profile" class="poplight" href="http://'+value.name+'" target="_blank"><img src="'+img+'"></a> </div>';
									str+='<div class="entry">';
									str+='<h3>'+value.name+'</h3>';
									str+='</div> <!-- .entry -->';
									str+='<div class="entry-action"> ';
									str+='<a class="icon-positive theTolltip" title="Influencers ('+number_format(value.total)+')">'+smac_number2(value.total)+'</a>';
									// str+='<a  title="Mark for Reply" onclick="#" href="#" class="reply theTolltip">&nbsp;</a> ';
									str+='</div><!-- .entry-action -->';
									str+='</div><!-- .list -->';
								});
							}catch(e){}
							$("#videoPositiveKOL").html(str);
						});
						
						smac_api(smac_api_url+'?method=kol&action=video_trolls&start=0&exclude='+param+'',function(dataCollection){
							
							var strMin = "";
							//Negative
							try{
								$.each(dataCollection.data.rows, function(key, value){
									strMin+='<div class="list">';
									if(value.img == null){var img = 'images/iconWeb2.png';}else{var img = value.img;}
									strMin+='<div class="smallthumb"><a rel="profile" class="poplight" href="http://'+value.name+'" target="_blank"><img src="'+img+'"></a> </div>';
									strMin+='<div class="entry">';
									strMin+='<h3>'+value.name+'</h3>';
									strMin+='</div> <!-- .entry -->';
									strMin+='<div class="entry-action"> ';
									strMin+='<a class="icon-negative theTolltip" title="Influencers ('+number_format(value.total)+')">'+smac_number2(value.total)+'</a>';
									// strMin+='<a  title="Mark for Reply" onclick="#" href="#" class="reply theTolltip">&nbsp;</a> ';
									strMin+='</div><!-- .entry-action -->';
									strMin+='</div><!-- .list -->';
								});
							}catch(e){}
							$("#videoNegativeKOL").html(strMin);
						});
						
						videoDataCollection(dataCollection.data);
					}
				}catch(e){
					if(channel == 'twitter'){
						$('#kolTopPeopleBar, #kolTwitPotentialImpression, #kolTwitMention, #twitPositiveKOL, #twitNegativeKOL').html("<h4 style='text-align:center;'>No data available.<h4>");
					}else if(channel == 'fb'){
						$('#kolTopPeopleBarFB, #kolFBLikes, #fbPositiveKOL, #fbNegativeKOL').html("<h4 style='text-align:center;'>No data available.<h4>");
					}else if(channel == 'site'){
						switch(webType){
							case 1:
								webHead = 'web'; 
								webHead2 = 'Web';
								break;
							case 2:
								webHead = 'forum'; 
								webHead2 = 'Forum';
								break;
							case 3:
								webHead = 'news'; 
								webHead2 = 'News';
								break;
							case 5:
								webHead = 'ecommerce'; 
								webHead2 = 'Ecommerce';
								break;
							case 0:
								webHead = 'corporate'; 
								webHead2 = 'Corporate';
								break;
							default:
								webHead = 'web'; 
								webHead2 = 'Web';
						}
						$('#kolTopPeopleBar'+webHead2+', #kol'+webHead2+'Comment, #kol'+webHead2+'Influence, #'+webHead+'PositiveKOL, #'+webHead+'NegativeKOL').html("<h4 style='text-align:center;'>No data available.<h4>");
					}else if(channel == 'video'){
						$('#kolTopPeopleBarVideo, #kolVideoComment, #kolVideoInfluence, #videoPositiveKOL, #videoNegativeKOL').html("<h4 style='text-align:center;'>No data available.<h4>");
					}
				}
			});	
		
	}
	
	//Twitter Dropdown Menu
	$("#twitExcludeKOL").live('change', function() {
		paramCheck=this.value;
		KOLDataCollection(paramCheck, 'twitter');
	});
	
	//Twitter KOL Dropdown Menu
	$("#top10KOLFilterSelect").live('change', function() {
		var dropID=this.value;
		
		switch(dropID){
			case '0':
				var kolID = 'tab-Post';
				break;
			case '1':
				var kolID = 'tab-Mentions';
				twitterDaily();
				break;
			case '2':
				var kolID = 'tab-Impression';
				twitterDaily();
				break;
			default:
				var kolID = 'tab-Post';
		}
		$('.tab_content').hide();
		$('#'+kolID+'').fadeIn();
	});
	
	//KOL view by Daily Mentions
	function twitterDaily(){
		smacLoader('kolTwitDailyMentions', 'loader-med', 'Daily Mentions');
		smacLoader('kolTwitDailyImpression', 'loader-med', 'Daily Impressions');
		smac_api(smac_api_url+'?method=kol&action=twitter_daily&people='+peopleCollection+'',function(dataCollection){
			
			//Mentions
			var category = new Array();
			var categoryInit = 0;
			var mentionKOLDailyArr = new Array();
			var impressionKOLDailyArr = new Array();
			$.each(dataCollection.data, function(k,v){
				var mentionsArr = new Array();
				var impressionArr = new Array();
				$.each(dataCollection.data[k], function(key, val){
					if(categoryInit == 0){
						var month = (val.dtpost).substr(5,2);
						var tgl = (val.dtpost).substr(8,2);
						category.push(tgl+"/"+month);
					}
					mentionsArr.push(intval(val['mentions']));
					impressionArr.push(intval(val['impression']));
				});
				if(categoryInit == 0){
					var show = true;
				}else{
					var show = false;
				}
				
				mentionKOLDailyArr.push({
								name: k,
								data: mentionsArr,
								visible: show
							});
				impressionKOLDailyArr.push({
								name: k,
								data: impressionArr,
								visible: show
							});
				
				categoryInit = 1;
			});
			
			lineChart('kolTwitDailyMentions', category, mentionKOLDailyArr, true);
			lineChart('kolTwitDailyImpression', category, impressionKOLDailyArr, true);
		});
	}
	
	//FB Dropdown Menu
	$("#fbExcludeKOL").live('change', function() {
		paramCheck=this.value;
		KOLDataCollection(paramCheck, 'fb');
	});
	
	//Exclude Data Toggle
	function KOLDataCollectionToggle(){
		if (paramCheck == 0){
			if(tabCheck == 'twitter')$("#idTwitExcludeKOL").fadeIn();
			if(tabCheck == 'fb')$("#idFbExcludeKOL").fadeIn();
			paramCheck = 1;
			KOLDataCollection(paramCheck, tabCheck);			
		}else{
			if(tabCheck == 'twitter')$("#idTwitExcludeKOL").fadeOut();
			if(tabCheck == 'fb')$("#idFbExcludeKOL").fadeOut();
			paramCheck = 0;
			KOLDataCollection(paramCheck, tabCheck);			
		}
	}
	
	//Twitter Data Collection
	function twitDataCollection(twitData){
		//Processing Data
			var topKOLImpressionArr = new Array();
			$.each(twitData.top_kol, function(key, value){
				topKOLImpressionArr.push(parseInt(value.impression));
			});
			
			//Find Max value of Array
			var maxKOLImpression=topKOLImpressionArr[0];
			var i=0;
			while(i<topKOLImpressionArr.length){
				maxKOLImpression=Math.max(maxKOLImpression,topKOLImpressionArr[i]);
				i++;
			}
			
			
			//Top KOL
			var topPost = twitData.top_kol;
			if(topPost.length > 0){
				$('#noDashboardDataPage').hide();
				var str="";
				var strFoto="";
				peopleCollection = "";
				var peopleInit = 0;
				$.each(twitData.top_kol, function(key, value){
					//Calculate Bar Height and Text Position
					var persen = (value.impression/maxKOLImpression)*100;
					var text = (((225*persen)/100)-25);
					var posisi = text/2;
				
					//Chart Bar
					str+='<td valign="bottom" height="225" >';
					str+='<div class="info-imp" title="Total Impressions">';
					str+='<a href="#" class="total-imp">'+smac_number2(value.impression)+'</a>';
					str+='<span class="space" style="display:none;">|</span>';
					str+='<a class="percent-black" style="display:none;" title="Share Percentage">100%</a>';
					str+='</div>';
					str+='<div id="c'+key+'" style="background-color:#00aefe; width:65px; height:'+persen+'%; margin: 0 auto;">';
					str+='<div id="cd'+key+'" style="margin:auto; padding:'+posisi+'px 0px 0px; height:30px; color:#FFF; text-align:center">';
					str+='<a class="percent-white" title="Share Percentage" href="#">'+value.share+'%</a>';
					str+='</div>';
					str+='</div>';
					str+='</td>';
					
					//Chart Foto
					strFoto+='<td valign="bottom" height="100" style=" text-align:center;">';
					strFoto+='<a href="#" onclick="twitterPopup(\''+value.id+'\',\''+value.name+'\'); return false;" class="smallthumbs relative" rel="profile" style="float:none;overflow:visible;">';
					strFoto+='<img src="'+value.img+'" width="48" height="48" style="margin:0" title="'+value.name+'"/>';
					strFoto+='</a>';
					strFoto+='<p style="font-size:11px; font-weight:bolder; display: block; width: 80px;">'+value.id+'</p>';
					strFoto+='</td>';
					
					if(peopleInit == 0){
						peopleInit=1;
						peopleCollection += ''+value.id+'';
					}else{
						peopleCollection += ','+value.id+'';
					} 
				});
				
				$("#kolTopPeopleBar").html(str);
				$("#kolTopPeopleFoto").html(strFoto);
				
				//Top Impression - Twitter
				var _category = new Array();
				var _data = new Array();
				$.each(twitData.top_impression, function(key, value){
					_data.push(parseInt(value.total));
					_category.push(value.id);
				});
				var data = {
					name : 'Potential Impression',
					data : _data
				};

				horizontalBarChart('kolTwitPotentialImpression', _category, data);
				
				//Top Mention - Twitter
				var _category = new Array();
				var _data = new Array();
				$.each(twitData.top_mention, function(key, value){
					_data.push(parseInt(value.total));
					_category.push(stripslashes(value.id));
				});
				var data = {
					name : 'Mention',
					data : _data
				};

				horizontalBarChart('kolTwitMention', _category, data);
				
				//Top Influencers - Twitter
				var str = "";
				//Positive
					$.each(twitData.top_influencers.positive, function(key, value){
						str+='<div class="list">';
						str+='<div class="smallthumb"><a rel="profile" href="#" onclick="twitterPopup(\''+value.name+'\'); return false;"><img src="'+value.img+'"></a> </div>';
						str+='<div class="entry">';
						str+='<h3>'+stripslashes(value.name)+'</h3>';
						str+='</div> <!-- .entry -->';
						str+='<div class="entry-action legend" style="clear: none;padding-top: 5px;"> ';
						str+='<a class="icon2 theTolltip" style="width:auto;" title="Mentions ('+number_format(value.stats.mentions)+')">'+smac_number2(parseInt(value.stats.mentions))+'</a>';
						str+='<a class="icon3 theTolltip" style="width:auto;" title="Total Impressions ('+number_format(value.stats.impression)+')">'+smac_number2(parseInt(value.stats.impression))+'</a>';
						str+='<a class="icon4 theTolltip" style="width:auto;" title="Retweet ('+number_format(value.stats.rt)+')">'+smac_number2(parseInt(value.stats.rt))+'</a>';
						str+='<a class="icon5 theTolltip" style="width:auto;" title="Retweet Impressions ('+number_format(value.stats.rt_imp)+')">'+smac_number2(parseInt(value.stats.rt_imp))+'</a>';
						// str+='<a class="icon-positive theTolltip" title="Influencers">'+smac_number2(value.total)+'</a>';
						// str+='<a  title="Mark for Reply" onclick="#" href="#" class="reply theTolltip">&nbsp;</a> ';
						str+='</div><!-- .entry-action -->';
						str+='</div><!-- .list -->';
					});
				
				var strMin = "";
				//Negative
					$.each(twitData.top_influencers.negative, function(key, value){
						strMin+='<div class="list">';
						strMin+='<div class="smallthumb"><a rel="profile" href="#" onclick="twitterPopup(\''+value.name+'\'); return false;"><img src="'+value.img+'"></a> </div>';
						strMin+='<div class="entry">';
						strMin+='<h3>'+stripslashes(value.name)+'</h3>';
						strMin+='</div> <!-- .entry -->';
						strMin+='<div class="entry-action legend" style="clear: none;padding-top: 5px;"> ';
						strMin+='<a class="icon2 theTolltip" style="width:auto;" title="Mentions ('+number_format(value.stats.mentions)+')">'+smac_number2(parseInt(value.stats.mentions))+'</a>';
						strMin+='<a class="icon3 theTolltip" style="width:auto;" title="Total Impressions ('+number_format(value.stats.impression)+')">'+smac_number2(parseInt(value.stats.impression))+'</a>';
						strMin+='<a class="icon4 theTolltip" style="width:auto;" title="Retweet ('+number_format(value.stats.rt)+')">'+smac_number2(parseInt(value.stats.rt))+'</a>';
						strMin+='<a class="icon5 theTolltip" style="width:auto;" title="Retweet Impressions ('+number_format(value.stats.rt_imp)+')">'+smac_number2(parseInt(value.stats.rt_imp))+'</a>';
						// strMin+='<a class="icon-negative theTolltip" title="Influencers">'+smac_number2(value.total)+'</a>';
						// strMin+='<a  title="Mark for Reply" onclick="#" href="#" class="reply theTolltip">&nbsp;</a> ';
						strMin+='</div><!-- .entry-action -->';
						strMin+='</div><!-- .list -->';
					});
				
				var dataTwitPlus = twitData.top_influencers.positive;
				var dataTwitMin = twitData.top_influencers.negative;
				if(dataTwitPlus.length < 1){str = "<h4 style='text-align:center;'>No data available.<h4>";}
				if(dataTwitMin.length < 1){strMin = "<h4 style='text-align:center;'>No data available.<h4>";}
				$("#twitPositiveKOL").html(str);
				$("#twitNegativeKOL").html(strMin);
			}else{
				$('.pageContent').hide();
				$('#noDashboardDataPage').delay(1000).show();
			}
	}
	
	//FB Data Collection
	function fbDataCollection(fbData){
		//Processing Data
			var topKOLImpressionArr = new Array();
			$.each(fbData.top_post, function(key, value){
				topKOLImpressionArr.push(value.share);
			});
			
			//Find Max value of Array
			var maxKOLImpression=topKOLImpressionArr[0];
			var i=0;
			while(i<topKOLImpressionArr.length){
				maxKOLImpression=Math.max(maxKOLImpression,topKOLImpressionArr[i]);
				i++;
			}
			
			//Top KOL
			var topPost = fbData.top_post;
			if(topPost.length > 0){
				$('#noDashboardDataPage').hide();

				var str="";
				var strFoto="";
				$.each(fbData.top_post, function(key, value){
					//Calculate Bar Height and Text Position
					var persen = (value.share/maxKOLImpression)*100;
					// var text = (((225*persen)/100)-25);
					// var posisi = text/2;
				
					//Chart Bar
					str+='<td valign="bottom" height="225" >';
					str+='<div class="info-imp" title="Total Impressions">';
					str+='<a href="#" class="total-imp">'+value.share+'</a>';
					str+='<span class="space" style="display:none;">|</span>';
					str+='<a class="percent-black" style="display:none;" title="Share Percentage">100%</a>';
					str+='</div>';
					str+='<div id="c'+key+'" style="background-color:#00aefe; width:65px; height:'+persen+'%; margin: 0 auto;">';
					// str+='<div id="cd'+key+'" style="margin:auto; padding:'+posisi+'px 0px 0px; height:30px; color:#FFF; text-align:center">';
					// str+='<a class="percent-white" title="Share Percentage" href="#">'+value.share+'%</a>';
					// str+='</div>';
					str+='</div>';
					str+='</td>';
					
					//Chart Foto
					strFoto+='<td valign="bottom" height="100" style=" text-align:center;">';
					strFoto+='<a href="http://www.facebook.com/'+stripslashes(value.id)+'" target="_blank" class="smallthumbs poplight relative" rel="profile" style="float:none;overflow:visible;">';
					strFoto+='<img src="'+value.img+'" width="48" height="48" style="margin:0" title="'+stripslashes(value.name)+'"/>';
					strFoto+='</a>';
					strFoto+='<p style="font-size:11px; font-weight:bolder; overflow: hidden;width: 80px;height:18px;">'+stripslashes(value.name)+'</p>';
					strFoto+='</td>';
				});
				
				$("#kolTopPeopleBarFB").html(str);
				$("#kolTopPeopleFotoFB").html(strFoto);
				
				//Top Likes
				var _category = new Array();
				var _data = new Array();
				$.each(fbData.top_like, function(key, value){
					_data.push(parseInt(value.total));
					_category.push(stripslashes(value.name));
				});
				var data = {
					name : 'Likes',
					data : _data
				};

				horizontalBarChart('kolFBLikes', _category, data);
				
				$("#tabfb-Post").show();
			}else{
				
				$('.pageContent').hide();
				$('#noDashboardDataPage').delay(1000).show();
			}

			
	}
	
	//WEB Data Collection
	function webDataCollection(webData, divWeb){
		//Processing Data
			var topKOLImpressionArr = new Array();
			$.each(webData.top_post, function(key, value){
				topKOLImpressionArr.push(value.total);
			});
			
			//Find Max value of Array
			var maxKOLImpression=topKOLImpressionArr[0];
			var i=0;
			while(i<topKOLImpressionArr.length){
				maxKOLImpression=Math.max(maxKOLImpression,topKOLImpressionArr[i]);
				i++;
			}
			
			//Top KOL
			var topPost = webData.top_post;
			if(topPost.length > 0){
				$('#noDashboardDataPage').hide();
				$('#tab'+webHead2).show();
				var str="";
				var strFoto="";
				$.each(webData.top_post, function(key, value){
				
					//Calculate Bar Height and Text Position
					var persen = (value.total/maxKOLImpression)*100;
					// var text = (((225*persen)/100)-25);
					// var posisi = text/2;
				
					//Chart Bar
					str+='<td valign="bottom" height="225" >';
					str+='<div class="info-imp" title="Total Impressions">';
					str+='<a href="#" class="total-imp">'+value.total+'</a>';
					str+='<span class="space" style="display:none;">|</span>';
					str+='<a class="percent-black" style="display:none;" title="Share Percentage">100%</a>';
					str+='</div>';
					str+='<div id="c'+key+'" style="background-color:#00aefe; width:65px; height:'+persen+'%; margin: 0 auto;">';
					// str+='<div id="cd'+key+'" style="margin:auto; padding:'+posisi+'px 0px 0px; height:30px; color:#FFF; text-align:center">';
					// str+='<a class="percent-white" title="Share Percentage" href="#">'+value.share+'%</a>';
					// str+='</div>';
					str+='</div>';
					str+='</td>';
					
					//Chart Foto
					strFoto+='<td valign="bottom" height="100" style=" text-align:center;">';
					strFoto+='<a href="#" class="smallthumbs poplight relative" rel="profile" style="float:none;overflow:visible;background:white;">';
					strFoto+='<img src="images/iconWeb2.png" width="48" height="48" style="margin:0" title="'+stripslashes(value.name)+'"/>';
					strFoto+='</a>';
					strFoto+='<p style="font-size:11px; font-weight:bolder; overflow: hidden;width: 80px;height:18px;">'+stripslashes(value.name)+'</p>';
					strFoto+='</td>';
				});
				
				$("#kolTopPeopleBar"+divWeb).html(str);
				$("#kolTopPeopleFoto"+divWeb).html(strFoto);
			}else{
				
				$('.pageContent').hide();
				$('#noDashboardDataPage').delay(1000).show();
			}
			//Top Comment
		
			// var _category = new Array();
			// var _data = new Array();
			// $.each(webData.top_comment, function(key, value){
				// _data.push(parseInt(value.total));
				// _category.push(stripslashes(value.id));
			// });
			// var data = {
				// name : 'Comments',
				// data : _data
			// };

			// horizontalBarChart('kolWebComment', _category, data);
			
			//Top Influence
			
			// var _category = new Array();
			// var _data = new Array();
			// $.each(webData.top_influences, function(key, value){
				// _data.push(parseInt(value.total));
				// _category.push(stripslashes(value.id));
			// });
			// var data = {
				// name : 'Influences',
				// data : _data
			// };

			// horizontalBarChart('kolWebInfluence', _category, data);
			
	}
	//Video Data Collection
	function videoDataCollection(videoData){
		console.log(videoData);
		//Processing Data
			var topKOLImpressionArr = new Array();
			$.each(videoData.top_post, function(key, value){
				topKOLImpressionArr.push(value.total);
			});
			
			//Find Max value of Array
			var maxKOLImpression=topKOLImpressionArr[0];
			var i=0;
			while(i<topKOLImpressionArr.length){
				maxKOLImpression=Math.max(maxKOLImpression,topKOLImpressionArr[i]);
				i++;
			}
			
			//Top KOL
			var topPost = videoData.top_post;
			if(topPost.length > 0){
				$('#noDashboardDataPage').hide();
				$('#tabVideo').show();
				var str="";
				var strFoto="";
				try{
					$.each(videoData.top_post, function(key, value){
						//Calculate Bar Height and Text Position
						var persen = (value.total/maxKOLImpression)*100;
						// var text = (((225*persen)/100)-25);
						// var posisi = text/2;
					
						//Chart Bar
						str+='<td valign="bottom" height="225" >';
						str+='<div class="info-imp" title="Total Impressions">';
						str+='<a href="#" class="total-imp">'+value.total+'</a>';
						str+='<span class="space" style="display:none;">|</span>';
						str+='<a class="percent-black" style="display:none;" title="Share Percentage">100%</a>';
						str+='</div>';
						str+='<div id="c'+key+'" style="background-color:#00aefe; width:65px; height:'+persen+'%; margin: 0 auto;">';
						// str+='<div id="cd'+key+'" style="margin:auto; padding:'+posisi+'px 0px 0px; height:30px; color:#FFF; text-align:center">';
						// str+='<a class="percent-white" title="Share Percentage" href="#">'+value.share+'%</a>';
						// str+='</div>';
						str+='</div>';
						str+='</td>';
						
						//Chart Foto
						strFoto+='<td valign="bottom" height="100" style=" text-align:center;">';
						strFoto+='<a href="#" class="smallthumbs poplight relative" rel="profile" style="float:none;overflow:visible;background:white;">';
						strFoto+='<img src="images/iconWeb2.png" width="48" height="48" style="margin:0" title="'+stripslashes(value.name)+'"/>';
						strFoto+='</a>';
						strFoto+='<p style="font-size:11px; font-weight:bolder; overflow: hidden;width: 80px;height:18px;">'+stripslashes(value.name)+'</p>';
						strFoto+='</td>';
					});
				}catch(e){}
				
				$("#kolTopPeopleBarVideo").html(str);
				$("#kolTopPeopleFotoVideo").html(strFoto);
			}else{
				// $("#kolTopPeopleBarVideo").html("No Data Available");
				$('.pageContent').hide();
				$('#noDashboardDataPage').delay(1000).show();
			}
			//Top Comment
			
			var _category = new Array();
			var _data = new Array();
			try{
				$.each(videoData.top_comment, function(key, value){
					_data.push(parseInt(value.total));
					_category.push(stripslashes(value.id));
				});
			}catch(e){}
			var data = {
				name : 'Comments',
				data : _data
			};

			horizontalBarChart('kolVideoComment', _category, data);
			
			//Top Influence
			
			var _category = new Array();
			var _data = new Array();
			$.each(videoData.top_influences, function(key, value){
				_data.push(parseInt(value.total));
				_category.push(stripslashes(value.id));
			});
			var data = {
				name : 'Influences',
				data : _data
			};

			horizontalBarChart('kolVideoInfluence', _category, data);
			
	}
function dataTable(divID, dataType, tab){
	if(tab == 'twitter'){
		var kolom =  [                                                                  
					 /* th1 */       { "bSortable": false,"bSearchable":false},
					 /* th2 */       { "bSortable": true},
					 /* th3 */       { "bSortable": true},
					 /* th4 */       { "bSortable": true},
					 /* th5 */       { "bSortable": true},
					 /* th6 */   	 { "bSortable": false,"bSearchable":false}
				  ];
	}else if(tab == 'website'){
		var kolom =  [                                                                  
					 /* th1 */       { "bSortable": false,"bSearchable":false},
					 /* th2 */       { "bSortable": true},
					 /* th3 */       { "bSortable": true},
					 /* th4 */       { "bSortable": true}
				  ];
	}else if(tab == 'video'){
		var kolom =  [                                                                  
					 /* th1 */       { "bSortable": true},
					 /* th2 */       { "bSortable": true}
				  ];
	}else{
		var kolom =  [                                                                  
					 /* th1 */       { "bSortable": false,"bSearchable":false},
					 /* th2 */       { "bSortable": true},
					 /* th3 */       { "bSortable": true},
					 /* th4 */       { "bSortable": true},
					 /* th5 */       { "bSortable": true}
				  ];
	}
	$('#'+divID+'-allpeople').dataTable({
			 "bJQueryUI": true,
			 "aaSorting": [[3,'desc']],
			 "aoColumns": kolom,
			 "sPaginationType": "full_numbers",
			  "bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": smac_api_url+'?method=kol&action='+dataType+'&campaign_id='+campaign_id+'&access_token='+access_token
			 
			 });
			 
	$('#twit-allpeople_wrapper .fg-toolbar').css('background','#44AAE1');
	$('#fb-allpeople_wrapper .fg-toolbar').css('background','#0071BB');
	$('#web-allpeople_wrapper .fg-toolbar').css('background','#F7931E');
	$('#forum-allpeople_wrapper .fg-toolbar').css('background','#AE8761');
	$('#news-allpeople_wrapper .fg-toolbar').css('background','#906EB0');
	$('#ecommerce-allpeople_wrapper .fg-toolbar').css('background','#9E0039');
	$('#corporate-allpeople_wrapper .fg-toolbar').css('background','#ABA000');
	$('#video-allpeople_wrapper .fg-toolbar').css('background','#C70000');
}

//KOL - All People
function kolAllInfluencerPositive(action, start, webType){
	//Loader
	smacLoader('twitAllPositiveKOL', 'loader-med', 'Positive Influencer');
	
	//Positive
	smac_api(smac_api_url+'?method=kol&action='+action+'&start='+start+'&exclude='+paramCheck+'&type='+webType+'',function(dataCollection){
		
		//Total People
		allTwitTotalPeoplePlus = parseInt(dataCollection.data.total);
		
		//Top Influencers
		var str = "";
			$.each(dataCollection.data.rows, function(key, value){
				str+='<div class="list">';
				if(value.img == null){var img = 'images/iconWeb2.png';}else{var img = value.img;}
				if (action == 'twitter_ambas'){
					str+='<div class="smallthumb"><a rel="profile" href="#" onclick="twitterPopup(\''+value.name+'\'); return false;"><img src="'+img+'"></a> </div>';
				}else if (action == 'fb_ambas'){
					str+='<div class="smallthumb"><a rel="profile" href="http://www.facebook.com/" target="_blank"><img src="'+img+'"></a> </div>';
				}else if (action == 'site_ambas'){
					str+='<div class="smallthumb"><a id="thumb'+value.id+'" onclick="webScreenshot(\''+value.screenshot+'\');return false;" class="poplight" style="background:white;"><img src="content/pics/thumb_'+value.screenshot+'" onerror="errorImage(this,'+value.id+');"></a></div>';
				}
				str+='<div class="entry">';
				if (action == 'site_ambas'){
					str+='<h3><a href="http://'+stripslashes(value.name)+'" target="_blank">'+stripslashes(value.name)+'</a></h3>';
				}else{
					str+='<h3>'+stripslashes(value.name)+'</h3>';
				}

				str+='</div> <!-- .entry -->';
				if (action == 'twitter_ambas'){
					if(value.stats != false){
					str+='<div class="entry-action legend" style="clear: none;padding-top: 5px;"> ';
					str+='<a class="icon2 theTolltip" style="width:auto;" title="Mentions ('+number_format(value.stats.mentions)+')">'+smac_number2(parseInt(value.stats.mentions))+'</a>';
					str+='<a class="icon3 theTolltip" style="width:auto;" title="Total Impressions ('+number_format(value.stats.impression)+')">'+smac_number2(parseInt(value.stats.impression))+'</a>';
					str+='<a class="icon4 theTolltip" style="width:auto;" title="Retweet ('+number_format(value.stats.rt)+')">'+smac_number2(parseInt(value.stats.rt))+'</a>';
					str+='<a class="icon5 theTolltip" style="width:auto;" title="Retweet Impressions ('+number_format(value.stats.rt_imp)+')">'+smac_number2(parseInt(value.stats.rt_imp))+'</a>';
					}else{
					str+='<div class="entry-action legend" style="clear: none;padding-top: 5px;"> ';
					str+='<a class="icon2 theTolltip" style="width:auto;" title="Mentions">0<span class="tip">Mentions</span></a>';
					str+='<a class="icon3 theTolltip" style="width:auto;" title="Total Impressions">0<span class="tip">Total Impressions</span></a>';
					str+='<a class="icon4 theTolltip" style="width:auto;" title="Retweet">0<span class="tip">Retweet</span></a>';
					str+='<a class="icon5 theTolltip" style="width:auto;" title="Retweet Impressions">0<span class="tip">Retweet Impressions</span></a>';
					}
				}else{
					str+='<div class="entry-action"> ';
					str+='<a class="icon-positive theTolltip" title="Influencers ('+number_format(value.total)+')">'+smac_number2(value.total)+'</a>';
				}	
				
				// str+='<a  title="Mark for Reply" onclick="#" href="#" class="reply theTolltip">&nbsp;</a> ';
				str+='</div><!-- .entry-action -->';
				str+='</div><!-- .list -->';
			});
		$("#twitAllPositiveKOL").html(str);
		
		
		
		
		//Init Page
		if(pageInitPlus == 0){
			pageInitPlus = 1;
			if(start == 0)start=1;
			smacPagination(dataCollection.data.total, start, 'cPaging', action, 'kolAllInfluencerPositive');
			kolAllInfluencerNegative(tabCheck2, 0, webType);
		}
	});
	
	
}

function kolAllInfluencerNegative(action, start, webType){
	//Loader
	smacLoader('twitAllNegativeKOL', 'loader-med', 'Negative Influencer');

	
	//Negative
	smac_api(smac_api_url+'?method=kol&action='+action+'&start='+start+'&exclude='+paramCheck+'&type='+webType+'',function(dataCollection){
		//Total People
		allTwitTotalPeopleMin = parseInt(dataCollection.data.total);
		
		var checkDataSentiment = allTwitTotalPeoplePlus+allTwitTotalPeopleMin;
		console.log(allTwitTotalPeoplePlus+','+allTwitTotalPeopleMin);
		if(checkDataSentiment > 0){
			$('#noDashboardDataPage').hide();
			$('#allPeopleTwitter').fadeIn();

			var strMin = "";
				$.each(dataCollection.data.rows, function(key, value){
					strMin+='<div class="list">';
					if(value.img == null){var img = 'images/iconWeb2.png';}else{var img = value.img;}
					if (action == 'twitter_troll'){
						strMin+='<div class="smallthumb"><a rel="profile" href="#" onclick="twitterPopup(\''+value.name+'\'); return false;"><img src="'+img+'"></a> </div>';
					}else if (action == 'fb_trolls'){
						strMin+='<div class="smallthumb"><a rel="profile" href="http://www.facebook.com/" target="_blank"><img src="'+img+'"></a> </div>';
					}else if (action == 'site_trolls'){
						strMin+='<div class="smallthumb"><a id="thumb'+value.id+'" onclick="webScreenshot(\''+value.screenshot+'\');return false;" class="poplight" style="background:white;"><img src="content/pics/thumb_'+value.screenshot+'" onerror="errorImage(this,'+value.id+');"></a></div>';
					}
					strMin+='<div class="entry">';
					if (action == 'site_trolls'){
						strMin+='<h3><a href="http://'+stripslashes(value.name)+'" target="_blank">'+stripslashes(value.name)+'</a></h3>';
					}else{
						strMin+='<h3>'+stripslashes(value.name)+'</h3>';
					}
					strMin+='</div> <!-- .entry -->';
					if (action == 'twitter_troll'){
						if(value.stats != false){
						strMin+='<div class="entry-action legend" style="clear: none;padding-top: 5px;"> ';
						strMin+='<a class="icon2 theTolltip" style="width:auto;" title="Mentions ('+number_format(value.stats.mentions)+')">'+smac_number2(parseInt(value.stats.mentions))+'<span class="tip">Mentions</span></a>';
						strMin+='<a class="icon3 theTolltip" style="width:auto;" title="Total Impressions ('+number_format(value.stats.impression)+')">'+smac_number2(parseInt(value.stats.impression))+'<span class="tip">Total Impressions</span></a>';
						strMin+='<a class="icon4 theTolltip" style="width:auto;" title="Retweet ('+number_format(value.stats.rt)+')">'+smac_number2(parseInt(value.stats.rt))+'<span class="tip">Retweet</span></a>';
						strMin+='<a class="icon5 theTolltip" style="width:auto;" title="Retweet Impressions ('+number_format(value.stats.rt_imp)+')">'+smac_number2(parseInt(value.stats.rt_imp))+'<span class="tip">Retweet Impressions</span></a>';
						}else{
							strMin+='<div class="entry-action legend" style="clear: none;padding-top: 5px;"> ';
						strMin+='<a class="icon2 theTolltip" style="width:auto;">0<span class="tip">Mentions</span></a>';
						strMin+='<a class="icon3 theTolltip" style="width:auto;">0<span class="tip">Total Impressions</span></a>';
						strMin+='<a class="icon4 theTolltip" style="width:auto;">0<span class="tip">Retweet</span></a>';
						strMin+='<a class="icon5 theTolltip" style="width:auto;">0<span class="tip">Retweet Impressions</span></a>';
						}
					}else{
						strMin+='<div class="entry-action"> ';
						strMin+='<a class="icon-positive theTolltip" title="Influencers ('+number_format(value.total)+')">'+smac_number2(value.total)+'</a>';
					}
					// strMin+='<a  title="Mark for Reply" onclick="#" href="#" class="reply theTolltip">&nbsp;</a> ';
					strMin+='</div><!-- .entry-action -->';
					strMin+='</div><!-- .list -->';
				});
			
			$("#twitAllNegativeKOL").html(strMin);
			
			
			if(titleCheck == 'Facebook' || titleCheck == 'Twitter'){
				var nameCount = 'People';
			}else{
				var nameCount = 'Website';
			}
			$("#allInfluencer").html(titleCheck+" - "+number_format(allTwitTotalPeoplePlus+allTwitTotalPeopleMin)+" "+nameCount);
			
			//Init Page
			if(pageInitMin == 0){
				pageInitMin = 1;
				if(start == 0)start=1;
				smacPagination(dataCollection.data.total, start, 'negativeKOLPaging', action, 'kolAllInfluencerNegative');
			}
		}else{
			$('#noDashboardDataPage').delay(1000).show();
		}
		
	});
}