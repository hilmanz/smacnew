//SMAC JAVASCRIPT

var person = '';
var topsy_estimate = {};
var notifications = [];


//IE8 Console.log issue hack
var alertFallback = false;
if (typeof console === "undefined" || typeof console.log === "undefined") {
  console = {};
  if (alertFallback) {
      console.log = function(msg) {
           alert(msg);
      };
  } else {
      console.log = function() {};
  }
}
//end of hack

$(document).ready(function(){
	
	//Prev sentiment tweet popup
	$('#mentions-overtime .paging .prev').click(function(){
		prevProfileTweet();
	});
	
	//Next sentiment tweet popup
	$('#mentions-overtime .paging .next').click(function(){
		nextProfileTweet();
	});

	$('#filter-kol').change(function(){
		window.location.href = $(this).val();
	});

	//tanggal form new/edit campaign
	if( $('input[name="start"]').length > 0){
		$('input[name="start"]').dynDateTime({
			ifFormat:     "%d/%m/%Y",
			onClose: function(cal){
					//alert(cal.date);
					var selDate = new Date(cal.date);
					selDate.setDate(selDate.getDate()); 
					var myDate=new Date();
					myDate.setDate(myDate.getDate() - 1);
					if( parseInt(myDate.getTime()) >= parseInt(selDate.getTime()) ){
						alert('Starting date should be equal or greater than today');
						$('input[name="start"]').val('') ;
					}
					cal.hide();
			}
		});
	}
	if( $('input[name="end"]').length > 0){
		$('input[name="end"]').dynDateTime();
	}
	
	if( $('#reload').length > 0){
		$('#reload').click(function(){
			window.location.href = location.href;
		});
	}
	
	$('#selectLanguage').change(function(){
		window.location.href = this.value;
	});
	
	$("#formSelectCampaign").change(function(){
		
		window.location.href = this.value;
		
	});
	
	//PILIH COUNTRY
	$('#country').change(function(){
		
		var d = new Date();
		
		var rand = d.getTime();
		
		var id = this.value;
		
		$.ajax({
		  url: 'index.php?page=profile&act=getstate&ajax=1&id='+id+'&rand='+rand,
		  dataType: 'json',
		  success: function( data ) {
		  	//alert(data.success);
			if(data.success == 1){
			
				var city = data.data;
				
				if( city ){
					var num = city.length;
					var htm = '';
				
					for(i=0;i<num;i++){
						htm += "<option value='"+city[i].state_id+"'>"+city[i].state_name+"</option>";
					}
				}else{
					var htm = '';
				}
				
				$('#state').fadeOut('slow',function(){
					$(this).html(htm);
					$(this).fadeIn();
				});
				
				getCity('index.php?page=profile&act=getcity&ajax=1&state='+city[0].state_id+'&country='+id+'&rand='+rand);
				
			}else{
				alert('Failed load state');
			}
		  }
		});
		
		return false;
		
	});
	
	//PILIH STATE
	$('#state').change(function(){
		
		var d = new Date();
		
		var rand = d.getTime();
		
		var id = this.value;
		
		var country = $('#country').val();
		
		$.ajax({
		  url: 'index.php?page=profile&act=getcity&ajax=1&state='+id+'&country='+country+'&rand='+rand,
		  dataType: 'json',
		  success: function( data ) {
		  	//alert(data.success);
			if(data.success == 1){
			
				var city = data.data;
				
				if( city ){
					var num = city.length;
					var htm = '';
				
					for(i=0;i<num;i++){
						htm += "<option value='"+city[i].loc_id+"'>"+city[i].city_name+"</option>";
					}
					
					htm += "<option value='0'>Other</option>";
					
				}else{
					var htm = "<option value='0'>Other</option>";
				}
				
				$('#city').fadeOut('slow',function(){
					$(this).html(htm);
					$(this).fadeIn();
				});
				
			}else{
				alert('Failed load city');
			}
		  }
		});
		
		return false;
		
	});
	
	//PILIH CITY
	$('#city').change(function(){
		
		var id = parseInt(this.value);
		
		if(id == 0){
			
			$('#city-other').fadeIn('slow');
		
		}else{
		
			$('#city-other').hide();
		
		}
		
		return false;
		
	});
	
	//PILIH LANGUAGE
	$('#language').change(function(){
		
		var val = this.value;
		
		if(val == 'other'){
			
			$('#lang-other').fadeIn('slow');
		
		}else{
		
			$('#lang-other').hide();
		
		}
		
		return false;
		
	});
	
	$('#btn_mentions').click(function(){
		loadChartPopup(person,'mentions');
	});
	
	$('#btn_impression').click(function(){
		loadChartPopup(person,'impression');
	});	
	
	$('#btn_rt').click(function(){
		loadChartPopupRt(person);
	});
	
});

