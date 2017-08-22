// :: Workflows ::
// :: @kia ::

	//Global Variable
	var initPage = 0;
	var initHash = false;
	var pageInit = 0;
	var excludeKeywordAll;
	var folderArrList = new Array();
	var perPage;
	var totalExcludeConvers;
	var tabType;
	var recentFolder;
	var markForReply = false;
	var workflowType = 'twitter'; 
	var typeParam='';
	var bgColor = 'bgBlue';
	var containerColor = '#33CCFF';
	var refreshPage = true;
	var filterFolder = 'showAll';
	var globalFolder, globalCustom, globalExclude, globalReply;
	var excludePrevent = false;
	var channelType = 0;
	var site_type = 1;
	$(document).ready(function(){
		Backbone.emulateHTTP = true;
		Backbone.emulateJSON = true;
		
		//initial main menu
		$('.icon_workflowmenu a').addClass('current');
		
		//channel tab menu
		tabChannel(workflowType);
		
		//Backbone Router
		var Router = Backbone.Router.extend({
			routes: {
				"workflow/:type" : "workflowType",
				"folder:name:custom:exclude:reply:auto" : "folders",
				"submenu_:tab" : "submenu",
				"download_raw" : "download_raw"
			},
			workflowType: function(type){
				if(type=='' || type=='twitter'){
					workflowType = type;
					typeParam='';
					channelType = 1;
					folderArrList = new Array();
					initPage=0;
					initHash=false;
					refreshPage=false;
					recentFolder = "folder10000";
					globalFolder='1'; globalCustom='0'; globalExclude='0'; globalReply='0';
					workflowMarked(initHash);
					bgColor='bgBlue';
					containerColor='#33CCFF';
					$('#tab_container_workflows').attr('style','background:'+containerColor);
					tabChannel(workflowType);
					
					$(".theTab").hide();
					$(".subtitles a").removeClass("active");
					$("#workflow div#submenu_market").fadeIn(); //Show first tab content
					$("#btnMarked").addClass("active");
				}else if(type=='facebook'){
					channelType = 2;
					workflowType = type;
					typeParam = 'fb_';
					folderArrList = new Array();
					initPage=0;
					initHash=false;
					refreshPage=false;
					recentFolder = "folder10000";
					globalFolder='1'; globalCustom='0'; globalExclude='0'; globalReply='0';
					workflowMarked(initHash);
					bgColor='bgOldBlue';
					containerColor='#0071BB';
					$('#tab_container_workflows').attr('style','background:'+containerColor);
					tabChannel(workflowType);
					
					$(".theTab").hide();
					$(".subtitles a").removeClass("active");
					$("#workflow div#submenu_market").fadeIn(); //Show first tab content
					$("#btnMarked").addClass("active");
				}else if(type=='web'){
					channelType = 3;
					site_type = 1;
					workflowType = type;
					typeParam = 'site_';
					folderArrList = new Array();
					initPage=0;
					initHash=false;
					refreshPage=false;
					recentFolder = "folder10000";
					globalFolder='1'; globalCustom='0'; globalExclude='0'; globalReply='0';
					workflowMarked(initHash);
					bgColor='bgOrange';
					containerColor='#F7931E';
					$('#tab_container_workflows').attr('style','background:'+containerColor);
					tabChannel(workflowType);
					
					$(".theTab").hide();
					$(".subtitles a").removeClass("active");
					$("#workflow div#submenu_market").fadeIn(); //Show first tab content
					$("#btnMarked").addClass("active");
				}else if(type=='forum'){
					channelType = 3;
					site_type = 2;
					workflowType = type;
					typeParam = 'site_';
					folderArrList = new Array();
					initPage=0;
					initHash=false;
					refreshPage=false;
					recentFolder = "folder10000";
					globalFolder='1'; globalCustom='0'; globalExclude='0'; globalReply='0';
					workflowMarked(initHash);
					bgColor='bgBrown';
					containerColor='#AE8761';
					$('#tab_container_workflows').attr('style','background:'+containerColor);
					tabChannel(workflowType);
					
					$(".theTab").hide();
					$(".subtitles a").removeClass("active");
					$("#workflow div#submenu_market").fadeIn(); //Show first tab content
					$("#btnMarked").addClass("active");
				}else if(type=='news'){
					channelType = 3;
					site_type = 3;
					workflowType = type;
					typeParam = 'site_';
					folderArrList = new Array();
					initPage=0;
					initHash=false;
					refreshPage=false;
					recentFolder = "folder10000";
					globalFolder='1'; globalCustom='0'; globalExclude='0'; globalReply='0';
					workflowMarked(initHash);
					bgColor='bgViolet';
					containerColor='#906EB0';
					$('#tab_container_workflows').attr('style','background:'+containerColor);
					tabChannel(workflowType);
					
					$(".theTab").hide();
					$(".subtitles a").removeClass("active");
					$("#workflow div#submenu_market").fadeIn(); //Show first tab content
					$("#btnMarked").addClass("active");
				}else if(type=='ecommerce'){
					channelType = 3;
					site_type = 4;
					workflowType = type;
					typeParam = 'site_';
					folderArrList = new Array();
					initPage=0;
					initHash=false;
					refreshPage=false;
					recentFolder = "folder10000";
					globalFolder='1'; globalCustom='0'; globalExclude='0'; globalReply='0';
					workflowMarked(initHash);
					bgColor='bgMaroon';
					containerColor='#9E0039';
					$('#tab_container_workflows').attr('style','background:'+containerColor);
					tabChannel(workflowType);
					
					$(".theTab").hide();
					$(".subtitles a").removeClass("active");
					$("#workflow div#submenu_market").fadeIn(); //Show first tab content
					$("#btnMarked").addClass("active");
				}else if(type=='corporate'){
					channelType = 3;
					site_type = 0;
					workflowType = type;
					typeParam = 'site_';
					folderArrList = new Array();
					initPage=0;
					initHash=false;
					refreshPage=false;
					recentFolder = "folder10000";
					globalFolder='1'; globalCustom='0'; globalExclude='0'; globalReply='0';
					workflowMarked(initHash);
					bgColor='bgGreenChoc';
					containerColor='#ABA000';
					$('#tab_container_workflows').attr('style','background:'+containerColor);
					tabChannel(workflowType);
					
					$(".theTab").hide();
					$(".subtitles a").removeClass("active");
					$("#workflow div#submenu_market").fadeIn(); //Show first tab content
					$("#btnMarked").addClass("active");
				}
			},
			
			folders: function(name, custom, exclude, reply, auto){
				$("#btnMarked").addClass("active");
				recentFolder = "folder"+name+''+custom+''+exclude+''+reply+''+auto;
				globalFolder=name; globalCustom=custom; globalExclude=exclude; globalReply=reply;
				$('#tab_container_workflows').attr('style','background:'+containerColor);
					$(".theTab").hide();
					$(".subtitles a").removeClass("active");
					$("#workflow div#submenu_market").fadeIn(); //Show first tab content
					$("#btnMarked").addClass("active");
				initHash = true;
				refreshPage=false;
				// initHash=false;
				if(initPage == 0){
					workflowMarked(initHash, name, custom, exclude, reply, auto);
				}else{
					folderMarket(name, custom, exclude, reply, auto);
				}
			},
			
			submenu: function(tab){
				if(tab == '' || tab == 'market'){
					$('#tab-analyze').remove();
					folderArrList = new Array();
				
					excludePrevent=false;
					$(".theTab").hide();
					$(".subtitles a").removeClass("active");
					$("#workflow div#submenu_market").fadeIn(); //Show first tab content
					$("#btnMarked").addClass("active");
					
					initPage=0;		
					recentFolder = "folder10000";
					globalFolder='1'; globalCustom='0'; globalExclude='0'; globalReply='0';
					refreshPage=false;
					initHash=false;
					workflowMarked(initHash);
					$('#tab_container_workflows').attr('style','background:'+containerColor);
				}else if(tab == 'exclude'){
					$('#tab-analyze').remove();
					excludePrevent = true;
					$(".theTab").hide();
					$(".tab_content").hide();
					$(".subtitles a").removeClass("active");
					$("#workflow div#submenu_exclude").fadeIn(); //Show first tab content
					$("#btnExclude").addClass("active");
					
					$('#tab-initial_ex').show();
					$('#folderList_ex li').addClass('active');
					$('#folderList_ex li a').css({
						'background': containerColor,
						'color': '#FFFFFF',
					});
					initPage=0;		
					recentFolder = "folder40100";
					globalFolder='4'; globalCustom='0'; globalExclude='1'; globalReply='0';
					refreshPage=false;
					// initHash=true;
					folderMarket(4,0,1,0,0);
					$('#tab_container_workflows_ex').attr('style','background:'+containerColor);
					$('#tab_container_workflows_ex').append(analyzeDetailHtml());
				}
			},
			download_raw:function(){
				document.location="index.php?req=nO-GoMFUPta6YrpK1hIgcDqCoSt7h_Clfsw0ltvTVA8-RQEagRakbmYaN8s16RmT&c="+channelType+"&t="+site_type+"&f="+globalFolder;
				app_router.navigate('', {replace: true}); 
			}
		});
		
		var app_router = new Router;
		Backbone.history.start();
		
		//initiate when user refresh page without #hash
		if (refreshPage == true){
			$("#btnMarked").addClass("active");
			initPage=0;
			recentFolder = "folder10000";
			workflowMarked(initHash);
				$(".theTab").hide();
				$(".subtitles a").removeClass("active");
				$("#workflow div#submenu_market").fadeIn(); //Show first tab content
				$("#btnMarked").addClass("active");
		}
		
		/*------------POP UP------------*/	
		jQuery("a#btnAutoResponder").click(function(){
			$("#tab-ActiveRules").show(); //Show first tab content
		});
		
		jQuery("#tab-analyze .smallthumb a,.back-analyze").click(function(){
			var targetID = jQuery(this).attr('href');
			jQuery(".analyze-detail,.analyze").hide();
			jQuery(targetID).fadeIn();
			
			return false;
		});
		jQuery("a.tab-newfolder").click(function(){
			var targetID = jQuery(this).attr('href');
			jQuery(targetID).fadeIn();
			$('label#loadingAddFolder').hide();
			jQuery(targetID+' .content').fadeIn();
			
			return false;
		});
		
		//On Tab Click Event
		$("ul.tabsz li").live('click', function(e) {
			if(excludePrevent != true){
				$("ul.tabsz li").removeClass("active"); //Remove any "active" class
				$(this).addClass("active"); //Add "active" class to selected tab
				var id = ($("ul.tabsz li.active a").attr('href')).substr(1);
				$(".tab_content").hide(); //Hide all tab content
				$('#'+id).show(); //Fade in the active ID content
			}
		});
	});
	
	//channel
	function tabChannel(channel){
		$('#tabKol a').removeClass('active');
		if(channel=='twitter'){
			$('#tabKol a:eq(0)').addClass('active');
		}else if(channel=='facebook'){
			$('#tabKol a:eq(1)').addClass('active');
		}else if(channel=='web'){
			$('#tabKol a:eq(2)').addClass('active');
		}else if(channel=='forum'){
			$('#tabKol a:eq(3)').addClass('active');
		}else if(channel=='news'){
			$('#tabKol a:eq(4)').addClass('active');
		}else if(channel=='ecommerce'){
			$('#tabKol a:eq(5)').addClass('active');
		}else if(channel=='corporate'){
			$('#tabKol a:eq(6)').addClass('active');
		}
	}
	
	function hideShow(hash, folderID, custom, exclude, reply, auto){
		//TAB...
		$(".tab_content").hide(); //Hide all content
		if (hash == false){
			$("ul.tabsz li:first").addClass("active").show(); //Activate first tab
			$(".tab_content:first").show(); //Show first tab content
		}else{
			$("ul.tabsz li.folder"+folderID).addClass("active").show(); //Activate specific tab
			$("#folder"+folderID+''+custom+''+exclude+''+reply+''+auto).show(); //Show specific tab content
		}
	}
	
	/**
	 * showing workflow folders and containers.
	 * @param {Object} hash
	 * @param {Object} folderID
	 * @param {Object} custom
	 * @param {Object} exclude
	 * @param {Object} reply
	 * @param {Object} auto
	 */
	function workflowMarked(hash, folderID, custom, exclude, reply, auto){
		//Loader
		smacLoader('folderList', 'loader', 'Folders');
		smacLoader('tab-initial', 'loader-med', 'Keywords');
		
		smac_api(smac_api_url+'?method=workflow&action='+typeParam+'folders&type='+site_type,function(dataCollection){
			
			var str='';
			var strContainer = '';
			var folderList = dataCollection.data.folders;
			var allLength = folderList.length;
			var showFolder=true;
			$.each(folderList, function(k,v){
				if(typeParam == 'site_'){
					if(v.folder_id == 2){showFolder = false;}
					else {showFolder = true;}
				}
				
				if(showFolder){
					if(k != 4){
						if (k != 5){
							folderArrList.push({id : v.folder_id, name : v.folder_name});
						}
					};
					if(k != 3){
						var add = k+1;
						var stripspace = v['folder_name'].replace(/ /g,'');
						str+='<li class="folder'+v['folder_id']+'"><a class="'+bgColor+'" href="#folder'+v['folder_id']+''+v.custom+''+v.exclude+''+v.reply+''+v.auto+'">'+v['folder_name']+' (<span>'+v['total']+'</span>)</a></li>';
						strContainer+='<div class="tab_content" id="folder'+v['folder_id']+''+v.custom+''+v.exclude+''+v.reply+''+v.auto+'" style="display: block;"></div>';
						if (add == allLength){
							str+=' <li class="newFolder"><a href="#tab-newfolder" class="tab-newfolder" >Add New Folder</a></li>';
							strContainer+=addFolderHtml();
							strContainer+=analyzeDetailHtml();
						}
					}
				}
			});
			$('#folderList').html(str);
			$('#tab_container_workflows').html(strContainer);
			var viewTypeFilter = '<div id="viewTypeFilter" style="text-align: right;height: 25px;"><a class="workflow-raw" href="#/download_raw">Download Raw</a><label style="color:white;margin: 0 10px 0 0;">View Type:</label><select id="filter-view"><option value="0">View All</option><option value="1">View per Keyword</option></select></div>';
			
			$('#tab_container_workflows').prepend(viewTypeFilter);
			hideShow(hash, folderID,custom,exclude,reply,auto);
			if (initPage == 0){
				if(folderID == null){folderID='1';}
				if(custom == null){custom='0';}
				if(exclude == null){exclude='0';}
				if(reply == null){reply='0';}
				if(auto == null){auto='0';}
				folderMarket(folderID,custom,exclude,reply,auto);
				initPage = 1;
			}
		});
	}
	
	function folderMarket(folderName, custom, exclude, reply, auto){
		var folderCall =  folderName+custom+exclude+reply+auto;
		if(reply == '1'){
			markForReply = true;
		}else if(reply == '0'){
			markForReply = false;
		}
		
		if (auto == '0'){
			
		}else{
			if(auto == '1'){
				filterFolder = 'showAll';
			}else if(auto == '2'){
				filterFolder = 'showAll';
			}
		}
		//Loader
		smacLoader('folder'+folderCall, 'loader-med', 'Keywords');
	
		if(exclude == '0'){
			$('#viewTypeFilter').show();
			if(filterFolder == 'showAll'){
				//head
				var headStr='';
				headStr+='<div id="viewAllContent_'+folderName+'"></div>';
				headStr+='<div id="viewAllContentPaging_'+folderName+'" class="paging"></div>';
				$('#folder'+folderCall).html(headStr);
				twitList('keyAll_'+folderName+'_'+auto, 0);
				if(custom == '1'){
					$('#folder'+folderCall).prepend('<div id="customhead"><a onclick="removeMarketFolder('+folderName+'); return false;" style="float: right;position: absolute;right: 21px;top: 37px; width: 200px;" class="tplbtn" href="#">Remove These Folder</a></div>');
				}
			}else{
				smac_api(smac_api_url+'?method=workflow&action='+typeParam+'keywords&folder_id='+folderName+'&type='+site_type,function(dataCollection){
					
					if(dataCollection.data != null){
						var str='';
						$.each(dataCollection.data, function(k,v){
							// var kw = v['keyword'].replace("/","_");
							if(v.keyword == 'N/A'){
								var kw = 'NA';
							}else{
								var kw = v.keyword;
							}
							str+='<h2 id="'+kw+'_'+folderName+'_h2" class="topic-group" style="overflow: visible;"><a onclick="twitList(\''+kw+'_'+folderName+'\', 0); return false;" class="showGroup" href="#">'+v.keyword+' (<span class="'+kw+'_'+folderName+'">'+v['total']+'</span>)</a>';
							str+='<span class="move_all"><a href="#" style="color:#ffffff;" onclick="folderList(null, \''+folderName+'\',\''+kw+'\');return false;">Move all</a></span>';
								str+='<div id="selecfolder-'+kw+'_'+folderName+'" class="selecfolder" style="display: none;left: 445px;top: 5px; ">';
									// str+='<a href="#selecfolder-'+v.keyword+'" class="active-rightarrow" style="display: block;" onclick="return false;">&nbsp;</a>';
									str+='<div style="margin: 0 0 0 5px;line-height: 16px;height:30px;width:175px;border-bottom:1px solid #777777">';
										str+='<span style="float:left;"><h3>Move all to :</h3></span>';
										str+='<span style="float:right;"><a href="javascript:void(0);" onclick="close_folder_globals();">X</a></span>';
									str+='</div>';
									str+='<div id="list-'+kw+'_'+folderName+'"></div>';
								str+='</div>';
							str+='</h2>';
							str+='<div id="'+kw+'_'+folderName+'" class="content" display="display:none">';
							str+='<div id="'+kw+'_'+folderName+'_list"></div>';
							str+='<div id="'+kw+'_'+folderName+'_paging" class="paging" style="padding: 0;"></div>';
							str+='</div>';
						});
						$('#folder'+folderCall).html(str);
					}else{
						var noData ='<div align="center" class="not-found-data"><img src="images/smac-no-items.png"></div>';
						$('#folder'+folderCall).html(noData);
					}
					if(custom == '1'){
						$('#folder'+folderCall).prepend('<div id="customhead"><a onclick="removeMarketFolder('+folderName+'); return false;" style="float: right;position: absolute;right: 21px;top: 37px; width: 200px;" class="tplbtn" href="#">Remove These Folder</a></div>');
					}
				});
			}
		}else if(exclude == '1'){
			$('#viewTypeFilter').hide();
			//head
			var headStr='';
			headStr+='<div class="mtitle" style="height: 45px;margin:0;background: '+containerColor+'">';
			headStr+='<span id="exc-apply" style="display:none;"><a onclick="" style="color:#ffffff;" href="#">Apply</a></span>';
			headStr+='<div style="padding: 15px 15px 0 0;" class="wfilters">';            
			headStr+='<label style="color:white;">Filter by:</label>'; 
			headStr+='<select id="filter-kol">'; 
			headStr+='<option value="">Everything</option>';
			headStr+='</select>'; 
			headStr+='</div></div>';
			headStr+='<div id="excludeContent"></div>';
			headStr+='<div id="excludeContentPaging" class="paging"></div>';
			$('#tab-initial_ex').html(headStr);
			
			//Generating Keyword			
			smac_api(smac_api_url+'?method=workflow&action='+typeParam+'keywords&folder_id='+folderName+'',function(dataCollection){
				if(dataCollection.data != null){
					var str='';
					$.each(dataCollection.data, function(k,v){
						str+='<option id="op_'+v.keyword+'" value="'+v.keyword+'">'+v.keyword+'</option>';
					});
					$('#filter-kol').append(str);
				}
			});
			
			//Initial Conversation
			excludeTweet("", 0, folderName);
		}
	}
	
	//Filter on Exclude Tab
	$('#filter-view').live('change', function(){
		var viewTypeSelected = $(this).val();
		if(viewTypeSelected == '0'){
			filterFolder = 'showAll';
		}else if(viewTypeSelected == '1'){
			filterFolder = 'perKeyword';
		}
		folderMarket(globalFolder, globalCustom, globalExclude, globalReply, 0);
	});
	
	//Filter on Exclude Tab
	$('#filter-kol').live('change', function(){
		var keyword = $(this).val();
		excludeTweet(keyword, 0, 4);
		if(keyword.length > 0){
			$('#exc-apply').show();
			$("#exc-apply").click(function(){
				apply_exclude_all(keyword,channelType,site_type);
			});
		}else{
			$('#exc-apply').hide();
		}
	});
	
	function excludeTweet(filter, start, folderName){
		
		tabType = 'exclude';
		//Loader
		smacLoader('excludeContent', 'loader-med', 'Conversation');
		
		smac_api(smac_api_url+'?method=workflow&action='+typeParam+'excluded_feeds&filter_by='+filter+'&start='+start+'&type='+site_type,function(dataCollection){
			$('#folderList_ex li a span').html(dataCollection.data.total);
			if(dataCollection.data.feeds != null){
				var str='';
				perPage = 0;
				$.each(dataCollection.data.feeds, function(k,v){
					perPage++;
					str+='<div id="divList_'+v.feed_id+'" class="list">';
						str+='<div class="tweetcontent">';
							str+='<div class="smallthumb">';
								str+='<a rel="profile" href="#" onclick="analyzeDetails(\''+v.author_id+'\');return false;">';
									str+='<img src="'+v.avatar_pic+'">';
								str+='</a>';
							str+='</div>';
							str+='<div class="entry">';
								str+='<h3>'+v.author_name+'</h3>';
								str+='<span>'+v.content+'</span>';
							str+='</div><!-- .entry -->';
							str+='<div class="entry-action">';
								if(v.generator == 'blackberry') var bb='active';
								if(v.generator == 'apple') var apple='active';
								if(v.generator == 'android') var android='active';
								str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
								str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
								str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
								if (v.rt_imp != null) {var retweet = v.rt_imp}else{ var retweet = 0};
								str+='<a style="margin-left: 15px;" class="icon-rts tip_trigger theTolltip" title="Retweet Frequency">'+retweet+'</a> ';
								if (v.imp != null) {var imp = v.imp}else{ var imp = 0};
								str+='<a class="icon-imp tip_trigger theTolltip" title="Total Impressions"> '+imp+'</a> ';
								// str+='<a class="icon-share tip_trigger">0.00028% <span class="tip">Share</span></a>';
							str+='</div><!-- .entry-action -->';
						str+='</div> <!-- .tweetcontent -->';  
						str+='<div style="min-width:105px;" class="grey-box">';
						    if(v.is_deleted==1){
						    	//hide temporarily
						    	//str+='<a onclick="apply_undo(\''+v.feed_id+'\',1)" style="float:right;" title="Undo Exclusion" class="icon_undo theTolltip" href="javascript:void(0);" id="btn_undo'+v.feed_id+'">&nbsp;</a>';
						    }else{
								str+='<a onclick="apply_exclude(\''+v.feed_id+'\','+channelType+','+site_type+')" style="float:right;" title="Apply to Topic" class="icon_workflow theTolltip" href="javascript:void(0);" id="btn_apply'+v.feed_id+'">&nbsp;</a>';
							}
							str+='<a class="rightarrow" href="#selecfolder-'+v.feed_id+'" onclick="folderList(\''+v.feed_id+'\',\''+folderName+'\',\''+v.keyword+'\'); return false;" style="display: block;float:left;position:absolute;left:6px;">&nbsp;</a>';
							str+='<div id="selecfolder-'+v.feed_id+'" class="selecfolder" style="display: none; ">';
								str+='<a href="#selecfolder-'+v.feed_id+'" class="active-rightarrow" style="display: block;" onclick="return false;">&nbsp;</a>';
								str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777">';
									str+='<span style="float:left;"><h3>Move to :</h3></span>';
									str+='<span style="float:right;"><a href="javascript:void(0);" onclick="close_folder_globals();">X</a></span>';
								str+='</div>';
								str+='<div id="list-'+v.feed_id+'_ex"></div>';
							str+='</div>';
						str+='</div></div>';
				});
				$('#excludeContent').html(str);
				totalExcludeConvers = parseInt(dataCollection.data.total);
				//Init Page
				if(start == 0){
					if(start == 0)start=1;
					smacPagination(parseInt(dataCollection.data.total), start, 'excludeContentPaging', filter, 'excludeTweet');
				}
			}else{
				var noData ='<div align="center" class="not-found-data"><img src="images/smac-no-items.png"></div>';
				$('#tab-initial_ex').html(noData);
			}
			$("#tab-initial_ex").fadeIn();
		});
	}
	
	function twitList(folderANDKey, start){
		var action;
		var addKeyword;
		tabType = 'default';
		var arrKF = folderANDKey.split("_");
		var keyword = arrKF[0];
		var folderName = arrKF[1];
		var auto = arrKF[2];
		perPage = 0;
		if(keyword == 'keyAll'){
			action='all_feeds';
			addKeyword='';
			//Loader
			smacLoader('viewAllContent_'+folderName, 'loader-med', 'All Feeds');
		}else{
			action='feeds';
			if (keyword == 'NA'){
				keyword = 'N/A';
			}
			addKeyword='&keyword='+keyword;
			//Loader
			smacLoader(folderANDKey+'_list', 'loader-med', 'Keywords');
		}
		
		if(auto == '1'){
			$('#viewTypeFilter').hide();
			tabType = 'sentiment';
			var apiURL = '?method=workflow&action='+typeParam+'folder_sentiment&type=1&group='+site_type+'&start='+start;
		}else if(auto == '2'){
			var apiURL = '?method=workflow&action='+typeParam+'folder_sentiment&type=2&group='+site_type+'&start='+start;
			tabType = 'sentiment';
			$('#viewTypeFilter').hide();
		}else{
			var apiURL = '?method=workflow&action='+typeParam+''+action+'&folder_id='+folderName+''+addKeyword+'&start='+start+'&type='+site_type;
			$('#viewTypeFilter').show();
		}

		smac_api(smac_api_url+apiURL,function(dataCollection){
			
			if(dataCollection.data.feeds != null){
				var str='';
				$.each(dataCollection.data.feeds, function(k,v){
					perPage++;
					str+='<div id="divList_'+v.feed_id+'" class="list">';
					if(markForReply == true){
						str+='<div class="tweetcontent" style="max-width: 47%;">';
					}else{
						str+='<div class="tweetcontent" style="max-width: 78%;">';
					}
					
					if(workflowType=='twitter'){
						var channelFlag = '';
						str+='<div class="smallthumb"> ';
							str+='<a rel="profile" href="#" onclick="analyzeDetails(\''+v.author_id+'\');return false;">';
							str+='<img src="'+v.avatar_pic+'"></a>';
						str+='</div>';
						str+='<div class="entry">';
							str+='<h3>'+stripslashes(v.author_name)+'</h3>';
							str+='<span style="word-wrap: break-word;">'+stripslashes(v.content)+'</span>';
						str+='</div><!-- .entry -->';
						str+='<div class="entry-action">';
						if(v.generator == 'blackberry') var bb='active';
						if(v.generator == 'apple') var apple='active';
						if(v.generator == 'android') var android='active';
						str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
						str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
						str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
						if (v.rt_imp != null) {var retweet = v.rt_imp}else{ var retweet = 0};
						str+='<a style="margin-left: 15px;" class="icon-rts theTolltip" title="Retweet Frequency ('+number_format(retweet)+')">'+smac_number2(retweet)+'</a> ';
						if (v.imp != null) {var imp = v.imp}else{ var imp = 0};
						str+='<a class="icon-imp tip_trigger theTolltip" title="Total Impressions ('+number_format(imp)+')">'+smac_number2(imp)+'</a> ';
						//str+='<a class="icon-share tip_trigger">0.039% <span class="tip">Share</span></a> ';
						str+='</div><!-- .entry-action -->';
					}else if(workflowType=='facebook'){
						var channelFlag = 'fb_';
						str+='<div class="smallthumb"> ';
							str+='<a rel="profile" href="#" onclick="analyzeDetails(\''+v.from_object_id+'\');return false;">';
							str+='<img src="https://graph.facebook.com/'+v.from_object_id+'/picture"></a>';
						str+='</div>';
						str+='<div class="entry">';
							str+='<h3>'+stripslashes(v.from_object_name)+'</h3>';
							str+='<span style="word-wrap: break-word;">'+stripslashes(v.message)+'</span>';
						str+='</div><!-- .entry -->';
						str+='<div class="entry-action">';
						if(v.application_object_name == 'blackberry') var bb='active';
						if(v.application_object_name == 'apple') var apple='active';
						if(v.application_object_name == 'android') var android='active';
						str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
						str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
						str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
						if (v.likes_count != null) {var likesCount = v.likes_count}else{ var likesCount = 0};
						str+='<a style="margin-left: 15px;" class="icon-likes theTolltip" title="Likes Count ('+number_format(likesCount)+')">'+smac_number2(likesCount)+'</a> ';
						str+='</div><!-- .entry-action -->';
					}else if(workflowType=='web'){
						var channelFlag = 'site_';
						str+='<div class="smallthumb"> ';
							str+='<a class="poplight" rel="profile" href="#" onclick="analyzeDetails(\''+v.author_name+'\');return false;" style="background:white;">';
							str+='<img src="images/iconWeb2.png"></a>';
						str+='</div>';
						str+='<div class="entry">';
							str+='<h3>'+stripslashes(v.author_name)+'</h3>';
							str+='<span style="word-wrap: break-word;">'+stripslashes(v.content)+'</span>';
						str+='</div><!-- .entry -->';
						str+='<div class="entry-action">';
						if(v.generator == 'blackberry') var bb='active';
						if(v.generator == 'apple') var apple='active';
						if(v.generator == 'android') var android='active';
						str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
						str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
						str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
						if (v.comments != null) {var komen = v.comments}else{ var komen = 0};
						str+='<a style="margin-left: 15px;" class="icon-posts theTolltip" title="Comments ('+number_format(komen)+')">'+smac_number2(komen)+'</a> ';
						str+='</div><!-- .entry-action -->';
					}else if(workflowType=='forum'){
						var channelFlag = 'site_';
						str+='<div class="smallthumb"> ';
							str+='<a class="poplight" rel="profile" href="#" onclick="analyzeDetails(\''+v.author_name+'\');return false;" style="background:white;">';
							str+='<img src="images/iconWeb2.png"></a>';
						str+='</div>';
						str+='<div class="entry">';
							str+='<h3>'+stripslashes(v.author_name)+'</h3>';
							str+='<span style="word-wrap: break-word;">'+stripslashes(v.content)+'</span>';
						str+='</div><!-- .entry -->';
						str+='<div class="entry-action">';
						if(v.generator == 'blackberry') var bb='active';
						if(v.generator == 'apple') var apple='active';
						if(v.generator == 'android') var android='active';
						str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
						str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
						str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
						if (v.comments != null) {var komen = v.comments}else{ var komen = 0};
						str+='<a style="margin-left: 15px;" class="icon-posts theTolltip" title="Comments ('+number_format(komen)+')">'+smac_number2(komen)+'</a> ';
						str+='</div><!-- .entry-action -->';
					}else if(workflowType=='news'){
						var channelFlag = 'site_';
						str+='<div class="smallthumb"> ';
							str+='<a class="poplight" rel="profile" href="#" onclick="analyzeDetails(\''+v.author_name+'\');return false;" style="background:white;">';
							str+='<img src="images/iconWeb2.png"></a>';
						str+='</div>';
						str+='<div class="entry">';
							str+='<h3>'+stripslashes(v.author_name)+'</h3>';
							str+='<span style="word-wrap: break-word;">'+stripslashes(v.content)+'</span>';
						str+='</div><!-- .entry -->';
						str+='<div class="entry-action">';
						if(v.generator == 'blackberry') var bb='active';
						if(v.generator == 'apple') var apple='active';
						if(v.generator == 'android') var android='active';
						str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
						str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
						str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
						if (v.comments != null) {var komen = v.comments}else{ var komen = 0};
						str+='<a style="margin-left: 15px;" class="icon-posts theTolltip" title="Comments ('+number_format(komen)+')">'+smac_number2(komen)+'</a> ';
						str+='</div><!-- .entry-action -->';
					}else if(workflowType=='ecommerce'){
						var channelFlag = 'site_';
						str+='<div class="smallthumb"> ';
							str+='<a class="poplight" rel="profile" href="#" onclick="analyzeDetails(\''+v.author_name+'\');return false;" style="background:white;">';
							str+='<img src="images/iconWeb2.png"></a>';
						str+='</div>';
						str+='<div class="entry">';
							str+='<h3>'+stripslashes(v.author_name)+'</h3>';
							str+='<span style="word-wrap: break-word;">'+stripslashes(v.content)+'</span>';
						str+='</div><!-- .entry -->';
						str+='<div class="entry-action">';
						if(v.generator == 'blackberry') var bb='active';
						if(v.generator == 'apple') var apple='active';
						if(v.generator == 'android') var android='active';
						str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
						str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
						str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
						if (v.comments != null) {var komen = v.comments}else{ var komen = 0};
						str+='<a style="margin-left: 15px;" class="icon-posts theTolltip" title="Comments ('+number_format(komen)+')">'+smac_number2(komen)+'</a> ';
						str+='</div><!-- .entry-action -->';
					}else if(workflowType=='corporate'){
						var channelFlag = 'site_';
						str+='<div class="smallthumb"> ';
							str+='<a class="poplight" rel="profile" href="#" onclick="analyzeDetails(\''+v.author_name+'\');return false;" style="background:white;">';
							str+='<img src="images/iconWeb2.png"></a>';
						str+='</div>';
						str+='<div class="entry">';
							str+='<h3>'+stripslashes(v.author_name)+'</h3>';
							str+='<span style="word-wrap: break-word;">'+stripslashes(v.content)+'</span>';
						str+='</div><!-- .entry -->';
						str+='<div class="entry-action">';
						if(v.generator == 'blackberry') var bb='active';
						if(v.generator == 'apple') var apple='active';
						if(v.generator == 'android') var android='active';
						str+='<a class="'+bb+'" href="#"><span class="blackberry">&nbsp;</span></a>';
						str+='<a class="'+apple+'" href="#"><span class="apple">&nbsp;</span></a>'; 
						str+='<a class="'+android+'" href="#"><span class="android">&nbsp;</span></a>';
						if (v.comments != null) {var komen = v.comments}else{ var komen = 0};
						str+='<a style="margin-left: 15px;" class="icon-posts theTolltip" title="Comments ('+number_format(komen)+')">'+smac_number2(komen)+'</a> ';
						str+='</div><!-- .entry-action -->';
					}
					
					str+='</div> <!-- .tweetcontent --> ';  

					
						if(auto == '1' || auto == '2'){
						str+='<div class="grey-box">';
						}else{
						str+='<div class="grey-box" style="min-width: 105px;">';
						str+='<a onclick="unFlagThis(\''+channelFlag+'\', \''+v.feed_id+'\', \''+folderName+'\',\''+folderANDKey+'\'); return false;" style="float:right;" title="Undo Mark" class="icon_undo theTolltip" href="javascript:void(0);">&nbsp;</a>';
						}
						
						if (workflowType != 'twitter'){
							if(auto == '1' || auto == '2'){
							var feedIDbyChannel = v.fid;
							}else{
								var feedIDbyChannel = v.feed_id;
							}
						}else{
							var feedIDbyChannel = v.feed_id;
						}
						str+='<a class="rightarrow theTolltip" title="Move to Folder" href="#selecfolder-'+feedIDbyChannel+'" onclick="folderList(\''+feedIDbyChannel+'\',\''+folderName+'\',\''+arrKF[0]+'\'); return false;" style="display: block;float:left;position:absolute;left:6px;">&nbsp;</a>';
						
						str+='<div id="selecfolder-'+feedIDbyChannel+'" class="selecfolder" style="display: none; ">';
							str+='<a href="#selecfolder-'+feedIDbyChannel+'" class="active-rightarrow" style="display: block;" onclick="return false;">&nbsp;</a>';
							str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777">';
								str+='<span style="float:left;"><h3>Move to :</h3></span>';
								str+='<span style="float:right;"><a href="javascript:void(0);" onclick="close_folder_globals();">X</a></span>';
							str+='</div>';
							str+='<div id="list-'+feedIDbyChannel+'"></div>';
						str+='</div>';
						
					str+='</div>';
					
					if(markForReply == true && v.reply_date == null){
						//mark for reply
						str+='<div class="commentbox" id="wf_comment_'+v.feed_id+'" style="float: right;">';
							str+='<textarea id="replyText'+v.feed_id+'" name="replyText'+v.feed_id+'" onkeyup="countChar(this);"></textarea>';
							str+='<span id="charNum" class="count-char">140</span>';
							str+='<input type="button" onclick="workflow_reply(\''+v.feed_id+'\',\''+v.author_id+'\',\''+folderName+'\');" class="send-reply" value="SEND REPLY">';
						str+='</div>';  
					}else if(markForReply == true && v.reply_date != null){
						str+='<div class="commentbox" id="wf_comment_'+v.feed_id+'" style="float: right;">';
						str+='<span class="message-sent">Replied at '+v.reply_date+'</span>';
						str+='</div>';  
					}
					
					str+='</div>';
				});
				
				if(keyword == 'keyAll'){
					$('#viewAllContent_'+folderName).html(str);
					
					if(markForReply == false){
						// $('.tweetcontent span').css({width: '560px', display: 'block'});
					}
					
					//Init Page
					if(start == 0){
						if(start == 0)start=1;
						if(auto != '0'){
							smacPagination(dataCollection.data.total_rows, start, 'viewAllContentPaging_'+folderName, 'keyAll_'+folderName+'_'+auto, 'twitList');
						}else{
							smacPagination(dataCollection.data.total, start, 'viewAllContentPaging_'+folderName, 'keyAll_'+folderName, 'twitList');
						}
					}
				}else{
					$('#'+folderANDKey+'_list').html(str);
					$('.content').hide();
					$('#'+arrKF[0]+'_'+folderName).show();
					
					//Init Page
					if(start == 0){
						if(start == 0)start=1;
						smacPagination(dataCollection.data.total, start, folderANDKey+'_paging', folderANDKey, 'twitList');
					}
				}
			}else{
				if(keyword == 'keyAll'){
					var noData ='<div align="center" class="not-found-data"><img src="images/smac-no-items.png"></div>';
					$('#viewAllContent_'+folderName).html(noData);
				}else{
					var noData ='<div align="center" class="not-found-data"><img src="images/smac-no-items.png"></div>';
					$('#'+arrKF[0]+'_'+folderName).html(noData);
					$('.content').hide();
					$('#'+arrKF[0]+'_'+folderName).show();
				}
			}
		});
	}
	
	//Show Folder List
	function folderList(feed_id, from, keyword){
		if (keyword == 'N/A'){
			keyword = 'NA';
		}
		var str='';
		$('.selecfolder').hide();
		if(feed_id != null){		
			$('#list-'+feed_id+'').html('');
			$('#list-'+feed_id+'_ex').html('');
			$.each(folderArrList, function(k,v){
				if (tabType == 'sentiment'){
					str+='<a href="#" class="listfolder" onclick="mark_sentiment(\''+feed_id+'\',\''+workflowType+'\',\''+v.id+'\',\''+from+'\'); return false;">'+v.name+'</a>';	
				}else{
					str+='<a href="#" class="listfolder" onclick="moveToFolder(\''+from+'\',\''+v.id+'\',\''+feed_id+'\',\''+keyword+'\'); return false;">'+v.name+'</a>';	
				}
			});
			$('#list-'+feed_id+'').html(str);
			$('#list-'+feed_id+'_ex').html(str);
			$('#selecfolder-'+feed_id+'').fadeIn();
			$('#selecfolder-'+feed_id+' a').show();
		}else{
			$('#list-'+keyword+'_'+from).html('');
			$('#list-'+keyword+'_ex').html('');
		
			$.each(folderArrList, function(k,v){
				str+='<a href="#" class="listfolder" onclick="moveToFolder(\''+from+'\',\''+v.id+'\', null,\''+keyword+'\'); return false;">'+v.name+'</a>';	
			});
			$('#list-'+keyword+'_'+from).html(str);
			$('#list-'+keyword+'_'+from+'_ex').html(str);
			$('#selecfolder-'+keyword+'_'+from).fadeIn();
			$('#selecfolder-'+keyword+'_'+from+' a').show();
		}
	}
	
	function close_folder_globals(){
		$('.selecfolder').hide();
	}
	
	//Move to folder
	function moveToFolder(from, to, feedID, keyword){
		
		if(from != to){
			if (feedID != null){
				smac_post(smac_api_url,{method:'workflow',action:''+typeParam+'move',from_folder:from,to_folder:to,feed_id:feedID,type: site_type},function(dataCollection){
					
					
					var totalConversFrom = parseInt($('li.folder'+from+' a span').html()) - 1;
					$('li.folder'+from+' a span').html(totalConversFrom);
					
					var totalConversTo = parseInt($('li.folder'+to+' a span').html()) + 1;
					$('li.folder'+to+' a span').html(totalConversTo); 
					
					if(tabType == 'default'){
						var totalConversToTab = parseInt($('.'+keyword+'_'+from).html()) - 1;
						perPage = perPage - 1;
						
						$('.'+keyword+'_'+from).html(totalConversToTab); 
						if(totalConversToTab <= 0){
							$('#'+keyword+'_'+from+'_paging').remove();
							$('#'+keyword+'_'+from+'_h2').remove();
						}else if(perPage <= 0){
							twitList(keyword+'_'+from, 0);
						}
					}else if(tabType == 'exclude'){
						totalExcludeConvers = totalExcludeConvers - 1;
						perPage = perPage - 1;
						

						if(totalExcludeConvers <= 0){
							$('#excludeContentPaging').hide();
							$('#op_'+keyword).remove();
							$('#exc-apply').hide();
							excludeTweet("", 0, from);
						}else if(perPage <= 0){
							excludeTweet(keyword, 0, from);
						}
					}
					
					
					
					$('#divList_'+feedID+'').remove(); 
				});
			}else{
				if (keyword == 'NA'){
					var realKey = 'N/A';
				}

				smac_post(smac_api_url,{method:'workflow',action:''+typeParam+'move_all',from_folder:from,to_folder:to,keyword:realKey,type: site_type},function(dataCollection){
					
					var totalConversFrom = parseInt($('li.folder'+from+' a span').html()) - parseInt($('.'+keyword+'_'+from).html());
					$('li.folder'+from+' a span').html(totalConversFrom);
					
					var totalConversTo = parseInt($('li.folder'+to+' a span').html()) + parseInt($('.'+keyword+'_'+from).html());
					$('li.folder'+to+' a span').html(totalConversTo); 
					
					$('#'+keyword+'_'+from+'_h2').remove();
					$('#'+keyword+'_'+from).remove();
					
					
				});
			}
		}
	}
	
	//Add Market Folder
	function addMarketFolder(){
		var folderName = $('input#newFolderName').val();
		if(folderName.length > 0){
			$('#loadingAddFolder').html('Loading...');
			$('#addNewFolderBtn').hide();
			smac_post(smac_api_url,{method:'workflow',action:'add_folder',folder_name:folderName},function(dataCollection){
				
				var data = dataCollection.data;
				if(dataCollection.status == 1){
					var str='<li class="folder'+data.folder_id+'"><a href="#folder'+data.folder_id+'100">'+data.folder_name+' (<span>0</span>)</a></li>';
					var strContainer='<div class="tab_content" id="folder'+data.folder_id+'100" style="display: none;"></div>';

					$('li.newFolder').before(str);
					$('#tab_container_workflows').append(strContainer);
					
					$('#loadingAddFolder').html('Folder <span style="color: #666666;">"'+folderName+'"</span> has been added.');
					$('#newFolderName').val('');
				}else{
					$('#loadingAddFolder').html('Add folder <span style="color: #666666;">"'+folderName+'"</span> is failed, please try again.');
				}
				$('#addNewFolderBtn').show();
			});
			
		}else{
			// alert("insert folder name first");
		}
	}
	
	//Remove Market Folder
	function removeMarketFolder(folderID){
		if(folderID != null){
			smac_post(smac_api_url,{method:'workflow',action:'remove_folder',folder_id:folderID},function(dataCollection){
				
				$('.folder'+folderID).remove();
				$(".tab_content").hide(); //Hide all tab content
				$("ul.tabsz li:first").addClass("active").show(); //Activate first tab
				$(".tab_content:first").show(); //Show first tab content
			});	
		}else{
			// alert("insert folder name first");
		}
	}
	
	function analyzeDetails(author_id){
		$(".tab_content").hide(); //Hide all tab content
		$('#tab-analyze').show(); 
		$('#tab-analyze .content').show(); //Fade in the active ID content
		
		//Loader
		smacLoader('an_wordcloud', 'loader-med', 'Wordcloud');
		
		smac_api(smac_api_url+'?method=workflow&action='+typeParam+'person&person='+author_id+'',function(dataCollection){
			
			if(workflowType=='twitter'){
				//Show container
				$('#wf_influenced_by').show();
				$('#wf_influencer_of').show();
				$('#wf_map').show();
				
				var dataSum = dataCollection.data.summary;
				$('#aUserID').html('@'+dataSum.author_id+' - '+dataSum.author_name);
				$('#author_about').html(': '+dataSum.about);
				$('#author_location').html(': '+dataSum.location);
				$('#wf_profile_box').prepend('<a style="float:none;overflow:visible;left:12px;top:12px;" rel="profile" class="smallthumbs absolute" onclick="twitterPopup(\''+dataSum.author_id+'\',\''+dataSum.author_name+'\'); " href="#"><img width="48" height="48" title="'+dataSum.author_name+'" style="margin:0" src="'+dataSum.author_avatar+'"></a>');
				$('#wf_exclude_btn').html('<a href="#" onclick="tgl_exc_person(\''+dataSum.author_id+'\',\''+dataSum.remove_link+'\');return false;">Exclude These Person</a>');
			}else if(workflowType=='facebook'){
				var dataSum = dataCollection.data;
				$('#aUserID').html('Facebook ID: '+dataSum.username+' - '+dataSum.name);
				$('#author_about').html(': '+dataSum.gender);
				$('#author_location').html(': '+dataSum.possible_country);
				$('#wf_profile_box').prepend('<a style="float:none;overflow:visible;left:12px;top:12px;" rel="profile" class="smallthumbs absolute" href="'+dataSum.link+'" target="_blank"><img width="48" height="48" title="'+dataSum.name+'" style="margin:0" src="https://graph.facebook.com/'+dataSum.id+'/picture"></a>');
				$('#wf_exclude_btn').html('<a href="#">Exclude These Person</a>');

			}else if(workflowType=='web'){
				var dataSum = dataCollection.data;
				$('#aUserID').html(dataSum.sitename);
				$('#wf_profile_box').hide();
				$('#wf_exclude_btn').hide();
				// $('#wf_profile_box').prepend('<a style="float:none;overflow:visible;left:12px;top:12px;" rel="profile" class="smallthumbs absolute" href="http://'+dataSum.sitename+'" target="_blank"><img width="48" height="48" title="'+dataSum.sitename+'" style="margin:0" src="images/iconWeb2.png"></a>');
				$('#wf_exclude_btn').html('<a href="#">Exclude These Person</a>');

			}
			//Wordcloud
			if(dataCollection.data.wordcloud != null){
				var arrTemp = dataCollection.data.wordcloud;
				//Rearrange
				arrTemp.sort(function(){ return Math.random()-0.5; });
				$("#an_wordcloud").html(wordcloud(arrTemp));
			}else{
				$("#an_wordcloud").html("Not available");
			}
			
			if(workflowType=='twitter'){
				//wf_influenced_by
				var by='';
				if(dataCollection.data.influence.influenced_by != null){
					$.each(dataCollection.data.influence.influenced_by, function(k,v){
						by+='<div class="smallthumb influenced-thumb">';
							by+='<a rel="profile" href="#" onclick="twitterPopup(\''+v.author_id+'\', \''+v.author_name+'\');"><img title="'+v.author_id+'" src="'+v.author_avatar+'"></a>';
							by+='<h3>'+v.author_id+'</h3>';
						by+='</div>';
					});
				}else{
					by+='None';
				}
				$('#wf_influenced_by .list').html(by);
				
				//wf_influenced_of
				var of='';
				if(dataCollection.data.influence.influencer_of != null){
					$.each(dataCollection.data.influence.influencer_of, function(k,v){
						of+='<div class="smallthumb influenced-thumb">';
							of+='<a rel="profile" href="#" onclick="twitterPopup(\''+v.author_id+'\', \''+v.author_name+'\'); "><img title="'+v.author_id+'" src="'+v.author_avatar+'"></a>';
							of+='<h3>'+v.author_id+'</h3>';
						of+='</div>';
					});
				}else{
					of+='None';
				}
				$('#wf_influencer_of .list').html(of);
			}
			$('#wf_interval_prog').fadeOut();
		});
		
		analyzeDetailsTweet(author_id, 0);
	}
	
	function analyzeDetailsTweet(author_id, start){
		//Loader
		smacLoader('wf_anl_tw', 'loader-med', 'Conversations');
		smac_api(smac_api_url+'?method=workflow&action='+typeParam+'person_feeds&person='+author_id+'&start='+start+'',function(dataCollection){
			var str='';
			$.each(dataCollection.data.feeds, function(k,v){
				str+='<div class="row">';
					str+='<div class="entry" style="padding: 5px 15px;">';
						str+='<h3>'+v.published+'</h3>';
						str+='<span>'+v.txt+'</span>';
					str+='</div><!-- .entry -->';
				str+='</div>';
			});
			$('#wf_anl_tw').html(str);
			
			//Init Page
			if(start == 0){
				if(start == 0)start=1;
				smacPagination(dataCollection.data.total_rows, start, 'wf_anl_tw_paging', author_id, 'analyzeDetailsTweet', 4);
			}
		});
	}
	
	function analyzeBack(){
		$(".tab_content").hide(); //Hide all tab content
		if(excludePrevent == true){
			$('#tab-initial_ex').show();
		}else{
			$('#'+recentFolder).show();
		}
	}
	
	//Add Folder HTML
	function addFolderHtml(){
		var strContainer='';
		strContainer+='<div id="tab-newfolder" class="tab_content">';
		strContainer+='<div id="section2">';
		strContainer+='<div class="headpopup">';
		strContainer+='<div class="content-left">';
		strContainer+='<h1>Add New Folder</h1>';
		strContainer+='</div><!-- .content-left -->';
		strContainer+='</div>';
		strContainer+='<div class="content">';
		strContainer+='<div id="new-folder">';
		strContainer+='<form class="add-new-folder">';
		strContainer+='<div class="row">';
		strContainer+='<input id="newFolderName" type="text" name="newFolderName" style="width: 525px; height: 20px;"/>';
		strContainer+='<label id="loadingAddFolder" style="color: #8EC448;"></label>';
		strContainer+='<input id="addNewFolderBtn" type="button" value="Add New Folder" onclick="addMarketFolder();"/>';
		strContainer+='</form>';
		strContainer+='</div>';
		strContainer+='</div>';
		strContainer+='</div><!-- .content -->';
		strContainer+='</div><!-- #section2 -->';
		strContainer+='</div><!-- #tab-newfolder -->';
		return strContainer;
	}
	
	//Analyze Detail Html
	function analyzeDetailHtml(){
		var str='';
		str+='<div id="tab-analyze" class="tab_content" style="display: none;">';
		str+='<div class="analyze-detail" id="analyze-1" style="display: block; ">';
			str+='<div class="mtitle" style="margin: 0;">';
				str+='<h3 class="fleft">Analyzing: <span id="aUserID"></span></h3>';
				str+='<h3 class="fright"><a href="#" class="back-analyze" onclick="analyzeBack(); return false;">&lt;&lt;&nbsp;Back</a></h3>';
			str+='</div>';
			str+='<div class="content">';
				str+='<div id="wf_profile_box" class="relative" style="min-height: 55px;">';
					str+='<table class="analyzingtable">';
						str+='<tbody>';
							str+='<tr>';
								str+='<td class="wf_profile_boxrow" valign="top" width="10%"><span class="label">About</span></td>';
								str+='<td valign="top"><span id="author_about"></span></td>';
							str+='</tr>';
							str+='<tr>';
								str+='<td class="wf_profile_boxrow"><span class="label" valign="top">Location</span></td>';
								str+='<td valign="top"><span id="author_location"></span></td>';
							str+='</tr>';
						str+='</tbody>';
					str+='</table>';
				str+='</div>';
				str+='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
					str+='<tbody>';
						str+='<tr><td>';
							str+='<div id="wf_interval_prog" class="duration" style="display:block;">';
								str+='<div class="loading-message">';
									str+='<img src="images/loader.gif">';
									str+='<div class="loading-text">Retrieving Data<br>This could take some time depending on volume</div>';
								str+='</div>';
							str+='</div>';
						str+='</td>';
						str+='<td><div class="tweet-short">';
							str+='<form>';
								str+='<input type="radio" name="source" id="wf_profile_source" value="myself" checked="checked" onclick="">';
								str+='<label>Tweets on topic</label>';
								str+='<input type="radio" name="source" id="wf_profile_source" value="global" onclick="">';
								str+='<label>All Tweets*</label>';
							str+='</form>';
							str+='</div>';
							str+='<div id="wf_exclude_btn" class="paging">';
							str+='</div>';
						str+='</td></tr>';
						str+='<tr><td valign="top">';
							str+='<div class="tweetbox" id="wf_anl_tw" style="min-height: 305px;">';
								
							str+='</div>';
							str+='<div id="wf_anl_tw_paging" class="paging"></div>';
						str+='</td><td valign="top" width="300">';
							str+='<div id="an_wordcloud" class="wordcloud-box wordclouds" style="width: 270px;padding: 15px;">';
								
							str+='</div>';
						str+='</td></tr>';
						str+='<tr><td colspan="2">';
							str+='<div class="workflow-influenced" id="wf_influenced_by" style="display:none;">';
								str+='<div class="mtitle grey">';
									str+='<h3 class="influenced-icon">Influenced By</h3>';
								str+='</div>';
								str+='<div class="content">';
									str+='<div class="list" style="padding: 10px;"><div style="text-align: center;"><span style="color:black;display:block;margin-bottom: 10px;">Loading Influenced By</span><img src="images/loader.gif"/></div></div>';
								str+='</div>';
							str+='</div>';
						str+='</td></tr>';
						str+='<tr><td colspan="2">';
							str+='<div class="workflow-influenced" id="wf_influencer_of" style="display:none;">';
								str+='<div class="mtitle grey">';
									str+='<h3 class="influenced-icon">Influencer Of</h3>';
								str+='</div>';
								str+='<div class="content">';
									str+='<div class="list" style="padding: 10px;"><div style="text-align: center;"><span style="color:black;display:block;margin-bottom: 10px;">Loading Influencer Of</span><img src="images/loader.gif"/></div></div>';
								str+='</div>';
							str+='</div>';
						str+='</td></tr>';
						str+='<tr><td colspan="2">';
							str+='<div id="wf_map" class="map" style="position: relative; background-color: rgb(229, 227, 223);display:none;">';
							str+='</div>';
						str+='</td></tr>';
					str+='</tbody>';
				str+='</table>';
			str+='</div><!-- .content -->';
		str+='</div></div>';
		return str;
	}
	
	function workflow_reply(feedID, authorID, folderID){
		var msg = encodeURIComponent($('#replyText'+feedID).val());
		
		smac_api(smac_api_url+'?method=workflow&action='+typeParam+'send_reply&person='+authorID+'&status='+msg+'&folder_id='+folderID+'&feed_id='+feedID+'',function(dataCollection){
			var now = new Date();
			var dd = now.getDate();
			var mm = now.getMonth()+1;
			var yyyy = now.getFullYear();
			var hour = now.getHours();
			var min = now.getMinutes();
			var sec = now.getSeconds();
			var repliedTime = dd+'/'+mm+'/'+yyyy+' '+hour+':'+min+':'+sec; 
			$('#wf_comment_'+feedID).html('<span class="message-sent">Replied at '+repliedTime+'</span>');
		});
	}
	
