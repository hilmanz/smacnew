// :: Download Report ::
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
				if(action == 'customReport'){
					summaryTabMenu('customReport');
					pageInit=0;
				}else if(action == 'rawPost'){
					summaryTabMenu('rawPost');
					pageInit=0;
				}else  if(action == 'exclusiveReport'){
					summaryTabMenu('exclusiveReport');
					pageInit=0;
				}
			},
			defaultRoute: function(actions){
				summaryTabMenu('customReport');
				pageInit=0;
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
			case '#customReport':
				var active = '.navCustom';
				break;
			case '#rawPost':
				var active = '.navRaw';
				break;
			case '#exclusiveReport':
				var active = '.navExclusive';
				break;
			default:
				var active = '.navCustom';
		}
		$(active).addClass("active");
	}
	
	function noDataReport(){
		var str='';
		str+='<div id="mytopic-banner">';
			str+='<div class="content">';
				str+='<h1 style="margin-top: 38px;">Sorry, there\'s no available report yet.</h1>';
			str+='</div>';
		str+='</div>';
		return str;
	}