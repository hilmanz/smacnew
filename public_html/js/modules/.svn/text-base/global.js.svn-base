// :: Global JS ::
// :: @cendekiapp ::
// :: It can be use on every page ::

//Global Variable
var twitterIDGlobal;
var globalPageInit = 0;

function twitterPopup(twitterID, twitterName){
	$("body,html").animate({scrollTop:0}, 500);
	//Assign to global
	globalPageInit = 0;
	twitterIDGlobal = twitterID;

	//Display Popup
	$("#master").append('<div id="fade" style="display: block;"></div>');
	$("#profile").prepend('<a class="close" href="#"><img alt="Close" title="Close Window" class="btn_close" src="images/close.png"></a>');
	$("#profile").fadeIn();
	$("#popupload").show();
	$("#popupbody").hide();
	if(twitterName != undefined){
		$('#profile .headpopup h1').html('@'+twitterID+' - '+twitterName);
	}else{
		$('#profile .headpopup h1').html('@'+twitterID);
	}
	smac_api(smac_api_url+'?method=kol&action=twitter_profile&person='+twitterID+'',function(dataCollection){
		
		//Summary Twitter data
		$("#popupbody .smallthumb img").attr('src', dataCollection.data.summary.author_avatar);
		$("#popupbody .statistik-profile a.icon1").html(number_format(parseInt(dataCollection.data.summary.followers)));
		$("#popupbody .statistik-profile a.icon2").html(number_format(parseInt(dataCollection.data.summary.total_mentions)));
		$("#popupbody .statistik-profile a.icon3").html(number_format(parseInt(dataCollection.data.summary.total_impression)));
		$("#popupbody .statistik-profile a.icon4").html(number_format(parseInt(dataCollection.data.summary.total_rt_mentions)));
		$("#popupbody .statistik-profile a.icon5").html(number_format(parseInt(dataCollection.data.summary.total_rt_impression)));
		$("#popupbody .statistik-profile a.icon6").html(dataCollection.data.summary.share_percentage+'%');
		$("#authorabout").html(dataCollection.data.summary.about);
		$("#authorlocation").html(dataCollection.data.summary.location);
		$("#popupbody .impact-score h1").html(dataCollection.data.summary.rank);
		$("#exc_button").html('<a class="tplbtn" onclick="tgl_exc_person(\''+twitterID+'\',\''+dataCollection.data.remove_link+'\');" href="#">Remove Person</a>');
		
		//Wordcloud
		$('#profilewc').html(wordcloud(dataCollection.data.wordcloud));
		
		//Overall Performance
		try{
			var category = new Array();
			var mentionsArr = new Array();
			var impressionArr = new Array();
			var rtArr = new Array();
			$.each(dataCollection.data.statistics, function(k, v) {
				var month = (v.published_date).substr(5,2);
				var tgl = (v.published_date).substr(8,2);
				category.push(tgl+"/"+month);
				
				mentionsArr.push(parseInt(v.mentions));
				impressionArr.push(parseInt(v.impression));
				rtArr.push(parseInt(v.rt));
			});
			var data = [{
							name : 'Mention',
							data : mentionsArr,
							color : '#39B54A',
							visible: true
						},{
							name : 'Total Impressions',
							data : impressionArr,
							color : '#92278F',
							visible: false
						},{
							name : 'Retweet Frequency',
							data : rtArr,
							color : '#00AEF0',
							visible: false
						}];
			lineChartMini('chartPop', category, data, true);
		}catch(e){
		
		}
		//Sentiment Overtime
		try{
			var positive = new Array(); 
			var negative = new Array(); 
			var category = new Array();
			if(dataCollection.data.sentiment.negative != null){
				//Negative
				$.each(dataCollection.data.sentiment.negative, function(k, v) {
					var month = (v.published_date).substr(5,2);
					var tgl = (v.published_date).substr(8,2);
					category.push(tgl+"/"+month);
					negative.push(parseInt(v.total));
				});
			}
			if(dataCollection.data.sentiment.positive != null){	
				//Positive
				$.each(dataCollection.data.sentiment.positive, function(k, v) {
					positive.push(parseInt(v.total));
				});
			}
			var data = [{
						name: 'Positive',
						data: positive,
						color: '#8ec448'
						},{
						name: 'Negative',
						data: negative,
						color: '#ff0000'
						}];
			lineChartMini('chartPop2', category, data, true);
		}catch(e){
		
		}
		
		//Tweet
		twitter_profile_feeds(twitterID, 0);
		
		//Show
		$("#popupload").hide();
		$("#popupbody").fadeIn();
	});
	
}

