<?php /* Smarty version 2.6.13, created on 2013-01-03 15:28:57
         compiled from smac/header.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/header.html', 29, false),)), $this); ?>
	<div id="message-top" class="message-top" style="display:none;">
		<div>
        <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['message']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<p><?php echo $this->_tpl_vars['message'][$this->_sections['i']['index']]; ?>
</p>
        <?php endfor; endif; ?>
        </div>
		<a href="#" class="close-message-top" onclick="javascript:setMessageCookie(<?php echo $this->_tpl_vars['user_id']; ?>
);">X</a>
    </div>
	<div id="header">
    	<div class="logo-block">
        	<a class="logosmall" href="<?php echo $this->_tpl_vars['urlhome']; ?>
">&nbsp;</a>
        </div><!-- .logo-block -->
        <div class="user-info">
        	<a class="bighelp tip_trigger theTolltip" id="reload" href="#" onclick="location.reload(); return false;" title="Reload Page">&nbsp;</a>
        	<a class="refresh tip_trigger theTolltip" href="#" title="Help">&nbsp;</a>
            <div class="userbox">
            	<span class="username"><a class="theTolltip" title="Click for Details" href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=account'), $this);?>
" style="text-decoration: none; color:#333;"><?php echo $this->_tpl_vars['fname']; ?>
 <?php echo $this->_tpl_vars['lname']; ?>
</a></span>
            	<span class="email-user"><a class="theTolltip" title="Click for Details" href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=account'), $this);?>
" style="text-decoration: none; color:#333;"><?php echo $this->_tpl_vars['email']; ?>
</a></span>
                <span class="status-user"><a class="theTolltip" title="Click for Details" href="<?php echo smarty_function_encrypt(array('url' => 'index.php?page=account&act=credit_usage'), $this);?>
" style="text-decoration: none; color:#333;"><?php if ($this->_tpl_vars['status'] == 1): ?>Paid</a><?php elseif ($this->_tpl_vars['status'] == 2): ?>Enterprise</a><?php elseif ($this->_tpl_vars['status'] == 3): ?>Paid</a><?php elseif ($this->_tpl_vars['status'] == 4): ?>Paid</a><?php elseif ($this->_tpl_vars['status'] == 5): ?>Enterprise</a><?php else: ?>Trial&nbsp;</a><?php endif; ?></span>
        	    				<a class="padlock theTolltip" title="Logout" href="logout.php"></a>
            	            </div>
        </div>
    </div><!-- #header -->