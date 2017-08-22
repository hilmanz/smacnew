/// Acit Jazz
/// ANIMATION HEADER FOOTER SMAC 
/// LOAD IMAGES FIRST

/*	$(document).ready(preload);
	function preload(){
		$.preload([
			"images/amplop.png",
			"images/awan2.png",
			"images/bg_city.png",
			"images/bg_footer2.png",
			"images/bubble_facebook.png",
			"images/bubble_rss.png",
			"images/bubble_twitter.png",
			"images/bubble1.png",
			"images/gear.png",
			"images/bullet.png",
			"images/icon_box_green_small.png",
			"images/icon_box_green.png",
			"images/icon_box_grey_small.png",
			"images/icon_box_grey.png",
			"images/kabel_foot.png",
			"images/kabel_foot2.png",
			"images/kabel_top.png",
			"images/logo.png",
			"images/mesin-atas.png",
			"images/mesin.png",
		], {
			init: function(loaded, total) {
			//console.log("loading: " + loaded + "/" + total);
			},
			loaded: function(img, loaded, total) {
			//console.log("loaded: " + loaded + "/" + total);	
			}
		});
	
	}*/
// ANIMATION
$(window).load(function(){
	
	function moveAmplopBiru(){
		$('#amplopBiru').animate({'left':'+=400'},8000).delay(0)
												    .animate({'top':'+=50',rotate: '+=90deg','left':'+=50',opacity:0},2000).delay(10)
													.animate({'left':'-=450','top':'-=50',rotate: '-=90deg', opacity:1},0,function(){
													setTimeout(moveAmplopBiru,1000);
		});
	}
	function moveAmplopMerah(){
		$('#amplopMerah').delay(2000).animate({'left':'+=400'},8000).delay(0)
												    .animate({'top':'+=50',rotate: '+=90deg','left':'+=50',opacity:0},2000).delay(10)
													.animate({'left':'-=450','top':'-=50',rotate: '-=90deg', opacity:1},0,function(){
													setTimeout(moveAmplopMerah,1000);
		});
	}
	function moveAmplopOrange(){
		$('#amplopOrange').delay(4000).animate({'left':'+=400'},8000).delay(0)
												    .animate({'top':'+=50',rotate: '+=90deg','left':'+=50',opacity:0},2000).delay(10)
													.animate({'left':'-=450','top':'-=50',rotate: '-=90deg', opacity:1},0,function(){
													setTimeout(moveAmplopOrange,1000);
		});
	}
	function moveAwan1(){
		$('#awan1').animate({'left':'+=450'},16000).delay(0)
													.animate({'left':'-=450'},16000,function(){
													setTimeout(moveAwan1,1000);
		});
	}
	function moveAwan2(){
		$('#awan2').animate({'left':'-=600'},16000).delay(0)
													.animate({'left':'+=600'},16000,function(){
													setTimeout(moveAwan2,1000);
		});
	}
	function moveAwan3(){
		$('#awan3').animate({'left':'-=300'},16000).delay(0)
													.animate({'left':'+=300'},16000,function(){
													setTimeout(moveAwan3,1000);
		});
	}
	function moveAwan4(){
		$('#awan4').animate({'left':'-=500'},16000).delay(0)
													.animate({'left':'+=500'},16000,function(){
													setTimeout(moveAwan4,1000);
		});
	}
	function moveBubble1(){
		$('#bubble1').animate({rotate: '+=180deg','left':'+=65','top':'+=30',scale: '-=0.33'},2500).delay(0)
													.animate({opacity:0,rotate: '-=180deg'},500).delay(0)
													.animate({'left':'-=65','top':'-=30',scale: '+=0.33'},500).delay(0)
													.animate({opacity:1,},1000,function(){
													setTimeout(moveBubble1,1000);
		});
	}
	function moveBubble2(){
		$('#bubble2').delay(4000).animate({rotate: '-=240deg','left':'+=140','top':'+=50',scale: '-=0.33'},3000).delay(0)
													.animate({opacity:0,rotate: '+=240deg'},500).delay(0)
													.animate({'left':'-=140','top':'-=50',scale: '+=0.33'},500).delay(0)
													.animate({opacity:1,},1000,function(){
													setTimeout(moveBubble2,1000);
		});
	}
	function moveBubbleRss(){
		$('#bubbleRss').delay(2000).animate({rotate: '+=240deg','left':'+=60','top':'+=10',scale: '-=0.70'},2000).delay(0)
													.animate({opacity:0,rotate: '-=240deg'},500).delay(0)
													.animate({'left':'-=60','top':'-=10',scale: '+=0.70'},500).delay(0)
													.animate({opacity:1,},1000,function(){
													setTimeout(moveBubbleRss,1000);
		});
	}
	function moveBubbleFacebook(){
		$('#bubbleFacebook').delay(3500).animate({rotate: '+=240deg','left':'+=90','top':'+=90',scale: '-=0.70'},3000).delay(0)
													.animate({opacity:0,rotate: '-=240deg'},500).delay(0)
													.animate({'left':'-=90','top':'-=90',scale: '+=0.70'},500).delay(0)
													.animate({opacity:1,},1000,function(){
													setTimeout(moveBubbleFacebook,1000);
		});
	}
	function moveBubbleTwitter(){
		$('#bubbleTwitter').delay(3000).animate({rotate: '-=240deg','left':'+=150','top':'+=10',scale: '-=0.70'},3000).delay(0)
													.animate({opacity:0,rotate: '+=240deg'},500).delay(0)
													.animate({'left':'-=150','top':'-=10',scale: '+=0.70'},500).delay(0)
													.animate({opacity:1,},1000,function(){
													setTimeout(moveBubbleTwitter,1000);
		});
	}
	// Run the functions when the document and all images have been loaded. 
	moveAmplopBiru();
	moveAmplopMerah();
	moveAmplopOrange();
	moveAwan1();
	moveAwan2();
	moveAwan3();
	moveAwan4();
	moveBubble1();
	moveBubble2();
	moveBubbleRss();
	moveBubbleFacebook();
	moveBubbleTwitter();
});

// ROTATE GEAR
setInterval(
	function () {
		$('#gear1,#gear2,#gear3,#gear4').animate({rotate: '+=10deg'}, 0);
	},
	200
);