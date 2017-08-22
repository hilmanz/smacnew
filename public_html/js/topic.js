var mainrules_count=0;
var mainrules_cap = 5;
var is_advance = false;
var new_tags = {};
var ch_id = "";
var topic_data = {channels:[],
					geo:[],
					additional_sites:[],
					additional_site_fields:1,
					lang:"all",
					topic_name:"",
					topic_group:0,
					topic_start:"",
					topic_desc:"",
					twitter_acc:"",
					max_clauses:10,
					fb_acc:""};
var new_tags = {};
var ch_id = "";
function onAddTag(tag) {
	//alert("Added a tag: " + tag);
}
function onRemoveTag(tag) {
	//alert("Removed a tag: " + tag);
	var str = "";
	var new_str = "";
	if(ch_id.length>0){
		str = $('#'+ch_id).val();
		
		var a = str.split(',');
		
		for(var i in a){
			if(a[i]!=tag){
				if(i>0){
					new_str+=",";
				}
				new_str+=a[i].toString();
			}
		}
		$('#'+ch_id).val(new_str.toString());
		
	}
	remove_tr();
	refresh_tooltip();
}
function onRemoveTag2(tag) {
	//alert("Removed a tag: " + tag);
	var str = "";
	var new_str = "";
	if(ch_id.length>0){
		str = $('#'+ch_id).val();
		
		var a = str.split(',');
		
		for(var i in a){
			if(a[i]!=tag){
				if(i>0){
					new_str+=",";
				}
				new_str+=a[i].toString();
			}
		}
		$('#'+ch_id).val(new_str.toString());
		
	}
	remove_tr2();
	//refresh_tooltip();
}
function refresh_tooltip(){
	
	for(var i=0;i<n_rules;i++){
		try{
			var ss = $('#brand').val()+"|"+$('#ruleall'+i).val()+"|"+$('#ruleany'+i).val()+"|"+$('#ruleexc'+i).val()
			
			var ruleset = topic_rules_str(ss);
			$("#ruletip"+i).html(ruleset);
		}catch(e){}
	}
}
function remove_rule(id){
	$('#ruleall'+id).val('');
	remove_tr();
}
function remove_rule2(id){
	$('#ruleall2'+id).val('');
	remove_tr2();
}
function edit_rule(id,label){
	rules = get_rule_data();
	
	$("#label").val(label);
	if($('#ruleall'+id).val().length>0){
		$("#keyword1").val($('#ruleall'+id).val());
	}
	if($('#ruleany'+id).val().length>0){
		$("#keyword2").val($('#ruleany'+id).val());
	}
	if($('#ruleexc'+id).val().length>0){
		$("#keyword3").val($('#ruleexc'+id).val());
	}
	
	$('#ruleall'+id).val('');
	remove_tr();
}
function remove_tr(){
	for(var i=0;i<n_rules;i++){
		try{
			if($('#ruleall'+i).val().length==0){
				$('#t_ruleall'+i).remove();
				$("#foot"+i).remove();
			}
		}catch(e){}
	}
	refresh_no();
}
function remove_tr2(){
	console.log('remove_tr2');
	for(var i=0;i<n_rules;i++){
		try{
			if($('#ruleall2'+i).val().length==0){
				$('#t_ruleall2'+i).remove();
				$("#foot2"+i).remove();
				n_rules_added--;
				if(n_rules_added<0){ n_rules_added = 0; }
			}
		}catch(e){}
	}
	//refresh_no();
}
function get_rule_data(){
	var ruledata = [];
	for(var i=0;i<n_rules;i++){
		try{
			if($('#ruleall'+i).val().length>0){
				var rd = [];
				try{
					rd.push($('#ruleall'+i).val());
					rd.push($('#ruleany'+i).val());
					rd.push($('#ruleexc'+i).val());
				}catch(e){}
				if(rd.length>0){
					ruledata.push(rd);
				}
			}
		}catch(e){}
	}
	return ruledata;
}
function rule_summary(){
	var ruledata = [];
	for(var i=0;i<n_rules;i++){
		try{
			if($('#ruleall'+i).val().length>0){
				var rd = [];
				try{
					rd.push($('#ruleall'+i).val());
				}catch(e){}
				if(rd.length>0){
					ruledata.push(rd);
				}
			}
		}catch(e){}
	}
	return ruledata;
}
function refresh_no(){
	var n=1;
	n_rules_added = 0;
	for(var i=0;i<n_rules;i++){
		try{
			if($('#ruleall'+i).val().length>0){
				$('#no_'+i).html(n.toString());
				n_rules_added++;
				n++;
			}
		}catch(e){}

		
	}
	reactivate_tooltip();
}
function onChangeTag(input,tag) {
	//alert(input);
	//alert("Changed a tag: " + tag);
	//var id = input.id;
	ch_id = input.id;
}
function splitRules(s){
	var pattern = /".+?"/gi;
	var re = new RegExp(pattern);
	var rs = s.match(re);
	if(rs!=null){
		$.each(rs,function(i,v){
	     	var str = v.replace(/\ /g,"_");
	    	s = s.replace(v,str);
		});
	}
	s = s.replace(/\ /g,",");
	var chunks = s.split(/,/g);
	var product = [];
	for(var i in chunks){
		if(chunks[i].length>0){
			product.push(chunks[i]);
		}
	}
	chunks = null;
	return product;
}
function rule_sequences(s){
	var pattern = /".+?"/gi;
	var re = new RegExp(pattern);
	var rs = s.match(re);
	if(rs!=null){
		$.each(rs,function(i,v){
	     	var str = v.replace(/\ /g,"_");
	    	s = s.replace(v,str);
		});
	}
	s = s.replace(/\ /g,",");
	s = s.replace(/_/g," ");
	
	return s;
}
function clauses_count(s){
	var chunks = s.split('|');
	var n_clauses = 0;
	for(var i=0;i<(chunks.length-1);i++){
	 if(chunks[i].length>0){
	 	try{
	   		n_clauses+=splitRules(chunks[i]).length;
	  	}catch(e){}
	 }
	}
	return n_clauses;
}
function topic_check_keywords(p1,p2,p3,p4,p5){
	//topic_clean_string('#'+p1);
	topic_clean_string('#'+p2);
	topic_clean_string('#'+p3);
	topic_clean_string('#'+p4);
	topic_clean_string('#'+p5);

	var brand = '';
	//var brand = $('#'+p1).val();
	var all = $('#'+p2).val();
	var any = $('#'+p3).val();
	var exclude = $('#'+p4).val();
	var lang = $('#'+p5).val();
	var str = brand+"|"+all+"|"+any+"|"+exclude+"|"+lang;
	
	return str;
}
function to_topsy_query(p1,p2,p3,p4,p5){
	var str = topic_check_keywords(null,'keyword1','keyword2','keyword3','lang');
	var parts = str.split("|");
	var strTopsy = parts[1];
	if(parts[2].length>0){
	    strTopsy+=" OR "+parts[2];
	}
	return strTopsy;
}
function topic_clean_string(id){

	$(id).val($(id).val().replace('|',''));
	$(id).val($(id).val().replace('!',''));
	$(id).val($(id).val().replace('$',''));
	$(id).val($(id).val().replace('%',''));
	$(id).val($(id).val().replace('^',''));
	$(id).val($(id).val().replace('(',''));
	$(id).val($(id).val().replace(')',''));
	$(id).val($(id).val().replace('{',''));
	$(id).val($(id).val().replace('}',''));
	$(id).val($(id).val().replace('+',' '));
	$(id).val($(id).val().replace('-',' '));
	$(id).val($(id).val().replace('\\',''));
	$(id).val($(id).val().replace('\/',''));
}


