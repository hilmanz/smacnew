<?php /* Smarty version 2.6.13, created on 2012-09-11 12:40:21
         compiled from smac/key-opinion-leader.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/key-opinion-leader.html', 4, false),array('function', 'proxy_image', 'smac/key-opinion-leader.html', 480, false),array('modifier', 'smac_number', 'smac/key-opinion-leader.html', 379, false),array('modifier', 'trim', 'smac/key-opinion-leader.html', 459, false),array('modifier', 'number_format', 'smac/key-opinion-leader.html', 459, false),)), $this); ?>
<script src="js/charts/highcharts.js" type="text/javascript"></script>
<script>n_wc=1</script>
<script>
var urlgetallpeople = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=keyopinionleader&act=getallpeople&ajax=1','exclude' => $this->_tpl_vars['exclude']), $this);?>
";
var url = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=keyopinionleader&act=overtime&ajax=1','person' => $this->_tpl_vars['arrName'],'campaign_id' => $this->_tpl_vars['campaign_id']), $this);?>
";
var m_url = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=keyopinionleader&act=overtime&ajax=1','person' => $this->_tpl_vars['arrName'],'campaign_id' => $this->_tpl_vars['campaign_id']), $this);?>
";
var url2 = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=keyopinionleader&act=impression_overtime&ajax=1','person' => $this->_tpl_vars['arrName'],'campaign_id' => $this->_tpl_vars['campaign_id']), $this);?>
";
var urlex1 =  "<?php echo $this->_tpl_vars['urlkeyopinion_ex1']; ?>
";
var urlex2 =  "<?php echo $this->_tpl_vars['urlkeyopinion_ex2']; ?>
";
var urlex3 =  "<?php echo $this->_tpl_vars['urlkeyopinion_ex3']; ?>
";
var urlex0 =  "<?php echo $this->_tpl_vars['urlkeyopinion']; ?>
";

<?php echo '
$(document).ready(function(){
	
	
	//all people
	$(\'#tbl-allpeople\').dataTable({
			 "bJQueryUI": true,
			 "aaSorting": [[3,\'desc\']],
			 "aoColumns":                                                       
				 [                                                                  
					 /* th1 */       { "bSortable": false,"bSearchable":false},
					 /* th2 */       { "bSortable": true},
					 /* th3 */       { "bSortable": true},
					 /* th4 */       { "bSortable": true},
					 /* th5 */       { "bSortable": true},
					 /* th6 */       { "bSortable": false,"bSearchable":false},
				  ],
			 "sPaginationType": "full_numbers",
			 "bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": urlgetallpeople
			 });
	'; ?>

	
	var a = <?php echo $this->_tpl_vars['arrTop']; ?>
;
	var aRT = <?php echo $this->_tpl_vars['arrRT']; ?>
;
	var nama = "<?php echo $this->_tpl_vars['arrName']; ?>
";
	
	var url3 = "<?php echo smarty_function_encrypt(array('url' => 'index.php?page=keyopinionleader&act=performance_overtime&ajax=1','person' => $this->_tpl_vars['arrName'],'campaign_id' => $this->_tpl_vars['campaign_id']), $this);?>
";
	var anum = a.length;
	
	var topHigh = <?php echo $this->_tpl_vars['topHigh']; ?>
