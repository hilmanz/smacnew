var n_data = 0;
var wf_total = 0;
var wf_tweets = null;
var wf_per_page = 4;
var wf_index = 0;
var wf_next_url = "";
var wf_prev_url = "";
var wf_curr_url = "";
var wf_curr_page = 1;

//marked tweet
var marked_n_data = 0;
var wf_marked_total = 0;
var wf_marked_tweets = null;
var wf_marked_offset = 0;
var wf_marked_curr_url = "";
//custom folder
var custom_n_data = 0;
var wf_custom_total = 0;
var wf_custom_tweets = null;
var wf_custom_offset = 0;
var wf_custom_curr_url = "";
var wf_custom_next_url = {};
var wf_custom_prev_url = {};
var wf_custom_curr_url = {};
//-->
//replies
var wf_reply_next_url = "";
var wf_reply_prev_url = "";
var wf_reply_curr_url = "";
//exclude
var wf_exc_next_url = "";
var wf_exc_prev_url = "";
var wf_exc_curr_url = "";
var wf_exc_url = "";
//analize
var wf_analize_next_url = "";
var wf_analize_prev_url = "";
var wf_analize_start=0;
var wf_analize_state = 0; //0->tweet list, 1->profile tab
var wf_analize_curr_url="";
var wf_analize_active_url="";
var wf_search_url = "";
var wf_curr_id = "";
var wf_gwc = "";
var wf_cwc = "";
var wf_all = "";
var wf_my = "";
var wf_inf1 ="";
var wf_inf2 ="";
var wf_total_unmark = 0;

//in-progress search
var wf_search = [];
var wf_search_check = false;
var wf_n_replied = 0;
var folders = {};
var folder_list = [];
var wf_active_tab = "";
var c_feed_id = "";
var c_keyword = "";
var c_pid = null;

var is_exc = false;
var wf_curr_keyword = "";

var pageInitWfPopup = 0;
var wf_topic_folders = [];
var n_flagged = 0;
var wf_popup_type=0;
var wf_popup_group_type = 0;
/**
 * popup for opening workflow popup manually
 */
function open_workflow_popup(kw,type,group_type){
	group_type = typeof group_type !== 'undefined' ? group_type : 0;
	popID = "popup-unmark";
	popWidth = 650;
	$("body").scrollTop(0);
	$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="images/close.png" class="btn_close" title="Close Window" alt="Close"></a>');

	//Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
	//var popMargTop = ($('#' + popID).height() + 400) / 2;
	var popMargLeft = ($('#' + popID).width() + 80) / 2;

	//Apply Margin to Popup
	$('#' + popID).css({
	    //'margin-top' : -popMargTop,
	    'margin-left' : -popMargLeft
	});
	
	//Fade in Background
	$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
	$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies
	
	if(wf_topic_folders.length==0){
		wf_get_folders(kw,type,group_type);
	}else{
		pageInitWfPopup = 0;
		open_conversation(kw,type,group_type);
	}
	
}
function open_conversation(kw,type,group_type){
	group_type = typeof group_type !== 'undefined' ? group_type : 0;
	wf_popup_type = type;
	wf_popup_group_type= group_type;
	//repopulate the global topic
	$("#markfolder").html(wf_popup_folder_global(kw,wf_topic_folders));
	
	//then load the conversations.
	//open twitter
	if(type==1){
		twitter_keyword_conversation(kw,0);
		document.location="#twitterPage";
	}else if(type==2){
		//facebook
		fb_keyword_conversation(kw,0);
		document.location="#facebookPage";
	}else if(type==3){
		//web
		web_keyword_conversation(kw,0);
		document.location="#webPage";
	}else{
		//web
		twitter_keyword_conversation(kw,0);
		document.location="#";
	}
}
function web_keyword_conversation(kw,start){
	$("html").scrollTop(0);
	$("#markfolder").show(); //global folder
	$("#wf_total_unmarked").html('Searching for');
	if(kw!=null){
		$('#wf_keyword').html(kw);
	}
	$("#popup-unmark .content-popup .contentdivs").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
	smac_api(smac_api_url+'?method=workflow&action=site_keyword_conversation&type='+wf_popup_group_type+'&keyword='+encodeURIComponent(kw)+'&start='+start,function(response){
		var str = "";
		if(response.status=="1"){
			if(response.data.feeds == null) response.data.feeds = [];
			if(response.data.feeds.length>0){
				$.each(response.data.feeds,function(i,v){
					str+=wf_web_conv(i,v,kw);
				});
				//smacPagination(data, n, divPage, type, fungsi, setPerPage)
				if(pageInitWfPopup == 0 && response.data.total_rows > 0){
					pageInitWfPopup = 1;
					if(start == 0)start=1;
					smacPagination(response.data.total_rows, start, 'wf_paging', kw, 'web_keyword_conversation');
				}
			}else{
				str = "no more unmarked website posts are available.";
			}
			$("#popup-unmark .content-popup .contentdivs").html(str);
		}else{
			str = "unable to retrieve data.";
			$("#popup-unmark .content-popup .contentdivs").html(str);
		}
	});
}
function fb_keyword_conversation(kw,start){
	$("html").scrollTop(0);
	$("#markfolder").show(); //global folder
	$("#wf_total_unmarked").html('Searching for');
	if(kw!=null){
		$('#wf_keyword').html(kw);
	}
	$("#popup-unmark .content-popup .contentdivs").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
	smac_api(smac_api_url+'?method=workflow&action=fb_keyword_conversation&keyword='+encodeURIComponent(kw)+'&start='+start,function(response){
		var str = "";
		if(response.status=="1"){
			
			if(response.data.feeds == null) response.data.feeds = [];
			if(response.data.feeds.length>0){
				$.each(response.data.feeds,function(i,v){
					str+=wf_fb_conv(i,v,kw);
				});
				//smacPagination(data, n, divPage, type, fungsi, setPerPage)
				if(pageInitWfPopup == 0 && response.data.total_rows > 0){
					pageInitWfPopup = 1;
					if(start == 0)start=1;
					smacPagination(response.data.total_rows, start, 'wf_paging', kw, 'fb_keyword_conversation');
				}
			}else{
				str = "no more unmarked facebook feeds are available.";
			}
			$("#popup-unmark .content-popup .contentdivs").html(str);
		}else{
			str = "unable to retrieve data.";
			$("#popup-unmark .content-popup .contentdivs").html(str);
		}
	});
}
function twitter_keyword_conversation(kw,start){
	
	$("html").scrollTop(0);
	$("#markfolder").show(); //global folder
	
	
	$("#wf_total_unmarked").html('Searching for');
	if(kw!=null){
		$('#wf_keyword').html(kw);
	}
	$("#popup-unmark .content-popup .contentdivs").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
	smac_api(smac_api_url+'?method=workflow&action=keyword_conversation&keyword='+encodeURIComponent(kw)+'&start='+start,function(response){
		var str = "";
		if(response.status=="1"){
			if(response.data.tweets == null) response.data.tweets = [];
			if(response.data.tweets.length>0){
				$.each(response.data.tweets,function(i,v){
					str+=wf_twitter_conv(i,v,kw);
				});
				//smacPagination(data, n, divPage, type, fungsi, setPerPage)
				if(pageInitWfPopup == 0 && response.data.total_rows > 0){
					pageInitWfPopup = 1;
					if(start == 0)start=1;
					smacPagination(response.data.total_rows, start, 'wf_paging', kw, 'twitter_keyword_conversation');
				}
			}else{
				str = "no more unmarked tweets are available.";
			}
			$("#popup-unmark .content-popup .contentdivs").html(str);
		}else{
			str = "unable to retrieve data.";
			$("#popup-unmark .content-popup .contentdivs").html(str);
		}
	});
}
/**
 * new twitter conversation's content
 */
