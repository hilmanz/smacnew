<?php /* Smarty version 2.6.13, created on 2013-01-03 15:28:57
         compiled from smac/meta.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'intval', 'smac/meta.html', 53, false),array('function', 'encrypt', 'smac/meta.html', 351, false),)), $this); ?>
<?php echo '
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="alexaVerifyID" content="aj6iu-GC1XzCquo-zsl3gOlgPeI" />
<meta name="google-site-verification" content="eBExl19sdO2bIrSM9_N1d48976h6BqwhZa2xoji3vj8" />
<meta name="keywords" content="Social Media Monitoring Indonesia, Social media listening, social media listening tool, social media marketing, social media monitoring, social media sentiment, social media engagement, social media tool,social media action center"/>
<meta name="description" content="SMAC essentially is a social media-monitoring tool, but with substantially more to help you measure the pulse of your campaign or listening exercise." />
<title>SMAC - SOCIAL MEDIA ACTION CENTER</title>
<link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link rel="icon" href="images/favicon.png"> 
<link href=\'https://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic\' rel=\'stylesheet\' type=\'text/css\'>
'; ?>

<?php if ($this->_tpl_vars['PAGE'] == 'dashboard' || $this->_tpl_vars['PAGE'] == 'kol' || $this->_tpl_vars['PAGE'] == 'ka' || $this->_tpl_vars['PAGE'] == 'liveTracked' || $this->_tpl_vars['PAGE'] == 'marketPage' || $this->_tpl_vars['PAGE'] == 'workflows' || $this->_tpl_vars['PAGE'] == 'allpost' || $this->_tpl_vars['PAGE'] == 'overview'): ?>
	<link href="css/smac_new.css" type="text/css" rel="stylesheet" />
<?php else: ?>
	<link href="css/smac.css" type="text/css" rel="stylesheet" />
	<link href="css/smac_new.css" type="text/css" rel="stylesheet" />
<?php endif; ?>

<?php echo '
<link href="css/smac-add.css" type="text/css" rel="stylesheet" />
<link href="css/tree.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" media="all" href="css/niceforms-default.css" />
<link href="css/demo_table_jui.css" type="text/css" rel="stylesheet" />
<link href="css/jqcloud.css" type="text/css" rel="stylesheet" />
<link href="css/smac_wordcloud.css" type="text/css" rel="stylesheet" />
<!--[if IE ]>
  <link href="css/fix-ie.css" rel="stylesheet" type="text/css">
<![endif]-->
<script type="text/javascript" src="js/php.js"></script>
<script type="text/javascript" src="js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jqcloud-0.2.8.js"></script>
<script type="text/javascript" src="js/jq_imagepreloader.js"></script>
<script type="text/javascript" src="js/underscore-min.js"></script>
<script type="text/javascript" src="js/backbone-min.js"></script>
<script type="text/javascript" src="js/charts/highcharts.js"></script>
'; ?>

<script type="text/javascript" src="init_js.php"></script>
<script>
	var use_video = false;
</script>
<?php if ($this->_tpl_vars['wf_folders']): ?>
<script type="text/javascript">
	var wf_folders = JSON.parse('<?php echo $this->_tpl_vars['wf_folders']; ?>
');
</script>
<?php endif;  if ($this->_tpl_vars['PAGE'] == 'topsummary'): ?>
<script type="text/javascript" src="js/report.js"></script>
<?php endif; ?>

<script type="text/javascript">
	var access_token = "<?php echo $this->_tpl_vars['access_token']; ?>
";
	var campaign_id = <?php echo ((is_array($_tmp=$this->_tpl_vars['campaign_id'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
;
	var smac_api_url = "<?php echo $this->_tpl_vars['api_url']; ?>
";
</script>
<?php echo '
<!-- SMAC JS -->
<script type="text/javascript" src="js/locale.js"></script>
<script type="text/javascript" src="js/smac.js"></script>
<script type="text/javascript" src="js/workflow.js"></script>
<script type="text/javascript" src="js/service.js"></script>

<!-- GLOBAL JS -->
<script type="text/javascript" src="js/modules/global.js"></script>

'; ?>

<?php if ($this->_tpl_vars['PAGE'] == 'dashboard'): ?>
	<script type="text/javascript" src="js/svg.js"></script>
	<script type="text/javascript" src="js/modules/chart.js"></script>
	<script type="text/javascript" src="js/modules/dashboard.js"></script>
<?php elseif ($this->_tpl_vars['PAGE'] == 'kol'): ?>
	<script type="text/javascript" src="js/modules/chart.js"></script>
	<script type="text/javascript" src="js/modules/kol.js"></script>
<?php elseif ($this->_tpl_vars['PAGE'] == 'ka'): ?>
	<script type="text/javascript" src="js/svg.js"></script>
	<script type="text/javascript" src="js/modules/chart.js"></script>
	<script type="text/javascript" src="js/modules/keyword_analysis.js"></script>
<?php elseif ($this->_tpl_vars['PAGE'] == 'liveTracked'): ?>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBCBmikvbbf7e_Bl5599DHrypJLOgyAgZc&sensor=false">
    </script>
    <script type="text/javascript" src="https://google-maps-utility-library-v3.googlecode.com/svn/tags/markermanager/1.0/src/markermanager.js"></script>
	<script type="text/javascript" src="js/markerclusterer.js"></script>
	<script type="text/javascript" src="js/modules/chart.js"></script>
	<script type="text/javascript" src="js/modules/liveTracked.js"></script>
	
<?php elseif ($this->_tpl_vars['PAGE'] == 'marketPage'): ?>
	<script type='text/javascript' src="js/template.js"></script>
	<script type='text/javascript' src='https://www.google.com/jsapi'></script>
	<script type="text/javascript" src="js/modules/chart.js"></script>
	<script type="text/javascript" src="js/modules/marketPage.js"></script>
<?php elseif ($this->_tpl_vars['PAGE'] == 'allpost'): ?>
	<script type="text/javascript" src="js/modules/chart.js"></script>
	<script type="text/javascript" src="js/modules/allpost.js"></script>
<?php elseif ($this->_tpl_vars['PAGE'] == 'overview'): ?>
	<script type="text/javascript" src="js/svg.js"></script>
	<script type="text/javascript" src="js/modules/chart.js"></script>
	<script type="text/javascript" src="js/modules/overview.js"></script>
<?php elseif ($this->_tpl_vars['PAGE'] == 'topsummary'): ?>
	<script type="text/javascript" src="js/modules/downloadReport.js"></script>
<?php elseif ($this->_tpl_vars['PAGE'] == 'workflows'): ?>
	<script type="text/javascript" src="js/modules/chart.js"></script>
	<script type="text/javascript" src="js/modules/workflows.js"></script>
<?php endif; ?>


<?php echo '
<!-- JQUERY UI -->
<link type="text/css" href="js/themes/base/ui.all.css" rel="stylesheet" />
	<!--<script type="text/javascript" src="js/jquery-1.3.2.js"></script>-->
	<script type="text/javascript" src="js/ui/ui.core.js"></script>
	<script type="text/javascript" src="js/ui/ui.progressbar.js"></script>
	<script type="text/javascript" src="js/ui/ui.resizable.js"></script>
	<script type="text/javascript" src="js/ui/ui.draggable.js"></script>
    <script type="text/javascript" src="js/ui/ui.accordion.js"></script>
   
    <script type="text/javascript"> 
      $(document).ready(function(){
        $("#accordion-keyword").accordion({
  		      active: 1
        });
	    });
	  
		jQuery(document).ready(function($) {
			/*------------POP UP------------*/	
			jQuery("a.showPopup").click(function(){
				var targetID = jQuery(this).attr(\'href\');
				jQuery(targetID).fadeIn();
				jQuery(targetID).addClass(\'visible\');
				jQuery("#bgPopup").fadeIn();
			});
			jQuery("a.popupClose").click(function(){
				jQuery(".popupStarted").fadeOut();
				jQuery("#bgPopup").fadeOut();
			});
			jQuery("#btnReady").click(function(){
				jQuery(".popupStarted").fadeOut();
				jQuery("#bgPopup").fadeOut();
			});
			jQuery("#btnNextPopup").click(function(){
				jQuery("#popupStep1").fadeOut();
				jQuery("#popupStep2").fadeIn();
			});
		});
	</script>
    
   
	<style type="text/css">
		.ui-progressbar-value { background-image: url(images/bar.gif); }
	</style>

	<script type="text/javascript">
	$(function() {
		$("#draggable #profile,#popup-sentiment,#popup-influenced-list, #popupmap").draggable();
		$( "#popup-unmark" ).draggable({ cancel: ".content-popup" });
		$( ".content-popup" ).disableSelection();
	});
	</script>
