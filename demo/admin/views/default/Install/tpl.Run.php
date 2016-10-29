<?php
/**
 * @name 首页模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<!--{template 'Header','Common'}-->

<div class="subnav">
    <div class="content-menu ib-a blue line-x">
       <a class="on" href="javascript:;"><em>第一步：填写数据库信息</em></a>    </div>
</div>

<div class="pad_10">
	<div class="common-form">
		<form  action="" method="post" >
			<table width="100%" class="table_form contentWrap">
				<tbody>
					<tr>
					<td>数据库地址</td> 
					<td><input type="text" name="dbhost" value="" class="input-text"> 如：localhost:3306</td>
					</tr>
					
					<tr>
					<td>数据库名称</td>
					<td><input type="text" name="dbname" value="" class="input-text"></td>
					</tr>
					
					<tr>
					<td>数据库用户名</td>
					<td><input type="text" name="dbuser" value="" class="input-text"></td>
					</tr>
					
					<tr>
					<td>数据库密码</td>
					<td><input type="password" name="dbpassword" value="" class="input-text"></td>
					</tr>
					
				</tbody>
			</table>
		    <div class="bk15"></div>
		    <input name="doSubmit" type="submit" value="提交" class="button">
		</form>
	</div>
</div>

<!--{template 'Footer','Common'}-->

