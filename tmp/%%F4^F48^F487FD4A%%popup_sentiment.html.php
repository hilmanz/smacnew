<?php /* Smarty version 2.6.13, created on 2013-01-03 15:28:58
         compiled from smac/popup_sentiment.html */ ?>
<div id="popup-sentiment" class="popup_block popupWidth" style="left: 33%;">
	<div class="headpopup">
    	<h1 class="fleft" style="margin-right: 30px;">Favourable</h1>
		<div id="tabSent">
			<a id="btnTwitter" onclick="popupSentiment(null, 0, 'sentiment_tweet');" class="conversPop theTolltip active" title="Twitter" style="margin-right:10px;"><span class="iconTwitter">&nbsp;</span></a>
			<a id="btnFacebook" onclick="popupSentiment(null, 0, 'fb_sentiment_post');" class="conversPop theTolltip" title="Facebook" style="margin-right:10px;"><span class="iconFacebook">&nbsp;</span></a>
			<a id="btnWeb" onclick="popupSentiment(null, 0, 'web_sentiment_post', 1);" class="conversPop theTolltip" title="Blog" style="margin-right:10px;"><span class="iconWeb">&nbsp;</span></a>
			<a id="btnForum" onclick="popupSentiment(null, 0, 'web_sentiment_post', 2);" class="conversPop theTolltip" title="Forum" style="margin-right:10px;"><span class="iconForum">&nbsp;</span></a>
			<a id="btnNews" onclick="popupSentiment(null, 0, 'web_sentiment_post', 3);" class="conversPop theTolltip" title="News" style="margin-right:10px;"><span class="iconNews">&nbsp;</span></a>
			<a id="btnEcommerce" onclick="popupSentiment(null, 0, 'web_sentiment_post', 5);" class="conversPop theTolltip" title="Ecommerce" style="margin-right:10px;"><span class="iconEcommerce">&nbsp;</span></a>
			<a id="btnCorporate" onclick="popupSentiment(null, 0, 'web_sentiment_post', 0);" class="conversPop theTolltip" title="Corporate/Personal" style="margin-right:10px;"><span class="iconCorporate">&nbsp;</span></a>
		</div>
    </div>
    <div id="sentimentPopup" class="content-popup" style="width:620px;">	
		<div style="width:620px;height:300px;text-align:center;padding-top:200px;background-color:#ffffff;"><img src="images/loader-med.gif"></div>
       
    </div><!-- .content-popup -->
    <div id="sentimentPopupPaging" class="paging">
    </div><!-- .paging -->
</div><!-- #popup-sentiment -->