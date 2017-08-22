
function reload_tooltip(){
    $(".tip_trigger").hover(function(){
        tip = $(this).find('.tip');
        tip.show(); //Show tooltip
    }, function() {
        tip.hide(); //Hide tooltip
    }).mousemove(function(e) {
        var mousex = e.pageX - 50; //Get X coodrinates
        var mousey = e.pageY + 20; //Get Y coordinates
        var tipWidth = tip.width(); //Find width of tooltip
        var tipHeight = tip.height(); //Find height of tooltip

        //Distance of element from the right edge of viewport
        var tipVisX = $(window).width() - (mousex + tipWidth);
        //Distance of element from the bottom of viewport
        var tipVisY = $(window).height() - (mousey + tipHeight);

        if ( tipVisX < 20 ) { //If tooltip exceeds the X coordinate of viewport
            mousex = e.pageX - tipWidth - 20;
        } if ( tipVisY < 20 ) { //If tooltip exceeds the Y coordinate of viewport
            mousey = e.pageY - tipHeight - 20;
        }
        //Absolute position the tooltip according to mouse position
        tip.css({  top: mousey, left: mousex });
    });
}
function tpl_market_country(data,n,total){
	var str="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"zebra\">"+
	"<tr class=\"heads\">"+
	  "<td>Locations</td>"+
	  "<td>Mentions</td>"+
	  "<td>Impressions</td>"+
	  "<td>Peoples</td>"+
	  "<td>Channels</td>"+
	  "<td>Shares</td>"+
	   " </tr>";
	var t = 0;
	
	for(var i in data){
		if(parseInt(i)>=n){
			str+="<tr>"+
			  "<td><a href='"+data[i].url+"'>"+data[i].country_name+"</a></td>"+
			  "<td>"+addCommas(data[i].mentions)+"</td>"+
			  "<td>"+addCommas(data[i].impression)+"</td>"+
			  "<td>"+addCommas(data[i].people)+"</td>"+
			  "<td><a class='tip_trigger twitter-bar' style='width:"+data[i].twitter+"%;float:left' alt='Twitter' href='#'>"+Math.round(data[i].twitter)+"%<span class='tip'>Twitter</span></a>" +
			  "		<a class='tip_trigger fb-bar' style='width:"+data[i].fb+"%;float:left' alt='Facebook' href='#'>"+Math.round(data[i].fb)+"%<span class='tip'>Facebook</span></a>" +
			  "<a class='tip_trigger web-bar'  style='width:"+data[i].web+"%;float:left' alt='Web' href='#'>"+Math.round(data[i].web)+"%<span class='tip'>Website</span></a></td>"+
			  "<td>"+data[i].share+"%</td>"+
			"</tr>";
			t++;
			if(t==total){
				break;
			}		}
	}
	  str+="</table>";
	
	  return str;
}
