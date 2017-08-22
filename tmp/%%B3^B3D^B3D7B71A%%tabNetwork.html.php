<?php /* Smarty version 2.6.13, created on 2012-09-13 14:39:53
         compiled from smac/widgets/tabNetwork.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/widgets/tabNetwork.html', 86, false),)), $this); ?>
<script type="text/javascript">
var default_tab="<?php echo $this->_tpl_vars['default']; ?>
";
</script>
                    <ul class="tabs3" style="width: 400px;">
						<?php if ($this->_tpl_vars['channels'][1]): ?><li id='wgtab1'><a href="#tab-twitter" class="tab-twitter"><span>&nbsp;</span><?php echo $this->_tpl_vars['source_count']['twitter']; ?>
%</a></li><?php endif; ?>
                        <?php if ($this->_tpl_vars['channels'][2]): ?><li id='wgtab2'><a href="#tab-facebook" class="tab-facebook"><span>&nbsp;</span><?php echo $this->_tpl_vars['source_count']['fb']; ?>
%</a></li><?php endif; ?>
						<?php if ($this->_tpl_vars['channels'][3]): ?><li id='wgtab3'><a href="#tab-blog" class="tab-blog"><span>&nbsp;</span><?php echo $this->_tpl_vars['source_count']['blog']; ?>
%</a></li><?php endif; ?>
                    </ul>
                    <div class="tab_container" id="channels" style="min-height:570px;">
						
						<?php if ($this->_tpl_vars['channels'][1]): ?>
                        <div id="tab-twitter" class="tab_content3">
                       		<div id='twitter-topconv'></div>
                            <div class="paging" style="padding:5px 0 0 0;">
                               <a href="<?php echo $this->_tpl_vars['urlcompare']; ?>
" class="see-detail">COMPARE CHANNELS</a>
                            </div>
						</div>
						<?php endif; ?>
						
						<?php if ($this->_tpl_vars['channels'][2]): ?>
                        <div id="tab-facebook" class="tab_content3">
                       	 	<div id='fb-topconv'></div>
                         
                            <div class="paging" style="padding:5px 0 0 0;">
								<a href="<?php echo $this->_tpl_vars['urlcompare']; ?>
" class="see-detail">COMPARE CHANNELS</a>
                            </div><!-- .paging -->
                        </div><!-- #tab-facebook -->
						<?php endif; ?>
						
						<?php if ($this->_tpl_vars['channels'][3]): ?>
                        <div id="tab-blog" class="tab_content3">
                        	<div id='web-topconv'></div>
                        	
                            <div class="paging" style="padding:5px 0 0 0;">
								<a href="<?php echo $this->_tpl_vars['urlcompare']; ?>
" class="see-detail">COMPARE CHANNELS</a>
                            </div><!-- .paging -->
                        </div><!-- #tab-blog -->
						<?php endif; ?>
						
                    </div><!-- .tab_container -->


<?php echo '
<script>

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
	
	$(function() {
		var $elem = $(\'body\');
		$(\'a.poplight\').click(
			function (e) {
				$(\'html, body\').animate({scrollTop: \'0px\'}, 800);
			}
		);
	});
</script>
	<script type="text/javascript" >
        jQuery(document).ready(function(){
        	$(".tab_content3").hide(); //Hide all content
        	switch(default_tab){
	        	case "fb":
	        		'; ?>

	        		dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=fb_top_conversation&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'fb-topconv','Loading Top Conversations');
	            	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=fb_wordcloud&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgwords2','Loading Wordcloud');
	            	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=fb_kol&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgkol2','Loading Top 5 KOLs');
	            	<?php echo '
	        		jQuery("#dtsource").html(\'Facebook\');
	        		$("#home-facebook").show();
	        		$("#home-twitter").hide();
	        		$("#tab-facebook").show();
	        		$("#wgtab2").addClass("active").show(); //Activate first tab
	            break;
	        	case "web":
	        	'; ?>

	        		dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=web_top_conversation&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'web-topconv','Loading Top Conversations');
	               	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=web_wordcloud&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgwords3','Loading Wordcloud');
	               	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=web_kol&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgkol3','Loading Top 5 KOLs');
	               	<?php echo '
	        		jQuery("#dtsource").html(\'Web\');
	        		$("#home-blog").show();
	        		$("#wgtab3").addClass("active").show(); //Activate first tab
	        		$("#tab-blog").show();
		        break;
		        default:
		       		 '; ?>

		        	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=twitter_top_conversation&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'twitter-topconv','Loading Top Conversations');
	            	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=favourite_words&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgwords','Loading Wordcloud');
	            	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=topKOL&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgkol','Loading Key Opinion Leader');
	               	<?php echo '
		        	jQuery("#dtsource").html(\'Twitter\');
		        	$("#home-twitter").show();
					$("#tab-twitter").show();
					$("#wgtab1").addClass("active").show(); //Activate first tab
					
				break;
        	}
	        	//On Click Event
	        	$("ul.tabs3 li").click(function() {
	        		$("ul.tabs3 li").removeClass("active"); //Remove any "active" class
	        		$(this).addClass("active"); //Add "active" class to selected tab
	        		$(".tab_content3").hide(); //Hide all tab content

	        		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
	        		wf_active_tab = activeTab;
	        		if(activeTab=="#tab-custom"){
	        			wf_custom_tab($(this));
	        		}
	        		
	        		$(activeTab).fadeIn(); //Fade in the active ID content
	        		return false;
	        	});
            jQuery("a.tab-facebook").click(function(){
            	'; ?>

            	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=fb_top_conversation&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'fb-topconv','Loading Top Conversations');
            	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=fb_wordcloud&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgwords2','Loading Wordcloud');
            	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=fb_kol&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgkol2','Loading Top 5 KOLs');
            	<?php echo '
            	jQuery("#dtsource").html(\'Facebook\');
                jQuery("#home-facebook").show();
                jQuery("#home-blog").hide();
                jQuery("#home-twitter").hide();
            });
            jQuery("a.tab-twitter").click(function(){
            	'; ?>

            		dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=twitter_top_conversation&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'twitter-topconv','Loading Top Conversations');
	            	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=favourite_words&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgwords','Loading Wordcloud');
	            	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=topKOL&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgkol','Loading Key Opinion Leader');
	               	<?php echo '
            	jQuery("#dtsource").html(\'Twitter\');
                jQuery("#home-facebook").hide();
                jQuery("#home-blog").hide();
                jQuery("#home-twitter").show();
            });
            jQuery("a.tab-blog").click(function(){
            	'; ?>

            	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=web_top_conversation&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'web-topconv','Loading Top Conversations');
               	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=web_wordcloud&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgwords3','Loading Wordcloud');
               	dashcontent("<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=web_kol&ajax=1','filter_date_from' => $this->_tpl_vars['filter_date_from'],'filter_to_date' => $this->_tpl_vars['filter_to_date']), $this);?>
",'wgkol3','Loading Top 5 KOLs');
               	<?php echo '
            	jQuery("#dtsource").html(\'Web\');
                jQuery("#home-twitter").hide();
                jQuery("#home-facebook").hide();
                jQuery("#home-blog").show();
                
            });
        });
    </script>
'; ?>