function topic_rules_str(rule,label){
	var n_count = topic_count();
	if(n_count>=0){
		var a = rule.split('|');
		a[1] = rule_sequences(a[1]);
		a[2] = rule_sequences(a[2]);
		a[3] = rule_sequences(a[3]);
		var str="";		
		var str2 = "";
		var mainkeyword = "";
		
		//if(a[0].length>0){
		//	str2+=a[0];
		//	mainkeyword+=a[0];
		//}
		if(a[1].length>0){
			str2+=" "+a[1].replace(',',' ');
			mainkeyword +=" "+a[1].replace(',',' ');
		}
		if(a[2].length>0){
			
			var ORsets = a[2].split(',');
			//str+=" OR("+a[2]+")";
			for(var i in ORsets){
				if(i>0){
					str2+=mainkeyword;
				}
				str2+=" "+ORsets[i].trim();
				if(i<(ORsets.length-1)){
					str2+=" or ";
				}
			}
		}
		if(a[3].length>0){
			str2+=" without "+implode(' and ',a[3].split(','))+"";
		}
		
		str = "Your specific rule for "+label+" will contain words: "+str2+" in post.";
		mainrules_count++;
		return str;
	}
}
function formated_rule_str(){
	var n_count = topic_count();
	var str_rules = "";
	for(var i=0;i<n_rules;i++){
		try{
			if($('#ruleall'+i).val().length>0){
				var rule = '|'+$('#ruleall'+i).val()+"|"+$('#ruleany'+i).val()+"|"+$('#ruleexc'+i).val();
				var a = rule.split('|');
				var str="";		
				var str2="";
				var mainkeyword = "";
				
				if(a[1].length>0){
					str2+=" "+a[1].replace(',',' ');
					mainkeyword +=" "+a[1].replace(',',' ');
				
				
					if(a[2].length>0){
						var ORsets = a[2].split(',');
						//str+=" OR("+a[2]+")";
						try{
							if(ORsets.length>1){
								//alert(ORsets);
								for(var ii in ORsets){
									if(ii>0){
										str2+=mainkeyword;
									}
									str2+=" "+ORsets[ii].trim();
									if(ii<(ORsets.length-1)){
										str2+=" OR ";
									}
								}
							}else if(a[2].length>0){
								str2+=" "+a[2];
							}
						}catch(e){
							str2+=" "+ORsets[0];
						}
					}
					
					if(a[3].length>0){
						str2+=" (Exclude : "+a[3]+")";
					}
					
					//str = "<strong>we will retrieve any conversation which contains the following words :</strong><br/>"+str2+"";
					if(i>0){
						str_rules+="<br/>";
					}
					str_rules+=str2;
				}
			}
		}catch(e){
			//skip
		}
	}
	
	return str_rules;
}
function formated_rule_str2(){
	var n_count = topic_count();
	var str_rules = "";
	for(var i=0;i<n_rules;i++){
		try{
			if($('#ruleall2'+i).val().length>0){
				str_rules+=$('#ruleall2'+i).val()+'<br/>';
			}
		}catch(e){
			//skip
		}
	}
	return str_rules;
}
function topic_count(){
	var n=0;
	for(var i=0;i<mainrules_count;i++){
		try{
			if($("#mainrules"+i).val().length>0){
				n++;
			}
		}catch(e){}
	}
	if(n<mainrules_cap){
		return n;
	}else{
		return -1;
	}
}
function topic_delete_rule(id){
	$("#ruleset"+id).hide();
	$("#mainrules"+id).val('');
}
function wordcloud_reload(kw){
	
	$.ajax({
		  url: relatedKeywordUrl+'&kw='+kw+"&lang="+$("#lang").val(),
		  dataType: 'html',
		  beforeSend:function(){
				$("#wordcloud").html("<div class='loaders'><p>Loading Suggestions</p><img src='images/ajax-loader.gif' /></div>");
			},
		  success: function( data ) {
			if(data){
				if(data!=""){
					$('#wordcloud').html(data);
					$('#wordcloud-box').css({'min-height' : '300px'});
				}else{
					$('#wordcloud').html("<span>Related keywords not found</span>");
				}
			}else{
				$('#wordcloud').html("<span>Related keywords not found</span>");
			}
		  }
		});
	
}
function get_estimate(rules){
	var estimates = {'w':0,'h':0,'m':0,'a':0,'d':0};
	//"response":{"w":275721,"h":1848,"a":4646804,"d":38711,"m":1132403}
	for(var i in rules){
		var kw = rules[i];
		$.ajax({
			  url: urlestm+'&kw='+kw,
			  dataType: 'json',
			  success: function( data ) {
				
				if(parseInt(data.response.w)>0){
					estimates.w+=parseInt(data.response.w);
				}
				if(parseInt(data.response.h)>0){
					estimates.h+=parseInt(data.response.h);
				}
				if(parseInt(data.response.m)>0){
					estimates.m+=parseInt(data.response.m);
				}
				if(parseInt(data.response.a)>0){
					estimates.a+=parseInt(data.response.a);
				}
				if(parseInt(data.response.d)>0){
					estimates.d+=parseInt(data.response.d);
				}
			}
		});
	}
	topsy_estimate =  estimates;
}
function get_topsy_rules(){
	rules = get_rule_data();
	var topsy_rules = [];
	for(var i in rules){
		var all = rules[i][0];
		var any = rules[i][1];
		var exc = rules[i][2];
		var str = "";
		if(all.length>0){
			str+=all;
		}
		if(any.length>0){
			var a = any.split(',');
			for(var m in a){
				if(m>0){
					str+=",";
				}
				str+=" OR "+a[m];
			}
		}
		if(exc.length>0){
			var b = exc.split(',');
			if(b.length>0){
				str+=' -';
			}
			for(var m in b){
				if(m>0){
					str+=",";
				}
				str+=""+b[m];
			}
		}
		topsy_rules.push(str);
	}
	return topsy_rules;
}
function compare_rule_table(id,topic,rule,n){
	n_compare_rule++;
	//var id = Math.round(Math.random()*999999);
	var str = "<div class='comparetopicgroup' id='cmp"+id+"'><h4>"+topic+" "+rule+"</h4><span class='comparetopicdelete'><a href='#' onclick=\"remove_cmp_rules('cmp"+id+"')\">Remove</a></span>";
	str+="<table width=\"100%\" id=\"tblcompare"+n+"\" class=\"roundgreen-table\"><thead><tr><th width=\"20\">No</th> <th>All of these</th><th>Any of these</th><th>Exclude these</th><th>Info</th>";
    str+="</tr></thead></table></div>";
    return str;
}
function remove_cmp_rules(id){
	n_compare_rule--;
	$('#'+id).remove();
}
function cost_table(){
	var user_topics = [];
	user_topics.push($("input[name='name']").val());
	var cmp = $("#comparetopic").val().split(',');
	for(var i in cmp){
		if(cmp[i].length>0){
			user_topics.push(cmp[i]);
		}
	}
	//costs
	var cost = campaign.cost;
	var credit = 1;
	campaign.orders = [{name:'topic',cost:campaign.cost,real_cost:campaign.cost}];
	if(atype<1){
		cost=0;
		credit=0;
	}
	//-->
	var str= "";
	for(var j in user_topics){
		str+="<tr>";
		str+="<td align='left' style='text-align:left;'>Standard Topic<br/>5 Rules, 100K Posts<br/>("+user_topics[j]+")</td>";
		str+="<td align='right'>"+cost+"</td>";
		str+="<td align='right'><input type='checkbox' checked='checked' disabled='true'/></td>";
		str+="</tr>";
	}
	if(campaign.account_type!=0&&campaign.account_type!=99){
		$.each(campaign.addons,function(k,v){
			campaign.orders.push({name:'addon'+v.id,cost:0,real_cost:v.price});
			str+="<tr>"+
				"<td align='left' style='text-align:left;'>"+v.name+"</td>"+
				"<td align='right'>"+v.price+"</td>"+
				"<td align='right'><input type='checkbox' id='addon"+v.id+"' name='addon[]' value='"+v.id+"' onchange='recalculate_cost($(this))' class='addon'/></td>"+
				"</tr>";
		});
	}
	$('#total_cost').html(addCommas(user_topics.length*cost));
	$('#total_credit').html(addCommas(user_topics.length*credit));
	$('#total_cost2').html(addCommas(user_topics.length*cost));
	$('#total_credit2').html(addCommas(user_topics.length*credit));
	return str;
}
function recalculate_cost(o){
	if(o.is(':checked')){
		$.each(campaign.orders,function(k,v){
			if(v.name=="addon"+o.val()){
				campaign.orders[k].cost=campaign.orders[k].real_cost;
				return false;
			}
		});
	}else{
		$.each(campaign.orders,function(k,v){
			if(v.name=="addon"+o.val()){
				campaign.orders[k].cost=0;
				return false;
			}
		});
	}
	//special for addon id 1,2,3 user may choose only 1 addon.
	if(o.val()=="1"){
		$.each(campaign.orders,function(k,v){
			if(v.name=="addon2"||v.name=="addon3"){
				campaign.orders[k].cost=0;
			}
		});
		$("#addon2").attr('checked','');
		$("#addon3").attr('checked','');
	}
	if(o.val()=="2"){
		$.each(campaign.orders,function(k,v){
			if(v.name=="addon1"||v.name=="addon3"){
				campaign.orders[k].cost=0;
			}
		});
		$("#addon1").attr('checked','');
		$("#addon3").attr('checked','');
	}
	if(o.val()=="3"){
		$.each(campaign.orders,function(k,v){
			if(v.name=="addon1"||v.name=="addon2"){
				campaign.orders[k].cost=0;
			}
		});
		$("#addon1").attr('checked','');
		$("#addon2").attr('checked','');
	}
	var total_cost = 0;
	//update the display
	$.each(campaign.orders,function(k,v){
		total_cost+=intval(v.cost);
	});
	$('#total_cost2').html(addCommas(total_cost));
}
function getCreateGroupPopup(){
	$.ajax({
	  url: "index.php?req=ujh1awLojcrr5vHkyxXELen1ze90tY-oA862WlDKeyQddjk2No-0MQ..",
	  dataType: 'json',
	  beforeSend: function(){
		$("#pp_group_type").html('');
		//$("#popup-unmark .content-popup .contentdivs").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
	  },
	  success: function( response ) {
		  if(response.length>0){
			  for(var i in response){
				  $("#pp_group_type").append("<option value='"+response[i].id+"'>"+response[i].name+"</value>");
			  }
		  }
	  }});
}
function topic_create_group(){
	var group_name = $("#pp_group_name").val();
	var group_type = $("#pp_group_type").val();
	$.ajax({
		  url: "index.php?req=ujh1awLojcrr5vHkyxXELTnsNtvD5JMKvwrC1OiQu3SleTtndkPneg..&group_name="+group_name+"&group_type="+group_type,
		  dataType: 'json',
		  beforeSend: function(){
			//$("#pp_group_type").html('');
			//$("#popup-unmark .content-popup .contentdivs").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
		  },
		  success: function( response ) {
			  if(response.status==1){
				  topic_load_groups(response.group_id);
				  $('#fade , .popup_block').fadeOut(function() {
						$('#fade, a.close').remove();  //fade them both out
					});
			  }
		  }});
}
function topic_load_groups(d){
	var sURL = "index.php?req=ujh1awLojcrr5vHkyxXELen1ze90tY-oHk34Qmg1woj9MsNi6VPUTw..";
	$.ajax({
		  url: sURL,
		  dataType: 'json',
		  beforeSend: function(){
			$("#topicgroup").html("<option value=''>Select Existing Group</option>");
		  },
		  success: function( response ) {
			  if(response.length>0){
				  for(var i in response){
					  if(response[i].id==d){
						  $("#topicgroup").append("<option value='"+response[i].id+"' selected='selected'>"+response[i].group_name+"</option>");
					  }else{
						  $("#topicgroup").append("<option value='"+response[i].id+"'>"+response[i].group_name+"</option>");
					  }
				  }
			  }
	}});
}


