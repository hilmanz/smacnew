<?php /* Smarty version 2.6.13, created on 2012-11-22 16:42:50
         compiled from smac/channelPage.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/channelPage.html', 178, false),)), $this); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="w1" align="left" valign="top">
    	<div id="colTwitter" class="w480">
            <div class="bgBlue">
                <div class="titles" style="position: relative;height: 40px;overflow: visible;">
                    <h3 class="iconTwitters">My Twitter: @<span id="myChannelTwitID"></span></h3>
										<div id="listTwitID" class="popupList">
						
					</div>
                </div>
                <div id="myTwitterChart" class="bgWhite" style="width:440px;height:280px;">
					<div style='text-align: center;'><span style='color:black;display:block;margin-bottom: 10px;'>Loading Twitter</span><img src='images/loader-med.gif'/></div>
                </div>
            </div>
            <div class="list-box">
                <div class="box firstBox">
                    <span>Mentions</span>
                    <span>RT</span>
                     <a href="#" class="helpsmall theTolltip" title="How many times your keywords have been mentioned accumulatively through the topic's timespan."></a>
                     <h1 id="myChannelTwitMention"></h1>
                     <?php if ($this->_tpl_vars['mention_change'] <> 0): ?>   
                    <?php if ($this->_tpl_vars['mention_change'] < 0): ?>
                     <span class="triangle arrow_down fleft">
                    <?php else: ?>              
                    <span class="triangle fleft">
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['mention_change'] < 300):  echo $this->_tpl_vars['mention_change']; ?>
%<?php endif; ?>
                    </span>
                    <?php endif; ?>
                    <span class="counts"><?php if ($this->_tpl_vars['mention_diff'] <> 0):  echo $this->_tpl_vars['mention_diff'];  endif; ?></span>
                </div>
                <div class="box">
                    <span>RT</span>
                     <a href="#" class="helpsmall theTolltip" title="How many times your keywords have been mentioned accumulatively through the topic's timespan."></a>
                     <h1 id="myChannelTwitRT"></h1>
                     <?php if ($this->_tpl_vars['mention_change'] <> 0): ?>   
                    <?php if ($this->_tpl_vars['mention_change'] < 0): ?>
                     <span class="triangle arrow_down fleft">
                    <?php else: ?>              
                    <span class="triangle fleft">
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['mention_change'] < 300):  echo $this->_tpl_vars['mention_change']; ?>
%<?php endif; ?>
                    </span>
                    <?php endif; ?>
                    <span class="counts"><?php if ($this->_tpl_vars['mention_diff'] <> 0):  echo $this->_tpl_vars['mention_diff'];  endif; ?></span>
                </div>
                <div class="box firstBox">
                    <span>New Followers</span>
                     <a href="#" class="helpsmall theTolltip" title="The absolute reach or a count of how many eyeballs your keyword(s) have generated, mentions times followers."></a>
                     <h1 id="myChannelTwitNewFollowers"></h1>
                   <?php if ($this->_tpl_vars['imp_change'] <> 0): ?>                  
                   <?php if ($this->_tpl_vars['imp_change'] < 0): ?>
                     <span class="triangle arrow_down fleft">
                    <?php else: ?>              
                    <span class="triangle fleft">
                    <?php endif; ?>
                   
                   <?php if ($this->_tpl_vars['imp_change'] < 300):  echo $this->_tpl_vars['imp_change']; ?>
%<?php endif; ?>
                   </span>
                    <?php endif; ?>
                    <span class="counts"><?php if ($this->_tpl_vars['imp_diff'] <> 0):  echo $this->_tpl_vars['imp_diff'];  endif; ?></span>
                </div>
                <div class="box">
                    <span>Unfollows</span>
                     <a href="#" class="helpsmall theTolltip" title="The absolute reach or a count of how many eyeballs your keyword(s) have generated, mentions times followers."></a>
                     <h1 id="myChannelTwitUnfollows"></h1>
                   <?php if ($this->_tpl_vars['imp_change'] <> 0): ?>                  
                   <?php if ($this->_tpl_vars['imp_change'] < 0): ?>
                     <span class="triangle arrow_down fleft">
                    <?php else: ?>              
                    <span class="triangle fleft">
                    <?php endif; ?>
                   
                   <?php if ($this->_tpl_vars['imp_change'] < 300):  echo $this->_tpl_vars['imp_change']; ?>
%<?php endif; ?>
                   </span>
                    <?php endif; ?>
                    <span class="counts"><?php if ($this->_tpl_vars['imp_diff'] <> 0):  echo $this->_tpl_vars['imp_diff'];  endif; ?></span>
                </div>
            </div>
         </div>
    </td>
    <td align="left" valign="top">
    	<div id="colFacebook" class="w480">
            <div class="bgOldBlue">
                <div class="titles" style="position: relative;height: 40px;overflow: visible;">
                    <h3 class="iconFacebooks">My Fanpage: <span id="myChannelFBID" style="display: inline-block;line-height: 20px;margin-bottom: -6px;overflow: hidden;width: 315px;"></span></h3>
                   					<div id="listFBID" class="popupList popupListFB">
						
					</div>
                </div>
                <div id="myFacebookChart" class="bgWhite" style="width:440px;height:280px;">
					<div style='text-align: center;'><span style='color:black;display:block;margin-bottom: 10px;'>Loading Facebook</span><img src='images/loader-med.gif'/></div>
                </div>
            </div>
            <div class="list-box">
                <div class="box firstBox">
                    <span>Posts</span>
                    <a class="helpsmall theTolltip" href="#" title="An index that rates and concludes your listening topic comprising of weighted scores of mentions, sentiment, dominance and meaning."></a>
                    <h1 id="myChannelFBPosts"><?php echo $this->_tpl_vars['impact']; ?>
</h1>
                    <?php if ($this->_tpl_vars['pii_diff'] > 0): ?>                      
                    <span class="triangle fleft">&nbsp;</span>
                    <?php elseif ($this->_tpl_vars['pii_diff'] < 0): ?>
                     <span class="triangle arrow_down fleft">&nbsp;</span>
                    <?php endif; ?>
                    <span class="counts"><?php if ($this->_tpl_vars['pii_diff'] <> 0):  echo $this->_tpl_vars['pii_diff'];  endif; ?></span>
                </div>
                <div class="box">
                    <span>Like</span>
                     <a href="#" class="helpsmall theTolltip" title="How many times your keywords have been mentioned accumulatively through the topic's timespan."></a>
                     <h1 id="myChannelFBLikes"></h1>
                     <?php if ($this->_tpl_vars['mention_change'] <> 0): ?>   
                    <?php if ($this->_tpl_vars['mention_change'] < 0): ?>
                     <span class="triangle arrow_down fleft">
                    <?php else: ?>              
                    <span class="triangle fleft">
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['mention_change'] < 300):  echo $this->_tpl_vars['mention_change']; ?>
%<?php endif; ?>
                    </span>
                    <?php endif; ?>
                    <span class="counts"><?php if ($this->_tpl_vars['mention_diff'] <> 0):  echo $this->_tpl_vars['mention_diff'];  endif; ?></span>
                </div>
                <div class="box firstBox">
                    <span>New Like</span>
                     <a href="#" class="helpsmall theTolltip" title="The absolute reach or a count of how many eyeballs your keyword(s) have generated, mentions times followers."></a>
                     <h1 id="myChannelFBNewlikes"><?php echo $this->_tpl_vars['impressi']; ?>
</h1>
                   <?php if ($this->_tpl_vars['imp_change'] <> 0): ?>                  
                   <?php if ($this->_tpl_vars['imp_change'] < 0): ?>
                     <span class="triangle arrow_down fleft">
                    <?php else: ?>              
                    <span class="triangle fleft">
                    <?php endif; ?>
                   
                   <?php if ($this->_tpl_vars['imp_change'] < 300):  echo $this->_tpl_vars['imp_change']; ?>
%<?php endif; ?>
                   </span>
                    <?php endif; ?>
                    <span class="counts"><?php if ($this->_tpl_vars['imp_diff'] <> 0):  echo $this->_tpl_vars['imp_diff'];  endif; ?></span>
                </div>
                <div class="box">
                    <span>Unlike</span>
                     <a href="#" class="helpsmall theTolltip" title="The absolute reach or a count of how many eyeballs your keyword(s) have generated, mentions times followers."></a>
                     <h1 id="myChannelFBUnlike"><?php echo $this->_tpl_vars['impressi']; ?>
</h1>
                   <?php if ($this->_tpl_vars['imp_change'] <> 0): ?>                  
                   <?php if ($this->_tpl_vars['imp_change'] < 0): ?>
                     <span class="triangle arrow_down fleft">
                    <?php else: ?>              
                    <span class="triangle fleft">
                    <?php endif; ?>
                   
                   <?php if ($this->_tpl_vars['imp_change'] < 300):  echo $this->_tpl_vars['imp_change']; ?>
%<?php endif; ?>
                   </span>
                    <?php endif; ?>
                    <span class="counts"><?php if ($this->_tpl_vars['imp_diff'] <> 0):  echo $this->_tpl_vars['imp_diff'];  endif; ?></span>
                </div>
            </div>
    	</div>
    	
    </td>
  </tr>
  <tr>
    <td colspan="2">                    
    	
        <div id="topPost">
          <div class="titles">
            <h3>Top Posts</h3>
          </div>
		  <div id="allPostnav2">
                <h1>
                <span class="navTwitter active"><a class="iconTwitter" href="#" onclick="myChannelTwitterPost(null, 0); return false;">&nbsp;</a></span>
                <span class="navFacebook"><a class="iconFacebook" href="#" onclick="myChannelFBPost(null, 0); return false;">&nbsp;</a></span>
                </h1>
				 <a class="smallArrow" href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=allpost'), $this);?>
#twitterTab">View All</a>
            </div>
          <div id="channels" class="bgGreys">
            <div id="tab-twitter">
              <div id="twitter-topconv">      
                
              </div>
            </div>
          </div><!-- .bgGreys -->
        </div><!-- .topPost -->
    </td>
  </tr>
</table>