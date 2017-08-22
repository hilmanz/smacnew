<?php /* Smarty version 2.6.13, created on 2012-11-22 16:42:50
         compiled from smac/facebookPage.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/facebookPage.html', 93, false),)), $this); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td colspan="4">
        <div class="bgOldBlue">
            <div class="titles">
                <h3 class="lfilters">Facebook Performance</h3>
				<div class="wfilters">
					<select name="d" id="fbDailySelect">
						<option value="fbVolbyMention">Performance by Mention</option>
									
					</select>
				</div>
            </div>
            <div id="fbDailyVolume" class="theChart" style="width: 943px; height: 280px; background: none repeat scroll 0% 0% white; position: relative;">
               
            </div>
        </div>
    </td>
  </tr>
  <tr>
  	<td colspan="4">
            <div class="list-box">
                <div id="fbTotalPost" class="box firstBox">
                    <span>Total Posts</span>
                    <a class="helpsmall theTolltip" href="#" title="Total Posts."></a>
					<h1></h1>
					<span class="triangle fleft">&nbsp;</span>
					<span class="counts"></span>
					                </div><!-- end .box-->
                <div id="fbLikes" class="box">
                    <span>Likes</span>
                    <a class="helpsmall theTolltip" href="#" title="Likes."></a>
					<h1></h1>
					<span class="triangle fleft">&nbsp;</span>
					<span class="counts"></span>
                                    </div><!-- end .box-->
                <div id="fbPeople" class="box">
                    <span>People</span>
                    <a class="helpsmall theTolltip" href="#" title="Peoples."></a>
					<h1></h1>
					<span class="triangle fleft">&nbsp;</span>
					<span class="counts"></span>
					                </div><!-- end .box-->
            </div><!-- end .list-box-->
    </td>
  </tr>
  <tr>
    <td align="left" valign="top" width="310">
    	<div id="topKeyword" class="favorite-word">
			<h1>Top Keywords</h1>			
			<div id="fbTopWords" class="wordclouds"></div>
        </div>
    </td>
    <td align="left" valign="top">
            <div class="thePeoples">
                <h1>Top People</h1>
 			    <a class="smallArrow" href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=kol'), $this);?>
#tabFacebook">View All</a>
                <div id="fbTopPeople" class="content">
                     
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
#tabs/facebookTab">View All</a>
          </div>
          <div id="channels" class="bgGreys">
            <div id="tab-facebook">
              <div id="fbTopPost">               
                
              </div>
            </div>
          </div><!-- .bgGreys -->
        </div><!-- .topPost -->
    </td>
  </tr>
</table>
<script>
<?php echo '
	$("#fbDailySelect").live(\'change\', function() {
		if(fbCount != 0){
			channelPerformance(\'fbDailyVolume\', this.value, \'Facebook\');
		}
    });
'; ?>

</script>