/**Legacy**/

var campaign = new Object();

campaign.step = 1;

campaign.goStep = function (step) {
	document.location="#";
	
	if(step == 1){
		$('.step'+campaign.step).hide();
		$('#cStep'+campaign.step).removeClass('bgGreen');
		$('#cStep'+campaign.step).addClass('bgDarkGrey');
		
		campaign.step = step;
		
		$('.step'+campaign.step).fadeIn('slow');
		$('#cStep'+campaign.step).removeClass('bgDarkGrey');
		$('#cStep'+campaign.step).addClass('bgGreen');
	}
	if(step == 2){
		
		
		var validate = 1;
		var source = 0;
		
		if( $("input[name='name']").val() == "" || $("input[name='name']").val() == "Your Topic Name" ){
			$(".errCampaignName").fadeIn();
			validate = 0;
		}
		if($("input[name='start']").val() == "-"){
			
			$(".errCampaignDate").fadeIn();
			$(".errCampaignDate").html(en['topic_start_date_error']);
			validate = 0;
		}
		
		if($("#topicgroup").val()==""){
			validate = 0;
			$(".errTopicGroup").fadeIn();
			$(".errTopicGroup").html(en['topic_group_error']);
		}
		if(topic_data.channels.length>0){
			source = 1;
		}else{
			
			$(".errCampaignSource").html(en['topic_channel_error']);
			$(".errCampaignSource").fadeIn();
		}

		if(validate == 1 && source == 1){
			insert_brand();
			$('.step'+campaign.step).hide();
			$('#cStep'+campaign.step).removeClass('bgGreen');
			$('#cStep'+campaign.step).addClass('bgDarkGrey');
			
			campaign.step = step;
			
			$('.step'+campaign.step).fadeIn('slow');
			$('#cStep'+campaign.step).removeClass('bgDarkGrey');
			$('#cStep'+campaign.step).addClass('bgGreen');	
		}else{
			$( "#campaign" ).effect('shake', {times: 2, distance: 7}, 50);
		}
	}
	
	if(step==3){
		var validate = 1;
		
		
		if(n_rules==0){
			$(".errKeyword4").fadeIn();
			validate = 0;
		}
		
		if(validate == 1){
			
			var topsy_rules = get_topsy_rules();
			get_estimate(topsy_rules);
			
			$('.step'+campaign.step).hide();
			$('#cStep'+campaign.step).removeClass('bgGreen');
			$('#cStep'+campaign.step).addClass('bgDarkGrey');
			
			campaign.step = step;
			
			$('.step'+campaign.step).fadeIn('slow');
			$('#cStep'+campaign.step).removeClass('bgDarkGrey');
			$('#cStep'+campaign.step).addClass('bgGreen');
		}else{
			$( ".step2" ).effect('shake', {times: 2, distance: 7}, 50);
		}
		
	}
	
	if(step==4){
		var validate = 1;
		
	
		
		if(validate == 1){
		
			
			
			$('.step'+campaign.step).hide();
			$('#cStep'+campaign.step).removeClass('bgGreen');
			$('#cStep'+campaign.step).addClass('bgDarkGrey');
			
			campaign.step = step;
			
			$('.step'+campaign.step).fadeIn('slow');
			$('#cStep'+campaign.step).removeClass('bgDarkGrey');
			$('#cStep'+campaign.step).addClass('bgGreen');
		}else{
			$( ".step3" ).effect('shake', {times: 2, distance: 7}, 50);
		}
	
	}
	
	if(step==5){
		var validate = 1;
		
		if(n_compare_rule>0&&$('#topicgroup').val().length==0){
			$(".errCompare1").fadeIn();
			validate = 0;
		}
		if(n_rules_added==0){
			validate = 0;
		}
		if(validate == 1){
			
			
			var lang = {"id":"Bahasa Indonesia","en":"English","ar":"Arabic","msa":"Bahasa Melayu","all":"All languages"};
			var geo = {"id":"Indonesia","sg":"Singapore","ph":"Philippines","my":"Malaysia","all":"Global"};
			$('#co-lang').html(lang[topic_data.lang]);
			
			var strGeo = "";
			$.each(topic_data.geo,function(n,v){
				if(n>0){
					strGeo+=", ";
				}
				strGeo += geo[v];
			});
			$('#co-geo').html(strGeo);
			$('#co-name').html($("input[name='name']").val());
			$('#co-desc').html(strip_tags($("#topicDesc").val()));
			$('#co-start').html($("input[name='start']").val());
			$('#co-end').html($("input[name='end']").val());
			
			if(topic_data.additional_sites.length>0){
				$('#co-additionalweb').html(toStringSerial(topic_data.additional_sites));
			}else{
				$('#co-additionalweb').html('-');
			}
			if( $("input[name='twitter_account']").val() != 'Your Twitter Account' ){
				$('#co-officialTwitter').html($("input[name='twitter_account']").val());
			}
			if( $("input[name='fb_account']").val() != 'Your Facebook Account' ){
				$('#co-officialFB').html($("input[name='fb_account']").val());
			}
			if( $("input[name='hastag']").val() != 'Your #hashtag' ){
				$('#co-hastag').html($("input[name='hastag']").val());
			}
			
			
			$('#co-historical').html($('#historical').val()+" Days");
			$('#co-comparison').html($("#comp_topics").val());
			
			if( $("input[name='related']").val() != 'Your Topic Name'){
				$('#co-event').html($("input[name='related']").val());
			}
			
			$('#co-twitter').show();
			var sChannels = "";
			var n_channel=topic_data.channels.length;
			$('#co-facebook').hide();
			$('#co-blog').hide();
			
			$.each(topic_data.channels,function(n,v){
				if(n>0){
					sChannels+=", ";
				}
				if(v==1){
					sChannels+="Twitter";
				}
				if(v==2){
					sChannels+="Facebook Public Pages";
				}
				if(v==3){
					sChannels+="Website &amp; Forums";
				}
				if(v==4){
					sChannels+="Additional Websites";
				}
			});
			
			$('#co-twitter').html(sChannels);
			
			
			
			
			
			if(!is_advance){
				$('#co-keyword').html(formated_rule_str());
			}else{
				$('#co-keyword').html(formated_rule_str2());
			}
			$('.step'+campaign.step).hide();
			$('#cStep'+campaign.step).removeClass('bgGreen');
			$('#cStep'+campaign.step).addClass('bgDarkGrey');
			
			campaign.step = step;
			
			
			$('.step'+campaign.step).fadeIn('slow');
			$('#cStep'+campaign.step).removeClass('bgDarkGrey');
			$('#cStep'+campaign.step).addClass('bgGreen');
		}else{
			$(".errKeyword4").fadeIn();
			$( ".step2" ).effect('shake', {times: 2, distance: 7}, 50);
		}
		
	}
	
	if(step==6){
		var validate = 1;
		
		/*
		if( $("input[name='start']").val() == "" || $("input[name='start']").val() == "Set Your Date" ){
			$(".errCampaignDate").fadeIn();
			validate = 0;
		}
		*/
		if(validate == 1){
			var str = cost_table();
			$('#payment-table-body').html(str);
			
			$('.step'+campaign.step).hide();
			$('#cStep'+campaign.step).removeClass('bgGreen');
			$('#cStep'+campaign.step).addClass('bgDarkGrey');
			
			campaign.step = step;
			
			$('.step'+campaign.step).fadeIn('slow');
			$('#cStep'+campaign.step).removeClass('bgDarkGrey');
			$('#cStep'+campaign.step).addClass('bgGreen');
		}else{
			$( ".step5" ).effect('shake', {times: 2, distance: 7}, 50);
		}
		
	}
	
	if(step==7){
		
	}
	
	if(step==8){
		$('.step'+campaign.step).hide();
		$('#cStep'+campaign.step).removeClass('bgGreen');
		$('#cStep'+campaign.step).addClass('bgDarkGrey');
			
		campaign.step = step;
			
		$('.step'+campaign.step).fadeIn('slow');
		$('#cStep'+campaign.step).removeClass('bgDarkGrey');
		$('#cStep'+campaign.step).addClass('bgGreen');
	}
	
	if(step==9){
		$('.step'+campaign.step).hide();
		$('#cStep'+campaign.step).removeClass('bgGreen');
		$('#cStep'+campaign.step).addClass('bgDarkGrey');
			
		campaign.step = step;
			
		$('.step'+campaign.step).fadeIn('slow');
		$('#cStep'+campaign.step).removeClass('bgDarkGrey');
		$('#cStep'+campaign.step).addClass('bgGreen');
	}
	/*
	if(step==10){
		$('.step'+campaign.step).hide();
		$('#cStep'+campaign.step).removeClass('bgGreen');
		$('#cStep'+campaign.step).addClass('bgDarkGrey');
			
		campaign.step = step;
			
		$('.step'+campaign.step).fadeIn('slow');
		$('#cStep'+campaign.step).removeClass('bgDarkGrey');
		$('#cStep'+campaign.step).addClass('bgGreen');
	}*/
	
	if(step == 10){
		//alert("test");
		//alert($("input[name='cfacebook']").val());
		//alert($("input[name='ctwitter']").val());
		var validate = 1;
		
		if($("textarea[name='keywords']").val() == ""){
			$(".errCampaignKeyword").fadeIn();
			validate = 0;
		}
		
		if(validate == 1){
			
			$('#co-lang').html($("#lang option:selected").text());
			$('#co-geo').html($("#geo option:selected").text());
			$('#co-name').html($("input[name='name']").val());
			$('#co-desc').html($("input[name='desc']").val());
			$('#co-start').html($("input[name='start']").val());
			$('#co-end').html($("input[name='end']").val());
			
			
			if( $("input[name='twitter_account']").val() != 'Your Twitter Account' ){
				$('#co-officialTwitter').html($("input[name='twitter_account']").val());
			}
			
			if( $("input[name='hastag']").val() != 'Your #hashtag' ){
				$('#co-hastag').html($("input[name='hastag']").val());
			}
			
			$('#co-brand').html($("input[name='brand']").val());
			$('#co-competitor').html($("textarea[name='competitor-keywords']").val());
			
			if( $("input[name='related']").val() != 'Your Topic Name'){
				$('#co-event').html($("input[name='related']").val());
			}
			
			
			//main keyword / keyword
			var num = listKeyword.length;
			
			var ckw = '';
			for(var i=0;i<num;i++){
				if( listKeyword[i] != '' && listKeyword[i].length>0){
					var go = true;
					/*
					var nux = listMainKeyword.length;
					for(var j=0;j<nux;j++){
						if( listMainKeyword[j] == listKeyword[i]){
							go = false;
						}
					}
					*/
					if(go){
						ckw += listKeyword[i];
						if(i != (num-1) ){
							ckw += ', ';
						}
					}
				}
			}
			$('#co-keyword').html(ckw);
			
			var num = listMainKeyword.length;
			var cmkw = '';
			for(var i=0;i<num;i++){
				if( listMainKeyword[i] != '' && listMainKeyword[i] ){
					cmkw += listMainKeyword[i];
					if(i != (num-1) ){
						cmkw += ', ';
					}
				}
			}
			
			var tw = $('input[name="twitter_account"]').val();
			tw = tw.split(',');
			var ktw = '';
			for(var i=0; i< tw.length; i++){
				if(tw[i] != '' && tw[i].length > 0){
					ktw += tw[i];
					if(i != (tw.length - 1) ){
						ktw += ',';
					}
				}
			}
			
			//alert($('#hastag').val());
			var tag = $('input[name="hastag"]').val();
			tag = tag.split(',');
			var ktag = '';
			for(var i=0; i< tag.length; i++){
				if(tag[i] != '' || tag[i].length > 0){
					ktag += tag[i];
					if(i != (tag.length - 1) ){
						ktag += ',';
					}
				}
			}
			
			$('#co-mainkeyword').html(ktw+','+ktag+','+cmkw);
			
			$('#co-twitter').hide();
			if( $("input[name='ctwitter']").is(":checked") ){
				$('#co-twitter').show();
			}
			$('#co-facebook').hide();
			if( $("input[name='cfacebook']").is(":checked") ){
				$('#co-facebook').show();
			}
			$('#co-blog').hide();
			if( $("input[name='cblog']").is(":checked") ){
				$('#co-blog').show();
			}
			
			var k = $("textarea[name='keywords']").val();
			
			k = k.replace(/\n/gi, "<br />")
			
			$('#co-keywords').html( k );
			
			$('.step'+campaign.step).hide();
			$('#cStep'+campaign.step).removeClass('bgGreen');
			$('#cStep'+campaign.step).addClass('bgDarkGrey');
			
			campaign.step = step;
			
			$('.step'+campaign.step).fadeIn('slow');
			$('#cStep'+campaign.step).removeClass('bgDarkGrey');
			$('#cStep'+campaign.step).addClass('bgGreen');
		}
	}
	
	$("input[name='name']").keyup(function(){
		$(".errCampaignName").fadeOut();
	})
	$("input[name='brand']").keyup(function(){
		$(".errCampaignDesc").fadeOut();
	})
	$("input[name='start']").click(function(){
		$(".errCampaignDate").fadeOut();
	})
	$("input[name='ctwitter'], input[name='cfacebook'], input[name='cblog']").click(function(){
		$(".errCampaignSource").fadeOut();
	})
	$("textarea[name='keywords']").keyup(function(){
		$(".errCampaignKeyword").fadeOut();
	})
	$("#keyword1").keyup(function(){
		$(".errKeyword1").fadeOut();
	});
	$("#label").keyup(function(){
		$(".errLabel").fadeOut();
	});
	$("#keyword2").keyup(function(){
		$(".errKeyword2").fadeOut();
	});
	$("#keyword3").keyup(function(){
		$(".errKeyword3").fadeOut();
	});
	$("#keyword4").keyup(function(){
		$(".errKeyword4").fadeOut();
	});
	$(".errLabel").fadeIn();
	$(".errKeyword1").fadeIn();
	$(".errKeyword2").fadeIn();
	$(".errKeyword3").fadeIn();
	
	
	//var relword = ['mewah', 'sport', 'idaman', 'keluarga', 'dinas'];
	var no=0;
	var kw='';
	var main= new Array("","","","","","","");
	$('.cari').unbind('click').click(function(e){
		
		e.stopPropagation();
		e.preventDefault();
		
		no = $(this).attr('no');
		kw = $('#keyword'+no).val();	
		var htm ='';
		
		
		//$('select').hide();
		//$('#relword'+no).fadeIn();
		//$('#comword').fadeIn();
		
		if(kw==''){
			alert("Please input the keyword first.");
		}else{
				
				//alert( "Main Keyword: "+getTotalMainKeyword() );
				
				if( getTotalMainKeyword() >= maxMainKeyword ){
					
					highlightMainKeyword();
					
					alert('Maximum '+maxMainKeyword+' main keyword');
					
					return false;
				}
				
					if(checkKeyword(kw,true)){
						if(checkSelectedKeyword()){
							$('#comword').append('<option value="'+kw+'">'+kw+'</option>');
							main[no]=kw;
							countSelectedKeyword();
							addListKeyword(kw);
							addListMainKeyword(kw);
						}else{
							//alert("Can't add keyword");
							disableAddKeyword();
						}
					}
					//$("#kwLoader"+no).fadeIn();
					//$("#relword"+no).empty().html("");
					
					$.ajax({
					  url: relatedKeywordUrl+'&kw='+kw+"&lang="+$("#lang").val(),
					  dataType: 'json',
					  beforeSend:function(){
							//$("#kwLoader"+no).html("<img src='images/loader.gif' style='position:absolute;top:140px;left:100px;' />");
							$("#wordcloud").html("<img src='images/loader-med.gif' />");
						},
					  success: function( data ) {
						if(data){
							if(data.status == 1){
								var dat = data.data;
								var tabel = data.tabel;
								
								if(dat){
									
									$('#wordcloud').html(dat);
									$('#tabel').html(tabel);
									$('#wordcloud-box').css({'min-height' : '290px','margin' : '120px 0 0 200px'});

								}
								
							}else{
										
								$('#wordcloud').html("<span>Related keywords not found</span>");
								$('#tabel').html('');
							}
						}else{
							$('#wordcloud').html("<span>Please try again!</span>");
							$('#tabel').html('');
						}
					  }
					});
					//$("#kwLoader"+no).fadeOut();
					
		}
		
   });
	$('#add').unbind('click').click(function(e) {
		
		e.stopPropagation();
		e.preventDefault();
		
		//alert("test");
		if(checkSelectedKeyword()){
			var add = null;
			$('#relword'+no+' option:selected').each(function(){
				sel1 = $(this).val();										
				add += '<option value="'+kw+' '+sel1+'">'+kw+' '+sel1+'</option>';										
			});			
			//$('#relword'+no+' option:selected').remove();
			//alert(add);
			//alert(add);
			$('#comword').append(add);
			countSelectedKeyword();
		}else{
		
			//alert("Can't add keyword");
			disableAddKeyword();
		}
   });  
   $('#remove').click(function() {
		var zplit ='';
		var del ='';
		var sel = $('#comword option:selected').size();
		
		if(sel > 0){
			$('#comword'+no+' option:selected').each(function(){
				sel2 = $(this).val();									
				zplit = sel2.split(" ");
				del += '<option value="'+zplit[1]+'">'+zplit[1]+'</option>';										
			});					
			$('#comword option:selected').remove();
			//$('#relword'+no+'').append(del); 
			countSelectedKeyword();
			enableAddKeyword();
		}
   });
   
   $('form').submit(function() {  
	 $('#select2 option').each(function(i) {  
	  $(this).attr("selected", "selected");  
	 });  
	});
	
	$('.hapus-pilihan').click(function(){
		removeKeyword();
	});
	
	$('.hapus-semua').click(function(){
		removeAllKeyword();
	});
 
}



