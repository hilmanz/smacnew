<?php /* Smarty version 2.6.13, created on 2012-09-13 14:39:54
         compiled from smac/widgets/top_conversation_twitter.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'smac/widgets/top_conversation_twitter.html', 8, false),array('modifier', 'strip_tags', 'smac/widgets/top_conversation_twitter.html', 8, false),)), $this); ?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['tw']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
 <div class="list">
     <div class="smallthumb">
         <a href="#?w=650&id=<?php echo $this->_tpl_vars['tw'][$this->_sections['i']['index']]['author_id']; ?>
" class="poplight" rel="profile"><img src="<?php echo $this->_tpl_vars['tw'][$this->_sections['i']['index']]['image']; ?>
" /></a>
     </div>
     <div class="entry">
         <h3><?php echo $this->_tpl_vars['tw'][$this->_sections['i']['index']]['name']; ?>
</h3><span class="date"><?php echo $this->_tpl_vars['tw'][$this->_sections['i']['index']]['published_date']; ?>
</span>
         <span><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tw'][$this->_sections['i']['index']]['txt'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</span>
     </div><!-- .entry -->
     <div class="entry-action">
     	<a href="#" <?php if ($this->_tpl_vars['tw'][$this->_sections['i']['index']]['device'] == 'blackberry'): ?>class="active"<?php endif; ?>><span class="blackberry" >&nbsp;</span></a>
     	<a href="#" <?php if ($this->_tpl_vars['tw'][$this->_sections['i']['index']]['device'] == 'apple'): ?>class="active"<?php endif; ?>><span class="apple" >&nbsp;</span></a>
     	<a href="#" <?php if ($this->_tpl_vars['tw'][$this->_sections['i']['index']]['device'] == 'android'): ?>class="active"<?php endif; ?>><span class="android" >&nbsp;</span></a>
         <a class="icon-rts tip_trigger" style="margin-left: 15px;"> <?php echo $this->_tpl_vars['tw'][$this->_sections['i']['index']]['rt']; ?>
  <span class="tip">Retweet Frequency</span></a>
         <a class="icon-imp tip_trigger"> <?php echo $this->_tpl_vars['tw'][$this->_sections['i']['index']]['imp']; ?>
  <span class="tip">Total Impressions</span></a>
         <a class="icon-share tip_trigger"><?php echo $this->_tpl_vars['tw'][$this->_sections['i']['index']]['share']; ?>
% <span class="tip">Share</span></a>
     	<a class="reply tip_trigger" href="#" onclick="mark_for_reply('<?php echo $this->_tpl_vars['tw'][$this->_sections['i']['index']]['id']; ?>
','<?php echo $this->_tpl_vars['tw'][$this->_sections['i']['index']]['reply_url']; ?>
')">&nbsp;<span class="tip">Mark for Reply</span></a>
     </div><!-- .entry-action -->
 </div>
<?php endfor; endif; ?>