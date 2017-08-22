<?php /* Smarty version 2.6.13, created on 2013-01-03 17:50:43
         compiled from smac/widgets/datefilter.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'dateUnixToIndo', 'smac/widgets/datefilter.html', 64, false),)), $this); ?>
<script type="text/javascript" src="js/dynDateTime/jquery.dynDateTime.js"></script>
<script type="text/javascript" src="js/dynDateTime/lang/calendar-en.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="js/dynDateTime/css/calendar-system.css"  />

<script type="text/javascript">
var start_date_filter = '<?php if ($this->_tpl_vars['dt_from'] == ''):  else:  echo $this->_tpl_vars['dt_from'];  endif; ?>';
var end_date_filter = '<?php if ($this->_tpl_vars['dt_to'] == ''):  else:  echo $this->_tpl_vars['dt_to'];  endif; ?>';
<?php echo '
$(document).ready(function(){
	if( $(\'input[name="dt_from"]\').length > 0){
		$(\'input[name="dt_from"]\').dynDateTime({
			ifFormat:     "%d/%m/%Y",
			onClose: function(cal){
					var selDate = new Date(cal.date);
					selDate.setDate(selDate.getDate()); 
					'; ?>

					var startDate=new Date('<?php echo $this->_tpl_vars['a_dates'][0]['value']; ?>
');
					<?php echo '
					'; ?>

					var endDate=new Date('<?php echo $this->_tpl_vars['a_dates'][$this->_tpl_vars['a_dates_num']]['value']; ?>
');
					<?php echo '
					startDate.setDate(startDate.getDate());
					endDate.setDate(endDate.getDate());
					if( parseInt(selDate.getTime()) < parseInt(startDate.getTime()) ){
						$(\'input[name="dt_from"]\').val('; ?>
'<?php echo $this->_tpl_vars['a_dates'][0]['label']; ?>
'<?php echo ') ;
					}
					if( parseInt(selDate.getTime()) > parseInt(endDate.getTime()) ){
						$(\'input[name="dt_from"]\').val('; ?>
'<?php echo $this->_tpl_vars['a_dates'][$this->_tpl_vars['a_dates_num']]['label']; ?>
'<?php echo ') ;
					}
					cal.hide();
			}
		});
	}
	if( $(\'input[name="dt_to"]\').length > 0){
		$(\'input[name="dt_to"]\').dynDateTime({
			ifFormat:     "%d/%m/%Y",
			onClose: function(cal){
					var selDate = new Date(cal.date);
					selDate.setDate(selDate.getDate()); 
					'; ?>

					var startDate=new Date('<?php echo $this->_tpl_vars['a_dates'][0]['value']; ?>
');
					<?php echo '
					'; ?>

					var endDate=new Date('<?php echo $this->_tpl_vars['a_dates'][$this->_tpl_vars['a_dates_num']]['value']; ?>
');
					<?php echo '
					startDate.setDate(startDate.getDate());
					endDate.setDate(endDate.getDate());
					if( parseInt(selDate.getTime()) < parseInt(startDate.getTime()) ){
						$(\'input[name="dt_to"]\').val('; ?>
'<?php echo $this->_tpl_vars['a_dates'][0]['label']; ?>
'<?php echo ') ;
					}
					if( parseInt(selDate.getTime()) > parseInt(endDate.getTime()) ){
						$(\'input[name="dt_to"]\').val('; ?>
'<?php echo $this->_tpl_vars['a_dates'][$this->_tpl_vars['a_dates_num']]['label']; ?>
'<?php echo ') ;
					}
					cal.hide();
			}
		});
	}
});
</script>
'; ?>


<form id="smacDateFilter" class="selectDate" action="index.php" method='GET' enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="req" value="<?php echo $this->_tpl_vars['datefilter_url_param']; ?>
"/>
    <input class="round5" type="text" name="dt_from" value="<?php if ($this->_tpl_vars['dt_from'] == ''):  echo $this->_tpl_vars['a_dates'][0]['label'];  else:  echo ((is_array($_tmp=$this->_tpl_vars['dt_from'])) ? $this->_run_mod_handler('dateUnixToIndo', true, $_tmp) : dateUnixToIndo($_tmp));  endif; ?>" readonly="readonly" /> To 
	<input class="round5" type="text" name="dt_to" value="<?php if ($this->_tpl_vars['dt_to'] == ''):  echo $this->_tpl_vars['a_dates'][$this->_tpl_vars['a_dates_num']]['label'];  else:  echo ((is_array($_tmp=$this->_tpl_vars['dt_to'])) ? $this->_run_mod_handler('dateUnixToIndo', true, $_tmp) : dateUnixToIndo($_tmp));  endif; ?>" readonly="readonly" />
    
	<input type="submit" value="go" class="btn-go" />
</form>