function twitter_profile_feeds(twitterID, page){
	smacLoader('chartPop3', 'loader-med', 'Twitter Feeds');
	smac_api(smac_api_url+'?method=kol&action=twitter_profile_feeds&person='+twitterID+'&start='+page+'',function(dataCollection){
		try{
		var str='';
		$.each(dataCollection.data.feeds, function(k,v){
			str+='<div class="list">';
				str+='<div class="entry">';
				str+='<span class="date">'+v.published+'</span>';
				str+='<span class="txt">'+stripslashes(v.txt)+'</span>';
				str+='</div>';
			str+='</div>';
		});
		
		$('#chartPop3').html(str);
		
		//Pagination
		if(globalPageInit == 0){
			globalPageInit = 1;
			if(page == 0)page=1;
			smacPagination(dataCollection.data.total_rows, page, 'twitterFeedPaging', twitterID, 'twitter_profile_feeds', 5);
		}
		}catch(e){}
	});
}

function countChar(val){
	var len = val.value.length;
	if (len > 140) {
		val.value = val.value.substring(0, 140);
	}else {
		$('#charNum').text(140 - len);
	}
}
function toggle_show_tips(){
	smac_api(smac_api_url+'?method=general&action=toggle_tips',function(){});
}

function openFolderList(id, last){
	$('#mt'+id+'-openFolderList').fadeIn();
	$('#mf'+id+'-openFolderList').fadeIn();
	$('#mw'+id+'-openFolderList').fadeIn();
	$('#mt'+id+'-openFolderList, #mf'+id+'-openFolderList, #mw'+id+'-openFolderList').css('cursor', 'move');
	$('#mt'+id+'-openFolderList, #mf'+id+'-openFolderList, #mw'+id+'-openFolderList').draggable();
}

function moveFolderList(folderID, id, channel, position, webType){
	if(position != null){
		var left = position;
	}else{
		var left = '735px';
	}
	
	if(channel == '1'){
		var headID = 'mt';
	}else if(channel == '2'){
		var headID = 'mf';
	}else if(channel == '3'){
		var headID = 'mw';
	}
	var str='';
	str+='<div class="selecfolder" id="'+headID+''+id+'-openFolderList" style="display: none;left: '+left+';">';
		str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777">';
			str+='<span style="float:left;margin:0;width:auto;">';
				str+='<h3 style=" font-size: 12px;margin: 0; padding: 5px 10px; color: white;">Move to :</h3>';
			str+='</span>';
			str+='<span style="float:right;margin:0;width:auto;">';
				str+='<a onclick="close_folder_global();" href="javascript:void(0);" style="width: auto;float:none;">X</a>';
			str+='</span>';
		str+='</div>';
		$.each(wf_folders, function(k,v){
			if(v.auto == '0'){
			str+='<a class="listfolder" href="#" onclick="mark_reply(\''+folderID+'\',\''+id+'\',\''+channel+'\',\''+v.folder_id+'\',\''+webType+'\'); return false;" style="float:none;width:auto;">'+v.folder_name+'</a>';
			}
		});
	str+='</div>';
	return str;
}


