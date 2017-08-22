<?php /* Smarty version 2.6.13, created on 2012-11-22 16:42:50
         compiled from smac/summaryPage.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/summaryPage.html', 80, false),)), $this); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="w1" align="left" valign="top">
            <div id="sumChart" class="bgGreen">
                <div class="titles">
                    <h3>All channel performance</h3>
                    <div class="wfilters">
                        <select id="dailySelect" name="d">
							<option value="sumVolbyMention">Volume by Mention</option>
                            <option value="sumVolbyImpression">Volume by Impression</option>
                            <option value="sumVolbyPositive">Volume by Positive Sentiment</option>
							<option value="sumVolbyNegative">Volume by Negative Sentiment</option>
                        </select>
                    </div>
                </div>
                <div id="sumVolumebySentiment" class="theChart" style="width:600px;height:207px;background: white;">
				</div>
            </div>
            <div id="sumList" class="list-box">
                <div id="sumPII" class="box firstBox">
                    <span>People &amp; Sites</span>
                    <a class="helpsmall theTolltip" href="#" title="People &amp; Sites."></a>
                    <h1 class="sumPII"></h1>
                    <span class="triangle fleft">&nbsp;</span>
					<span class="counts"></span>
                </div>
                <div id="sumTM" class="box">
                    <span>Total Posts</span>
                     <a href="#" class="helpsmall theTolltip" title="How many times your keywords have been mentioned accumulatively through the topic's timespan."></a>
                     <h1></h1>
					 <span class="triangle fleft">&nbsp;</span>
					 <span class="counts"></span>
					                 </div>
                <div id="sumPI" class="box">
                    <span>Potential Impressions</span>
                     <a href="#" class="helpsmall theTolltip" title="The absolute reach or a count of how many eyeballs your keyword(s) have generated, mentions times followers."></a>
                     <h1></h1>
					 <span class="triangle fleft">&nbsp;</span>
					 <span class="counts"></span>
					                </div>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top" width="330">
                    <div class="favorite-word">
                        <h1>Topic Wordcloud</h1>
						<div id="sumTopWords" class="wordclouds">
						</div>
                    </div>
                </td>
                <td valign="top">
                    <div id="wgkol_new">
						<div class="thePeoples">
							<h1>Top People</h1>
							<a class="smallArrow" href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=kol'), $this);?>
#tabTwitter">View All</a>
							<div id="sumTopPeople" class="content">
							</div><!-- .content-->
						</div><!-- .thePeoples-->
					</div>
                </td>
              </tr>
            </table>
    </td><!-- .w1-->
    <td class="w2" align="left" valign="top">
        <div id="pieChart1">
            <div class="titles">
                <h3>Share of Voice - All Rules</h3>
            </div>
            <div class="bgGreys">
                <div id="sumShareofVoice" class="theChart" style="width: 290px; height: 350px;">
                </div>
            </div>
        </div><!-- # End pieChart1-->
        <div id="pieChart2">
            <div class="titles">
                <h3>Sentiment All Channels</h3>
            </div>
            <div class="bgGreys">
                <div id="sumSentimentAllChannels" class="theChart" style="width: 290px; height: 325px;">
                </div>
            </div>
        </div><!-- # End pieChart2-->
    </td><!-- .w2-->
  </tr>
  <tr>
    <td colspan="2">                    
        <div id="topPost">
          <div class="titles">
            <h3>Top Posts</h3>
           
  		    <a class="smallArrow" href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=allpost'), $this);?>
#twitterTab">View All</a>
          </div>
          <div id="channels" class="bgGreys">
            <div id="tab-twitter">
              <div id="sumTopPost">
              </div>
            </div>
          </div><!-- .bgGreys -->
        </div><!-- .topPost -->
    </td>
  </tr>
</table>

<script>
<?php echo '
	$("#dailySelect").live(\'change\', function() {
		if(sumCount != null){
			if(sumCount != 0){
				allChannelPerformance(this.value);
			}
		}
    });
'; ?>

</script>