function countSelectedKeyword(){
	
	var num = $('#comword option').size();
	$("#numSelectedKeyword").text(num+'/'+maxTotalKeyword+' Available');
	
} 

function checkSelectedKeyword(){
	
	var num = $('#comword option').size();
	if(num < maxTotalKeyword){
		if(num == (maxTotalKeyword - 1)){
			disableAddKeyword();
		}
		return true;
	}
	return false;
} 

function disableAddKeyword(){
	$('.cari').hide();
	$('#add').hide();
}

function enableAddKeyword(){
	$('.cari').show();
	$('#add').show();
}

function addKeyword(kw,sel1,nouse){
	if(f_focus=="keyword1"){
		$(f_focus).val($(f_focus).val()+' '+sel1);
	}else{
		if($(f_focus).val().length>0){
			$(f_focus).val($(f_focus).val()+','+sel1);
		}else{
			$(f_focus).val(sel1);
		}
	}
}
function removeAllKeyword(){
	$("#comword").each(function(){
            $("#comword option").attr("selected","selected"); 
	});
	removeKeyword();
}

function removeKeyword(){
	var zplit ='';
	var del ='';
	var sel = $('#comword option:selected').size();
		
		if(sel > 0){
			$('#comword option:selected').each(function(){
				sel2 = $(this).val();									
				removeListKeyword(sel2);
			});					
			$('#comword option:selected').remove();
			countSelectedKeyword();
			enableAddKeyword();
		}
}

