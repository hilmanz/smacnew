<?php /* Smarty version 2.6.13, created on 2012-11-22 16:42:50
         compiled from smac/twitterPage.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/twitterPage.html', 133, false),)), $this); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td colspan="4">
        <div class="bgBlue">
            <div class="titles">
                <h3 class="lfilters">
                    <select name="d" id="twitDailySelect">
						<option value="twitVolbySentiment">Potential Impact Index</option>
						<option value="twitVolbyImpression">Volume by Impression</option>
						<option value="twitVolbyMention">Volume by Mention</option>
						<option value="twitVolbySentiment2">Volume by Sentiment</option>
					</select>
                </h3>
            </div>
            <div id="twitDailyVolume" class="theChart" style="width:943px;height:280px;background:white;">
            </div>
        </div>
    </td>
  </tr>
  <tr>
  	<td colspan="4">
            <div class="list-box">
                <div id="twitTotalPost" class="box firstBox">
                    <span>Total Posts</span>
                    <a class="helpsmall theTolltip" href="#" title="Total Posts."></a>
                    <h1></h1>
                    <span class="triangle fleft">&nbsp;</span>       
                    <span class="counts"></span>
                </div><!-- end .box-->
                <div id="twitOriginalPost" class="box">
                    <span>Original Posts</span>
                    <a class="helpsmall theTolltip" href="#" title="Original Posts."></a>
                     <h1></h1>
					 <span class="triangle fleft">&nbsp;</span>
					                     <span class="counts"></span>
                </div><!-- end .box-->
                <div id="twitRetweet" class="box">
                    <span>RT</span>
                    <a class="helpsmall theTolltip" href="#" title="Retweet."></a>
                     <h1></h1>
					 <span class="triangle fleft">&nbsp;</span>
					 <span class="counts"></span>
					                 </div><!-- end .box-->
                <div id="twitPotentialImpression" class="box">
                    <span>Potential Impressions</span>
                     <a href="#" class="helpsmall theTolltip" title="The absolute reach or a count of how many eyeballs your keyword(s) have generated, mentions times followers."></a>
                     <h1><?php echo $this->_tpl_vars['impressi']; ?>
</h1>
					 <span class="triangle fleft">&nbsp;</span>
					 <span class="counts"></span>
                                   </div><!-- end .box-->
                <div id="twitPeople" class="box">
                    <span>People</span>
                    <a class="helpsmall theTolltip" href="#" title="People."></a>
                     <h1></h1>
					 <span class="triangle fleft">&nbsp;</span>
					 <span class="counts"></span>
                                   </div><!-- end .box-->
            </div><!-- end .list-box-->
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">
    	<div id="topKeyword" class="favorite-word">
			<h1>Top Keywords</h1>		
			<div id="twitTopWords" class="wordclouds"></div>
        </div>
    </td>
    <td align="left" valign="top">
        <div id="pieChartSentiment">
            <div class="titles">
                <h3>Sentiment</h3>
            </div>
            <div class="bgGreys">
                <div id="twitSentiment" class="theChart" style="width:280px;height:323px;">
                   
                </div>
            </div>
        </div><!-- # End pieChart1-->
    </td>
    <td align="left" valign="top">
            <div class="thePeoples">
                <h1>Top People</h1>
  				<a class="smallArrow" href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=kol'), $this);?>
#tabs/tabTwitter">View All</a>
                <div id="twitTopPeople" class="content">
                            
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
#twitterTab">View All</a>
          </div>
          <div id="channels" class="bgGreys">
            <div id="tab-twitter">
              <div id="twitTopPost">
                
              </div>
            </div>
          </div><!-- .bgGreys -->
        </div><!-- .topPost -->
    </td>
  </tr>
</table>
<script>
<?php echo '
	$("#twitDailySelect").live(\'change\', function() {
		if(twitCount != 0){
			channelPerformance(\'twitDailyVolume\', this.value, \'Twitter\');
		}
    });
'; ?>

</script>