function messageShow(sid){
	var id=getMessageCookie(sid);
	if (id!=null && id!="")
	{
	}	
	else 
	{
		if( $('#message-top').length > 0 ){
			$('#message-top').show();
		}
	}
}
function notify(){
	notifications = [];
	
	$.ajax({
	  url: notifyURL,
	  dataType: 'json',
	  beforeSend: function(){
					
	  },
	  success: function( response ) {
		  if(response.status==1){
			  var msg ="";
			  try{
				  if(response.link!=null){
					  msg = "<a href='javascript:void(0);' onclick=\"show_process('"+response.link+"')\" style='color:#fff'>"+response.message+"</a>";
				  }else{
					  msg = response.message;
				  }
				  notifications.push(msg+"<br/>");
				  msg = null;
			  }catch(e){}
			  //-->
		  }else{
			  $('#message-top').fadeOut();
		  }
		  if(notifications.length>0){
			splash(notifications);
		  }else{
			  $('#message-top').hide();
		  }
		  setTimeout(function() {
		  		notify();
			}, 60000); 
	  }});
	
	
	
}
function splash(messages){
	var str = "";
	for(var n in messages){
		str+=messages[n]+"<br/>";
	}
	$('#message-top div').html(str);
	$('#message-top').fadeIn();
}
function setMessageCookie(c_id)
{
	value='ok';
	exdays = 1; //di set sehari
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_id + "=" + c_value;
}

function getMessageCookie(c_id)
{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_id)
		{
			return unescape(y);
		}
	}
}

