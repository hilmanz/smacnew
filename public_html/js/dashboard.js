function dashcontent(sURL,target,txt){
	$.ajax({
	  url: sURL,
	  dataType: 'html',
	  beforeSend: function(){
		//$("#pp_group_type").html('');
		$("#"+target).html("<div class='loaders'><p>"+txt+"</p><img src='images/ajax-loader.gif' /></div>");
		//$("#popup-unmark .content-popup .contentdivs").html("<div style='width:650px;min-height:125px;background-color:white; margin:0 auto; padding:75px 0 0 0; text-align:center;'><img src='images/loader.gif' align='center' /></div>");			
	  },
	  success: function( response ) {
		  if(response.length>0){
			 // console.log(response);
			  $("#"+target).html(response);
			  if(target=='wgtab'){
				  //console.log('wgtab');
				  $(".tab_content").hide(); //Hide all content
					$("ul.tabs li:first").addClass("active").show(); //Activate first tab
					$(".tab_content:first").show(); //Show first tab content
					//On Click Event
					$("ul.tabs li").click(function() {
						$("ul.tabs li").removeClass("active"); //Remove any "active" class
						$(this).addClass("active"); //Add "active" class to selected tab
						$(".tab_content").hide(); //Hide all tab content

						var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
						wf_active_tab = activeTab;
						if(activeTab=="#tab-custom"){
							wf_custom_tab($(this));
						}
						
						$(activeTab).fadeIn(); //Fade in the active ID content
						return false;
					});
				  
			  }
		  }
	  }});
}