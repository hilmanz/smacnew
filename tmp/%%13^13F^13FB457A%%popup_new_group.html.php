<?php /* Smarty version 2.6.13, created on 2013-01-03 15:28:58
         compiled from smac/popup_new_group.html */ ?>
<div id="popup-new-group" class="popup_block popupWidth popup-unmark" style="min-height:150px;">
	<div class="headpopup">
    	<h1 class="fleft">Create a New Topic Group</h1>
    </div>
    <div><p>You can create topic groups within SMAC to easily compare or group together topics most relevant to your business.</p></div>
    <div class="content-popup">
    	<div class="contentdivs">
    		<div class="rows bb">
                <label>Group Name</label>
                <input id="pp_group_name" class="round5" type="text" name="pp_group_name" style="width:320px"/>
                <span class="errCompare1 messageError">Please fill topic group name</span>
            </div>
            <div class="rows bb">
                <label>Group Type</label>
                <select id="pp_group_type" name="pp_group_type">
                <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['group_type']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                	<option value="<?php echo $this->_tpl_vars['group_type'][$this->_sections['i']['index']]['id']; ?>
"><?php echo $this->_tpl_vars['group_type'][$this->_sections['i']['index']]['name']; ?>
</option>
                <?php endfor; endif; ?>
                </select>
            </div>
    	</div>
        <div class="act-button">
            <a class="cancelgroup" href="javascript:void(0);">CANCEL</a>
            <a class="creategroup" href="javascript:topic_create_group();">CREATE</a>
        </div><!-- .paging -->
    </div><!-- .content-popup -->
</div><!-- #popup-sentiment -->