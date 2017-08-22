<?php /* Smarty version 2.6.13, created on 2012-11-22 16:42:50
         compiled from smac/dashboard.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/dashboard.html', 91, false),)), $this); ?>
<div id="main-container">
    	<?php echo $this->_tpl_vars['sidebar']; ?>

        <div id="container">
    		<?php echo $this->_tpl_vars['menu']; ?>

            <div class="title-bar">
                <h1><a>DASHBOARD</a></h1>
            </div>
            <div class="title-bar">
                <div id="dashboardNav">
                	<span class="navSummary">
						<a id="buttonSummary" href="#summayPage" class="active theDashboard" title="Summary"><span class="iconSummary">&nbsp;</span></a>
					</span>
                	<span class="navTwitter">
						<a id="buttonTwitter" href="#twitterPage" class="theDashboard" title="Twitter"><span class="iconTwitter">&nbsp;</span></a>
					</span>
                	<span class="navFacebook">
						<a id="buttonFacebook" href="#facebookPage" class="theDashboard" title="Facebook"><span class="iconFacebook">&nbsp;</span></a>
					</span>
                	<span class="navWeb">
						<a id="buttonWeb" href="#webPage" class="theDashboard" title="Blog"><span class="iconWeb">&nbsp;</span></a>
					</span>
					<span class="navForum">
						<a id="buttonForum" href="#forumPage" class="theDashboard" title="Forum"><span class="iconForum">&nbsp;</span></a>
					</span>
					<span class="navNews">
						<a id="buttonNews" href="#newsPage" class="theDashboard" title="News"><span class="iconNews">&nbsp;</span></a>
					</span>
					               		 <?php if ($this->_tpl_vars['channel_account'] == '1'): ?>
                	<span class="navChannel">
						<a id="buttonChannel" href="#channelPage" class="theDashboard" title="My Channel"><span class="iconChannel">&nbsp;</span></a>
					</span>
                    <?php endif; ?>
					<span class="navNews">
						<a id="buttonEcommerce" href="#ecommercePage" class="theDashboard" title="Ecommerce"><span class="iconEcommerce">&nbsp;</span></a>
					</span>
					<span class="navNews">
						<a id="buttonCorporate" href="#corporatePage" class="theDashboard" title="Corporate"><span class="iconCorporate">&nbsp;</span></a>
					</span>
                    <div class="wfilters"> <?php echo $this->_tpl_vars['widget_datefilter']; ?>
</div>
                </div>
			</div>
            <?php if ($this->_tpl_vars['data_available']): ?>
            <div id="mainContent">
            	<div id="summayPage" class="pageContent">
					 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/summaryPage.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </div><!-- #twitterPage -->
                <div id="twitterPage" class="pageContent">
					 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/twitterPage.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </div><!-- #fbPage -->
                <div id="facebookPage" class="pageContent">
					 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/facebookPage.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </div>
                <!-- #webPage -->
                <div id="webPage" class="pageContent">
					 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/webPage.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </div>
				<div id="forumPage" class="pageContent">
					 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/forumPage.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </div>
				<div id="newsPage" class="pageContent">
					 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/newsPage.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </div>
                <!-- #videoPage -->
                <div id="videoPage" class="pageContent">
					 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/videoPage.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </div>
                <!-- #channelpage -->
                <div id="channelPage" class="pageContent">
					 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/channelPage.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </div><!-- #twitterPage -->
				<div id="noDashboardDataPage" style="display:none;">
					<div id="mytopic-banner">
						<div class="content">
							<h1 style="margin-top: 38px;">Sorry, there seems to be no posts yet<br>for this channel</h1>
						</div>
					</div>
				</div>
            </div><!-- #mainContent -->
            <?php else: ?>
            	<div id="notAvailable">
            			<div class="blankText">
            				<h1>Your first report is not ready yet.</h1>
                            <p>You have to wait 24 hours before it's completed.</p>
                            <p>In the meantime you can visit the "Live Track" page <br />
								to see what's happening with your Topic in real-time..</p>
                            <a href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=liveTracked'), $this);?>
" class="btnGreenBar">See Live Track</a>
            			</div>
                        <div class="screenCap">
                        	<img src="images/blank_dashboard.gif" />
                        </div>
                </div>
            <?php endif; ?>
        </div><!-- #container -->
    </div><!-- #main-container -->