<?php
/**
 * @name 模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<!--{template 'Header','Common'}-->
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
       <a class="on" href="javascript:;"><em>密码修改</em></a>    
    </div>
</div>

<div class="pad_10">
 <div class="common-form">
	<form name="myform" action="" method="post" >
	<input type="hidden" name="info[userid]" value=""></input>

	<table width="100%" class="table_form contentWrap">
	<tr>
	<td width="80">帐号</td> 
	<td><!--{$user['username']}--></td>
	</tr>
	
	<tr>
	<td>旧密码</td>
	<td>
	<input type="password" name="info[password]" id="realname" class="input-text" size="30" value=""></input>
	</td>
	</tr>
	
	
	<tr>
	<td>新密码</td>
	<td>
	<input type="password" name="info[new_password]" id="realname" class="input-text" size="30" value=""></input>
	</td>
	</tr>
	
	<tr>
	<td>确认新密码</td>
	<td>
	<input type="password" name="info[renew_password]" id="realname" class="input-text" size="30" value=""></input>
	</td>
	</tr>
	
	</table>
	
	    <div class="bk15"></div>
	    <input name="dosubmit" type="submit" value="提交" class="button" id="dosubmit">
	</form>
 </div>
</div>

<!--{template 'Footer','Common'}-->