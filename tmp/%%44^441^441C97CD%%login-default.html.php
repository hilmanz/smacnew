<?php /* Smarty version 2.6.13, created on 2012-11-22 16:19:04
         compiled from smac/login-default.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'encrypt', 'smac/login-default.html', 27, false),)), $this); ?>

    	<div id="signIn">
        	<a href="index.php" class="logobig">&nbsp;</a>
        	<div class="login-content login-default">
                <form class="loginform" id="login" method="post" enctype="application/x-www-form-urlencoded">
                	<div class="row">
                    <label>Account Domain</label>
                    <input type="text" name="subdomain" class="account-domain" id="subdomain" value="" />
                    <span class="labeldomain">.smacapp.com</span>
                    </div>
                    <div class="row">
                    <label>Username</label>
                    <input type="text" name="username" value="" />
                    </div>
                    <div class="row">
                    <label>Password</label>
                    <input type="password" name="password" value="" />
                    </div>
                    <div class="row">
                    <input name="login" id="login" type="hidden" value="1" />
					
                    <input type="submit" value="&nbsp;" />
                    </div>
                </form>
            </div><!-- .login-content -->
            <div class="forgot">
            	<p>Forgot your password? <a href="<?php echo smarty_function_encrypt(array('url' => '?page=login&act=reset_password'), $this);?>
">Get a new one here</a></p> 
            	<p><a href="index.php?req=iFa-615kpUoth9Dg1ps_84JrF9ncCpN_">Register</a></p>
            </div>
        </div><!-- #login -->