var listMainKeyword = new Array();
var listKeyword = new Array();
var idx = 0;
var idxMain = 0;

function highlightMainKeyword(){
	var num = listMainKeyword.length;
	for(var i=0;i<num;i++){
		if(listMainKeyword[i] != '' && listMainKeyword[i]){
			
			$('#comword').find('option').each(function(j, opt) {
				//alert( 'main kw: '+ listMainKeyword[i] +' - '+ $(opt).val());
				if( $(opt).val() == listMainKeyword[i] ){
					//alert( 'kw: '+ $(opt).val() );
					$(opt).css('background-color','#fbcbcb');
					//break;
				}
			});
			
		}
	}
}

function getTotalMainKeyword(){
	var total = 0;
	var num = listMainKeyword.length;
	for(var i=0;i<num;i++){
		if(listMainKeyword[i] != '' && listMainKeyword[i]){
			total = total + 1;
		}
	}
	return total;
}

function getTotalRelatedKeyword(){
	var total = 0;
	var num = listKeyword.length;
	for(var i=0;i<num;i++){
		if(listKeyword[i] != '' && listKeyword[i]){
			total = total + 1;
		}
	}
	return total;
}

function addListMainKeyword(kw){
	listMainKeyword[idx] = kw;
	idxMain++;
}

