<?php /* Smarty version 2.6.13, created on 2013-01-03 15:28:58
         compiled from smac/popup_unmark_tweet.html */ ?>
<div id="popup-unmark" class="popup_block popupWidth popup-unmark">
	<div id="section1">
        <div class="headpopup">
            <h1 class="fleft">
            <span id='wf_total_unmarked'>Searching for</span> unmarked tweets for keyword: <span class="fgreen" id="wf_keyword"></span>
            </h1>
            <div id="markfolder" class="fright">
                
            </div>
        </div>
        <div class="content-popup">
            <div class="contentdivs">
            </div>
            <div id="wf_paging" class="paging">
            	            </div><!-- .paging -->
        </div><!-- .content-popup -->
   	</div><!-- #section1 -->
	<div id="section2" style="display:none">
        <div class="headpopup">
        	<div class="content-left">
                <h1>Choose Folder:</h1>
                <p></p>
            </div><!-- .content-left -->
        	<div class="content-right">
                <a class="backbtn" href="#section1" title="Back" onclick="wf_popup_back();">&nbsp;</a>
                <a class="newfolder" href="#new-folder" title="New Folder">&nbsp;</a>
            </div><!-- .content-left -->
        </div>
        <div class="content-popup">
                <div id="new-folder" class="list" style="display:none">
                   <form class="add-new-folder">
                   <textarea id="wf_new_folder_txt"></textarea>
                   <input type="button" value="Add New Folder" onclick="wf_add_folder();"/>
                   <input type="button" value="Cancel" />
                   </form>
                </div>
                <div id="wf_folder_list">
               
                </div>
               
        </div><!-- .content-popup -->
   	</div><!-- #section2 -->
</div><!-- #popup-sentiment -->