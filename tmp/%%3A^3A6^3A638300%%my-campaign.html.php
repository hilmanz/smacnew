<?php /* Smarty version 2.6.13, created on 2012-09-13 14:39:23
         compiled from smac/my-campaign.html */ ?>
<script src="js/charts/highcharts.js" type="text/javascript"></script>

<script type="text/javascript">
var raw = <?php echo $this->_tpl_vars['raw']; ?>
;
//console.log(raw);
var is_new_data = false;
var group_topics = <?php echo $this->_tpl_vars['group_topics']; ?>
;
<?php echo '
var chart;
var first_group = 0;
var datagroup = {};
//alert(raw[0]);
//alert(raw[0].campaign_name);
$(document).ready(function() {
	//loadPiiPie();
	//loadMentionPie();
	//loadImpPie();
	loadListCampaign();
	//createListCompare();
	
	//list compare prev
	$(\'#list-prev\').click(function(){
		if( listCurrentPage > 1){
			$(\'#list-compare-\'+listCurrentPage).hide();
			listCurrentPage = listCurrentPage - 1;
			$(\'#list-compare-\'+listCurrentPage).fadeIn();
			if( listCurrentPage == 1){
				$(this).hide();
			}
			$(\'#list-next\').show();
		}
	});
	
	//list compare next
	$(\'#list-next\').click(function(){
		if( listCurrentPage < listPage){
			$(\'#list-compare-\'+listCurrentPage).hide();
			listCurrentPage = listCurrentPage + 1;
			$(\'#list-compare-\'+listCurrentPage).fadeIn();
			if( listCurrentPage == listPage){
				$(this).hide();
			}
			$(\'#list-prev\').show();
		}
	});
	
	//list compare go
	$(\'form.form-compare\').submit(function(){
		var arr = new Array();
		var idx = 0;
		$("form.form-compare").find(\'input:checkbox\').each(function(){ 
			var checked = $(this).attr(\'checked\'); 
			if(checked){ 
				arr[idx] = $(this).val();
				idx = idx + 1;
			} 
		}); 
		
		var num = arr.length;
		
		if( num < 2 || num > 3 ){
			alert(\'Compare data only 2 or 3 topic!\');
			return false;
		}
		
		//loadPiiPieCompare(arr);
		//loadMentionPieCompare(arr);
		//loadImpPieCompare(arr);
		$(\'#popup-compare\').fadeOut();
		$(\'#fade\').fadeOut();
		return false;
	});
	
});

function new_data(group_id){
	var is_empty = true;
	for(var i in datagroup[group_id]){
		var r = raw[datagroup[group_id][i]];
		//alert(group_id+"->"+r.no_data);
		if(r.mentions!="0" && r.people!="0" && r.potential_impression!="0"){
			is_empty = false;
			break;
		}
	}
	return is_empty;
}
function no_data(group_id){
	var is_no_data = false;
	for(var i in datagroup[group_id]){
		var r = raw[datagroup[group_id][i]];
		
		if(r.no_data!=undefined){
			
			if(r.ts>r.avts){
				is_no_data = true;
				break;
			}
		}
	}
	return is_no_data;
}
function loadListCampaign(){
	var num = raw.length;
	//console.log(\'total : \'+num);
	if(num > 0){
		
		var color = 0;
		var bg = \'ganjils\';
		var htm = \'\';
		var group_id = raw[0].group_id;
		//biar bisa auto collapse
		
		var n_group = 0;
		htm += getGroupHead(raw[0].group_name,group_id,n_group);
		for(var t in raw){
			
			
			if(group_topics[raw[t].group_id]>1){
				if(datagroup[raw[t].group_id]==null){
					datagroup[raw[t].group_id] = [];
				}
				datagroup[raw[t].group_id].push(t);
			}
		}
		for(var i=0; i<num; i++){
			
			if(!is_new_data){
				if(color == 0){
					bg = \'ganjils\';
					color = 1;
				}else{
					bg = \'genaps\';
					color = 0;
				}
				
				if( raw[i].performance.pii_diff < 0 ){
					var pii = \'down\';
					var piiNumber = raw[i].performance.pii_diff;
					var piiPercent = raw[i].performance.pii_change;
				}else{
					var pii = \'up\';
					var piiNumber = raw[i].performance.pii_diff;
					var piiPercent = raw[i].performance.pii_change;
				}
				
				if( raw[i].performance.mention_change < 0 ){
					var men = \'down\';
					var menNumber = raw[i].performance.mention_diff;
					var menPercent = raw[i].performance.mention_change.toFixed(1);
				}else{
					var men = \'up\';
					var menNumber = raw[i].performance.mention_diff;
					var menPercent = raw[i].performance.mention_change.toFixed(1);
				}
				
				if( raw[i].performance.imp_change < 0 ){
					var imp = \'down\';
					var impNumber = raw[i].performance.imp_diff;
					var impPercent = raw[i].performance.imp_change.toFixed(1);
				}else{
					var imp = \'up\';
					var impNumber = raw[i].performance.imp_diff;
					var impPercent = raw[i].performance.imp_change.toFixed(1);
				}
			
				htm += \'<tr class="\'+bg+\'">\';
	            htm += \'<td><h3 class="margin0"><a class="cLink" href="\'+raw[i].campaign_link+\'">\'+raw[i].campaign_name+\'</a></h3><p>\'+raw[i].description+\'</p><div class="control">\';
				if(raw[i].n_status == \'0\'){
					htm += \'<a href="\'+raw[i].link_change+\'" class="run" onclick="javascript:if( !confirm(\\\'Are you sure want to run this topic?\\\') ) return false;">Paused</a>\';
	            }else if(raw[i].n_status == \'1\'){
					htm += \'<a href="\'+raw[i].link_change+\'" class="run pause" onclick="javascript:if (! confirm(\\\'Are you sure want to pause this topic?\\\') ) return false;">Running</a>\';
				}
				if(raw[i].removeable==\'1\'){
					htm += \'<a href="\'+raw[i].link_remove+\'" class="icon_stoptopic" onclick="javascript:if( !confirm(\\\'\'+addslashes(en[\'topic_removal_confirmation\'])+\' \\\') ) return false;">Delete</a></div></td>\';
				}
				htm +=\'</div></td>\';
				if(raw[i].quality==0){
					htm +=  \'<td valign="top" align="center"><h1>N/A</h1>\';
				}else{
					htm +=  \'<td valign="top" align="center"><h1>\'+(raw[i].quality)+\'</h1>\';
				}
	            htm +=                    \'<div class="\'+pii+\' percent_change_entry">\'; 
	            htm +=                       \'<span class="\'+pii+\'">\'+piiNumber+\'</span>\';
	            //htm +=                        \'<span class="percentage">\'+piiPercent+\'%</span>\';
	            htm +=                    \'</div>\';
	            htm +=                \'</td>\';
	            htm +=                \'<td valign="top" align="center">\';
	            
	            htm +=                	\'<h1>\'+addCommas(raw[i].total_mentions)+\'</h1>\';
	            htm +=                    \'<div class="\'+men+\' percent_change_entry"> \';
	            htm +=                       \' <span class="\'+men+\'">\'+menNumber+\'</span>\';
	            if(menPercent<300){
	           	 htm +=                        \'<span class="percentage">\'+menPercent+\'%</span>\';
	            }
	            htm +=                   \'</div>\';
	            htm +=                \'</td>\';
	            htm +=                \'<td valign="top" align="center">\';
	            if(raw[i].potential_impression==0){
	            	htm +=                	\'<h1>N/A</h1>\';
	            }else{
	            	htm +=                	\'<h1>\'+(raw[i].potential_impression)+\'</h1>\';
	            }
	            htm +=                    \'<div  class="\'+imp+\' percent_change_entry"> \';
	            htm +=                       \'<span class="\'+imp+\'">\'+impNumber+\'</span>\';
	            if(impPercent < 300){
	            htm +=                       \'<span class="percentage">\'+impPercent+\'%</span>\';
	            }
	            htm +=                    \'</div>\';
	            htm +=                \'</td>\';
	            htm +=                \'<td align="center" width="70"><div id="small-sentiment-\'+parseInt(raw[i].campaign_id)+\'" style="width:130px;height:130px;margin: -25px 0;"></div></td>\';
	            htm +=                \'<td align="center">\';
	            htm +=                	\'<h1>\'+(raw[i].people)+\'</h1>\';
	            htm +=                \'</td>\';
	            htm +=                \'<td align="center" width="70"><div id="small-channel-\'+raw[i].campaign_id+\'" style="width:130px;height:130px;margin: -25px 0;"></div></td>\';
	            htm +=          \'</tr>\';
			}
            if(raw.length>(i+1)){
				if(group_id!=raw[i+1].group_id){
					if(!is_new_data){
						
						htm += getGroupEnd();
					}
					is_new_data = false;
					group_name = raw[i+1].group_name;
					group_id = raw[i+1].group_id;
					n_group++;
					
					//if(!new_data(group_id)){
						//alert(\'yey\');
						htm += getGroupHead(group_name,group_id,n_group);
					//}else{
						//is_new_data = true;
					//	//htm += new_data_content(raw[i+1].availability_date,raw[i+1].live_track_url);
					//}
				}
            }
		}
		if(!is_new_data){
			htm += getGroupEnd();
		}
		//$(\'#list-topic\').append(htm);
		$(\'#accordion-keyword\').append(htm);
		//alert(group_id+"->"+group_topics[group_id]);
		//if(group_topics[group_id]>1){
			try{
			loadPiiPie();
			loadMentionPie();
			loadImpPie();
			}catch(e){
				//console.log(\'cannot load main charts : \'+e.message);
			}
		//}
		for(var j=0;j < num; j++){
			loadSmallSentiment(\'small-sentiment-\'+raw[j].campaign_id, raw[j].sentiment.positive, raw[j].sentiment.negative, raw[j].sentiment.netral);
			loadSmallChannel(\'small-channel-\'+raw[j].campaign_id, raw[j].source.twitter, raw[j].source.facebook, raw[j].source.blog);
		}
		//scan for new data
		scan_new_data();
		
	}
}

function scan_new_data(){
	for(var i in datagroup){
		if(new_data(i)){
			if(no_data(i)){
				$("#tgroup-"+i).html(no_data_content(raw[datagroup[i][0]].availability_date,raw[datagroup[i][0]].keyword_url));
			}else{
				$("#tgroup-"+i).html(new_data_content(raw[datagroup[i][0]].availability_date,raw[datagroup[i][0]].live_track_url));
			}
		}
	}
	
}
function new_data_content(d,url){
  var str=\'\';
  str+=\'<div id="mytopic-banner">\';
  str+=\'<div class="content">\';
  str+=\'<h1>YOUR FIRST REPORT WILL BE READY <br />\';
    str+=\'ON \'+d+\' </h1>\';
    str+=\'</div>\';
    str+=\'<a class="green-btn2" href="\'+url+\'">WHILE YOU ARE WAITING, CHECKOUT WHAT\\\'S HAPPENING IN REAL-TIME VIA OUR LIVE TRACK</a>\';
    str+=\'</div>\';
  return str;
}
function no_data_content(d,url){
  var str=\'\';
  str+=\'<div id="mytopic-banner">\';
  str+=\'<div class="content">\';
  str+=\'<h1>There is no data in this topic to display<br />\';
    str+=\'</h1>\';
    str+=\'</div>\';
    str+=\'<a class="green-btn2" href="\'+url+\'">CLICK HERE TO CHANGE YOUR KEYWORD</a>\';
    str+=\'</div>\';
  return str;
}
function getGroupHead(group_name,group_id,n_group){
	if(group_name==null){
		if(n_group==0){
			group_name = "Default Group";
		}else{
			group_name = "Default Group";
		}
	}
		
	var str = \'<h2 class="topic-group"><a href="#tgroup-\'+group_id+\'">\'+group_name+\'</a></h2>\';
 	str+=\'<div class="accordion-content" id="tgroup-\'+group_id+\'">\';
 	//chartnya
 	if(group_topics[group_id]>1){
	 	str+=\'<div class="piechart-list">\';
		str+=\'<table width="100%" border="0" cellspacing="0" cellpadding="0">\';
		str+=\'<tr>\';
		str+=\'<td width="33%">\';
		str+=\'<div id="piechart-quality" class="piechartbig" style="background:#f2f2f2;padding:10px 0; margin:0;">\';
		str+=\'<h3>Potential Impact Index</h3>\';
		str+=\'<div id="piipie_\'+group_id+\'" style="width:315px;height:315px;margin: -40px 0;"></div>\';
		str+=\'<p>Potential Impact Index indicates the quality of conversation which includes frequency, sentiment & individual dominance.</p>\';
		str+=\'</div>\';
		str+=\'</td>\';		     
		str+=\'<td width="33%" align="center">\';
		str+=\'<div id="piechart-mentions" class="piechartbig" style="padding:10px 0; margin:0;">\';
		str+=\'<h3>Posts</h3>\';
		str+=\'<div id="mentionpie_\'+group_id+\'" style="width:315px;height:315px;margin: -40px 0;"></div>\';
		str+=\'<p>How many mentions of your keywords occurred in the topic based on a 100% twitter and Facebook Open Graph feed.</p>\';
		str+=\'</div>\';
		str+=\'</td>\';		        
		str+=\'<td width="33%" align="right">\';
		str+=\'<div id="piechart-impressions" class="piechartbig" style="background:#f2f2f2;padding:10px 0; margin:0;">\';
		str+=\'<h3>Potential Impressions</h3>\';
		str+=\'<div id="imppie_\'+group_id+\'" style="width:315px;height:315px;margin: -40px 0;"></div>\';
		str+=\'<p>How many potential impressions or "eyeballs" this topic generated calculating the followers and likes of each of your keywords. </p>\';
		str+=\'</div>\';
		str+=\'</td>\';
		str+=\'</tr>\';
		str+=\'</table>\';
		str+=\'</div>\';
 	}
	//---end of chart
 	
 	str+=\'<table id="mytopic" width="100%" border="0" cellspacing="0" cellpadding="0">\';
	str+=\'<thead class="headTable">\';
 	str+=\'<td width="20%">\';
 	str+=\'<h3 class="margin0">Topic</h3>\';
 	str+=\'</td>\';
 	str+=\'<td width="15%" align="center">\';
 	str+=\'<h3 class="margin0">P.I.I</h3>\';
 	str+=\'</td>\';
 	str+=\'<td width="15%" align="center">\';
 	str+=\'<h3 class="margin0">Posts</h3>\';
	str+=\'</td>\';
	str+=\'<td width="15%" align="center">\';
	str+=\'<h3 class="margin0">Impressions</h3>\';
	str+=\' </td>\';
	str+=\'<td width="100" align="center">\';
	str+=\'<h3 class="margin0">Sentiment</h3>\';
	str+=\' </td>\';
	str+=\'<td width="20%" align="center">\';
	str+=\'<h3 class="margin0">People</h3>\';
	str+=\' </td>\';
	str+=\'<td width="100" align="center">\';
	str+=\'  <h3 class="margin0">Channel</h3>\';
	str+=\' </td>\';
	str+=\'</thead>\';
	str+=\'<tbody id="list-topic">\';
	//alert(str);
	return str;
	
}
function getGroupEnd(){
	var str=\'</tbody>\';
	str+=\'</table>\';
	str+=\'</div>\';
	return str;
}
function loadSmallSentiment(el,p,n,t){
	chart = new Highcharts.Chart({
      chart: {
         renderTo: el,
         plotBackgroundColor: null,
         backgroundColor: null,
         plotBorderWidth: null,
         plotShadow: false
      },
      title: {
         text: \'\'
      },
      tooltip: {
         formatter: function() {
            return \'<b>\'+ (this.point.name == \'+\' ? \'Favourable\' : this.point.name == \'-\' ? \'Unfavourable\' : this.point.name == \'N\' ? \'Neutral\' : null) +\'</b><br> \'+ Highcharts.numberFormat(this.percentage,2) +\' %\';
         }
      },
	  credits: {
				        enabled: false
				    },
      plotOptions: {
         pie: {
            allowPointSelect: false,
            cursor: \'pointer\',
            dataLabels: {
				    	distance: -20,
			               color: \'white\',
			               formatter: function() {
			                   return this.percentage > 5 ? \'<b>\'+ this.point.name +\'</b>\' : null;
			               }
            },
            showInLegend: false
         }
      },
      colors: ["#3fb350", "#dd3e3b", "#878889"],
       series: [{
         type: \'pie\',
         name: \'Browser share\',
         data: [
            [\'+\',   parseInt(p)],
			[\'-\',   parseInt(n)],
			[\'N\',   parseInt(t)]
         ]
      }]
   });
}

function loadSmallChannel(el,t,f,b){
	
	chart = new Highcharts.Chart({
      chart: {
         renderTo: el,
         plotBackgroundColor: null,
         backgroundColor: null,
         plotBorderWidth: null,
         plotShadow: false
      },
      title: {
         text: \'\'
      },
      tooltip: {
         formatter: function() {
            return \'<b>\'+ (this.point.name == \'T\' ? \'Twitter\' : this.point.name == \'F\' ? \'Facebook\' : this.point.name == \'W\' ? \'Web\' : null) +\'</b>: \'+ Highcharts.numberFormat(this.percentage,2) +\' %\';
         }
      },
	  credits: {
				        enabled: false
				    },
      plotOptions: {
         pie: {
            allowPointSelect: false,
            cursor: \'pointer\',
            dataLabels: {
				    	distance: -20,
			               color: \'white\',
			               formatter: function() {
			                   return this.percentage > 5 ? \'<b>\'+ this.point.name +\'</b>\' : null;
			               }
            },
            showInLegend: false
         }
      },
      colors: ["#01bff1", "#0044dd", "#ff4400"],
       series: [{
         type: \'pie\',
         name: \'Browser share\',
         data: [
            [\'T\',   parseInt(t)],
			[\'F\',   parseInt(f)],
			[\'W\',   parseInt(b)]
         ]
      }]
   });
}

function loadPiiPie(){
	for(var s in datagroup){
		var rawdata = datagroup[s];
		var numData = rawdata.length;
		var pieData = new Array();
		for(var i in rawdata){
			var n = rawdata[i];
			
			pieData[i] = [raw[n].campaign_name+\'[:]\'+raw[n].campaign_link+\'[:]\'+raw[n].quality, parseFloat(Math.abs(raw[n].quality))];
		}
		
		chart = new Highcharts.Chart({
		  colors: ["#d7ce0d", "#a63c50", "#3c63cc"],
	      chart: {
	         renderTo: \'piipie_\'+s,
	         plotBackgroundColor: null,
	         backgroundColor: null,
	         plotBorderWidth: null,
	         plotShadow: false
	      },
	      title: {
	         text: \'\'
	      },
	      tooltip: {
	         formatter: function() {
				var nm = this.point.name;
				nm = nm.split(\'[:]\');
	            return \'<b>\'+ nm[0] +\'</b>: \'+ nm[2];
	         }
	      },
		  credits: {
					        enabled: false
					    },
	      plotOptions: {
	         pie: {
	            dataLabels: {
	               enabled: true,
	               distance: -40,
	               color: \'#000\',
	               formatter: function() {
						var nm = this.point.name;
						nm = nm.split(\'[:]\');
	                   return Highcharts.numberFormat(this.percentage,0) > 10 ? \'<b>\'+ nm[0] +\'</b><br>\'+ nm[2] :null;
	               }
	            },
	            showInLegend: false
	         },
			series: {
	            cursor: \'pointer\',
	            events: {
	                click: function(event) {
						var nm = event.point.name;
						nm = nm.split(\'[:]\');
						window.location.href = nm[1];
	                }
	            }
	        }
	      },
	       series: [{
	         type: \'pie\',
	         name: \'Browser share\',
	         data: pieData
	      }]
	   });
	}
}

function loadMentionPie(){
	for(var s in datagroup){
		
		var rawdata = datagroup[s];
		var numData = raw.length;
		var pieData = new Array();
		for(var i in rawdata){
			var n = rawdata[i];
			if(raw[n].n_mentions.length==0){
				raw[n].n_mentions = 0;
			}
			pieData[i] =  [raw[n].campaign_name+\'[:]\'+raw[n].campaign_link,parseFloat(raw[n].n_mentions)];
		}
		
		
		chart = new Highcharts.Chart({
		  colors: ["#d7ce0d", "#a63c50", "#3c63cc"],
	      chart: {
	         renderTo: \'mentionpie_\'+s,
	         plotBackgroundColor: null,
	         backgroundColor: null,
	         plotBorderWidth: null,
	         plotShadow: false
	      },
	      title: {
	         text: \'\'
	      },
	      tooltip: {
	         formatter: function() {
				var nm = this.point.name;
				nm = nm.split(\'[:]\');
	            return \'<b>\'+ nm[0] +\'</b>: \'+ Highcharts.numberFormat(this.percentage,2) +\' %\';
	         }
	      },
		  credits: {
					        enabled: false
					    },
	      plotOptions: {
	         pie: {
					    	dataLabels: {
				               enabled: true,
				               distance: -40,
				               color: \'#000\',
				               formatter: function() {
									var nm = this.point.name;
									nm = nm.split(\'[:]\');
					    			return Highcharts.numberFormat(this.percentage,0) > 5 ? \'<b>\'+ nm[0] +\'</b><br>\'+ Highcharts.numberFormat(this.percentage,0) +\' %\' :null;
				               }
				            },
				            showInLegend: false
	         },
			 series: {
	            cursor: \'pointer\',
	            events: {
	                click: function(event) {
						var nm = event.point.name;
						nm = nm.split(\'[:]\');
						window.location.href = nm[1];
	                }
	            }
	        }
	      },
	       series: [{
	         type: \'pie\',
	         name: \'Browser share\',
	         data: pieData
	      }]
	   });
	}
}

function loadImpPie(){
	for(var s in datagroup){
		var rawdata = datagroup[s];
		var numData = raw.length;
		var pieData = new Array();
		
		for(var i in rawdata){
			var n = rawdata[i];
			if(typeof raw[n].n_potential_impression=="undefined"){
				raw[n].n_potential_impression = 0;
			}
			if(raw[n].n_potential_impression<0){
				raw[n].n_potential_impression = 0;
			}
			try{
				pieData[i] =  [raw[n].campaign_name+\'[:]\'+raw[n].campaign_link,parseFloat(raw[n].n_potential_impression)];
			}catch(e){}
		}
	
		chart = new Highcharts.Chart({
		  colors: ["#d7ce0d", "#a63c50", "#3c63cc"],
	      chart: {
	         renderTo: \'imppie_\'+s,
	         plotBackgroundColor: null,
	         backgroundColor: null,
	         plotBorderWidth: null,
	         plotShadow: false
	      },
	      title: {
	         text: \'\'
	      },
	      tooltip: {
	         formatter: function() {
				var nm = this.point.name;
				nm = nm.split(\'[:]\');
	            return \'<b>\'+ nm[0] +\'</b>: \'+ Highcharts.numberFormat(this.percentage,2) +\' %\';
	         }
	      },
		  credits: {
					        enabled: false
					    },
	      plotOptions: {
	         pie: {
					    	dataLabels: {
				               enabled: true,
				               distance: -40,
				               color: \'#000\',
				               formatter: function() {
									var nm = this.point.name;
									nm = nm.split(\'[:]\');
					    			return Highcharts.numberFormat(this.percentage,0) > 5 ? \'<b>\'+ nm[0] +\'</b><br>\'+ Highcharts.numberFormat(this.percentage,0) +\' %\' :null;
				               }
				            },
				            showInLegend: false
	         },
			 series: {
	            cursor: \'pointer\',
	            events: {
	                click: function(event) {
						var nm = event.point.name;
						nm = nm.split(\'[:]\');
						window.location.href = nm[1];
	                }
	            }
	        }
	      },
	       series: [{
	         type: \'pie\',
	         name: \'Browser share\',
	         data: pieData
	      }]
	   });
	}
}

var minimalList = 3;
var listPage = 0;
var listCurrentPage = 1;
var listPerPage = 5;
function createListCompare(){
	var num = raw.length;
	
	if( num > minimalList ){
		var htm = \'\';
		for(var i=0; i < num; i++){
			if( (i % listPerPage) == 0){
				listPage++;
				if(i != 0){
					htm += "</table></div>";
				}
				if( i == 0){
					htm += "<div id=\'list-compare-"+listPage+"\'><table border=\'0\' cellspacing=\'0\' cellpadding=\'0\' class=\'zebra\' width=\'100%\'>";
				}else{
					htm += "<div id=\'list-compare-"+listPage+"\' style=\'display:none;\'><table border=\'0\' cellspacing=\'0\' cellpadding=\'0\' class=\'zebra\' width=\'100%\'>";
				}
			}
							
			htm += "<tr>";
			htm += "<td width=\'20\'><input type=\'checkbox\' name=\'campaign-"+i+"\' value=\'"+i+"\' /></td>";
			htm += "<td><label>" + raw[i].campaign_name + "</label></td>";
			htm += "</tr>";
							
		}
		
		$(\'#list-prev\').hide();
		
		if( listPage > 1){
			$(\'#list-next\').show();
		}else{
			$(\'#list-next\').hide();
		}
		
		$(\'#list-compare\').html(htm);
		
	}else{
		if($(\'btn-compare\').length > 0){
			$(\'btn-compare\').hide();
		}
	}
	
}

function loadPiiPieCompare(arr){
	try{
		var numData = arr.length;
		var pieData = new Array();
		var idx = 0;
		for(i=0;i < numData;i++){
			pieData[idx] = [raw[arr[i]].campaign_name+\'[:]\'+raw[arr[i]].campaign_link, (raw[arr[i]].quality)];
			idx = idx + 1;
		}
		
		chart = new Highcharts.Chart({
		  colors: ["#d7ce0d", "#a63c50", "#3c63cc"],
	      chart: {
	         renderTo: \'piipie\',
	         plotBackgroundColor: null,
	         backgroundColor: null,
	         plotBorderWidth: null,
	         plotShadow: false
	      },
	      title: {
	         text: \'\'
	      },
	      tooltip: {
	         formatter: function() {
				var nm = this.point.name;
				nm = nm.split(\'[:]\');
	            return \'<b>\'+ nm[0] +\'</b>: \'+ this.y;
	         }
	      },
		  credits: {
					        enabled: false
					    },
	      plotOptions: {
	         pie: {
	            dataLabels: {
	               enabled: true,
	               distance: -40,
	               color: \'white\',
	               formatter: function() {
						var nm = this.point.name;
						nm = nm.split(\'[:]\');
	                   return Highcharts.numberFormat(this.percentage,0) > 10 ? \'<b>\'+ nm[0] +\'</b><br>\'+ this.y :null;
	               }
	            },
	            showInLegend: false
	         },
			series: {
	            cursor: \'pointer\',
	            events: {
	                click: function(event) {
						var nm = event.point.name;
						nm = nm.split(\'[:]\');
						window.location.href = nm[1];
	                }
	            }
	        }
	      },
	       series: [{
	         type: \'pie\',
	         name: \'Browser share\',
	         data: pieData
	      }]
	   });
	}catch(e){
		//console.log(\'no data\');
	}
}

function loadMentionPieCompare(arr){
	
	var numData = arr.length;
	var pieData = new Array();
	var idx = 0;
	for(i=0;i < numData;i++){
		pieData[idx] =  [raw[arr[i]].campaign_name+\'[:]\'+raw[arr[i]].campaign_link,parseFloat(raw[arr[i]].n_mentions)];
		idx = idx + 1;
	}
	
	chart = new Highcharts.Chart({
	  colors: ["#d7ce0d", "#a63c50", "#3c63cc"],
      chart: {
         renderTo: \'mentionpie\',
         plotBackgroundColor: null,
         backgroundColor: null,
         plotBorderWidth: null,
         plotShadow: false
      },
      title: {
         text: \'\'
      },
      tooltip: {
         formatter: function() {
			var nm = this.point.name;
			nm = nm.split(\'[:]\');
            return \'<b>\'+ nm[0] +\'</b>: \'+ Highcharts.numberFormat(this.percentage,2) +\' %\';
         }
      },
	  credits: {
				        enabled: false
				    },
      plotOptions: {
         pie: {
				    	dataLabels: {
			               enabled: true,
			               distance: -40,
			               color: \'white\',
			               formatter: function() {
								var nm = this.point.name;
								nm = nm.split(\'[:]\');
				    			return Highcharts.numberFormat(this.percentage,0) > 5 ? \'<b>\'+ nm[0] +\'</b><br>\'+ Highcharts.numberFormat(this.percentage,0) +\' %\' :null;
			               }
			            },
			            showInLegend: false
         },
		 series: {
            cursor: \'pointer\',
            events: {
                click: function(event) {
					var nm = event.point.name;
					nm = nm.split(\'[:]\');
					window.location.href = nm[1];
                }
            }
        }
      },
       series: [{
         type: \'pie\',
         name: \'Browser share\',
         data: pieData
      }]
   });
}


function loadImpPieCompare(arr){
	
	var numData = arr.length;
	var pieData = new Array();
	var idx = 0;
	for(i=0;i < numData;i++){
		pieData[idx] =  [raw[arr[i]].campaign_name+\'[:]\'+raw[arr[i]].campaign_link, parseFloat(raw[arr[i]].n_potential_impression)];
		idx = idx + 1;
	}
	
	chart = new Highcharts.Chart({
	  colors: ["#d7ce0d", "#a63c50", "#3c63cc"],
      chart: {
         renderTo: \'imppie\',
         plotBackgroundColor: null,
         backgroundColor: null,
         plotBorderWidth: null,
         plotShadow: false
      },
      title: {
         text: \'\'
      },
      tooltip: {
         formatter: function() {
			var nm = this.point.name;
			nm = nm.split(\'[:]\');
            return \'<b>\'+ nm[0] +\'</b>: \'+ Highcharts.numberFormat(this.percentage,2) +\' %\';
         }
      },
	  credits: {
				        enabled: false
				    },
      plotOptions: {
         pie: {
				    	dataLabels: {
			               enabled: true,
			               distance: -40,
			               color: \'white\',
			               formatter: function() {
								var nm = this.point.name;
								nm = nm.split(\'[:]\');
				    			return Highcharts.numberFormat(this.percentage,0) > 5 ? \'<b>\'+ nm[0] +\'</b><br>\'+ Highcharts.numberFormat(this.percentage,0) +\' %\' :null;
			               }
			            },
			            showInLegend: false
         },
		 series: {
            cursor: \'pointer\',
            events: {
                click: function(event) {
					var nm = event.point.name;
					nm = nm.split(\'[:]\');
					window.location.href = nm[1];
                }
            }
        }
      },
       series: [{
         type: \'pie\',
         name: \'Browser share\',
         data: pieData
      }]
   });
}
	
'; ?>

</script>
<div id="main-container">
<?php if ($this->_tpl_vars['raw_num'] == 0): ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_first_topic.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
    	<?php echo $this->_tpl_vars['sidebar']; ?>

        <div id="container"> 
            <div class="title-bar padR15">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            	<tr>
                	<td align="left">
            			<h1>
                        	<a href="javascript:void(0);">My Topics</a>
                        </h1>
                    </td>
                    <td align="right">
						<?php if ($this->_tpl_vars['raw_num'] > 3): ?>
												<?php endif; ?>
					</td>
                </tr>
            </table>
            </div>
			
			<?php if ($this->_tpl_vars['first_data']): ?>
            <div id="notAvailable">
                    <div class="blankText">
                        <h1>You don't have any report yet.</h1>
                        <p>Create your first Topic now.<br />
                        Simply click <strong>"New Topic"</strong> button<br />
                        on the sidebar.</p>
                        <img src="images/samplestarted.jpg" />
                    </div>
                    <div class="screenCap">
                        <img src="images/blank_mytopic.gif" />
                    </div>
            </div>
            <?php elseif ($this->_tpl_vars['no_data_available']): ?>
            <div id="mytopic-banner">
            	<div class="content">
            	<h1>There's no data in this topic to display</h1>
                </div>
                            </div>
			<?php endif; ?>
            
			
			
            <div id="campaign" class="pad1015" <?php if ($this->_tpl_vars['empty_data'] == 1): ?>style="display:none;"<?php endif; ?>>
                 <div id="accordion-keyword">
                 </div><!-- #accordion-keyword -->
                <?php echo '
                <script type="text/javascript" >
				jQuery(document).ready(function() {
					 $(\'.topic-group a\').click(function() {
                          var targetID = jQuery(this).attr(\'href\');
                           $(".topic-group a").removeClass("active");
                           $(".topic-group a").addClass("nonActive");
                           $(".accordion-content").css({\'display\' : \'none\'});
                           $(this).addClass("active");
                           $(this).removeClass("nonActive");
						   $(targetID).toggle(500);
					 });
				});
                </script>
                '; ?>

           	</div>
        </div><!-- #container -->
    </div><!-- #main-container -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_compare.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>