function addListKeyword(kw){
	listKeyword[idx] = kw;
	idx++;
}

function checkKeyword(kw,err){
	var num = listKeyword.length;
	for(var i=0;i<num;i++){
		if(kw == listKeyword[i]){
			if(err){
				alert('Keyword already selected');
			}
			return false;
		}
	}
	return true;
}
	
function removeListKeyword(kw){
	var num = listKeyword.length;
	for(var i=0;i<num;i++){
		if(kw == listKeyword[i]){
			listKeyword[i] = '';
		}
	}
	
	var num = listMainKeyword.length;
	for(var i=0;i<num;i++){
		if(kw == listMainKeyword[i]){
			listMainKeyword[i] = '';
		}
	}
}

//Tambah twitter_account ke keyword
function addTwitterAccountToKeyword(){
	
}

function addAnotherKeywords(){
	var brand = $('input[name="brand"]').val();
	brand = brand.split(',');
	for(var i=0;i<brand.length;i++){
		if( brand[i] != ''){
			if(brand[i].match(/^\s+$/) === null) {
				addKeywordForAnother(brand[i]);
			}
		}
	}
	
	var brand = $('input[name="related"]').val();
	brand = brand.split(',');
	for(var i=0;i<brand.length;i++){
		if( brand[i] != ''){
			if(brand[i].match(/^\s+$/) === null) {
				addKeywordForAnother(brand[i]);
			}
		}
	}
	
	var brand = $('input[name="hastag"]').val();
	if( brand != ''){
		if(brand.match(/^\s+$/) === null) {
			addKeywordForAnother(brand);
		}
	}
}

