<?php /* Smarty version 2.6.13, created on 2013-01-03 15:28:58
         compiled from smac/popup.html */ ?>
<script src="js/charts/highcharts.js" type="text/javascript"></script>
<div id="draggable" >

<div id="profile" class="popup_block popupWidth" style="display: none; width: 650px; margin-left: -330px;">
	
	<div class="headpopup">
    	<h1 class="fleft"></h1>
        <a class="logo-twitter fright" href="#">&nbsp;</a>
    </div>
    <div id="popupload"><div style='width:550px;height:200px;text-align:center;margin-top:100px;'><img src='images/loader-med.gif' /></div></div>    
    <div id="popupbody">
    <div class="content-popup">
   	 	<div class="smallthumb">
        	<img src="">
        </div>
        <div class="statistik-profile">
        	<a class="icon1" href="#" title="Followers" no="1">&nbsp;</a>
        	<a class="icon2" href="#" title="Mentions" no="2">&nbsp;</a>
        	<a class="icon3" href="#" title="Total Impressions" no="3">&nbsp;</a>
        	<a class="icon4" href="#" title="Retweet Frequency" no="4">&nbsp;</a>
        	<a class="icon5" href="#" title="Retweeted Impressions" no="5">&nbsp;</a>
        	<a class="icon6" href="#" title="share" no="5">0%</a>
        </div>
        <div class="impact-score">
        	<span>RANK</span>
        	<h1></h1>
        	<div id="exc_button" style="margin-left:13px;">
        	
        	</div>
        </div>
	</div>
    <div id="profile-detail">
    	<div id="about-profile">
        	<span>About :</span>
            <span id="authorabout" class="entry"></span>
        </div>
        <div id="location-profile">
        	<span>Location :</span>
            <span id="authorlocation" class="entry"></span>
       	</div>
    </div>
    <ul class="tabs2">
        <li><a href="#favorite-word" class="favorite-word-tab">Favorite Word</a></li>
        <li><a href="#overall-performance" class="overall-performance">Overall Performance</a></li>
        <li><a href="#sentiment-overtime" class="sentiment-overtime">Sentiment Over Time</a></li>
        <li><a href="#mentions-overtime" class="mentions-overtime">Tweets</a></li>
    </ul>
    <div class="tab_container">
        <div id="favorite-word" class="tab_content2">
            <div class="content-popup" style="min-height:300px;">
                    <div id="profilewc" class="favorite-word">
                            <h1><a class="black" style="font-size:30px;" href="#">null</a></h1>
                           
                    </div><!-- .favorite-word -->
            </div>
        </div><!-- #tab-facebook -->
        <div id="overall-performance" class="tab_content2">
            <div class="content-popup">
            	                	<div style="width:380;height:200px;clear:both;" id="chartPop"></div>
                	
                 <!--<img src="content/chart4.jpg"/>-->
            </div>
        </div><!-- #tab-blog -->
        <div id="sentiment-overtime" class="tab_content2">
            <div class="content-popup">
                
                	<div style="width:380;height:200px;clear:both;" id="chartPop2"></div>
                	
                 <!--<img src="content/chart4.jpg"/>-->
            </div>
        </div><!-- #tab-blog -->
        <div id="mentions-overtime" class="tab_content2">
            <div class="content-popup">
                <div id="chartPop3"></div>
            </div>
			<div id="twitterFeedPaging" class="paging">
			</div>
        </div><!-- #tab-blog -->
    </div><!-- .tab_container -->
    <div class="legend">
        	<a class="icon1" href="#" title="Followers" no="1">Followers</a>
        	<a class="icon2" href="#" title="Mentions" no="2">Mentions</a>
        	<a class="icon3" href="#" title="Total Impressions" no="3">Total Impressions</a>
        	<a class="icon4" href="#" title="Retweet Frequency" no="4">Retweet Frequency</a>
        	<a class="icon5" href="#" title="Retweeted Impressions" no="5">Retweeted Impressions</a>
        	<a class="icon6" href="#" title="Share" no="5">Share</a>
    </div>
    </div>
</div>
</div>
    <script>
    <?php echo '
    $(".tip_triggers").hover(
			
			function(){
				var no = $(this).attr(\'no\');
				$(".hov"+no).stop(true,true).fadeIn("fast");			
			},
			function(){
				var no = $(this).attr(\'no\');
				$(".hov"+no).stop(true,true).fadeOut("fast");
				}
			);

	//$(".tip_triggers").mousemove(function(c){
	//		var no = $(this).attr(\'no\');
	//		var left = c.pageX - 550;
	//		var top = c.pageY - 570;
	//		$(".hov"+no).css({
	//				\'left\': left,
	//				\'top\' : top
	//			})
	//	});
    '; ?>

    </script>