function wf_twitter_conv(id,data,kw){
	
	var str="";
	str+="<div id=wf"+id+" class=\"list\">";
	str+="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	str+="<tr>";
	str+="<td>";
	str+="<div class=\"tweetcontent\">";
	str+="<div class=\"smallthumb\">";
	str+="<a href=\"#?w=650&id="+data.author_id+"\" class=\"poplight\" rel=\"profile\"><img src=\""+data.author_avatar+"\"/></a>";
	str+="</div>";
	str+=" <div class=\"entry\">";
	str+="<h3>"+data.author_name+"</h3>";
	str+="<span>"+data.content+"</span>";
	str+="</div><!-- .entry -->";
	str+="<div class=\"entry-action\">";
	if(data.generator=="blackberry"){
		str+="<a href=\"#\" class=\"active\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}
	if(data.generator=="apple"){
		str+="<a href=\"#\" class=\"active\"><span class=\"apple\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"apple\" >&nbsp;</span></a>";
	}
	if(data.generator=="android"){
		str+="<a href=\"#\" class=\"active\"><span class=\"android\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"android\" >&nbsp;</span></a>";
	}
	if(data.rt_imp==null){
		data.rt_imp = 0;
	}
	str+="<a class=\"icon-rts tip_trigger\" style=\"margin-left: 15px;\"> "+addCommas(data.rt)+"</a>";
	str+="<a class=\"icon-imp tip_trigger\"> "+addCommas(floatval(data.impression)+floatval(data.rt_imp))+" </a>";
	str+="<a class=\"icon-share tip_trigger\">"+data.share+"%</a>";
	str+="</div><!-- .entry-action -->";
	str+="</div> <!-- .tweetcontent -->";  
	str+="</td>";
	str+=" <td>";
	str+="<div id=\"wfmn"+id+"\" class=\"grey-box btn_workflow_folder\">";
	
	str+="<a href=\"javascript:void(0);\" class=\"backbtn rightarrow\" onclick=\"wf_show_folder('selecfolders-"+data.feed_id+"');\">&nbsp;</a>";
		
	str+="<div id=\"selecfolders-"+data.feed_id+"\" class=\"selecfolder\" style=\"display: none;\"><a href=\"javascript:void(0);\" class=\"active-rightarrow\">&nbsp;</a>";
	//str+="<h3>Move to :</h3>";
	str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777"><span style="float:left;"><h3>Move to :</h3></span>';
    str+='<span style="float:right;"><a href="javascript:void(0);" onClick="close_folder_global();">X</a></span></div>';
  
	$.each(wf_topic_folders,function(i,v){
		str+="<a href=\"javascript:wf_flag_topic("+id+",'"+kw+"','"+data.feed_id+"',"+v.folder_id+");\" class=\"listfolder\">"+v.folder_name+"</a>";
	});
	str+="</div>";
	str+="</div>";
	str+="<div id=\"wfldr"+id+"\" class=\"grey-box\" style='display:none;'>";
	str+="<img src='images/loader.gif' align='center'/>";
	str+="</div>";
	str+="</td>";
	str+="</tr>";
	str+="</table>";
	str+="</div><!-- .list -->";
	return str;
}
function wf_fb_conv(id,data,kw){
	
	var str="";
	str+="<div id=wf"+id+" class=\"list\">";
	str+="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	str+="<tr>";
	str+="<td>";
	str+="<div class=\"tweetcontent\">";
	str+="<div class=\"smallthumb\">";
	var pic = "https://graph.facebook.com/"+data.author_id+"/picture";
	str+="<a href=\"javascript:void(0);\" class=\"poplight\" rel=\"profile\" target=\"_blank\"><img src=\""+pic+"\"/></a>";
	str+="</div>";
	str+=" <div class=\"entry\">";
	str+="<h3>"+data.author_name+"</h3>";
	str+="<span>"+data.content+"</span>";
	str+="</div><!-- .entry -->";
	str+="<div class=\"entry-action\">";
	if(data.generator=="blackberry"){
		str+="<a href=\"#\" class=\"active\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}
	if(data.generator=="apple"){
		str+="<a href=\"#\" class=\"active\"><span class=\"apple\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"apple\" >&nbsp;</span></a>";
	}
	if(data.generator=="android"){
		str+="<a href=\"#\" class=\"active\"><span class=\"android\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"android\" >&nbsp;</span></a>";
	}
	if(data.rt_imp==null){
		data.rt_imp = 0;
	}
	str+="<a class=\"icon-likes theTolltip\" title=\"Total Likes\">"+addCommas(intval(data.likes))+"</a>";
	str+="</div><!-- .entry-action -->";
	str+="</div> <!-- .tweetcontent -->";  
	str+="</td>";
	str+=" <td>";
	str+="<div id=\"wfmn"+id+"\" class=\"grey-box btn_workflow_folder\">";
	
	str+="<a href=\"javascript:void(0);\" class=\"backbtn rightarrow\" onclick=\"wf_show_folder('selecfolders-"+data.feed_id+"');\">&nbsp;</a>";
		
	str+="<div id=\"selecfolders-"+data.feed_id+"\" class=\"selecfolder\" style=\"display: none;\"><a href=\"javascript:void(0);\" class=\"active-rightarrow\">&nbsp;</a>";
	//str+="<h3>Move to :</h3>";
	str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777"><span style="float:left;"><h3>Move to :</h3></span>';
    str+='<span style="float:right;"><a href="javascript:void(0);" onClick="close_folder_global();">X</a></span></div>';
  
	$.each(wf_topic_folders,function(i,v){
		str+="<a href=\"javascript:wf_flag_topic("+id+",'"+kw+"','"+data.feed_id+"',"+v.folder_id+");\" class=\"listfolder\">"+v.folder_name+"</a>";
	});
	str+="</div>";
	str+="</div>";
	str+="<div id=\"wfldr"+id+"\" class=\"grey-box\" style='display:none;'>";
	str+="<img src='images/loader.gif' align='center'/>";
	str+="</div>";
	str+="</td>";
	str+="</tr>";
	str+="</table>";
	str+="</div><!-- .list -->";
	return str;
}
function wf_web_conv(id,data,kw){
	
	var str="";
	str+="<div id=wf"+id+" class=\"list\">";
	str+="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	str+="<tr>";
	str+="<td>";
	str+="<div class=\"tweetcontent\">";
	str+="<div class=\"smallthumb\">";
	str+="<a href=\""+data.link+"\" class=\"poplight\" rel=\"profile\" target=\"_blank\"><img src=\"images/iconWeb2.png\"></a>";
	str+="</div>";
	str+=" <div class=\"entry\">";
	str+="<h3>"+data.author_name+"</h3>";
	str+="<span>"+data.summary+"</span>";
	str+="</div><!-- .entry -->";
	str+="<div class=\"entry-action\">";
	if(data.generator=="blackberry"){
		str+="<a href=\"#\" class=\"active\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}
	if(data.generator=="apple"){
		str+="<a href=\"#\" class=\"active\"><span class=\"apple\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"apple\" >&nbsp;</span></a>";
	}
	if(data.generator=="android"){
		str+="<a href=\"#\" class=\"active\"><span class=\"android\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"android\" >&nbsp;</span></a>";
	}
	if(data.rt_imp==null){
		data.rt_imp = 0;
	}
	
	str+="</div><!-- .entry-action -->";
	str+="</div> <!-- .tweetcontent -->";  
	str+="</td>";
	str+=" <td>";
	str+="<div id=\"wfmn"+id+"\" class=\"grey-box btn_workflow_folder\">";
	
	str+="<a href=\"javascript:void(0);\" class=\"backbtn rightarrow\" onclick=\"wf_show_folder('selecfolders-"+data.feed_id+"');\">&nbsp;</a>";
		
	str+="<div id=\"selecfolders-"+data.feed_id+"\" class=\"selecfolder\" style=\"display: none;\"><a href=\"javascript:void(0);\" class=\"active-rightarrow\">&nbsp;</a>";
	//str+="<h3>Move to :</h3>";
	str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777"><span style="float:left;"><h3>Move to :</h3></span>';
    str+='<span style="float:right;"><a href="javascript:void(0);" onClick="close_folder_global();">X</a></span></div>';
  
	$.each(wf_topic_folders,function(i,v){
		str+="<a href=\"javascript:wf_flag_topic("+id+",'"+kw+"','"+data.feed_id+"',"+v.folder_id+");\" class=\"listfolder\">"+v.folder_name+"</a>";
	});
	str+="</div>";
	str+="</div>";
	str+="<div id=\"wfldr"+id+"\" class=\"grey-box\" style='display:none;'>";
	str+="<img src='images/loader.gif' align='center'/>";
	str+="</div>";
	str+="</td>";
	str+="</tr>";
	str+="</table>";
	str+="</div><!-- .list -->";
	return str;
}
function wf_show_folder(id){
	$('#'+id).fadeIn();
}
function wf_close_folder(id){
	$('#'+id).fadeOut();
}
function wf_get_folders(kw,type,group_type){
	group_type = typeof group_type !== 'undefined' ? group_type : 0;
	
	//wf_topic_folders
	smac_api(smac_api_url+'?method=workflow&action=folders',function(response){
		var str = "";
		if(response.status==1){
			wf_topic_folders = response.data.folders;
			$("#markfolder").html(wf_popup_folder_global(kw,wf_topic_folders));
			$("#markfolder").hide();
			open_conversation(kw,type,group_type);
		}
	});
}

/**
 * @param id the button id to hide when the flag completed.
 * @param feed_id the feed_id to flag
 * @param type 1 for twitter, 2 for fb, 3 for web
 * @param folder_id 
 */
function mark_reply(id,feed_id,type,folder_id,webType){
	
	$(id).hide();
	$(id+'-openFolderList').hide();
	var action = "";
	folder_id = intval(folder_id);
	if(folder_id==0){folder_id=1;}
	type = intval(type);
	switch(type){
		case 1:	
			action="flag";
		break;
		case 2:	
			action="fb_flag_feeds";
		break;
		case 3:	
			action="site_flag_feeds";
		break;
		default:
			action="flag";
		break;
	}
	smac_api(smac_api_url+'?method=workflow&action='+action+'&keyword=N/A&feed_id='+feed_id+'&folder_id='+folder_id+'&type='+webType,
	function(response){
		var str = "";
		if(response.status==1){
			$(id).hide();
			$(id).after('<a style="float: right;margin: 0 9px 0 0;"><img src="images/icon_centang.png" width="24px" height="27px"/></a>');
			$(id+'-openFolderList').hide();
		}else{
			$(id).show();
		}
	});	
}
function wf_flag_topic(id,keyword,feed_id,folder_id){
	
	//wf_close_folder(id);
	$("#wfldr"+id).show();
	$("#wfmn"+id).hide();
	var action = "";
	wf_popup_type = intval(wf_popup_type);
	switch(wf_popup_type){
		case 1:	
			action="flag";
		break;
		case 2:	
			action="fb_flag";
		break;
		case 3:	
			action="site_flag";
		break;
		default:
			action="flag";
		break;
	}
	
	smac_api(smac_api_url+'?method=workflow&action='+action+'&keyword='+keyword+'&feed_id='+feed_id+'&folder_id='+folder_id+"&group_type="+wf_popup_group_type,
	function(response){
		var str = "";
		if(response.status==1){
			$("#wf"+id).hide();
			n_flagged++;
			if(n_flagged==10){
				n_flagged=0;
				pageInitWfPopup = 0;
				if(wf_popup_type==1){
					twitter_keyword_conversation(keyword,0);
				}else if(wf_popup_type==2){
					fb_keyword_conversation(keyword,0);
				}else if(wf_popup_type==3){
					web_keyword_conversation(keyword,0);
				}else{
					twitter_keyword_conversation(keyword,0);
				}
			}
		}else{
			$("#wfldr"+id).hide();
			$("#wfmn"+id).show();
		}
	});	
}
function wf_flag_keyword(keyword,folder_id){
	var action = "";
	wf_popup_type = intval(wf_popup_type);
	wf_popup_group_type = intval(wf_popup_group_type);
	switch(wf_popup_type){
		case 1:	
			action="flag_keyword";
		break;
		case 2:	
			action="fb_flag_keyword";
		break;
		case 3:	
			action="site_flag_keyword";
		break;
		default:
			action="flag_keyword";
		break;
	}
	
	$("#popup-unmark .content-popup .contentdivs").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");
	smac_api(smac_api_url+'?method=workflow&action='+action+'&keyword='+keyword+'&folder_id='+folder_id+'&group_type='+wf_popup_group_type,
	function(response){
		if(response.status==1){
			$("#popup-unmark .content-popup .contentdivs").html("All feeds has been flagged successfully.");
		}else{
			if(wf_popup_type==1){
					twitter_keyword_conversation(keyword,0);
			}else if(wf_popup_type==2){
				fb_keyword_conversation(keyword,0);
			}else if(wf_popup_type==1){
				twitter_keyword_conversation(keyword,0);
			}else{
				twitter_keyword_conversation(keyword,0);
			}
		}
	});
	close_folder_global();
}
/**Legacies**/
function getWorkflowPopup(sUrl,w){
	sUrl='index.php?req='+sUrl;
	wf_curr_url = sUrl;
	n_data = 0
	wf_total=0;
	wf_index=0;
	wf_curr_page=1;
	wf_curr_keyword = w;
	workflow_getdata(sUrl,1,w);
	
	document.location="#";
}
function refresh_popup_content(){
	n_data = 0
	wf_total=0;
	wf_index=0;
	wf_curr_page=1;
	wf_next_url = null;
	workflow_getdata(wf_curr_url,1,wf_curr_keyword);
}
function wf_popup_folder_global(kw,folder_list){
	//alert(folder_list);
	var str='<a class="rightarrow" href="javascript:void(0);" onclick="open_folder_global();return false;">&nbsp;</a>';
        str+='<div id="selecfolders-ID" class="selecfolder" style="display: none;"><a href="javascript:void(0);" class="active-rightarrow">&nbsp;</a>';
        str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777"><span style="float:left;"><h3>Move to :</h3></span>';
        str+='<span style="float:right;"><a href="javascript:void(0);" onClick="close_folder_global();">X</a></span></div>';
       $.each(folder_list,function(i,v){
       		str+="<a href=\"javascript:wf_flag_keyword('"+kw+"','"+v.folder_id+"');\" class=\"listfolder\">"+v.folder_name+"</a>";
       });
	 str+='</div>';
	return str;
}
function close_folder_global(){
	$('.selecfolder').hide();
	$("a.rightarrow").css({'display' : 'block'});
	$("a.active-rightarrow").css({'display' : 'none'});
}
function open_folder_global(){
	$("#selecfolders-ID").fadeIn();
	$("a.rightarrow").css({'display' : 'none'});
	$("a.active-rightarrow").css({'display' : 'block'});
	
		
}
function wf_flag3(kw,url,folder_name){
	$.ajax({
		  url: "index.php?req="+url,
		  dataType: 'json',
		  beforeSend: function(){
			$("#popup-unmark .content-popup .contentdivs").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			  if(response.status==1){
				  //hide keyword from wordcloud
				  try{
					  if(curr_wc.length>0){
						  $("#"+curr_wc).hide();
						  curr_wc = "";
					  }
				  }catch(e){}
				  //-->
				  $("#selecfolders-ID").fadeOut();
				  $("#popup-unmark .content-popup .contentdivs").html("All tweets has been moved to '"+folder_name+"'");
			  }else{
				  $("#popup-unmark .content-popup .contentdivs").html("Cannot move the tweets, please try again later !");
			  }
		  }});
}
function workflow_getdata(sUrl,t,w){
	//alert('page:'+wf_curr_page+' offset:'+wf_index)
	//console.log('workflow_getdata');
	if(parseFloat(wf_total)>0){
		
		if(t==1){
			if((wf_curr_page+1)>Math.ceil(parseFloat(wf_total)/parseFloat(wf_per_page))){
				//console.log('next page please');
				wf_total=0;
				wf_tweets = null;
				if(wf_next_url.length>0){
					sUrl = "index.php?req="+wf_next_url;
				}
			}
			
		}else if(t==0){
			if(wf_curr_page-1<0){
				//console.log('previous page please');
				wf_total=0;
				wf_tweets = null;
				if(wf_prev_url.length>0){
					sUrl = "index.php?req="+wf_prev_url;
				}
			}
		}else if(t==-1){
			//console.log('refresh');
			//force refresh
			wf_total=0;
		}
		else{}
	}
	
	if(wf_total==0){
		//console.log('wf_total==0');
		$.ajax({
			  url: sUrl+"&rand="+Math.random()*1000,
			  dataType: 'json',
			  beforeSend: function(){
				$("#wf_total_unmarked").html('Searching for');
				if(w!=null){
					 $('#wf_keyword').html(w);
				}
				$("#popup-unmark .content-popup .contentdivs").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
			  },
			  success: function( response ) {
				  if(response.status){
					 if(response.total==0){
						  var str = "no more unmarked tweets are available.";
						  $('#wf_keyword').html(response.keyword);
						  $("#popup-unmark .content-popup .contentdivs").html(str);
					  }else{
							var str = "";
							wf_tweets = response.data;
							wf_next_url = response.next_url;
							wf_prev_url = response.prev_url;
							wf_total = response.total;
							wf_total_unmark = response.total_unmark;
							//$("#wf_total_unmarked").html(addCommas(response.total_unmark));
							$("#wf_total_unmarked").html("");
							if(t==1){
								wf_curr_page = 1;
							}else{
								wf_curr_page = 25;
							}
							
							var sOffset = (wf_curr_page * wf_per_page) - wf_per_page;
							for(var i=0;i<wf_per_page;i++){
								var d = wf_tweets[sOffset+i];
								try{
								str+=workflow_content(sOffset+i,d,response.keyword);
								}catch(e){}
								
							}
							$('#wf_keyword').html(response.keyword);
							
							//popup folder untuk move all tweets from these keyword.
							$("#markfolder").html(wf_popup_folder_global(response.keyword,wf_tweets[0].flags2));
							
							wf_popup_reload_events();
							$("#popup-unmark .content-popup .contentdivs").html(str);
					 }
				  }else{
					var str = "unable to retrieve data.";
					//str+=workflow_content();
					$("#popup-unmark .content-popup .contentdivs").html(str);
				  }
				  reload_dropdown();
			  }});
	}else{
		//console.log('wf_total <> 0');
		//console.log('t : '+t);
		var str = "";

		if(t==1){
			wf_curr_page+=1;
			var sOffset = (wf_curr_page * wf_per_page) - wf_per_page;
			//console.log('sOffset : '+sOffset);
			//console.log('wf_per_page : '+wf_per_page);
			//console.log('length : '+wf_tweets.length);
			//if((sOffset+wf_per_page)>wf_tweets.length){
				//sworkflow_getdata(wf_curr_url,1,wf_curr_keyword);
			//}else{
				for(var i=0;i<wf_per_page;i++){
					var d = wf_tweets[sOffset+i];
					try{
						str+=workflow_content(sOffset+i,d,$('#wf_keyword').html());
					}catch(e){
						//console.log('out of offset');
					}
				}
			//}
		}else{
			wf_curr_page-=1;
			if(wf_curr_page<=0){
				wf_curr_page = 1;
			}
			var sOffset = (wf_curr_page * wf_per_page) - wf_per_page;
			for(var i=0;i<wf_per_page;i++){
				var d = wf_tweets[sOffset+i];
				try{
				str+=workflow_content(sOffset+i,d,$('#wf_keyword').html());
				}catch(e){}
			}
			
		}
		$("#popup-unmark .content-popup .contentdivs").html(str);
		reload_dropdown();
	}
}
function wf_popup_reload_events(){
	
	$(".btn_workflow_folder a").click(function(){
		
        var targetID = $(this).attr('href');
        $("#section1").hide();
        $(targetID).fadeIn();
        
        return false;
    });
    $("a.backbtn").click(function(){
        var targetID = $(this).attr('href');
        
        $("#section2").hide();
        $(targetID).fadeIn();
        
        return false;
    });
    $("a.newfolder").click(function(){
        var targetID = $(this).attr('href');
        $(targetID).fadeIn();
        return false;
    });
}
function wf_folder_page(id,feed_id,keyword){
	c_pid = id;
	c_feed_id = feed_id;
	c_keyword = keyword;
	$("#section1").hide();
    $("#section2").fadeIn();
    var sUrl = "index.php?req=Y6OJ-_yKdhliqkjKzgAUkkst7RAkMkJyes6w-NVkbemS7QRvVjoNZQ..&keyword="+keyword+"&feed_id="+feed_id;
    var str="";
    $.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_folder_list").html("<div style='width:197px;min-height:36px;background-color:white; margin:0 auto; padding:30px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");
		  },
		  success: function( response ) {
			  if(response.status==1){
				  var d = response.data;
				  for(var i in d){
					  str+=folder_item(id,d[i].folder_name,d[i].folder);
				  }
			  }else{
				  str = "<span class='message-failed'>Cannot load the folder, please try again later !</span>";
			  }
			  $("#wf_folder_list").html(str);
	}});
	
}
function wf_add_folder(){
	var folder_name = $("#wf_new_folder_txt").val();
	var sUrl = "index.php?req=Y6OJ-_yKdhliqkjKzgAUklUMEa6r2PpHmW4ueYL2Uzj9MsNi6VPUTw..&name="+folder_name+"&keyword="+c_keyword+"&feed_id="+c_feed_id;
    var str="";
    $.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_folder_list").html("<div style='width:197px;min-height:36px;background-color:white; margin:0 auto; padding:30px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");
		  },
		  success: function( response ) {
			  if(response.status==1){
				  wf_folder_page(c_pid,c_feed_id,c_keyword)
			  }
			  
	}});
}
function wf_add_folder2(){
	var folder_name = $("#wf_new_folder_txt").val();
	var sUrl = "index.php?req=Y6OJ-_yKdhliqkjKzgAUklUMEa6r2PpHmW4ueYL2Uzj9MsNi6VPUTw..&name="+folder_name+"&keyword="+c_keyword+"&feed_id="+c_feed_id;
    var str="";
    $.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_folder_list").html("<div style='width:197px;min-height:36px;background-color:white; margin:0 auto; padding:30px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");
		  },
		  success: function( response ) {
			  if(response.status==1){
				 document.location.reload();
			  }
			  
	}});
}
function folder_item(id,name,url){
	
	var str='<div class="list">';
    str+='<a class="grey-box folders" href=\'javascript:wf_flag('+id+',"'+url+'")\'>'+name+'</a>';
    str+='</div>';
    return str;
}
function wf_popup_back(){
	$("#section1").fadeIn();
    $("#section2").hide();
}
function wf_next(){
	workflow_getdata(wf_curr_url,1,null);
}
function wf_prev(){
	workflow_getdata(wf_curr_url,0,null);
}
function workflow_content(id,data,kw){
	
	var str="";
	str+="<div id=wf"+id+" class=\"list\">";
	str+="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	str+="<tr>";
	str+="<td>";
	str+="<div class=\"tweetcontent\">";
	str+="<div class=\"smallthumb\">";
	str+="<a href=\"#?w=650&id="+data.author_id+"\" class=\"poplight\" rel=\"profile\"><img src=\""+data.avatar_pic+"\"/></a>";
	str+="</div>";
	str+=" <div class=\"entry\">";
	str+="<h3>"+data.author_name+"</h3>";
	str+="<span>"+data.content+"</span>";
	str+="</div><!-- .entry -->";
	str+="<div class=\"entry-action\">";
	if(data.device=="blackberry"){
		str+="<a href=\"#\" class=\"active\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}
	if(data.device=="apple"){
		str+="<a href=\"#\" class=\"active\"><span class=\"apple\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"apple\" >&nbsp;</span></a>";
	}
	if(data.device=="android"){
		str+="<a href=\"#\" class=\"active\"><span class=\"android\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"android\" >&nbsp;</span></a>";
	}
	if(data.rt_imp==null){
		data.rt_imp = 0;
	}
	str+="<a class=\"icon-rts tip_trigger\" style=\"margin-left: 15px;\"> "+addCommas(data.rt_total)+"</a>";
	str+="<a class=\"icon-imp tip_trigger\"> "+addCommas(parseFloat(data.imp)+parseFloat(data.rt_imp))+" </a>";
	str+="<a class=\"icon-share tip_trigger\">"+data.share+"%</a>";
	str+="</div><!-- .entry-action -->";
	str+="</div> <!-- .tweetcontent -->";  
	str+="</td>";
	str+=" <td>";
	str+="<div id=\"wfmn"+id+"\" class=\"grey-box btn_workflow_folder\">";
	
	str+="<a href=\"#selecfolders-"+data.feed_id+"\" class=\"backbtn rightarrow\">&nbsp;</a>";
		
	str+="<div id=\"selecfolders-"+data.feed_id+"\" class=\"selecfolder\" style=\"display: none;\"><a href=\"#selecfolders-"+data.feed_id+"\" class=\"active-rightarrow\">&nbsp;</a>";
	//str+="<h3>Move to :</h3>";
	str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777"><span style="float:left;"><h3>Move to :</h3></span>';
    str+='<span style="float:right;"><a href="javascript:void(0);" onClick="close_folder_global();">X</a></span></div>';
  
	folder_list = data.flags;
	for(var s in folder_list){
		str+="<a href=\"javascript:wf_flag("+id+",'"+folder_list[s].url+"');\" class=\"listfolder\">"+folder_list[s].folder_name+"</a>";
	}
	str+="</div>";
	str+="</div>";
	str+="<div id=\"wfldr"+id+"\" class=\"grey-box\" style='display:none;'>";
	str+="<img src='images/loader.gif' align='center'/>";
	str+="</div>";
	str+="</td>";
	str+="</tr>";
	str+="</table>";
	str+="</div><!-- .list -->";
	return str;
}
function wf_flag2(type,url){
	//$("#wf"+id).remove();
	//remove item ini dari daftar
	
	$.ajax({
		  url: "index.php?req="+url,
		  dataType: 'json',
		  beforeSend: function(){
			$("#popup-unmark .content-popup .contentdivs").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			  if(response.status==1){
				  
				  wf_load_marked(wf_marked_curr_url);
				  wf_load_reply(wf_reply_curr_url);
				  wf_exc_reply(wf_exc_curr_url);
				  wf_load_analize(wf_analize_active_url);
				  reload_custom_tab();
				  $(wf_active_tab).fadeIn();
				  //$("ul.tabs li").removeClass("active"); //Remove any "active" class
			  }else{
				  
			  }
		  }});
	
	//workflow_getdata(wf_curr_url,-1);
}
function wf_flag(id,url){
	//$("#wf"+id).remove();
	//balik ke halaman listing
	wf_popup_back();
	//remove item ini dari daftar
	$("#wfmn"+id).hide();
	$("#wfldr"+id).show();
	
	$.ajax({
		  url: "index.php?req="+url,
		  dataType: 'json',
		  beforeSend: function(){
			$("#popup-unmark .content-popup .contentdivs").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			  if(response.status==1){
				  var new_items = [];
					for(var t in wf_tweets){
						if(t!=id){
							new_items.push(wf_tweets[t]);
						}
					}
					wf_tweets = new_items;
					new_items = null;
					wf_total--;
					var sOffset = (wf_curr_page * wf_per_page) - wf_per_page;
					//console.log('wf_curr_page : '+wf_curr_page);
					//console.log('wf_per_page : '+wf_per_page);
					//console.log('sOffset : ('+wf_curr_page+'*'+wf_per_page+') - '+wf_per_page);
					//console.log('sOffset : '+sOffset);
					var str="";
					for(var i=0;i<wf_per_page;i++){
						var nextOffset = sOffset+i;
						if(wf_tweets.length>nextOffset){
							console.log('more tweets');
							var d = wf_tweets[sOffset+i];
							try{
								str+=workflow_content(sOffset+i,d);
								
							}catch(e){
								console.log('empty offset');
							}
						}else{
							console.log('no more tweets');
							//these means. .these page is running out of contents
							//try to reload.. but first we have to know the total left.
							if(wf_total_unmark>wf_total){
								//reload
								refresh_popup_content();
							}
						}
					}
					$("#popup-unmark .content-popup .contentdivs").html(str);
					reload_dropdown();
			  }else{
				  $("#wfmn"+id).show();
				$("#wfldr"+id).hide();
			  }
		  }});
	
	//workflow_getdata(wf_curr_url,-1);
}
function wf_load_content(URLS){
	
	wf_exc_url = URLS[3];
	
	wf_load_marked(URLS[0]);
	wf_load_reply(URLS[1]);
	wf_exc_reply(URLS[3]);
	wf_load_analize(URLS[2]);
	wf_get_search_queue(URLS[4]);
}
function wf_custom_tab(obj){
	
	/*
	var f = obj.find("a").attr('id').split('tab_custom_');
	var folder_id = f[1];
	var sUrl = folders[obj.find("a").attr('id')];
	wf_load_custom(folder_id,sUrl);
	*/
}
function reload_custom_tab(){
	for(var s in folders){
		var f = s.split('tab_custom_');
		var folder_id = f[1];
		wf_load_custom(folder_id,folders[s]);
	}
}
function wf_load_custom(id,sUrl){
	//alert(url);
	var str="";
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_custom"+id).html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			  if(response.status==1){
				  var data =response.data;
				  wf_custom_next_url[id] = response.next_url;
				  wf_custom_prev_url[id] = response.prev_url;
				  wf_custom_curr_url[id] = sUrl;
				  $("#n-custom"+id).html(response.total);
				  var keyword="";
				
				  for(var i in data){
					  if(i+1==parseInt(response.per_page)){
						  str+=wf_custom_content(data[i],2);
					  }else if(i==0||keyword!=data[i].keyword){
						  keyword = data[i].keyword;
						  str+=wf_custom_content(data[i],1);
					  }else{
						  str+=wf_custom_content(data[i],0);
					  }
				  }
				 
				  if(response.total>10){
					 
					  $('#tab-custom'+id+' .paging').show();
				  }else{
					  
					  $('#tab-custom'+id+' .paging').hide();
				  }
				  
				  if(str.length>0){
					  $('#wf_custom'+id).html(str);
				  }else{
					  $('#wf_custom'+id).html("<div class='not-found-data' align='center'><img src='images/smac-no-items.png' /></div>");
					  //$('.tab_workflow_custom .paging').css({'display' : 'none'});
					  
				  }
				  reload_dropdown();
			  }
		  }});

}
function wf_load_marked(sUrl){
	//alert(url);
	var str="";
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_marked").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			  if(response.status==1){
				  var data =response.data;
				  wf_marked_next_url = response.next_url;
				  wf_marked_prev_url = response.prev_url;
				  wf_marked_curr_url = sUrl;
				  $("#n-marked").html(response.total);
				  var keyword="";
				
				  for(var i in data){
					  if(i+1==parseInt(response.per_page)){
						  str+=wf_marked_content(data[i],2);
					  }else if(i==0||keyword!=data[i].keyword){
						  keyword = data[i].keyword;
						  str+=wf_marked_content(data[i],1);
					  }else{
						  str+=wf_marked_content(data[i],0);
					  }
				  }
				  if(str.length>0){
					  $('#wf_marked').html(str);
				  }else{
					  $('#wf_marked').html("<div class='not-found-data' align='center'><img src='images/smac-no-items.png' /></div>");
					  $('#tab-marked .paging').css({'display' : 'none'});
				  }
				  reload_dropdown();
			  }
		  }});
	/*var str="";
	str+=wf_marked_content(null,1);
	str+=wf_marked_content(null,0);
	str+=wf_marked_content(null,2);
	*/
	//$('#wf_marked').html(str);
}
function reload_dropdown(){
	console.log('reload_dropdown');
	jQuery("a.rightarrow").click(function(){
		var targetID = jQuery(this).attr('href');
		$("a.rightarrow").css({'display' : 'block'});
		$(this).css({'display' : 'none'});
		$("a.active-rightarrow").css({'display' : 'block'});
		jQuery(".selecfolder").hide();
		jQuery(targetID).fadeIn();
		return false;
	});
	jQuery("a.active-rightarrow").click(function(){
		$(this).css({'display' : 'none'});
		$("a.rightarrow").css({'display' : 'block'});
		jQuery(".selecfolder").css({'display' : 'none'});
		return false;
	});
}
function wf_load_analize(sUrl){
	//alert(url);
	var str="";
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_analize").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			  if(response.status==1){
				  var data =response.data;
				  wf_analize_next_url = response.next_url;
				  wf_analize_prev_url = response.prev_url;
				  wf_analize_active_url = sUrl;
				  $("#n-analize").html(response.total);
				  var keyword="";
				
				  for(var i in data){
					  if(i+1==parseInt(response.per_page)){
						  str+=wf_analize_content(data[i],2);
					  }else if(i==0||keyword!=data[i].keyword){
						  keyword = data[i].keyword;
						  str+=wf_analize_content(data[i],1);
					  }else{
						  str+=wf_analize_content(data[i],0);
					  }
				  }
				  if(str.length>0){
					  $('#wf_analize').html(str);
				  }else{
					  $('#wf_analize').html("<div class='not-found-data' align='center'><img src='images/smac-no-items.png' /></div>");
					  $('#tab-analyze .paging').css({'display' : 'none'});
				  }
				  reload_dropdown();

			  }
		  }});
	
	//$('#wf_reply').html(str);
}
function wf_load_reply(sUrl){
	//alert(url);
	var str="";
	wf_n_replied = 0;
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_reply").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			  if(response.status==1){
				  var data =response.data;
				  wf_reply_next_url = response.next_url;
				  wf_reply_prev_url = response.prev_url;
				  wf_reply_curr_url = sUrl;
				  
				  var keyword="";
				
				  for(var i in data){
					  if(i+1==parseInt(response.per_page)){
						  str+=wf_reply_content(data[i],2,i);
					  }else if(i==0||keyword!=data[i].keyword){
						  keyword = data[i].keyword;
						  str+=wf_reply_content(data[i],1,i);
					  }else{
						  str+=wf_reply_content(data[i],0,i);
					  }
				  }
				  $("#n-reply").html(response.total-wf_n_replied);
				  if(str.length>0){
					  $('#wf_reply').html(str);
				  }else{
					  $('#wf_reply').html("<div class='not-found-data' align='center'><img src='images/smac-no-items.png' /></div>");
					  $('#tab-reply .paging').css({'display' : 'none'});
				  }
				  reload_dropdown();
				  
			  }
		  }});
	
	//$('#wf_reply').html(str);
}
function wf_exc_reply(sUrl){
	//alert(url);
	var str="";
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_exc").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			  if(response.status==1){
				  var data =response.data;
				  var jobs = response.jobs;
				  
				  wf_exc_next_url = response.next_url;
				  wf_exc_prev_url = response.prev_url;
				  wf_exc_curr_url = sUrl;
				  $("#n-exclude").html(response.total);
				  var keyword="";
				  
				  for(var i in data){
					  if(i+1==parseInt(response.per_page)){
						  str+=wf_exc_content(data[i],2);
					  }else if(i==0||keyword!=data[i].keyword){
						  keyword = data[i].keyword;
						  str+=wf_exc_content(data[i],1,jobs);
					  }else{
						  str+=wf_exc_content(data[i],0,jobs);
					  }
				  }
				  if(str.length>0){
					  $('#wf_exc').html(str);
				  }else{
					  $('#wf_exc').html("<div class='not-found-data' align='center'><img src='images/smac-no-items.png' /></div>");
					  $('#tab-exclusions .paging').css({'display' : 'none'});
				  }
				  reload_dropdown();
				  
			  }
		  }});
	
	//$('#wf_reply').html(str);
}
function wf_people_profile(uid,uname,m_uri,g_uri,gwc,cwc,p_url,search_url,inf1,inf2,exc_person){
	//reset paging
	wf_analize_start = 0;
	
	
	$("#wf_interval").hide();
	$("#wf_interval_prog").hide();
	
	$('#analyze').hide();
	$('#analyze-1').show();
	var str = wf_analize_people_content(uid,uname,p_url,search_url,exc_person);
	$('#analyze-1').html(str);
	wf_person_detail(uid);
	//load popup content
	//cek checkbox
	wf_all = g_uri;
	wf_gwc = gwc;
	wf_cwc = cwc;
	wf_my = m_uri;
	wf_inf1 = inf1;
	wf_inf2 = inf2;
	t_uri = m_uri;
	wc_uri = cwc;
	
	wf_analize_curr_url = t_uri;
	//-->
	wf_analize_tweets(t_uri,wc_uri);
	wf_analize_state = 1;
	
	wf_analize_wordcloud(wc_uri);
	
	
	//influencer of
	wf_influencer_of(0);
	//influenced by
	wf_influenced_by(0);
	
	
	//back button
	$(".back-analyze").click(function(){
        var targetID = $(this).attr('href');
        $(".analyze-detail,.analyze").hide();
        $(targetID).fadeIn();
        wf_analize_state = 0;
    });
}
function wf_analize_tweets(url,wc_uri){
	var sUrl = "index.php?"+url+"&start="+wf_analize_start;
	
	var str="";
	//tweets
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_anl_tw").html("<div style='width:420px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			 
			  if(response.status=="1"){
				  var data =response.data;
				  for(var i in data){
					  str+= wf_analize_tweets_content(data[i],i);
				  }
				  if(str.length>10){
					$("#wf_anl_tw").html(str);
				  }else{
					$("#wf_anl_tw").html("<div class='not-found-data' align='center'><img src='images/smac-no-items.png' /></div>");
				  }
				  //other content
				  //
				  wf_render_map(data);
				  //-->
			  }
		  }});
}
function wf_analize_wordcloud(url){
	var sUrl = "index.php?req="+url;
	
	var str="";
	//tweets
	$.ajax({
		  url: sUrl,
		  dataType: 'text/html',
		  beforeSend: function(){
			$("#wf_wcbox").html("<script>n_wc=1;</script><div style='width:300px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			 
			  if(response.length>0){
				 $("#wf_wcbox").html(response);
			  }else{
				  $("#wf_wcbox").html("no wordcloud available yet.");
			  }
	}});
}
function wf_influencer_of(type){
	var sUrl = "index.php?req="+wf_inf1+"&type="+type;
	$("#wf_influencer_of").show();
	var str="";
	//tweets
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_influencer_of .content .list").html("<div style='width:260px;min-height:16px;background-color:white; margin:0 auto; padding:16px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");
		
		  },
		  success: function( response ) {
			  var data = response.data;
			  if(response.status=="1"){
				  for(var i in data){
					  if(data[i].author_id.length>0){
						  str+="<div class=\"smallthumb influenced-thumb\"><a href=\"#\"><img src=\""+data[i].author_avatar+"\" title='"+data[i].author_id+"'/></a><h3>"+data[i].author_id+"</h3></div>";
					  }
				  }
				  if(str.length>0){
					  $("#wf_influencer_of .content .list").html(str);
				  }else{
					  $("#wf_influencer_of .content .list").html("None");
				  }
			  }else{
				  $("#wf_influencer_of .content .list").html("NONE");
			  }
			  
	}});
}
function wf_influenced_by(type){
	var sUrl = "index.php?req="+wf_inf2+"&type="+type;
	
	var str="";
	//tweets
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_influenced_by .content .list").html("<div style='width:260px;min-height:16px;background-color:white; margin:0 auto; padding:16px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			  var data = response.data;
			  if(response.status=="1"){
				  for(var i in data){
					  if(data[i].author.length>0){
						  str+="<div class=\"smallthumb influenced-thumb\"><a href=\"#\"><img src=\""+data[i].author_pic+"\" title='"+data[i].author+"'/></a><h3>"+data[i].author+"</h3></div>";
					  }
				  }
				  if(str.length>0){
					  $("#wf_influenced_by .content .list").html(str);
				  }else{
					  $("#wf_influenced_by .content .list").html("None");
				  }
			  }else{
				  $("#wf_influenced_by .content .list").html("NONE");
			  } 
	}});
}
function wf_analize_tweets_next(){
	wf_analize_start+=4;
}
function wf_analize_tweets_prev(){
	wf_analize_start+=4;
}
function toggle_wf_tweet(uid,toggle){
	if(toggle==1){
		wf_analize_curr_url = wf_all;
		
		wf_analize_tweets(wf_all,wf_gwc);
		wf_analize_state = 1;
		
		wf_analize_wordcloud(wf_gwc);
		
		//influencer of
		wf_influencer_of(1);
		//influenced by
		wf_influenced_by(1);
		
		var flag = false;
		wf_curr_id = uid;
		if(wf_search_check){
			//check apakah sedang dalam progress apa tidak
			for(var i in wf_search){
				if(wf_search[i].author_id==uid){
					flag=true;
					break;
				}
			}
			if(flag){
				$("#wf_interval").hide();
				$("#wf_interval_prog").fadeIn();
			}else{
				$("#wf_interval").fadeIn();
				$("#wf_interval_prog").hide();
			}
		}
	}else{
		$("#wf_interval").fadeOut();
		wf_analize_curr_url = wf_my;
		wf_analize_tweets(wf_my,wf_cwc);
		wf_analize_state = 1;
		
		wf_analize_wordcloud(wf_cwc);
		//influencer of
		wf_influencer_of(0);
		//influenced by
		wf_influenced_by(0);
	}
}
function wf_btn_duration_clicked(uid,url,search_url){
	
	$("#popup-profile-estimate .content-popup .paging").hide();
	$("#popup-profile-estimate").fadeIn();
	$("#profile_id").html('@'+uid);
	//run the estimation
	var sUrl = "index.php?req="+url;
	
	wf_search_url = search_url;
	//estimation
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#popup-profile-estimate .content-popup .contentdivs").html("<img src='images/loader.gif' align='center'/><p>Please wait while we're estimating your search volume</p>");			
		  },
		  success: function( response ) {
			 //var o = $.parseJSON(response);
			 var o = response;
			 try{
				 var daily_est = parseFloat(o.d);
				 var days = $('#wf_profiling_duration').val();
				
				 var volume = addCommas(daily_est*parseInt(days));
			 }catch(e){
				 volume = "0";
			 }
			 $("#popup-profile-estimate .content-popup .contentdivs").html("Estimate Volume for "+days+" days : "+volume+"<br/>Are you sure to continue ?");
			 $("#popup-profile-estimate .content-popup .paging").show();
	}});
	//--->
	
}
function wf_get_search_queue(url){
	var sUrl = url;
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  success: function( response ) {
				wf_search_check = true;
				wf_search = response.data;
	}});
}
function wf_profiling_cancel(){
	$("#popup-profile-estimate").fadeOut();
	$("#wf_interval").fadeIn();
	$("#wf_interval_prog").fadeOut();
}
function wf_profiling_proceed(){
	
	//do something
	var sUrl = "index.php?req="+wf_search_url+"&interval="+$('#wf_profiling_duration').val();
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#popup-profile-estimate .content-popup .contentdivs").html("<img src='images/loader.gif' align='center'/><p>Saving your request..</p>");		
		  },
		  success: function( response ) {
			  if(response.status==1){
				  try{
					  wf_search.push({'author_id':wf_curr_id});
				  }catch(e){
					  wf_search = [{'author_id':wf_curr_id}];
				  }
			  }else{
				  	alert('Sorry, your request is failed. Please try again later !');
			  }
			  $("#popup-profile-estimate").fadeOut();
			  $("#wf_interval").fadeOut();
			  $("#wf_interval_prog").fadeIn();
		}});
	//-->
	
	//--->
}
function wf_exclude_person(url){
	
	var sUrl = "index.php?req="+url;
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
				$("#wf_exclude_btn").html("<img src='images/loader.gif'/>");		
		  },
		  success: function( response ) {
			 if(response.status==1){
				 $("#wf_exclude_btn").html("these person has been excluded successfully !");		
			 }else{
				 $("#wf_exclude_btn").html("failed to exclude these person.");		
			 }
		}});
}
function wf_analize_people_content(uid,uname,p_url,search_url,excp_url){
var str="<div class=\"mtitle\">";
str+="<h3 class=\"fleft\">Analyzing: @"+uid+" - "+uname+"</h3>";
str+="<h3 class=\"fright\"><a href=\"#analyze\" class=\"back-analyze\">&lsaquo;&lsaquo;&nbsp;Back</a></h3>";
str+="</div>";
 str+="<div class=\"content\">";
 str+="<div id='wf_profile_box'>";
 str+="</div>";
 str+="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
 str+="<tr>";
 str+="<td>";
 str+="<div id='wf_interval' class=\"duration\" style='display:none;'>";
 str+="<form >";
 str+="<label>Select durations:</label>";
 str+="<select id='wf_profiling_duration'>";
 str+="<option value='1'>1 Days</option>";
 str+="<option value='3'>3 Days</option>";
 str+="<option value='7'>7 Days</option>";
 str+="<option value='14'>14 Days</option>";
 str+="<option value='30'>1 Month</option>";
 str+="</select>";
 str+="<input type='button' name='wf_btn_duration' value='Search' onclick='wf_btn_duration_clicked(\""+uid+"\",\""+p_url+"\",\""+search_url+"\")'/>";
 str+="</form>";
 str+="</div>";
 str+="<div id='wf_interval_prog' class=\"duration\" style='display:none;'>";
 str+="<div class='loading-message'><img src='images/loader.gif'/><div class='loading-text'>Retrieving Data</br>This could take some time depending on volume</div></div>";
 str+="</div>";
 str+="</td>";
 str+="<td>";
 str+="<div class=\"tweet-short\">";
 str+="<form>";
 str+="<input type=\"radio\" name='source' id='wf_profile_source' value='myself' checked='checked' onclick='toggle_wf_tweet(\""+uid+"\",0);'/>";
 str+="<label>Tweets on topic</label>";
 str+="<input type=\"radio\" name='source' id='wf_profile_source' value='global' onclick='toggle_wf_tweet(\""+uid+"\",1);'/>";
 str+="<label>All Tweets*</label>";
 str+="</form>";
 str+="</div>";
 str+="<div id='wf_exclude_btn' class='paging'><a href='#' onclick='wf_exclude_person(\""+excp_url+"\");return false;'>Exclude These Person</a></div>";
 str+="</td>";
 str+="</tr>";
 str+="<tr>";
 str+="<td valign=\"top\">";
 str+="<div class=\"tweetbox\" id='wf_anl_tw'>";

 str+=" </div>";
 str+="</td>";
 str+="<td valign=\"top\" width=\"300\">";
 str+="<div class=\"wordcloud-box\">";
 str+="<div id=\"wf_wcbox\" style=\"position:absolute;margin:0 0 0 -130px;left:30%;overflow:hidden;width:330px;height:276px;\"></div>";
 str+="</div>";
 str+="</td>";
 str+="</tr>";
 str+="<tr>";
 str+="<td colspan=\"2\">";
 str+="<div class=\"workflow-influenced\" id='wf_influenced_by'>";
 str+="<div class=\"mtitle grey\"><h3 class=\"influenced-icon\">Influenced By</h3></div>";
 str+="<div class=\"content\">";
 str+="<div class=\"list\">";
 str+="<div class=\"smallthumb mr10\"><a href=\"#\"><img src=\"http://a0.twimg.com/profile_images/533755745/gadis-profile_pict_normal.JPG\" /></a></div>";
 //str+="<div class=\"smallthumb mores\"><a href=\"#?w=650&id=influenced-by\" class=\"poplight\" rel=\"popup-influenced-list\">MORE</a></div>";
 str+="</div>";
 str+="</div>";
 str+="</div>";
 str+="</td>";
 str+="</tr>";
 str+="<tr>";
 str+="<td colspan=\"2\">";
 str+="<div class=\"workflow-influenced\" id='wf_influencer_of'>";
 str+="<div class=\"mtitle grey\"><h3 class=\"influenced-icon\">Influencer Of</h3></div>";
 str+="<div class=\"content\">";
 str+="<div class=\"list\">";
 //str+="<div class=\"smallthumb mores\"><a href=\"#?w=650&id=influenced-of\" class=\"poplight\" rel=\"popup-influenced-list\">MORE</a></div>";
 str+="</div>";
 str+="</div>";
 str+="</div>";
 str+="</td>";
 str+="</tr>";
 str+="<tr>";
 str+="<td colspan=\"2\">";
 str+="<div id=\"wf_map\" class=\"map\">";
 //str+="<iframe width=\"100%\" height=\"250\" frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"http://maps.google.com/maps?hl=in&amp;georestrict=input_srcid:d4788cbd04828ade&amp;ie=UTF8&amp;view=map&amp;cid=13345342660711292025&amp;q=Nuansa+Musik+Pondok+Pinang&amp;hq=Nuansa+Musik+Pondok+Pinang&amp;hnear=&amp;t=m&amp;iwloc=A&amp;ll=-6.273729,106.773719&amp;spn=0.006295,0.006295&amp;output=embed\"></iframe><br /><small><a href=\"http://maps.google.com/maps?hl=in&amp;georestrict=input_srcid:d4788cbd04828ade&amp;ie=UTF8&amp;view=map&amp;cid=13345342660711292025&amp;q=Nuansa+Musik+Pondok+Pinang&amp;hq=Nuansa+Musik+Pondok+Pinang&amp;hnear=&amp;t=m&amp;iwloc=A&amp;ll=-6.273729,106.773719&amp;spn=0.006295,0.006295&amp;source=embed\" style=\"color:#0000FF;text-align:left\">View Larger Map</a></small>";
 str+="</div>";
 str+="</td>";
 str+="</tr>";
 str+="</table>";
 str+="</div><!-- .content -->";
 return str;
}
function wf_analize_tweets_content(data,index){
	var tbcolor = "#f2f2f2";
	if(index%2==0||index==0){
		tbcolor = "#f2f2f2";
	}else{
		tbcolor = "#f8f8f8";
	}
	var str="";
	str+="<div class=\"row\" style=\"background:"+tbcolor+";\">";  
	str+="<div class=\"entry\">";
	str+="<h3>"+data.published_date+"</h3>";
	str+="<span>"+data.content+"</span>";
	str+="</div><!-- .entry -->";
	str+="</div><!-- .row -->";
	return str;
}
function wf_analize_content(data,flag){
	var str="";
	if(flag==1){
		str+="<div class=\"mtitle\">";
	    str+="<h3 class=\"fleft\">Keyword: "+data.keyword+"</h3>";
	    str+="<!--<h3 class=\"fright\">3 People Marked</h3>-->";
	    str+="</div>";
		str+="<div class=\"content\">";
	}
	str+="<div class=\"list\">";
	str+="<div class=\"tweetcontent\">";
	str+="<div class=\"smallthumb\">";
	str+=" <a href=\"#\" onclick='wf_people_profile(\""+data.author_id+"\",\""+data.author_name+"\",\""+data.my_url+"\",\""+data.global_url+"\",\""+data.gwc_url+"\",\""+data.cwc_url+"\",\""+data.p_url+"\",\""+data.search_url+"\",\""+data.inf_url+"\",\""+data.infby_url+"\",\""+data.ex_person+"\");return false;'><img src=\""+data.avatar_pic+"\" /></a>";
	str+="</div>";
	str+="<div class=\"entry\">";
	str+="<h3>"+data.author_name+"</h3>";
	str+="<span>"+data.content+"</span>";
	str+="</div><!-- .entry -->";
	str+="<div class=\"entry-action\">";
	if(data.device=="blackberry"){
		str+="<a href=\"#\" class=\"active\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}
	if(data.device=="apple"){
		str+="<a href=\"#\" class=\"active\"><span class=\"apple\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"apple\" >&nbsp;</span></a>";
	}
	if(data.device=="android"){
		str+="<a href=\"#\" class=\"active\"><span class=\"android\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"android\" >&nbsp;</span></a>";
	}
	str+="<a class=\"icon-rts tip_trigger\" style=\"margin-left: 15px;\"> "+addCommas(data.rt_total)+" <span class=\"tip\">Retweet Frequency</span></a>";
	str+=" <a class=\"icon-imp tip_trigger\"> "+addCommas(parseFloat(data.imp)+parseFloat(data.rt_imp))+" <span class=\"tip\">Total Impressions</span></a>";
	str+=" <a class=\"icon-share tip_trigger\">"+data.share+"% <span class=\"tip\">Share</span></a>";
	str+=" </div><!-- .entry-action -->";
	str+="</div> <!-- .tweetcontent -->   ";
	str+="<div class=\"grey-box\">";
	
	str+="<a href=\"#selecfolder-"+data.feed_id+"\" class=\"rightarrow\">&nbsp;</a>";
	str+="<div id=\"selecfolder-"+data.feed_id+"\" class=\"selecfolder\" style=\"display: none;\"><a href=\"#selecfolder-"+data.feed_id+"\" class=\"active-rightarrow\">&nbsp;</a>";
	//str+="<h3>Move to :</h3>";
	str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777"><span style="float:left;"><h3>Move to :</h3></span>';
    str+='<span style="float:right;"><a href="javascript:void(0);" onClick="close_folder_global();">X</a></span></div>';
  
	folder_list = data.flags;
	for(var s in folder_list){
		str+="<a href=\"javascript:wf_flag2(3,'"+folder_list[s].url+"');\" class=\"listfolder\">"+folder_list[s].folder_name+"</a>";
	}
	str+="</div>";
	
	//str+="<a href=\"javascript:wf_flag2(3,'"+data.mark_url+"');\" class=\"icon_workflow\">&nbsp;</a>";
	//str+="<a href=\"javascript:wf_flag2(3,'"+data.reply_url+"');\" class=\"icon_commentw\">&nbsp;</a>";
	//str+="<a href=\"javascript:wf_flag2(3,'"+data.exclude_url+"');\" class=\"icon_stopw\">&nbsp;</a>";
	str+="</div>";
	str+="</div>  <!-- .list --> ";
	if(flag==2){
		str+="</div>";
	}
	return str;
}
function wf_exc_content(data,flag,jobs){
	
	var str="";
	
	in_progress = false;
	if(jobs.length>0){
		for(var t in jobs){
			if(jobs[t].keyword==data.keyword){
				in_progress = true;
				break;
			}
		}
	}
	
	if(flag==1){
		str+="<div class=\"mtitle\">";
	    str+="<h3 class=\"fleft\">Keyword: "+data.keyword+"</h3>";
	    //str+="<!--<h3 class=\"fright\">3 People Marked</h3>-->";
	    if(in_progress==false){
	    	str+="&nbsp;<span id='exc-apply'><a href='#' style='color:#ffffff;' onClick='apply_exclude_all(\""+data.keyword+"\")'>Apply</a></span>";
	    }
	    str+="</div>";
		str+="<div class=\"content\">";
	}
	str+="<div class=\"list\">";
	str+="<div class=\"tweetcontent\">";
	str+="<div class=\"smallthumb\">";
	str+=" <a href=\"#?w=650&id="+data.author_id+"\" class=\"poplight\" rel=\"profile\"><img src=\""+data.avatar_pic+"\" /></a>";
	str+="</div>";
	str+="<div class=\"entry\">";
	str+="<h3>"+data.author_name+"</h3>";
	str+="<span>"+data.content+"</span>";
	str+="</div><!-- .entry -->";
	str+="<div class=\"entry-action\">";
	if(data.device=="blackberry"){
		str+="<a href=\"#\" class=\"active\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}
	if(data.device=="apple"){
		str+="<a href=\"#\" class=\"active\"><span class=\"apple\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"apple\" >&nbsp;</span></a>";
	}
	if(data.device=="android"){
		str+="<a href=\"#\" class=\"active\"><span class=\"android\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"android\" >&nbsp;</span></a>";
	}
	
	str+="<a class=\"icon-rts tip_trigger\" style=\"margin-left: 15px;\"> "+addCommas(data.rt_total)+" <span class=\"tip\">Retweet Frequency</span></a>";
	str+=" <a class=\"icon-imp tip_trigger\"> "+addCommas(parseFloat(data.imp)+parseFloat(data.rt_imp))+" <span class=\"tip\">Total Impressions</span></a>";
	str+=" <a class=\"icon-share tip_trigger\">"+data.share+"% <span class=\"tip\">Share</span></a>";
	str+=" </div><!-- .entry-action -->";
	str+="</div> <!-- .tweetcontent -->   ";
	str+="<div class=\"grey-box\" style='min-width:105px;'>";
	//str+="<div class=\"grey-box\">";
	str+="<a href=\"#selecfolder-"+data.feed_id+"\" class=\"rightarrow\" style='float:left;position:absolute;left:5px;'>&nbsp;</a>";
	//str+="<a href=\"#selecfolder-"+data.feed_id+"\" class=\"rightarrow\">&nbsp;</a>";
	
	if(in_progress){
		str+="<a id='btn_apply"+data.feed_id+"' href=\"javascript:void(0);\" class=\"icon_jam_green\" title='exclusion is in progress' style='float:right;'>&nbsp;</a>";
	}else{
		if(data.is_deleted==0){
			str+="<a id='btn_apply"+data.feed_id+"' href=\"javascript:void(0);\" class=\"icon_workflow\" title='apply to topic' style='float:right;' onclick='apply_exclude(\""+data.feed_id+"\")'>&nbsp;</a>";
			str+="<a id='btn_undo"+data.feed_id+"' href=\"javascript:void(0);\" class=\"icon_undo\" title='undo' style='float:right;display:none;' onclick='apply_undo(\""+data.feed_id+"\")'>&nbsp;</a>";
		}else{
			str+="<a id='btn_apply"+data.feed_id+"' href=\"javascript:void(0);\" class=\"icon_workflow\" title='apply to topic' style='float:right;display:none;' onclick='apply_exclude(\""+data.feed_id+"\")'>&nbsp;</a>";
			str+="<a id='btn_undo"+data.feed_id+"' href=\"javascript:void(0);\" class=\"icon_undo\" title='undo' style='float:right;' onclick='apply_undo(\""+data.feed_id+"\")'>&nbsp;</a>";
	
		}
	}
	str+="<div id=\"selecfolder-"+data.feed_id+"\" class=\"selecfolder\" style=\"display: none;\"><a href=\"#selecfolder-"+data.feed_id+"\" class=\"active-rightarrow\">&nbsp;</a>";
	//str+="<h3>Move to :</h3>";
	str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777"><span style="float:left;"><h3>Move to :</h3></span>';
    str+='<span style="float:right;"><a href="javascript:void(0);" onClick="close_folder_global();">X</a></span></div>';
  
	folder_list = data.flags;
	for(var s in folder_list){
		str+="<a href=\"javascript:wf_flag2(4,'"+folder_list[s].url+"');\" class=\"listfolder\">"+folder_list[s].folder_name+"</a>";
	}
	str+="</div>";
	//str+="<a href=\"javascript:wf_flag2(4,'"+data.reply_url+"');\" class=\"icon_commentw\">&nbsp;</a>";
	//str+="<a href=\"javascript:wf_flag2(4,'"+data.analize_url+"');\" class=\"icon_searchw\">&nbsp;</a>";
	str+="</div>";
	str+="</div>  <!-- .list --> ";
	if(flag==2){
		str+="</div>";
	}
	return str;
}
function wf_custom_content(data,flag){
	var str="";
	if(flag==1){
		str+="<div class=\"mtitle\">";
	    str+="<h3 class=\"fleft\">Keyword: "+data.keyword+"</h3>";
	    str+="<!--<h3 class=\"fright\">3 People Marked</h3>-->";
	    str+="</div>";
		str+="<div class=\"content\">";
	}
	str+="<div class=\"list\">";
	str+="<div class=\"tweetcontent\">";
	str+="<div class=\"smallthumb\">";
	str+=" <a href=\"#?w=650&id="+data.author_id+"\" class=\"poplight\" rel=\"profile\"><img src=\""+data.avatar_pic+"\" /></a>";
	str+="</div>";
	str+="<div class=\"entry\">";
	str+="<h3>"+data.author_name+"</h3>";
	str+="<span>"+data.content+"</span>";
	str+="</div><!-- .entry -->";
	str+="<div class=\"entry-action\">";
	if(data.device=="blackberry"){
		str+="<a href=\"#\" class=\"active\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}
	if(data.device=="apple"){
		str+="<a href=\"#\" class=\"active\"><span class=\"apple\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"apple\" >&nbsp;</span></a>";
	}
	if(data.device=="android"){
		str+="<a href=\"#\" class=\"active\"><span class=\"android\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"android\" >&nbsp;</span></a>";
	}
	str+="<a class=\"icon-rts tip_trigger\" style=\"margin-left: 15px;\"> "+addCommas(data.rt_total)+" <span class=\"tip\">Retweet Frequency</span></a>";
	str+=" <a class=\"icon-imp tip_trigger\"> "+addCommas(parseFloat(data.imp)+parseFloat(data.rt_imp))+" <span class=\"tip\">Total Impressions</span></a>";
	str+=" <a class=\"icon-share tip_trigger\">"+data.share+"% <span class=\"tip\">Share</span></a>";
	str+=" </div><!-- .entry-action -->";
	str+="</div> <!-- .tweetcontent -->   ";
	str+="<div class=\"grey-box\">";
	str+="<a href=\"#selecfolder-"+data.feed_id+"\" class=\"rightarrow\">&nbsp;</a>";
	str+="<div id=\"selecfolder-"+data.feed_id+"\" class=\"selecfolder\" style=\"display: none;\"><a href=\"#selecfolder-"+data.feed_id+"\" class=\"active-rightarrow\">&nbsp;</a>";
	//str+="<h3>Move to :</h3>";
	str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777"><span style="float:left;"><h3>Move to :</h3></span>';
    str+='<span style="float:right;"><a href="javascript:void(0);" onClick="close_folder_global();">X</a></span></div>';
  
	folder_list = data.flags;
	for(var s in folder_list){
		str+="<a href=\"javascript:wf_flag2(5,'"+folder_list[s].url+"');\" class=\"listfolder\">"+folder_list[s].folder_name+"</a>";
	}
	str+="</div>";
	//str+="<a href=\"javascript:wf_flag2(1,'"+data.reply_url+"');\" class=\"icon_commentw\">&nbsp;</a>";
	//str+="<a href=\"javascript:wf_flag2(1,'"+data.analize_url+"');\" class=\"icon_searchw\">&nbsp;</a>";
	//str+="<a href=\"javascript:wf_flag2(1,'"+data.exclude_url+"');\" class=\"icon_stopw\">&nbsp;</a>";
	//str+="<a href=\"#\" class=\"icon_commentw\">&nbsp;</a>";
	//str+="<a href=\"#\" class=\"icon_searchw\">&nbsp;</a>";
	//str+="<a href=\"#\" class=\"icon_stopw\">&nbsp;</a>";
	str+="</div>";
	str+="</div>  <!-- .list --> ";
	if(flag==2){
		str+="</div>";
	}
	return str;
}
function wf_marked_content(data,flag){
	var str="";
	if(flag==1){
		str+="<div class=\"mtitle\">";
	    str+="<h3 class=\"fleft\">Keyword: "+data.keyword+"</h3>";
	    str+="<!--<h3 class=\"fright\">3 People Marked</h3>-->";
	    str+="</div>";
		str+="<div class=\"content\">";
	}
	str+="<div class=\"list\">";
	str+="<div class=\"tweetcontent\">";
	str+="<div class=\"smallthumb\">";
	str+=" <a href=\"#?w=650&id="+data.author_id+"\" class=\"poplight\" rel=\"profile\"><img src=\""+data.avatar_pic+"\" /></a>";
	str+="</div>";
	str+="<div class=\"entry\">";
	str+="<h3>"+data.author_name+"</h3>";
	str+="<span>"+data.content+"</span>";
	str+="</div><!-- .entry -->";
	str+="<div class=\"entry-action\">";
	if(data.device=="blackberry"){
		str+="<a href=\"#\" class=\"active\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"blackberry\" >&nbsp;</span></a>";
	}
	if(data.device=="apple"){
		str+="<a href=\"#\" class=\"active\"><span class=\"apple\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"apple\" >&nbsp;</span></a>";
	}
	if(data.device=="android"){
		str+="<a href=\"#\" class=\"active\"><span class=\"android\" >&nbsp;</span></a>";
	}else{
		str+="<a href=\"#\"><span class=\"android\" >&nbsp;</span></a>";
	}
	str+="<a class=\"icon-rts tip_trigger\" style=\"margin-left: 15px;\"> "+addCommas(data.rt_total)+" <span class=\"tip\">Retweet Frequency</span></a>";
	str+=" <a class=\"icon-imp tip_trigger\"> "+addCommas(parseFloat(data.imp)+parseFloat(data.rt_imp))+" <span class=\"tip\">Total Impressions</span></a>";
	str+=" <a class=\"icon-share tip_trigger\">"+data.share+"% <span class=\"tip\">Share</span></a>";
	str+=" </div><!-- .entry-action -->";
	str+="</div> <!-- .tweetcontent -->   ";
	str+="<div class=\"grey-box\">";
	str+="<a href=\"#selecfolder-"+data.feed_id+"\" class=\"rightarrow\">&nbsp;</a>";
	str+="<div id=\"selecfolder-"+data.feed_id+"\" class=\"selecfolder\" style=\"display: none;\"><a href=\"#selecfolder-"+data.feed_id+"\" class=\"active-rightarrow\">&nbsp;</a>";
	//str+="<h3>Move to :</h3>";
	str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777"><span style="float:left;"><h3>Move to :</h3></span>';
    str+='<span style="float:right;"><a href="javascript:void(0);" onClick="close_folder_global();">X</a></span></div>';
  
	folder_list = data.flags;
	for(var s in folder_list){
		str+="<a href=\"javascript:wf_flag2(1,'"+folder_list[s].url+"');\" class=\"listfolder\">"+folder_list[s].folder_name+"</a>";
	}
	str+="</div>";
	//str+="<a href=\"javascript:wf_flag2(1,'"+data.reply_url+"');\" class=\"icon_commentw\">&nbsp;</a>";
	//str+="<a href=\"javascript:wf_flag2(1,'"+data.analize_url+"');\" class=\"icon_searchw\">&nbsp;</a>";
	//str+="<a href=\"javascript:wf_flag2(1,'"+data.exclude_url+"');\" class=\"icon_stopw\">&nbsp;</a>";
	//str+="<a href=\"#\" class=\"icon_commentw\">&nbsp;</a>";
	//str+="<a href=\"#\" class=\"icon_searchw\">&nbsp;</a>";
	//str+="<a href=\"#\" class=\"icon_stopw\">&nbsp;</a>";
	str+="</div>";
	str+="</div>  <!-- .list --> ";
	if(flag==2){
		str+="</div>";
	}
	return str;
}
function wf_char_count(no){
	var left = 140-parseInt($("#wf_reply_txt_"+no).val().length);
	if(left<0){
		$("#wf_reply_txt_"+no).val($("#wf_reply_txt_"+no).val().substring(0,140));
		left=0;
	}
	$("#wf_cnt_"+no).html(left);
}
function wf_reply(no,uid,flag_url){
	var sUrl = reply_url+"&status=@"+uid+" "+$("#wf_reply_txt_"+no).val();
	
	var str="";
	//tweets
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_comment_"+no).html("<div style='width:197px;min-height:36px;background-color:white; margin:0 auto; padding:30px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");
		  },
		  success: function( response ) {
			  if(response.status==1){
				 mark_as_replied(no,flag_url);
			  }else{
				  $("#wf_comment_"+no).html("<span class='message-failed'>Failed to send reply. Please try again later</span>");
			  }
	}});
}
function mark_as_replied(no,flag_url){
	var sUrl = 'index.php?req='+flag_url;
	
	var str="";
	//tweets
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#wf_comment_"+no).html("<div style='width:197px;min-height:36px;background-color:white; margin:0 auto; padding:30px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");
		  },
		  success: function( response ) {
			  if(response.status==1){
				  wf_n_replied++;
				  $("#n-reply").html(parseInt($("#n-reply").html())-1);
				  $("#wf_comment_"+no).html("<span class='message-sent'>Reply Sent on "+response.data.reply_time+"</span>");
			  }else{
				  $("#wf_comment_"+no).html("<span class='message-failed'>Failed to send reply. Please try again later</span>");
			  }
	}});
}
function wf_reply_content(data,flag,no){
	var str="";
	
	if(flag==1){
		str+="<div class=\"mtitle\">";
	    str+="<h3 class=\"fleft\">Keyword: "+data.keyword+"</h3>";
	    str+="<!--<h3 class=\"fright\">3 People Marked</h3>-->";
	    str+="</div>";
		str+="<div class=\"content\">";
	}
		str+="<div class=\"list\">";
		str+="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
		str+="<tr>";
		str+="<td valign=\"top\">";
		str+="<div class=\"tweetcontent\">";
		str+="<div class=\"smallthumb\">";
		str+=" <a href=\"#?w=650&id="+data.author_id+"\" class=\"poplight\" rel=\"profile\"><img src=\""+data.avatar_pic+"\" /></a>";
		str+="</div>";
		str+="<div class=\"entry\">";
		str+="<h3>"+data.author_name+"</h3>";
		str+="<span>"+data.content+"</span>";	
		str+="</div><!-- .entry -->";
		str+="<div class=\"entry-action\">";
		if(data.device=="blackberry"){
			str+="<a href=\"#\" class=\"active\"><span class=\"blackberry\" >&nbsp;</span></a>";
		}else{
			str+="<a href=\"#\"><span class=\"blackberry\" >&nbsp;</span></a>";
		}
		if(data.device=="apple"){
			str+="<a href=\"#\" class=\"active\"><span class=\"apple\" >&nbsp;</span></a>";
		}else{
			str+="<a href=\"#\"><span class=\"apple\" >&nbsp;</span></a>";
		}
		if(data.device=="android"){
			str+="<a href=\"#\" class=\"active\"><span class=\"android\" >&nbsp;</span></a>";
		}else{
			str+="<a href=\"#\"><span class=\"android\" >&nbsp;</span></a>";
		}
		str+="<a class=\"icon-rts tip_trigger\" style=\"margin-left: 15px;\"> "+addCommas(data.rt_total)+" <span class=\"tip\">Retweet Frequency</span></a>";
		str+=" <a class=\"icon-imp tip_trigger\"> "+addCommas(parseFloat(data.imp)+parseFloat(data.rt_imp))+" <span class=\"tip\">Total Impressions</span></a>";
		str+=" <a class=\"icon-share tip_trigger\">"+data.share+"% <span class=\"tip\">Share</span></a>";
		
		str+="</div><!-- .entry-action -->";
		str+="</div> <!-- .tweetcontent -->   ";
		str+="</td>";
		str+="<td valign=\"top\" align=\"right\" width=\"215\">";
		str+="<div id='wf_comment_"+no+"' class=\"commentbox\">";
		if(is_authorized){
			if(data.reply_date==null){
				str+="<form action=\"\" class=\"comment-box\" id='wf_reply_"+no+"'>";
				str+="<textarea id='wf_reply_txt_"+no+"' onkeyup='wf_char_count("+no+")'></textarea>";
				str+="<span class=\"count-char\" id='wf_cnt_"+no+"'>140</span>";
				str+="<input type=\"button\" value=\"SEND REPLY\" class=\"send-reply\" onclick=\"wf_reply("+no+",'"+data.author_id+"','"+data.flag_url+"')\"/>";
				str+="</form>";
			}else{
				wf_n_replied++;
				str+="<span class='message-sent'>Reply Sent on "+data.reply_date+"</span>";
			}
		}else{
			str+="<span class='message-sent' style='width:175px;'><a href='?req=8lGYeZrFHFZgedAG2fFT4s7R7rlKAwy8VB46eG0Ik2w.'>Please authorize your account here to send a reply.</a></span>";
		}
		str+="</div> ";
		str+="</td>";
		str+="<td valign=\"top\" align=\"right\" width=\"55\">";
		str+="<div class=\"grey-box\">";
		str+="<a href=\"#selecfolder-"+data.feed_id+"\" class=\"rightarrow\">&nbsp;</a>";
		str+="<div id=\"selecfolder-"+data.feed_id+"\" class=\"selecfolder\" style=\"display: none;\"><a href=\"#selecfolder-"+data.feed_id+"\" class=\"active-rightarrow\">&nbsp;</a>";
		//str+="<h3>Move to :</h3>";
		str+='<div style="height:30px;width:175px;border-bottom:1px solid #777777"><span style="float:left;"><h3>Move to :</h3></span>';
        str+='<span style="float:right;"><a href="javascript:void(0);" onClick="close_folder_global();">X</a></span></div>';
  
		folder_list = data.flags;
		for(var s in folder_list){
			str+="<a href=\"javascript:wf_flag2(2,'"+folder_list[s].url+"');\" class=\"listfolder\">"+folder_list[s].folder_name+"</a>";
		}
		str+="</div>";
		
		//str+="<a href=\"javascript:wf_flag2(3,'"+data.mark_url+"');\" class=\"icon_workflow\">&nbsp;</a>";
		//str+="<a href=\"javascript:wf_flag2(3,'"+data.analize_url+"');\" class=\"icon_searchw\">&nbsp;</a>";
		//str+="<a href=\"javascript:wf_flag2(3,'"+data.exclude_url+"');\" class=\"icon_stopw\">&nbsp;</a>";
		str+="</div>";
		str+="</td>";
		str+="</tr>";
		str+="</table>";
		str+="</div>  <!-- .list -->";
		if(flag==2){
			str+="</div>";
		}
		return str;
}
function wf_marked_next(){
	if(wf_marked_next_url.length>0){
		wf_load_marked("index.php?"+wf_marked_next_url);
	}
}
function wf_marked_prev(){
	if(wf_marked_prev_url.length>0){
		wf_load_marked("index.php?"+wf_marked_prev_url);
	}
}
function wf_reply_next(){
	if(wf_reply_next_url.length>0){
		wf_load_reply("index.php?"+wf_reply_next_url);
	}
}
function wf_reply_prev(){
	if(wf_reply_prev_url.length>0){
		wf_load_reply("index.php?"+wf_reply_prev_url);
	}
}
function wf_exc_next(){
	if(wf_exc_next_url.length>0){
		wf_exc_reply("index.php?"+wf_exc_next_url);
	}
}
function wf_exc_prev(){
	if(wf_exc_prev_url.length>0){
		wf_exc_reply("index.php?"+wf_exc_prev_url);
	}
}
function wf_analize_next(){
	if(wf_analize_state==0){
		if(wf_analize_next_url.length>0){
			wf_load_analize("index.php?"+wf_analize_next_url);
		}
	}else{
		wf_analize_start++;
		wf_analize_tweets(wf_analize_curr_url);
	}
	document.location="#";
}
function wf_analize_prev(){
	if(wf_analize_state==0){
		if(wf_analize_prev_url.length>0){
			wf_load_analize("index.php?"+wf_analize_prev_url);
		}
	}else{
		wf_analize_start--;
		if(wf_analize_start<0){
			wf_analize_start = 0;
		}
		wf_analize_tweets(wf_analize_curr_url);
	}
	document.location="#";
}
function wf_render_map(data){
		try{
			if (GBrowserIsCompatible()) {
	
				//alert('masuk');
				var map = new GMap2(document.getElementById("wf_map"));
			
				//map.addControl(new GSmallMapControl());
			
				//map.addControl(new GMapTypeControl());
				
				map.setCenter(new GLatLng(-3.000,118.000), 5);
				
				var customUI = map.getDefaultUI();
				customUI.controls.scalecontrol = false;
				map.setUI(customUI);
				
				for(var i in data){
					lat = parseFloat(data[i].coordinate_x);
					lon = parseFloat(data[i].coordinate_y);
					if(lat>0||lon>0){
					
						var marker = new GMarker(new GLatLng(lat,lon));
						map.addOverlay(marker);
					}
				}
				
			}
		}catch(e){
			console.log(e.message);
		}
}
function wf_person_detail(uid){
	//we need to grab the info about user
	var tw_wrap_url = "index.php?page=workflow&act=twitter_wrapper&ajax=1";
	var sUrl = tw_wrap_url+"&screen_name="+uid;
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			//$("#wf_comment_"+no).html("<div style='width:197px;min-height:36px;background-color:white; margin:0 auto; padding:30px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");
		  },
		  success: function( response ) {
			 
			  if(response.id>1){
				 var author_timezone = response.time_zone;
				 var author_about = response.description;
				 var author_location = response.location;
				 var arr_raw = author_location.split(":");
				 var str = "<table class='analyzingtable'><tr><td class='wf_profile_boxrow' valign='top'><span class='label'>About</span></td><td valign='top'><span id='author_about'>:&nbsp;&nbsp;"+author_about+"</span></td>";
				 str+= "<tr><td class='wf_profile_boxrow'><span class='label' valign='top'>Location</span></td><td valign='top'><span id='author_location'>:&nbsp;&nbsp;"+author_location+"</span></td></table>";
				 $("#wf_profile_box").html(str);
				 
				 try{
					 var arr_loc = arr_raw[1].split(",");
					 
					
					 try{
						 if(arr_loc.length>0){
							 var coordinate_x = $.trim(arr_loc[0]);
							 var coordinate_y = $.trim(arr_loc[1]);
							 if(parseFloat(coordinate_x)>0||parseFloat(coordinate_y)>0){
								 
								 get_real_address(coordinate_x,coordinate_y);
							 }
						 }
					 }catch(e){}
				 }catch(e){}
			  }else{
				  
			  }
	}});
}
function get_real_address(x,y){
	var sUrl = gmap_url+"&x="+x+"&y="+y;
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			$("#author_location").html("<img src='images/loader.gif'/>");
		  },
		  success: function( response ) {
			  if(response.status=="OK"){
				  
				  var author_location = response.results[0]['formatted_address'];
				  if(author_location!=null){
					  $("#author_location").html(":&nbsp;&nbsp;"+author_location);
				  }
			  }
	}});
}
function mark_for_reply(id,r){
	var sUrl = 'index.php?req='+r;
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			popup_msg('Mark for Reply',"<img src='images/loader.gif'/> Marking in progress");
		  },
		  success: function( response ) {
			  if(response.status==1){
				  popup_msg_close();
			  }
	}});
}
var exc_id = "";
var exc_kw = "";
var exc_type = 0;
var exc_webType = 0;
function apply_exclude_all(kw,type, webType){
	exc_kw = kw;
	exc_type = type;
	exc_webType = webType;
	popup_msg('Warning','<div align=\'center\' class=\'messagewarning\'>This action will modify the topic\'s data, are you sure?</div>',on_apply_exclude_all,null);
}
function apply_exclude(feed_id,type,webType){
	//alert(feed_id);
	exc_id = feed_id;
	exc_type = type;
	exc_webType = webType;
	popup_msg('Warning','<div align=\'center\' class=\'messagewarning\'>This action will modify the topic\'s data and the changes will be permanent, are you sure?</div>',on_apply_exclude,null);
}
function apply_undo(feed_id){
	//alert(feed_id);
	exc_id = feed_id;
	popup_msg('Warning','<div align=\'center\' class=\'messagewarning\'>This action will modify the topic\'s data, are you sure?</div>',on_apply_undo,null);
}
function on_apply_exclude_all(){
	type = intval(exc_type);
	switch(type){
		case 1:	
			_action="exclude_all";
		break;
		case 2:	
			_action="fb_exclude_all";
		break;
		case 3:	
			_action="site_exclude_all";
		break;
		default:
			_action="apply_exclude";
		break;
	}
	popup_msg_update("Please wait","<img src='images/loader.gif'/> Removal in progress<br/>",null,onExcludeCancel);
	smac_post(smac_api_url,{method:'workflow',action:_action,keyword:exc_kw, type:exc_webType},function(response){
		if(response.status==1){
			popup_msg_update("Completed","Your change will take effect after we regenerate your statistics within 1 hour or more.",onExcludeAllCancel,null);
		}else{
			popup_msg_update("Completed","We're unable to save your change, please try again later !",null,onExcludeCancel);
		}
	});
}
function on_apply_exclude(){
	type = intval(exc_type);
	switch(type){
		case 1:	
			_action="apply_exclude";
		break;
		case 2:	
			_action="fb_apply_exclude";
		break;
		case 3:	
			_action="site_apply_exclude";
		break;
		default:
			_action="apply_exclude";
		break;
	}
	popup_msg_update("Please wait","<img src='images/loader.gif'/> Removal in progress<br/>",null,onExcludeCancel);
	smac_post(smac_api_url,{method:'workflow',action:_action,feed_id:exc_id, type:exc_webType},function(response){
		if(response.status==1){
			$("#btn_apply"+exc_id).hide();
			popup_msg_update("Completed","Your change will take effect after we regenerate your statistics within 1 hour or more.",onExcludeCancel,null);
		}else{
			popup_msg_update("Completed","We're unable to save your change, please try again later !",null,onExcludeCancel);
		}
	});
}
function on_apply_undo(){
	var sUrl = wfApplyUndo+'&feed_id='+exc_id;
	$.ajax({
		  url: sUrl,
		  dataType: 'json',
		  beforeSend: function(){
			
			popup_msg_update("Please wait","<img src='images/loader.gif'/> Retrieval in progress<br/>",null,onUndoCancel);
		  },
		  success: function( response ) {
			  if(response.status==1){
				  $("#btn_apply"+exc_id).show();
				  $("#btn_undo"+exc_id).hide();
				  popup_msg_update("Completed","Your change will take effect after we regenerate your statistics within 1 hour or more.",onUndoFinished,null);
			  }else{
				  popup_msg_update("Completed","We're unable to undo your change, please try again later !",null,onUndoCancel);
			  }
	}});
}
function onUndoFinished(){
	popup_msg_close();
}
function onUndoCancel(){
	//console.log('onUndoCancel');
	popup_msg_close();
}
function onExcludeCancel(){
	//console.log('onExcludeCancel');
	popup_msg_close();
}