;
	
	<?php echo '
	
	for (var i = 0; i < anum; i++) {	
		
		var persen = (a[i]/topHigh)*100;
		var text = (((225*persen)/100)-25);
		var posisi = text/2;
		
		$("#c"+i).animate({
			height: persen+"%"}, 1000);

		//$("div#cd"+i).text(a[i]).hide().fadeIn(1500);
		$("div#cd"+i).html(\'<a href=\\\'#\\\' title=\\\'Share Percentage\\\' class=\\\'percent-white\\\'>\'+aRT[i]+\'%</a>\').hide().fadeIn(1500);
		if(posisi>0){
			$("div#cd"+i).css("paddingTop", posisi);
		}
		
		if(persen<20){
			$("#c"+i).css("background", "#00aefe");
		}else if(persen>=20 && persen<=40){
			$("#c"+i).css("background", "#00aefe");
		}else if(persen>40 && persen<=60){
			$("#c"+i).css("background", "#00aefe");	
		}else if(persen>60 && persen<=80){
			$("#c"+i).css("background", "#00aefe");	
		}else if(persen>80 && persen<=100){
			$("#c"+i).css("background", "#00aefe");	
		}else{
			$("#c"+i).css("background", "transparent");	
			}
	}
	
	
	//CHarts
			
			function loadChart3(){

				$("#chart3").html("<div style=\'width:550px;height:200px;text-align:center;margin:100px auto;\'><img src=\'images/loader-med.gif\' /></div>");
				var options = null;
				options = {
					chart: {
						renderTo: \'chart3\',
						type: \'line\'
					},
					credits: {
				        enabled: false
				    },
					title: {
						text: \'\'
					},
					xAxis: {
						enabled: false,
						categories: []
					},
					yAxis: {
						title: {
							text: \'\'
						}
					},
					tooltip: {
				         formatter: function() {
				            return \'\'+
				               this.series.name +\': \'+ this.y;
				         }
				    },
					legend: {
						enabled: true
					},
					series: []
				};
				
				// Load the data from the XML file 
				
				$.get(url3,
						function(xml) {
					
					// Split the lines
					var $xml = $(xml);
					
					// push categories
					$xml.find(\'categories item\').each(function(i, category) {
						options.xAxis.categories.push($(category).text());
					});
					
					// push series
					$xml.find(\'series\').each(function(i, series) {
						var seriesOptions = {
							name: $(series).find(\'name\').text(),
							data: []
						};
						
						// push data points
						$(series).find(\'data point\').each(function(i, point) {
							seriesOptions.data.push(
								parseInt($(point).text())
							);
						});
						
						// add it to the options
						options.series.push(seriesOptions);
					});
					var chart = null;
					chart = new Highcharts.Chart(options);
				});
			}
			loadChart3();
	
});

//Impression chart

function loadChart2(){

				$("#chart2").html("<div style=\'width:550px;height:200px;text-align:center;margin:100px auto;\'><img src=\'images/loader-med.gif\' /></div>");
				var options = null;
				options = {
					chart: {
						renderTo: \'chart2\',
						type: \'bar\'
					},
					credits: {
				        enabled: false
				    },
					title: {
						text: \'\'
					},
					xAxis: {
						enabled: false,
						categories: []
					},
					yAxis: {
						title: {
							text: \'\'
						}
					},
					tooltip: {
				         formatter: function() {
				            return \'\'+
				               this.series.name +\': \'+ Highcharts.numberFormat(this.y,0);
				         }
				    },
					legend: {
						enabled: true
					},
					series: []
				};
				
				// Load the data from the XML file 
				
				$.get(url2,
						function(xml) {
					
					// Split the lines
					var $xml = $(xml);
					var visibility;
					// push categories
					$xml.find(\'categories item\').each(function(i, category) {
						options.xAxis.categories.push($(category).text());
					});
					
					// push series
					$xml.find(\'series\').each(function(i, series) {
						if (i>0){
							visibility = false;
						}else{
							visibility = true;
						}

						var seriesOptions = {
							name: $(series).find(\'name\').text(),
							data: [],
							visible: visibility
						};
						
						// push data points
						$(series).find(\'data point\').each(function(i, point) {
							seriesOptions.data.push(
								parseInt($(point).text())
							);
						});
						
						// add it to the options
						options.series.push(seriesOptions);
					});
					var chart = null;
					chart = new Highcharts.Chart(options);
				});
			}

//Mentions chart

