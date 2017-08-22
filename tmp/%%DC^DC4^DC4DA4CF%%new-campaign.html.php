<?php /* Smarty version 2.6.13, created on 2013-01-03 15:28:57
         compiled from smac/new-campaign.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'intval', 'smac/new-campaign.html', 19, false),array('function', 'encrypt', 'smac/new-campaign.html', 665, false),)), $this); ?>
<?php if ($this->_tpl_vars['total_topic'] == 0): ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_create_topic.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<script type="text/javascript" src="js/dynDateTime/jquery.dynDateTime.js"></script>
<script type="text/javascript" src="js/dynDateTime/lang/calendar-en.js"></script>

<script type="text/javascript" src="js/jquery.effects.core.js"></script>
<script type="text/javascript" src="js/jquery.effects.shake.js"></script>

<script type="text/javascript" src="js/topic.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="js/dynDateTime/css/calendar-system.css"  />
<link rel="stylesheet" type="text/css" media="all" href="css/tags.css"/>

<script type="text/javascript">
var maxMainKeyword = <?php echo $this->_tpl_vars['maxMainKeyword']; ?>
;
var maxRelatedKeyword = <?php echo $this->_tpl_vars['maxRelatedKeyword']; ?>
;
var maxTotalKeyword = <?php echo $this->_tpl_vars['totalKeyword']; ?>
;
var quota_limit = <?php echo ((is_array($_tmp=$this->_tpl_vars['quota_limit'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
;
var max_topic = <?php echo ((is_array($_tmp=$this->_tpl_vars['max_topic'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
;
var atype = <?php echo ((is_array($_tmp=$this->_tpl_vars['account_type'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
;
var f_focus = 'keyword1';
var n_rules = 0;
var n_rules_added = 0;
var n_compare = 0;
var n_compare_rule = 0;
var comparison = [];
campaign.addons = <?php echo $this->_tpl_vars['addons']; ?>
;
campaign.cost = <?php echo $this->_tpl_vars['cost']; ?>
;
campaign.account_type=<?php echo ((is_array($_tmp=$this->_tpl_vars['account_type'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
;
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.tagsinput.js"></script>
<?php echo '
<script type="text/javascript">
$(function(){
	var currentValue = $(\'#currentValue\');
	$("#slider").slider({ 
		max: 30,
		min: 0,
		slide: function(event, ui) {
			var daily = parseInt(topsy_estimate.d);
			var n_history = parseInt(ui.value);
			if(n_history<0){
				n_history=0;
			}
			$("#slider").attr("style","background:url(images/bg_slide.jpg) no-repeat "+(500*(ui.value/30))+"px 0;");
			currentValue.html(addCommas(Math.floor((ui.value)*daily)));
			var percent_quota = Math.round(((ui.value)*daily/quota_limit)*100);
			$("#note-quota").html("This is "+percent_quota+"% of your quota");
			$(\'#historical\').val(n_history);
		}
	});
});
function reactivate_tooltip(){
	//topltip
    $(".tip_trigger").hover(function(){
        tip = $(this).find(\'.tip\');
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
jQuery(document).ready(function($) {
	/*------------POP UP------------*/	
	jQuery("#localchecked").click(function(){
		jQuery("#globalchecked").removeAttr("checked");
		jQuery(".localmarket").removeAttr("disabled");
		jQuery("#marketcountry").fadeIn();
	});
	jQuery("#cspecific").click(function(){
		jQuery(".additionalSites").removeAttr("disabled");
		jQuery("#additionalbox").fadeIn();
		jQuery("#addMoreLink").fadeIn();
	});
	jQuery("#globalchecked").click(function(){
		jQuery("#marketcountry").hide();
		jQuery("#localchecked").removeAttr("checked");
		jQuery(".localmarket").attr("disabled","disabled");
	});
});

</script>
'; ?>

<div id="main-container">
    	<?php echo $this->_tpl_vars['sidebar']; ?>

        <div id="container" class="create-topic">   	
            <div class="title-bar" style="padding: 10px 15px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            	<tr>
                	<td align="left">
            			<h1><a href="#">Create New Topic </a> <span class="grey">  <!-- &gt; Topic Details--></span></h1>
            		</td>
                    <td align="right">
                    	<span id="cStep1" class="question circle circleSize3 white dInBlock bgGreen" onclick="javascript:campaign.goStep(1);" style="cursor:pointer;">1</span>
                        <span id="cStep2" class="question circle circleSize3 white dInBlock bgDarkGrey" onclick="javascript:campaign.goStep(2);" style="cursor:pointer;">2</span>
                                                <span id="cStep5" class="question circle circleSize3 white dInBlock bgDarkGrey" onclick="javascript:campaign.goStep(5);" style="cursor:pointer;">3</span>
                        <span id="cStep6" class="question circle circleSize3 white dInBlock bgDarkGrey" onclick="javascript:campaign.goStep(6);" style="cursor:pointer;">4</span>
                    </td>
                </tr>
            </table>
            </div>
            <form action="<?php echo $this->_tpl_vars['urladdcampaign']; ?>
" method="post">
            <div id="campaign" class="pad1015 step1" style="display:block;">
                <?php if ($this->_tpl_vars['err']): ?>
                <p style="color:#f00;"><strong><?php echo $this->_tpl_vars['err']; ?>