function unFlagThis(channelFlag, feedFlag, folderFlag, folderNKey){
	smac_api(smac_api_url+'?method=workflow&action='+channelFlag+'unflag&feed_id='+feedFlag+'&folder_id='+folderFlag+'&type='+site_type,function(dataCollection){
		
		var totalConversFrom = parseInt($('li.folder'+folderFlag+' a span').html()) - 1;
		$('li.folder'+folderFlag+' a span').html(totalConversFrom);
		
		$('#divList_'+feedFlag).remove();
		$('.tip-yellow').css('opacity', '0');
		perPage = perPage - 1;
						
		if(perPage <= 0){
			twitList(folderNKey, 0);
		}
	});
}

function mark_sentiment(feed_id,type,folder_id, from){
	var action = "";
	folder_id = intval(folder_id);
	switch(type){
		case 'twitter':	
			action="flag";
		break;
		case 'facebook':	
			action="fb_flag_feeds";
		break;
		default:
			action="site_flag_feeds";
		break;
	}
	smac_api(smac_api_url+'?method=workflow&action='+action+'&keyword=N/A&feed_id='+feed_id+'&folder_id='+folder_id+'&type='+site_type,
	function(response){
		var str = "";
		if(response.status==1){
			var totalConversFrom = parseInt($('li.folder'+from+' a span').html()) - 1;
			$('li.folder'+from+' a span').html(totalConversFrom);
		
			var totalConversTo = parseInt($('li.folder'+folder_id+' a span').html()) + 1;
			$('li.folder'+folder_id+' a span').html(totalConversTo);
			
			$('#divList_'+feed_id+'').remove(); 
		}
	});	
}
