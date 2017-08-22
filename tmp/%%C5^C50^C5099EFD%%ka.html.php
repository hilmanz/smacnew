<?php /* Smarty version 2.6.13, created on 2012-10-01 16:48:39
         compiled from smac/ka.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/ka.html', 11, false),)), $this); ?>
<div id="main-container">
    	<?php echo $this->_tpl_vars['sidebar']; ?>

        <div id="container">
    		<?php echo $this->_tpl_vars['menu']; ?>

			<div id="kaDefaultpage">
				<div class="title-bar">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td align="left">
									<h1><?php if ($this->_tpl_vars['market']): ?><span id='txtmarket'>Market Keyword Analysis - <?php echo $this->_tpl_vars['market']; ?>
</span><?php else: ?><a href="<?php echo $this->_tpl_vars['urlkeywordanalysis']; ?>
">Keyword Analysis</a><?php endif; ?> 
							   <?php if ($this->_tpl_vars['market']): ?> <a id='btnglobaldata' href='<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=toggle_geo'), $this);?>
'>Switch to Global Data</a><?php endif; ?></h1>
							</td>
							<td align="right">
								<div style="float:right;">
									<span style="display:inline-block;">
									
										<label>Filter by:</label>
										<select name="d" id="dailySelect">
											<option value="summary_by_rule">My Rules</option>
											<option value="top1">Top 10</option>
											<option value="top2">Top 50</option>
										</select>
									
									</span>
									&nbsp;
									<span style="display:inline-block;">
									<?php echo $this->_tpl_vars['widget_datefilter']; ?>

									</span>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="sentiment">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					 <?php if ($this->_tpl_vars['data_available']): ?>  
					  <tr>
						<td class="w200" valign="top" align="left">
							<ul id="kaTabs" class="tabs">													
								<li><a href="#tab-mention" class="tab-mention">Mentions</a></li>
								<li><a href="#impression-overtime" class="impression-overtime">Impressions</a></li>
								<li><a href="#retweets" class="retweets">Retweets</a></li>
								<li><a href="#potential-impact" class="potential-impact">Potential Impact Index</a></li>
								<li><a href="#graph-sentiment" class="graph-sentiment">Sentiment</a></li>
							</ul>
						</td>
						<td class="wfull" valign="top" align="left">
							<div id="kaContainer" class="tab_container" style="min-width:750px;">
								<div id="tab-mention" class="tab_content">
									<div style="min-width:710px;height:650px;" id="kaMention"></div>
								</div>
								<div id="impression-overtime" class="tab_content" >                          
								   <div style="min-width:710px;height:650px;" id="kaImpression"></div>
								</div>
								<div id="retweets" class="tab_content" >                          
								   <div style="min-width:710px;height:650px;" id="kaRetweets"></div>
								</div>
								<div id="potential-impact" class="tab_content">
								   <div style="min-width:710px;height:350px;" id="kaPII"></div>
								</div>
								<div id="graph-sentiment" class="tab_content">
								   
								   <div style="min-width:710px;height:350px;" id="kaSentiment"></div>
								   
									<div id="show-editsentiment" style="cursor:pointer;">Edit Sentiment</div>
									
									<div id="tabel-tweets-block">
										<div id="list-tweet">
											<div class="headpopup">
												<h1 class="fleft"></h1>
											</div>
											<div id="kaSentimentConversation" class="content-popup">	
											</div>
											<div id="sentimentPaging" class="paging">											
											</div>
										</div>
									</div>
								   
									<div id="tabel-sentiment-block" style="display:none;">
									   <table id="tbl-sentiment" style="width:100%; display:table;" width="100%" border="0" cellspacing="0" cellpadding="0" class="tablesorter addlist zebra">
										<thead>
										<tr>
											<th><strong>Keyword</strong></th>
											<th><strong>Category</strong></th>
											<th><strong>Occurance</strong></th>
											<th><strong>Weightage</strong></th>
											<th width="80">&nbsp;</th>
										</tr>
										</thead>
										<tbody id="editSentimentBody">
										
										<tr>
											<td colspan="5" class="dataTables_empty">Loading data from server</td>
										</tr>
										
										
										</tbody>
									   </table>
									</div>
									
								</div>
								
							</div>
							<div class="content" id="tabel-bottom">
								  <div class="titles">
									<h2>Breakdown by your keyword / rule</h2>
									<a id="kaBreakdownViewAll" class="smallArrow" href="#">View Detail</a>
								  </div>
								<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableGreen">
									<thead>
									  <tr>
										<th width="100">Rules</th>
										<th>Channel</th>
										<th>Post </th>
										<th>Sentiment</th>
										<th>Words</th>
									  </tr>
									</thead>
									<tbody id="kaBreakdown">
									 
									</tbody>
									<tfoot>
										<tr>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
									</tfoot>
								</table>
								<div class="legend">
									<div class="fleft">
										<span class="recBlue">Twitter</span>
										<span class="recOldBlue">Facebook</span>
										<span class="recOrange">Others</span>
										<span class="recRed">Negative</span>
										<span class="recGreen">Positive</span>
										<span class="recGrey">Neutral</span>
									</div>
								</div>
							</div>
						</td>
					  </tr>
					 <?php else: ?>
						<tr>
							<td align="center" valign="top">
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
											<img src="images/blank_keywordAnalysis.gif" />
										</div>
								</div>
							</td>
						</tr> 	
					<?php endif; ?>
					</table>
				</div><!-- #key-opinion -->
			</div><!-- #Keyword Analysis Default Page -->
			<div id="kaDetailPage" style="display: none;">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/ka_rule.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</div><!-- #Keyword Analysis Default Page -->
        </div><!-- #container -->
    </div><!-- #main-container -->