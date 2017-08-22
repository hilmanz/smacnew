<?php /* Smarty version 2.6.13, created on 2012-10-01 16:48:33
         compiled from smac/kol.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/kol.html', 8, false),)), $this); ?>

<div id="main-container">
    	<?php echo $this->_tpl_vars['sidebar']; ?>

        <div id="container">
    		<?php echo $this->_tpl_vars['menu']; ?>

            <div class="title-bar">
                <h1><?php if ($this->_tpl_vars['market']): ?><span id='txtmarket'>Market Key Opinion Leader - <?php echo $this->_tpl_vars['market']; ?>
</span><?php else: ?><a href="<?php echo $this->_tpl_vars['urlkeyopinion']; ?>
">Key Opinion Leader</a><?php endif; ?> 
         	    <?php if ($this->_tpl_vars['market']): ?> <a id='btnglobaldata' href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=toggle_geo'), $this);?>
">Switch to Global Data</a><?php endif; ?></h1>
            </div>
            <div class="title-bar">
            	<div id="tabKol">
                	<a id="buttonTwitter" href="#tabTwitter"><span class="iconTwitter">&nbsp;</span></a>
                	<a id="buttonFacebook" href="#tabFacebook"><span class="iconFacebook">&nbsp;</span></a>
                	<a id="buttonWeb" href="#tabWeb"><span class="iconWeb">&nbsp;</span></a>
                </div>
				<div id="mainKOL">
					<div class="filters"><input type="checkbox" id="exclude1" onchange="KOLDataCollectionToggle();"/>Exclude News &amp; Corporate Accounts</div>
				</div>
				<div id="detailKOL">
					<a href="#" class="smallArrow">Back</a>
				</div>
			</div>
            <div id="key-opinion">
                <?php if ($this->_tpl_vars['data_available']): ?>
            	<div id="tabTwitter" class="pageContent">
                    <div class="titles">
                        <h3>Top 10 People</h3>
                        <div id="idTwitExcludeKOL" class="wfilters">
                            <label>Exclude By</label>
                            <select name="d" id="twitExcludeKOL">
								<option value="1">News</option>
								<option value="2">Corporate Accounts</option>
							</select>
                        </div>
                    </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="w200" valign="top" align="left">
                            <ul class="tabs">
                                <li><a id="twitKOL" href="#tab-Post">Top 10 KOL</a></li>
                                                                <li><a id="twitAllDT" href="#tab-AllPeople">All People</a></li>
                            </ul>
                        </td>
                        <td class="wfull" valign="top" align="left">
                            <div class="tab_container" style="position: relative;">
								<div id="top10KOLFilter" class="wfilters" style="position: absolute; top:20px; right: 20px;z-index:5;">
										<label>View By</label>
										<select name="d" id="top10KOLFilterSelect">
											<option value="0">Overall</option>
											<option value="1">Daily Mentions</option>
											<option value="2">Daily Impressions</option>
										</select>
								</div>
                                <div id="tab-Post" class="tab_content">
                                   <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr id="kolTopPeopleBar">
											
                                        </tr>
										<tr id="kolTopPeopleFoto">
							
                                        </tr>
                                    </table>
                                </div> <!-- #tab-Post -->
								<div id="tab-Mentions" class="tab_content">
									<div style="min-width:750px;height:350px;" id="kolTwitDailyMentions"></div>
								</div>
								<div id="tab-Impression" class="tab_content">
									<div style="min-width:750px;height:350px;" id="kolTwitDailyImpression"></div>
								</div>
                                <div id="tab-PotentialImpression" class="tab_content" >
                                   <div style="min-width:750px;height:350px;" id="kolTwitPotentialImpression"></div>
                                </div><!-- #tab-PotentialImpression -->
                                <div id="tab-Influence" class="tab_content" >
                                   <div style="min-width:750px;height:350px;" id="kolTwitMention"></div>
                                </div><!-- #tab-Influence -->
                                								<div id="tab-AllPeople" class="tab_content" >
                                    <div style="min-width:750px">
                                   <table id="twit-allpeople" width="100%" border="0" cellspacing="0" cellpadding="0" class="tablesorter addlist zebra" style="min-width:750px;">
                                        <thead>
                                        <tr>
                                            <th><strong>Pic</strong></th>
                                            <th><strong>Username</strong></th>
                                            <th><strong>Name</strong></th>
                                            <th><strong>Impression</strong></th>
                                            <th><strong>% Share</strong></th>
                                            <th><strong>PII</strong></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                   </table>
                                   </div>
                                </div><!-- #all-people -->
                            </div>
                        </td>
                      </tr>
                      <tr>
                        <td valign="top" align="left">&nbsp;</td>
                        <td valign="top" align="left">
                            <div id="greenbox" class="box">
                                <div class="titles">
                                    <h3>Positive Influencers</h3>
                                     <a class="smallArrow" href="#allPeopleTwitter">View All</a>
                                </div>
								<div id="twitPositiveKOL"></div>
                            </div><!-- #ambassador -->
                            <div id="redbox" class="box">
                                <div class="titles">
                                    <h3>Negative Influencers</h3>
                                     <a class="smallArrow" href="#allPeopleTwitter">View All</a>
                                </div>
                                <div id="twitNegativeKOL"></div>
                            </div><!-- #troll -->
                        </td>
                      </tr>
                    </table>
                </div><!-- #tabTwitter -->
            	<div id="tabFacebook" class="pageContent">
                    <div class="titles">
                        <h3>Top 10 People</h3>
                        <div id="idFbExcludeKOL" class="wfilters">
                            <label>Exclude By</label>
                            <select name="d" id="fbExcludeKOL">
								<option value="1">News</option>
								<option value="2">Corporate Accounts</option>
							</select>
                        </div>
                    </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="w200" valign="top" align="left">
                            <ul class="tabs">
                                <li><a href="#tabfb-Post">Post</a></li>
                                <li><a href="#tabfb-Likes">Likes</a></li>
                                <li><a href="#tabfb-AllPeople" >All People</a></li>
                            </ul>
                        </td>
                        <td class="wfull" valign="top" align="left">
                            <div class="tab_container">
                                <div id="tabfb-Post" class="tab_content" style="display:block;">
                                   <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr id="kolTopPeopleBarFB">  
										</tr>
                                        <tr id="kolTopPeopleFotoFB">                                        
                                        </tr>
                                    </table>
                                </div> <!-- #tab-Post -->
                                <div id="tabfb-Likes" class="tab_content" >
                                   <div style="min-width:750px;height:350px;" id="kolFBLikes"></div>
                                </div><!-- #tab-PotentialImpression -->
                                <div id="tabfb-AllPeople" class="tab_content" >
                                    <div style="min-width:750px">
                                   <table id="fb-allpeople" width="100%" border="0" cellspacing="0" cellpadding="0" class="tablesorter addlist zebra" style="min-width:750px;">
                                        <thead>
                                        <tr>
                                            <th><strong>Pic</strong></th>
                                            <th><strong>Username</strong></th>
                                            <th><strong>Name</strong></th>
                                            <th><strong>Impression</strong></th>
                                            <th><strong>% Share</strong></th>
                                            
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                   </table>
                                   </div>
                                </div><!-- #all-people -->
                            </div>
                        </td>
                      </tr>
                      <tr>
                        <td valign="top" align="left">&nbsp;</td>
                        <td valign="top" align="left">
                            <div id="greenbox" class="box">
                                <div class="titles">
                                    <h3>Positive Influencers</h3>
                                    <a class="smallArrow" href="#allPeopleFB">View All</a>
                                </div>
                                <div id="fbPositiveKOL"></div>
                            </div><!-- #ambassador -->
                            <div id="redbox" class="box">
                                <div class="titles">
                                    <h3>Negative Influencers</h3>
                                    <a class="smallArrow" href="#allPeopleFB">View All</a>
                                </div>
                                <div id="fbNegativeKOL"></div>
                            </div><!-- #troll -->
                        </td>
                      </tr>
                    </table>
                </div><!-- #tabFacebook -->
            	<div id="tabWeb" class="pageContent">
                    <div class="titles">
                        <h3>Top 10 People</h3>                     
                    </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="w200" valign="top" align="left">
                            <ul class="tabs">
                                <li><a href="#tabweb-Post">Post</a></li>
                                <li><a href="#tabweb-Comments">Comments</a></li>
                                <li><a href="#tabweb-Influence">Influence</a></li>
                                <li><a href="#tab-AllWebsite" >All Websites</a></li>
                            </ul>
                        </td>
                        <td class="wfull" valign="top" align="left">
                            <div class="tab_container">
                                <div id="tabweb-Post" class="tab_content">
                                   <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr id="kolTopPeopleBarWeb">  
										</tr>
                                        <tr id="kolTopPeopleFotoWeb">                                        
                                        </tr>
                                    </table>
                                </div> <!-- #tab-Post -->
                                <div id="tabweb-Comments" class="tab_content" >
                                   <div style="min-width:750px;height:350px;" id="kolWebComment"></div>
                                </div><!-- #tab-PotentialImpression -->
                                <div id="tabweb-Influence" class="tab_content" >
                                   <div style="min-width:750px;height:350px;" id="kolWebInfluence"></div>
                                </div><!-- #tab-Influence -->
                                <div id="tab-AllWebsite" class="tab_content" >
                                    <div style="min-width:750px">
                                   <table id="web-allpeople" width="100%" border="0" cellspacing="0" cellpadding="0" class="tablesorter addlist zebra" style="min-width:750px;">
                                        <thead>
                                        <tr>
                                            <th><strong>Pic</strong></th>
                                            <th><strong>Username</strong></th>
                                            <th><strong>Name</strong></th>
                                            <th><strong>Impression</strong></th>
                                            <th><strong>% Share</strong></th>
                                            <th><strong>PII</strong></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                   </table>
                                   </div>
                                </div><!-- #all-people -->
                            </div>
                        </td>
                      </tr>
                      <tr>
                        <td valign="top" align="left">&nbsp;</td>
                        <td valign="top" align="left">
                            <div id="greenbox" class="box">
                                <div class="titles">
                                    <h3>Positive Influencers</h3>
                                    <a class="smallArrow" href="#allPeopleWeb">View All</a>
                                </div>
                                <div id="webPositiveKOL"></div>
                            </div><!-- #ambassador -->
                            <div id="redbox" class="box">
                                <div class="titles">
                                    <h3>Negative Influencers</h3>
									<a class="smallArrow" href="#allPeopleWeb">View All</a>
                                </div>
                                <div id="webNegativeKOL"></div>
                            </div><!-- #troll -->
                        </td>
                      </tr>
                    </table>
                </div><!-- #tabWeb -->
				
				<div id="allPeopleTwitter" class="pageContent">
                    <div class="titles">
                        <h3 id="allInfluencer"></h3>
                    </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td valign="top" align="left">
                            <div id="greenbox" class="box">
                                <div class="titles">
                                    <h3>Positive Influencers</h3>                             
                                </div>
                                <div id="twitAllPositiveKOL"></div>
								<div id="cPaging" class="paging"></div>
                            </div><!-- #green -->
                            <div id="redbox" class="box">
                                <div class="titles">
                                    <h3>Negative Influencers</h3>
                                </div>
                               <div id="twitAllNegativeKOL"></div>
							   <div id="negativeKOLPaging" class="paging"></div>
                            </div><!-- #troll -->
                        </td>
                      </tr>
                    </table>
                </div><!-- #All People -->
                  <?php else: ?>
                    <div id="notAvailable">
                            <div class="blankText">
                                <h1>Your first report is not ready yet.</h1>
                                <p>You have to wait 24 hours before it's completed.</p>
                    <p>In the meantime you can visit the "Live Track" page <br />
                        to see what's happening with your Topic in real-time..</p>
                                <a href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=livetrack'), $this);?>
" class="btnGreenBar">See Live Track</a>
                            </div>
                            <div class="screenCap">
                                <img src="images/blank_kol.gif" />
                            </div>
                    </div>
	             <?php endif; ?>
            </div><!-- #key-opinion -->
        </div><!-- #container -->
    </div><!-- #main-container -->