function loadChart(){

	$("#chart").html("<div style=\'width:550px;height:200px;text-align:center;margin:100px auto;\'><img src=\'images/loader-med.gif\' /></div>");
	var options = null;
	options = {
		chart: {
			renderTo: \'chart\',
			zoomType: \'x\',
			type: \'bar\'
		},
		credits: {
	        enabled: false
	    },
		title: {
			text: \'\'
		},
		xAxis: {
			categories: [],
		},
		yAxis: {
						title: {
							text: \'\'
						}
					},
		plotOptions: {
         bar: {
            dataLabels: {
               enabled: false
            }
         }
      },
		tooltip: {
	         formatter: function() {
	            return \'\'+
	               this.series.name +\': \'+ Highcharts.numberFormat(this.y,0);
	         }
	    },
		legend: {
			enabled: true
		},
		series: []
	};
	
	// Load the data from the XML file 
	
	$.get(m_url,
			function(xml) {
		
		// Split the lines
		var $xml = $(xml);
		var visibility = true;
		// push categories
		$xml.find(\'categories item\').each(function(i, category) {
			options.xAxis.categories.push($(category).text());
		});
		
		// push series
		$xml.find(\'series\').each(function(i, series) {
			visibility = true;
			
			var seriesOptions = {
				name: $(series).find(\'name\').text(),
				data: [],
				visible: visibility
			};
			
			// push data points
			$(series).find(\'data point\').each(function(i, point) {
				seriesOptions.data.push(parseInt($(point).text())
			);
			});
			
			// add it to the options
			options.series.push(seriesOptions);
		});
		
		//alert(options.series);
		
		var chart = null;
		chart = new Highcharts.Chart(options);
	});
}