//EDIT SENTIMENT
function sentimentChange(id, channel, webType, icon, custom){
	var left;
	if(channel == '1'){
		var headID = 'mt';
		left = '280px';
	}else if(channel == '2'){
		var headID = 'mf';
		left = '165px';
	}else if(channel == '3'){
		var headID = 'mw';
		left = '130px';
	}
	
	if(custom){
		var top = 'top:'+custom+';';
	}else{
		var top = 'top:1px;'
	}
	var str='';
	str+='<div class="selecfolder" id="'+headID+''+id+'-sentimentChange" style="display: none;left: '+left+';'+top+'">';
		str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777">';
			str+='<span style="float:left;margin:0;width:auto;">';
				str+='<h3 style=" font-size: 12px;margin: 0; padding: 5px 10px; color: white;">Change sentiment :</h3>';
			str+='</span>';
			str+='<span style="float:right;margin:0;width:auto;">';
				str+='<a onclick="close_folder_global();" href="javascript:void(0);" style="width: auto;float:none;">X</a>';
			str+='</span>';
		str+='</div>';
		str+='<a class="listfolder" href="#" onclick="changeSentiment(2,\''+id+'\',\''+channel+'\',\''+webType+'\',\''+icon+'\'); return false;" style="float:none;width:auto;">very positive</a>';
		str+='<a class="listfolder" href="#" onclick="changeSentiment(1,\''+id+'\',\''+channel+'\',\''+webType+'\',\''+icon+'\');return false;" style="float:none;width:auto;">positive</a>';
		str+='<a class="listfolder" href="#" onclick="changeSentiment(0,\''+id+'\',\''+channel+'\',\''+webType+'\',\''+icon+'\');return false;" style="float:none;width:auto;">neutral</a>';
		str+='<a class="listfolder" href="#" onclick="changeSentiment(-1,\''+id+'\',\''+channel+'\',\''+webType+'\',\''+icon+'\');return false;" style="float:none;width:auto;">negative</a>';
		str+='<a class="listfolder" href="#" onclick="changeSentiment(-2,\''+id+'\',\''+channel+'\',\''+webType+'\',\''+icon+'\');return false;" style="float:none;width:auto;">very negative</a>';
	str+='</div>';
	return str;
}

function sentimentChangePopup(id){
	$('#mt'+id+'-sentimentChange').fadeIn();
	$('#mf'+id+'-sentimentChange').fadeIn();
	$('#mw'+id+'-sentimentChange').fadeIn();
	$('#mt'+id+'-sentimentChange, #mf'+id+'-sentimentChange, #mw'+id+'-sentimentChange').css('cursor', 'move');
	$('#mt'+id+'-sentimentChange, #mf'+id+'-sentimentChange, #mw'+id+'-sentimentChange').draggable();
}

function changeSentiment(sentiment, id, channel, webType, prevIcon){
	//if success
	switch (sentiment){
		case 2:
			var sentimentTXT = 'very positive';
			var icon = 'positive';
			break;
		case 1:
			var sentimentTXT = 'positive';
			var icon = 'positive';
			break;
		case 0:
			var sentimentTXT = 'neutral';
			var icon = sentimentTXT;
			break;
		case -1:
			var sentimentTXT = 'negative';
			var icon = 'negative';
			break;
		case -2:
			var sentimentTXT = 'very negative';
			var icon = 'negative';
			break;
		default:
			var sentimentTXT = 'neutral';
			var icon = sentimentTXT;
	}
	if(sentiment>1){
		var sentimentTXT = 'very positive';
		var icon = 'positive';
	}else if(sentiment<-1){
		var sentimentTXT = 'very negative';
		var icon = 'negative';
	}
	
	smac_api(smac_api_url+'?method=general&action=update_sentiment&id='+id+'&sentiment='+sentiment+'&channel='+channel,function(dataCollection){
		try{
			$("a#sentiment_"+id).removeClass('icon-'+prevIcon);
			$("a#sentiment_"+id).addClass('icon-'+icon);
			$("a#sentiment_"+id).html(sentimentTXT);
		}catch(e){}
	});
}
//END OF EDIT SENTIMENT

