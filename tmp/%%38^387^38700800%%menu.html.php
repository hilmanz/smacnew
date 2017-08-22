<?php /* Smarty version 2.6.13, created on 2013-01-03 17:50:43
         compiled from smac/menu.html */ ?>

        	<div class="top">
            	<div class="block-info">
                    <div class="box campaign-name">
                    	<span>Topic Name</span>
                        <h1><?php echo $this->_tpl_vars['campaign']['name']; ?>
</h1>
                    </div>
                    <div class="box country-name">
                    	<span>Country</span>
                    	<?php if ($this->_tpl_vars['campaign']['country_single']): ?>
                        <h1><?php echo $this->_tpl_vars['campaign']['country']; ?>
</h1>
                        <?php else: ?>
                        <h1 style="font-size:12px;"><?php echo $this->_tpl_vars['campaign']['country']; ?>
</h1>
                        <?php endif; ?>
                    </div>
                    <div class="box start-date">
                    	<span>Start Date</span>
                        <h1><?php echo $this->_tpl_vars['campaign']['start']; ?>
</h1>
                    </div>
                                        <div class="box status" <?php if (! $this->_tpl_vars['filterByGeo']): ?>style="border:none;"<?php endif; ?>>
                    	<span>Status</span>
                        <h1><?php if ($this->_tpl_vars['campaign']['n_status'] == '1'): ?>Active<?php elseif ($this->_tpl_vars['campaign']['n_status'] == '2'): ?>Paused<?php else: ?>Non-Active<?php endif; ?></h1>
                    </div>
                    
					<?php if ($this->_tpl_vars['filterByGeo']): ?>
					<div class="box filter-geography">
						<span>Language</span>
						<h1><?php echo $this->_tpl_vars['campaign']['lang_str']; ?>
</h1>
					                    </div>
					<?php endif; ?>					
				</div>
                <div class="menu-icon">
                	<span class="dashboard"><a class="menuAtas1 dashboard theTolltip" href="<?php echo $this->_tpl_vars['urlhome']; ?>
" title="Dashboard">&nbsp;</a></span>
                	<span class="opinion"><a class="menuAtas2 opinion theTolltip" href="<?php echo $this->_tpl_vars['urlkeyopinion']; ?>
" title="Key Opinion Leader">&nbsp;</a></span>
                	<span class="analisis"><a class="menuAtas3 analisis theTolltip" href="<?php echo $this->_tpl_vars['urlkeywordanalysis']; ?>
" title="Keyword Analysis">&nbsp;</a></span>
                	<span class="livetrack"><a class="menuAtas4 livetrack theTolltip" href="<?php echo $this->_tpl_vars['urllivetrack']; ?>
" title="Live Track">&nbsp;</a></span>
                	<!--<a class="menuAtas5 responder tip_trigger" href="<?php echo $this->_tpl_vars['urlautoresponder']; ?>
">&nbsp;<span class="tip">Autoresponders</span></a>-->
                	                	<span class="topsummary"><a class="menuAtas5 topsummary theTolltip" href="<?php echo $this->_tpl_vars['urltopsummary']; ?>
" title="Download Report">&nbsp;</a></span>
                    <span class="icon_workflowmenu"><a href="<?php echo $this->_tpl_vars['urlworkflow']; ?>
" class="menuAtas6 icon_workflowmenu theTolltip" title="Workflow">&nbsp;</a></span>
                    <?php if ($this->_tpl_vars['has_market']): ?><span class="icon_marketmenu"><a href="<?php echo $this->_tpl_vars['urlmarket']; ?>
" class="menuAtas7 icon_marketmenu theTolltip" title="Markets">&nbsp;</a></span><?php endif; ?>
                </div>
            </div><!-- .top -->