function addKeywordForAnother(kw){
	if(checkKeyword(kw,false)){
		if(checkSelectedKeyword()){
			$('#comword').append('<option value="'+kw+'">'+kw+'</option>');
			//main[no]=kw;
			countSelectedKeyword();
			addListKeyword(kw);
			//addListMainKeyword(kw);
		}else{
			disableAddKeyword();
		}
	}
}

function step1_init(){
	topic_data.topic_name = "";
	topic_data.topic_group = "";
	topic_data.topic_start = "";
	topic_data.topic_desc = "";
	topic_data.twitter_acc = "";
	topic_data.fb_acc = "";
	topic_data.channels = [];
	topic_data.geo = [];
	topic_data.additional_sites = [];
	//coverage
	if($("#globalchecked").attr("checked")){
		topic_data.geo.push("all");
	}else{
		if($("#localchecked").attr("checked")){
			if($("#cid").attr("checked")){
				topic_data.geo.push("id");
			}
			if($("#cmy").attr("checked")){
				topic_data.geo.push("my");
			}
			if($("#csg").attr("checked")){
				topic_data.geo.push("sg");
			}
			if($("#cph").attr("checked")){
				topic_data.geo.push("ph");
			}
		}
	}
	//language
	topic_data.lang = $('input[name=lang]:checked').val();
	//channels
	if($("#ctwitter").attr("checked")){
		
		topic_data.channels.push(1);
	}
	if($("#cweb").attr("checked")){
		topic_data.channels.push(3);
	}
	if($("#cfb").attr("checked")){
		topic_data.channels.push(2);
	}
	if($("#cspecific").attr("checked")){
		var has_additional = false;
		for(var i=1;i<=topic_data.additional_site_fields;i++){
			if($("#site"+i).val().length>0){
				if($("#site"+i).val()!="http://www.websitename.com"){
					topic_data.additional_sites.push($("#site"+i).val());
					has_additional =true;
				}
			}
		}
		if(has_additional){
			topic_data.channels.push(4);
		}
	}
	//clausal limits
	topic_data.max_clauses = 10;
	if(topic_data.lang!="all"){
		topic_data.max_clauses-=1;
	}
	if(topic_data.geo.length>0){
		for(var n in topic_data.geo){
			topic_data.max_clauses--;
		}
	}
	$("#txtmaxclauses").html(topic_data.max_clauses);
	$("#txtmaxclauses2").html(topic_data.max_clauses);
	campaign.goStep(2);
	return false;
}
/**
 * adding additional sites links
 * @return
 */