function getCity(url){
		
		$.ajax({
		  url: url,
		  dataType: 'json',
		  success: function( data ) {
		  	//alert(data.success);
			if(data.success == 1){
			
				var city = data.data;
				
				if( city ){
					var num = city.length;
					var htm = '';
				
					for(i=0;i<num;i++){
						htm += "<option value='"+city[i].loc_id+"'>"+city[i].city_name+"</option>";
					}
					
					htm += "<option value='0'>Other</option>";
					
				}else{
					var htm = "<option value='0'>Other</option>";
				}
				
				$('#city').fadeOut('slow',function(){
					$(this).html(htm);
					$(this).fadeIn();
				});
				
			}else{
				alert('Failed load city');
			}
		  }
		});
	
}
function getProfile(url){
	//console.log("get profile");
	var d = new Date();
		
	var rand = d.getTime();
	
	person = url.split('&');
	person = person[person.length -1];
	person = person.split('=');
	person = person[1];
	
	//alert(person);
	
	//'index.php?&rand='+rand+'&'+url
	$("#profile .content-popup .statistik-profile .icon1").html("0<span class='tip2 hov1'>Followers</span>");
	$("#profile .content-popup .statistik-profile .icon2").html("0<span class='tip2 hov2'>Mentions</span>");
	$("#profile .content-popup .statistik-profile .icon3").html("0<span class='tip2 hov3'>Total Impressions</span>");
	$("#profile .content-popup .statistik-profile .icon4").html("0<span class='tip2 hov4'>Retweet Frequency</span>");
	$("#profile .content-popup .statistik-profile .icon5").html("0<span class='tip2 hov5'>Retweeted Impressions</span>");
	$("#profile .content-popup .statistik-profile .icon6").text("");
	$.ajax({
	  url: popupProfileUrl+'&rand='+rand+'&id='+person,
	  dataType: 'json',
	  beforeSend: function(){
		//alert('test');
		//$("#profile .tab_container #favorite-word .content-popup .favorite-word").html("<div style='width:300px; margin:0 auto; padding:100px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");
		//alert('test');
		
		//Tab tweets dikasih loader
		$('#mentions-overtime .paging .prev').hide();
		$('#mentions-overtime .paging .next').hide();
		$("#popupbody").hide();
		//$("#profile content-popup").hide();
		//$("#profile").hide();
		$("#popupload").show();
		$('#exc_button').html('');
		//$("#mentions-overtime .content-popup").html("<div style='width:550px;height:200px;text-align:center;margin-top:100px;'><img src='images/loader-med.gif' /></div>");
		
	  },
	  success: function( data ) {
	  	//alert(data.success);
		if(data.success == 1){
			$("#popupbody").show();
			$("#popupload").hide();
			var dat = data.data;
			
			$("#profile .headpopup h1").text("@"+dat.username+" - "+dat.name);
			$("#profile .content-popup .smallthumb img").attr('src', dat.image);
			$("#profile .content-popup .statistik-profile .icon1").html(dat.follower+"<span class='tip2 hov1'>Followers</span>");
			$("#profile .content-popup .statistik-profile .icon2").html(dat.mention+"<span class='tip2 hov2'>Mentions</span>");
			$("#profile .content-popup .statistik-profile .icon3").html(dat.impressi+"<span class='tip2 hov3'>Total Impressions</span>");
			$("#profile .content-popup .statistik-profile .icon4").html(dat.rt+"<span class='tip2 hov4'>Retweet Frequency</span>");
			$("#profile .content-popup .statistik-profile .icon5").html(dat.rt_impression+"<span class='tip2 hov5'>Retweeted Impressions</span>");
			//$("#profile .content-popup .impact-score h1").text(data.impact_score);
			$("#profile .content-popup .impact-score h1").text(dat.rank);
			
			$("#profile .content-popup .statistik-profile .icon6").text(dat.share_percentage+'%');
			$('#btn_mentions').text(dat.mention);
			$('#authorabout').text(dat.about);
			$('#authorlocation').text(dat.location);
			$('#authortimezone').text(dat.timezone);
			$('#btn_impression').text(dat.impressi);
			$('#btn_rt').text(dat.rt);
			$('#exc_button').html("<a href='#' onclick=\"tgl_exc_person('"+person+"','"+dat.remove_link+"');\" class='tplbtn'>Remove Person</a>");
			
			var word = data.word;
			/*
			var num = word.length;
			var htm = '';
			for(i=0;i<num;i++){
				if(word[i].value <= 12){
					size = 12;
				}else if( word[i].value > 30){
					size= 31; 
				}else{
					size = word[i].value
				}
				
            	htm += '<h1><a class="black" style="font-size:'+size+'px;" href="#">'+word[i].word+'</a></h1>\n';	
            }
             */
            //$("#profile .tab_container #favorite-word .content-popup .favorite-word").html(word);
            
            loadChartPopup(person,'mentions');
			loadChartSentimentOverTime(person);
			//loadChartMentionOverTime(person);
			loadChartTweets(person);
			
			//loading wordcloud
			if(popupProfileWCUrl.length>0){
				n_wc = 1;
				//console.log('loading wordcloud :'+popupProfileWCUrl);
				dashcontent(popupProfileWCUrl+'&id='+person,'profilewc','Loading Wordcloud');
			}
			
		}else{
			alert('Failed load profile');
		}
	  }
	});
	
}
var profileTweetStart = 0;
var profileTweetPerPage = 5;
var profileTweetPerson = '';
function loadChartTweets(person){
	profileTweetStart = 0;
	profileTweetPerson = person;
	getProfileTweet(person,profileTweetStart);
}
function prevProfileTweet(){
	profileTweetStart = profileTweetStart - profileTweetPerPage;
	getProfileTweet(profileTweetPerson,profileTweetStart);
}
function nextProfileTweet(){
	profileTweetStart = profileTweetStart + profileTweetPerPage;
	getProfileTweet(profileTweetPerson,profileTweetStart);
}
function getProfileTweet(person,start){
	var d = new Date();
	var rand = d.getTime();
	
	$.ajax({
		url: popupProfileTweetUrl+'&rand='+rand+'&person='+person+'&start='+start+'&perpage='+profileTweetPerPage,
		dataType: 'json',
		beforeSend: function(){
			$('#mentions-overtime .paging .prev').hide();
			$('#mentions-overtime .paging .next').hide();
			$("#mentions-overtime .content-popup").html("<div style='width:550px;height:200px;text-align:center;margin-top:100px;'><img src='images/loader-med.gif' /></div>");
		},
		success: function( data ) {
		  	if(data.success == 0){
				alert('Failed load tweets');
			}else{
				var raw = data.data;
				//alert(raw[0].author_id);
				rawnum = raw.length;
				if(rawnum > 0){
					var htm = '';
					for(var i=0;i<rawnum;i++){
						htm += '<div class="list">';
						//htm +=				'<div class="smallthumb">';
						//htm +=					'<a href="#"><img src="'+raw[i].author_avatar+'" /></a>';
						//htm +=				'</div>';
						htm +=				'<div class="entry">';
						//htm +=					'<h3>'+raw[i].author_name+'</h3>';
						htm +=					'<span class="date">'+raw[i].published_date+'</span>';
						htm +=					'<span class="txt">'+raw[i].content+'</span>';
						
						if( parseInt(raw[i].sentiment) < 0 ){
							htm +=					'<span class="icon-min">&nbsp;</span>';
						}else if( parseInt(raw[i].sentiment) > 0 ){
							htm +=					'<span class="icon-plus">&nbsp;</span>';
						}
						
						htm +=				'</div>';
						htm +=			'</div>';
					}
					
					if( start > 0){
						$('#mentions-overtime .paging .prev').show();
					}else{
						$('#mentions-overtime .paging .prev').hide();
					}
					
					if( start < data.total && (start + profileTweetPerPage) < data.total){
						$('#mentions-overtime .paging .next').show();
					}else{
						$('#mentions-overtime .paging .next').hide();
					}
					
					$('#mentions-overtime .content-popup').html(htm);
					
				}else{
					$('#mentions-overtime .content-popup').html('');
				}
			}
		}
	});
}

