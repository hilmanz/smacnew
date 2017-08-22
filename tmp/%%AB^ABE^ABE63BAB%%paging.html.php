<?php /* Smarty version 2.6.13, created on 2012-10-31 10:04:21
         compiled from common/paging.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'common/paging.html', 2, false),)), $this); ?>
<div id="paging" class="paging">
<?php if ($this->_tpl_vars['isPrev']): ?><a href="<?php if ($this->_tpl_vars['base_url'] <> ""):  echo smarty_function_encrypt(array('url' => $this->_tpl_vars['base_url'],'st' => $this->_tpl_vars['begin']), $this); else:  echo smarty_function_encrypt(array('url' => '?','st' => $this->_tpl_vars['begin']), $this); endif; ?>">&lt;&lt;</a> <?php endif; ?>
&nbsp;<?php unset($this->_sections['l']);
$this->_sections['l']['name'] = 'l';
$this->_sections['l']['loop'] = is_array($_loop=$this->_tpl_vars['page']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['l']['show'] = true;
$this->_sections['l']['max'] = $this->_sections['l']['loop'];
$this->_sections['l']['step'] = 1;
$this->_sections['l']['start'] = $this->_sections['l']['step'] > 0 ? 0 : $this->_sections['l']['loop']-1;
if ($this->_sections['l']['show']) {
    $this->_sections['l']['total'] = $this->_sections['l']['loop'];
    if ($this->_sections['l']['total'] == 0)
        $this->_sections['l']['show'] = false;
} else
    $this->_sections['l']['total'] = 0;
if ($this->_sections['l']['show']):

            for ($this->_sections['l']['index'] = $this->_sections['l']['start'], $this->_sections['l']['iteration'] = 1;
                 $this->_sections['l']['iteration'] <= $this->_sections['l']['total'];
                 $this->_sections['l']['index'] += $this->_sections['l']['step'], $this->_sections['l']['iteration']++):
$this->_sections['l']['rownum'] = $this->_sections['l']['iteration'];
$this->_sections['l']['index_prev'] = $this->_sections['l']['index'] - $this->_sections['l']['step'];
$this->_sections['l']['index_next'] = $this->_sections['l']['index'] + $this->_sections['l']['step'];
$this->_sections['l']['first']      = ($this->_sections['l']['iteration'] == 1);
$this->_sections['l']['last']       = ($this->_sections['l']['iteration'] == $this->_sections['l']['total']);
 if ($this->_tpl_vars['curr_page'] == $this->_tpl_vars['page'][$this->_sections['l']['index']]['no']): ?><a class="active"><?php echo $this->_tpl_vars['page'][$this->_sections['l']['index']]['no']; ?>
</a>
<?php else: ?>
<a href="<?php if ($this->_tpl_vars['base_url'] <> ""):  echo smarty_function_encrypt(array('url' => $this->_tpl_vars['base_url'],'st' => $this->_tpl_vars['page'][$this->_sections['l']['index']]['start']), $this); else:  echo smarty_function_encrypt(array('url' => '?','st' => $this->_tpl_vars['page'][$this->_sections['l']['index']]['start']), $this); endif; ?>"><?php echo $this->_tpl_vars['page'][$this->_sections['l']['index']]['no']; ?>
</a><?php endif; ?>
&nbsp;<?php endfor; endif;  if ($this->_tpl_vars['isNext']): ?>
<a href="<?php if ($this->_tpl_vars['base_url'] <> ""): ?>
          <?php echo smarty_function_encrypt(array('url' => $this->_tpl_vars['base_url'],'st' => $this->_tpl_vars['last']), $this); else:  echo smarty_function_encrypt(array('url' => '?','st' => $this->_tpl_vars['last']), $this); endif; ?>">&gt;&gt;</a>
<?php endif; ?>
</div>