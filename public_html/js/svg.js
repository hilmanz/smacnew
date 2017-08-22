/**
 * drawing PII Quadrant
 * @param {Object} p
 * example parameters : 
 * {id:1,label:"sample 1",volume:100,viral:100,sentiment:-5}
 */
 
//Global Variable
var popupSVGInit = 0;
var typeCap, typeNorm;

function quadrant(p){
	//Quadrant Type Interation => rate or Potential Impact Index => viral
	var type = p.type;
	
	if(type){typeCap = 'INTERACTION RATE'; typeNorm='Rate';}
	else {typeCap = 'VIRAL EFFECT'; typeNorm='Viral';}
	//Detect Browser
	var allow = true;
	$.each($.browser, function(k, v) {
		if(k == 'msie'){
			switch($.browser.version){
				case '6.0':
					allow = false;
					break;
				case '7.0':
					allow = false;
					break;
				case '8.0':
					allow = false;
					break;
				default:
					allow = true;
			}
		}else if(k == 'safari'){	
			var versi = $.browser.version;
			
			var headVersion = parseInt(versi.substr(0,3));
			if(headVersion <= 534){
				allow = false;
			}else{
				allow = true;
			}
		}
	});
	
	var target = p.target;
	if(allow != false){
		
		var nodes = p.data;
		var canvasSize = {width:p.width,height:p.height};
		var qd = {width:p.width-100,height:p.height-50,x:85,y:12,x2:0,y1:0,y2:0};
		qd.x2 = qd.x+qd.width;
		qd.y1 = (qd.height/2)+qd.y;
		qd.y2 = (qd.height/2)+qd.y;
		qd.y3 = (qd.height)+qd.y;
		qd.x3 = (qd.width/2)+qd.x;
		qd.halfHeight = qd.height/2;
		qd.y4 = qd.y+qd.halfHeight;
		
		var texts = [
		{text:"GOOD &amp; UNPOPULAR",x:qd.x+10,y:qd.y+20,size:16,color:"#BBBBBB"},
		{text:"GOOD &amp; POPULAR",x:qd.width-85,y:qd.y+20,size:16,color:"#BBBBBB"},
		{text:"BAD &amp; UNPOPULAR",x:qd.x+10,y:qd.height-(qd.y-10),size:16,color:"#BBBBBB"},
		{text:"BAD &amp; POPULAR",x:qd.width-65,y:qd.height-(qd.y-10),size:16,color:"#BBBBBB"},
		{text:typeCap,x:qd.x3-40,y:qd.height+qd.y+15,size:12,color:"#BBBBBB"}
		];
		var texts2 = [
		{text:"VERY GOOD",x:qd.x-75,y:qd.y+7,size:10,color:"#00ee00"},
		{text:"GOOD",x:qd.x-45,y:qd.y+(qd.height/4)+5,size:10,color:"#00ee00"},
		{text:"BAD",x:qd.x-35,y:qd.height+qd.y-(qd.height/4),size:10,color:"#dd0000"},
		{text:"VERY BAD",x:qd.x-65,y:qd.height+qd.y-2,size:10,color:"#dd0000"}
		];
		var svg = '<svg xmlns="http://www.w3.org/2000/svg" height="'+canvasSize.height+'" width="'+canvasSize.width+'" baseProfile="full" version="1.1">'+
					'<!-- canvas rectangle -->'+
					'<rect x="0" y="0" width="'+canvasSize.width+'" height="'+canvasSize.height+'" stroke="black" fill="transparent" stroke-width="1"></rect>'+
					'<!-- diagram rectangle -->'+
					'<rect x="'+qd.x+'" y="'+qd.y+'" width="'+qd.width+'" height="'+qd.height+'" stroke="#cccccc" fill="transparent" stroke-width="1"/></rect>'+
					'<!-- quadrant lines -->'+
					'<line x1="'+qd.x+'" x2="'+qd.x2+'" y1="'+qd.y1+'" y2="'+qd.y2+'" stroke="black" stroke-width="2" stroke-opacity="0.5" stroke-linecap="butt" stroke-dasharray="2, 9"/>'+
					'<line x1="'+qd.x3+'" x2="'+qd.x3+'" y1="'+qd.y+'" y2="'+qd.y3+'" stroke="black" stroke-width="2" stroke-opacity="0.5" stroke-linecap="butt" stroke-dasharray="2, 9"/>';
		
		//draw the grids
		var n_hgrids = qd.width/20; //the horizontal grids gap size
		var n_vgrids = qd.height/20; //the vertical grids gap size
		//horizontals
		for(var i=0;i<20;i++){
			var nx = qd.x+(n_hgrids*i);
			svg+='<line x1="'+nx+'" x2="'+nx+'" y1="'+qd.y+'" y2="'+qd.y3+'" stroke="#e5e5e5" stroke-width="1" stroke-opacity="0.5" stroke-linecap="butt"/>';
		}
		//verticals
		for(var i=0;i<20;i++){
			var ny = qd.y+(n_vgrids*i);
			svg+='<line x1="'+qd.x+'" x2="'+qd.x2+'" y1="'+ny+'" y2="'+ny+'" stroke="#e5e5e5" stroke-width="1" stroke-opacity="0.5" stroke-linecap="butt"/>';
		}
		//positive background (green color)
		svg+='<rect fill="#00ee00" x="'+qd.x+'" y="'+qd.y+'" width="'+qd.width+'" height="'+qd.halfHeight+'" opacity="0.1"></rect>';
		//negative background (red color)
		svg+='<rect fill="#dd0000" x="'+qd.x+'" y="'+qd.y4+'" width="'+qd.width+'" height="'+qd.halfHeight+'" opacity="0.1"></rect>';
		
		//default texts and labels
		for(var i=0;i<texts.length;i++){
			svg+='<text x="'+texts[i].x+'" y="'+texts[i].y+'" font-family="Verdana" font-size="'+texts[i].size+'" font-weight="bold" fill="'+texts[i].color+'">'+texts[i].text+'</text>';
		}
		for(var i=0;i<texts2.length;i++){
			svg+='<text x="'+texts2[i].x+'" y="'+texts2[i].y+'" font-family="Verdana" font-size="'+texts2[i].size+'" fill="'+texts2[i].color+'">'+texts2[i].text+'</text>';
		}
		var sx = qd.halfHeight-20;
		svg+='<text x="70" y="'+sx+'" font-family="Verdana" font-size="10" fill="#BBBBBB" transform="rotate(90 70,'+sx+')">SENTIMENT</text>';
		//-->
		
		//content
		//looking for maximum volumes
		var mx = 0;
		$.each(nodes,function(i,v){
			console.log(v.volume);
			mx = Math.max(mx,v.volume);
		});
		for(var i=0;i<nodes.length;i++){
			var origin = {x:qd.width/2,y:qd.height/2};
			var cx = ((nodes[i].viral/100)*qd.width)+qd.x;
			
			var cy = ((((nodes[i].sentiment*-1)/10)*(qd.height/2))+origin.y)+qd.y;
			var r = 10+((nodes[i].volume/mx)*(qd.height/6));
			var f = "#"+(255+Math.round(((255*255*255)*Math.random()))).toString(16);
			
			var cx2 = cx-(r*Math.cos(45));
			var cy2 = cy-(r*Math.sin(45));
			
			var id="node"+i;
			//volume circle
			svg+='<circle onclick="showSVGTooltip('+cx+','+cy+', \''+nodes[i].label+'\', '+nodes[i].sentiment+', '+nodes[i].viral+', '+nodes[i].volume+');" onmouseover="$(\'#'+id+'\').show();" onmouseout="$(\'#'+id+'\').hide();hideSVGTooltip();" opacity="0.5" cx="'+cx+'" cy="'+cy+'" r="'+r+'" fill="'+f+'"></circle><circle cx="'+cx+'" cy="'+cy+'" r="4" fill="#000000"></circle>';

			//create circle for sentiment value
			//svg+='<circle onmouseover="$(\'#'+id+'\').show();" onmouseout="$(\'#'+id+'\').hide();" opacity="0.8" fill="#ffffff" r=10 cx="'+cx2+'" cy="'+cy2+'"></circle><text x="'+(cx2-4)+'" y="'+(cy2+2)+'" font-size="10" font-family="Verdana">'+nodes[i].sentiment+'</text>';
			
			//legend
			svg+='<rect fill="'+f+'" x="'+((i*100)+85)+'" y="'+(qd.y3+17)+'" width="15" height="15"></rect>';
			svg+='<text x="'+((i*100)+105)+'" y="'+(qd.y3+28)+'" font-family="Verdana" font-size="10">'+nodes[i].label+'</text>';
		}
		for(var i=0;i<nodes.length;i++){
			var id="node"+i;
			var cx = ((nodes[i].viral/100)*qd.width)+qd.x;
			var cy = ((((nodes[i].sentiment*-1)/10)*(qd.height/2))+origin.y)+qd.y;
			var r = 10+((nodes[i].volume/mx)*(qd.height/6));
			//finally, draw the node's text
			svg+='<text id="'+id+'" x="'+(cx-(nodes[i].label.length*4/2))+'" y="'+(cy+r+10)+'" font-family="Verdana" font-size="10" style="display:none;">'+nodes[i].label+'</text>';
			//svg+='<text id="'+id+'" x="'+cx+'" y="'+cy+'" font-family="Verdana" font-size="10" style="display:none;">'+nodes[i].label+'</text>';
			
		}
		//-->
		
		//closing up
		svg+='<!--end of quadrant lines-->'+
			 '</svg>';
		//end drawing
		$("#"+target).html(svg);
		$("#"+target).css('position', 'relative');
		var str='';
		str+='<div onmouseover="$(\'div#ToolTip\').show();" onmouseout="hideSVGTooltip();" id="ToolTip" style="position: absolute;top:0;left:0;background: white;display:none;padding: 10px;border: 1px solid grey; opacity: 0.8;">';
		str+='<span id="labelSVG" style="font-size: 15px;font-weight:bold;">Soulnation</span><br>';
		str+='<span id="piiSVG"></span><br>';
		str+='<span id="viralSVG"></span><br>';
		str+='<span id="volumeSVG"></span>';
		str+='</div>';
		$("#"+target).append(str);
	}else{
		$("#"+target).html('Quadrant Diagram can only be viewed using modern browsers. (e.g Internet Explorer 9+, Firefox 14+, Chrome 21+, Safari 6+, Opera 12+)');
	}	
}

function showSVGTooltip(x,y,label,sentiment,viral,volume){
	$('div#ToolTip').css({
			top: y,
			left: x,
			color: '#333333'
	});
	$('span#labelSVG').html(label);
	$('span#piiSVG').html("Sentiment : "+sentiment);
	$('span#viralSVG').html(typeNorm+" : "+viral);
	$('span#volumeSVG').html("Volume : "+smac_number2(volume));
	$('div#ToolTip').show();
}

function hideSVGTooltip(){
	$('div#ToolTip').hide();
}