function onExcludeAllCancel(){
	//console.log('onExcludeAllCancel');
	popup_msg_close();
	document.location.reload();
}
function onExcludeAllFinished(){
	//console.log('onExcludeAllFinished');
	wf_exc_reply(wf_exc_url);
	popup_msg_close();
}


function wf_custom_next(id){
	if( wf_custom_next_url[id].length>0){
		wf_load_custom(id,"index.php?"+wf_custom_next_url[id]);
	}
}
function wf_custom_prev(id){
	if(wf_custom_prev_url[id].length>0){
		wf_load_custom(id,"index.php?"+wf_custom_prev_url[id]);
	}
}
function exc_check(){
	$.ajax({
	  url: exc_status_url,
	  dataType: 'json',
	  beforeSend: function(){
					
	  },
	  success: function( response ) {
		  if(response.status==1){
			  if(response.total>0){
				  if(!is_exc){
					  is_exc = true;
				  }
			  }else{
				  if(is_exc){
					  //document.location.reload();
					  wf_exc_reply(wf_exc_curr_url);
					  is_exc=false;
				  }
			  }
			  setTimeout(function() {
  					exc_check();
				}, 10000); 
		  }
	  }});
}
function onDeleteFolder(){
	$.ajax({
		  url: rf_url+'&id='+dfid,
		  dataType: 'json',
		  beforeSend: function(){
				popup_msg_update('Deletion in Progress',"<img src='images/loader.gif'/> Please wait..<br/>",null,null);		
		  },
		  success: function( response ) {
			  dfid=null;
			  if(response.status==1){
				  popup_msg_update('Success','The folder has been deleted successfully',null,null);
				  document.location.reload();
			  }else if(response.status==2){
				  popup_msg_update('Failed','We cannot remove these folder. <br/>It need to be emptied before you can delete it !',null,onCancelDeleteFolder);
			  }else{
				  popup_msg_update('Failed','Sorry, we are unable to delete these folder. <br/>Please try again later !',null,onCancelDeleteFolder);
			  }
		  }});
}
function onCancelDeleteFolder(){
	dfid = null;
	popup_msg_close();
}
var dfid = null;
var urlexc = "";
var p_exc = "";
function remove_folder(folder_id){
	
	dfid=folder_id;
	popup_msg('Warning','These folder needs to be empty before you can delete it. Delete now ?',onDeleteFolder,onCancelDeleteFolder);
	return false;
}
function tgl_exc_person(p,url){
	urlexc = url;
	p_exc = p;
	popup_msg("Removing '"+p+"'","Removing these person from reporting will also removing all of his/her tweets permanently. Are you sure ?",onPeopleExclude,onCancelPeopleExclude);
}
function onPeopleExclude(){
	$.ajax({
		  url: "index.php?".concat(urlexc),
		  dataType: 'json',
		  beforeSend: function(){
				popup_msg_update('Removal in Progress',"<img src='images/loader.gif'/> Please wait..<br/>",null,null);		
		  },
		  success: function( response ) {
			  
			 
			  if(response.status==1){
				  //popup_msg_update('Success',p_exc+" has been removed successfully !",null,null);
				  popup_msg_update('Success',"The changes were successfully applied and will take affect momentarily. Topic statistic updates will be visible tomorrow.",null,null);
				  document.location.reload();
			  }else{
				  popup_msg_update('Failed','Sorry, we are unable to remove these person. <br/>Please try again later !',null,onCancelPeopleExclude);
			  }
			  urlexc = "";
			  p_exc = "";
		  }});
}
function onCancelPeopleExclude(){
	 urlexc = "";
	 p_exc = "";
	popup_msg_close();
}