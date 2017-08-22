<?php /* Smarty version 2.6.13, created on 2012-10-15 16:11:37
         compiled from smac/liveTracked.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/liveTracked.html', 45, false),)), $this); ?>
<div id="main-container">
    	<?php echo $this->_tpl_vars['sidebar']; ?>

        <div id="container">
    		<?php echo $this->_tpl_vars['menu']; ?>

            <div class="title-bar">
            	<h1><a id="menuAtas" href="#" no="4">Live Track</a>
            		<span id="livetrackLoader" style="margin-left: 10px;"><img src='images/loader-small.gif'/></span>
            		<span id="mapLoader" style="margin: 0 0 0 250px;"></span>
            		</h1>
				<div id="liveTrackFilter" class="wfilters">
						<label>View By</label>
						<select id="liveTrackFilterSelect" name="d">
							<option value="1">Map</option>
							<option value="0">Summary</option>						
						</select>
				</div>
			
			</div>
            <div id="live-track">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="4">
                            <div class="bgGreen">
                                <div id="livetrackChart" class="theChart bgWhite" style="width: 920px;height: 280px;">
                                    
                                </div>
								<div id="livetrackMap" class="theChart bgWhite" style="width: 920px;height: 280px;display:none;text-align: center; color: grey;">
									<h2>Not Available</h2>
                                </div>
                                <div id="mapNoData" class="theChart bgWhite" style="width: 920px;height: 280px;display:none;text-align:center;color:grey;">
                                  	<div id="mytopic-banner">
										<div class="content">
										<h1 style="margin-top: 38px;">Sorry, no available geo data for this topic <br/>at the moment<br></h1>
										</div>
									</div>
                                </div>
                            </div>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="top">
                            <div class="boks">
                                <div class="titles">
                                    <h3>Trending Issues</h3>
									<a href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=ka'), $this);?>
" class="smallArrow">View All</a>
                                </div>
                                <div id="trendingIssues" class="bgGreys">
                                	
                                </div>	
                            </div>
                        </td>
                        <td align="left" valign="top">
                            <div class="thePeoples boks">
                                <div class="titles">
                                    <h3>Trending People</h3>
									<a href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=kol'), $this);?>
" class="smallArrow">View All</a>
                                </div>
                                <div id="trendingPeople" class="content">
                                     
                                </div><!-- .content-->
                            </div><!-- .thePeoples-->
                        </td>
                        <td align="left" valign="top">
                            <div class="boks">
                                <div class="titles">
                                    <h3>Top Locations</h3>
									<a href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=marketPage'), $this);?>
" class="smallArrow">View All</a>
                                </div>
                                <div id="topLocations" class="bgGreys">
                                	
                                </div>
                            </div><!-- # End pieChart1-->
                        </td>
                      </tr>
                      <tr>
                        <td colspan="4">
                            <div id="topPost" class="pageContent" style="display:block;">
                              <div class="titles">
                                <h3>Actual Post (Updated Hourly)</h3>
                              </div>
                              <div id="channels" class="bgGreys">
                                <div id="tab-twitter">
                                  <div id="twitter-topconv">                                 
                                    
                                  </div>
                                </div>
                              </div><!-- .bgGreys -->
							  <div id="liveTrackFeedPage" class="paging"></div>
                            </div><!-- .topPost -->
                        </td>
                      </tr>
                    </table>
            </div><!-- #live-track -->
        </div><!-- #container -->
    </div><!-- #main-container -->