<?php /* Smarty version 2.6.13, created on 2013-01-03 15:20:22
         compiled from smac/login.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'urlencode', 'smac/login.html', 12, false),array('function', 'encrypt', 'smac/login.html', 17, false),)), $this); ?>


    	<div id="signIn">
        	<a href="index.php" class="logobig">&nbsp;</a>
        	<div class="login-content">
                <form class="loginform" id="login" method="post" enctype="application/x-www-form-urlencoded">
                    <label>Username</label>
                    <input type="text" name="username" value="" />
                    <label>Password</label>
                    <input type="password" name="password" value="" />
                    <input name="login" id="login" type="hidden" value="1" />
					<input name="subdomain" id="subdomain" type="hidden" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['subdomain'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
" />
                    <input type="submit" value="&nbsp;" />
                </form>
            </div><!-- .login-content -->
            <div class="forgot">
            	<p>Forgot your password? <a href="<?php echo smarty_function_encrypt(array('url' => '?page=login&act=reset_password'), $this);?>
">Get a new one here</a></p> 
            	<p><a href="index.php?req=iFa-615kpUoth9Dg1ps_84JrF9ncCpN_">Register</a></p>
            </div>
        </div><!-- #login -->