<?php /* Smarty version 2.6.13, created on 2012-10-01 16:48:39
         compiled from smac/ka_rule.html */ ?>
            <div class="title-bar">
                <h1><a href="#">Analysis For <span id="kaRuleTitle"></span></a></h1>
                 <a class="smallArrow" href="#" onclick="kaDetails(0, false); return false;">Back</a>
            </div>
            <div id="sentiment">
            	<div class="titles">
                	<div class="wfilters">
                    <select id="kaRuleMenu">
                    </select>
                    </div>
                </div>
                <div id="sumChart" class="bgGreen">
                	<div class="box">
                  	    <h3>Channel</h3>
                        <div id="channelChartDetail" class="bgWhite">
                            
                        </div>
                    </div>
                	<div class="box">
                  	    <h3>Post</h3>
                        <div id="postChartDetail" class="bgWhite" style="width: 293px;height:180px;">
                            
                        </div>
                    </div>
                	<div class="box last">
                  	    <h3>Sentiment</h3>
                        <div id="sentimentChartDetail" class="bgWhite">
                            
                        </div>
                    </div>
                </div>
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="w200" valign="top" align="left">
                        <ul id="keywordDetailList" class="tabs tabs3">
                        </ul>
                    </td>
                    <td class="wfull pageContent" valign="top" align="left" style="display: block;">
                    	<div class="tab_container" style="min-width:750px;">
                            <div id="tab-list" class="tab_content tab_content3">
                                
                            </div><!-- .tab_content -->
							
                        </div>
						<div id="cPaging" class="paging"></div>
                    </td>
                  </tr>
                 
                </table>
            </div><!-- #key-opinion -->