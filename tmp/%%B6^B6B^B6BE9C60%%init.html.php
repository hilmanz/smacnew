<?php /* Smarty version 2.6.13, created on 2013-01-03 15:28:46
         compiled from smac/js/init.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/js/init.html', 1, false),)), $this); ?>
var popupUrlMentions = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=profile&act=chartmentions&ajax=1&type=mentions'), $this);?>
";
var popupUrlImpressions = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=profile&act=chartmentions&ajax=1&type=impressions'), $this);?>
";
var popupUrlRt = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=profile&act=chartrt&ajax=1'), $this);?>
";
var popupUrlChartSentimentOvertime = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=profile&act=chart_sentiment_overtime&ajax=1&type=sentiment'), $this);?>
";
var popupUrlChartMentionOvertime = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=profile&act=chart_sentiment_overtime&ajax=1&type=mention'), $this);?>
";
var popupProfileUrl = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=profile&act=popup'), $this);?>
";
var editSentimentUrl = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=sentiment&act=getsave&ajax=1'), $this);?>
";
var relatedKeywordUrl = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=campaign&act=getrelatedkeywords&ajax=1'), $this);?>
";
var popupProfileTweetUrl = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=profile&act=getprofiletweets&ajax=1'), $this);?>
";
var popupWorkflowTweets = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=workflow&act=getTweets&ajax=1'), $this);?>
";
var popupProfileWCUrl = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=profile&act=get_wordcloud&ajax=1'), $this);?>
";
var wfApplyExc = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=workflow&act=apply_exclude&ajax=1'), $this);?>
";
var wfApplyExcAll = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=workflow&act=apply_exclude_all&ajax=1'), $this);?>
";
var wfApplyUndo = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=workflow&act=apply_undo&ajax=1'), $this);?>
";
var wfFoldersURL = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=workflow&act=get_folders&ajax=1'), $this);?>
";
var notifyURL = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=monitor&act=info&ajax=1'), $this);?>
";
var curr_wc = "";
var exc_status_url = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=monitor&act=exc_status&ajax=1'), $this);?>
";
var pdf = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=topsummary&act=download_pdf&ajax=1'), $this);?>
";
var reports = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=topsummary&act=report_list&ajax=1'), $this);?>
";