// :: Overview ::
// :: @kia ::

	//Global Variable
	var pageInit=0;
	var contentGroupsData;
	var elementArr;
	var openInit=0;
	var initID, initShareIssues, initSharePeople;
	
	
	$(document).ready(function(){
		Backbone.emulateHTTP = true;
		Backbone.emulateJSON = true;
		
		//Backbone Router
		var Router = Backbone.Router.extend({
			routes: {
				"*action" : "hashTagMenu"
			},
			hashTagMenu: function(action){
				if(action == 'dufaultTab' || action == ''){
					topicGroups('NA', 0);
				}
			}
		});
		
		var app_router = new Router;
		Backbone.history.start();
	});
	
	//Tab Menu
	function tabMenu(hashTag){
		$(".contentGroup").hide();
		$('#tgroup-'+hashTag).fadeIn();
	}
	
	function topicGroups(na, start){
		//Loader
		smacLoader('accordion-keyword', 'loader-med', 'Topic Lists');
		
		smac_api(smac_api_url+'?method=overview&action=topic_groups&start='+start+'',function(dataCollection){
			
			var str='';
			var cekData;
			$.each(dataCollection.data.groups, function(k,v){
				
				if(k == 0){initID = v.id;}
				if (v.name == ""){
					var groupName = "Default Group";
				}else{
					var groupName = v.name;
				}
				var shared_author = "";
				var shared_issues = "";
				try{
					$.each(v.shared_author,function(k,v){
						if(k>0){
							shared_author+=", ";
						}
						shared_author+=v.author_id;
						
					});
					if(k == 0){initSharePeople=shared_author;}
					$.each(v.shared_issues,function(k,v){
						if(k>0){
							shared_issues+=", ";
						}
						shared_issues+=v.keyword;
						
					});
					if(k == 0){initShareIssues=shared_issues;}
				}catch(e){}
				str+='<h2 class="topic-group">';
				str+='<a href="#" class="showGroup" onclick="tabMenu('+v.id+');contentGroups('+v.id+',\''+shared_author+'\',\''+shared_issues+'\');return false;">'+groupName+'</a>';
				str+='<a href="#" class="manageGroup">Manage Group</a>';
				str+='</h2>';
				
				str+='<div id="tgroup-'+v.id+'" class="accordion-content contentGroup"></div>';
				
			});
			$('#accordion-keyword').html(str);
			
			// Init Page
			if(pageInit == 0){
				pageInit = 1;
				if(start == 0)start=1;
				smacPagination(dataCollection.data.total_rows, start, 'accordion-keyword_page', na, 'topicGroups', 20);
			}
			if(openInit == 0){
				openInit=1;
				tabMenu(initID);
				contentGroups(initID, initSharePeople, initShareIssues);
			}
		});
		
	}
	
	function contentGroups(topicID,shared_author,shared_issue){
		if(shared_issue==undefined){
			shared_issue = "N/A";
		}
		if(shared_author==undefined){
			shared_author = "N/A";
		}
		//Loader
		smacLoader('tgroup-'+topicID, 'loader-med', 'Topic Details');
		
		//clean
		$('.topicTable').html('');
		
		smac_api(smac_api_url+'?method=overview&action=summary&group_id='+topicID+'',function(dataCollection){
			try{
				//assign to global var
				contentGroupsData = dataCollection.data;
				
				if (contentGroupsData.length != 0 ){
					var str='';
					//Quadrant Chart
					str+='<div class="rowsTop">';
						str+='<div class="titleChart"><h3>Potential Impact Index</h3></div>';
						str+='<div class="bgChart">';
							str+='<div id="quadrant_'+topicID+'"></div>';
						str+='</div>';
					str+='</div>';

					//Shared Issue
					if(contentGroupsData.length > 1){		
						if(strlen(shared_issue)>0){
							str+='<div class="col2">';
							str+='<div class="titleChart">';
							
							str+='<h3>Shared Issues</h3></div>';
							
							str+='<div class="bgChart"><li id="sharedissues">'+shared_issue+'</li></div></div>';
						}
						//Shared People
						if(strlen(shared_author)>0){
							str+='<div class="col2 colLast">';
							str+='<div class="titleChart">';
							str+='<h3>Shared People</h3></div>';
							
							str+='<div class="bgChart"><li id="sharedpeople">'+shared_author+'</li></div></div>';
						}
					}
					//Topic Table
					str+='<table id="mytopic" width="100%" cellspacing="0" cellpadding="0" border="0" >';
					str+='<thead class="headTable"><tr><td width="20%"><h3 class="margin0">Topic</h3></td><td width="15%" align="center"><h3 class="margin0">P.I.I</h3></td><td width="15%" align="center"><h3 class="margin0">Posts</h3></td><td width="15%" align="center"><h3 class="margin0">Impressions</h3> </td><td width="100" align="center"><h3 class="margin0">Sentiment</h3> </td><td width="20%" align="center"><h3 class="margin0">People</h3> </td><td width="100" align="center">  <h3 class="margin0">Channel</h3> </td></tr></thead>';
					str+='<tbody id="list-topic" class="topic_'+topicID+' topicTable"><tr><td colspan="8">No data available yet</td></tr></tbody>';
					str+='<tfoot><tr><td colspan="8"></td></tr></tfoot></table>';
					
					$('#tgroup-'+topicID).html(str);
					
					//Quadrant Chart
					var quadrantData = new Array();
					var checkImpression = 0;
					try{
						var i=0;
						$.each(contentGroupsData, function(k,v){
							checkImpression+=v.impressions;
							if(i<5){
								var qData = {
									id:parseInt(v.campaign_id),
									label:v.campaign_name,
									volume:parseInt(v.quadrant.volume),
									viral:parseFloat(v.quadrant.viral_score),
									sentiment:round(v.quadrant.pii,2)
									};
								quadrantData.push(qData);
								i++;
							}
						});
						if(checkImpression != 0){
							quadrant({target: 'quadrant_'+topicID,width:943,height:280,data:quadrantData});
						}else{
							$('#quadrant_'+topicID).html(noDataOverview('Your report is not ready yet.'));
						}
					}catch(e){
					}
					//Populate Topic Table
					var tbl='';
					elementArr = new Array();
					$.each(contentGroupsData, function(k,v){
						//variable check
						if(v.impressions != '0'){
							var pii = v.pii;
							var mentions = smac_number2(v.mentions);
							var impression = smac_number2(v.impressions);
							var people = smac_number2(v.people);
						}else{
							var pii = 'N/A';
							var mentions = 'N/A';
							var impression = 'N/A';
							var people = 'N/A';
						}
						
						if(k%2==0){var row = 'genaps'}
						else{var row = 'ganjils'}			
						elementArr.push(k);
						
						tbl+='<tr class="'+row+'">';
						tbl+='<td>';
							tbl+='<h3 class="margin0">';
							if(v.n_status == '1'){
								tbl+='<a href="?'+v.topic_url+'" class="cLink">'+v.campaign_name+'</a><img style="margin-left:7px;" width="14" height="14" src="images/ajax-loader.gif"/>';
							}else{
								tbl+='<a href="?'+v.topic_url+'" class="cLink">'+v.campaign_name+'</a>';
							}
							tbl+='</h3>';
							tbl+='<p>'+v.description+'</p>';
							tbl+='<p style="font-size:10px;margin-bottom: 20px;">Last Data Collected:<br/> '+v.last_data_collected+'</p>';
							tbl+='<div class="control">';
							if(v.n_status == '1'){
								tbl+='<a onclick="javascript:if(!confirm(\'Are you sure want to pause this topic?\') ) return false;" class="run pause" href="index.php?'+v.pause_url+'">Collecting</a>';
							}else if(v.n_status == '0'){
								tbl+='<a onclick="javascript:if(!confirm(\'Are you sure want to run this topic?\') ) return false;" class="run" href="index.php?'+v.resume_url+'">Paused</a>';
							}
							tbl+='<a onclick="javascript:if( !confirm(\'This will delete all rules within the topic. Data deleted cannot be recovered and will not reduce the topics used quota.\') ) return false;" class="icon_stoptopic" href="index.php?'+v.delete_url+'">Delete</a></div>';
						tbl+='</td>';
						tbl+='<td valign="top" align="center">';
							tbl+='<h1>'+pii+'</h1>';
							tbl+='<div class="up percent_change_entry"><span class="up">0</span></div>';
						tbl+='</td>';
						tbl+='<td valign="top" align="center">';
							tbl+='<h1>'+mentions+'</h1>';
							tbl+='<div class="up percent_change_entry">';
							tbl+='<span class="up">0</span>';
							tbl+='<span class="percentage">0.0%</span></div>';
						tbl+='</td>';
						tbl+='<td valign="top" align="center">';
							tbl+='<h1>'+impression+'</h1>';
							tbl+='<div class="up percent_change_entry"><span class="up">0</span>';
							tbl+='<span class="percentage">0.0%</span></div>';
						tbl+='</td>';
						tbl+='<td width="70" align="center"><div style="width:130px;height:130px;margin: -25px 0;" id="small-sentiment-'+k+'"></td>';
						tbl+='<td align="center"><h1>'+people+'</h1></td>';
						tbl+='<td width="70" align="center"><div style="width:130px;height:130px;margin: -25px 0;" id="small-channel-'+k+'"></td>';
						tbl+='<tr>';
					});
					$('.topic_'+topicID).html(tbl);
					
					//Generate Pie Chart each of Topic
					var pie='';
					$.each(elementArr, function(k,v){
						if($('#small-channel-'+v).length){
							//Pie on table - Channel
							var sourceFB = parseInt(contentGroupsData[v].source.facebook);
							var sourceTw = parseInt(contentGroupsData[v].source.twitter);
							var sourceWeb = parseInt(contentGroupsData[v].source.blog);
							var totalChnl = sourceFB+sourceTw+sourceWeb;
							
							if(totalChnl != 0){
								var channelArr = new Array();
								var facebook = {
									name : 'Facebook',
									y	 : sourceFB,
									color: '#262DED'
								}
								var twitter = {
									name : 'Twitter',
									y	 : sourceTw,
									color: '#31EBE8'
								}
								var web = {
									name : 'Web',
									y	 : sourceWeb,
									color: '#FABC11'
								}
								channelArr.push(facebook);
								channelArr.push(twitter);
								channelArr.push(web);
								
								pieChartMiniChannel('small-channel-'+v+'', channelArr, 80);
							}else if(totalChnl == 0){
								$('#small-channel-'+v+'').html('');
							}
						}
						if($('#small-sentiment-'+v).length){
							//Pie on table - Sentiment
							if(contentGroupsData[v].sentiment.postive == null){
								var sourcePlus = 0;
							}else{
								var sourcePlus = parseInt(contentGroupsData[v].sentiment.postive);
							}
							if(contentGroupsData[v].sentiment.negative == null){
								var sourceMin = 0;
							}else{
								var sourceMin = parseInt(contentGroupsData[v].sentiment.negative);
							}
							if(contentGroupsData[v].sentiment.netral == null){
								var sourceNet = 0;
							}else{
								var sourceNet = parseInt(contentGroupsData[v].sentiment.netral);
							}
							
							var totalSent = sourcePlus+sourceMin+sourceNet;
							
							if(totalSent > 0){
								var channelArr = new Array();
								var positive = {
									name : 'Positive',
									y	 : sourcePlus,
									color: '#8ec448'
								};
								var negative = {
									name : 'Negative',
									y	 : sourceMin,
									color: '#ff0000'
								};
								var neutral = {
									name : 'Neutral',
									y	 : sourceNet,
									color: '#666666'
								};
								channelArr.push(positive);
								channelArr.push(negative);
								channelArr.push(neutral);
								
								pieChartMiniSentiment('small-sentiment-'+v+'', channelArr, 80);
							}else{
								$('#small-sentiment-'+v+'').html('');
							}
						}
					});
				}else{
					$('#tgroup-'+topicID).html(noDataOverview('There is no data in this topic to display'));
				}
			}catch(e){
				$('#tgroup-'+topicID).html(noDataOverview('There is no data in this topic to display'));
			}
		});
		
	}
	
function noDataOverview(txt){
	var str='';
	str+='<div id="mytopic-banner">';
	str+='<div class="content">';
	str+='<h1 style="margin-top: 38px;">'+txt+'<br></h1>';
	str+='</div>';
	str+='</div>';
	return str;
}