function loadChartSentimentOverTime(person){

	$("#chartPop2").html("<div style='width:550px;height:200px;text-align:center;margin-top:100px;'><img src='images/loader.gif' /></div>");
	
	
	var options = null;
	options = {
		chart: {
			renderTo: 'chartPop2',
			type: 'line'
		},
		credits: {
	        enabled: false
	    },
		title: {
			text: ''
		},
		xAxis: {
			enabled: false,
			categories: [],
			labels: {
				rotation: -45,
				align: 'right'
				
			}
		},
		yAxis: {
			title: {
				text: ''
			},
			style: {
			   color: '#FF0000'
			}
		},
		tooltip: {
	         formatter: function() {
	            return ''+
	               this.series.name +': '+ this.y;
	         }
	    },
		legend: {
			enabled: true
		},
		series: []
	};
	
	
	//'index.php?page=profile&act=chartmentions&ajax=1&person='+person+'&type='+type
	
	// Load the data from the XML file 
	$.get(popupUrlChartSentimentOvertime+'&person='+person, function(xml) {
		
		// Split the lines
		var $xml = $(xml);
		
		// push categories
		$xml.find('categories item').each(function(i, category) {
			options.xAxis.categories.push($(category).text());
		});
		
		// push series
		$xml.find('series').each(function(i, series) {
			var seriesOptions = {
				name: $(series).find('name').text(),
				data: []
			};
			
			// push data points
			$(series).find('data point').each(function(i, point) {
				seriesOptions.data.push(
					parseFloat($(point).text())
				);
			});
			
			// add it to the options
			options.series.push(seriesOptions);
		});
		var chart = null;
		chart = new Highcharts.Chart(options);
	});
}