<script language="javascript" type="text/javascript" src="js/niceforms.js"></script>
<!-- PNG FIX -->
<script type="text/javascript" src="js/jquery.pngFix.pack.js"></script> 
<script type="text/javascript" src="js/jquery.pngFix.js"></script> 
<script type="text/javascript" src="js/dashboard.js"></script>
<!-- TAB -->
<script type="text/javascript">
$(document).ready(function() {
	$(\'div\').pngFix( );
	
	//TAB...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content
	
	//On Click Event
	$("ul.tabs li").live(\'click\', function() {
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		wf_active_tab = activeTab;
		if(activeTab=="#tab-custom"){
			wf_custom_tab($(this));
		}
		
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});
	//TAB...
	$(".tab_content2").hide(); //Hide all content
	$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
	$(".tab_content2:first").show(); //Show first tab content
	//On Click Event
	$("ul.tabs2 li").click(function() {
		$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content2").hide(); //Hide all tab content
		
		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

	
	
	//topltip
	
    $(".tip_trigger").hover(function(){
        tip = $(this).find(\'.tip\');
        tip.show(); //Show tooltip
    }, function() {
        tip.hide(); //Hide tooltip
    }).mousemove(function(e) {
        var mousex = e.pageX - 50; //Get X coodrinates
        var mousey = e.pageY + 20; //Get Y coordinates
        var tipWidth = tip.width(); //Find width of tooltip
        var tipHeight = tip.height(); //Find height of tooltip

        //Distance of element from the right edge of viewport
        var tipVisX = $(window).width() - (mousex + tipWidth);
        //Distance of element from the bottom of viewport
        var tipVisY = $(window).height() - (mousey + tipHeight);

        if ( tipVisX < 20 ) { //If tooltip exceeds the X coordinate of viewport
            mousex = e.pageX - tipWidth - 20;
        } if ( tipVisY < 20 ) { //If tooltip exceeds the Y coordinate of viewport
            mousey = e.pageY - tipHeight - 20;
        }
        //Absolute position the tooltip according to mouse position
        tip.css({  top: mousey, left: mousex });
    });
	
	// POP UP
	$(\'a.poplight[href^=#]\').live(\'click\',function() {
		var popID = $(this).attr(\'rel\'); //Get Popup Name
		var popURL = $(this).attr(\'href\'); //Get Popup href to define size
		$("body").scrollTop(0);
		//Pull Query & Variables from href URL
		var query= popURL.split(\'?\');
		var dim= query[1].split(\'&\');
		var popWidth = dim[0].split(\'=\')[1]; //Gets the first query string value
		//get subject name.
		var qsubject = (dim[1].split(\'=\')[0]).toString();
		var qvalue = (dim[1].split(\'=\')[1]).toString();
		
		var url = query[1];
		
		//Fade in the Popup and add close button
		$(\'#\' + popID).fadeIn().css({ \'width\': Number( popWidth ) }).prepend(\'<a href="#" class="close"><img src="images/close.png" class="btn_close" title="Close Window" alt="Close"></a>\');

    	//Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
    	//var popMargTop = ($(\'#\' + popID).height() + 400) / 2;
    	var popMargLeft = ($(\'#\' + popID).width() + 80) / 2;

		//Apply Margin to Popup
		$(\'#\' + popID).css({
		    //\'margin-top\' : -popMargTop,
		    \'margin-left\' : -popMargLeft
		});

		//Fade in Background
		$(\'body\').append(\'<div id="fade"></div>\'); //Add the fade layer to bottom of the body tag.
		$(\'#fade\').css({\'filter\' : \'alpha(opacity=80)\'}).fadeIn(); //Fade in the fade layer - .css({\'filter\' : \'alpha(opacity=80)\'}) is used to fix the IE Bug on fading transparencies

		if(popID=="popupmsg"){
			//alert(\'popupmessage\');
		}else{
			if(qsubject=="id"){
				getProfile(url);
			}else if(qsubject=="topic"){
				getCreateGroupPopup();
			}else{
				//identify current clicked wordcloud 
				curr_wc = $(this).parent().attr(\'id\');
				//workflow popup
				getWorkflowPopup(qvalue,$(this).html());
			}
		}
		
		return false;
	});

	//Close Popups and Fade Layer
	$(\'a.close,a.cancelgroup\').live(\'click\', function(event) { //When clicking on the close or fade layer...
		event.preventDefault();
		globalPageInit = 0;
		$(\'#fade\').css(\'opacity\', \'0\');
		$(\'#fade , .popup_block\').fadeOut(function() {
			$(\'#fade, a.close\').remove();  //fade them both out
		});
		//return false;
	});
	// table zebra
		$("table.zebra tr:odd").addClass("ganjil");
		$("table.zebra tr:even").addClass("genap");
		$("table.zebra th").parent().addClass("tbheading");
	// message top
		$(".close-message-top").click(function() {
			$("#message-top").fadeOut("slow");
		});
	// Accordion
		$(".fadeout-top-summary").click(function() {
			$("#content-top-summary").fadeOut("slow");
			$("#top-summary a.hide").css({\'display\' : \'none\'});
			$("#top-summary a.show").css({\'display\' : \'block\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
		});
		$(".fadeout-top-50-keyword").click(function() {
			$("#content-top-50-keyword").fadeOut("slow");
			$("#top-50-keyword a.hide").css({\'display\' : \'none\'});
			$("#top-50-keyword a.show").css({\'display\' : \'block\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
		});
		$(".fadeout-top-5-keyword-conversation").click(function() {
			$("#content-top-5-keyword-conversation").fadeOut("slow");
			$("#top-5-keyword-conversation a.hide").css({\'display\' : \'none\'});
			$("#top-5-keyword-conversation a.show").css({\'display\' : \'block\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
		});
		$(".fadeout-top-25-influencers").click(function() {
			$("#content-top-25-influencers").fadeOut("slow");
			$("#top-25-influencers a.hide").css({\'display\' : \'none\'});
			$("#top-25-influencers a.show").css({\'display\' : \'block\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
		});
		$(".fadeout-key-issues").click(function() {
			$("#content-key-issues").fadeOut("slow");
			$("#key-issues a.hide").css({\'display\' : \'none\'});
			$("#key-issues a.show").css({\'display\' : \'block\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
		});
		$(".fadeout-top50links").click(function() {
			$("#content-top50links").fadeOut("slow");
			$("#top50links a.hide").css({\'display\' : \'none\'});
			$("#top50links a.show").css({\'display\' : \'block\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
		});
		$(".fadeout-branded-twitter-account").click(function() {
			$("#content-branded-twitter-account").fadeOut("slow");
			$("#branded-twitter-account a.hide").css({\'display\' : \'none\'});
			$("#branded-twitter-account a.show").css({\'display\' : \'block\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
		});
		$(".fadein-top-summary").click(function() {
			$("#content-top-summary").fadeIn("slow");
			$("#top-summary a.hide").css({\'display\' : \'block\'});
			$("#top-summary a.show").css({\'display\' : \'none\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
			'; ?>

			dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=topsummary&act=topic_summary&ajax=1','filter_from_date' => $this->_tpl_vars['filter_from_date'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'content-top-summary','Loading Topic Summary');
			<?php echo '
		});
		$(".fadein-top-50-keyword").click(function() {
			$("#content-top-50-keyword").fadeIn("slow");
			$("#top-50-keyword a.hide").css({\'display\' : \'block\'});
			$("#top-50-keyword a.show").css({\'display\' : \'none\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
			'; ?>

			dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=topsummary&act=top_50_keywords&ajax=1','filter_from_date' => $this->_tpl_vars['filter_from_date'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'content-top-50-keyword','Loading Top 50 Keywords');
			<?php echo '
		});
		$(".fadein-top-5-keyword-conversation").click(function() {
			$("#content-top-5-keyword-conversation").fadeIn("slow");
			$("#top-5-keyword-conversation a.hide").css({\'display\' : \'block\'});
			$("#top-5-keyword-conversation a.show").css({\'display\' : \'none\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
			'; ?>

			dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=topsummary&act=top_5_conversation&ajax=1','filter_from_date' => $this->_tpl_vars['filter_from_date'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'content-top-5-keyword-conversation','Loading Top 5 Conversations');
			<?php echo '
		});
		$(".fadein-top-25-influencers").click(function() {
			$("#content-top-25-influencers").fadeIn("slow");
			$("#top-25-influencers a.hide").css({\'display\' : \'block\'});
			$("#top-25-influencers a.show").css({\'display\' : \'none\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
			'; ?>

			dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=topsummary&act=top_influencers&ajax=1','filter_from_date' => $this->_tpl_vars['filter_from_date'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'content-top-25-influencers','Loading Top Influencers');
			<?php echo '
		});
		$(".fadein-key-issues").click(function() {
			$("#content-key-issues").fadeIn("slow");
			$("#key-issues a.hide").css({\'display\' : \'block\'});
			$("#key-issues a.show").css({\'display\' : \'none\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
			'; ?>

			dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=topsummary&act=key_issues&ajax=1','filter_from_date' => $this->_tpl_vars['filter_from_date'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'content-key-issues','Loading Key Issues');
			<?php echo '
		});
		$(".fadein-top50links").click(function() {
			$("#content-top50links").fadeIn("slow");
			$("#top50links a.hide").css({\'display\' : \'block\'});
			$("#top50links a.show").css({\'display\' : \'none\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
			'; ?>

			dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=topsummary&act=top_links&ajax=1','filter_from_date' => $this->_tpl_vars['filter_from_date'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'content-top50links','Loading Top 50 Links');
			<?php echo '
		});
		$(".fadein-branded-twitter-account").click(function() {
			$("#content-branded-twitter-account").fadeIn("slow");
			$("#branded-twitter-account a.hide").css({\'display\' : \'block\'});
			$("#branded-twitter-account a.show").css({\'display\' : \'none\'});
			$("span.tooltip-expand").css({\'display\' : \'none\'});
			'; ?>

			dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=topsummary&act=brand&ajax=1','filter_from_date' => $this->_tpl_vars['filter_from_date'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'content-branded-twitter-account','Loading Brand Data');
			<?php echo '
		});
	
});
'; ?>


<?php echo '

</script>

<script src="js/scroll-startstop.events.jquery.js" type="text/javascript"></script>
<!-- TREE MENU -->
<script type="text/javascript" src="js/enhance.js"></script>		
<script type="text/javascript">
	// Run capabilities test
	enhance({
		loadScripts: [
			\'js/jQuery.tree.js\',
			\'js/tree.js\'
		]	
	});   
</script>
'; ?>



<?php echo '
    <script>
	// Anchor Effect
    $(function() {
    
            function filterPath(string) {
                    return string
                    .replace(/^\\//,\'\')
                    .replace(/(index|default).[a-zA-Z]{3,4}$/,\'\')
                    .replace(/\\/$/,\'\');
            }
    
            var locationPath = filterPath(location.pathname);
            var scrollElem = scrollableElement(\'html\', \'body\');
    
            // Any links with hash tags in them (can\'t do ^= because of fully qualified URL potential)
            $(\'a.anchor[href*=#]\').each(function() {
    				
                    // Ensure it\'s a same-page link
                    var thisPath = filterPath(this.pathname) || locationPath;
                    if (  locationPath == thisPath
                            && (location.hostname == this.hostname || !this.hostname)
                            && this.hash.replace(/#/,\'\') ) {
    
                                    // Ensure target exists
                                    var $target = $(this.hash), target = this.hash;
                                    if (target) {
    
                                            // Find location of target
                                            var targetOffset = $target.offset().top;
                                            $(this).click(function(event) {
    
                                                    // Prevent jump-down
                                                    event.preventDefault();
    
                                                    // Animate to target
                                                    $(scrollElem).animate({scrollTop: targetOffset}, 1000, function() {
    
                                                            // Set hash in URL after animation successful
                                                            location.hash = target;
    
                                                    });
                                            });
                                    }
                    }
    
            });
    
            // Use the first element that is "scrollable"  (cross-browser fix?)
            function scrollableElement(els) {
                    for (var i = 0, argLength = arguments.length; i <argLength; i++) {
                            var el = arguments[i],
                            $scrollElement = $(el);
                            if ($scrollElement.scrollTop()> 0) {
                                    return el;
                            } else {
                                    $scrollElement.scrollTop(1);
                                    var isScrollable = $scrollElement.scrollTop()> 0;
                                    $scrollElement.scrollTop(0);
                                    if (isScrollable) {
                                            return el;
                                    }
                            }
                    }
                    return [];
            }
    
    });
	// load images
	
	$(document).ready(preload);
	
	function preload(){
	
	
		$.preload([
			"images/bg_button_login_hover.png",
			"images/bg_password_active.png",
			"images/bg_password_active2.png",
			"images/bg_account_domain_active.png",
			"images/bg_username_active2.png",
			"images/bg_username_active.png",
			"images/bg_username_active2.png",
			"images/bg_password2.png",
			"images/bg_username2.png",
			"images/btn_go_hover.png",
			"images/campaign-control_hover.png",
			"images/icon_mobile_active.png",
			"images/icon_web_active.png",
			
			"images/icon_menu_hover.png",
			"images/icon_menu_summary.png",
			"images/icon_menu_summary_hover.png",
			"images/icon_menu.png",
			"images/smacwait_btn.png",
			"images/smacwait_btn_hover.png",
			"images/btn_next_new_topic.jpg",
			"images/btn_next_new_topic_hover.jpg",
			"images/btn-green.png",
		], {
			init: function(loaded, total) {
			//console.log("loading: " + loaded + "/" + total);
			},
			loaded: function(img, loaded, total) {
			//console.log("loaded: " + loaded + "/" + total);	
			}
		});
	
	}
    </script>
'; ?>

<?php echo '
<script src="js/jquery.poshytip.js" type="text/javascript"></script>
<script>
	$(function() {
		var $elem = $(\'body\');
		$(\'a.poplight\').click(
			function (e) {
				$(\'html, body\').animate({scrollTop: \'0px\'}, 800);
			}
		);
	});
	//<![CDATA[
	$(function(){
		$(\'.theTolltip\').poshytip({
			liveEvents: true
		});
	});
	//]]>
</script>
'; ?>