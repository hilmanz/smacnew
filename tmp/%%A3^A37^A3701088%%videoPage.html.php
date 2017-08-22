<?php /* Smarty version 2.6.13, created on 2012-11-22 16:42:50
         compiled from smac/videoPage.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/videoPage.html', 52, false),)), $this); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td colspan="4">
        <div class="bgRed">
            <div class="titles">
                <h3 class="lfilters">Video Channel Performance</h3>
				<div class="wfilters">
					<select name="d" id="videoDailySelect">
						<option value="videoVolbyMention">Volume by Mention</option>
						<option value="videoVolbyImpression">Volume by Impression</option>
						<option value="videoVolbyLike">Volume by Likes</option>
						<option value="videoVolbyDislike">Volume by Dislikes</option>					
					</select>
				</div>
            </div>
            <div id="videoDailyVolume" class="theChart" style="width: 943px; height: 280px; background: none repeat scroll 0% 0% white; position: relative;">
                
            </div>
        </div>
    </td>
  </tr>
  <tr>
  	<td valign="top">
            <div class="list-box">
                <div id="videoTotalPost" class="box">
                    <span>Total Videos</span>
                    <a class="helpsmall theTolltip" href="#" title="Total Videos"></a>
					<h1></h1>
					<span class="triangle fleft">&nbsp;</span>
					<span class="counts"></span>
				
                </div><!-- end .box-->
                <div id="videoTotalPeople" class="box">
                    <span>Total People</span>
                    <a class="helpsmall theTolltip" href="#" title="Total People"></a>
					<h1></h1>
					<span class="triangle fleft">&nbsp;</span>
					<span class="counts"></span>
					
                </div><!-- end .box-->
            </div><!-- end .list-box-->
    </td>
    <td align="left" valign="top" width="310">
    	<div id="topKeyword" class="favorite-word">
			<h1>Comment Wordcloud</h1>				
            <div id="videoTopWords" class="wordclouds"></div>
        </div>
    </td>
    <td align="left" valign="top">
            <div class="thePeoples">
                <h1>Top People</h1>
  			    <a class="smallArrow" href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=kol'), $this);?>
#tabVideo">View All</a>
                <div id="videoTopPeople" class="content">
                     
                </div><!-- .content-->
            </div><!-- .opinion-->
    </td>
  </tr>
  <tr>
    <td colspan="4">
        <div id="topPost">
          <div class="titles">
            <h3>Top Posts</h3>
    	    <a class="smallArrow" href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=allpost'), $this);?>
#videoTab">View All</a>
          </div>
          <div id="channels" class="bgGreys">
            <div id="tab-twitter">
              <div id="videoTopPost">
              
              </div>
            </div>
          </div><!-- .bgGreys -->
        </div><!-- .topPost -->
    </td>
  </tr>
</table>

<script>
<?php echo '
	$("#videoDailySelect").live(\'change\', function() {
		if(videoCount != 0){
			channelPerformance(\'videoDailyVolume\', this.value, \'Video\');
		}
    });
'; ?>

</script>