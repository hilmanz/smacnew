<?php /* Smarty version 2.6.13, created on 2012-09-13 14:39:52
         compiled from smac/home.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/home.html', 10, false),array('modifier', 'number_format', 'smac/home.html', 71, false),)), $this); ?>
	<div id="main-container">
    	<?php echo $this->_tpl_vars['sidebar']; ?>

        <div id="container">
    		<?php echo $this->_tpl_vars['menu']; ?>

            <div class="title-bar">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left">
          					  	<h1><?php if ($this->_tpl_vars['market']): ?><span id='txtmarket'>Market Dashboard - <?php echo $this->_tpl_vars['market']; ?>
</span><?php else: ?><span id='txtmarket'>Global Dashboard - </span><span id='dtsource'>Twitter</span><?php endif; ?> 
                           <?php if ($this->_tpl_vars['market']): ?> <a id='btnglobaldata' href='<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=toggle_geo'), $this);?>
'>Switch to Global Data</a><?php endif; ?></h1>
                        </td>
                        <td align="right">
                           <div style="float:right"> <?php echo $this->_tpl_vars['widget_datefilter']; ?>
</div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <?php if ($this->_tpl_vars['data_available']): ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="w1" align="left" valign="top">
                    <div id="home-twitter" <?php if ($this->_tpl_vars['web_total'] > 0): ?>style="display:none;"<?php endif; ?>>
                            <div class="list-box" style="width: 645px;">
                                <div class="box">
                                    <span>Potential Impact Index</span>
                                        <a href="#" class="helpsmall tip_trigger">&nbsp;<span class="tip">An index that rates and concludes your listening topic comprising of weighted scores of mentions, sentiment, dominance and meaning.</span></a>
                                    <h1 style="margin: 10px 0;"><?php echo $this->_tpl_vars['impact']; ?>
</h1>
                                    
                                    <?php if ($this->_tpl_vars['pii_diff'] > 0): ?>                      
                                    <span class="triangle fleft">&nbsp;</span>
                                    <?php elseif ($this->_tpl_vars['pii_diff'] < 0): ?>
                                     <span class="triangle arrow_down fleft">&nbsp;</span>
                                    <?php endif; ?>
                                    <span class="fright f18"><?php if ($this->_tpl_vars['pii_diff'] <> 0):  echo $this->_tpl_vars['pii_diff'];  endif; ?></span>
                                </div>
                                <div class="box">
                                    <span>Total Mention</span>
                                        <a href="#" class="helpsmall tip_trigger">&nbsp;<span class="tip">How many times your keywords have been mentioned accumulatively through the topic's timespan</span></a>
                                     <h1 style="margin: 10px 0;"><?php echo $this->_tpl_vars['mention']; ?>
</h1>
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
                                   
                                    <span class="fright f18"><?php if ($this->_tpl_vars['mention_diff'] <> 0):  echo $this->_tpl_vars['mention_diff'];  endif; ?></span>
                                </div>
                                <div class="box">
                                    <span>Potential Impressions</span>
                                        <a href="#" class="helpsmall tip_trigger">&nbsp;<span class="tip">The absolute reach or a count of how many eyeballs your keyword(s) have generated, mentions times followers.</span></a>
                                     <h1 style="margin: 10px 0;"><?php echo $this->_tpl_vars['impressi']; ?>
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
                                    <span class="fright f18"><?php if ($this->_tpl_vars['imp_diff'] <> 0):  echo $this->_tpl_vars['imp_diff'];  endif; ?></span>
                                </div>
                            </div>
                            <div class="big-title link-bar">
                                <h1><a href="#"><?php echo $this->_tpl_vars['people']; ?>
 People</a> in conversation over <?php echo ((is_array($_tmp=$this->_tpl_vars['total_days'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 days</h1>
                            </div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td valign="top" width="330">
                                    <div id="wgwords"></div>
                                                                   </td>
                                <td valign="top">
                                    <div id="wgkol"></div>
                                                                     </td>
                              </tr>
                            </table>
                                                  </div><!-- # End Home Twitter-->
          			  <div id="home-facebook" style="display:none;">
                        <div class="list-box" style="width: 645px;">
                            <div class="box facebook-box">
                                <span>Post Likes</span>
                                    <a href="#" class="helpsmall tip_trigger">&nbsp;<span class="tip">An index that rates and concludes your listening topic comprising of weighted scores of mentions, sentiment, dominance and meaning.</span></a>
                                <h1 style="margin: 10px 0;"><?php echo $this->_tpl_vars['fb_likes']; ?>
</h1>
                                
                               
                                <?php if ($this->_tpl_vars['like_change'] > 0): ?>                      
                                <span class="triangle fleft"><?php echo $this->_tpl_vars['fb_like_change']; ?>
 %</span>
                                <?php elseif ($this->_tpl_vars['fb_like_change'] < 0): ?>
                                 <span class="triangle arrow_down fleft"><?php echo $this->_tpl_vars['fb_like_change']; ?>
 %</span>
                                <?php endif; ?>
                                <span class="fright f18"><?php if ($this->_tpl_vars['fb_like_diff'] <> 0):  echo $this->_tpl_vars['fb_like_diff'];  endif; ?></span>
                                
                            </div>
                            <div class="box facebook-box">
                                <span>Public Posts</span>
                                    <a href="#" class="helpsmall tip_trigger">&nbsp;<span class="tip">How many times your keywords have been mentioned accumulatively through the topic's timespan</span></a>
                                 <h1 style="margin: 10px 0;"><?php echo $this->_tpl_vars['fb_mentions']; ?>
</h1>
                                  <?php if ($this->_tpl_vars['fb_mention_change'] > 0): ?>                      
                                <span class="triangle fleft"><?php echo $this->_tpl_vars['fb_mention_change']; ?>
 %</span>
                                <?php elseif ($this->_tpl_vars['mention_change'] < 0): ?>
                                 <span class="triangle arrow_down fleft"><?php echo $this->_tpl_vars['fb_mention_change']; ?>
 %</span>
                                <?php endif; ?>
                                <span class="fright f18"><?php if ($this->_tpl_vars['fb_mention_diff'] <> 0):  echo $this->_tpl_vars['fb_mention_diff'];  endif; ?></span>
                                
                                
                                
                            </div>
                        </div>  
                        <div class="big-title link-bar">
                            <h1><a href="#"><?php echo $this->_tpl_vars['fb_people']; ?>
 People</a> in conversation over <?php echo ((is_array($_tmp=$this->_tpl_vars['total_days'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 days</h1>
                        </div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td valign="top" width="330">
                      				  <div id="wgwords2"></div>
                                </td>
                                <td valign="top">
                  				      <div id="wgkol2"></div>
                                </td>
                              </tr>
                            </table>
            		  </div><!-- # End Home Facebook-->
          			  <div id="home-blog" style="display:none;">
                        <div class="list-box" style="width: 645px;">
                            <div class="box blog-box">
                                <span>Total Websites</span>
                                    <a href="#" class="helpsmall tip_trigger">&nbsp;<span class="tip">An index that rates and concludes your listening topic comprising of weighted scores of mentions, sentiment, dominance and meaning.</span></a>
                                <h1 style="margin: 10px 0;"><?php echo $this->_tpl_vars['total_websites']; ?>
</h1>
                                
                                                            </div>
                            <div class="box blog-box">
                                <span>Total Posts</span>
                                    <a href="#" class="helpsmall tip_trigger">&nbsp;<span class="tip">How many times your keywords have been mentioned accumulatively through the topic's timespan</span></a>
                                 <h1 style="margin: 10px 0;"><?php echo $this->_tpl_vars['web_mentions']; ?>
</h1>
                                                            </div>
                        </div>
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td valign="top" width="330">
                      				  <div id="wgwords3"></div>
                                </td>
                                <td valign="top">
                  				      <div id="wgkol3"></div>
                                </td>
                              </tr>
                            </table>
            		  </div><!-- # End Home Blog-->
                    </td><!-- .w1-->
                    <td class="w2" align="left" valign="top">
                    
                        <div id="wgtab"></div>
                        <script>dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=top_conversation&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date'],'tab' => $this->_tpl_vars['default_tab']), $this);?>
",'wgtab','Loading Top Conversations');</script>
                                            
                    </td><!-- .w2-->
                  </tr>
                </table>
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
                        	<img src="images/blank_dashboard.gif" />
                        </div>
                </div>
            <?php endif; ?>
        </div><!-- #container -->
    </div><!-- #main-container -->