function loadChartMentionOverTime(person){

	$("#chartPop3").html("<div style='width:550px;height:200px;text-align:center;margin-top:100px;'><img src='images/loader.gif' /></div>");

	var options = null;
	options = {
		chart: {
			renderTo: 'chartPop3',
			type: 'line'
		},
		credits: {
	        enabled: false
	    },
		title: {
			text: ''
		},
		xAxis: {
			enabled: false,
			categories: []
		},
		yAxis: {
			title: {
				text: ''
			},
			style: {
			   color: '#FF0000'
			}
		},
		tooltip: {
	         formatter: function() {
	            return ''+
	               this.series.name +': '+ this.y;
	         }
	    },
		legend: {
			enabled: true
		},
		series: []
	};
	
	
	// Load the data from the XML file 
	$.get(popupUrlChartMentionOvertime+'&person='+person, function(xml) {
		
		// Split the lines
		var $xml = $(xml);
		
		// push categories
		$xml.find('categories item').each(function(i, category) {
			options.xAxis.categories.push($(category).text());
		});
		
		// push series
		$xml.find('series').each(function(i, series) {
			var seriesOptions = {
				name: $(series).find('name').text(),
				data: []
			};
			
			// push data points
			$(series).find('data point').each(function(i, point) {
				seriesOptions.data.push(
					parseInt($(point).text())
				);
			});
			
			// add it to the options
			options.series.push(seriesOptions);
		});
		var chart = null;
		chart = new Highcharts.Chart(options);
	});
}

function loadChartPopup(person,type){

	$("#chartPop").html("<div style='width:550px;height:200px;text-align:center;margin-top:100px;'><img src='images/loader.gif' /></div>");
	
	if(type == 'mentions'){
		var scolor = '#39B54A';
	}else{
		var scolor = '#92278F';
	}
	
	var options = null;
	options = {
		chart: {
			renderTo: 'chartPop',
			type: 'line'
		},
		credits: {
	        enabled: false
	    },
		title: {
			text: ''
		},
		xAxis: {
			enabled: false,
			categories: []
		},
		yAxis: {
			title: {
				text: ''
			},
			style: {
			   color: '#FF0000'
			}
		},
		tooltip: {
	         formatter: function() {
	            return ''+
	               this.series.name +': '+ this.y;
	         }
	    },
		legend: {
			enabled: false
		},
		series: []
	};
	
	
	if(type == 'mentions'){
		var urlx = popupUrlMentions;
	}else{
		var urlx = popupUrlImpressions;
	}
	//'index.php?page=profile&act=chartmentions&ajax=1&person='+person+'&type='+type
	
	// Load the data from the XML file 
	$.get(urlx+'&person='+person, function(xml) {
		
		// Split the lines
		var $xml = $(xml);
		
		// push categories
		$xml.find('categories item').each(function(i, category) {
			options.xAxis.categories.push($(category).text());
		});
		
		// push series
		$xml.find('series').each(function(i, series) {
			var seriesOptions = {
				name: $(series).find('name').text(),
				data: [],
				color: scolor
			};
			
			// push data points
			$(series).find('data point').each(function(i, point) {
				seriesOptions.data.push(
					parseInt($(point).text())
				);
			});
			
			// add it to the options
			options.series.push(seriesOptions);
		});
		var chart = null;
		chart = new Highcharts.Chart(options);
	});
}

