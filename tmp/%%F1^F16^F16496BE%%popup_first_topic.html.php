<?php /* Smarty version 2.6.13, created on 2012-09-18 18:13:57
         compiled from smac/popup_first_topic.html */ ?>
<div id="popupGetStarted" class="popupStarted">
	<div id="popupStep1" class="popupContainer">
        <div class="popupHead">
        	<h1>Welcome to SMAC, <?php echo $this->_tpl_vars['username']; ?>
!</h1>
            <a class="popupClose" href="#">X</a>
        </div>
        <div class="popupContent">
        	<h1>SMAC is designed to extract the sentiment values from online conversations.<br />
				But that's not all. There's a lot of things you can do with it.</h1>
            <img src="images/getstarted_img2.png" />
            <p>Using SMAC is easy. Start with creating your first Topic.</p>
        </div>
        <div class="popupFoot">
        	<div class="chekPop">
            	<input type="checkbox" /><label>Don't show tips again</label>
            </div>
            <a id="btnNextPopup" class="btnPopup" href="#">NEXT</a>
        </div>
    </div>
	<div id="popupStep2" class="popupContainer" style="display:none">
        <div class="popupHead">
        	<h1>Create Your First Topic</h1>
            <a class="popupClose" href="#">X</a>
        </div>
        <div class="popupContent">
            <img src="images/getstarted_img3.png" />
        </div>
        <div class="popupFoot">
        	<div class="chekPop">
            	<input type="checkbox" /><label>Don't show tips again</label>
            </div>
            <a id="btnCreateNewTopic" class="btnGreenBar" href="<?php echo $this->_tpl_vars['urlnewcampaign']; ?>
">CREATE NEW TOPIC</a>
        </div>
    </div>
</div>
<div id="bgPopup"></div>