function conversationByDateGlobal(dt, page){
	var arrDtKey = dt.split("_");
	switch(arrDtKey[1]){
		case 'Twitter':
			var channel = 1;
			break;
		case 'Facebook':
			var channel = 2;
			break;
		case 'Web':
			var channel = 3;
			break;
		case 'Video':
			var channel = 4;
			break;
		default:
			var channel = 1;
	}
	if(page == 0){
		if(globalPageInit == 0){
		$('#master').append('<div id="fade" style="display: block;"></div>');		
		$("#popup-sentiment").prepend('<a class="close" href="#"><img style="margin: -20px -33px 0 615px;" alt="Close" title="Close Window" class="btn_close" src="images/close.png"></a>');
		$("#popup-sentiment").fadeIn();
		$("#popup-sentiment .headpopup h1").html('Conversation');
		globalPageInit = 1;
		}
	}
	smacLoader('sentimentPopup', 'loader-med', 'Conversation');
	smac_api(smac_api_url+'?method=feeds&action=by_date&date='+arrDtKey[0]+'&channel='+channel+'&start='+page+'',function(dataCollection){
		var dataLength = dataCollection.data.feeds;
		if(dataLength.length > 0){
			var list = '';
			$.each(dataLength, function(k,v){		
				switch(channel){
					case 1:
						list += conversationList(v.profile_image_url, v.name, v.txt, date('d/m/Y', strtotime(v.published)),"#");
						break;
					case 2:
						list += conversationList(v.profile_image_url, v.name, v.txt, v.published,"#");
						break;
					case 3:
						list += conversationList('images/iconWeb2.png', v.name, v.txt, v.published,"#");
						break;
					case 4:
						list += conversationList(v.pic, v.name, v.txt, v.published,"#play_video/"+urlencode(v.url)+"/1");
						break;
					default:
						list += conversationList(v.profile_image_url, v.name, v.txt, date('d/m/Y', strtotime(v.published)),"#");
				}
				
			});
			$("#popup-sentiment .content-popup").html(list);
			if(page == 0){
				page=1;
				smacPagination(dataCollection.data.total_rows, page, 'sentimentPopupPaging', dt, 'conversationByDateGlobal', 20);
			}
		}else{
			$('#sentimentPopup').html("No data available");
			$('#sentimentPopupPaging').hide();
		}
	});
}
function siteconversationByDateGlobal(dt, page){
	var arrDtKey = dt.split("_");
	
	switch(arrDtKey[1]){
		case 'web':
			var channel = 1;
			break;
		case 'forum':
			var channel = 2;
			break;
		case 'news':
			var channel = 3;
			break;
		case 'video':
			var channel = 4;
			break;
		default:
			var channel = 0;
	}
	if(page == 0){
		if(globalPageInit == 0){
		$('#master').append('<div id="fade" style="display: block;"></div>');		
		$("#popup-sentiment").prepend('<a class="close" href="#"><img style="margin: -20px -33px 0 615px;" alt="Close" title="Close Window" class="btn_close" src="images/close.png"></a>');
		$("#popup-sentiment").fadeIn();
		$("#popup-sentiment .headpopup h1").html('Conversation');
		globalPageInit = 1;
		}
	}
	smacLoader('sentimentPopup', 'loader-med', 'Conversation');
	smac_api(smac_api_url+'?method=feeds&action=site_by_date&date='+arrDtKey[0]+'&channel='+channel+'&start='+page+'',function(dataCollection){
		var dataLength = dataCollection.data.feeds;
		if(dataLength.length > 0){
			var list = '';
			$.each(dataLength, function(k,v){		
				switch(channel){
					case 1:
						list += conversationList(v.profile_image_url, v.name, v.txt, date('d/m/Y', strtotime(v.published)),"#");
						break;
					case 2:
						list += conversationList(v.profile_image_url, v.name, v.txt, v.published,"#");
						break;
					case 3:
						list += conversationList('images/iconWeb2.png', v.name, v.txt, v.published,"#");
						break;
					case 4:
						list += conversationList(v.pic, v.name, v.txt, v.published,"#play_video/"+urlencode(v.url)+"/1");
						break;
					default:
						list += conversationList(v.profile_image_url, v.name, v.txt, date('d/m/Y', strtotime(v.published)),"#");
				}
				
			});
			$("#popup-sentiment .content-popup").html(list);
			if(page == 0){
				page=1;
				smacPagination(dataCollection.data.total_rows, page, 'sentimentPopupPaging', dt, 'conversationByDateGlobal', 20);
			}
		}else{
			$('#sentimentPopup').html("No data available");
			$('#sentimentPopupPaging').hide();
		}
	});
}
function conversationList(avatar, name, content, date,pic_url){
	var str='';
	str+='<div class="list">';
		str+='<div class="smallthumb" style="background: #fff;">';
			str+='<a class="poplight" href="'+pic_url+'"><img src="'+avatar+'" /></a>';
		str+='</div>';
		str+='<div class="entry" style="border-bottom: none;">';
			str+='<h3>'+name+'</h3>';
			str+='<span>'+content+'</span>';
			str+='<span class="date">'+date+'</span>';
		str+='</div><!-- .entry -->';
	str+='</div><!-- .list -->';
	return str;
}
function closeAnyPopup(){
	$('#popup-sentiment').hide();
}
function global_play_video(url, trigger){
	$("body,html").animate({scrollTop:0}, 500);
	closeAnyPopup();
		if(trigger==0){
			$('#master').append('<div id="fade" style="display: block;"></div>');
		}
		$('#popup-video').fadeIn();
			
	var a = getUrlVars(urldecode(url));
	
	var str='<iframe id="videoIframe" width="420" height="315" src="http://www.youtube.com/embed/'+a.v+'" frameborder="0" allowfullscreen></iframe>';
	$("#popup-video .video_content").html(str);
}
function global_close_video(){
	// $("body,html").animate({scrollTop:0}, 500);
	// $('#popup-video').fadeOut();
	
	// globalPageInit = 0;
	// $('#fade').css('opacity', '0');
	// $('#fade , .popup_block').fadeOut(function() {
		// $('#fade, a.close').remove();  //fade them both out
	// });
}
function getUrlVars(url)
{
    var vars = [], hash;
    var hashes = url.slice(url.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

//Choose wordcloud channel
function wordcloudChannel(index){
	var str='';
	str+='<div class="selecfolder" id="wordcloudChannel-'+index+'" style="display: none;left:0px;">';
		str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777">';
			str+='<span style="float:left;margin:0;width:auto;">';
				str+='<h3 style=" font-size: 12px;margin: 0; padding: 5px 10px; color: white;">Pick channel :</h3>';
			str+='</span>';
			str+='<span style="float:right;margin:0;width:auto;">';
				str+='<a onclick="close_folder_global();" href="javascript:void(0);" style="width: auto;float:none;">X</a>';
			str+='</span>';
		str+='</div>';
		str+='<div id="cloudChannelList-'+index+'">';
		str+='</div>';
	str+='</div>';
	return str;
}

//Image SRC not found/error
function errorImage(err,id){
	$('#thumb'+id).removeAttr('onclick');
	$(err).attr('src', "images/iconWeb2.png");
}

//Web Screen shot
function webScreenshot(img){
	console.log(img);
	$("body,html").animate({scrollTop:0}, 500);
	
	//Display Popup
	$("#master").append('<div id="fade" style="display: block;"></div>');
	$("#popup-screenshot").prepend('<a class="close" href="#"><img alt="Close" title="Close Window" class="btn_close" src="images/close.png" style="margin: -20px -33px 0 770px;"></a>');
	$("#popup-screenshot").fadeIn();
	
	$('.screenchotPopup').attr('src','content/pics/'+img);
}