function loadChartPopupRt(person){
	
	var options = null;
	options = {
		chart: {
			renderTo: 'chartPop',
			type: 'line'
		},
		credits: {
	        enabled: false
	    },
		title: {
			text: ''
		},
		xAxis: {
			enabled: false,
			categories: []
		},
		yAxis: {
			title: {
				text: ''
			}
		},
		tooltip: {
	         formatter: function() {
	            return ''+
	               this.series.name +': '+ this.y;

	         }
	    },
		legend: {
			enabled: false
		},
		series: []
	};
	
	//'index.php?page=profile&act=chartrt&ajax=1&person='+person
	// Load the data from the XML file 
	$.get(popupUrlRt+'&person='+person, function(xml) {
		
		// Split the lines
		var $xml = $(xml);
		
		// push categories
		$xml.find('categories item').each(function(i, category) {
			options.xAxis.categories.push($(category).text());
		});
		
		// push series
		$xml.find('series').each(function(i, series) {
			var seriesOptions = {
				name: $(series).find('name').text(),
				data: [],
				color: '#00AEF0'
			};
			
			// push data points
			$(series).find('data point').each(function(i, point) {
				seriesOptions.data.push(
					parseInt($(point).text())
				);
			});
			
			// add it to the options
			options.series.push(seriesOptions);
		});
		var chart = null;
		chart = new Highcharts.Chart(options);
	});
}

var sentiment = new Object();
sentiment.cid = 0;
sentiment.edit = function(id){
	sentiment.cancel(sentiment.cid);
	sentiment.cid = id;
	$("#btn-"+id).hide();
	$("#cbtn-"+id).fadeIn();
	$("#edit-"+id).fadeIn();	
	
}
sentiment.cancel = function(id){
	if( $("#btn-"+id).length > 0){
		sentiment.cid = 0;
		$("#btn-"+id).fadeIn();
		$("#cbtn-"+id).hide();
		$("#edit-"+id).hide();	
	}
}
sentiment.save = function(id){
	
		var val = $('#val-'+id).val();
	
		var d = new Date();
		
		var rand = d.getTime();
		
		$.ajax({
		  url: editSentimentUrl+'&id='+id+'&rand='+rand+'&value='+val,
		  dataType: 'json',
		  success: function( data ) {
		  	//alert(data.success);
			if(data.success == 1){
				if(parseInt(val) < 0 ){
					var cat = 'Unfavourable';
				}else if(parseInt(val) > 0){
					var cat = 'Favourable';
				}else{
					var cat = 'Neutral';
				}
				
				//window.location.href = location.href;
				$('#c-'+id).fadeOut('slow',function(){
					$('#c-'+id).text(cat);
					$('#c-'+id).fadeIn('slow');
				});
				$('#w-'+id).fadeOut('slow',function(){
					$('#w-'+id).text(val);
					$('#w-'+id).fadeIn('slow',function(){
						$('#edit-'+id).hide();
						$('#btn-'+id).show();
					});
				});
				splash([en['edit_sentiment_ok']]);
				document.location="#";
			}else{
				splash([en['edit_sentiment_failed']]);
			}
		  }
		});
	
}

