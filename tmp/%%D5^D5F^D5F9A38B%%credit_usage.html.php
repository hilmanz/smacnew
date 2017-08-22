<?php /* Smarty version 2.6.13, created on 2012-10-31 10:04:21
         compiled from smac/credit_usage.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'smac/credit_usage.html', 57, false),array('modifier', 'htmlentities', 'smac/credit_usage.html', 57, false),array('modifier', 'number_format', 'smac/credit_usage.html', 66, false),)), $this); ?>
<div id="main-container">
    	<?php echo $this->_tpl_vars['sidebar']; ?>

        <div id="container">   	
            <div class="title-bar padR15">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            	<tr>
                	<td align="left">
            			<h1><a href="#">My Account</a></h1>
                    </td>
                </tr>
            </table>
            </div>
            <div id="campaign" class="pad1015">
            	<div class="headTable">
                    <h2>Your Acccount History</h2>
                    <p><?php echo $this->_tpl_vars['name']; ?>
, this is your account history of transactions within your SMAC Account.</p>
                    <div class="detailAccount">
                    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>Name</td>
                            <td>Username</td>
                          </tr>
                          <tr>
                            <td>Status</td>
                            <td>Active</td>
                          </tr>
                        </table>
                    </div>
                </div>
              <div style="display:block;width:100%;float:left;overflow: hidden">
               	<h4>Your Active Subscriptions : </h4>
                    <div class="CSSTable">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:0 0 40px 0;">   
                    <tr>
                        <td valign="top" width="180" style="text-align:left;">
                            <h4 class="margin0">Service</h4>
                        </td>
                        <td valign="top">
                             <h4 class="margin0">Start</h4>      	
                        </td>
                        <td valign="top">
                             <h4 class="margin0">End</h4>      	
                        </td>
                          <td valign="top">
                             <h4 class="margin0">Days Left</h4>      	
                        </td>
                         <td valign="top">
                             <h4 class="margin0">Price</h4>      	
                        </td>
                         <td valign="top">&nbsp;
                            
                        </td>
                    </tr>
                    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['orders']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                    <tr <?php if ($this->_sections['i']['iteration'] % 2 == 1): ?> class="ganjil"<?php else: ?>  class="genap"<?php endif; ?>>
                        <td valign="top" width="180" style="text-align:left;">
                            <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['orders'][$this->_sections['i']['index']]['campaign_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('htmlentities', true, $_tmp) : htmlentities($_tmp)); ?>

                        </td>
                        <td valign="top">
                            <?php echo $this->_tpl_vars['orders'][$this->_sections['i']['index']]['campaign_start']; ?>

                        </td>
                        <td valign="top">
                             <?php echo $this->_tpl_vars['orders'][$this->_sections['i']['index']]['expired']['expired_date']; ?>

                        </td>
                         <td valign="top">
                             <?php echo ((is_array($_tmp=$this->_tpl_vars['orders'][$this->_sections['i']['index']]['days_left'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
	
                        </td>
                         <td valign="top">
                             <?php if ($this->_tpl_vars['orders'][$this->_sections['i']['index']]['last_order']['total_price']):  echo ((is_array($_tmp=$this->_tpl_vars['orders'][$this->_sections['i']['index']]['last_order']['total_price'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp));  else:  echo $this->_tpl_vars['default_price'];  endif; ?>
                        </td>
                         <td valign="top">
                             <span>
                                
                                    <?php if ($this->_tpl_vars['orders'][$this->_sections['i']['index']]['last_order']['n_status'] == 0): ?>
                                    <a href="<?php echo $this->_tpl_vars['orders'][$this->_sections['i']['index']]['checkout_url']; ?>
" class="button">Pay Now</a>
                                    <?php elseif ($this->_tpl_vars['orders'][$this->_sections['i']['index']]['last_order']['n_status'] == 2): ?>
                                            <span class="canceledBtn">Canceled</span>
                                    <?php else: ?>
                                        <?php if ($this->_tpl_vars['orders'][$this->_sections['i']['index']]['days_left'] == 0): ?>
                                            <a href="<?php echo $this->_tpl_vars['orders'][$this->_sections['i']['index']]['extend_url']; ?>
" class="button">Extend</a>
                                        <?php else: ?>
                                            <span class="activebtn">Active</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                
                             </span>
                             <?php if ($this->_tpl_vars['orders'][$this->_sections['i']['index']]['last_order']['n_status'] == 0): ?>
                             <span><a href="<?php echo $this->_tpl_vars['orders'][$this->_sections['i']['index']]['cancel_url']; ?>
" class="button">Cancel Invoice</a></span>
                             <?php endif; ?>
                        </td>
                    </tr>
                    <?php endfor; endif; ?>
                    </table>
                    </div>
            	</div>
					<div>
						<?php echo $this->_tpl_vars['paging']; ?>

					</div>
               </div>
				
           	</div>
        </div><!-- #container -->
    </div><!-- #main-container -->