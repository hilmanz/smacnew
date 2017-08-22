<?php /* Smarty version 2.6.13, created on 2012-10-30 18:03:33
         compiled from smac/edit_group.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'urlencode', 'smac/edit_group.html', 31, false),array('modifier', 'stripslashes', 'smac/edit_group.html', 31, false),array('modifier', 'htmlentities', 'smac/edit_group.html', 31, false),)), $this); ?>
<script type="text/javascript" src="js/dynDateTime/jquery.dynDateTime.js"></script>
<script type="text/javascript" src="js/dynDateTime/lang/calendar-en.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="js/dynDateTime/css/calendar-system.css"  />

<div id="main-container">
    	<?php echo $this->_tpl_vars['sidebar']; ?>

        <div id="container">   	
            <div class="title-bar padR15">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            	<tr>
                	<td align="left">
            			<h1><a href="#">Topic Group(s)</a> <span class="grey">&gt; Manage</span></h1>
            		</td>
                    <td align="right">
                    	
                    </td>
                </tr>
            </table>
            </div>
            <div class="p10">
                <div id="campaign">
                    <p>This is a list of your topics and groups, drag and drop your topics into the desired groups below.</p>
                    
                    <?php if ($this->_tpl_vars['err']): ?>
                    <p style="color:#f00;"><strong><?php echo $this->_tpl_vars['err']; ?>
</strong></p>
                    <?php endif; ?>
                    <div id="mytopic">
                        <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['groups']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                        <div id="g-<?php echo $this->_tpl_vars['groups'][$this->_sections['i']['index']]['id']; ?>
" class="droppable">
                        	<div id="grouppane">
                            	<a href="#" onclick="show_edit_popup(<?php echo $this->_tpl_vars['groups'][$this->_sections['i']['index']]['id']; ?>
,'<?php echo ((is_array($_tmp=$this->_tpl_vars['groups'][$this->_sections['i']['index']]['group_name'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
');"> <span class="titleGroups"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['groups'][$this->_sections['i']['index']]['group_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('htmlentities', true, $_tmp) : htmlentities($_tmp)); ?>
 </span>
                                <span class="smallLoader" style="display:none;"><img src="images/smallloader.png" /></span></a>
                            </div>
                        	<div id="topicpane">
                        		<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['groups'][$this->_sections['i']['index']]['topics']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>
                        		<div id="t-<?php echo $this->_tpl_vars['groups'][$this->_sections['i']['index']]['topics'][$this->_sections['j']['index']]['id']; ?>
" class="draggable"><?php echo $this->_tpl_vars['groups'][$this->_sections['i']['index']]['topics'][$this->_sections['j']['index']]['name']; ?>
</div>
                        		<?php endfor; endif; ?>
                        	</div>
                        </div>
                        <?php endfor; endif; ?>
                    </div>
                </div>
          </div>
        </div><!-- #container -->
    </div><!-- #main-container -->
<script type="application/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="application/javascript">
	var update_url = "<?php echo $this->_tpl_vars['update_url']; ?>
";
</script>
<?php echo '
<script type="application/javascript">
	function show_edit_popup(i,n){
		var sHtmlForm ="";
		sHtmlForm+=\'<form method="post" enctype="application/x-www-form-urlencoded">\'+
					\'<input type="text" name="name" value="\'+urldecode(n)+\'" autocomplete="off"/><input type="hidden" name="id" value="\'+i+\'"/>\'+
					\'<input type="submit" name="btnSubmit" value="Save"/>\'+
					\'<input type="hidden" name="update_group" value="1"/></form>\';
		popup_msg("Edit Topic",sHtmlForm,null,null);
		return false;
	}
	$(document).ready(function(){
		$(".draggable").draggable();
		$(".droppable").droppable({
			drop: function( event, ui ) {
				var $container= $(this);
				var $item = ui.draggable;
				
				$item.fadeOut(function(){
					$item.css(\'top\',0);
					$item.css(\'left\',0);
					change_group($item,$container,$container.attr(\'id\'),$item.attr(\'id\'));
				});
			}
		});
	});
	function change_group($item,$container,g,t){
		var _group_id = g.replace("g-","");
		var _topic_id = t.replace("t-","");
		$("#"+g).find(\'span.smallLoader\').show();
		$.ajax({
			  type: "POST",
			  url: update_url,
			  data: { group_id:_group_id , topic_id:_topic_id},
			  success: function(response){
			  		$("#"+g).find(\'span.smallLoader\').hide();
			  		$item.css(\'top\',0);
					$item.css(\'left\',0);
					$item.appendTo($container.find(\'div#topicpane\')).show(
						function(){
							$item.css(\'top\',0);
							$item.css(\'left\',0);
						}
					);
			  }
		});
	}
</script>
'; ?>