</strong></p>
                <?php endif; ?>    
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bgGrey round10 tableNewCampaign" style="margin: 10px 10px 10px 0; padding:0 20px 10px 20px;">
                    <tr>
                        <td colspan="2">
                            <h3>Define Your Topic</h3>
                            <p>The topic name or group you create will just serve as identifier for this topic within SMAC, it will not affect any keywords or the tracking process.</p>
                        </td>
                    </tr>
                    <tr>
                    	<td width="200" valign="top"><label>Topic Name*</label></td>
                        <td width="100%">
                            <input class="round5" type="text" name="name" value="" onfocus="javascript:this.value='';" />
                           <a href="#" class="helpsmall helpform theTolltip" title="This is how we identify your topic. Will not affect your data in any way.">&nbsp;</a>
                            <span class="errCampaignName messageError">Please fill topic name</span>
                        </td>
                    </tr>
                    <tr>
                    	<td valign="top" style="padding:15px 0 0 0; border:none;"><label>Topic Group*</label></td>
                        <td width="100%" style="padding:15px 0 0 0; border:none;">
                        	
                        	<select id="topicgroup" name="topicgroup">
                        		<option value="" selected="selected">Select Existing Group</option>
                        	</select>
                           <a href="#" class="helpsmall helpform theTolltip" style="margin:1px 0 0 13px;" title="You can create topic groups within SMAC to easily compare or group together topics most relevant to your business.">&nbsp;</a>
                            <span class="errTopicGroup messageError" style="margin:2px 0 0 10px;">Please choose the topic group</span>
                        </td>
                    </tr>
                    <tr>
                    <td width="200"><label>&nbsp;</label></td>
                        <td width="100%">
                          <a href="#?w=650&topic=1" rel="popup-new-group" class="poplight" id="create-group-btn">Create new group</a>
                        </td>
                    </tr>
                    <tr>
                    	<td valign="top"><label>Topic Description</label></td>
                        <td width="100%">
                            <textarea style="width:323px; padding:5px;" name="topicDesc" id="topicDesc"></textarea>
                        </td>
                    </tr>
                    <tr>
                    	<td valign="top"><label>Coverage</label></td>
                        <td width="100%">
                            <input type="checkbox" name="coverage" id="globalchecked" value="global" checked="checked"/><label class="checkboxLabel">Global</label>
                            <input type="checkbox" name="coverage" id="localchecked" value="local"/><label class="checkboxLabel">Local Market</label>
                            <span id='marketcountry' style="display:none;">
                            <input type="checkbox" id="cid" name="localmarket[]" value="id" disabled="disabled" class="localmarket" checked="true"/><label class="checkboxLabel">Indonesia</label>
                            <input type="checkbox" id="cmy" name="localmarket[]" value="my" disabled="disabled" class="localmarket"/><label class="checkboxLabel">Malaysia</label>
                            <input type="checkbox" id="csg" name="localmarket[]" value="sg" disabled="disabled" class="localmarket"/><label class="checkboxLabel">Singapore</label>
                            <input type="checkbox" id="cph" name="localmarket[]" value="ph" disabled="disabled" class="localmarket"/><label class="checkboxLabel">Philipines</label>
                            </span>
                            <a href="#" class="checkboxTip helpsmall theTolltip" title="Determine the coverage area from which the data will be obtained. "Local Market" can be specified to one of four countries available.">&nbsp;</a>
                        </td>
                    </tr>
                    <tr>
                    	<td valign="top"><label>Language(s)</label></td>
                        <td width="100%">
                        	<input type="radio" id="lang" name="lang" value="all" checked="checked"/><label class="checkboxLabel">Global</label>
                            <input type="radio" id="lang" name="lang" value="en"/><label class="checkboxLabel">English</label>
                            <input type="radio" id="lang" name="lang" value="id"/><label class="checkboxLabel">Bahasa Indonesia</label>
                            <input type="radio" id="lang" name="lang" value="msa"/><label class="checkboxLabel">Bahasa Melayu</label>
                            <input type="radio" id="lang" name="lang" value="ar"/><label class="checkboxLabel">Arabic</label>
                            <a href="#" class="checkboxTip helpsmall theTolltip" title="Choose which language(s) to include in the keyword search processing, based on your targeted locations.">&nbsp;</a>
                        </td>
                    </tr>
                    <tr>
                    	<td valign="top"><label>Channel(s)</label></td>
                        <td width="100%">
                            <input type="checkbox" id="ctwitter" name="channels[]" value="1" checked="checked"/><label class="checkboxLabel">Twitter</label>
                            <input type="checkbox" id="cweb" name="channels[]" value="3"/><label class="checkboxLabel">Website & Forums</label>
                            <input type="checkbox" id="cfb" name="channels[]" value="2"/><label class="checkboxLabel">Facebook Public Pages</label>
                            <input type="checkbox" id="cspecific" name="channels[]" value="4"/><label class="checkboxLabel">Additional Sites</label>
                            <a href="#" class="checkboxTip helpsmall theTolltip" title="Capture the online conversations taking place on chosen social channel(s) to use in generating the analytic data.">&nbsp;</a>
							<div class="row">
                                <span class="additionalbox" id="additionalbox" style="display:none;">
                                    <p class="rowInput"><input class="round5 additionalSites" type="text" id="site1" name="a_site[]" value="http://www.websitename.com" onfocus="javascript:this.value='';"  disabled="disabled"/></p>
                                </span>
                                <span class="addMore" id="addMoreLink" style="display:none;"><a href="javascript:void(0);" onclick="additionalSitesField();">+</a></span>
                                                            </div>
                        </td>
                    </tr>
                    <tr>
                    	<td valign="top"><label>Twitter Account</label></td>
                        <td width="100%">
                            <input class="round5" type="text" name="twitter_account" value="" onfocus="javascript:this.value='';" />
                           <a href="#" class="helpsmall helpform theTolltip" title="SMAC can also track and analyze your official twitter account. You will need to authorize SMAC to use this account to manually or automatically reply.">&nbsp;</a>
                            <span class="errOfficialTwitter messageError"><?php echo $this->_tpl_vars['errOfficialTwitter']; ?>
