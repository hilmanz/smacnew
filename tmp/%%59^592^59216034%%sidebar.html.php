<?php /* Smarty version 2.6.13, created on 2013-01-03 17:50:43
         compiled from smac/sidebar.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'floatval', 'smac/sidebar.html', 7, false),array('modifier', 'utf8tohtml', 'smac/sidebar.html', 20, false),array('modifier', 'number_format', 'smac/sidebar.html', 84, false),array('function', 'encrypt', 'smac/sidebar.html', 64, false),)), $this); ?>
<?php if ($this->_tpl_vars['usage_show']):  echo '
<script type="text/javascript">
	$(function() {
		$("#progressbar").progressbar({
'; ?>

			value: <?php echo ((is_array($_tmp=$this->_tpl_vars['account']['percentage'])) ? $this->_run_mod_handler('floatval', true, $_tmp) : floatval($_tmp)); ?>

<?php echo '
		});
	});
	</script>
'; ?>

<?php endif; ?>
<div id="sidebar">
        <div class="select-campaign">
            <form action="#" method="post">
                    <select size="1" id="formSelectCampaign">
                    	<option value="#">Select Topic</option>
                        <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['campaign']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                        <option value="<?php echo $this->_tpl_vars['campaign'][$this->_sections['i']['index']]['link']; ?>
" <?php if ($this->_tpl_vars['campaign'][$this->_sections['i']['index']]['id'] == $this->_tpl_vars['campaignId']): ?>selected='selected'<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['campaign'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('utf8tohtml', true, $_tmp) : utf8tohtml($_tmp)); ?>
</option>
                        <?php endfor; endif; ?>
                       
                    </select>
            </form>
        </div>
        <div id="side-menu">
           <ul id="tree-menu">
           		<li></li>
                <li><a class="mycampaign" href="#">My Topics</a>
                    <ul>
                        <li><a href="<?php echo $this->_tpl_vars['urlmycampaign']; ?>
"> &bull; Overview</a></li>
                        <li><a href="<?php echo $this->_tpl_vars['urlnewcampaign']; ?>
"> &bull; New Topic</a></li>
                         <?php if ($this->_tpl_vars['has_campaign']): ?>
                        <li><a href="<?php echo $this->_tpl_vars['urleditcampaign']; ?>
"> &bull; Edit Topic</a></li>
                        <li><a href="<?php echo $this->_tpl_vars['urldeletecampaign']; ?>
"> &bull; Delete Topic</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo $this->_tpl_vars['url_edit_group']; ?>
"> &bull; Topic Groups</a></li>
                    </ul>
                </li>
                <?php if ($this->_tpl_vars['has_campaign']): ?>
                <li><a class="mykeywords" href="#">My Keywords</a>
                    <ul>
                        <li><a href="<?php echo $this->_tpl_vars['urlmykeyword']; ?>
"> &bull; Overview</a></li>
                                            </ul>
                </li>
                <?php endif; ?>
                <li><a class="myaccount" href="#">My Account</a>
                    <ul>
                        <li><a href="<?php echo $this->_tpl_vars['urlmyaccount']; ?>
"> &bull; Overview</a></li>
                        <?php if ($this->_tpl_vars['urladduser']): ?>
                        <li><a href="<?php echo $this->_tpl_vars['urladduser']; ?>
"> &bull; Add User</a></li>
                        <?php endif; ?>
                       <!-- <li><a href="#"> &bull; Upgrade Account</a></li>-->
                       
                       <li><a href="<?php echo $this->_tpl_vars['urlviewtransaction']; ?>
"> &bull; Payments &amp; Usage</a></li>
                       <li><a href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=account&act=preferences'), $this);?>
"> &bull; Preferences</a></li>
                    </ul>
                </li>
                     <!--
                <li><a class="billing" href="#">Billing</a>
                    <ul>
                        <li><a href="#"> &bull; Overview</a></li>
                        <li><a href="#"> &bull; Invoices List &amp; Status</a></li>
                    </ul>
                </li>
                -->
                <li><a class="helpdesk" href="http://helpdesk.smacapp.com" target="_blank">Helpdesk</a></li>
                <!--<li><a class="faq" href="<?php echo $this->_tpl_vars['urlfaq']; ?>
">F.A.Q</a></li>-->
                <li><a class="logout" href="logout.php">Logout</a></li>
            </ul>
        </div>
		<?php if ($this->_tpl_vars['usage_show']): ?>
		<?php if ($this->_tpl_vars['account']['total_limit'] <> -1): ?>
        <div id="accout-ussage">
            <h4>Your Topic Usage</h4>
            <span><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['account']['total_usage'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</strong> of <?php echo ((is_array($_tmp=$this->_tpl_vars['account']['total_limit'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</span>
            <div id="progressbar"><span class="percent-ussage"><?php echo $this->_tpl_vars['account']['percentage']; ?>
%</span></div>
                    </div>
        <?php endif; ?>
		<?php endif; ?>
</div>