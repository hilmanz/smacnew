<?php /* Smarty version 2.6.13, created on 2013-01-03 15:28:58
         compiled from smac/master.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
	<head>
	<?php echo $this->_tpl_vars['meta']; ?>

	<?php if ($this->_tpl_vars['errLogin']): ?>
	<script>
		alert("Sorry, wrong username and / or passwords, please try again !");
	</script>
	<?php endif; ?>
	</head>
	<body id="master" <?php if ($this->_tpl_vars['PAGE'] == 'login'): ?>style="background:url(images/bg.gif) repeat #bfbfbf;"<?php endif; ?>>
		<div id="body">
			<?php echo $this->_tpl_vars['header']; ?>

			<?php echo $this->_tpl_vars['mainContent']; ?>

			<?php echo $this->_tpl_vars['footer']; ?>

			<div class="tip-sum tip-yellow2">
				<div class="tip-inner tip-bg-image"></div>
				<div class="tip-arrow-bottom2" style="visibility: visible;"></div>
			</div>
		</div>
		<script>
			<?php echo '
				var check = $("a#menuAtas").attr("no");
				//var check = 1;
				for (i=1;i<=6;i++){
					if (check == i){
						$("a.menuAtas"+i).addClass("select"+i);
					}
				}
				var url = window.location.href;
				url = url.substr(url.lastIndexOf("/") + 1);
				$(".menu-icon").find("a[href=\'" + url + "\']").addClass("current");
			'; ?>

		</script>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_sentiment.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_ka_conversation.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_unmark_tweet.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_estimate_profiling.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_new_group.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_message.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_map_feed.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_video.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'smac/popup_screenshot.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		
    <?php echo '
	<script type="text/javascript">
		setTimeout(function() {
  			notify();
		}, 60000); 
	</script>
    <script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push([\'_setAccount\', \'UA-867847-33\']);
	  _gaq.push([\'_setDomainName\', \'.smacapp.com\']);
	  _gaq.push([\'_trackPageview\']);
	
	  (function() {
		var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
		ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
		var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
    
    '; ?>

	</body>
</html>