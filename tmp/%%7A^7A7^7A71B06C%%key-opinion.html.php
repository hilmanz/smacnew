<?php /* Smarty version 2.6.13, created on 2012-09-13 14:39:54
         compiled from smac/widgets/key-opinion.html */ ?>

	<div class="thePeoples">
		<h1>Top People</h1>
		<a class="smallArrow" href="#">View All</a>
		<div class="content">
			<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['opinion']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<a href="#?w=650&id=<?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['name']; ?>
" class="poplight" rel="profile"><img src="<?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['image']; ?>
" /></a>
				</div>
				<div class="entry">
					<h3><?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['name']; ?>
</h3>
					<a class="icon-rts tip_trigger"><?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['rt']; ?>
  <span class="tip">Retweet Frequency</span></a>
					<a class="icon-imp tip_trigger"><?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['followers']; ?>
  <span class="tip">Total Impressions</span></a>
					<a class="icon-share tip_trigger"><?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['share']; ?>
%  <span class="tip">Share</span></a>
				</div>
			</div>
			<?php endfor; endif; ?>
		</div><!-- .content-->
	</div><!-- .thePeoples-->
<?php if ($this->_tpl_vars['old']): ?>
                    <div class="opinion">
                        <h1>Key Opinion Leader</h1>
                        <div class="content" style="height:353px">
                        	
                        	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['opinion']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                <a href="#?w=650&id=<?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['name']; ?>
" class="poplight" rel="profile"><img src="<?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['image']; ?>
" /></a>
                                </div>
                                <div class="entry">
                                    <h3><?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['name']; ?>
</h3>
                                    <a class="icon-rts tip_trigger"><?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['rt']; ?>
  <span class="tip">Retweet Frequency</span></a>
                                    <a class="icon-imp tip_trigger"><?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['followers']; ?>
  <span class="tip">Total Impressions</span></a>
                                    <a class="icon-share tip_trigger"><?php echo $this->_tpl_vars['opinion'][$this->_sections['i']['index']]['share']; ?>
%  <span class="tip">Share</span></a>
                                                                    </div>
                            </div>
                        	<?php endfor; endif; ?>
                        	
                        </div><!-- .content-->
                    </div><!-- .opinion-->
<?php endif; ?>

<?php echo '
<script>
	$(function() {
		var $elem = $(\'body\');
		$(\'a.poplight\').click(
			function (e) {
				$(\'html, body\').animate({scrollTop: \'0px\'}, 800);
			}
		);
	});
</script>
'; ?>