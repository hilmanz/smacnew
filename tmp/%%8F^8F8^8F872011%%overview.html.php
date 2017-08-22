<?php /* Smarty version 2.6.13, created on 2013-01-03 15:20:36
         compiled from smac/overview.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/overview.html', 27, false),)), $this); ?>
<div id="main-container">
<?php if ($this->_tpl_vars['show_tips'] == 1):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_first_topic.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
    	<?php echo $this->_tpl_vars['sidebar']; ?>

        <div id="container"> 
			<?php if ($this->_tpl_vars['first_data']): ?>
            <div id="notAvailable">
                    <div class="blankText">
                        <h1>You don't have any report yet.</h1>
                        <p>Create your first Topic now.<br />
                        Simply click <strong>"New Topic"</strong> button<br />
                        on the sidebar.</p>
                        <img src="images/samplestarted.jpg" />
                    </div>
                    <div class="screenCap">
                        <img src="images/blank_mytopic.gif" />
                    </div>
            </div>
            <?php elseif ($this->_tpl_vars['no_data_available']): ?>
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
                            <img src="images/blank_kol.gif" />
                        </div>
                </div>
            <?php else: ?>
            <div class="title-bar">
            	<h1><a href="javascript:void(0);">My Topics</a></h1>
            </div>
			<?php endif; ?>
            <div id="mycampaign" <?php if ($this->_tpl_vars['empty_data'] == 1): ?>style="display:none;"<?php else: ?>style="display:block;"<?php endif; ?> class="pageContent">
                 <div id="accordion-keyword">
                 </div><!-- #accordion-keyword -->
				 <div id="accordion-keyword_page" class="paging">
                 </div><!-- #accordion-keyword_page -->
                <?php echo '
                <script type="text/javascript" >
				jQuery(document).ready(function() {
					 $(\'.topic-group a.showGroup\').click(function() {
                          var targetID = jQuery(this).attr(\'href\');
                           $(".topic-group a.showGroup").removeClass("active");
                           $(".topic-group a.showGroup").addClass("nonActive");
                           $(".accordion-content").fadeOut();
                           $(this).addClass("active");
                           $(this).removeClass("nonActive");
						   $(targetID).toggle(\'slow\');
					 });
				});
                </script>
                '; ?>

           	</div>
        </div><!-- #container -->
    </div><!-- #main-container -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_compare.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	
	<script>
	var encriptURLManageGroup = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=campaign&act=edit_topic_group'), $this);?>
";
	
	<?php echo '
	$(".manageGroup").live(\'click\', function(){
		window.location.href=encriptURLManageGroup;
	});
	'; ?>

	</script>