function additionalSitesField(){
	var id = topic_data.additional_site_fields + 1;
	if(id<=10){
		topic_data.additional_site_fields++;
		var p = $("<p>");
		var input = $("<input>");
		p.attr("class","rowInput");
		input.attr("class","round5 additionalSites");
		input.attr("type","text");
		input.attr("id","site"+id);
		input.attr("name","a_site[]");
		input.attr("value","");
		input.attr("onfocus","javascript:this.value='';");
		p.append(input);
		$("#additionalbox").append(p);
	}
	return false;
}
function test_topic(){
	//use these method for testing js functionality.
	
}
function toStringSerial(arr){
	var str="";
	$.each(arr,function(x,v){
		if(x>0){
			str+=",";
		}
		str+=v.toString();
	});
	return str;
}

//inserting brand name into rule list
function insert_brand(){
	try{
		if($("#brand").val().length>0){
			$("#keyword1").val($("#brand").val());
			refresh_no();
			if(n_rules_added<5){
				var str = topic_check_keywords('brand','keyword1','keyword2','keyword3','lang');
				var ruleset = topic_rules_str(str);
				//alert(ruleset);
				//$('#ruleset').append(topic_rules_str(str));
				var _main_keywords = $('#keyword1').val();
				//if($('#brand').val().length>0){
					//_main_keywords = $('#brand').val()+','+_main_keywords;
				//}
				var str="<tr id=\"t_ruleall"+n_rules+"\"><td><span id='no_"+n_rules+"'>0</span></td><td><input type='hidden' id='rulelabel"+n_rules+"' name='rulelabel"+n_rules+"' value='"+$('#keyword1').val()+"'/>"+$('#keyword1').val()+"</td><td class='key1'><input type='text' id='ruleall"+n_rules+"' name='ruleall"+n_rules+"' value='"+_main_keywords+"'/></td><td class='key2'><input type='text' id='ruleany"+n_rules+"' name='ruleany"+n_rules+"' value='"+$('#keyword2').val()+"'/></td><td class='key3'><input type='text' id='ruleexc"+n_rules+"' name='ruleexc"+n_rules+"' value='"+$('#keyword3').val()+"'/></td><td><a class='helpsmall helpform tip_trigger' href='#'>&nbsp;<span id=\"ruletip"+n_rules+"\" class=\"tip\">"+ruleset+"</span></a></td></tr>";
				
				$('#tblrules').append(str);
				$('#ruleall'+n_rules).tagsInput({width:'auto',onChange:onChangeTag,onRemoveTag:onRemoveTag});
				$('#ruleany'+n_rules).tagsInput({width:'auto',onChange:onChangeTag,onRemoveTag:onRemoveTag});
				$('#ruleexc'+n_rules).tagsInput({width:'auto',onChange:onChangeTag,onRemoveTag:onRemoveTag});
				n_rules++;
				$("#n_rules").val(n_rules);
				refresh_no();
				reactivate_tooltip();
				$("#keyword1,#keyword2,#keyword3").val('');
			}
		}
	}catch(e){}
}

function analyze_advance_rule(){
	var rule = $("#keywordcustom").val()+" ";

	var clauses = [];
	var groups = [];
	var final_clauses = [];
	var is_group = false;
	var is_merge = false;
	
	//rule.length;
	var c = "";
	for(var i=0;i<rule.length;i++){
	    //search until found whitespace.. then we got our first clause
	    if(rule[i]!=" " && rule[i]!='(' && rule[i]!=')' && rule[i]!="" && rule[i]!='"'){
	        if(rule[i].length>0){
	            c+=rule[i].toString();
	        }
	        
	    }else if(rule[i]=='"'){
	        if(!is_merge){
	            is_merge = true;
	        }else{
	            is_merge = false;
	        }
	
	    }else if(rule[i]=='('){
	        is_group = true;
	    }else if(rule[i]==')'){
	        is_group = false;
	        groups.push(c);
	        c = "";
	        
	    }else{
	      //console.log(c);
	        if(c.length>0){
	            if(!is_merge){
	                if(!is_group){
	                   
	                    clauses.push(c);
	                }else{
	                    groups.push(c);
	                }
	                 c = "";
	            }else{
	                  c += ' ';
	            }
	        }
	       
	        //and clauses
	    }
	}

	var total_clauses = 0;
	
	
	var mainClause = [];
	var strClause = "";
	$.each(clauses,function(k,v){
	    if(v.toUpperCase()!="OR"){
	        strClause += v+" ";
	        total_clauses++;
	    }else{
	        mainClause.push(strClause);
	        strClause = "";
	    }
	});
	if(strClause.length>0){
	    mainClause.push(strClause);
	   
	}
	
	$.each(mainClause,function(i,j){
	    var mc = mainClause[i];
	   
	    $.each(groups,function(k,v){
	       if(v.toUpperCase()!="OR"){
	            mc += v;
	             
	        }else{
	            mc = mainClause[i];
	        }
	        
	        if(mc!=mainClause[i]){
	            final_clauses.push(mc);
	            mc = '';
	        }
	    });
	    if(mc.length>0){
	        final_clauses.push(mc);
	        mc = '';
	    }
	});
	//additional clauses
	$.each(groups,function(k,v){
	       if(v.toUpperCase()!="OR"){
	            total_clauses++;
	        }
	});
	
	return	{rulesets:final_clauses,total_clauses:total_clauses};
}