</span>
                            <p class="sample">e.g. @username</p>
                        </td>
                    </tr>
                    <tr>
                    	<td valign="top"><label>Facebook Account</label></td>
                        <td width="100%">
                            <input class="round5" type="text" name="fb_account" value="" onfocus="javascript:this.value='';" />
                            <a href="#" class="helpsmall helpform theTolltip" title="SMAC can also track and analyze your official facebook account. You will need to authorize SMAC to use this account to manually or automatically reply.">&nbsp;</a>
                            <span class="errOfficialTwitter messageError"><?php echo $this->_tpl_vars['errOfficialFacebook']; ?>
</span>
                            <p class="sample">e.g. http://www.facebook.com/username</p>
                        </td>
                    </tr>
                    <tr>
                    	<td valign="top"><label>Topic Date*</label></td>
                        <td width="100%">
                            <input class="round5" type="text" name="start" value="-" readonly="readonly" />
                            <input class="round5" type="hidden" name="end" value=""/>
                           <a href="#" class="helpsmall helpform theTolltip" title="Tell us the date to start this topic">&nbsp;</a>
                            <span class="errCampaignDate messageError"><?php echo $this->_tpl_vars['errCampaignDate']; ?>
</span>
                        </td>
                    </tr>
                    <tr>
                    	<td align="right" colspan="2" style="border:none;"><i>*Fields are mandatory</i></td>
                    </tr>
                </table>
                <input type="hidden" name="geo" id="geo" value="all"/>
                
                <!-- <input id="cSubmit1" class="next-form" type="button" value="Next" onclick="javascript:campaign.goStep(2);" /> -->
                <input id="cSubmit1" class="next-form" type="button" value="Next" onclick="javascript:step1_init();" />
            
            </div> <!-- . STEP 1 -->
           	<div id="campaign" class="pad1015 step2 tableNewCampaign" style="display:none;">
                <table id="stdform" width="100%" border="0" cellspacing="0" cellpadding="0" class="bgGrey round10" style="margin: 10px 10px 10px 0; padding:10px 20px 10px 20px;">
                    <tr>
                        <td colspan="3">
                            <h3>Additional Keywords</h3>
							<p>SMAC will automatically generate your keywords into a specific rule, with maximum <span id="txtmaxclauses">10</span> keywords per rule.</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border:none; padding:0;">
                        <span class="errKeyword4 fLeft" style="display:none;color: red;margin-top: 10px; padding:15px 0;">You should create at least 1 rule.</span>
                        </td>
                    </tr>
                    <tr>
                    	<td width="200" valign="top"><label>Label</label></td>
                        <td width="320" valign="top">
                            <input id="label" class="round5" type="text" name="label" style="width:320px" value="" maxlength="50"/>
                            <p class="sample">Name will not affect any keywords or the tracking process</p>
                        </td>
                        <td width="300" rowspan="5" valign="top" style="padding:15px;">
                        
                            <input type="button" id='btnkw1' value='Suggest Related Keywords'/>
                            <a href="#" class="helpsmall helpform theTolltip" title="View the keywords recommendation refering to the main keyword that you might have missed.">&nbsp;</a>
                            <div id="keyword-suggestions">
                                <div id="wordcloud-box" class="round5" style="position:relative; width:358px;">
                                     <div id="wordcloud" class="wordcloud" style="width:100%;display:table; clear:both;">
                                            <?php echo $this->_tpl_vars['wordcloud']; ?>

                                     </div>
                                </div>	
                            </div>
                        </td>
                    </tr>
                    <tr>
                    	<td width="200" valign="top"><label>All of these words</label></td>
                        <td width="320" valign="top">
                            <input id="keyword1" class="round5" type="text" name="keyword1" style="width:300px" value=""/>
                            <a href="#" class="helpsmall helpform theTolltip" title="The main keyword (or exact sentence) will always be included in the tracking process and determines the outline of the monitored topic.">&nbsp;</a>
                            <p class="sample">Your main keyword should be in one word or exact sentence</p>
                        </td>
                    </tr>
                    <tr>
                    	<td width="200" valign="top"><label>Any of these words</label></td>
                        <td width="320" valign="top">
                            <input id="keyword2" class="round5" type="text" name="keyword2" style="width:300px" value=""/>
                           <a href="#" class="helpsmall helpform theTolltip" title="Add more sub-keywords as the extentions to your main keyword to expand the monitoring result.">&nbsp;</a>
                            <p class="sample">Separate keywords with comma</p>
                        </td>
                    </tr>
                    <tr>
                    	<td width="200" valign="top"><label>Not these words</label></td>
                        <td width="320" valign="top">
                            <input id="keyword3" class="round5" type="text" name="keyword3" style="width:300px" value=""/>
                           <a href="#" class="helpsmall helpform theTolltip" title="Specify keywords to exclude from the tracking process to maintain topic relevance.">&nbsp;</a>
                            <p class="sample">Separate keywords with comma</p>
                        </td>
                    </tr>
                   
                    <tr>
                    	<td width="200" valign="top"></td>
                        <td width="320" valign="top">
							<a href="javascript:void(0);" class="advance-keyword btnAdvance">Advance Rule</a>
                        	<a href="javascript:void(0);" class="submit-keyword">Submit Keywords</a>                      	
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div id="ruleset">
                                
                            </div>
                            <input type="hidden" name="n_rules" id="n_rules" value="0"/>
                            <table width="100%" id="tblrules" class="roundgreen-table">
                                <thead>
                                    <tr>
                                       <th width="20" align="center">No</th><th align="center">Label</th> <th>All of these</th><th>Any of these</th><th>Exclude these</th>
                                    </tr>
                                </thead>
                                    <tr id="sampleRule">
                                      <td align="center"><span>1</span></td>
                                      <td><input type="hidden" value="Sample" >Sample</td>
                                      <td class="key1"><input type="text" value="Coffee" style="display: none;">
                                        <div class="tagsinput" style="width: auto;">
                                          <span class="tag"><span>Coffee&nbsp;&nbsp;</span><a href="#" title="Removing tag">x</a></span>
                                          <div id="">
                                            <input data-default="add a tag" value="" id="">
                                          </div>
                                          <div class="tags_clear"></div>
                                        </div></td>
                                      <td class="key2"><input type="text" value="Good,Bad,Morning" style="display: none;">
                                        <div class="tagsinput" id="" style="width: auto;">
                                            <span class="tag"><span>Good&nbsp;&nbsp;</span><a href="#" title="Removing tag">x</a></span>
                                            <span class="tag"><span>Bad&nbsp;&nbsp;</span><a href="#" title="Removing tag">x</a></span>
                                            <span class="tag"><span>Morning&nbsp;&nbsp;</span><a href="#" title="Removing tag">x</a></span>
                                          <div>
                                            <input data-default="add a tag" value="" id="">
                                          </div>
                                          <div class="tags_clear"></div>
                                        </div>
                                      </td>
                                      <td class="key3"><input type="text" value="Maker,Taste" style="display: none;">
                                        <div class="tagsinput" style="width: auto;">
                                        <span class="tag"><span>Maker&nbsp;&nbsp;</span><a href="#" title="Removing tag">x</a></span>
                                        <span class="tag"><span>Taste&nbsp;&nbsp;</span><a href="#" title="Removing tag">x</a></span>
                                          <div>
                                            <input data-default="add a tag" value=""/>
                                          </div>
                                          <div class="tags_clear"></div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr id="sampleRuleFoot" class="foot">
                                       <td colspan="6">
                                       	<span>Your specific rule for Sample will contain words: "Coffee Good" and "Coffee Bad" and "Coffee Morning" without "Maker" and "Taste" in post.</span>
                                        <a class="deleteRule" href="#">Delete</a>
                                        <a class="editRule" href="#">Edit</a>
                                       </td>
                                    </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="3" align="right" style="border:none;"><i>*You can add up to 5 additional rules</i></td>
                    </tr>
                </table>
                <table id="advform" width="100%" border="0" cellspacing="0" cellpadding="0" class="bgGrey round10" style="margin: 10px 10px 10px 0; padding:10px 20px 10px 20px;display:none;">
                    <tr>
                        <td colspan="3">
                            <h3>Advance Rules</h3>
							<p>SMAC will automatically generate your keywords into a specific rule, with maximum <span id="txtmaxclauses2">10</span> keywords per rule.</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border:none; padding:0;">
                        <span class="errKeyword4 fLeft" style="display:none;color: red;margin-top: 10px; padding:15px 0;">You should create at least 1 rule.</span>
                        </td>
                    </tr>
                    <tr>
                    	<td width="200" valign="top"><label>Label</label></td>
                        <td width="320" valign="top">
                            <input id="label2" class="round5" type="text" name="label2" style="width:320px" value="" maxlength="50"/>
                            <p class="sample">Name will not affect any keywords or the tracking process</p>
                        </td>
                    </tr>
                   
                    <tr>
                    	<td width="200" valign="top"><label>Custom Rules</label></td>
                    	  <td width="320" valign="top">
                            <input id="keywordcustom" class="round5" type="text" name="keywordcustom" style="width:300px" value="cheap (food OR drink)"/>
                           <a href="#" class="helpsmall helpform theTolltip" title='Examples : <br/>good foods<br/>"Pacific Place"<br/>good OR bad<br/>cafe (jakarta OR Singapore)<br/>'>&nbsp;</a>
                            <p class="sample custom">These will match : cheap food OR cheap drink</p>
                            <p class="sample customclauses">Total Keywords : 3</p>
                        </td>
                    </tr>
                    <tr>
                    	<td width="200" valign="top"></td>
                        <td width="320" valign="top">
							<a href="javascript:void(0);" class="standard-keyword btnBasic">Basic Rules</a>
                        	<a href="javascript:void(0);" class="submit-keyword">Submit Keywords</a>                 	
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div id="ruleset">
                                
                            </div>
                            <input type="hidden" name="n_rules2" id="n_rules2" value="0"/>
                            <table width="100%" id="tblrules2" class="roundgreen-table">
                                <thead>
                                    <tr>
                                       <!--<th width="20" align="center">No</th>--><th align="center">Label</th> <th>Rule</th>
                                    </tr>
                                </thead>
                                    <tr id="sampleRule">
                                      <!--<td align="center"><span>1</span></td>-->
                                      <td><input type="hidden" value="Sample" >Sample</td>
                                      <td class="key1"><input type="text" value="Coffee" style="display: none;">
                                        <div class="tagsinput" style="width: auto;">
                                          <span class="tag"><span>Coffee&nbsp;&nbsp;</span><a href="#" title="Removing tag">x</a></span>
                                          <div id="">
                                            <input data-default="add a tag" value="" id="">
                                          </div>
                                          <div class="tags_clear"></div>
                                        </div></td>
                                      
                                    </tr>
                                    <tr id="sampleRuleFoot" class="foot">
                                       <td colspan="6">
                                       	<span>Your specific rule for Sample will contain words: "Coffee Good" and "Coffee Bad" and "Coffee Morning" without "Maker" and "Taste" in post.</span>
                                        <a class="deleteRule" href="#">Delete</a>
                                        <a class="editRule" href="#">Edit</a>
                                       </td>
                                    </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="3" align="right" style="border:none;"><i>*You can add up to 5 additional rules</i></td>
                    </tr>
                </table>
                <input type="hidden" name="geo" id="geo" value="all"/>
                <a onclick="javascript:campaign.goStep(1);" class="prev-form">Previous</a>
                <input id="cSubmit1" class="next-form" type="button" value="Next" onclick="javascript:campaign.goStep(5);" />      
            </div> <!-- . STEP 2 -->
           	
           	<div id="campaign" class="pad1015 step3" style="display:none;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bgGrey round10" style="margin: 10px 10px 10px 0; padding:0 20px 10px 20px;">
                    <tr>
                        <td colspan="3" class="subtitle">
                            <h3>Historical Data</h3>
							<span>Do you want to pull historical data on these keywords? Use the slider below to get an estimate of how many lines each time period will consume. This data will be added to your topic quota.</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                    	<td style="width:500px" valign="top">
                        	<div id="slder-historical">
                                <div id="slider" style="background:url(images/bg_slide.jpg) no-repeat 0px 0;">
                                </div>
                            </div>
                            <div id="slider-caption">
                            	<span class="cap1">1</span>
                            	<span class="cap7">7</span>
                            	<span class="cap30">30</span>
                            </div>
                        </td>
                        <td valign="top">
                        	<div id="estimate">
                        	    <h3>Estimated Volume*</h3>
                                <div id="estimate-value">
                                    <span id="currentValue">0</span>
                                    <p class="note" id="note-quota">This is 0% of your quota</p>
                                </div>
                           	    <i>*Volumes are only estimates, actually line consumption may vary</i>
                            </div>
                        
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="historical" id="historical" value="0"/>
                <input type="hidden" name="geo" id="geo" value="all"/>
                <a onclick="javascript:campaign.goStep(2);" class="prev-form">Previous</a>
                <input id="cSubmit1" class="next-form" type="button" value="Next" onclick="javascript:campaign.goStep(5);" />      
            </div> <!-- . STEP 3 -->
           	
           	<div id="campaign" class="pad1015 step4" style="display:none;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bgGrey round10 comparison" style="margin: 10px 10px 10px 0; padding:20px 20px 35px 20px;">
                    <tr>
                        <td colspan="3" class="subtitle">
                            <h3>Create a Comparison (Optional)</h3>
                              <span>You can run a direct comparison on your competitors, industry, etc. Enter the brand name of your competitor(s) below, SMAC will automatically copy all keywords to the competitor's topic and replace the brand with what you specify below.</span>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="3">
                            <div id="cmpcontainer" class="rows">
                            
                            </div>
                            <input type="hidden" name="comp_topics" id="comp_topics" value=""/>
                            <input type="hidden" name="comp_brands" id="comp_brands" value=""/>
                            <div class="rows">
                                <label>Enter Comparison Topic</label>
                                <div id="add-topic">
                                    <div class="comparisonTopics">
                                       <input id="comparetopic" class="round5" type="text" name="comparetopic" style="width:320px"/>
                                       <a href="#" class="helpsmall helpform theTolltip">&nbsp;<span class="tip">Donec ullamcorper nulla non metus auctor fringilla.<br />
                                         Nulla vitae elit libero,<strong> a pharetra augue.</strong></span></a>
                                        <span class="errCampaignName messageError">Please fill topic name</span>
                                       
                                    </div>
                                    
                                </div>
                                 <label>Enter Comparison Brand</label>
                                <div id="add-topic">
                                    <div class="comparisonTopics">
                                       <input id="comparebrand" class="round5" type="text" name="comparebrand" style="width:320px"/>
                                       <a href="#" class="helpsmall helpform theTolltip">&nbsp;<span class="tip">Donec ullamcorper nulla non metus auctor fringilla.<br />
                                         Nulla vitae elit libero,<strong> a pharetra augue.</strong></span></a>
                                        <span class="errCampaignName messageError">Please fill brand name</span>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="add-topic">Add Topic</a>
                        </td>
                    </tr>
                </table>
			   <textarea name="keywords" style="display:none;"></textarea>
                <a onclick="javascript:campaign.goStep(3);" class="prev-form">Previous</a>
                <input id="cSubmit1" class="next-form" type="button" value="Next" onclick="javascript:selectRelated3();" />      
            </div> <!-- . STEP 4 -->
           	
           	<div id="campaign" class="pad1015 step5" style="display:none;"> 
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bgGrey round10 tableNewCampaign" style="margin:0 0 40px 0; padding:0 20px 10px;">
                      <tr>
                       <td colspan="3">
                            <h3>We're Almost Done!</h3>
                            <p>Make sure you check all the fields before proceeding to the next step. Please re-confirm your keywords selection carefully.</p>
                        </td>
                      </tr>
                    <tr>               	                   	                  	
                        <td style="width:200px;"><h4>Topic name</h4></td><td>:</td><td><span id="co-name"></span></td>
                   </tr>
                   <tr>              	                   	                  	
                        <td style="width:200px;"><h4>Topic Description</h4></td><td>:</td><td><span id="co-desc"></span></td>
                   </tr>
                   <tr>               	                   	                  	
                        <td style="width:200px;"><h4>Coverage</h4></td><td>:</td><td><span id="co-geo"></span></td>
                   </tr>
                   <tr>          	                   	                  	
                        <td style="width:200px;"><h4>Language</h4></td><td>:</td><td><span id="co-lang"></span></td>
                   </tr>
                   <tr>
                        <td><h4>Channels</h4></td><td>:</td><td><span id="co-twitter">Twitter, </span><span id="co-facebook">Facebook Public Pages, </span><span id="co-blog">Website &amp; Forums</span></td>
                  </tr>
                    <tr> 
                        <td valign="top"><h4>Twitter Account</h4></td><td valign="top" style="padding: 15px 0 0 0;">:</td><td style="padding: 15px 0;"><span id="co-officialTwitter"></span></td>
                  </tr>	
                   <tr> 
                        <td valign="top"><h4>Facebook Account</h4></td><td valign="top" style="padding: 15px 0 0 0;">:</td><td style="padding: 15px 0;"><span id="co-officialFB"></span></td>
                  </tr>	
                   <tr>
                        <td><h4>Additional Sites</h4></td><td>:</td><td><span id="co-additionalweb">-</span></td>
                  </tr>
                    <tr> 
                        <td><h4>Topic Date</h4></td><td>:</td><td><span id="co-start"></span></td>
                   </tr>
                    <tr>              	                   	                  	
                        <td style="width:200px;"><h4>Keywords</h4></td><td>:</td><td><span id="co-keyword"></span></td>
                   </tr>
                   <tr>              	                   	                  	
                        <td style="width:200px; border:none;"><h4>Historical Data</h4></td><td style="border:none;">:</td><td style="border:none;"><span id="co-historical"></span></td>
                   </tr>
                                   </table>
                <p style="float:right;">
                <i>You will be charged on the next page. If you have a corporate contract with us, please prepare your credit vouchers.</i>
                </p>
                <input type="hidden" name="geo" id="geo" value="all"/>
                <a onclick="javascript:campaign.goStep(2);" class="prev-form">Previous</a>
                <input id="cSubmit1" class="next-form" type="button" value="Next" onclick="javascript:campaign.goStep(6);" />      
            </div> <!-- . STEP 5 -->
           	
           	<div id="campaign" class="pad1015 step6" style="display:none;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bgGrey round10 tableNewCampaign" style="margin: 10px 10px 10px 0; padding:0 20px 10px 20px;">
                    <tr>
                        <td colspan="10">
                            <h3>Settle Payment & Activate Topic!</h3>
                            <p>Make sure your credit is ready.  We accept online payments by PayPal & major credit cards. You can also enter your SMAC Credit codes in the field below to activate this topic.</p>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="10">
                        	<div id="payment-table">
                                <table width="100%" border="0" cellspacing="2" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th align="left" style="text-align:left;">Subscriptions</th>
                                            <th align="center" style="text-align:center;" width="100" title="Monthly Subscription Fee ">Price (USD)</th>
                                            <th align="center" style="text-align:center;" width="30"></th>
                                        </tr>
                                    </thead>
                                    <tbody id='payment-table-body'>
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                    						<td colspan="4"><p><span>This will cost USD</span><strong><span id="total_cost2">0</span></strong></p></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="10" style="border:none; padding:15px 0 0 0;"><label style="margin:0;">Choose your payment method:</label></td>
                    </tr>
                    <tr>
                    	<?php if ($this->_tpl_vars['account_type'] <> '0'): ?>
                    	<td style="width:30px; border:none;"><input name='payment' type="radio" value="paypal" checked="checked"/></td>
                    	<td style="width:120px; border:none;"><img src="images/paypal.jpg"></td>
                    	<?php else: ?>
                    	<td style="width:30px; border:none;"><input name='payment' type="radio" value="paypal" disabled="true"/></td>
                    	<td style="width:120px; border:none;"><img src="images/paypal.jpg"></td>
                    	<td style="width:30px; border:none;"><input name='payment' type="radio" value="trial" checked="checked"/></td>
                    	<td style="border:none;"><label>Trial Account</label></td>
                    	<?php endif; ?>
                    </tr>
                </table>
                    <a onclick="javascript:campaign.goStep(5);" class="prev-form">Previous</a>
                        <input type="hidden" name="add" value="1"/>
                        <input type="hidden" name="method" value="1"/>
                        <input id="cSubmit1" type="submit" value="CREATE TOPIC" />  
            </div> <!-- . STEP 6 -->
             
                                  	
           	</form>
           	
        </div><!-- #container -->
    </div><!-- #main-container -->
    
    <script type="application/javascript">
	var urlx = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=campaign&act=getrelatedkeywords&ajax=1'), $this);?>
