<?php /* Smarty version 2.6.13, created on 2013-01-03 15:28:58
         compiled from smac/popup_ka_conversation.html */ ?>
<div id="popup-ka-conversation" class="popup_block popupWidth" style="left: 33%;">
	<div class="headpopup">
    	<h1 class="fleft">Conversation</h1>
		<div id="tabKol">
			<a id="buttonTwitter" onclick="conversationByDate(null, 0, 'conversation_by_date');" class="conversPop theTolltip active" title="Twitter" style="margin-right:10px;"><span class="iconTwitter">&nbsp;</span></a>
			<a id="buttonFacebook" onclick="conversationByDate(null, 0, 'fb_conversation_by_date');" class="conversPop theTolltip" title="Facebook" style="margin-right:10px;"><span class="iconFacebook">&nbsp;</span></a>
			<a id="buttonWeb" onclick="conversationByDate(null, 0, 'web_conversation_by_date', 1);" class="conversPop theTolltip" title="Blog" style="margin-right:10px;"><span class="iconWeb">&nbsp;</span></a>
			<a id="buttonForum" onclick="conversationByDate(null, 0, 'web_conversation_by_date', 2);" class="conversPop theTolltip" title="Forum" style="margin-right:10px;"><span class="iconForum">&nbsp;</span></a>
			<a id="buttonNews" onclick="conversationByDate(null, 0, 'web_conversation_by_date', 3);" class="conversPop theTolltip" title="News" style="margin-right:10px;"><span class="iconNews">&nbsp;</span></a>
			<a id="buttonEcommerce" onclick="conversationByDate(null, 0, 'web_conversation_by_date', 5);" class="conversPop theTolltip" title="Ecommerce" style="margin-right:10px;"><span class="iconEcommerce">&nbsp;</span></a>
			<a id="buttonCorporate" onclick="conversationByDate(null, 0, 'web_conversation_by_date', 0);" class="conversPop theTolltip" title="Corporate/Personal" style="margin-right:10px;"><span class="iconCorporate">&nbsp;</span></a>
		</div>
    </div>
    <div id="conversationPopup" class="content-popup" style="width:620px;">	
		<div style="width:620px;height:300px;text-align:center;padding-top:200px;background-color:#ffffff;"><img src="images/loader-med.gif"></div>
       
    </div><!-- .content-popup -->
    <div id="conversationPopupPaging" class="paging">
    </div><!-- .paging -->
</div><!-- #popup-sentiment -->