<?php /* Smarty version 2.6.13, created on 2012-11-22 16:42:50
         compiled from smac/webPage.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/webPage.html', 70, false),)), $this); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td colspan="4">
        <div class="bgOrange">
            <div class="titles">
                <h3 class="lfilters">Website Channel Performance</h3>
				<div class="wfilters">
					<select name="d" id="webDailySelect">
						<option value="webVolbyMention">Volume by Mention</option>
											
					</select>
				</div>
            </div>
            <div id="webDailyVolume" class="theChart" style="width: 943px; height: 280px; background: none repeat scroll 0% 0% white; position: relative;">
                
            </div>
        </div>
    </td>
  </tr>
  <tr>
  	<td valign="top">
            <div class="list-box">
                <div id="webTotalPost" class="box">
                    <span>Total Posts</span>
                    <a class="helpsmall theTolltip" href="#" title="Total Posts"></a>
					<h1></h1>
					<span class="triangle fleft">&nbsp;</span>
					<span class="counts"></span>
					                </div><!-- end .box-->
                <div id="webLikes" class="box">
                    <span>Websites</span>
                    <a class="helpsmall theTolltip" href="#" title="Websites"></a>
					<h1></h1>
					<span class="triangle fleft">&nbsp;</span>
					<span class="counts"></span>
					                </div><!-- end .box-->
            </div><!-- end .list-box-->
    </td>
    <td align="left" valign="top" width="310">
    	<div id="topKeyword" class="favorite-word">
			<h1>Top Keywords</h1>				
            <div id="webTopWords" class="wordclouds"></div>
        </div>
    </td>
    <td align="left" valign="top">
            <div class="thePeoples">
                <h1>Top Websites</h1>
  			    <a class="smallArrow" href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=kol'), $this);?>
#tabWeb">View All</a>
                <div id="webTopPeople" class="content">
                     
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
#tabs/webTab">View All</a>
          </div>
          <div id="channels" class="bgGreys">
            <div id="tab-twitter">
              <div id="webTopPost">
              
              </div>
            </div>
          </div><!-- .bgGreys -->
        </div><!-- .topPost -->
    </td>
  </tr>
</table>

<script>
<?php echo '
	$("#webDailySelect").live(\'change\', function() {
		if(blogCount != 0){
			channelPerformance(\'webDailyVolume\', this.value, \'Web\');
		}
    });
'; ?>

</script>