";
	var urlestm = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=campaign&act=estimate_from_topsy&ajax=1'), $this);?>
";
    <?php echo '
	$(".submit-keyword").click(function(){
		if(!is_advance){
			if($("#keyword1").val().length>0&&$("#label").val().length>0){
				$("#sampleRule").hide();
				$("#sampleRuleFoot").hide();
				refresh_no();
				if(n_rules_added<5){
					var str = topic_check_keywords(null,\'keyword1\',\'keyword2\',\'keyword3\',\'lang\');
					var n_clauses = clauses_count(str);
					if(n_clauses<=topic_data.max_clauses){
						var ruleset = topic_rules_str(str,$("#label").val());
						var _main_keywords = $(\'#keyword1\').val();
						var str="<tr id=\\"t_ruleall"+n_rules+"\\"><td align=\'center\'><span id=\'no_"+n_rules+"\'>0</span></td>";
						str+="<td><input type=\'hidden\' id=\'rulelabel"+n_rules+"\' name=\'rulelabel"+n_rules+"\' value=\'"+$(\'#label\').val()+"\'/>"+$(\'#label\').val()+"</td>";
						str+="<td class=\'key1\'><input type=\'text\' id=\'ruleall"+n_rules+"\' name=\'ruleall"+n_rules+"\' value=\'"+_main_keywords+"\'/></td>";
						str+="<td class=\'key2\'><input type=\'text\' id=\'ruleany"+n_rules+"\' name=\'ruleany"+n_rules+"\' value=\'"+rule_sequences($(\'#keyword2\').val())+"\'/></td>";
						str+="<td class=\'key3\'><input type=\'text\' id=\'ruleexc"+n_rules+"\' name=\'ruleexc"+n_rules+"\' value=\'"+rule_sequences($(\'#keyword3\').val())+"\'/></td></tr>";
						str+="<tr id=\'foot"+n_rules+"\' class=\'foot\'><td  colspan=\'6\'><span id=\\"ruletip"+n_rules+"\\">"+ruleset+"</span>";
						str+="<a class=\'deleteRule\' href=\'#\' onclick=\'remove_rule("+n_rules+");return false;\'>Delete</a>";
						str+="<a class=\'editRule\' href=\'#\' onclick=\'edit_rule("+n_rules+",\\""+$("#label").val()+"\\")\'>Edit</a></td></tr>";
						
						$(\'#tblrules\').append(str);
						
						$(\'#ruleall\'+n_rules).tagsInput({width:\'auto\',onChange:onChangeTag,onRemoveTag:onRemoveTag});
						$(\'#ruleany\'+n_rules).tagsInput({width:\'auto\',onChange:onChangeTag,onRemoveTag:onRemoveTag});
						$(\'#ruleexc\'+n_rules).tagsInput({width:\'auto\',onChange:onChangeTag,onRemoveTag:onRemoveTag});
						n_rules++;
						$("#n_rules").val(n_rules);
						refresh_no();
						reactivate_tooltip();
						$("#label,#keyword1,#keyword2,#keyword3").val(\'\');
					}else{
						//alert("you can only have maximum 10 clauses per rule");
						popup_msg(\'Warning\',en[\'topic_clauses_exceeded\'],null,null);
					}
					
				}
			}
		}else{
			var adv_rules = analyze_advance_rule();
			if(n_rules_added<5&&adv_rules.total_clauses<=topic_data.max_clauses){
				$("#advform #sampleRule").hide();
				$("#advform #sampleRuleFoot").hide();
				var matchstr = "";
				$.each(adv_rules.rulesets,function(k,v){
					if(k>0){
						matchstr += \' or \';
					}
					matchstr += \'"\'+v+\'"\';
				});
				var str="<tr id=\\"t_ruleall2"+n_rules+"\\"><!--<td align=\'center\'><span id=\'no_"+n_rules+"\'>0</span></td>-->";
				str+="<td><input type=\'hidden\' id=\'rulelabel2"+n_rules+"\' name=\'rulelabel2"+n_rules+"\' value=\'"+$(\'#label2\').val()+"\'/>"+$(\'#label2\').val()+"</td>";
				str+="<td class=\'key1\'><input type=\'text\' id=\'ruleall2"+n_rules+"\' name=\'ruleall2"+n_rules+"\' value=\'"+$("#keywordcustom").val()+"\'/></td>";
				str+="</tr>";
				str+="<tr id=\'foot2"+n_rules+"\' class=\'foot\'><td  colspan=\'6\'><span id=\\"ruletip2"+n_rules+"\\">"+matchstr+"</span>";
				str+="<a class=\'deleteRule\' href=\'#\' onclick=\'remove_rule2("+n_rules+");return false;\'>Delete</a>";
				//str+="<a class=\'editRule\' href=\'#\' onclick=\'edit_rule("+n_rules+",\\""+$("#label").val()+"\\")\'>Edit</a></td></tr>";
				
				$(\'#tblrules2\').append(str);
				$(\'#ruleall2\'+n_rules).tagsInput({width:\'auto\',onChange:onChangeTag,onRemoveTag:onRemoveTag2});
				n_rules++;
				n_rules_added++;
				$("#n_rules2").val(n_rules);
			}else if(adv_rules.total_clauses>topic_data.max_clauses){
				popup_msg(\'Warning\',en[\'topic_clauses_exceeded\'],null,null);	
			}else{}
		}
		
	});

	//add comparison topic
	$(".add-topic").click(function(){
		var topicname = $("#comparetopic").val();
		var topicbrand = $("#comparebrand").val();
		var ruledata = get_rule_data();
		if(topicname.length>0&&topicbrand.length>0){
			comparison.push({\'topic\':topicname,\'brand\':topicbrand});
			var strTopic = "";
			var strBrand = "";
			for(var i in comparison){
				if(i>0){
					strTopic+=",";
					strBrand+=",";
				}
				strTopic+=comparison[i].topic;
				strBrand+=comparison[i].brand;
			}
			
			$(\'#comp_topics\').val(strTopic);
			$(\'#comp_brands\').val(strBrand);
			
			$(\'#cmpcontainer\').append(tbl);
			var tbl_id = Math.round(Math.random()*999999);
			var tbl = compare_rule_table(tbl_id,topicname.toString(),topicbrand.toString(),n_compare);
			$(\'#cmpcontainer\').append(tbl);
			for(var n in ruledata){
				var rule_id = n_compare+"_"+n;
				var rule1 = topicbrand;
				var rule2 = "";
				var rule3 = "";
				try{rule1 += ","+ruledata[n][0];}catch(e){}
				try{rule2 = ruledata[n][1];}catch(e){}
				try{rule3 = ruledata[n][2];}catch(e){}
				var ruleset="lorem ipsum";
				var str="<tr id=\\"t_ruleall"+rule_id+"\\"><td><span id=\'no_"+rule_id+"\'>"+(parseInt(n)+1)+"</span></td><td class=\'key1\'><input type=\'text\' id=\'ruleall"+rule_id+"\' name=\'ruleall"+rule_id+"\' value=\'"+rule1+"\'/></td><td class=\'key2\'><input type=\'text\' id=\'ruleany"+rule_id+"\' name=\'ruleany"+rule_id+"\' value=\'"+rule2+"\'/></td><td class=\'key3\'><input type=\'text\' id=\'ruleexc"+rule_id+"\' name=\'ruleexc"+rule_id+"\' value=\'"+rule3+"\'/></td><td><a class=\'helpsmall helpform theTolltip\' href=\'#\' title=\'"+ruleset+"\'>&nbsp;</a></td></tr>";
				$(\'#tblcompare\'+n_compare).append(str);
				$(\'#ruleall\'+rule_id).tagsInput({width:\'auto\',onChange:onChangeTag,onRemoveTag:onRemoveTag});
				$(\'#ruleany\'+rule_id).tagsInput({width:\'auto\',onChange:onChangeTag,onRemoveTag:onRemoveTag});
				$(\'#ruleexc\'+rule_id).tagsInput({width:\'auto\',onChange:onChangeTag,onRemoveTag:onRemoveTag});
				
			}
			n_compare++;
			$("#n_compare").val(n_compare);
		}
	});

	
	$("#btnkw1").click(function(){
		var str = to_topsy_query(null,\'keyword1\',\'keyword2\',\'keyword3\',\'lang\');
		wordcloud_reload(str);
	});
	$("#keyword1").focus(function(){
		f_focus = "#keyword1";
	});
	$("#keyword2").focus(function(){
		f_focus = "#keyword2";
	});
	$("#keyword3").focus(function(){
		f_focus = "#keyword3";
	});
	
	
	topic_load_groups(0);


	$("#keywordcustom").keyup(function(){
		var adv_rules = analyze_advance_rule();
		var matchstr = "";
		$.each(adv_rules.rulesets,function(k,v){
			if(k>0){
				matchstr += \' or \';
			}
			matchstr += \'"\'+v+\'"\';
		});
		$("p.custom").html(\'Any feed contains : \'+matchstr);
		$("p.customclauses").html(\'Total Keywords : \'+adv_rules.total_clauses);
	});
   	
   	$(".advance-keyword").click(function(){
   		$("#stdform").hide();
		$("#advform").fadeIn();
		is_advance = true;
   	});
   	$(".standard-keyword").click(function(){
   		$("#advform").hide();
		$("#stdform").fadeIn();
		is_advance = false;
   	});
   	
   	//campaign.goStep(2);
    '; ?>

   
    </script>