function addCommas(str) {
    var amount = new String(str);
    amount = amount.split("").reverse();

    var output = "";
    for ( var i = 0; i <= amount.length-1; i++ ){
        output = amount[i] + output;
        if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = ',' + output;
    }
    return output;
}
function wc_overlap(x,y,top,bottom,left,right,txt,steps){
	var n_placed=0;
	for(var i in txt){
		var tt = txt[i];
		var a_offsetLeft = left;
		var a_offsetTop = top;
		var a_offsetWidth = Math.round(right - left);
		var a_offsetHeight = Math.round(bottom- top);
		
		var b_offsetLeft = parseFloat(tt.left);
		var b_offsetTop = parseFloat(tt.top);
		var b_offsetWidth = Math.round(tt.right - tt.left);
		var b_offsetHeight = Math.round(tt.bottom-tt.top);
		
		
		//alert(i+"-->"+(Math.abs(2*a_offsetLeft+a_offsetWidth-2*b_offsetLeft-b_offsetWidth))+" < "+(a_offsetWidth + b_offsetWidth));
		if(Math.abs(2*a_offsetLeft+a_offsetWidth-2*b_offsetLeft-b_offsetWidth) < a_offsetWidth + b_offsetWidth){
			if (Math.abs(2*a_offsetTop + a_offsetHeight - 2*b_offsetTop - b_offsetHeight) < a_offsetHeight + b_offsetHeight) {
				return true;
			}
		}
		n_placed++;
		//print "#".$n_placed."<br/>";
		if(n_placed==steps){return false;}
	}
	return false;
	
}
var wcqueue = [];
var is_render = false;
var n_wc = 10;
function wordcloud_queue(id){
	wcqueue.push(id);
	//if(!is_render){
		//wordcloud_render(wcqueue.shift());
	//}
	//console.log('hide '+id);
	//console.log(wcqueue.length+' - '+n_wc);
	if(wcqueue.length==n_wc){wordcloud_render(wcqueue.shift());}
}
function wordcloud_render(id){
	//console.log('render wordcloud #'+id);
	is_render=true;
	$("#"+id).show();
	//alert($("#id").children());
	var sp = [];
	var n=0;
	var x = 150;
	var y = 150;
	 $("#"+id).children().each(function(){
		 var c = $(this);
		 var a = c.find('a');
		 var txtwidth = a.width();
		 var txtheight = a.height();
		 var deg = 0.62*Math.random()*100;
		 var radius = 0.1;
		 if(sp.length==0){
			 txt_left = 150;
			 txt_top = 150;
			 txt_right = 150+(a.width());
			 txt_bottom = 150+(a.height());
			 a.css('left',txt_left);
			 a.css('top',txt_top);
			 sp.push({'id':a.attr('id'),'width':txtwidth,'height':txtheight,'top':txt_top,'left':txt_left,'right':txt_right,'bottom':txt_bottom});
		 }else{
			
			 var o = {};
			 if(n>0&&n%2==0){
					ori = 1;
				}else{
					ori = -1;
				}
			 do{
				deg = 0.62*(Math.random()*360) * ori;
				radius+=1;
				xx = x + (radius * Math.cos(deg))/2;
				yy = y + (radius * Math.sin(deg))/2;
				
				txt_top = yy;
				txt_left = xx;
				txt_right = xx+(a.width());
				txt_bottom = yy+(a.height());
				
			 }while(wc_overlap(xx,yy,txt_top,txt_bottom,txt_left,txt_right,sp,n));
			 
			 a.css('left',txt_left);
			 a.css('top',txt_top);
			 //alert(txt_left+","+txt_top+","+txt_right+","+txt_bottom);
			 sp.push({'id':a.attr('id'),'width':a.width,'height':a.height,'top':txt_top,'left':txt_left,'txt':a.html(),'right':txt_right,'bottom':txt_bottom});
			 
		 }
		 n++;
	 });
	 //is_render=false;
	 if(wcqueue.length>0){
		 
		 wordcloud_render(wcqueue.shift());
	 }
	// rearrange(sp);
}
function popup_msg(caption,msg,onOKHandler,onCancelHandler){
	var popID = "popupmsg";
	var popWidth = "500"
	
	//Fade in the Popup and add close button
	$('#' + popID).fadeIn().css({'width': Number( popWidth ) }).prepend('<a href="#" class="close" style=""><img src="images/close.png" style="margin:-27px -23px 0 '+popWidth+'px" title="Close Window" alt="Close"></a>');

	//Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
	//var popMargTop = ($('#' + popID).height() + 400) / 2;
	var popMargLeft = ($('#' + popID).width() + 80) / 2;

	//Apply Margin to Popup
	$('#' + popID).css({
	    //'margin-top' : -popMargTop,
	    'margin-left' : -popMargLeft
	});
	$("#popupmsgbtn1").unbind();
	$("#popupmsgbtn2").unbind();
	if(onOKHandler!=null&&typeof(onOKHandler)==="function"){
		$("#popupmsgbtn1").show();
		$("#popupmsgbtn1").click(function(){
			onOKHandler();
		});
	}else{
		$("#popupmsgbtn1").hide();
	}
	if(onCancelHandler!=null&&typeof(onCancelHandler)==="function"){
		$("#popupmsgbtn2").show();
		$("#popupmsgbtn2").click(function(){
		onCancelHandler();
		});
	}else{
		$("#popupmsgbtn2").hide();
	}
	//Fade in Background
	$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
	$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies
	$("#popupmsgcaption").html(caption);
	$("#popupmsgbody").html(msg);
	
	return false;
}
function popup_msg_update(caption,msg,onOKHandler,onCancelHandler){
	
		var popID = "popupmsg";
		$("#popupmsgbtn1").unbind();
		$("#popupmsgbtn2").unbind();
		
		if(onOKHandler!=null&&typeof(onOKHandler)=="function"){
			$("#popupmsgbtn1").show();
			$("#popupmsgbtn1").click(function(){
				onOKHandler();
				
			});
		}else{
			$("#popupmsgbtn1").unbind('click');
			$("#popupmsgbtn1").hide();
		}
		if(onCancelHandler!=null&&typeof(onCancelHandler)=="function"){
			$("#popupmsgbtn2").show();
			$("#popupmsgbtn2").click(function(){
				onCancelHandler();
				
			});
		}else{
			$("#popupmsgbtn2").unbind('click');
			$("#popupmsgbtn2").hide();
		}
		$("#popupmsgcaption").html(caption);
		$("#popupmsgbody").html(msg);
		
		return false;
	
}
function popup_msg_close(){
	$('#fade , .popup_block').fadeOut(function() {
		$('#fade, a.close').remove();  //fade them both out
	});
	return false;
}
function show_process(uri){
	$.ajax({
		  url: 'index.php?'+uri,
		  dataType: 'json',
		  beforeSend: function(){
				popup_msg('Current Running Jobs',"<img src='images/loader-med.gif' /> Please wait..",null,null);
		  },
		  success: function( response ) {
			  dfid=null;
			  if(response.status==1){
				  popup_msg_update('Success',response.content,null,null);
			  }else{
				  popup_msg_update('Failed','Sorry, we are unable to retrieve information <br/>Please try again later !',
						  			null,null);
			  }
		  }});
}
function change_label(id,label,u){
	//label = escape(label);
	
	var content = "<input type='text' name='editlabel' id='editlabel' value='"+label+"'/>" +
				 "<input type='button' name='save' value='Save' " +
				 "onclick=\"save_label('"+id+"',$('#editlabel').val(),'"+u+"')\"/>";
	popup_msg('Change Label',content,null,null);
}
function save_label(i,l,u){
	$.ajax({
	  url: u,
	  dataType: 'json',
	  data : {id:i,label:l},
	  beforeSend: function(){
		  popup_msg_update('Saving..',"<img src='images/loader-med.gif' /> Please wait..",null,null);
	  },
	  success: function( response ) {
		 
		  if(response.status==1){
			  popup_msg_update('Success',"The label has been updated successfully !",onLabelSaved,null);
		  }else{
			  popup_msg_update('Failed','Sorry, we are unable to retrieve information <br/>Please try again later !',
					  			null,null);
		  }
	  }});
}
function onLabelSaved(){
	document.location.reload();
}
function generate_pdf(){
	var n=0;
	var report_types = "";
	for(var i=1;i<13;i++){
		if($('#t'+i).is(':checked')){
			if(n==1){
				report_types+=",";
			}
			report_types+=""+i;
			n=1;
		}
	}
	var dt_from = $('input[name="dt_from"]').val();
	var dt_to = $('input[name="dt_to"]').val();
	
	var report_label = $("#report_label").val();
	var uri = pdf;
	$.ajax({
		  url: uri,
		  dataType: 'json',
		  data : {types:report_types,label:report_label,from:dt_from,until:dt_to},
		  beforeSend: function(){
			  $('.popup_block').hide();
			  popup_msg('Saving..',"<img src='images/loader-med.gif' /> Please wait..",null,null);
		  },
		  success: function( response ) {
			  if(response.status==1){
				  popup_msg_update('Success',"<div style='height:150px;'>Your report is now being processed, it will take some minutes to complete.</div>",null,null);
				  try{
					  reload_reports();
				  }catch(e){}
			  }else{
				  popup_msg_update('Failed',"<div style='height:150px;'>Sorry, we are unable to send your request<br/>Please try again later !</div>",
						  			null,null);
			  }
		  }});
	return false;
}