function toggle_kol_filter(t){
	
	if($(\'#exclude1\').is(\':checked\')){
		if(urlex1.length>0){
			document.location = urlex1;
			
		}
	}else{
		if(urlex0.length>0){
			document.location = urlex0;
		}
	}
}

</script>
'; ?>


<div id="main-container">
    	<?php echo $this->_tpl_vars['sidebar']; ?>

        <div id="container">
    		<?php echo $this->_tpl_vars['menu']; ?>

            <div class="title-bar">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left">
          					  	<h1><?php if ($this->_tpl_vars['market']): ?><span id='txtmarket'>Market Key Opinion Leader - <?php echo $this->_tpl_vars['market']; ?>
</span><?php endif; ?> 
                           <?php if ($this->_tpl_vars['market']): ?> <a id='btnglobaldata' href='<?php echo smarty_function_encrypt(array('url' => 'index.php?page=home&act=toggle_geo'), $this);?>
'>Switch to Global Data</a><?php endif; ?></h1>
                        </td>
                        <td align="right">
                        	   <div style="float:right; padding-right:100px;"><input type="checkbox" id="exclude1" onchange="toggle_kol_filter(1);return false;" <?php if ($this->_tpl_vars['exclude'] == '1'): ?>checked='true'<?php endif; ?>/>Exclude News &amp; Corporate Accounts</div>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="key-opinion">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <?php if ($this->_tpl_vars['data_available']): ?>
                  <tr>
                    <td class="w200 w175" valign="top" align="left">
                        <ul class="tabs">
                            <li><a href="#top-10" class="top-10"><span class="f48">Top 10</span><br /><span class="f32">People</span></a></li>
                            <li><a href="#mention-overtime" class="mention-overtime" onclick="loadChart()">Mentions</a></li>
                        	<li><a href="#impression-overtime" class="impression-overtime" onclick="loadChart2()">Impressions</a></li>
                        	<li><a href="#all-people" class="all-people">All People</a></li>
													</ul>
                    </td>
                    <td class="wfull" valign="top" align="left">
                    	<div class="tab_container">
                            <div id="top-10" class="tab_content">
                               <!--<img src="content/chart5.jpg"   />-->
                               
                               <table cellpadding="0" cellspacing="0" border="0" width="100%">
                               		
									<tr>
										<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['top']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
										<td valign="bottom" height="225" >
											<div class="info-imp" title="Total Impressions">
                                                <a href="#" class="total-imp"><?php echo ((is_array($_tmp=$this->_tpl_vars['top'][$this->_sections['i']['index']]['total_impression'])) ? $this->_run_mod_handler('smac_number', true, $_tmp) : smac_number($_tmp)); ?>
</a>
                                                	<span class="space" style="display:none;">|</span>
                                                <a class="percent-black" style="display:none;" title="Share Percentage">100%</a>
                                            </div>
											<div id="c<?php echo $this->_sections['i']['index']; ?>
" style="background-color:#00aefe; width:65px; height:0; margin: 0 auto;">
												<div id="cd<?php echo $this->_sections['i']['index']; ?>
" style="margin:auto; padding:0 0; height:30px; color:#FFF; text-align:center"></div>
											</div>
										</td>
										<?php endfor; endif; ?>
										
									<tr>
										<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['top']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
										<td valign="bottom" height="100" style=" text-align:center;">
											<a href="#?w=650&id=<?php echo $this->_tpl_vars['top'][$this->_sections['i']['index']]['name']; ?>
" class="smallthumbs poplight relative" rel="profile" style="float:none;overflow:visible;">
												<?php if ($this->_tpl_vars['top'][$this->_sections['i']['index']]['is_paid']): ?><img src="images/icon-bintang.png" class="bintang"/><?php endif; ?>
												<img src="<?php echo $this->_tpl_vars['top'][$this->_sections['i']['index']]['img']; ?>
" width="48" height="48" style="margin:0" title="<?php echo $this->_tpl_vars['top'][$this->_sections['i']['index']]['real_name']; ?>
"/>
											</a>
											<p style="font-size:11px; font-weight:bolder"><?php echo $this->_tpl_vars['top'][$this->_sections['i']['index']]['name']; ?>
</p>
										</td>
										<?php endfor; endif; ?>
									</tr>
								</table>
                               
                            </div> <!-- #top-10 -->
                            <div id="mention-overtime" class="tab_content" >
                            
                               <!--<img src="content/chart2.jpg" />-->
                               
                               <div style="min-width:750px;height:350px;" id="chart"></div>
                            
                            </div><!-- #mention-overtime -->
                            
							<div id="impression-overtime" class="tab_content" >
                            
                               <div style="min-width:750px;height:350px;" id="chart2"></div>
                              
                            </div><!-- #impression-overtime -->
                            <div id="all-people" class="tab_content" >
                            	<div style="min-width:750px">
                               <table id="tbl-allpeople" width="100%" border="0" cellspacing="0" cellpadding="0" class="tablesorter addlist zebra" style="min-width:750px;">
	                               	<thead>
	                               	<tr>
	                               		<th><strong>Pic</strong></th>
	                               		<th><strong>Username</strong></th>
	                               		<th><strong>Name</strong></th>
	                               		<th><strong>Impression</strong></th>
	                               		<th><strong>% Share</strong></th>
	                               		<th><strong>PII</strong></th>
	                               	</tr>
	                               	</thead>
	                               	<tbody>
									
									
									
									
	                               	</tbody>
                               </table>
                               </div>
                              
                            </div><!-- #all-people -->
                                                    </div>
                    </td>
                  </tr>
                  <tr>
                    <td valign="top" align="left">&nbsp;</td>
                    <td valign="top" align="left">
                    	<div id="total-mention" class="box">
                            <div class="headbox">
                            	<span>Total Mentions</span>
                           	 	<a href="#" class="helpsmall tip_trigger">&nbsp;<span class="tip">How many times your keywords have been mentioned accumulatively through the campaign's timespan</span></a>
                           	</div>
                            <div class="boxcaption">
                                <h1><?php echo $this->_tpl_vars['total_mentions']; ?>
</h1>
                                <h2><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['total_mentions_true'])) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</h2>
                            </div>
                            
                            <div class="captionbottom">
                            	 <?php if ($this->_tpl_vars['mention_change'] > 0): ?>                      
                            	<span class="triangle"><?php echo $this->_tpl_vars['mention_change']; ?>
%</span>
	                            <?php elseif ($this->_tpl_vars['mention_change'] < 0): ?>
	                             <span class="triangle arrow_down"><?php echo $this->_tpl_vars['mention_change']; ?>
%</span>
	                            <?php endif; ?>
                            </div>
                           
                        </div><!-- #total-mention -->
                    	<div id="ambassador" class="box">
                            <div class="headbox">
                                <span>Fans</span>
                           	 	<a href="#" class="helpsmall tip_trigger">&nbsp;<span class="tip">Ambassadors are people that have mentioned your keywords in a mostly favorable manner and maintains a large number of influence.</span></a>
                            </div>
                            <div class="list borderbot">
                            	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['ambas']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
                            	<div class="box-list">
                                    <div class="smallthumb">
                                        <a href="#?w=650&id=<?php echo $this->_tpl_vars['ambas'][$this->_sections['i']['index']]['name']; ?>
" class="poplight" rel="profile"><img src="<?php echo smarty_function_proxy_image(array('url' => $this->_tpl_vars['ambas'][$this->_sections['i']['index']]['img']), $this);?>
" /></a>
                                    </div>
                                    <div class="boxcaption">
                                    	<h4><?php echo $this->_tpl_vars['ambas'][$this->_sections['i']['index']]['name']; ?>
</h4>
										<span class="count"><?php echo $this->_tpl_vars['ambas'][$this->_sections['i']['index']]['positive']; ?>
</span>
                                    </div>
                                </div><!-- .box-list -->
                            	<?php endfor; endif; ?>
                            </div><!-- .list -->
                        </div><!-- #ambassador -->
                    	<div id="troll" class="box">
                            <div class="headbox">
                                <span>Haters</span>
                           	 	<a href="#" class="helpsmall tip_trigger">&nbsp;<span class="tip">Trolls are people that have mentioned your keywords in a mostly unfavorable manner and maintains a large number of influence.</span></a>
                            </div>
                            <div class="list borderbot">
                            	
                            	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['troll']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
                            	<div class="box-list">
                                    <div class="smallthumb">
                                        <a href="#?w=650&id=<?php echo $this->_tpl_vars['troll'][$this->_sections['i']['index']]['name']; ?>
" class="poplight" rel="profile"><img src="<?php echo smarty_function_proxy_image(array('url' => $this->_tpl_vars['troll'][$this->_sections['i']['index']]['img']), $this);?>
" /></a>
                                    </div>
                                    <div class="boxcaption">
                                    	<h4><?php echo $this->_tpl_vars['troll'][$this->_sections['i']['index']]['name']; ?>
</h4>
										<span class="count"><?php echo $this->_tpl_vars['troll'][$this->_sections['i']['index']]['positive']; ?>
</span>
                                    </div>
                                </div><!-- .box-list -->
                            	<?php endfor; endif; ?>
                            	
                            </div><!-- .list -->
                        </div><!-- #troll -->
                                               </td>
                  </tr>
				  <tr>
					<td></td>
					<td>
						<div class="relative" style="margin-top:20px;">
							<img src="images/icon-bintang.png" class="absolute" style="top:-2px;">
							<span style="text-align:left;margin-left: 30px;">Sponsored Key Opinion Leader</span>
						</div>
					</td>
				  </tr>
                  <?php else: ?>
	            	<tr>
	            		<td align="center" valign="top">
                            <div id="notAvailable">
                                    <div class="blankText">
                                        <h1>Your first report is not ready yet.</h1>
                                        <p>You have to wait 24 hours before it's completed.</p>
                            <p>In the meantime you can visit the "Live Track" page <br />
								to see what's happening with your Topic in real-time..</p>
                                        <a href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=livetrack'), $this);?>
" class="btnGreenBar">See Live Track</a>
                                    </div>
                                    <div class="screenCap">
                                        <img src="images/blank_kol.gif" />
                                    </div>
                            </div>
	            		</td>
	            	</tr> 	
	            <?php endif; ?>
                </table>
            </div><!-- #key-opinion -->
        </div><!-- #container -->
    </div><!-- #main-container -->